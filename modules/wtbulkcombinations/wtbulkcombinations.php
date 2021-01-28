<?php
/**
 * 2016 WeeTeam
 *
 * @author    WeeTeam <info@weeteam.net>
 * @copyright 2016 WeeTeam
 * @license   http://www.gnu.org/philosophy/categories.html (Shareware)
 */

class WtBulkCombinations extends Module
{
	public $short_name = 'WBC';

	private $attributes = array();
	private $attribute_js = array();
	private $attribute_groups = array();
	private $attributes_names = array();

	private $errors = array();

	public function __construct()
	{
		$this->version = '1.0.0';
		$this->name = 'wtbulkcombinations';
		$this->displayName = $this->l('Bulk combintations creation');
		$this->description = $this->l('Bulk combintations creation');
		$this->author = 'Weeteam';
		$this->tab = 'quick_bulk_update';
		$this->module_key = 'd90d2e819fdc10d4eadc24147183b04c';
		parent::__construct();
		$this->bootstrap = true;
	}

	private function initAttributes()
	{
		$this->attributes = Attribute::getAttributes($this->context->language->id, true);
		$this->attribute_js = array();

		foreach ($this->attributes as $attribute)
			$this->attribute_js[$attribute['id_attribute_group']][$attribute['id_attribute']] = $attribute['name'];

		$this->attribute_groups = AttributeGroup::getAttributesGroups($this->context->language->id);

		foreach ($this->attributes as $attribute)
		{
			$this->attributes_names[$attribute['id_attribute']] = array(
				'name' => $attribute['name'],
				'group_name' => $attribute['attribute_group']
			);
		}
	}

	public function getContent()
	{
		$this->context->controller->addCSS($this->getPathUri().'views/css/'.$this->name.'.css', 'all');
		$this->initAttributes();

		if (($action = Tools::getValue('action')) && $action == 'getProducts')
		{
			// List of products
			$category = new Category((int)Tools::getValue('categoryId'), (int)Context::getContext()->language->id);
			$a_products = $category->getProducts((int)Context::getContext()->language->id, 1, 1000000, 'position');

			echo Tools::jsonEncode($a_products);
			die;
		}

		$success = array();
		$show_combinations_form = false;
		$category_tree = '';
		$category_select_tree = '';

		// Create combinations for selected products
		if (Tools::isSubmit('submit-combination'))
		{
			$a_product_id = Tools::getValue('product');
			$a_category_id = Tools::getValue('category');

			if (($products_select_type = Tools::getValue('products_select_type')) && $products_select_type == 1)
			{
				$a_all_products = Product::getSimpleProducts((int)Context::getContext()->language->id);

				$a_product_id = array();

				foreach ($a_all_products as $a_product)
					$a_product_id[] = $a_product['id_product'];
			}

			if ((!$a_product_id || !count($a_product_id)) && (!$a_category_id || !count($a_category_id)))
				$this->errors[] = $this->l('Please select at least one product or category to process');
			else
			{
				$a_combinations = Tools::getValue('combination');
				$a_new_combinations = array();

				foreach ($a_combinations as $a_combination)
					if (isset($a_combination['combination'])
						&& is_array($a_combination['combination'])
						&& isset($a_combination['price'])
						&& isset($a_combination['quantity']))
					{
						$a_new_combinations[] = array(
							'combination' => $a_combination['combination'],
							'price' => (float)$a_combination['price'],
							'quantity' => (int)$a_combination['quantity'],
							'price_impact' => '1' // 1 = plus, -1 = minus, 0 = no impact
						);
					}

				// Get product IDs from categories
				if ($a_category_id && (!$a_product_id || !count($a_product_id)))
				{
					$a_product_id = array();

					foreach ($a_category_id as $i_category_id)
					{
						// List of products
						$category = new Category((int)$i_category_id, (int)Context::getContext()->language->id);
						$a_products = $category->getProducts((int)Context::getContext()->language->id, 1, 1000000, 'position');

						if ($a_products && count($a_products))
							foreach ($a_products as $a_product)
								$a_product_id[] = $a_product['id_product'];
					}

					$a_product_id = array_unique($a_product_id);
				}

				// Recreation process
				foreach ($a_product_id as $i_product_id)
				{
					$this->createCombinationsForProduct (
						(int)$i_product_id,
						$a_new_combinations,
						Tools::getValue('remove_previous')
					);
				}
			}

			if (!count($this->errors))
				$success[] = $this->l('Combinations were successfully created');
			else
				$show_combinations_form = true;
		}

		$a_ready_combinations = Tools::getValue('combination', array());
		$a_selected_attributes = Tools::getValue('attributes', array());
		$a_selected_attributes_groups = array();

		// Process deletion of combination
		if (Tools::isSubmit('submit-delete-combination'))
		{
			$i_combination_remove = (int)Tools::getValue('submit-delete-combination');

			if (isset($a_ready_combinations[$i_combination_remove]))
			{
				unset($a_ready_combinations[$i_combination_remove]);
				$a_ready_combinations = array_values($a_ready_combinations);
			}
		}

		// Remove all combinations
		if (Tools::isSubmit('submit-delete-all'))
			$a_ready_combinations = array();

		// Prccess combinations display
		if (Tools::isSubmit('submit-attributes') && count($a_selected_attributes) == 0)
			$this->errors[] = $this->l('Please select at least one attribute to create combination');
		elseif (($a_selected_attributes || $a_ready_combinations) && count($success) == 0)
		{
			foreach ($this->attributes as $attribute)
			{
				foreach ($a_selected_attributes as $i_selected_attribute)
				{
					if ($i_selected_attribute == $attribute['id_attribute'])
					{
						$id_group = $attribute['id_attribute_group'];

						if (!isset($a_selected_attributes_groups[$id_group]))
							$a_selected_attributes_groups[$id_group] = array();

						$a_selected_attributes_groups[$id_group][] = $i_selected_attribute;
					}
				}
			}

			// Mixed combinations
			$a_combinations = $this->mixCombinations(array_values($a_selected_attributes_groups));

			// Remove duplicates
			foreach ($a_combinations as $i_key => $a_combination)
			{
				foreach ($a_ready_combinations as $a_ready_combination)
				{
					if (count(array_diff($a_combination, $a_ready_combination['combination'])) == 0)
						unset($a_combinations[$i_key]);
				}
			}

			// Add to the already created
			foreach ($a_combinations as $a_combination)
			{
				$a_ready_combinations[] = array(
					'combination' => $a_combination,
					'price' => '0',
					'quantity' => '0',
				);
			}

			// Parse template
			$this->smarty->assign('a_ready_combinations', $a_ready_combinations);
			$this->smarty->assign('attributes_names', $this->attributes_names);

			// Generate category for products selection tree
			$tree = new HelperTreeCategories('categories-tree', $this->l('Filter by category'));
			$tree->setAttribute('is_category_filter', (bool)Category::getRootCategory()->id);
			$tree->setInputName('id-category-product');
			$tree->setRootCategory(Category::getRootCategory()->id);
			$category_tree = $tree->render();

			// Generate category selection tree
			$tree = new HelperTreeCategories('categories-select-tree', $this->l('Select category'));
			$tree->setAttribute('is_category_filter', (bool)Category::getRootCategory()->id);
			$tree->setInputName('category');
			$tree->setRootCategory(Category::getRootCategory()->id);
			$tree->setUseCheckBox(true);
			$category_select_tree = $tree->render();

			$show_combinations_form = true;
		}

		$this->smarty->assign('show_combinations_form', $show_combinations_form);
		$this->smarty->assign('attribute_groups', $this->attribute_groups);
		$this->smarty->assign('attribute_js', $this->attribute_js);
		$this->smarty->assign('errors', $this->errors);
		$this->smarty->assign('success', $success);

		if ($show_combinations_form)
			return $this->display(__FILE__, 'views/templates/admin/form_top.tpl').$category_select_tree
				.$this->display(__FILE__, 'views/templates/admin/form_mid.tpl').$category_tree
				.$this->display(__FILE__, 'views/templates/admin/form_btm.tpl');
		else
			return $this->display(__FILE__, 'views/templates/admin/form_top.tpl')
				.$this->display(__FILE__, 'views/templates/admin/form_mid.tpl')
				.$this->display(__FILE__, 'views/templates/admin/form_btm.tpl');
	}

	public function install()
	{
		if (!parent::install())
			return false;
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

	protected function mixCombinations($list)
	{
		if (count($list) <= 1)
			return count($list) ? array_map(create_function('$v', 'return (array($v));'), $list[0]) : $list;

		$res = array();
		$first = array_pop($list);

		foreach ($first as $attribute)
		{
			$tab = $this->mixCombinations($list);

			foreach ($tab as $to_add)
				$res[] = is_array($to_add) ? array_merge($to_add, array($attribute)) : array($to_add, $attribute);
		}

		return $res;
	}

	private function createCombinationsForProduct($i_product_id, $a_new_combinations, $b_remove_previous_combinations = false)
	{
		$product = new Product($i_product_id, true, $this->context->language->id);

		// Clear previous combinations
		if ($b_remove_previous_combinations && $product->hasAttributes())
		{
			$a_product_attributes_ids = array();
			$a_product_attributes = $product->getAttributeCombinations($this->context->language->id);

			foreach ($a_product_attributes as $a_product_attribute)
				$a_product_attributes_ids[] = $a_product_attribute['id_product_attribute'];

			$a_product_attributes_ids = array_unique($a_product_attributes_ids);

			foreach ($a_product_attributes_ids as $a_product_attributes_id)
			{
				if (($depends_on_stock = StockAvailable::dependsOnStock($product->id))
					&& StockAvailable::getQuantityAvailableByProduct($product->id, $a_product_attributes_id))
					$this->errors[] = $this->l('For product #').$product->id
						.$this->l(' it is not possible to delete a combination while it still has some quantities in the Advanced Stock Management. You must delete its stock first.');
				else
				{
					$product->deleteAttributeCombination((int)$a_product_attributes_id);
					$product->checkDefaultAttributes();

					Tools::clearColorListCache((int)$product->id);

					if (!$product->hasAttributes())
					{
						$product->cache_default_attribute = 0;
						$product->update();
					}
					else
						Product::updateDefaultAttribute($product->id);

					if ($depends_on_stock && !Stock::deleteStockByIds($product->id, $a_product_attributes_id))
						$this->errors[] = $this->l('For product #').$product->id.$this->l(' error while deleting the stock');
				}
			}
		}

		$a_id_product_attributes = array();

		// Add combinations
		foreach ($a_new_combinations as $a_new_combination)
		{
			// Create new combination of attributes
			$attribute_combination_list = $a_new_combination['combination'];
			$attribute_wholesale_price = '0';
			$attribute_price = $a_new_combination['price'];
			$attribute_price_impact = $a_new_combination['price_impact'];
			$attribute_weight = '0.00';
			$attribute_weight_impact = '0';
			$attribute_unity = '0.00';
			$attribute_unit_impact = '0';
			$attribute_ecotax = '0';
			// $_POST['id_image_attr'] = '0';
			$attribute_reference = '';
			$attribute_ean13 = '';
			// $_POST['attribute_default'] = '0';
			// $_POST['attribute_location'] = '0';
			$attribute_upc = '';
			$attribute_minimal_quantity = '1';
			$available_date_attribute = '0000-00-00';

			if ($product->productAttributeExists($attribute_combination_list))
			{
				$this->errors[] = $this->l('For product #').$product->id
					.Tools::displayError(' combination from attributes #')
					.implode(', ', $attribute_combination_list)
					.Tools::displayError(' already exists.');
			}
			else
			{
				$id_product_attribute = $product->addCombinationEntity(
					$attribute_wholesale_price,
					$attribute_price * $attribute_price_impact,
					$attribute_weight * $attribute_weight_impact,
					$attribute_unity * $attribute_unit_impact,
					$attribute_ecotax,
					0,
					false, // Tools::getValue('id_image_attr'),
					$attribute_reference,
					null,
					$attribute_ean13,
					false, // Tools::getValue('attribute_default')
					false, // Tools::getValue('attribute_location'),
					$attribute_upc,
					$attribute_minimal_quantity,
					array(),
					$available_date_attribute
				);

				StockAvailable::setProductDependsOnStock((int)$product->id, $product->depends_on_stock, null, (int)$id_product_attribute);
				StockAvailable::setProductOutOfStock((int)$product->id, $product->out_of_stock, null, (int)$id_product_attribute);

				$a_id_product_attributes[] = $id_product_attribute;

				// Init cimbination
				$combination = new Combination((int)$id_product_attribute);
				$combination->setAttributes($attribute_combination_list);

				// images could be deleted before
				// $id_images = false; // Tools::getValue('id_image_attr');
				$product->checkDefaultAttributes();

				/*
				if (Tools::getValue('attribute_default')) {
					Product::updateDefaultAttribute((int)$product->id);

					if (isset($id_product_attribute)) {
						$product->cache_default_attribute = (int)$id_product_attribute;
					}

					if ($available_date = Tools::getValue('available_date_attribute')) {
						$product->setAvailableDate($available_date);
					} else {
						$product->setAvailableDate();
					}
				}
				*/

				// Set just created combination quantity
				StockAvailable::setQuantity($product->id, (int)$id_product_attribute, (int)$a_new_combination['quantity']);
				Hook::exec('actionProductUpdate', array('id_product' => (int)$product->id, 'product' => $product));
			}
		}
	}
}