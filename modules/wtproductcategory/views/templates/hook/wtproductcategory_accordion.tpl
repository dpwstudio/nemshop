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
	
{foreach from=$group_cat_result item=group_cat name=group_cat_result}
	{$group_cat_info = $group_cat.cat_info}
	{foreach from=$group_cat_info item=cat_info name=g_cat_info}
 <div class="block-content">
	<div id="wt-prod-cat-{$cat_info.id_cat|intval}" class="row">
		  <div class="cat-bar col-sm-12">
		
			<div class="block-home-title">
					<div class="wt-out-title">
					  <h3><a href="{$link->getCategoryLink($cat_info.id_cat, $cat_info.link_rewrite)|escape:'html':'UTF-8'}" title="{$cat_info.cat_name|escape:'html':'UTF-8'}">{$cat_info.cat_name|escape:'html':'UTF-8'}</a></h3>
					</div>
		    </div>
		  </div>
		
		{if isset($group_cat.show_sub) && $group_cat.show_sub == 1}
		<div class="sub-cat wt-col-md-2">
			<ul class="sub-cat-ul">
				{foreach from = $cat_info.sub_cat item=sub_cat name=sub_cat_info}
					<li><a href="{$link->getCategoryLink($sub_cat.id_category, $sub_cat.link_rewrite)|escape:'html':'UTF-8'}" title="{$sub_cat.name|escape:'html':'UTF-8'}">{$sub_cat.name|escape}</a></li>
				{/foreach}
				{if $cat_info.show_img == 1 && isset($cat_info.id_image) && $cat_info.id_image > 0}
				<li class="cat-img">
					<a href="{$link->getCategoryLink($cat_info.id_cat, $cat_info.link_rewrite)|escape:'html':'UTF-8'}" title="{$cat_info.cat_name|escape:'html':'UTF-8'}">
						<img src="{$link->getCatImageLink($cat_info.link_rewrite, $cat_info.id_image, 'medium_default')|escape:'html':'UTF-8'}"/>
					</a>
				</li>
				{/if}
			</ul>
		</div>
		{/if}
		<div class="wt-prod-special col-sm-5 col-md-6">
			{if isset($cat_info.product_list) && count($cat_info.product_list) > 0}
		{foreach from=$cat_info.product_list item=product name=product_list}
			{if $smarty.foreach.product_list.iteration <= 1}
			<div class="item product-box ajax_block_product">
			<div class="product-container">
				<div class="product-container-img">
					<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}">
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'thickbox_default')|escape:'html':'UTF-8'}" alt="{$product.legend|escape:html:'UTF-8'}" /></a>
					{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
						<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
							<span class="sale-label">{l s='Sale' mod='wtproductcategory'}</span>
						</a>
					{/if}
					<div class="prod-hover">
						<div class="out-button">
						{if isset($quick_view) && $quick_view}
							<a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view' mod='wtproductcategory'}">
								<span>{l s='Quick view' mod='wtproductcategory'}</span>
							</a>
						{/if}
						{hook h='displayProductListFunctionalButtons' product=$product}
						{if isset($comparator_max_item) && $comparator_max_item}
							<div class="compare">
								<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product|intval}" title="{l s='Add to Compare' mod='wtproductcategory'}">
									{l s='Compare' mod='wtproductcategory'}
								</a>
							</div>
						{/if}
						</div>
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
						</div>
					</div>
				</div>
				<div class="product-container-content">
					<h5 class="product-name"><a href="{$product.link|escape:'html':'UTF-8'}">{$product.name|escape:'html':'UTF-8'}</a></h5>
					<p>
						{$product.description_short|strip_tags|escape:'html':'UTF-8'|truncate:150:'...'}
					</p>
					{hook h='displayProductListReviews' product=$product}
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
								{if $product.specific_prices.reduction_type == 'percentage'}
									<span class="price-percent-reduction">-{$product.specific_prices.reduction|escape:'html':'UTF-8' * 100}%</span>
								{/if}
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
		{/if}
		</div>
		<div class="product_list col-sm-7 col-md-6">
			<div class="owl-prod-cat">
				{if isset($cat_info.product_list) && count($cat_info.product_list) > 0}
				{foreach from=$cat_info.product_list item=product name=product_list}
					{if $smarty.foreach.product_list.iteration > 1}
						{if $smarty.foreach.product_list.iteration % $number_line == 0 || $number_line == 1}
						{$i=0}
						<div class="item product-box ajax_block_product col-sm-6">
						{/if}
						{$i=$i+1}
						<div class="product-container item-{$i|intval}">
							<div class="product-container-img">
								<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}">
								<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$product.legend|escape:html:'UTF-8'}" /></a>
								<div class="wt-label">
								{if isset($product.new) && $product.new == 1}
									<a class="new-box" href="{$product.link|escape:'html':'UTF-8'}">
										<span class="new-label">{l s='New' mod='wtproductcategory'}</span>
									</a>
								{/if}
								{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
									<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
										{if $product.specific_prices.reduction_type == 'percentage'}
											<span class="price-percent-reduction">-{$product.specific_prices.reduction|escape:'html':'UTF-8' * 100}%</span>
										{else}
											<span class="sale-label">{l s='Sale' mod='wtproductcategory'}</span>
										{/if}	
									</a>
								{/if}
								</div>
								<div class="prod-hover">
									<div class="out-button">
									{if isset($quick_view) && $quick_view}
										<a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view' mod='wtproductcategory'}">
											<span>{l s='Quick view' mod='wtproductcategory'}</span>
										</a>
									{/if}
									{hook h='displayProductListFunctionalButtons' product=$product}
									{if isset($comparator_max_item) && $comparator_max_item}
										<div class="compare">
											<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product|intval}" title="{l s='Add to Compare' mod='wtproductcategory'}">
												{l s='Compare' mod='wtproductcategory'}
											</a>
										</div>
									{/if}
									</div>
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
									</div>
								</div>
							</div>
							<div class="product-container-content">
								<h5 class="product-name"><a href="{$product.link|escape:'html':'UTF-8'}">{$product.name|escape:'html':'UTF-8'}</a></h5>
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
						{if $smarty.foreach.product_list.iteration % $number_line == 1 ||$smarty.foreach.product_list.last || $number_line == 1}
						</div>
						{/if}
					{/if}
				{/foreach}
				{/if}
			</div>
		</div>
		{if $cat_info.cat_banner!='' }
		<div class="cat-banner col-sm-12">
			<a href="{$link->getCategoryLink($cat_info.id_cat, $cat_info.link_rewrite)|escape:'html':'UTF-8'}" title="{$cat_info.cat_name|escape:'html':'UTF-8'}"><img src="{$banner_path|escape:'html':'UTF-8'}{$cat_info.cat_banner|escape:'html':'UTF-8'}" alt=""/></a>
		</div>
		{/if}
	</div>
	</div>
	{/foreach}
{/foreach}