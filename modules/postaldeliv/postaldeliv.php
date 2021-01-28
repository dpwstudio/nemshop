<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from EURL ébewè - www.ebewe.net
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the EURL ébewè is strictly forbidden.
 * In order to obtain a license, please contact us: contact@ebewe.net
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe EURL ébewè - www.ebewe.net
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la EURL ébewè est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter la EURL ébewè a l'adresse: contact@ebewe.net
 * ...........................................................................
 *
 * @package   Postaldeliv
 * @author    Paul MORA
 * @copyright Copyright (c) 2011-2016 EURL ébewè - www.ebewe.net - Paul MORA
 * @license   Commercial license
 * Support by mail  :  contact@ebewe.net
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Loading Models
 */
require_once(_PS_MODULE_DIR_.'postaldeliv/models/PostalDeliv.php');

class Postaldeliv extends Module
{
    public function __construct()
    {
        $this->name = 'postaldeliv';
        $this->tab = 'shipping_logistics';
        $this->version = '2.1.2';
        $this->author = 'ébewè - ebewe.net';
        $this->need_instance = 0;
        $this->module_key = 'ccc93a38bc63cb123994eed42f533a5c';
        parent::__construct();
        $this->displayName = $this->l('Postal Deliv');
        $this->description = $this->l('Delivery by postal code');
    }

    /**
    * Install Module
    *
    **/
    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('actionCarrierUpdate')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->installModuleTab(
                'AdminPostalDeliv',
                array((int)Configuration::get('PS_LANG_DEFAULT')=>'Postal Deliv'),
                'AdminParentShipping'
            )
            || !$this->__createtable()) {
            return false;
        }
        return true;
    }

    /**
    * Uninstall Module
    *
    **/
    public function uninstall()
    {
        if (!parent::uninstall()
            || !$this->uninstallModuleTab('AdminPostalDeliv')
            || !$this->__removetable()) {
            return false;
        }
        return true;
    }

    /**
    * install Tab
    *
    * @param mixed $tabClass
    * @param mixed $tabName
    * @param mixed $idTabParent
    * @return bool $pass
    */
    private function installModuleTab($tabClass, $tabName, $idTabParent)
    {
        $idTab = Tab::getIdFromClassName($idTabParent);
        $pass = true;
        $tab = new Tab();
        $tab->name = $tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTab;
        $pass = $tab->save();

        return $pass;
    }

    /**
    * uninstall Tab
    *
    * @param mixed $tabClass
    * @return bool $pass
    */
    private function uninstallModuleTab($tabClass)
    {
        $pass = true;
        @unlink(_PS_IMG_DIR_.'t/'.$tabClass.'.gif');
        $idTab = Tab::getIdFromClassName($tabClass);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $pass = $tab->delete();
        }
        return $pass;
    }

    /**
    * Create Table
    */
    private function __createtable()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'postaldeliv` (
                `id_postaldeliv` INT NOT NULL AUTO_INCREMENT,
                `id_carrier` INT(10) NOT NULL,
                `country` TEXT NOT NULL,
                `postcode` TEXT NOT NULL,
                `county` TEXT NOT NULL,
                `range` TEXT NOT NULL,
                `available` TINYINT(1) NOT NULL,
                PRIMARY KEY (`id_postaldeliv`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;

            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'postaldeliv_shop` (
              `id_postaldeliv` int(11) unsigned NOT NULL,
              `id_shop` int(11) unsigned NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ';

        if (!Db::getInstance()->Execute($sql)) {
            return false;
        }

        return true;
    }

    /**
    * Remove Table
    */
    private function __removetable()
    {
        $sql = '
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'postaldeliv`;
        ';
        if (!Db::getInstance()->Execute($sql)) {
            return false;
        }

        return true;
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (version_compare(_PS_VERSION_, '1.6.0', '>=') === true
            && Tools::getValue('controller') == 'AdminPostalDeliv') {
            $this->context->controller->addJquery();
            $this->context->controller->addJs($this->_path.'views/js/postaldeliv.js');
            $this->context->controller->addCss($this->_path.'views/css/postaldeliv.css');
        }
    }

    public function hookActionCarrierUpdate($params)
    {
        $result = Db::getInstance()->getValue('
            SELECT MAX(id_carrier)
            FROM `'._DB_PREFIX_.'carrier`');

        Db::getInstance()->Execute('
            UPDATE `'._DB_PREFIX_.'postaldeliv`
            SET `id_carrier`='.(int)$result.'
            WHERE `id_carrier`='.(int)$params['id_carrier']);
    }
}
