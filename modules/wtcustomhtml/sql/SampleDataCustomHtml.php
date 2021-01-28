<?php
/**
* 2007-2014 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class SampleDataCustomHtml
{
	public function initData()
	{
		$result = true;
		$id_shop = Configuration::get('PS_SHOP_DEFAULT');
		
		$block1 = '<div class="bn-top-home">\r\n<ul>\r\n<li>\r\n<div class="out-top-home"><a href="#"><img src="{static_block_url}themes/wt_delicieux/img/cms/openning-hour.jpg" alt="" /></a></div>\r\n</li>\r\n</ul>\r\n</div>';
		
		$block2 = '<div class="payment">\r\n<p><img src="{static_block_url}themes/wt_delicieux/img/cms/footer_icon.png" alt="" /></p>\r\n<p>© 2016 Delicieux <a href="#">Prestashop Theme</a> by <a href="#">WaterThemes</a>. Website Template by Prestashop.</p>\r\n</div>';
		
		$block3 = '<div class="wt-html-nav">\r\n<ul>\r\n<li class="facebook"><a class="_blank" href="http://www.facebook.com/prestashop" target="_blank"> <i class="icon-facebook">facebook</i> </a></li>\r\n<li class="twitter"><a class="_blank" href="http://www.twitter.com/prestashop" target="_blank"> <i class="icon-twitter">twitter</i> </a></li>\r\n<li class="rss"><a class="_blank" href="http://www.prestashop.com/blog/en/" target="_blank"> <i class="icon-rss">rss</i> </a></li>\r\n<li class="google-plus"><a class="_blank" href="https://www.google.com/+prestashop" rel="publisher" target="_blank"> <i class="icon-google-plus">google</i> </a></li>\r\n</ul>\r\n</div>';
		
		$block4 = '<div class="wt-home-banner">\r\n<div class="container">\r\n<div class="wt-out-banner"><span class="bg_icon"> <img src="{static_block_url}themes/wt_delicieux/img/cms/gato-cake.png" alt="" /></span>\r\n<h3>shushi kings</h3>\r\n<p>Duis bibendum sit amet mi in ornare. Sed quam lectus, fringilla sed enim quis, laoreet efficitur</p>\r\n</div>\r\n</div>\r\n</div>';
		
		$block5 = '<div class="wt-topcolumn-banner">\r\n<ul class="htmlcontent-home row">\r\n<li class="col-xs-6">\r\n<div class="out-content-home"><a href="#"><img src="{static_block_url}themes/wt_delicieux/img/cms/banner-1.jpg" alt="" /></a>\r\n<div class="item-html">\r\n<h4>Matcha</h4>\r\n<span>shop now</span></div>\r\n</div>\r\n</li>\r\n<li class="col-xs-6">\r\n<div class="out-content-home"><a href="#"><img src="{static_block_url}themes/wt_delicieux/img/cms/banner-2.jpg" alt="" /></a>\r\n<div class="item-html">\r\n<h4>Chili Powder</h4>\r\n<span>shop now</span></div>\r\n</div>\r\n</li>\r\n<li class="col-xs-6">\r\n<div class="out-content-home"><a href="#"> <img src="{static_block_url}themes/wt_delicieux/img/cms/banner-3.jpg" alt="" /></a>\r\n<div class="item-html">\r\n<h4>Turmeric</h4>\r\n<span>shop now</span></div>\r\n</div>\r\n</li>\r\n<li class="col-xs-6">\r\n<div class="out-content-home"><a href="#"> <img src="{static_block_url}themes/wt_delicieux/img/cms/banner-4.jpg" alt="" /></a>\r\n<div class="item-html">\r\n<h4>Bay Leaves</h4>\r\n<span>shop now</span></div>\r\n</div>\r\n</li>\r\n</ul>\r\n</div>';
		
		$block6 = '<ul>\r\n<li><a title="visa" href="#"><img src="{static_block_url}themes/wt_delicieux/img/cms/visa.png" alt="" /></a></li>\r\n<li><a title="master card" href="#"><img src="{static_block_url}themes/wt_delicieux/img/cms/paypal.png" alt="" /></a></li>\r\n<li><a title="american" href="#"><img src="{static_block_url}themes/wt_delicieux/img/cms/usa.png" alt="" /></a></li>\r\n<li><a title="paypal" href="#"><img src="{static_block_url}themes/wt_delicieux/img/cms/rss.png" alt="" /></a></li>\r\n<li><a title="rss" href="#"><img src="{static_block_url}themes/wt_delicieux/img/cms/master-cart.png" alt="" /></a></li>\r\n</ul>';
		
		
		$result &= Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'wtcustomhtml` (`id_wtcustomhtml`, `hook`, `active`) 
			VALUES
			(1, "displayTopHome", 1),
			(2, "displayBottomFooter", 1),
			(3, "displayNav", 1),
			(4, "displayBottomHome", 1),
			(5, "displayTopColumn", 1),
			(6, "displayFooter", 1);'); 
		
		$result &= Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'wtcustomhtml_shop` (`id_wtcustomhtml`, `id_shop`,`active`) 
			VALUES 
			(1,'.$id_shop.', 1),
			(2,'.$id_shop.', 1),
			(3,'.$id_shop.', 1),
			(4,'.$id_shop.', 1),
			(5,'.$id_shop.', 1),
			(6,'.$id_shop.', 1);');
			
		foreach (Language::getLanguages(false) as $lang)
		{
			$result &= Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'wtcustomhtml_lang` (`id_wtcustomhtml`, `id_shop`, `id_lang`, `title`, `content`) 
			VALUES 
			("1", "'.$id_shop.'","'.$lang['id_lang'].'","Top Home Banner", \''.$block1.'\'),
			("2", "'.$id_shop.'","'.$lang['id_lang'].'","Copy Right", \''.$block2.'\'),
			("3", "'.$id_shop.'","'.$lang['id_lang'].'","Social Link", \''.$block3.'\'),
			("4", "'.$id_shop.'","'.$lang['id_lang'].'","Home Banner", \''.$block4.'\'),
			("5", "'.$id_shop.'","'.$lang['id_lang'].'","Category Banner", \''.$block5.'\'),
			("6", "'.$id_shop.'","'.$lang['id_lang'].'","Paymnet Footer", \''.$block6.'\');');
		}
		return $result;
	}
}