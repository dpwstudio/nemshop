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

{$id_lang = Context::getContext()->language->id}
{foreach from = $product_groups item = product_group name = product_group}
	<div class="{$product_group.class|escape:'html':'UTF-8'}">
		<div class="block-home-title">
			<div class="wt-out-title">
			   <h3>{$product_group.title|escape:'html':'UTF-8'}</h3>
			</div>	
		</div>
		<div class="owlslider-prod-filter product_list row">
			<div class="group-1 col-sm-6">
			<div class="group-out-item clearfix">
			{foreach from=$product_group.product_list item=product name=product_list}
				{if $smarty.foreach.product_list.iteration <= 4}
				<div class="item ajax_block_product col-sm-6">
					<div class="product-container">
						<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium_default')|escape:'html':'UTF-8'}" alt="{$product.legend|escape:html:'UTF-8'}" /></a>
					</div>
				</div>
				{/if}
				{/foreach}
			</div>
			</div>
			<div class="group-2 col-sm-6">
				{foreach from=$product_group.product_list item=product name=product_list}
				{if $smarty.foreach.product_list.iteration > 4 && $smarty.foreach.product_list.iteration <=7}
				<div class="item ajax_block_product{if $smarty.foreach.product_list.last} last{/if}">
					<div class="product-container clearfix" itemscope itemtype="https://schema.org/Product">
						<div class="left-block">
							<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html':'UTF-8'}" alt="{$product.legend|escape:html:'UTF-8'}" /></a>
						</div>
						<div class="right-block">
							<h5 class="product-name"><a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}">{$product.name|escape:'html':'UTF-8'}</a></h5>
							<p class="product-desc">{$product.description_short|strip_tags:'UTF-8'|truncate:120:'...'|escape:'html':'UTF-8'}</p>
							{if (!$PS_CATALOG_MODE && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
								<div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
									{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
										<span itemprop="price" class="price product-price">
											{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
										</span>
										<meta itemprop="priceCurrency" content="{$currency->iso_code|escape:'html':'UTF-8'}" />
										{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
											{hook h="displayProductPriceBlock" product=$product type="old_price"}
											<span class="old-price product-price">
												{displayWtPrice p=$product.price_without_reduction}
											</span>
										{/if}
										{hook h="displayProductPriceBlock" product=$product type="price"}
										{hook h="displayProductPriceBlock" product=$product type="unit_price"}
									{/if}
								</div>
							{/if}
						</div>	
					</div>
				</div>
				{/if}
			{/foreach}
			</div>
		</div>
	</div>
{/foreach}