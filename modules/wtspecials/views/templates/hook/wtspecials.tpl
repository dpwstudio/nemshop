{*
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{$number_line = 1}
<div id="wt-special-products" class="wt-special-products">
	<div class="block-title">
		<h3>{l s='Hot Deals' mod='wtspecials'}</h3>
	</div>
	<div class="container">
		<div class="block-content row">	
			{if isset($new_products) && $new_products}
				<div class="new-prod-content col-sm-12">
					<div class="special-prod-slider {if $wtconfig->used_slider == 0} cs-no-slider{/if}">
						{foreach from=$new_products item=product name=products}
							{if isset($wtconfig) && (($smarty.foreach.products.iteration % $number_line == 1 && $wtconfig->used_slider == 1) || $wtconfig->used_slider == 0 || $number_line == 1)}
								<div class="item ajax_block_product{if $smarty.foreach.products.first} first{/if}">
							{/if}	
								<div class="product-container">
								 <div class="out-product-image-container">
									<div class="product-image-container">
										<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="" itemprop="url">
											<img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title=""  itemprop="image" />
										</a>
										<div class="wt-label">
										{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
										  <a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
											{if $product.specific_prices.reduction_type == 'percentage'}
												<span class="price-percent-reduction">-{$product.specific_prices.reduction|escape:'html':'UTF-8' * 100}%</span>
											{else}
												<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
												<span class="sale-label">{l s='Sale' mod='wtspecials'}</span>
												</a>
											{/if}	
											</a>
										{/if}
										</div>
										{hook h='displayCountDownProduct' product=$product prev_id='ps'}
									</div>
								</div>
								<div class="out-content">
									<h5 class="product-name">
										<a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}">{$product.name|escape:'html':'UTF-8'|truncate:18:''}</a>
									</h5>
									{hook h='displayProductListReviews' product=$product}
									<p class="product-desc" itemprop="description">
										{$product.description_short|strip_tags|escape:'html':'UTF-8'|truncate:120:'...'}
									</p>
									
									{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
									<div class="content_price">
										{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
											<span class="price product-price">
												{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
											</span>
											{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
												{hook h="displayProductPriceBlock" product=$product type="old_price"}
												<span class="old-price product-price">
													{displayWtPrice p=$product.price_without_reduction}
												</span>
												{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
											{/if}
											{hook h="displayProductPriceBlock" product=$product type="price"}
											{hook h="displayProductPriceBlock" product=$product type="unit_price"}
										{/if}
									</div>
									{/if}
								  </div>
								</div>
							{if isset($wtconfig) && ((($smarty.foreach.products.last || $smarty.foreach.products.iteration % $number_line == 0) && $wtconfig->used_slider == 1 ) || $wtconfig->used_slider == 0 || $number_line == 1)}
								</div>
							{/if}
							
						{/foreach}
					</div>
					{if isset($wtconfig->used_slider) && $wtconfig->used_slider == 1}
						<script type="text/javascript">
							$(document).ready(function() {
								$("#wt-special-products .special-prod-slider").owlCarousel({
									loop: true,
									slideSpeed: 100,
									responsive: {
										0: { items: 1},
										464:{ items: 2},
										750:{ items: 3, slideBy:2},
										974:{ items: 1},
										1170:{ items: 1}
									},
									dots: false,
									nav: true
								  });
							});
						</script>
					{/if}
				</div>
			{else}
				<p class="alert alert-warning">{l s='No product at this time' mod='wtspecials'}</p>
			{/if}
		</div>
	</div>
</div>