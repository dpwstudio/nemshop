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

<h1 class="page-heading">{l s='Search: ' mod='wtblog'}<span class="lighter">{$wt_blog_tag->name|escape:'htmlall':'UTF-8'}</span>
<span class="heading-counter">{$count|intval}{l s=' results have been found.' mod='wtblog'}</span>
</h1>

<!--list post-->
{if $wt_postes_empty == 0}
	{include file="./post_list.tpl"}
{/if}
{if $wt_postes_empty == 1}
	<div class="empty">{l s='There are no posts in this tag' mod='wtblog'}</div>
{/if}
<!--pagination-->
<div class="content_sortPagiBar clearfix">
{if $wt_postes_empty != 1}
	{include file="./pagination.tpl"}
{/if}
</div>

