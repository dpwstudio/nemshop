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

{$number_line = 3}
{$id_lang = Context::getContext()->language->id}
	<div class="block-home-title">
		<div class="wt-out-title">
			<h3>{l s='Nos suggestions' mod='wtproductcategory'}</h3>
		</div>
	</div>
	<div class="block-content row type-colum">
		{foreach from=$group_cat_info item=cat_info name=g_cat_info}
		<div class="wt-prod-cat-{$cat_info.id_cat|intval} col-xs-12 col-sm-4">
		<div class="wt-out-prod-cat">
			<div class="cat-bar">
			  <div class="out-wt-prod">
				{if $cat_info.cat_icon!='' }
					<div class="icon_cat" style="background-color:{$cat_info.cat_color|escape:'html':'UTF-8'}">
					   <img src="{$icon_path|escape:'html':'UTF-8'}{$cat_info.cat_icon|escape:'html':'UTF-8'}" alt=""/>
					</div>
				{/if}
			    <h3><a href="{$link->getCategoryLink($cat_info.id_cat, $cat_info.link_rewrite)|escape:'html':'UTF-8'}" title="{$cat_info.cat_name|escape:'html':'UTF-8'}">{$cat_info.cat_name|escape:'html':'UTF-8'}</a></h3>
			  </div>
			</div>
			{if isset($group_cat.show_sub) && $group_cat.show_sub == 1}
			<div class="sub-cat">
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
			{if $cat_info.cat_banner!='' }
			<div class="cat-banner">
				<a href="{$link->getCategoryLink($cat_info.id_cat, $cat_info.link_rewrite)|escape:'html':'UTF-8'}" title="{$cat_info.cat_name|escape:'html':'UTF-8'}">
					<img src="{$banner_path|escape:'html':'UTF-8'}{$cat_info.cat_banner|escape:'html':'UTF-8'}" alt=""/>
				</a>
			</div>
			{/if}
			<div class="prod-list-col">
				<div class="owl-prod-cat">
					{if isset($cat_info.product_list) && count($cat_info.product_list) > 0}
					{foreach from=$cat_info.product_list item=product name=product_list}
						{if $smarty.foreach.product_list.iteration % $number_line == 1 || $number_line == 1}
						<div class="item product-box ajax_block_product">
						{/if}
							<div class="product-container">
								<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.legend|escape:html:'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html':'UTF-8'}" alt="{$product.legend|escape:html:'UTF-8'}" /></a>
								<div class="prod-content">
									<h5 class="product-name">
										<a href="{$product.link|escape:'html':'UTF-8'}" title="">{$product.name|escape:'html':'UTF-8'}</a>
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
		</div>
		{/foreach}
	</div>