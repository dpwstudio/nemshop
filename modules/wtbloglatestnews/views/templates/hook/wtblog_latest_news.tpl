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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="blog_latest_new_home" class="clearfix block">
	<div class="container">
	    <div class="block-home-title">
			<div class="wt-out-title">
			<h3><a href="#">{l s='Latest Post' mod='wtbloglatestnews'}</a></h3>
			</div>
		</div>
		<div class="block_content">
			{if isset($view_data) AND !empty($view_data)}
				{assign var='i' value=1}
				<ul class="row">
				{foreach from=$view_data item=post}
						{assign var="options" value=null}
						{$options.id_post = $post.id_wt_blog_post}
						{$options.slug = $post.link_rewrite}
						<li class="col-xs-12 col-sm-6">
							<div class="blog-img">
								<a href="{$post['link']|escape:'htmlall':'UTF-8'}" title="">
								<img alt="" class="feat_img_small" src="{$modules_dir|escape:'html':'UTF-8'}wtblog/{$post.image|escape:'html':'UTF-8'}">
								</a>
							</div>
							<div class="blog-content">
								<div class="g-blog-info">
									<div class="blog-date">{$post.date_add|date_format|escape:'htmlall':'UTF-8'}</div>
								</div>
								<h5 class="post_title">
									<a href="{$post['link']|escape:'htmlall':'UTF-8'}">
									{$post.name|truncate:30:'...'|escape:'html':'UTF-8'}
									</a>
								</h5>
								<p>
									{$post.description_short|strip_tags|truncate:115:'...'|escape:'htmlall':'UTF-8'}
								</p>
								<div class="g-blog-info">
									<div class="blog-author">{$post.author|escape:'htmlall':'UTF-8'}</div>
								</div>
							</div>
						</li>
					{$i=$i+1}
				{/foreach}
				</ul>
			{/if}
		 </div>
	</div>
</div>