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

<div id="wt-block-facebook-social" class="footer-block col-xs-12 col-sm-4">
<h4>{l s='Suivez-nous' mod='wtblockfacebookandsocial'}</h4>
<div class="block_content toggle-footer">
{if $facebookurl != ''}
<div id="fb-root"></div>
<div id="facebook_block" class="toggle-footer facebook_block">
	<div class="facebook-fanbox">
		<div class="fb-like-box" data-href="{$facebookurl|escape:'html':'UTF-8'}" data-colorscheme="light" data-width="389" data-height="200" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false">
		</div>
	</div>
</div>
{/if}
{if $facebookurl != ''}
<script type="text/javascript">

$(document).ready(function() {
	if (isIE()) {
	 return false;
	} else {
	 initfb(document, 'script', 'facebook-jssdk');
	}
});
function isIE () {
  var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}

function initfb(d, s, id)
{
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) 
		return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=334341610034299";
	fjs.parentNode.insertBefore(js, fjs);
}
</script>
{/if}
</div>
</div>
