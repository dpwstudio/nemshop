{*
* 2007-2015 PrestaShop
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

{extends file="helpers/form/form.tpl"}

{block name="input"}
	{$smarty.block.parent}
	{if $input.type == 'range'}
		{foreach from=$fields_value[$input.name] item=field_value name=field}
			<div class="postal_range">
				<input type="text"
					   name="{$input.name|escape:'html':'UTF-8'}_from_{$smarty.foreach.field.index|escape:'html':'UTF-8'}"
					   value="{$field_value[0]|escape:'html':'UTF-8'}"
					   class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
						/>
				{l s='and' mod='postaldeliv'}
				<input type="text"
					   name="{$input.name|escape:'html':'UTF-8'}_to_{$smarty.foreach.field.index|escape:'html':'UTF-8'}"
					   value="{$field_value[1]|escape:'html':'UTF-8'}"
					   class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
						/>
				{if $field_value[0] != '' || $field_value[1] != ''}
					<i class="icon-minus-sign" data-range="{$smarty.foreach.field.index|escape:'html':'UTF-8'}"></i>
				{/if}
			</div>
		{/foreach}
		<i class="icon-plus-sign" data-range="{$smarty.foreach.field.iteration|escape:'html':'UTF-8'}"></i>
		<input value="{$smarty.foreach.field.iteration|escape:'html':'UTF-8'}" type="hidden" name="{$input.name|escape:'html':'UTF-8'}_count" id="{$input.name|escape:'html':'UTF-8'}_count">
	{/if}
{/block}

{block name="after"}
	{addJsDefL name=data_and}{l s='and' mod='postaldeliv' js=1}{/addJsDefL}
	{addJsDefL name=data_delete_confirm}{l s='Are you sure you want to delete this range ?' mod='postaldeliv' js=1}{/addJsDefL}
{/block}
