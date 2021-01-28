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

class AdminPostalDelivController extends ModuleAdminController
{
    public $module = 'postaldeliv';
    public $bootstrap = true;

    public function __construct()
    {
        $this->table = 'postaldeliv';
        $this->className = 'PostalDelivModel';

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash')
        );

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->identifier = 'id_postaldeliv';

        $this->context = Context::getContext();
    }

    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryPlugin('tagify');
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_rule'] = array(
                'href' => self::$currentIndex.'&addpostaldeliv&token='.$this->token,
                'desc' => $this->l('Add new rule', null, null, false),
                'icon' => 'process-icon-new'
            );
        }

        $this->page_header_toolbar_btn['support'] = array(
            'href' => 'https://addons.prestashop.com/contact-community.php?id_product=4891',
            'desc' => $this->l('Ask the developer for support', null, null, false),
            'icon' => 'process-icon-1 icon-info-circle',
            'target' => true
        );

        $this->page_header_toolbar_btn['rate'] = array(
            'href' => 'http://addons.prestashop.com/fr/ratings.php?id_product=4891',
            'desc' => $this->l('Comment and rate the module', null, null, false),
            'icon' => 'process-icon-1 icon-star',
            'target' => true
        );

        parent::initPageHeaderToolbar();
    }

    /**
     * Function used to render the list to display for this controller
     */
    public function renderList()
    {
        $this->fields_list = array(
            'id_postaldeliv' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 40,
                'search' => false,
            ),
            'carrier' => array(
                'title' => $this->l('Carrier'),
                'width' => 40,
                'search' => false,
            ),
            'available' => array(
                'title' => $this->l('Availability'),
                'width' => 40,
                'search' => false,
                'callback' => 'displayHumanReadableAvailability',
            ),
            'country' => array(
                'title' => $this->l('Country'),
                'width' => 40,
                'search' => false,
                'callback' => 'displayHumanReadableCountry',
            ),
            'postcode' => array(
                'title' => $this->l('Postcode'),
                'width' => 40,
                'search' => false,
            ),
            'county' => array(
                'title' => $this->l('County'),
                'width' => 40,
                'search' => false,
            ),
            'range' => array(
                'title' => $this->l('Range'),
                'width' => 40,
                'search' => false,
                'callback' => 'renderRange',
            ),
        );

        $this->_select = 'c.`name` as carrier';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'postaldeliv_shop` b ON (b.`id_postaldeliv` = a.`id_postaldeliv`)
                        LEFT JOIN `'._DB_PREFIX_.'carrier` c ON (c.`id_carrier` = a.`id_carrier`)';
        $this->_where = ' AND b.id_shop IN ('.implode(',', array_map('intval', Shop::getContextListShopID())).')';
        $this->_group = 'GROUP BY a.`id_postaldeliv`';

        return parent::renderList();
    }

    /**
     * Function used to render the form for this controller
     */
    public function renderForm()
    {
        if (Shop::isFeatureActive()) {
            $shop_array = array(
                    'type' => 'select',
                    'multiple' => true,
                    'label' => $this->l('Shops'),
                    'name' => 'shop[]',
                    'class' => 'chosen',
                    'options' => array(
                        'query' => Shop::getShops(),
                        'id' => 'id_shop',
                        'name' => 'name',
                        'default' => array(
                            'label' => $this->l('All shops'),
                            'value' => 0
                        )
                    )
                );
        } else {
            $shop_array = array(
                'type' => 'hidden',
                'name' => 'shop[]',
            );
        }
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Configure your delivery restriction'),
                'icon' => 'icon-globe'
            ),
            'input' => array(
                $shop_array,
                array(
                    'type' => 'select',
                    'multiple' => true,
                    'label' => $this->l('Countries'),
                    'name' => 'countries[]',
                    'class' => 'chosen',
                    'options' => array(
                        'query' => Country::getCountries((int)$this->context->language->id, true),
                        'id' => 'id_country',
                        'name' => 'name',
                        'default' => array(
                            'label' => $this->l('All countries'),
                            'value' => 0
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Carrier'),
                    'name' => 'id_carrier',
                    'options' => array(
                        'query' => Carrier::getCarriers(
                            (int)$this->context->language->id,
                            true,
                            false,
                            false,
                            null,
                            Carrier::ALL_CARRIERS
                        ),
                        'id' => 'id_carrier',
                        'name' => 'name',
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Availability'),
                    'name' => 'available',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'available_on',
                            'value' => 1,
                            'label' => $this->l('Available')
                        ),
                        array(
                            'id' => 'available_off',
                            'value' => 0,
                            'label' => $this->l('Unavailable')
                        ),
                    )
                ),
                array(
                    'type' => 'tags',
                    'label' => $this->l('For the following postcodes'),
                    'name' => 'postcode',
                    'hint' => array(
                        $this->l('Separate your postcodes with commas like so: 75001,75002,').'<br><br>'
                        .$this->l('You can use any format of postcodes as long as you separate them with commas'),
                    ),
                ),
                array(
                    'type' => 'range',
                    'label' => $this->l('Or postcodes between'),
                    'name' => 'range',
                    'class' => 'inline fixed-width-lg',
                    'hint' => $this->l('This function only works for numerical postcodes'),
                ),
                array(
                    'type' => 'tags',
                    'label' => $this->l('Or the postcodes starting with'),
                    'name' => 'county',
                    'hint' => $this->l('Separate your codes with commas like so: 75,76,'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save')
            )
        );

        if (Tools::getValue('id_postaldeliv')) {
            $object = $this->loadObject(true);
            $this->fields_value['shop[]'] = $object->getShops();
            $this->fields_value['countries[]'] = explode(',', $object->country);
            $this->fields_value['range'] = unserialize($object->range);
        } else {
            $this->fields_value['shop[]'] = Shop::getContextListShopID();
        }

        if (!Shop::isFeatureActive()) {
            $this->fields_value['shop[]'] = $this->context->shop->id;
        }

        return parent::renderForm();
    }

    protected function afterAdd($object)
    {
        $country = Tools::getValue('countries');

        // handles countries associations
        if (empty($country) || $country == 0 || in_array(0, $country)) {
            $country = array(0);
        }
        $country = implode(',', $country);

        $object->country = $country;

        // handles ranges
        $range_count = Tools::getValue('range_count');
        $range = array();
        for ($i=0; $i<=$range_count; $i++) {
            if (Tools::getIsset('range_from_'.$i) && Tools::getIsset('range_to_'.$i)) {
                $range_from = Tools::getValue('range_from_'.$i);
                $range_to = Tools::getValue('range_to_'.$i);
                if (!Tools::isEmpty($range_from) && !Tools::isEmpty($range_to)) {
                    $range[] = array(Tools::getValue('range_from_'.$i), Tools::getValue('range_to_'.$i));
                }
            }
        }
        $object->range = serialize($range);

        // Save country and range
        $object->save();

        // handles shops associations
        $shop = Tools::getValue('shop');
        $object->saveShops($shop);

        return true;
    }

    protected function afterUpdate($object)
    {
        $country = Tools::getValue('countries');

        // handles countries associations
        if (empty($country) || $country == 0 || in_array(0, $country)) {
            $country = array(0);
        }
        $country = implode(',', $country);
        $object->country = $country;

        $range_count = Tools::getValue('range_count');
        $range = array();
        for ($i=0; $i<=$range_count; $i++) {
            if (Tools::getIsset('range_from_'.$i) && Tools::getIsset('range_to_'.$i)) {
                $range_from = Tools::getValue('range_from_'.$i);
                $range_to = Tools::getValue('range_to_'.$i);
                if (!Tools::isEmpty($range_from) && !Tools::isEmpty($range_to)) {
                    $range[] = array(Tools::getValue('range_from_'.$i), Tools::getValue('range_to_'.$i));
                }
            }
        }
        $object->range = serialize($range);

        $object->save();

        if (Shop::isFeatureActive()) {
            // handles shops associations
            $shop = Tools::getValue('shop');
            $object->saveShops($shop);
        }

        return true;
    }

    public static function displayHumanReadableCountry($countries)
    {
        $class = new AdminPostalDelivController();

        if (empty($countries) || is_null($countries)) {
            return $class->l('All countries');
        }

        $countries = explode(',', $countries);
        $names = array();

        foreach ($countries as $id_country) {
            $country = new Country((int)$id_country);
            $names[] = $country->name[Context::getContext()->language->id];
        }

        return implode(',', $names);
    }

    public static function displayHumanReadableAvailability($available)
    {
        $class = new AdminPostalDelivController();

        if (empty($available) || is_null($available)) {
            return $class->l('unavailable for');
        } else {
            return $class->l('available for');
        }
    }

    public static function renderRange($echo)
    {
        if (Tools::isEmpty($echo)) {
            return '--';
        }

        $range_array = unserialize($echo);

        $range_string = '';
        foreach ($range_array as $range) {
            $range_string .= $range[0].'->'.$range[1].'; ';
        }

        return $range_string;
    }
}
