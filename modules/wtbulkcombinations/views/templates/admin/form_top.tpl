{*
* 2016 WeeTeam
*
* @author    WeeTeam <info@weeteam.net>
* @copyright 2016 WeeTeam
* @license   http://www.gnu.org/philosophy/categories.html (Shareware)
*}
{foreach from=$errors item=error}
<div class="alert alert-danger">{$error|escape:'htmlall':'UTF-8'}</div>
{/foreach}
{foreach from=$success item=success_msg}
<div class="alert alert-success">{$success_msg|escape:'htmlall':'UTF-8'}</div>
{/foreach}

<form action="" method="POST" enctype="multipart/form-data" class="form-horizontal">
	{if isset($show_combinations_form) && $show_combinations_form}
	<div class="panel">
		<h3>{l s='Define price impact and quantity of combinations' mod='wtbulkcombinations'}</h3>
		<div class="form-group">
			<table class="table">
				<tbody>
					{if $a_ready_combinations && count($a_ready_combinations)}
					{foreach from=$a_ready_combinations key=i_key item=a_combination}
					<tr>
						<td rowspan="2">
							{foreach from=$a_combination.combination item=id_attribute}
							<input type="hidden" name="combination[{$i_key|intval}][combination][]" value="{$id_attribute|intval}" />
							{/foreach}
							{foreach from=$a_combination.combination item=id_attribute}
							<span style="font-weight: bold;">{$attributes_names[$id_attribute]['group_name']|escape:'htmlall':'UTF-8'}:</span> {$attributes_names[$id_attribute]['name']|escape:'htmlall':'UTF-8'}
							<br/>
							{/foreach}
							</td>
							<td>{l s='Price' mod='wtbulkcombinations'}</td>
							<td><input type="text" style="width: 50px;" name="combination[{$i_key|intval}][price]" value="{$a_combination['price']|floatval}" size="5" /></td>
							<td rowspan="2" align="center">
							<button type="submit" name="submit-delete-combination" value="{$i_key|intval}" class="btn btn-default"><i class="icon-trash"></i></button>
						</td>
					</tr>
					<tr>
						<td>{l s='Quantity' mod='wtbulkcombinations'}</td>
						<td><input type="text" style="width: 50px;" name="combination[{$i_key|intval}][quantity]" value="{$a_combination['quantity']|intval}" size="5" /></td>
					</tr>
					{/foreach}
					{/if}
				</tbody>
			</table>
		</div>
		<div class="panel-footer">
			<button type="submit" name="submit-delete-all" class="btn btn-default pull-right"><i class="process-icon-delete"></i>{l s='Remove all' mod='wtbulkcombinations'}</button>
		</div>
	</div>

	<div class="panel" id="selectProductsForCombinations">
		<h3>{l s='Create these combinations for' mod='wtbulkcombinations'}</h3>
		<div class="row">
			<div class="form-group">
				<div class="col-md-4">
					<div class="checkbox">
						<label for="products_select_type1"><input type="radio" id="products_select_type1" name="products_select_type" value="1" checked="checked"> {l s='All the products' mod='wtbulkcombinations'}</label>
					</div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<label for="products_select_type2"><input type="radio" id="products_select_type2" name="products_select_type" value="2"> {l s='Select categories' mod='wtbulkcombinations'}</label>
					</div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<label for="products_select_type3"><input type="radio" id="products_select_type3" name="products_select_type" value="3"> {l s='Select products' mod='wtbulkcombinations'}</label>
					</div>
				</div>
			</div>
			<div class="form-group products_select_type products_select_type2" style="display: none;">
				<div class="form-group">
					<div class="bloc-leadin">
						<div id="container_category_select_tree">
	{/if}