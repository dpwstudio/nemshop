<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class WtPagetitle extends Module
{
	private $temp_url = '{wtpagetitle_url}';
	private $html;
	private $settings_default;
	private $wt_manu_config;
	private $config;
	public function __construct()
	{
		$this->name = 'wtpagetitle';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'waterthemes';
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('WT Page Title');
		$this->description = $this->l('Get page title');
	}
	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayPageTitle'))
			return false;
		return true;
	}
	public function hookdisplayPageTitle()
	{
		$page_title = '';
		$id_lang = (int)$this->context->language->id;
		$page = $this->context->smarty->tpl_vars['page_name']->value;
		if (Tools::getIsset('id_category') && $page == 'category')
		{
			$id_cat = Tools::getValue('id_category');
			$category = new Category($id_cat);
			$page_title = $category->name[$id_lang];
		}
		else if (Tools::getIsset('id_product') && $page == 'product')
		{
			$id_prod = Tools::getValue('id_product');
			$product = new Product($id_prod);
			$page_title = $product->name[$id_lang];
		}
		else if ($page == 'authentication')
			$page_title = $this->l('Authentication');
		else if ($page == 'my-account')
			$page_title = $this->l('Mon compte');
		else if ($page == 'order')
			$page_title = $this->l('Votre panier');
		else if ($page == 'order-opc')
			$page_title = $this->l('Votre panier');
		else if ($page == 'address')
			$page_title = $this->l('Vos adresses');
		else if ($page == 'addresses')
			$page_title = $this->l('Vos adresses');
		else if ($page == 'module-cheque-payment')
			$page_title = $this->l('Récapitulatif de la commande');
		else if ($page == 'order-confirmation')
			$page_title = $this->l('Confirmation de la commande');
		else if ($page == 'history')
			$page_title = $this->l('Historique de vos commandes');
		else if ($page == 'order-slip')
			$page_title = $this->l('Mes avoirs');
		else if ($page == 'identity')
			$page_title = $this->l('Vos informations personnelles');
		else if ($page == 'module-blockwishlist-mywishlist')
			$page_title = $this->l('Wishlist');
		else if ($page == 'contact')
			$page_title = $this->l('Service client - Contactez-nous');
		else if ($page == 'prices-drop')
			$page_title = $this->l('Price drop');
		else if ($page == 'new-products')
			$page_title = $this->l('Nouveaux produits');
		else if ($page == 'best-sales')
			$page_title = $this->l('Meilleures ventes');
		else if ($page == 'stores')
			$page_title = $this->l('Nos magasins');
		else if ($page == 'cms')
			$page_title = $this->l('A propos de nous');
		else if ($page == 'sitemap')
			$page_title = $this->l('Sitemap');
		else if ($page == 'module-bankwire-payment')
			$page_title = $this->l('Bank-Wire Payment');
		else if ($page == 'module-cheque-payment')
			$page_title = $this->l('Check payment');
			else if ($page == 'module-wtblog-category')
			$page_title = $this->l('Blog categories');
		else if ($page == 'module-wtblog-details')
			$page_title = $this->l('Blog detail');
		$this->context->smarty->assign(
			array('page_title' => $page_title)
		);
		return $this->display(__FILE__, 'wtpagetitle.tpl');
	}
}