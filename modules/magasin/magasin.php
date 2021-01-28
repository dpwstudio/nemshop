<?php



if (!defined('_PS_VERSION_'))

	exit;



class magasin extends PaymentModule

{

	private $_html = '';

	private $_postErrors = array();



	public $magasinName;

	public $address;

	public $extra_mail_vars;



	public function __construct()

	{

		$this->name = 'magasin';

		$this->tab = 'payments_gateways';

		$this->version = '2.1';

		$this->author = 'Cyril CHALAMON';

		$this->controllers = array('payment', 'validation');



		$this->currencies = true;

		$this->currencies_mode = 'checkbox';



		$config = Configuration::getMultiple(array('magasin_NAME', 'magasin_ADDRESS'));

		if (isset($config['magasin_NAME']))

			$this->magasinName = $config['magasin_NAME'];

		if (isset($config['magasin_ADDRESS']))

			$this->address = $config['magasin_ADDRESS'];



		$this->bootstrap = true;

		parent::__construct();	



		$this->displayName = $this->l('Magasin');

		$this->description = $this->l('Ce module vous permet d\'accepter le paiement en magasin sur votre boutique Prestashop.');

		$this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir supprimer ces détails ?');



		if (!count(Currency::checkPaymentCurrencies($this->id)))

			$this->warning = $this->l('No currency has been set for this module.');

	

	}



	public function install()

	{

		if (!parent::install() || !$this->registerHook('payment') || !$this->registerHook('paymentReturn'))

			return false;



		//Création de l'état de commande 'paiement au magasin'

		$insert = array(

			'invoice' => 0,

			'send_email' => 0,

			'module_name' => $this->name,

			'color' => 'RoyalBlue',

			'unremovable' => 1,

			'hidden' => 0,

			'logable' => 1,

			'delivery' => 0,

			'shipped' => 0,

			'paid' => 0,

			'deleted' => 0);



		if(!Db::getInstance()->autoExecute(_DB_PREFIX_.'order_state', $insert, 'INSERT'))

		return false;

		$id_order_state = (int)Db::getInstance()->Insert_ID();

		$languages = Language::getLanguages(false);

		foreach ($languages as $language)

		Db::getInstance()->autoExecute(_DB_PREFIX_.'order_state_lang', array('id_order_state'=>$id_order_state, 'id_lang'=>$language['id_lang'], 'name'=>'Paiement au magasin', 'template'=>''), 'INSERT');

		

		Configuration::updateValue('PS_OS_mandat', $id_order_state);

		unset($id_order_state);

		return true;

	}



	public function uninstall()

	{

		if (!$this->uninstallDB() || !Configuration::deleteByName('magasin_NAME') || !Configuration::deleteByName('magasin_ADDRESS') || !parent::uninstall())

			return false;

		return true;

	}

	public function uninstallDb()

	{	

		$sql = "DELETE FROM `"._DB_PREFIX_."order_state` WHERE `module_name` = 'magasin'";

		if (!Db::getInstance()->execute($sql))

		die();

		$sql2 = "DELETE FROM `"._DB_PREFIX_."order_state_lang` WHERE `name` = 'Magasin'";

		if (!Db::getInstance()->execute($sql2))

		die();

		return true;

	}



	private function _postValidation()

	{

		if (Tools::isSubmit('btnSubmit'))

		{

			

		}

	}



	private function _postProcess()

	{

		$this->_html .= $this->displayConfirmation($this->l('Settings updated'));

	}



	private function _displaymagasin()

	{

		return $this->display(__FILE__, 'infos.tpl');

	}



	public function getContent()

	{

		$this->_html = '';



		if (Tools::isSubmit('btnSubmit'))

		{

			$this->_postValidation();

			if (!count($this->_postErrors))

				$this->_postProcess();

			else

				foreach ($this->_postErrors as $err)

					$this->_html .= $this->displayError($err);

		}



		$this->_html .= $this->_displaymagasin();

		$this->_html .= $this->renderForm();



		return $this->_html;

	}



	public function hookPayment($params)

	{

		if (!$this->active)

			return;

		if (!$this->checkCurrency($params['cart']))

			return;



		//Affiche le mode de paiement si le nom du transporteur est "retrait au magasin" avec les termes exacts

		$sql = 'SELECT `id_carrier` FROM  `'._DB_PREFIX_.'carrier` WHERE `deleted`=0 AND `active`=1 AND `name`= "Retrait au magasin"';

		if ($results = Db::getInstance()->ExecuteS($sql))

		foreach ($results as $row)

        $carrier_magasin =  $row['id_carrier'];



		$this->smarty->assign(array(

			'carrier_magasin' => $carrier_magasin,

			'this_path' => $this->_path,

			'this_path_magasin' => $this->_path,

			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'

		));

		return $this->display(__FILE__, 'payment.tpl');

	}



	public function hookPaymentReturn($params)

	{

		if (!$this->active)

			return;



		$state = $params['objOrder']->getCurrentState();

		if ($state == Configuration::get('PS_OS_magasin') || $state == Configuration::get('PS_OS_OUTOFSTOCK'))

		{

			$this->smarty->assign(array(

				'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),

				'status' => 'ok',

				'id_order' => $params['objOrder']->id

			));

			if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))

				$this->smarty->assign('reference', $params['objOrder']->reference);

		}

		else

			$this->smarty->assign('status', 'failed');

		return $this->display(__FILE__, 'payment_return.tpl');

	}



	public function checkCurrency($cart)

	{

		$currency_order = new Currency((int)($cart->id_currency));

		$currencies_module = $this->getCurrency((int)$cart->id_currency);



		if (is_array($currencies_module))

			foreach ($currencies_module as $currency_module)

				if ($currency_order->id == $currency_module['id_currency'])

					return true;

		return false;

	}

	

	public function renderForm()

	{

		$fields_form = array(

			'form' => array(

				'submit' => array(

					'title' => $this->l('J\'ai compris'),

				)

			),

		);

		

		$helper = new HelperForm();

		$helper->show_toolbar = false;

		$helper->table =  $this->table;

		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

		$helper->default_form_language = $lang->id;

		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$this->fields_form = array();

		$helper->id = (int)Tools::getValue('id_carrier');

		$helper->identifier = $this->identifier;

		$helper->submit_action = 'btnSubmit';

		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(

			'fields_value' => $this->getConfigFieldsValues(),

			'languages' => $this->context->controller->getLanguages(),

			'id_language' => $this->context->language->id

		);



		return $helper->generateForm(array($fields_form));

	}

	

	public function getConfigFieldsValues()

	{



	}

}

