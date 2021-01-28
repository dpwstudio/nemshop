{*
* 2016 WeeTeam
*
* @author    WeeTeam <info@weeteam.net>
* @copyright 2016 WeeTeam
* @license   http://www.gnu.org/philosophy/categories.html (Shareware)
*}
	{if isset($show_combinations_form) && $show_combinations_form}
						</div>
					</div>
				</div>
				<div class="form-group products_select_type3_products col-md-6" style="padding: 0 5px 0 25px;">
				
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group" style="margin-bottom: 0;">
				<div class="col-md-6">
					<div><b>{l s='Options' mod='wtbulkcombinations'}</b></div>
					<div>
						<div class="checkbox">
							<label for="remove_previous"><input type="checkbox" id="remove_previous" name="remove_previous" value="1"> {l s='Remove previous combinations for selected products' mod='wtbulkcombinations'}</label>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<button type="submit" name="submit-combination" onclick="javascript: return confirm('{l s='Are you sure want to create those combinations for selected products?' mod='wtbulkcombinations' js='1'}')" value="1" class="btn btn-default pull-right"><i class="process-icon-save"></i>{l s='Create combinations' mod='wtbulkcombinations'}</button>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<button type="button" id="butAddMoreCombinations" class="btn btn-default"><i class="process-icon-new"></i>{l s='Create other combinations' mod='wtbulkcombinations'}</button>
		</div>
	</div>
	{/if}

	<div class="panel" id="attributesSelect"{if $show_combinations_form} style="display: none;"{/if}>
		<h3>{l s='Select attributes to create combinations' mod='wtbulkcombinations'}</h3>
		<div clas="row">
			<div class="form-group">
				<div class="col-md-6">
				<strong>{l s='Click to select attributes' mod='wtbulkcombinations'}</strong>
					<div id="attributes-list">
					{foreach from=$attribute_groups item=attribute_group}
						{if isset($attribute_js[$attribute_group['id_attribute_group']])}
							<div class="optgroup" data-name="{$attribute_group['id_attribute_group']|intval}" id="{$attribute_group['id_attribute_group']|intval}">
								<div class="title">{$attribute_group['name']|escape:'htmlall':'UTF-8'}</div>
								<div class="content">
								{foreach $attribute_js[$attribute_group['id_attribute_group']] key=k item=v}
									<div class="option" id="attr_{$k|intval}" data-group="{$attribute_group['id_attribute_group']|intval}" data-group-name="{$attribute_group['name']|escape:'htmlall':'UTF-8'}" data-value="{$k|intval}" data-name="{$k|intval}" title="{$v|escape:'htmlall':'UTF-8'}">
										<input type="hidden" name="attributes_tmp[]" value="{$k|intval}" />{$v|escape:'htmlall':'UTF-8'}
									</div>
								{/foreach}
								</div>
							</div>
						{/if}
					{/foreach}
					</div>
				</div>

				<div class="col-md-6">
					<strong>{l s='Selected attributes' mod='wtbulkcombinations'}</strong>
					<div id="attributes-list-result">

					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<button type="submit" name="submit-attributes" value="1" class="btn btn-default pull-right"><i class="process-icon-save"></i>{l s='Create combinations' mod='wtbulkcombinations'}</button>
		</div>
	</div>
	<script>
	function treeClickFunc()
	{
		var categoryId = $('div.products_select_type3 #categories-tree input[type=radio]:checked').val();

		$.ajax({
			type: "POST",
			url: "",
			data: { action: 'getProducts', categoryId: categoryId },
			success: function( data ) {
				$('.products_select_type3_products').text('');

				if (data.length > 0)
				{
					$.each(data, function(key, value) {
					$('div.products_select_type3_products').append('<div class="col-md-4">'
						+ '<div class="checkbox">'
						+ '<label for="product' + value.id_product + '">'
						+ '<input type="checkbox" id="product' + value.id_product + '" name="product[]" value="' + value.id_product + '">'
						+ ' ' + value.name + '</label>'
						+ '</div>'
						+ '</div>');
					});
				}
				else
					alert('{l s='There is no products in this category' mod='wtbulkcombinations' js='1'}')
			},
			dataType: 'json'
		});
	}
	function initDeleteSelected()
	{
		$("#attributes-list-result .option").unbind('click').click(function() {
			if ($(this).parent().find('.option').length == 1)
			{
				$(this).parent().remove();
			}
			else
			{
				$(this).remove();
			}

			initDeleteSelected();
		});
	}
	$(function() {
		$( "#attributes-list .option").click(function(){
			var groupDivId = "#attributes-list-result .group" + $(this).data('group')
			var groupDiv = $(groupDivId);

			if (!groupDiv.length)
			{
				$("#attributes-list-result").append('<div class="group' + $(this).data('group') + '">'
					+ '<div class="title">' + $(this).data('group-name') + '</div></div>');

				groupDiv = $(groupDivId);
			}

			if (groupDiv.find('div.option[data-value=' + $(this).data('value') + ']').length == 0)
			{
				var clone = $(this).clone();
				if (clone.find('input[type=hidden]').length > 0)
					clone.find('input[type=hidden]').attr('name', 'attributes[]');
						
				groupDiv.append(clone);
				initDeleteSelected();
			}
		});
		{if isset($show_combinations_form) && $show_combinations_form}
		$('#selectProductsForCombinations input[name=products_select_type]').change(function(){

			$('div.products_select_type3_products input:checkbox').attr('checked', false);
			$('#categories-select-tree input:checkbox').attr('checked', false);
			$('#categories-select-tree .tree-selected').removeClass('tree-selected');

			$('#selectProductsForCombinations .products_select_type').hide();
			$('#selectProductsForCombinations .' + $(this).attr('id')).show();
		});

		$('#butAddMoreCombinations').click(function(){
			$('#attributesSelect').slideDown();
		});
		{/if}
  	});
  	</script>
</form>