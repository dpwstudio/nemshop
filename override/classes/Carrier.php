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
class Carrier extends CarrierCore
{
    /**
     * Return the carrier name from the shop name (e.g. if the carrier name is '0').
     *
     * The returned carrier name is the shop name without '#' and ';' because this is not the same validation.
     *
     * @return string Carrier name
     */
    /*
    * module: postaldeliv
    * date: 2017-02-27 02:32:31
    * version: 2.1.2
    */
    public static function getCarrierNameFromShopName()
    {
        return str_replace(
            array('#', ';'),
            '',
            Configuration::get('PS_SHOP_NAME')
        );
    }
    /**
     * Check if postcode starts with code
     *
     * Created for Postal Deliv
     */
    /*
    * module: postaldeliv
    * date: 2017-02-27 02:32:31
    * version: 2.1.2
    */
    private static function __startsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }
        return false;
    }
    /**
     * Get all carriers in a given language
     *
     * @param integer $id_lang Language id
     * @param $modules_filters, possible values:
    PS_CARRIERS_ONLY
    CARRIERS_MODULE
    CARRIERS_MODULE_NEED_RANGE
    PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE
    ALL_CARRIERS
     * @param boolean $active Returns only active carriers when true
     * @return array Carriers
     * Created for Postal Deliv
     */
    /*
    * module: postaldeliv
    * date: 2017-02-27 02:32:31
    * version: 2.1.2
    */
    public static function getCarriers2(
        $id_lang,
        $active = false,
        $delete = false,
        $id_zone = false,
        $ids_group = null,
        $modules_filters = self::PS_CARRIERS_ONLY,
        $postcode = false,
        $id_country = false
    ) {
        if ($ids_group && (!is_array($ids_group) || !count($ids_group))) {
            return array();
        }
        $sql = '
		SELECT c.*, cl.delay
		FROM `'._DB_PREFIX_.'carrier` c
		LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cl ON (c.`id_carrier` = cl.`id_carrier`
		    AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
		LEFT JOIN `'._DB_PREFIX_.'carrier_zone` cz ON (cz.`id_carrier` = c.`id_carrier`)'.
            ($id_zone ? 'LEFT JOIN `'._DB_PREFIX_.'zone` z ON (z.`id_zone` = '.(int)$id_zone.')' : '').'
		'.Shop::addSqlAssociation('carrier', 'c').'
		WHERE c.`deleted` = '.($delete ? '1' : '0');
        if ($active) {
            $sql .= ' AND c.`active` = 1 ';
        }
        if ($id_zone) {
            $sql .= ' AND cz.`id_zone` = '.(int)$id_zone.' AND z.`active` = 1 ';
        }
        if ($ids_group) {
            $sql .= ' AND EXISTS (SELECT 1 FROM '._DB_PREFIX_.'carrier_group
									WHERE '._DB_PREFIX_.'carrier_group.id_carrier = c.id_carrier
									AND id_group IN ('.implode(',', array_map('intval', $ids_group)).')) ';
        }
        switch ($modules_filters) {
            case 1:
                $sql .= ' AND c.is_module = 0 ';
                break;
            case 2:
                $sql .= ' AND c.is_module = 1 ';
                break;
            case 3:
                $sql .= ' AND c.is_module = 1 AND c.need_range = 1 ';
                break;
            case 4:
                $sql .= ' AND (c.is_module = 0 OR c.need_range = 1) ';
                break;
        }
        $sql .= ' GROUP BY c.`id_carrier` ORDER BY c.`position` ASC';
        $cache_id = 'Carrier::getCarriers_'.md5($sql);
        if (!Cache::isStored($cache_id)) {
            $carriers = Db::getInstance()->executeS($sql);
            Cache::store($cache_id, $carriers);
        } else {
            $carriers = Cache::retrieve($cache_id);
        }
        
        foreach ($carriers as $key => $carrier) {
            if (Module::isEnabled('postaldeliv') && $postcode != false && $id_country != false) {
                $rules = Db::getInstance()->executeS('
                    SELECT * FROM `'._DB_PREFIX_.'postaldeliv` a
                    LEFT JOIN `'._DB_PREFIX_.'postaldeliv_shop` b ON (b.`id_postaldeliv` = a.`id_postaldeliv`)
                    WHERE a.`id_carrier`='.(int)$carrier['id_carrier'].'
                    AND b.`id_shop`='.(int)Context::getContext()->shop->id);
                if ($rules) {
                    foreach ($rules as $rule) {
                        $countries = explode(',', $rule['country']);
                        if (in_array(0, $countries) || in_array($id_country, $countries)) {
                            $in_postcode = in_array($postcode, explode(',', $rule['postcode']));
                            $starts_with = Carrier::__startsWith($postcode, explode(',', $rule['county']));
                            $in_range = false;
                            if ($ranges = unserialize($rule['range'])) {
                                foreach ($ranges as $range) {
                                    if ($postcode >= $range[0] && $postcode <= $range[1]) {
                                        $in_range = true;
                                    }
                                }
                            }
                            if (($rule['available'] == '0' && ($in_postcode || $starts_with || $in_range))
                                || ($rule['available'] == '1' && !$in_postcode && !$starts_with && !$in_range)) {
                                unset($carriers[$key]['id_carrier']);
                            } elseif ($carrier['name'] == '0') {
                                $carriers[$key]['name'] = Carrier::getCarrierNameFromShopName();
                            }
                        }
                    }
                }
            }
        }
        
        return $carriers;
    }
    /**
     * Get available Carriers for Order
     *
     * @param int       $id_zone Zone ID
     * @param array     $groups  Group of the Customer
     * @param Cart|null $cart    Optional Cart object
     * @param array     &$error  Contains an error message if an error occurs
     *
     * @return array Carriers for the order
     * Modified for Postal Deliv
     */
    /*
    * module: postaldeliv
    * date: 2017-02-27 02:32:31
    * version: 2.1.2
    */
    public static function getCarriersForOrder($id_zone, $groups = null, $cart = null, &$error = array())
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;
        if (is_null($cart)) {
            $cart = $context->cart;
        }
        if (isset($context->currency)) {
            $id_currency = $context->currency->id;
        }
        
        $postcode = '';
        if (isset($context->cookie->postcode)) {
            $postcode = $context->cookie->postcode;
            $id_country = $context->cookie->id_country;
        } else {
            $id_address = $cart->id_address_delivery;
            $adress = new Address((int)$id_address);
            $postcode = $adress->postcode;
            $id_country = $adress->id_country;
        }
        if (is_array($groups) && !empty($groups)) {
            $result = Carrier::getCarriers2(
                $id_lang,
                true,
                false,
                (int)$id_zone,
                $groups,
                self::PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE,
                $postcode,
                $id_country
            );
        } else {
            $result = Carrier::getCarriers2(
                $id_lang,
                true,
                false,
                (int)$id_zone,
                array(Configuration::get('PS_UNIDENTIFIED_GROUP')),
                self::PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE,
                $postcode,
                $id_country
            );
        }
        $results_array = array();
        
        foreach ($result as $k => $row) {
            
            if (isset($row['id_carrier'])) {
                
                $carrier = new Carrier((int)$row['id_carrier']);
                $shipping_method = $carrier->getShippingMethod();
                if ($shipping_method != Carrier::SHIPPING_METHOD_FREE) {
                    if (($shipping_method == Carrier::SHIPPING_METHOD_WEIGHT
                        && $carrier->getMaxDeliveryPriceByWeight($id_zone) === false)) {
                        $error[$carrier->id] = Carrier::SHIPPING_WEIGHT_EXCEPTION;
                        unset($result[$k]);
                        continue;
                    }
                    if (($shipping_method == Carrier::SHIPPING_METHOD_PRICE
                        && $carrier->getMaxDeliveryPriceByPrice($id_zone) === false)) {
                        $error[$carrier->id] = Carrier::SHIPPING_PRICE_EXCEPTION;
                        unset($result[$k]);
                        continue;
                    }
                    if ($row['range_behavior']) {
                        if (!$id_zone) {
                            $id_zone = (int)Country::getIdZone(Country::getDefaultCountryId());
                        }
                        if ($shipping_method == Carrier::SHIPPING_METHOD_WEIGHT
                            && (!Carrier::checkDeliveryPriceByWeight(
                                $row['id_carrier'],
                                $cart->getTotalWeight(),
                                $id_zone
                            ))) {
                            $error[$carrier->id] = Carrier::SHIPPING_WEIGHT_EXCEPTION;
                            unset($result[$k]);
                            continue;
                        }
                        if ($shipping_method == Carrier::SHIPPING_METHOD_PRICE
                            && (!Carrier::checkDeliveryPriceByPrice(
                                $row['id_carrier'],
                                $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING),
                                $id_zone,
                                $id_currency
                            ))) {
                            $error[$carrier->id] = Carrier::SHIPPING_PRICE_EXCEPTION;
                            unset($result[$k]);
                            continue;
                        }
                    }
                }
                $row['name'] = ((string)$row['name'] != '0' ? $row['name'] : Carrier::getCarrierNameFromShopName());
                $row['price'] = (($shipping_method == Carrier::SHIPPING_METHOD_FREE) ? 0 :
                    $cart->getPackageShippingCost((int)$row['id_carrier'], true, null, null, $id_zone));
                $row['price_tax_exc'] = (($shipping_method == Carrier::SHIPPING_METHOD_FREE) ? 0 :
                    $cart->getPackageShippingCost((int)$row['id_carrier'], false, null, null, $id_zone));
                $row['img'] = file_exists(_PS_SHIP_IMG_DIR_.(int)$row['id_carrier'].'.jpg') ?
                    _THEME_SHIP_DIR_.(int)$row['id_carrier'].'.jpg' : '';
                if ($row['price'] === false) {
                    unset($result[$k]);
                    continue;
                }
                $results_array[] = $row;
                
            }
            
        }
        $prices = array();
        if (Configuration::get('PS_CARRIER_DEFAULT_SORT') == Carrier::SORT_BY_PRICE) {
            foreach ($results_array as $r) {
                $prices[] = $r['price'];
            }
            if (Configuration::get('PS_CARRIER_DEFAULT_ORDER') == Carrier::SORT_BY_ASC) {
                array_multisort($prices, SORT_ASC, SORT_NUMERIC, $results_array);
            } else {
                array_multisort($prices, SORT_DESC, SORT_NUMERIC, $results_array);
            }
        }
        return $results_array;
    }
}
