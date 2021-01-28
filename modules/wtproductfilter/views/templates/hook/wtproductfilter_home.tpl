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

<div class="wt-prod-filter prod-filter-home clearfix">
	<div class="block-content">
		{foreach from=$group_prod_fliter item=product_hook name=product_hook}
			{if $product_hook.type_display == 'accordion'}
				{include file="./wtproductfilter_accordion.tpl" product_groups = $product_hook.product_group prod_img='large_default'}
			{/if}
			{if $product_hook.type_display == 'column'}
				{include file="./wtproductfilter_column.tpl" product_groups = $product_hook.product_group}
			{/if}
			{if $product_hook.type_display == 'tab'}
				{include file="./wtproductfilter_tab.tpl" product_groups = $product_hook.product_group}
			{/if}
			{if $product_hook.use_slider == 1}
			<script type="text/javascript">
			$(window).load(function()
			{
				$(".prod-filter-home .owl-prod-filter").owlCarousel({
				  loop: true,
					responsive: {
							0: { items: 1},
							464:{ items: 2,slideBy:2},
							750:{ items: 3,slideBy:2},
							974:{ items: 4,slideBy:2},
							1170:{ items: 4,slideBy:2}
						},
				  dots: false,
				  nav: true,
				  loop: true
				});
			});
			</script>
			{/if}
		{/foreach}
	</div>
</div>