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
{$number_line = 1}
{$id_lang = Context::getContext()->language->id}
<div class="block-content type-tab">
<div class="block-home-title"><h3>{l s='Clothing' mod='wtproductcategory'}</h3></div>
<ul id="wt-prod-cat-tabs" class="nav nav-tabs clearfix">
	{foreach from=$group_cat_info item=cat_info name=g_cat_info}
		<li class="{if $smarty.foreach.g_cat_info.first} active{/if}"><a data-toggle="tab" href="#wt-prod-cat-{$smarty.foreach.g_cat_info.index|intval}" class="tab_li">
			{if $cat_info.cat_icon !=''}
			<div class="icon_cat" style="background-color:{$cat_info.cat_color|escape:'html':'UTF-8'}">
			<img src="{$icon_path|escape:'html':'UTF-8'}{$cat_info.cat_icon|escape:'html':'UTF-8'}" alt=""/>
			</div>
			{/if}
			 <h3>{$cat_info.cat_name|escape:'html':'UTF-8'} </h3></a>
		</li>
	{/foreach}
</ul>
<div class="tab-content">
	{foreach from=$group_cat_info item=cat_info name=g_cat_info}
	<div id="wt-prod-cat-{$smarty.foreach.g_cat_info.index|intval}" class="tab-pane {if $smarty.foreach.g_cat_info.first} active{/if}">
		{if isset($cat_info.show_sub) && $cat_info.show_sub == 1}
		<div class="sub-cat col-sm-2">
			<ul class="sub-cat-ul">
				{foreach from = $cat_info.sub_cat item=sub_cat name=sub_cat_info}
					<li><a href="{$link->getCategoryLink($sub_cat.id_category, $sub_cat.link_rewrite)|escape:'html':'UTF-8'}" title="{$sub_cat.name|escape:'html':'UTF-8'}">{$sub_cat.name|escape:'html':'UTF-8'}</a></li>
				{/foreach}
				{if $cat_info.show_img == 1 && isset($cat_info.id_image) && $cat_info.id_image > 0}
				<li class="cat-img">
					<a href="{$link->getCategoryLink($cat_info.id_cat, $cat_info.link_rewrite)|escape:'html':'UTF-8'}" title="{$cat_info.cat_name|escape:'html':'UTF-8'}">
						<img src="{$link->getCatImageLink($cat_info.link_rewrite, $cat_info.id_image, 'medium_default')|escape:'html':'UTF-8'}"/>
					</a>
				</li>
				{/if}
				
				{if isset($cat_info.special_prod_obj) && count($cat_info.special_prod_obj)}
					{$cat_product = $cat_info.special_prod_obj}
					{$id_lang = Context::getContext()->language->id}
					<li class="wt-prod-special">
						<h5 class="product-name">{$cat_product->name[$id_lang]|escape:'html':'UTF-8'}</h5>
						<a class="product_img_link" href="{$link->getProductLink($cat_product)|escape:'html':'UTF-8'}" title="{$cat_product->name[$id_lang]|escape:'html':'UTF-8'}">
						
						<img class="replace-2x img-responsive" src="{$link->getImageLink($cat_product->link_rewrite, $cat_product->id_image, 'home_default')|escape:'html':'UTF-8'}" alt="" title=""/>
						</a>
					</li>
				{/if}
			</ul>
		</div>
		{/if}
		{if $cat_info.cat_banner !=''}
		<div class="cat-banner col-sm-4">
			{if $cat_info.cat_banner!='' }
				<a href="{$link->getCategoryLink($cat_info.id_cat, $cat_info.link_rewrite)|escape:'html':'UTF-8'}" title="{$cat_info.cat_name|escape:'html':'UTF-8'}">
				<img src="{$banner_path|escape:'html':'UTF-8'}{$cat_info.cat_banner|escape:'html':'UTF-8'}" alt=""/>
				</a>
			{/if}
		</div>
		{/if}
		<div class="product_list">
			<div class="owl-prod-cat">
				{if isset($cat_info.product_list) && count($cat_info.product_list) > 0}
				{foreach from=$cat_info.product_list item=product name=product_list}
					{if $smarty.foreach.product_list.iteration % $number_line == 1 || $number_line == 1}
					<div class="item product-box ajax_block_product">
					{/if}
						<div class="product-container">
							<div class="product-container-img">
								<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:'html':'UTF-8'}">
									<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$product.legend|escape:html:'UTF-8'}" />
								</a>
								<div class="wt-label">
								{if isset($product.new) && $product.new == 1}
									<a class="new-box" href="{$product.link|escape:'html':'UTF-8'}">
										<span class="new-label">{l s='New' mod='wtproductcategory'}</span>
									</a>
								{/if}
								{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
									<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
										<span class="sale-label">{l s='Sale!' mod='wtproductcategory'}</span>
									</a>
								{/if}
								</div>
								{if isset($quick_view) && $quick_view}
								<a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view' mod='wtproductcategory'}">
									<span>{l s='Quick view' mod='wtproductcategory'}</span>
								</a>
								{/if}
								
								<div class="prod-hover">
									<div class="functional-buttons clearfix">
										{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
											{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
												{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($static_token)}&amp;token={$static_token|escape:'html':'UTF-8'}{/if}{/capture}
												<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='wtproductcategory'}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity > 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
													<span>{l s='Add to cart' mod='wtproductcategory'}</span>
												</a>
											{else}
												<span class="button ajax_add_to_cart_button btn btn-default disabled">
													<span>{l s='Out of stock' mod='wtproductcategory'}</span>
												</span>
											{/if}
										{/if}
										<div class="out-button">
										{hook h='displayProductListFunctionalButtons' product=$product}
										{if isset($comparator_max_item) && $comparator_max_item}
											<div class="compare">
												<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product|intval}" title="{l s='Add to Compare' mod='wtproductcategory'}">
													{l s='Compare' mod='wtproductcategory'}
												</a>
											</div>
										{/if}
										</div>
									</div>
								</div>
							</div>
							<h5 class="product-name">
							<a class="" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:'html':'UTF-8'}">		{$product.name|escape:'html':'UTF-8'}
							</a>
							</h5>
							{hook h='displayProductListReviews' product=$product}
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
					{if $smarty.foreach.product_list.iteration % $number_line == 0 || $smarty.foreach.product_list.last || $number_line == 1}
					</div>
					{/if}
				{/foreach}
				{else}
					<div class="item product-box ajax_block_product">
						<p class="alert alert-warning">{l s='No product at this time' mod='wtproductcategory'}</p>
					</div>
				{/if}
			</div>
			{if count($cat_info.manufacture)>0}
			<div class="manu-list">
				<ul>
					{foreach from=$cat_info.manufacture item=manu_item name=manufacture}
						<li><a href="#">{$manu_item->name|escape:'html':'UTF-8'}</a></li>
					{/foreach}
				</ul>
			</div>
			{/if}
		</div>
	</div>
{/foreach}
</div>
</div>