<?php
/**
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once (dirname(__FILE__).'/url/WTLink.php');
require_once (dirname(__FILE__).'/classes/WTPost.php');
require_once (dirname(__FILE__).'/classes/WTCategory.php');
require_once (dirname(__FILE__).'/classes/WTComment.php');
require_once (dirname(__FILE__).'/classes/WTTag.php');
include_once(dirname(__FILE__).'/sql/SampleData.php');
class WTBlog extends Module
{
	private $tabParentClass = null;
	private $tabClassBlog = 'AdminWTBlog';
	private $tabNameBlog = null;
	private $_html_categories = '';
	private $psVersion = false;
	private $classTab = array();
	private $wtLink;
	private $html = '';
	private $imageType;
	private $myHook = array('displayLeftColumn');
	private $myHookRegister = array('footer', 'displayLeftColumn', 'displayRightColumn');
	public function __construct()
	{
		$this->name = 'wtblog';
		$this->displayName = $this->l('WT Blog');
		$this->description = $this->l('WT Blog Module');
		$this->bootstrap = true;
		$this->version = '1.6.0';
		$this->author = 'waterthemes';
		$this->controllers = array('categoryPost', 'post', 'rss', 'tag');
		$this->tab = 'others';
		$this->wtLink = new WTLink();
		$this->imageType = 'jpg';
		parent::__construct();
	}
	/*install*/
	public function existsTab($tabClass)
	{
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT id_tab AS id 
		FROM `'._DB_PREFIX_.'tab` t 
		WHERE LOWER(t.`class_name`) = \''.pSQL($tabClass).'\'');
		if (count($result) == 0) 
			return false;
		return true;
	}
	public function installDb()
	{
		/* create table */
		$sql = array();
		require_once(dirname(__FILE__).'/sql/install.php');
		foreach ($sql as $sq)
			if (!Db::getInstance()->Execute($sq))
				return false;
		/* init category home */
		$id_shop_default = (int)Configuration::get('PS_SHOP_DEFAULT');
		$sql = ' INSERT INTO '._DB_PREFIX_.'wt_blog_category(id_wt_blog_category,category_parent, level_depth, id_shop_default,active, position, allow_comment) VALUES(1, 0, 0, '.$id_shop_default.', 1, 0, 1) ';
		Db::getInstance()->execute($sql);
		$shops = Shop::getShops();
		foreach ($shops as $shop)
		{
			$sql = ' INSERT INTO '._DB_PREFIX_.'wt_blog_category_shop(id_wt_blog_category,id_shop,position) VALUES(1,'.$shop['id_shop'].',0) ';
			Db::getInstance()->execute($sql);
			foreach (Language::getLanguages(false) as $lang)
			{
				$sql = ' INSERT INTO '._DB_PREFIX_.'wt_blog_category_lang(id_wt_blog_category, id_lang, id_shop, name,link_rewrite) VALUES(1, '.$lang['id_lang'].', '.$shop['id_shop'].' , "Blog Home", "blog-home") ';
				Db::getInstance()->execute($sql);
			}
		}
		$sample_data = new SampleDataBlog();
		$sample_data->initData();
		$sample_data->installConfiguration();
		return true;
	}
	public function deleteImage()
	{
		#category
		$save_path = _PS_MODULE_DIR_.'wtblog/views/img/media/categories/';
		#folder src
		$images = glob($save_path.'src/'.'*.'.$this->imageType.'');
		foreach ($images as $image)
			unlink($save_path.'src/'.basename($image));
		#folder cache
		$images = glob($save_path.'cache/'.'*.'.$this->imageType.'');
		$imageNotDel = array('');
		foreach ($images as $image)
		{
			$pos = strpos(basename($image), 'no_image_');
			if ($pos === false)
				unlink($save_path.'cache/'.basename($image));
		}
		#post
		$save_path = _PS_MODULE_DIR_.'wtblog/views/img/media/posts/';
		#folder src
		$images = glob($save_path.'src/'.'*.'.$this->imageType.'');
		foreach ($images as $image)
			unlink($save_path.'src/'.basename($image));
		#folder cache
		$images = glob($save_path.'cache/'.'*.'.$this->imageType.'');
		foreach ($images as $image)
		{
			$pos = strpos(basename($image), 'no_image_');
			if ($pos === false)
				unlink($save_path.'cache/'.basename($image));
		}
	}
	
	public function installTabBlog()
	{
		if (!$this->existsTab('AdminWTBlog'))
			if (!$this->addTab('WT Blog', 'AdminWTBlog', 0))
				return false;
		if (!$this->existsTab('AdminWTCategory'))
			if (!$this->addTab('Blog Category', 'AdminWTCategory', $this->getIdTabFromClassName('AdminWTBlog')))
				return false;
		if (!$this->existsTab('AdminWTPost'))
			if (!$this->addTab('Blog Post', 'AdminWTPost', $this->getIdTabFromClassName('AdminWTBlog')))
				return false;
		if (!$this->existsTab('AdminWTComment'))
			if (!$this->addTab('Blog Comment', 'AdminWTComment', $this->getIdTabFromClassName('AdminWTBlog')))
				return false;
		if (!$this->existsTab('AdminWTTags'))
			if (!$this->addTab('Blog Tag', 'AdminWTTags', $this->getIdTabFromClassName('AdminWTBlog')))
				return false;
		if (!$this->existsTab('AdminCSSetting'))
			if (!$this->addTab('Blog Setting URL', 'AdminCSSetting', $this->getIdTabFromClassName('AdminWTBlog')))
				return false;
		return true;
	}
	public function getIdTabFromClassName($tabName)
	{
		$sql = 'SELECT id_tab FROM '._DB_PREFIX_.'tab WHERE class_name="'.$tabName.'"';
		$tab = Db::getInstance()->getRow($sql);
		return $tab['id_tab'];
	}
	private function addTab($tabName, $tabClass, $id_parent)
	{
		$tab = new Tab();	
		$langs = Language::getLanguages();
		foreach ($langs as $lang)
			$tab->name[$lang['id_lang']] = $tabName;
		$tab->class_name = $tabClass;
		$tab->module = $this->name;
		$tab->id_parent = $id_parent;
		if (!$tab->save())
			return false;
		return true;
	}
	public function install()
	{
		if (!parent::install())
			return false;
		if (!$this->installDb() || !$this->installTabBlog() || !$this->registerHook('header') || !$this->registerHook('moduleRoutes') || !$this->registerHook('actionShopDataDuplication') || !$this->registerHook('actionShopDataDuplication') || !$this->registerHook('displayBackOfficeHeader'))
				return false;
		foreach ($this->myHookRegister as $hook)
			if (!$this->registerHook($hook))
				return false;
		$this->deleteImage();
		return true;
	}
	/*end install*/
	/*uninstall*/
	public function uninstallDb()
	{
		$sql = array();
		require_once(dirname(__FILE__).'/sql/uninstall.php');
		foreach ($sql as $sq)
			if (!Db::getInstance()->Execute($sq))
				return false;
		return true;
	}
	public function uninstallConfiguration()
	{
		Configuration::deleteByName('WT_SHOW_BLOCK_CATEGORY');
		Configuration::deleteByName('WT_DISPLAY_CATEGORY');
		Configuration::deleteByName('BLOCK_CATEG_DHTML');
		Configuration::deleteByName('WT_SHOW_BLOCK_TAG');
		Configuration::deleteByName('WT_DISPLAY_TAG'); 
		Configuration::deleteByName('WT_NUMBER_TAG_DISPLAYED');
		Configuration::deleteByName('WT_COMMENTS_VALIDATE');		
		Configuration::deleteByName('WT_ALLOW_COMMENTS_BY_GUESTS');
		Configuration::deleteByName('WT_DISPLAY_CAPTCHA');
		Configuration::deleteByName('WT_IMAGE_SIZE_RELATED_POSTS');
		Configuration::deleteByName('WT_IMAGE_SIZE_RELATED_PRODUCT');
		Configuration::deleteByName('WT_ALLOW_CATEGORY_IMAGE');
		Configuration::deleteByName('WT_ALLOW_CATEGORY_DESCRIPTION');
		Configuration::deleteByName('WT_SHOW_BLOCK_LASTEST');
		Configuration::deleteByName('WT_LASTEST_POST');
		Configuration::deleteByName('WT_OP_LASTEST_POST');
		Configuration::deleteByName('WT_TBEP_SHOW');
		Configuration::deleteByName('WT_IMEP_SHOW');
		Configuration::deleteByName('WT_IMIPD_SHOW');
		Configuration::deleteByName('WT_IMEP_LIST_SHOW');
		Configuration::deleteByName('WT_COLP_MAXIMUM');
		Configuration::deleteByName('WT_B_SUMMARY_CHARACTER_COUNT');
		Configuration::deleteByName('WT_POSTS_PER_PAGE');
		Configuration::deleteByName('WT_NUM_RELATED_POSTS');
		Configuration::deleteByName('WT_NUM_RELATED_PRODUCTS');
		Configuration::deleteByName('WT_IMG_SMALL_SIZE');
		Configuration::deleteByName('WT_IMG_SMALL_H_SIZE');
		Configuration::deleteByName('WT_IMG_MEDIUM_SIZE');
		Configuration::deleteByName('WT_IMG_MEDIUM_H_SIZE');
		Configuration::deleteByName('WT_IMG_LARGE_SIZE');
		Configuration::deleteByName('WT_IMG_LARGE_H_SIZE');
		Configuration::deleteByName('WT_IMG_CATEGORY_SIZE');
		Configuration::deleteByName('WT_IMG_CATEGORY_H_SIZE');
		Configuration::deleteByName('WT_POSITION_CURRENT_COMMENT');
		Configuration::deleteByName('WT_NUMBER_CURRENT_COMMENT');
		Configuration::deleteByName('WT_COMMENT_SIZE_IMAGE_POST');
		Configuration::deleteByName('WT_NUMBER_COMMENT_DETAIL');
		Configuration::deleteByName('BLOCK_CATEG_DISPLAY_PAGE');
		Configuration::deleteByName('WT_TAG_DISPLAY_PAGE');
		Configuration::deleteByName('LASTEST_POST_DISPLAY_PAGE');
		Configuration::deleteByName('CURRENT_COMMENT_DISPLAY_PAGE');
		Configuration::deleteByName('WT_MAIN_BLOG_URL');
		return true;
	}
	private function removeTab($tab_class)
	{
		$id_tab = Tab::getIdFromClassName($tab_class);
		if ($id_tab != 0)
		{
			$tab = new Tab($id_tab);
			$tab->delete();
			return true;
		}
		return false;
	}
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		if (!$this->removeTab('AdminWTBlog'))
			return false;
		if (!$this->removeTab('AdminWTCategory'))
			return false;
		if (!$this->removeTab('AdminWTPost'))
			return false;
		if (!$this->removeTab('AdminWTComment'))
			return false;
		if (!$this->removeTab('AdminWTTags'))
			return false;
		if (!$this->uninstallDb() || !$this->uninstallConfiguration())
			return false;
		$this->deleteImage();
		return true;
	}
	public function getCategories($id_lang, $id_shop)
	{
		$sql = 'SELECT * FROM '._DB_PREFIX_.'wt_blog_category a
				LEFT JOIN '._DB_PREFIX_.'wt_blog_category_lang b
				ON a.id_wt_blog_category = b.id_wt_blog_category AND b.id_shop = '.$id_shop.' AND b.id_lang='.$id_lang.'
				LEFT JOIN '._DB_PREFIX_.'wt_blog_category_shop c
				ON ( a.id_wt_blog_category = c.id_wt_blog_category AND c.id_shop = '.$id_shop.')
				WHERE a.active=1 AND b.id_lang='.$id_lang.' AND c.id_shop = '.$id_shop.'
				ORDER BY c.position ASC
				';
		$data = Db::getInstance()->ExecuteS($sql);
		return $data;
	}
	public function imageResizeThumb($src_path, $des_path, $new_width, $new_height)
	{
		ImageManager::resize($src_path, $des_path, $new_width, $new_height);
	}
	public function postProcess()
	{
		$id_lang = $this->context->language->id;
		$id_shop = $this->context->shop->id;
		if (Tools::getValue('submitConfiguration'))
		{
			$WT_COMMENTS_VALIDATE = Tools::getValue('WT_COMMENTS_VALIDATE');
			$WT_ALLOW_COMMENTS_BY_GUESTS = Tools::getValue('WT_ALLOW_COMMENTS_BY_GUESTS');
			$WT_DISPLAY_CAPTCHA = Tools::getValue('WT_DISPLAY_CAPTCHA');
			$WT_ALLOW_CATEGORY_IMAGE = Tools::getValue('WT_ALLOW_CATEGORY_IMAGE');
			$WT_ALLOW_CATEGORY_DESCRIPTION = Tools::getValue('WT_ALLOW_CATEGORY_DESCRIPTION');
			$WT_SHOW_BLOCK_CATEGORY = Tools::getValue('WT_SHOW_BLOCK_CATEGORY');
			$WT_DISPLAY_CATEGORY = Tools::getValue('WT_DISPLAY_CATEGORY'); 
			$BLOCK_CATEG_DHTML = Tools::getValue('BLOCK_CATEG_DHTML');
			$WT_SHOW_BLOCK_TAG = Tools::getValue('WT_SHOW_BLOCK_TAG');
			$WT_DISPLAY_TAG = Tools::getValue('WT_DISPLAY_TAG');
			$WT_NUMBER_TAG_DISPLAYED = Tools::getValue('WT_NUMBER_TAG_DISPLAYED');
			$WT_OP_LASTEST_POST = Tools::getValue('WT_OP_LASTEST_POST');
			$WT_SHOW_BLOCK_LASTEST = Tools::getValue('WT_SHOW_BLOCK_LASTEST');
			$WT_LASTEST_POST = Tools::getValue('WT_LASTEST_POST'); 
			$WT_TBEP_SHOW = Tools::getValue('WT_TBEP_SHOW');
			$WT_IMEP_SHOW = Tools::getValue('WT_IMEP_SHOW');
			$WT_IMIPD_SHOW = Tools::getValue('WT_IMIPD_SHOW');
			$WT_IMEP_LIST_SHOW = Tools::getValue('WT_IMEP_LIST_SHOW');
			$WT_COLP_MAXIMUM = Tools::getValue('WT_COLP_MAXIMUM');
			$WT_B_SUMMARY_CHARACTER_COUNT = Tools::getValue('WT_B_SUMMARY_CHARACTER_COUNT');
			$WT_POSTS_PER_PAGE = Tools::getValue('WT_POSTS_PER_PAGE');
			$WT_NUM_RELATED_POSTS = Tools::getValue('WT_NUM_RELATED_POSTS');
			$WT_NUM_RELATED_PRODUCTS = Tools::getValue('WT_NUM_RELATED_PRODUCTS');
			$WT_IMG_SMALL_SIZE = Tools::getValue('WT_IMG_SMALL_SIZE');
			$WT_IMG_SMALL_H_SIZE = Tools::getValue('WT_IMG_SMALL_H_SIZE');
			$WT_IMG_MEDIUM_SIZE = Tools::getValue('WT_IMG_MEDIUM_SIZE');
			$WT_IMG_MEDIUM_H_SIZE = Tools::getValue('WT_IMG_MEDIUM_H_SIZE');
			$WT_IMG_LARGE_SIZE = Tools::getValue('WT_IMG_LARGE_SIZE');
			$WT_IMG_LARGE_H_SIZE = Tools::getValue('WT_IMG_LARGE_H_SIZE');
			$WT_IMG_CATEGORY_SIZE = Tools::getValue('WT_IMG_CATEGORY_SIZE');
			$WT_IMG_CATEGORY_H_SIZE = Tools::getValue('WT_IMG_CATEGORY_H_SIZE');
			$WT_POSITION_CURRENT_COMMENT = Tools::getValue('WT_POSITION_CURRENT_COMMENT');
			$WT_NUMBER_CURRENT_COMMENT = Tools::getValue('WT_NUMBER_CURRENT_COMMENT');
			$WT_COMMENT_SIZE_IMAGE_POST = Tools::getValue('WT_COMMENT_SIZE_IMAGE_POST');
			$WT_NUMBER_COMMENT_DETAIL = Tools::getValue('WT_NUMBER_COMMENT_DETAIL');
			$WT_IMAGE_SIZE_RELATED_POSTS = Tools::getValue('WT_IMAGE_SIZE_RELATED_POSTS');
			$WT_IMAGE_SIZE_RELATED_PRODUCT = Tools::getValue('WT_IMAGE_SIZE_RELATED_PRODUCT');
			$CATEGORY_RSS_NUMBER = Tools::getValue('CATEGORY_RSS_NUMBER');
			$BLOCK_CATEG_DISPLAY_PAGE = Tools::getValue('BLOCK_CATEG_DISPLAY_PAGE');
			$WT_TAG_DISPLAY_PAGE = Tools::getValue('WT_TAG_DISPLAY_PAGE');
			$LASTEST_POST_DISPLAY_PAGE = Tools::getValue('LASTEST_POST_DISPLAY_PAGE');
			$CURRENT_COMMENT_DISPLAY_PAGE = Tools::getValue('CURRENT_COMMENT_DISPLAY_PAGE');
			$WT_MAIN_BLOG_URL = Tools::getValue('WT_MAIN_BLOG_URL');
			/* general image size */
			$src_no_image = _PS_MODULE_DIR_.'wtblog/views/img/media/posts/';
			$src_path = _PS_MODULE_DIR_.'wtblog/views/img/media/posts/src/';
			$des_path = _PS_MODULE_DIR_.'wtblog/views/img/media/posts/cache/';
			$postObj = new WTPost();
			$src_path_cat = _PS_MODULE_DIR_.'wtblog/views/img/media/categories/src/';
			$des_path_cat = _PS_MODULE_DIR_.'wtblog/views/img/media/categories/cache/';
			$catObj = new WTCategory();
			$cats = $catObj->getIdAllCat($id_lang, $id_shop);
			$posts = $postObj->getIdAllPost($id_lang, $id_shop);
			if ($WT_IMG_SMALL_SIZE != Configuration::get('WT_IMG_SMALL_SIZE') 
				|| $WT_IMG_SMALL_H_SIZE != Configuration::get('WT_IMG_SMALL_H_SIZE') 
				|| $WT_IMG_SMALL_SIZE != Configuration::get('WT_IMG_MEDIUM_SIZE') 
				|| $WT_IMG_SMALL_H_SIZE != Configuration::get('WT_IMG_MEDIUM_H_SIZE') 
				|| $WT_IMG_SMALL_SIZE != Configuration::get('WT_IMG_LARGE_SIZE') 
				|| $WT_IMG_SMALL_H_SIZE != Configuration::get('WT_IMG_LARGE_H_SIZE'))
			{
				foreach ($posts as $post)
				{
					$id = $post['id_wt_blog_post'];
					if ($WT_IMG_SMALL_SIZE != Configuration::get('WT_IMG_SMALL_SIZE') 
					|| $WT_IMG_SMALL_H_SIZE != Configuration::get('WT_IMG_SMALL_H_SIZE'))
						$this->imageResizeThumb($src_path.$id.'.'.$this->imageType, $des_path.$id.'_'.$id_shop.'_small.'.$this->imageType, $WT_IMG_SMALL_SIZE, $WT_IMG_SMALL_H_SIZE);
					if ($WT_IMG_MEDIUM_SIZE != Configuration::get('WT_IMG_MEDIUM_SIZE') 
					|| $WT_IMG_MEDIUM_H_SIZE != Configuration::get('WT_IMG_MEDIUM_H_SIZE'))
						$this->imageResizeThumb($src_path.$id.'.'.$this->imageType, $des_path.$id.'_'.$id_shop.'_medium.'.$this->imageType, $WT_IMG_MEDIUM_SIZE, $WT_IMG_MEDIUM_H_SIZE);
					if ($WT_IMG_LARGE_SIZE != Configuration::get('WT_IMG_LARGE_SIZE') 
					|| $WT_IMG_LARGE_H_SIZE != Configuration::get('WT_IMG_LARGE_H_SIZE'))
						$this->imageResizeThumb($src_path.$id.'.'.$this->imageType, $des_path.$id.'_'.$id_shop.'_large.'.$this->imageType, $WT_IMG_LARGE_SIZE, $WT_IMG_LARGE_H_SIZE);
				}
				$this->imageResizeThumb($src_no_image.'no_image.jpg', $des_path.'no_image_small.'.$this->imageType, $WT_IMG_SMALL_SIZE, $WT_IMG_SMALL_H_SIZE);
				$this->imageResizeThumb($src_no_image.'no_image.jpg', $des_path.'no_image_medium.'.$this->imageType, $WT_IMG_MEDIUM_SIZE, $WT_IMG_MEDIUM_H_SIZE);
				$this->imageResizeThumb($src_no_image.'no_image.jpg', $des_path.'no_image_large.'.$this->imageType, $WT_IMG_LARGE_SIZE, $WT_IMG_LARGE_H_SIZE);
			}
			if ($WT_IMG_CATEGORY_SIZE != Configuration::get('WT_IMG_CATEGORY_SIZE') || $WT_IMG_CATEGORY_H_SIZE != Configuration::get('WT_IMG_CATEGORY_H_SIZE')) 
				foreach ($cats as $cat)
				{
					$id = $cat['id_wt_blog_category'];
						$this->imageResizeThumb($src_path_cat.$id.'.'.$this->imageType, $des_path_cat.$id.'_'.$id_shop.'_category.'.$this->imageType, $WT_IMG_CATEGORY_SIZE, $WT_IMG_CATEGORY_H_SIZE);
				}
				
			Configuration::updateValue('WT_COMMENTS_VALIDATE', $WT_COMMENTS_VALIDATE);
			Configuration::updateValue('WT_ALLOW_COMMENTS_BY_GUESTS', $WT_ALLOW_COMMENTS_BY_GUESTS);
			Configuration::updateValue('WT_DISPLAY_CAPTCHA', $WT_DISPLAY_CAPTCHA);
			Configuration::updateValue('WT_ALLOW_CATEGORY_IMAGE', $WT_ALLOW_CATEGORY_IMAGE);		
			Configuration::updateValue('WT_ALLOW_CATEGORY_DESCRIPTION', $WT_ALLOW_CATEGORY_DESCRIPTION);		
			Configuration::updateValue('WT_SHOW_BLOCK_CATEGORY', $WT_SHOW_BLOCK_CATEGORY);
			Configuration::updateValue('WT_DISPLAY_CATEGORY', $WT_DISPLAY_CATEGORY);
			Configuration::updateValue('BLOCK_CATEG_DHTML', $BLOCK_CATEG_DHTML);
			Configuration::updateValue('WT_SHOW_BLOCK_TAG', $WT_SHOW_BLOCK_TAG);
			Configuration::updateValue('WT_DISPLAY_TAG', $WT_DISPLAY_TAG);
			Configuration::updateValue('WT_SHOW_BLOCK_LASTEST', $WT_SHOW_BLOCK_LASTEST);
			Configuration::updateValue('WT_LASTEST_POST', $WT_LASTEST_POST);
			Configuration::updateValue('WT_TBEP_SHOW', $WT_TBEP_SHOW);
			Configuration::updateValue('WT_IMEP_LIST_SHOW', $WT_IMEP_LIST_SHOW);
			Configuration::updateValue('WT_IMEP_SHOW', $WT_IMEP_SHOW);
			Configuration::updateValue('WT_IMIPD_SHOW', $WT_IMIPD_SHOW);
			Configuration::updateValue('WT_POSITION_CURRENT_COMMENT', $WT_POSITION_CURRENT_COMMENT);
			Configuration::updateValue('WT_COMMENT_SIZE_IMAGE_POST', $WT_COMMENT_SIZE_IMAGE_POST);
			Configuration::updateValue('WT_IMAGE_SIZE_RELATED_POSTS', $WT_IMAGE_SIZE_RELATED_POSTS);
			Configuration::updateValue('WT_IMAGE_SIZE_RELATED_PRODUCT', $WT_IMAGE_SIZE_RELATED_PRODUCT);
			Configuration::updateValue('CATEGORY_RSS_NUMBER', $CATEGORY_RSS_NUMBER);
			Configuration::updateValue('BLOCK_CATEG_DISPLAY_PAGE', $BLOCK_CATEG_DISPLAY_PAGE);
			Configuration::updateValue('WT_TAG_DISPLAY_PAGE', $WT_TAG_DISPLAY_PAGE);
			Configuration::updateValue('LASTEST_POST_DISPLAY_PAGE', $LASTEST_POST_DISPLAY_PAGE);
			Configuration::updateValue('CURRENT_COMMENT_DISPLAY_PAGE', $CURRENT_COMMENT_DISPLAY_PAGE);
			
			$rs_setting = true;
			if ($WT_MAIN_BLOG_URL != '')
				Configuration::updateValue('WT_MAIN_BLOG_URL', $WT_MAIN_BLOG_URL);
			else
				$rs_setting = false;
				
			if ($this->isInt($WT_NUMBER_COMMENT_DETAIL))
				Configuration::updateValue('WT_NUMBER_COMMENT_DETAIL', $WT_NUMBER_COMMENT_DETAIL);
			else
				$rs_setting = false;
				
			if ($this->isInt($WT_NUMBER_TAG_DISPLAYED))
				Configuration::updateValue('WT_NUMBER_TAG_DISPLAYED', $WT_NUMBER_TAG_DISPLAYED);
			else
				$rs_setting = false;
			if ($this->isInt($WT_NUMBER_CURRENT_COMMENT))
				Configuration::updateValue('WT_NUMBER_CURRENT_COMMENT', $WT_NUMBER_CURRENT_COMMENT);
			else
				$rs_setting = false;
			if ($this->isInt($WT_OP_LASTEST_POST))
				Configuration::updateValue('WT_OP_LASTEST_POST', $WT_OP_LASTEST_POST);
			else
				$rs_setting = false;	
			
			if ($this->isInt($WT_COLP_MAXIMUM))
				Configuration::updateValue('WT_COLP_MAXIMUM', $WT_COLP_MAXIMUM);
			else
				$rs_setting = false;
			
			if ($this->isInt($WT_B_SUMMARY_CHARACTER_COUNT))
				Configuration::updateValue('WT_B_SUMMARY_CHARACTER_COUNT', $WT_B_SUMMARY_CHARACTER_COUNT);
			else
				$rs_setting = false;
			
			if ($this->isInt($WT_POSTS_PER_PAGE))
				Configuration::updateValue('WT_POSTS_PER_PAGE', $WT_POSTS_PER_PAGE);
			else
				$rs_setting = false;
			if ($this->isInt($WT_NUM_RELATED_POSTS))
				Configuration::updateValue('WT_NUM_RELATED_POSTS', $WT_NUM_RELATED_POSTS);
			else
				$rs_setting = false;
			
			if ($this->isInt($WT_NUM_RELATED_PRODUCTS))
				Configuration::updateValue('WT_NUM_RELATED_PRODUCTS', $WT_NUM_RELATED_PRODUCTS);
			else
				$rs_setting = false;
			if ($this->isInt($WT_IMG_SMALL_SIZE))
				Configuration::updateValue('WT_IMG_SMALL_SIZE', $WT_IMG_SMALL_SIZE);
			else
				$rs_setting = false;
			
			if ($this->isInt($WT_IMG_SMALL_H_SIZE))
				Configuration::updateValue('WT_IMG_SMALL_H_SIZE', $WT_IMG_SMALL_H_SIZE);
			else
				$rs_setting = false;
			if ($this->isInt($WT_IMG_MEDIUM_SIZE))
				Configuration::updateValue('WT_IMG_MEDIUM_SIZE', $WT_IMG_MEDIUM_SIZE);
			else
				$rs_setting = false;
			
			if ($this->isInt($WT_IMG_MEDIUM_H_SIZE))
					Configuration::updateValue('WT_IMG_MEDIUM_H_SIZE', $WT_IMG_MEDIUM_H_SIZE);
			else
				$rs_setting = false;
			
			if ($this->isInt($WT_IMG_LARGE_SIZE))
				Configuration::updateValue('WT_IMG_LARGE_SIZE', $WT_IMG_LARGE_SIZE);
			else
				$rs_setting = false;
			
			if ($this->isInt($WT_IMG_LARGE_H_SIZE))
				Configuration::updateValue('WT_IMG_LARGE_H_SIZE', $WT_IMG_LARGE_H_SIZE);
			else
				$rs_setting = false;
			if ($this->isInt($WT_IMG_CATEGORY_SIZE))
				Configuration::updateValue('WT_IMG_CATEGORY_SIZE', $WT_IMG_CATEGORY_SIZE);
			else
				$rs_setting = false;
			if ($this->isInt($CATEGORY_RSS_NUMBER))
				Configuration::updateValue('CATEGORY_RSS_NUMBER', $CATEGORY_RSS_NUMBER);
			else
				$rs_setting = false;
			if (Tools::getValue('categoryBox') != '')
			{
				$cat = implode(',', Tools::getValue('categoryBox'));
				Configuration::updateValue('CATEGORY_RSS', $cat);
			}
			else
				Configuration::updateValue('CATEGORY_RSS', '');
			if ($this->isInt($WT_IMG_CATEGORY_H_SIZE))
				Configuration::updateValue('WT_IMG_CATEGORY_H_SIZE', $WT_IMG_CATEGORY_H_SIZE);
			else
				$rs_setting = false;
			if (!$rs_setting)
				Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&saveError');
			else
				Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&saveConfirmation');		
		}
		elseif (Tools::isSubmit('saveConfirmation'))
			$this->html = $this->displayConfirmation($this->l('Configs updated successfully'));
		elseif (Tools::isSubmit('saveError'))
			$this->html = $this->displayError($this->l('Configs updated error'));
	}
	public function isInt($value)
	{
		return ((string)(int)$value === (string)$value || $value === false);
	}
	public function getContent()
	{
		$this->postProcess();
		$this->displayForm();
		return $this->html;
	}
	public function displayForm()
	{
		$id = Context::getContext()->shop->id;
		$id_shop = $id ? $id: Configuration::get('PS_SHOP_DEFAULT');
		$id_lang = Context::getContext()->language->id;
		$link_main = Configuration::get('WT_MAIN_BLOG_URL');
		$main_blog_url = $this->wtLink->getMainBlogUrl($link_main);
		$value_option = Configuration::get('WT_B_SUMMARY_CHARACTER_COUNT');
		if (!$value_option)
			$id_shop = Configuration::get('PS_SHOP_DEFAULT');
		$this->html .= '
			<link href="'.__PS_BASE_URI__.'modules/wtblog/views/css/admin/wtblog.css" rel="stylesheet" type="text/css" media="all" />
			<script type="text/javascript" src="'.__PS_BASE_URI__.'modules/wtblog/views/js/admin/wtblog.js"></script>
			<div class="panel col-lg-12">
			<div class="panel-heading">'.$this->displayName.'</div>
				<div class="panel col-sm-12 col-lg-12">
					<form id="plsubmitConfiguration" name="submitConfiguration" method="post" action="">
						<fieldset class="manager-0 tab-manager wtblogtabs">
							<h4>'.$this->l('General setting').'</h4>
							<div class="form-group">
								<label class="control-label col-lg-3">'.$this->l('Main blog URL').'</label>
								<div class="col-lg-9">
									<input type="text" size="8" value="'.(Configuration::get('WT_MAIN_BLOG_URL')).'" name="WT_MAIN_BLOG_URL" />
									<p class="help-block">'.$this->l('Main blog url is: ').'<a target="_blank" href="'.$main_blog_url.'">'.$main_blog_url.'</a></p>
								</div>
							</div>	
						</fieldset>
						<fieldset class="manager-1 tab-manager wtblogtabs">
						<h4>'.$this->l('Config page blog list').'</h4>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Number of post per page').'</label>
						<div class="col-lg-9">
							<input type="text" size="8" value="'.(Configuration::get('WT_POSTS_PER_PAGE')).'" name="WT_POSTS_PER_PAGE" />
							<p class="help-block">'.$this->l('Number of post per page is displayed on post list page.').'</p>
						</div>
					</div>
				</fieldset>
				<fieldset class="manager-2 tab-manager wtblogtabs">
				<h4>'.$this->l('Config for comment').'</h4>
					<label class="control-label col-lg-3">'.$this->l('Using captcha').'</label>
					<div class="col-lg-9">
						<input id="display_on" type="radio" value="1" '.(Configuration::get('WT_DISPLAY_CAPTCHA') == '1' ? 'checked=checked' : '').' name="WT_DISPLAY_CAPTCHA" />
						<label class="t" for="display_on">
							<img title="Enabled" alt="Enabled" src="../img/admin/enabled.gif">
						</label>
						<input id="display_off" type="radio" '.(Configuration::get('WT_DISPLAY_CAPTCHA') == '0' ? 'checked=checked' : '').' value="0" name="WT_DISPLAY_CAPTCHA">
						<label class="t" for="display_off">
							<img title="Disabled" alt="Disabled" src="../img/admin/disabled.gif">
						</label>
						<p class="help-block">'.$this->l('Using or no captcha for comment.').'</p>
					</div>
					<label class="control-label col-lg-3">'.$this->l('All comments must be validated by an employee').'</label>
					<div class="col-lg-9">
						<input id="display_on" type="radio" value="1" '.(Configuration::get('WT_COMMENTS_VALIDATE') == '1' ? 'checked=checked' : '').' name="WT_COMMENTS_VALIDATE" />
						<label class="t" for="display_on">
							<img title="Enabled" alt="Enabled" src="../img/admin/enabled.gif">
						</label>
						<input id="display_off" type="radio" '.(Configuration::get('WT_COMMENTS_VALIDATE') == '0' ? 'checked=checked' : '').' value="0" name="WT_COMMENTS_VALIDATE">
						<label class="t" for="display_off">
							<img title="Disabled" alt="Disabled" src="../img/admin/disabled.gif">
						</label>
						<p class="help-block">'.$this->l('All comments must be validated by an employee').'</p>
					</div>
					<label class="control-label col-lg-3">'.$this->l('Allow guest comments').'</label>
					<div class="col-lg-9">
						<input id="display_on" type="radio" value="1" '.(Configuration::get('WT_ALLOW_COMMENTS_BY_GUESTS') == '1' ? 'checked=checked' : '').' name="WT_ALLOW_COMMENTS_BY_GUESTS" />
						<label class="t" for="display_on">
							<img title="Enabled" alt="Enabled" src="../img/admin/enabled.gif">
						</label>
						<input id="display_off" type="radio" '.(Configuration::get('WT_ALLOW_COMMENTS_BY_GUESTS') == '0' ? 'checked=checked' : '').' value="0" name="WT_ALLOW_COMMENTS_BY_GUESTS">
						<label class="t" for="display_off">
							<img title="Disabled" alt="Disabled" src="../img/admin/disabled.gif">
						</label>
						<p class="help-block">'.$this->l('Allow or disallow the ability to post comments for unregistered users').'</p>
					</div>
					<div id="num_related_posts" style="display:none;">
						<label class="control-label col-lg-3">'.$this->l('Number of comments displayed').'</label>
						<div class="col-lg-9">
							<input type="text" size="8" value="'.(Configuration::get('WT_NUMBER_COMMENT_DETAIL')).'" name="WT_NUMBER_COMMENT_DETAIL" />
						</div>
					</div>
					<div class="separation"></div>
					</fieldset>
					<fieldset class="manager-3 tab-manager wtblogtabs">
					<h4>'.$this->l('Config image size').'</h4>
					<div class="form-group col-lg-12">
					<label class="control-label col-lg-3">'.$this->l('Post image size small (in px)').'</label>
					<div class="col-lg-9">
						<div class="col-lg-4">
						<input type="text" size="8" value="'.(Configuration::get('WT_IMG_SMALL_SIZE')).'" name="WT_IMG_SMALL_SIZE" />
						</div>
						<div class="col-lg-1">x</div>
						<div class="col-lg-4">
						<input type="text" size="8" value="'.(Configuration::get('WT_IMG_SMALL_H_SIZE')).'" name="WT_IMG_SMALL_H_SIZE" />
						</div>
					</div>
					</div>
					<div class="form-group col-lg-12">
					<label class="control-label col-lg-3">'.$this->l('Post image size medium (in px)').'</label>
					<div class="col-lg-9">
						<div class="col-lg-4">
						<input type="text" size="8" value="'.(Configuration::get('WT_IMG_MEDIUM_SIZE')).'" name="WT_IMG_MEDIUM_SIZE" />
						</div>
						<div class="col-lg-1">x</div>
						<div class="col-lg-4">
						<input type="text" size="8" value="'.(Configuration::get('WT_IMG_MEDIUM_H_SIZE')).'" name="WT_IMG_MEDIUM_H_SIZE" />
						</div>
					</div>
					</div>
					<div class="form-group col-lg-12">
					<label class="control-label col-lg-3">'.$this->l('Post image size large(in px)').'</label>
					<div class="col-lg-9">
						<div class="col-lg-4">
						<input type="text" size="8" value="'.(Configuration::get('WT_IMG_LARGE_SIZE')).'" name="WT_IMG_LARGE_SIZE" />
						</div>
						<div class="col-lg-1">x</div>
						<div class="col-lg-4">
						<input type="text" size="8" value="'.(Configuration::get('WT_IMG_LARGE_H_SIZE')).'" name="WT_IMG_LARGE_H_SIZE" />
						</div>
					</div>
					</div>
				<div class="form-group col-lg-12">
					<label class="control-label col-lg-3">'.$this->l('Category image size (in px)').'</label>
					<div class="col-lg-9">
						<div class="col-lg-4">
						<input type="text" size="8" value="'.(Configuration::get('WT_IMG_CATEGORY_SIZE')).'" name="WT_IMG_CATEGORY_SIZE" />
						</div>
						<div class="col-lg-1">x</div>
						<div class="col-lg-4">
						<input type="text" size="8" value="'.(Configuration::get('WT_IMG_CATEGORY_H_SIZE')).'" name="WT_IMG_CATEGORY_H_SIZE" />
						</div>
					</div>
				</div>
				</fieldset> 
				<fieldset class="manager-4 tab-manager wtblogtabs">
					<h4>'.$this->l('Block categories').'</h4>
					<label class="control-label col-lg-3">'.$this->l('Dynamic').'</label>
					<div class="col-lg-9">
					<input type="radio" name="BLOCK_CATEG_DHTML" value="1" '.(Configuration::get('BLOCK_CATEG_DHTML') == '1' ? 'checked=checked' : '').'>
					<label class="t" for="dhtml_on"> <img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled"></label>
					<input type="radio" name="BLOCK_CATEG_DHTML" id="dhtml_off" value="0" '.(Configuration::get('BLOCK_CATEG_DHTML') == '0' ? 'checked=checked' : '').'>
					<label class="t" for="dhtml_off"> <img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled"></label>
					<p class="help-block">'.$this->l('Activate dynamic (animated) mode for sublevels.').'</p>
					</div>
					<label class="control-label col-lg-3">'.$this->l('Only display blog page').'</label>
					<div class="col-lg-9">
					<input type="radio" name="BLOCK_CATEG_DISPLAY_PAGE" value="1" '.(Configuration::get('BLOCK_CATEG_DISPLAY_PAGE') == '1' ? 'checked=checked' : '').'>
					<label class="t" for="dhtml_on"> <img src="../img/admin/enabled.gif" alt="Enabled" title="Enabled"></label>
					<input type="radio" name="BLOCK_CATEG_DISPLAY_PAGE" id="dhtml_off" value="0" '.(Configuration::get('BLOCK_CATEG_DISPLAY_PAGE') == '0' ? 'checked=checked' : '').'>
					<label class="t" for="dhtml_off"> <img src="../img/admin/disabled.gif" alt="Disabled" title="Disabled"></label>
					<p class="help-block">'.$this->l('If it is actived, block only display blog page. And contrary, block display all page.').'</p>
					</div>
				</fieldset>
				<fieldset class="manager-5 tab-manager wtblogtabs">	
					<h4>'.$this->l('Block tags').'</h4>
					<label class="control-label col-lg-3">'.$this->l('Number tags displayed').'</label>
					<div class="col-lg-9">
						<input type="text" size="8" value="'.(Configuration::get('WT_NUMBER_TAG_DISPLAYED')).'" name="WT_NUMBER_TAG_DISPLAYED" />
						<p class="help-block">'.$this->l('Set the number of tags to be displayed in this block').'</p>
					</div>
				</fieldset>
				<fieldset class="manager-6 tab-manager wtblogtabs">
					<h4>'.$this->l('Block lastest post').'</h4>
					<label class="control-label col-lg-3">'.$this->l('Numbers post display').'</label>
					<div class="col-lg-9">
						<input type="text" size="8" value="'.(Configuration::get('WT_OP_LASTEST_POST')).'" name="WT_OP_LASTEST_POST" />
						<p class="help-block">'.$this->l('Specify the amount of lastest post to be displayed in lastest post block').'</p>
					</div>
				</fieldset>
				<fieldset class="manager-7 tab-manager wtblogtabs">
				<h4>'.$this->l('Block current comments').'</h4>
					<label class="control-label col-lg-3">'.$this->l('Numbers post display').'</label>
					<div class="col-lg-9">
						<input type="text" size="8" value="'.(Configuration::get('WT_NUMBER_CURRENT_COMMENT')).'" name="WT_NUMBER_CURRENT_COMMENT" />
						<p class="help-block">'.$this->l('Specify the amount of lastest post to be displayed in lastest post block').'</p>
					</div>
				</fieldset>
				<fieldset class="manager-8 tab-manager wtblogtabs">
					<h4>'.$this->l('Category RSS').'</h4>
					<p class="help-block">'.$this->l('If categories are selected, they will display link rss in front end.').'</p>
					<div>
					<label class="control-label col-lg-3">'.$this->l('Number of post').'</label>
					<div class="col-lg-9">
						<input type="text" size="8" value="'.(Configuration::get('CATEGORY_RSS_NUMBER')).'" name="CATEGORY_RSS_NUMBER" />
						<p class="help-block">'.$this->l('Number of post are extracted RSS.').'</p>
					</div>
					<table cellspacing="0" cellpadding="0" class="table" style="clear:both;">
						<tr>
							<th>c</th>
							<th>ID</th>
							<th style="width: 600px">'.$this->l('Category Blog').'</th>
						</tr>';
						$id_root = 1; //home category
						$arrCheck = explode(',', Configuration::get('CATEGORY_RSS'));
						$categories = WTCategory::getCategoriesCheckbox($id_lang, $id_shop);
						$this->html .= $this->getCheckboxCatalog($arrCheck, $categories, $categories[0][1], $id_root, 1).
					'</table>
					</div>
				</fieldset>
				<center><input type="submit" name="submitConfiguration" value="Save" class="btn btn-default" /></center>
					</form>
				</div>
			</div>
		';
		return $this->html;
}
	public static function getCheckboxCatalog($arrCheck, $categories, $current, $id_category = 1, $level, $has_suite = array())
	{
		$done = array();
		static $irow;
		if (!isset($done[$current['infos']['category_parent']]))
			$done[$current['infos']['category_parent']] = 0;
		$done[$current['infos']['category_parent']] += 1;
		if (isset($categories[$current['infos']['category_parent']]))
			$todo = count($categories[$current['infos']['category_parent']]);
		$doneC = $done[$current['infos']['category_parent']];
		if ($id_category != 1)
		{
			$result = '
			<tr class="'.($irow++ % 2 ? 'alt_row' : '').'">
				<td>
					<input type="checkbox" name="categoryBox[]" class="categoryBox" id="categoryBox_'.$id_category.'" value="'.$id_category.'"'.(in_array($id_category, $arrCheck) ? ' checked="checked"' : '').'/>
				</td>
				<td>
					'.$id_category.'
				</td>
				<td>';
				for ($i = 2; $i < $level; $i++)
					if (isset($has_suite[$i - 2]))
						$result .= '<img src="../img/admin/lvl_'.$has_suite[$i - 2].'.gif" alt="" />';
				$result .= '<img style="vertical-align:middle" src="../img/admin/'.($level == 1 ? 'lv1.gif' : 'lv2_'.($todo == $doneC ? 'f' : 'b').'.gif').'" alt="" /> &nbsp;
				<label for="categoryBox_'.$id_category.'" class="t">'.Tools::stripslashes($current['infos']['name']).'</label></td>
			</tr>';
		}
		else
			$result = '';
		if ($level > 1)
			$has_suite[] = ($todo == $doneC ? 0 : 1);
		if (isset($categories[$id_category]))
			foreach ($categories[$id_category] as $key => $row)
				if ($key != 'infos')
				{
					$level += 1;
					$result .= self::getCheckboxCatalog($arrCheck, $categories, $categories[$id_category][$key], $key, $level, $has_suite);
				}
		
		return $result;
	}
	public function hookModuleRoutes($params)
	{
		return array(
		'wt_main_url' => array(
			'controller' =>	'categoryPost',
			'params' => array(
				'fc' => 'module',
				'module' => 'wtblog',
			),
			'rule' =>		'{mainlink}.html',
			'keywords' => array(
				'mainlink' =>	array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'mainlink'),
			),
		),
		'wt_category' => array(
			'controller' =>	null,
			'rule' =>		'module/{module}{/:controller}/{rewrite}-{id_wt_blog_category}.html',
			'keywords' => array(
				'module' =>			array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'module'),
				'controller' =>		array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'controller'),
				'id_wt_blog_category' =>				array('regexp' => '[0-9]+', 'param' => 'id_wt_blog_category'),
				'rewrite' =>		array('regexp' => '[_a-zA-Z0-9-\pL]*'),
			),
			'params' => array(
				'fc' => 'module',
			),
		),
		'wt_blog_post' => array(
			'controller' =>	null,
			'rule' =>		'module/{module}{/:controller}/{id_wt_blog_post}-{category_parent}-{rewrite}.html',
			'keywords' => array(
				'module' =>			array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'module'),
				'controller' =>		array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'controller'),
				'category_parent' =>		array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'category_parent'),
				'id_wt_blog_post' =>				array('regexp' => '[0-9]+', 'param' => 'id_wt_blog_post'),
				'rewrite' =>		array('regexp' => '[_a-zA-Z0-9-\pL]*'),
			),
			'params' => array(
				'fc' => 'module',
			),
		),
		'wt_tag' => array(
			'controller' =>	null,
			'rule' =>		'module/{module}{/:controller}/{id_wt_blog_tag}-{name}.html',
			'keywords' => array(
				'module' =>			array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'module'),
				'controller' =>		array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'controller'),
				'id_wt_blog_tag' =>				array('regexp' => '[0-9]+', 'param' => 'id_wt_blog_tag'),
				'name' =>		array('regexp' => '[_a-zA-Z0-9_-]+'),
			), 
			'params' => array(
				'fc' => 'module',
			),
		),
		'wt_rss' => array(
			'controller' =>	null,
			'rule' =>		'module/{module}{/:controller}/{idrss}.html',
			'keywords' => array(
				'module' =>			array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'module'),
				'controller' =>		array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'controller'),
				'idrss' =>				array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'idrss')
			), 
			'params' => array(
				'fc' => 'module',
			),
		)
		);
	}
	public function getTree($resultParents, $resultIds, $maxDepth, $id_category = 1, $currentDepth = 0)
	{
		$children = array();
		if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth))
			foreach ($resultParents[$id_category] as $subcat)
				$children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_wt_blog_category'], $currentDepth + 1);
		if (!isset($resultIds[$id_category]))
			return false;
		return array('id' => $id_category, 'link' => $this->wtLink->getCategoryPostLink($id_category, $resultIds[$id_category]['link_rewrite']),
			'name' => $resultIds[$id_category]['name'],
			'desc' => '',
			'children' => $children,
			'link_rewrite' => $resultIds[$id_category]['link_rewrite']);
	}
	public static function getLastestPost($id_lang = null, $id_shop = null, $post_length = 4, $img_size = 'medium')
	{
		$posts = Db::getInstance()->ExecuteS('
			SELECT ps.*,pl.*,p.* FROM '._DB_PREFIX_.'wt_blog_post p
			INNER JOIN '._DB_PREFIX_.'wt_blog_post_lang pl
			ON (p.id_wt_blog_post=pl.id_wt_blog_post AND pl.id_lang = '.$id_lang.' AND pl.id_shop = '.$id_shop.')
			INNER JOIN '._DB_PREFIX_.'wt_blog_post_shop ps
			ON (p.id_wt_blog_post=ps.id_wt_blog_post AND ps.id_shop = '.$id_shop.')
			WHERE ps.active = 1
			ORDER BY p.date_add DESC
			LIMIT 0, '.$post_length.'
		');
		$posts_new = array();
		$postObj = new WTPost();
		foreach ($posts as $post)
		{
			$post['date_add'] = $postObj->formatDateAdd($post['date_add']);
			$author = new Employee($post['author']);
			$post['author'] = $author->firstname.' '.$author->lastname;
			$postOb = new WTPost($post['id_wt_blog_post']);
			$post['count_comment'] = $postOb->getCountComment($id_lang, $id_shop);
			$link = new WTLink();
			$post['link'] = $link->getLinkPostDetail($post['id_wt_blog_post'], $post['link_rewrite'], $post['id_wt_blog_category']);
			
			$save_path = _PS_MODULE_DIR_.'wtblog/views/img/media/posts/src/'.$post['id_wt_blog_post'];
			$url_path = 'views/img/media/posts/cache/'.$post['id_wt_blog_post'].'_'.$id_shop;
			if (file_exists($save_path.'.jpg'))
				$post['image'] = $url_path.'_'.$img_size.'.jpg';
			else
				$post['image'] = 'views/img/media/posts/cache/no_image_medium.jpg';
			$posts_new[] = $post;
		}
		return $posts_new;
	}
	public function getCurrentComments($id_lang = null, $id_shop = null, $nb = 5)
	{
		$comments = Db::getInstance()->ExecuteS('
			SELECT c.*,cl.* FROM '._DB_PREFIX_.'wt_blog_comment c
			INNER JOIN '._DB_PREFIX_.'wt_blog_comment_lang cl
			ON (c.id_wt_blog_comment = cl.id_wt_blog_comment AND cl.id_lang = '.$id_lang.')
			WHERE c.id_shop = '.$id_shop.' AND c.active = 1
			ORDER BY c.date_add DESC
			LIMIT 0, '.$nb.'
		');
		$result = array();
		$postObj = new WTPost();
		foreach ($comments as $comment)
		{
			$post_comment = new WTPost($comment['id_wt_blog_post']);
			$comment['date_add'] = $postObj->formatDateAdd($comment['date_add']);
			$comment['name_post'] = $post_comment->name[$id_lang];
			$comment['link_post'] = $this->wtLink->getLinkPostDetail($post_comment->id_wt_blog_post, $post_comment->link_rewrite[$id_lang], $post_comment->id_wt_blog_category);
			$imep = 'medium';
			if ($imep != 'none')
			{
					$save_path = _PS_MODULE_DIR_.'wtblog/views/img/media/posts/src/'.$post_comment->id_wt_blog_post;
					$url_path = 'views/img/media/posts/cache/'.$post_comment->id_wt_blog_post.'_'.$id_shop;
					if (file_exists($save_path.'.'.$this->imageType))
						$comment['image_post'] = $url_path.'_'.$imep.'.'.$this->imageType;
			}
			$result[] = $comment;
		}
		return $result;
	}
	public function getContentForHook()
	{
		$id_lang = $this->context->language->id;
		$id_shop = $this->context->shop->id;
		$this->context->smarty->assign('wt_path', $this->_path);
		/*get categories*/
		$this->context->smarty->assign('CS_SHOW_BLOCK_CATEGORY', Configuration::get('CS_SHOW_BLOCK_CATEGORY'));
		$this->context->smarty->assign('CS_DISPLAY_CATEGORY', Configuration::get('CS_DISPLAY_CATEGORY'));
		$maxdepth = Configuration::get('BLOCK_CATEG_MAX_DEPTH');
		$result = $this->getCategories($id_lang, $id_shop);
		$resultParents = array();
		$resultIds = array();
		foreach ($result as &$row)
		{
			$resultParents[$row['category_parent']][] = &$row;
			$resultIds[$row['id_wt_blog_category']] = &$row;
		}
		$block_categ_tree = $this->getTree($resultParents, $resultIds, Configuration::get('BLOCK_CATEG_MAX_DEPTH'));
		unset($resultParents);
		unset($resultIds);
		$is_dhtml = (Configuration::get('BLOCK_CATEG_DHTML') == 1 ? true : false);
		$id_wt_blog_category = Tools::getValue('id_wt_blog_category');
		$id_wt_blog_post = Tools::getValue('id_wt_blog_post');
		if (Tools::isSubmit('id_wt_blog_category'))
		{
			$this->context->cookie->last_visited_blog_category = $id_wt_blog_category;
			$this->context->smarty->assign('id_wt_blog_category_current', $this->context->cookie->last_visited_blog_category);
		}
		if (Tools::isSubmit('id_wt_blog_post'))
		{
			$this->context->cookie->last_visited_blog_category = Tools::getValue('category_parent');
			$this->smarty->assign('id_wt_blog_category_current', (int)$this->context->cookie->last_visited_blog_category);
		}
		$this->context->smarty->assign('wt_blockCategTree', $block_categ_tree);
		if (file_exists(_PS_THEME_DIR_.'modules/wtblog/views/templates/hook/blockcategories.tpl'))
			$this->context->smarty->assign('wt_branche_tpl_path', _PS_THEME_DIR_.'modules/blockcategories/category-tree-branch.tpl');
		else
			$this->context->smarty->assign('wt_branche_tpl_path', _PS_MODULE_DIR_.'wtblog/views/templates/hook/category-tree-branch.tpl');
		$this->context->smarty->assign('wt_isDhtml', $is_dhtml);
		/*get post lastest*/
		$lastest_posts = WTBlog::getLastestPost($id_lang, $id_shop, Configuration::get('WT_OP_LASTEST_POST'), 'small');
		$this->context->smarty->assign('lastest_posts', $lastest_posts);
		$this->context->smarty->assign('wt_image_size_relate_posts', Configuration::get('WT_IMAGE_SIZE_RELATED_POSTS'));
		$this->context->smarty->assign('WT_SHOW_BLOCK_LASTEST', Configuration::get('WT_SHOW_BLOCK_LASTEST'));
		$this->context->smarty->assign('DISPLAY_LASTEST_POST', Configuration::get('WT_LASTEST_POST'));
		$this->context->smarty->assign('wt_imep_show', Configuration::get('WT_IMEP_SHOW'));
		$this->context->smarty->assign('WT_COLP_MAXIMUM', Configuration::get('WT_COLP_MAXIMUM'));
		/*get tags*/
		$tags = $this->getMainTags($id_lang, $id_shop, Configuration::get('WT_NUMBER_TAG_DISPLAYED'));
		$this->context->smarty->assign('WT_SHOW_BLOCK_TAG', Configuration::get('WT_SHOW_BLOCK_TAG'));
		$this->context->smarty->assign('WT_DISPLAY_TAG', Configuration::get('WT_DISPLAY_TAG'));
		$this->context->smarty->assign('tags', $tags);
		/*get comments current*/ 
		$nb = Configuration::get('WT_NUMBER_CURRENT_COMMENT');
		$position = Configuration::get('WT_POSITION_CURRENT_COMMENT');
		$comments = $this->getCurrentComments($id_lang, $id_shop, $nb);
		$this->context->smarty->assign('position', $position);
		$this->context->smarty->assign('blockcomments', $comments);
		/*page display*/
		$this->context->smarty->assign('BLOCK_CATEG_DISPLAY_PAGE', Configuration::get('BLOCK_CATEG_DISPLAY_PAGE'));
		$this->context->smarty->assign('WT_TAG_DISPLAY_PAGE', Configuration::get('WT_TAG_DISPLAY_PAGE'));
		$this->context->smarty->assign('LASTEST_POST_DISPLAY_PAGE', Configuration::get('LASTEST_POST_DISPLAY_PAGE'));
		$this->context->smarty->assign('CURRENT_COMMENT_DISPLAY_PAGE', Configuration::get('CURRENT_COMMENT_DISPLAY_PAGE'));
	}
	public function getMainTags($id_lang = null, $id_shop = null, $nb = 10)
	{
		$tags = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT t.name,t.id_wt_blog_tag, COUNT(pt.id_wt_blog_tag) AS times
		FROM `'._DB_PREFIX_.'wt_blog_post_tag` pt
		LEFT JOIN `'._DB_PREFIX_.'wt_blog_tag` t ON (t.id_wt_blog_tag = pt.id_wt_blog_tag)
		LEFT JOIN `'._DB_PREFIX_.'wt_blog_post` p ON (p.id_wt_blog_post = pt.id_wt_blog_post )
		WHERE t.`id_lang` = '.$id_lang.' AND (pt.id_wt_blog_post IN (SELECT ps.id_wt_blog_post FROM '._DB_PREFIX_.'wt_blog_post_shop ps WHERE ps.id_shop = '.$id_shop.')) 
		GROUP BY t.id_wt_blog_tag
		ORDER BY times DESC
		LIMIT 0, '.$nb);
		$tags_new = array();
		foreach ($tags as $tag)
		{
			$tag['link'] = $this->wtLink->getTagLink($tag['id_wt_blog_tag'], $tag['name']);
			$tags_new[] = $tag;
		}
		return $tags_new;
	}
	public function hookActionShopDataDuplication($params)
	{
		/* duplicate category for shop */
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'wt_blog_category_shop (id_wt_blog_category, id_shop, position)
		SELECT id_wt_blog_category, '.$params['new_id_shop'].', position
		FROM '._DB_PREFIX_.'wt_blog_category_shop
		WHERE id_shop = '.$params['old_id_shop']);
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'wt_blog_category_lang (id_wt_blog_category, id_lang, id_shop, name, description, meta_title, meta_description, meta_keywords, link_rewrite)
		SELECT id_wt_blog_category, id_lang, '.$params['new_id_shop'].', name, description, meta_title, meta_description, meta_keywords, link_rewrite
		FROM '._DB_PREFIX_.'wt_blog_category_lang
		WHERE id_shop = '.$params['old_id_shop']);
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'wt_blog_post_shop (id_wt_blog_post, id_shop, id_wt_blog_category,id_shop_default, date_add, position, active, related_posts, related_products)
		SELECT id_wt_blog_post, '.$params['new_id_shop'].', id_wt_blog_category, id_shop_default, date_add, position, active, related_posts, related_products
		FROM '._DB_PREFIX_.'wt_blog_post_shop
		WHERE id_shop = '.$params['old_id_shop']);
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'wt_blog_post_lang (id_wt_blog_post, id_lang, id_shop, name, description, meta_title, meta_description, meta_keywords, link_rewrite)
		SELECT id_wt_blog_post, id_lang, '.$params['new_id_shop'].', name, description, meta_title, meta_description, meta_keywords, link_rewrite
		FROM '._DB_PREFIX_.'wt_blog_post_lang
		WHERE id_shop = '.$params['old_id_shop']);
	}
	public function hookDisplayLeftColumn()
	{
		$this->getContentForHook();
		return $this->display(__FILE__, 'views/templates/hook/columnleft.tpl');
	}
	public function hookHeader($params)
	{		
		$this->context->controller->addCSS($this->_path.'views/css/wtblogstyle.css');
	}
	public function hookdisplayBackOfficeHeader($params)
	{
		$this->context->controller->addCSS($this->_path.'views/css/admin/wtblog.css');
	}
}