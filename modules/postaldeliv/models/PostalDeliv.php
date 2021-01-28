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

class PostalDelivModel extends ObjectModel
{
    public $id;
    public $id_postaldeliv;
    public $id_carrier;
    public $country;
    public $postcode;
    public $county;
    public $range;
    public $available;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'postaldeliv',
        'primary' => 'id_postaldeliv',
        'fields' => array(
            'id_carrier' =>         array('type' => self::TYPE_INT),
            'country' =>            array('type' => self::TYPE_STRING, 'size' => 510),
            'postcode' =>           array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 510),
            'county' =>             array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 510),
            'range' =>              array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 510),
            'available' =>          array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    public function delete()
    {
        parent::delete();

        Db::getInstance()->delete('postaldeliv_shop', 'id_postaldeliv = '.(int)$this->id);

        return true;
    }

    public function getShops()
    {
        $id_shops = Db::getInstance()->executeS('SELECT `id_shop` FROM `'._DB_PREFIX_.'postaldeliv_shop`
        WHERE `id_postaldeliv` = '.(int)$this->id);

        $results = array();
        foreach ($id_shops as $id_shop) {
            $results[] = $id_shop['id_shop'];
        }

        return $results;
    }

    public function saveShops($shop)
    {
        Db::getInstance()->delete('postaldeliv_shop', 'id_postaldeliv = '.(int)$this->id);
        $id_shops = array();
        if (Shop::isFeatureActive()) {
            if (empty($shop) || $shop == 0 || $shop == array(0) || in_array(0, $shop)) {
                $shop = Shop::getCompleteListOfShopsID();
            }

            foreach ($shop as $id_shop) {
                $id_shops[] = array('id_postaldeliv' => (int)$this->id, 'id_shop' => (int)$id_shop);
            }
        } else {
            $id_shops[] = array('id_postaldeliv' => (int)$this->id, 'id_shop' => (int)$shop);
        }

        Db::getInstance()->insert('postaldeliv_shop', $id_shops);

        return true;
    }
}
