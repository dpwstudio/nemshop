{**
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{$number_line = 2}
{$id_lang = Context::getContext()->language->id}
{foreach from = $product_groups item = product_group name = product_group}
	<div class="{$product_group.class|escape:'html':'UTF-8'} product_list">
		<div class="block-home-title">
			<div class="wt-out-title"><h3>{$product_group.title|escape:'html':'UTF-8'}</h3></div>	
		</div>
	<div class="out-prod-filter">
			<div class="owl-prod-filter">
				{if isset($product_group.product_list) && count($product_group.product_list) > 0}
				{foreach from=$product_group.product_list item=product name=product_list}
					{if isset($product.id_product)}
					{if $smarty.foreach.product_list.iteration % $number_line == 1 || $number_line == 1}
					{$i = 0}
					<div class="item product-box ajax_block_product">
					{/if}
					{$i=$i+1}
					<div class="product-container item-{$i|intval}"  itemscope itemtype="https://schema.org/Product">
						<div class="product-container-img">
							<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}">
								<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, $prod_img)|escape:'html':'UTF-8'}" alt="{$product.legend|escape:html:'UTF-8'}" />
							</a>
							<div class="wt-label">
							{if isset($product.new) && $product.new == 1}
								<a class="new-box" href="{$product.link|escape:'html':'UTF-8'}">
									<span class="new-label">{l s='New' mod='wtproductfilter'}</span>
								</a>
							{/if}
							{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
							  <a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
								{if $product.specific_prices.reduction_type == 'percentage'}
									<span class="price-percent-reduction">-{$product.specific_prices.reduction|escape:'html':'UTF-8' * 100}%</span>
								{else}
									<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
									<span class="sale-label">{l s='Sale' mod='wtproductfilter'}</span>
									</a>
								{/if}	
								</a>
							{/if}
							</div>
							<div class="prod-hover">
								<div class="out-button">
								{if isset($quick_view) && $quick_view}
									<a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view' mod='wtproductfilter'}">
										<span>{l s='Quick view' mod='wtproductfilter'}</span>
									</a>
								{/if}
								{hook h='displayProductListFunctionalButtons' product=$product}
								{if isset($comparator_max_item) && $comparator_max_item}
									<div class="compare">
										<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product|intval}" title="{l s='Add to Compare' mod='wtproductfilter'}">
											{l s='Compare' mod='wtproductfilter'}
										</a>
									</div>
								{/if}
								</div>
								<div class="functional-buttons clearfix">
								{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
									{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
										{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($static_token)}&amp;token={$static_token|escape:'html':'UTF-8'}{/if}{/capture}
										<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='wtproductfilter'}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity > 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
											<span>{l s='Add to cart' mod='wtproductfilter'}</span>
										</a>
									{else}
										<span class="button ajax_add_to_cart_button btn btn-default disabled">
											<span>{l s='Out of stock' mod='wtproductfilter'}</span>
										</span>
									{/if}
								{/if}
								</div>	
							</div>
						</div>
						{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
							<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
								{if $product.specific_prices.reduction_type == 'percentage'}
									<span class="sale-label">
										-{$product.specific_prices.reduction|escape:'html':'UTF-8' * 100}%
									</span>
								{else}
									<span class="sale-label">
										-{convertPrice price=$product.reduction}
									</span>
								{/if}
							</a>
						{/if}
						<h5 class="product-name">
							<a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}">{$product.name|escape:'html':'UTF-8'}</a>
						</h5>
						{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
						<div class="content_price">
							{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
								{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
									<span class="price product-price special-price">
										{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
									</span>
									{hook h="displayProductPriceBlock" product=$product type="old_price"}
									<span class="old-price product-price">
										{displayWtPrice p=$product.price_without_reduction}
									</span>
									{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
								{else}
									<span class="price product-price">
									{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
									</span>
								{/if}
								{hook h="displayProductPriceBlock" product=$product type="price"}
								{hook h="displayProductPriceBlock" product=$product type="unit_price"}
							{/if}
						</div>
						{/if}
					</div>
					{if $smarty.foreach.product_list.iteration % $number_line == 0 ||$smarty.foreach.product_list.last|| $number_line == 1}
					</div>
					{/if}
					{/if}
				{/foreach}
				{else}
					<div class="item product-box ajax_block_product">
						<p class="alert alert-warning">{l s='No product at this time' mod='wtproductfilter'}</p>
					</div>	
				{/if}
			</div>
	  </div>
	</div>
{/foreach}