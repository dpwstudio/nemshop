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
 * Function used to update your module from previous versions to the version 2.1.1,
 * Don't forget to create one file per version.
 */
function upgrade_module_2_1_1($object)
{
    // Update postaldeliv table and create postaldeliv_shop
    if (!Db::getInstance()->execute('
        ALTER TABLE `'._DB_PREFIX_.'postaldeliv`
        DROP `id_shop`;

        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'postaldeliv_shop` (
          `id_postaldeliv` int(11) unsigned NOT NULL,
          `id_shop` int(11) unsigned NOT NULL
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ')) {
        return false;
    }

    // Update id_shop for each id_postaldeliv
    $shop = Shop::getContextListShopID();
    $id_postaldelivs = Db::getInstance()->executeS('SELECT `id_postaldeliv` FROM `'._DB_PREFIX_.'postaldeliv`');
    $id_shops = array();
    foreach ($id_postaldelivs as $id) {
        foreach ($shop as $id_shop) {
            $id_shops[] = array('id_postaldeliv' => (int)$id['id_postaldeliv'], 'id_shop' => (int)$id_shop);
        }
    }

    if (!Db::getInstance()->insert('postaldeliv_shop', $id_shops)) {
        return false;
    }

    if (!$object->uninstallOverrides()
        || !$object->installOverrides()) {
        return false;
    }
    return true;
}
