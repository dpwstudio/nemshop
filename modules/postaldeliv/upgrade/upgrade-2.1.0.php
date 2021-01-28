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
 * @package   PostalDeliv
 * @author    Paul MORA
 * @copyright Copyright (c) 2011-2016 EURL ébewè - www.ebewe.net - Paul MORA
 * @license   Commercial license
 * Support by mail  :  contact@ebewe.net
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 2.1.0,
 * Don't forget to create one file per version.
 */
function upgrade_module_2_1_0($object)
{
    // Update tab link
    $idTab = Tab::getIdFromClassName('PostDeliv');
    if ($idTab != 0) {
        $tab = new Tab($idTab);
        $tab->class_name = 'AdminPostalDeliv';
        $pass = $tab->save();
        if (!$pass) {
            return false;
        }
    }

    // Update postaldeliv table
    if (!Db::getInstance()->execute('
        ALTER TABLE `'._DB_PREFIX_.'postaldeliv`
        ADD `country` TEXT NOT NULL,
        ADD `range` TEXT NOT NULL
        ')) {
        return false;
    }

    // Set All countries for each carrier
    if (!Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'postaldeliv`
            SET `country` = 0
            WHERE 1')) {
        return false;
    }

    // Update ranges from postaldeliv_range to postaldeliv
    $ranges = Db::getInstance()->executeS('SELECT *
        FROM `'._DB_PREFIX_.'postaldeliv_range`
    ');
    $ranges_array = array();
    foreach ($ranges as $range) {
        $ranges_array[$range['id_carrier']][] = array($range['from'], $range['to']);
    }
    foreach ($ranges_array as $id_carrier => $range_array) {
        $range_array = serialize($range_array);
        if (!Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'postaldeliv`
            SET `range` = "'.pSQL($range_array).'"
            WHERE `id_carrier` = '.(int)$id_carrier)) {
            return false;
        }
    }

    // Delete postaldeliv_range table
    if (!Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'postaldeliv_range`')) {
        return false;
    }

    // Update Overrides
    if (!$object->uninstallOverrides()
        || !$object->installOverrides()) {
        return false;
    }

    return true;
}
