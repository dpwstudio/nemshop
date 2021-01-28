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

{if isset($errorposts) && $errorposts}
	<div class="error">{l s='There is no post' mod='wtblog'}</div>
{else}
{if isset($post)}
	<div id="wt_post">
		{if $wt_imipd_show != 'none'}
			{if isset($post['image']) && $post['image'] != ''}
				<div class="img-blog-detail">
				<img class="" src="{$post['image']|escape:'htmlall':'UTF-8'}" title="{$post['name']|escape:'html':'UTF-8'}" alt="{$post['name']|escape:'htmlall':'UTF-8'}" />
				</div>
			{/if}
		{/if}
		<h5 class="post_title">{$post['name']|escape:'htmlall':'UTF-8'}</h5>
		<p>{$post['description_short']|strip_tags|escape:'html':'UTF-8'}</p>
		<p>{$post['description']|escape:'quotes':'UTF-8'}</p>
		<div class="g-blog-info">
			<div class="blog-author">{$post['author']|escape:'htmlall':'UTF-8'}</div>	
			<div class="blog-date">{$post['date_add']|escape:'htmlall':'UTF-8'}</div>
		</div>
		{if isset($wttags) && $wttags}
			<div class="tag-blog clearfix">
				<span class="title_tag_blog">{l s='Tags:' mod='wtblog'}</span>
				<div class="tag-list">
					{foreach from=$wttags item=tag name=wttags}
						<span><a href="{$tag.link|escape:'html':'UTF-8'}">{$tag.name|escape:'htmlall':'UTF-8'}{if !$smarty.foreach.wttags.last},{/if}</a></span>
					{/foreach}
				</div>
			</div>
		{/if}
	</div>
	
	<!--related posts-->
	{if isset($related_posts) && $related_posts }
	<div class="related_posts blog_block">
		<div class="block-home-title">
			<div class="wt-out-title"><h3>{l s='Related Articles' mod='wtblog'}</h3></div>
		</div>
		<ul class="blog_content">
		{foreach from=$related_posts item=related_post name=related_posts}
			<li class="item-related-post">
				<strong><a href="{$WTLink->getLinkPostDetail($related_post['id_wt_blog_post'],$related_post['link_rewrite'],$related_post['id_wt_blog_category'])|escape:'htmlall':'UTF-8'}" title="{$related_post['name']|escape:'htmlall':'UTF-8'}">{$related_post['name']|escape:'htmlall':'UTF-8'}</a></strong>
			</li>
		{/foreach}
		</ul>
	</div>
	{/if}
		<!-- display comment list -->
		{if $count_comment_total>0}
		<div class="comment-list">
			<div class="block-home-title">
				<div class="wt-out-title">
					<h3>{if $count_comment_total <= 1} {l s='Comment' mod='wtblog'}{/if}{if $count_comment_total > 1} {l s='Comments' mod='wtblog'}{/if}</h3>
				</div>
			</div>
			{foreach from=$comments item=comment name=cscomments}
				<div class="comment-item {if $smarty.foreach.cscomments.last}last{/if}">
					<h5 class="post_title">{$comment['title']|escape:'htmlall':'UTF-8'}</h5>
					<p>{$comment['content']|escape:'htmlall':'UTF-8'}</p>
					<div class="g-blog-info">
						<div class="blog-author">{$comment['author_name']|escape:'htmlall':'UTF-8'}</div>	
						<div class="blog-date">{$comment['date_add']|escape:'htmlall':'UTF-8'}</div>
					</div>
				</div>
			{/foreach}
			{if $count_comment_total > $count_comment_show && !isset($viewall)}
			<a href="{$WTLink->getLinkPostDetail($post['id_wt_blog_post'],$post['link_rewrite'],$post['id_wt_blog_category'])|escape:'htmlall':'UTF-8'}{if $url_rewrite == 1}?{else}&{/if}viewall">{l s='view all' mod='wtblog'}</a>
			{elseif isset($viewall)}
				<a href="{$WTLink->getLinkPostDetail($post['id_wt_blog_post'],$post['link_rewrite'],$post['id_wt_blog_category'])|escape:'htmlall':'UTF-8'}">{l s='Collapse' mod='wtblog'}</a>
			{/if}
		</div>
		{/if}
		<!-- /display comment list -->
	<!-- display form comment-->
	{if $display_form_comment == 1}
		<div class="comment_form">
			<form action="{$WTLink->getLinkPostDetail($post['id_wt_blog_post'],$post['link_rewrite'],$post['id_wt_blog_category'])|escape:'htmlall':'UTF-8'}" name="addcomment" method="post" class="std">
				<fieldset>
					<div class="block-home-title">
						<div class="wt-out-title"><h3>{l s='Leave your comment' mod='wtblog'}</h3></div>
					</div>
					<div class="blog_content">
					<div class="out_left">
					{if isset($error)}
						<div class="alert alert-danger">
							{l s='There ' mod='wtblog'}{if $error|@count <= 1} {l s='is ' mod='wtblog'} {else} {l s='are ' mod='wtblog'}{/if}{$error|@count|intval} {if $error|@count <= 1} {l s='error ' mod='wtblog'} {else} {l s='errors ' mod='wtblog'}{/if}
							<ol>
								{if isset($error['author_name'])}<li>{$error['author_name']|escape:'htmlall':'UTF-8'}</li>{/if}
								{if isset($error['author_email'])}<li>{$error['author_email']|escape:'htmlall':'UTF-8'}</li>{/if}
								{if isset($error['title'])}<li>{$error['title']|escape:'htmlall':'UTF-8'}</li>{/if}
								{if isset($error['captcha'])}<li>{$error['captcha']|escape:'htmlall':'UTF-8'}</li>{/if}
								{if isset($error['content'])}<li>{$error['content']|escape:'htmlall':'UTF-8'}</li>{/if}
							</ol>
						</div>
					{elseif isset($success)}
						<div class="success">
							<p class="alert alert-success">
							{l s='Comment is success!' mod='wtblog'}
							</p>
						</div>
					{/if}
					
					{if $logged}
							<p class="text form-group">
								<label for="name">{l s='Your Name' mod='wtblog'} <em class="pl_requie">*</em></label>
								<input class="plinput form-control" type="text" name="author_name" value="{$cookie->customer_firstname|escape:'htmlall':'UTF-8'} {$cookie->customer_lastname|escape:'htmlall':'UTF-8'}"/>
							</p>
							<p class="text form-group">
								<label for="email">{l s='Email' mod='wtblog'} <em class="pl_requie">*</em></label>
								<input class="plinput form-control" type="text" name="author_email" value="{$cookie->email|escape:'htmlall':'UTF-8'}"/> 
							</p>
					{else}
						<p class="text form-group">
							<label for="name">{l s='Your Name' mod='wtblog'} <em class="pl_requie">*</em></label>
							<input class="plinput form-control" type="text" name="author_name" value=""/>
						</p>
						<p class="text form-group">
							<label for="email">{l s='Email' mod='wtblog'} <em class="pl_requie">*</em></label>
							<input class="plinput form-control" type="text" name="author_email" value=""/> 
						</p>
					{/if}
					<input type="hidden" name="id_wt_blog_post" value="{$post['id_wt_blog_post']|escape:'htmlall':'UTF-8'}"/>
					<input type="hidden" name="id_shop" value="{$id_shop|intval}"/>
					<p class="text form-group">
							<label for="email">{l s='Title' mod='wtblog'} <em class="pl_requie">*</em></label>
							<input class="plinput form-control" type="text" name="title" value=""/> 
					</p>
					{if $using_captcha == 1}
					<p class="text captcha form-group">
							<label for="email">{l s='Enter the code' mod='wtblog'} <em class="pl_requie">*</em></label>
							<input class="plinput form-control" type="text" name="captcha"  value=""/>
					</p>
					<p>
						<img src="{$modules_dir|escape:'html':'UTF-8'}wtblog/classes/CaptchaSecurityImages.php?width=120&height=40&characters=5" alt=""/>
					</p>
					{/if}
					</div>
					<div class="out_right">
					<input type="hidden" name="active" value="{if $validate_comment ==1}0{else}1{/if}"/>
					<p class="textarea form-group">
						<label for="comment">{l s='Comment' mod='wtblog'} <em class="pl_requie">*</em></label>
						<textarea class ="rte form-control" id="elm1" name="content" cols="60" rows="6"></textarea>
					</p>
					<input type="hidden" name="submitcomment" value="true" />
					<button  type="submit">{l s='Submit' mod='wtblog'}</button>
					</div>
					</div>
				</fieldset>
			</form>
		</div>
	{/if}<!-- end display form comment-->
{/if}<!--end isset post-->
{/if}
<!--end content-->
