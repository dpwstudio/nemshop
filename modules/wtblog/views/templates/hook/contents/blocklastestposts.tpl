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

<h4 class="title_block">{l s='Lastest Blog' mod='wtblog'}</h4>
{if $lastest_posts|@count > 0}
<div class="block_content">
	<ul class="ul-lastest-post">
	{foreach from=$lastest_posts item=post name=lastest_posts}
		<li {if $smarty.foreach.lastest_posts.last}class="last"{/if}>
			{if $post['image'] != ''}
				<a class="blog_lastest_posts_img" href="{$post['link']|escape:'htmlall':'UTF-8'}" title="{$post['name']|escape:'htmlall':'UTF-8'}">
					<img class="img-responsive" src="{$wt_path|escape:'htmlall':'UTF-8'}{$post['image']|escape:'htmlall':'UTF-8'}" title="{$post['name']|escape:'htmlall':'UTF-8'}" alt="{$post['name']|escape:'htmlall':'UTF-8'}" />
				</a>
			{/if}
			<h5>
			<a href="{$post['link']|escape:'htmlall':'UTF-8'}" title="{$post['name']|escape:'htmlall':'UTF-8'}">
				<span>{$post['name']|escape:'htmlall':'UTF-8'|truncate:60:'...'}</span>
			</a>
			</h5>
			<div class="g-blog-info">
				<div class="blog-author">{$post['author']|escape:'htmlall':'UTF-8'}</div>
				<div class="blog-date">{$post['date_add']|escape:'htmlall':'UTF-8'}</div>
			</div>
		</li>
	{/foreach}
	</ul>
</div>
{else}
	<div class="no_item">{l s='There is no lastest post' mod='wtblog'}</div>
{/if}
<div class="clearfix"></div>