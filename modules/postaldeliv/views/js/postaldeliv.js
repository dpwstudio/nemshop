/*
 * 2007-2016 PrestaShop
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
 * @package   Postaldeliv
 * @author    Paul MORA
 * @copyright Copyright (c) 2011-2016 EURL ébewè - www.ebewe.net - Paul MORA
 * @license   Commercial license
 * Support by mail  :  contact@ebewe.net
 */

$(document).ready(function(){
    $('.adminpostaldeliv').on('click', 'i.icon-plus-sign', function(){
        data_range_plus = parseInt( $(this).attr('data-range') ) + 1;
        data_range = parseInt( $(this).attr('data-range') );

        $(this).before(
            '<div class="postal_range">' +
                '<input type="text" name="range_from_'+ data_range +'" class="inline fixed-width-lg">' +
                ' ' + data_and + ' ' +
                '<input type="text" name="range_to_'+ data_range +'" class="inline fixed-width-lg">' + ' ' +
                '<i data-range="'+ data_range +'" class="icon-minus-sign"></i>' +
            '</div>');
        $(this).attr('data-range', data_range_plus);
        $('#range_count').val(data_range_plus);
    }).on('click', 'i.icon-minus-sign', function(){
        conf = confirm( data_delete_confirm );
        if ( conf == true )
            $(this).parent().remove();
    });
});
