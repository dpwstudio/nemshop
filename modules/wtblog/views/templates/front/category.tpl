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
<!--category detail-->
<div class="wt-blog-category">
	
	{if isset($wt_blog_category['image']) && $wt_allow_category_image == 1 && {$wt_blog_category['image']}!=''}
			<div class="content_scene_cat">
				<img src="{$wt_blog_category['image']|escape:'html':'UTF-8'}" title="{$wt_blog_category['name']|escape:'html':'UTF-8'}" alt="{$wt_blog_category['name']|escape:'html':'UTF-8'}" />
			</div>
	{/if}
	
	<div class="wt-blog-cat-img">
		<div class="container">
			<h1 class="page-heading"> 
			{if isset($wt_blog_category)}
				<span class="cat-name">{$wt_blog_category['name']|escape:'html':'UTF-8'}</span>
			{else}
				{l s='Welcome to our blog' mod='wtblog'}
			{/if}
			{if isset($wt_post_list)}<span class="heading-counter">{l s='There ' mod='wtblog'} {if {$count_blog|intval} > 1}{l s='are ' mod='wtblog'} {else}{l s='is ' mod='wtblog'} {/if}{$count_blog|intval} {if {$count_blog|intval} > 1}{l s='posts' mod='wtblog'} {else}{l s='post' mod='wtblog'} {/if}</span>{/if}
			</h1>
			{if isset($wt_blog_category)}
				{if $wt_allow_category_description == 1 && $wt_blog_category['description'] != ''}
					{if strlen($wt_blog_category['description']) > 350}
					<div id="category_description_short" class="rte">{$wt_blog_category['description']|truncate:120|escape:'html':'UTF-8'}</div>
					{else}
						<div class="rte">{$wt_blog_category['description']|escape:'html':'UTF-8'}</div>
					{/if}
				{/if}
			{/if}
		</div>
	</div>
</div> <!-- close div cs-category-info -->
<!--list post-->
{if $wt_postes_empty == 0}
	{include file="./post_list.tpl"}
	<div class="content_sortpagebar clearfix">
		<div class="bottom-pagination-content clearfix">
			{if $wt_postes_empty != 1}
				{include file="./pagination.tpl"}
			{/if}
		</div>
	</div>
{/if}


{if $wt_postes_empty == 1}
	<div class="empty">{l s='There are no posts in this category' mod='wtblog'}</div>
{/if}
