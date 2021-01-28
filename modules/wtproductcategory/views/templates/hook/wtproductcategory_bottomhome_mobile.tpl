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

<div class="wt-prod-cat prod-mobile clearfix">
	{foreach from=$group_cat_result item=group_cat name=group_cat_result}
			{include file="./wtproductcategory_accordion_mobile.tpl" group_cat_info = $group_cat.cat_info}
			<script type="text/javascript">
			$(document).ready(function() {
				$(".prod-mobile .owl-prod-cat").owlCarousel({
				  loop: true,
				  responsive: {
						0: { items: 1},
						464:{ items: 1},
						750:{ items: 1},
						974:{ items: 1},
						1170:{ items: 1}
					},
				  dots: false,
				  nav: true,
				  loop: true
				  });
			});
			</script>
	{/foreach}	
</div>