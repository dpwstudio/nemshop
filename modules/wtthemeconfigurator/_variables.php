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

	$themes_colors = array(
		'default'
	);
	
	$items_settings = array(
		'body_color' => array(
			'text' => 'Background color of body',
			'note' => 'Support Box Layout Only',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' => 'body',
			),
			'default_val' => '#fff',
			'frontend' => true,
		),
		'content_bkg' => array(
			'text' => 'Background content',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' =>'#index .columns-container,.columns-container, body #page,.block-home-title .wt-out-title',
			),
			'default_val' => '#fff',
			'frontend' => true,
		),
		'footer_bkg' => array(
			'text' => 'Background footer',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' =>'.footer-container,#page #footer_bottom .container,.header-container,.wt-menu-sticky',
			),
			'default_val' => '#1c1c1c',
			'frontend' => true,
		),
		'main_color' => array(
			'text' => 'Main color',
			'note' => 'Button, label Sale,...',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' =>'.product-container-img .button.ajax_add_to_cart_button:hover,.price-percent-reduction,.wishlist a:hover, .wishlist a.checked, .compare a:hover, .compare a.checked, .quick-view:hover, .quick-view.checked,.owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots.clickable .owl-dot:hover span,.our-store-item-right .readmore,#newsletter_block_left .form-group .button-small,#search_block_top .btn.button-search,.search_hover.active:before,.shopping_cart > a:first-child:hover:before,.button.button-medium, .button.lnk_view, .closefb, .cart_navigation .button-medium, .button.button-medium.exclusive, .comment_form button,.top-pagination-content ul.pagination li.active, .bottom-pagination-content ul.pagination li.active,#wt_reset,.box-info-product .exclusive,#wishlist_button:hover, #wishlist_button.checked, #wishlist_button_nopop:hover, #wishlist_button_nopop.checked,.button.button-small:hover, .cart_navigation .button-exclusive:hover, .button.exclusive-medium:hover,.button.ajax_add_to_cart_button,.top-pagination-content ul.pagination li:hover, .bottom-pagination-content ul.pagination li:hover,#wt_scroll_top:hover,.sale-label,ul.step li.step_current span,#login_form .button.button-medium:hover,.address_add .button.button-small,#my-account ul.myaccount-link-list li a:hover i,ul.step li.step_done:hover a',
				
				
				'color' =>'.shopping_cart > a:first-child span,.header_user_info a:hover, .header_user_info a.active,a:hover, a:focus,.wt-menu-horizontal ul li.level-1:hover > a,.wt-html-nav ul li a:hover,div.wt_category_feature h4.title a:hover,ul#wt-prod-filter-tabs li.active a, ul#wt-prod-filter-tabs li a:hover,.price.product-price,.product-name a:hover,div.g-blog-info,.our-store-item-right .readmore:hover,.copy-right .payment-left ul li a,.footer-container #footer a:hover,#newsletter_block_left .form-group .button-small:hover,.wt-menu-horizontal ul li.level-1 ul li a:hover,#categories_block_left li span.grower:hover + a, #categories_block_left li a:hover, #categories_block_left li a.selected, #blog_categories li span.grower:hover + a, #blog_categories li a:hover, #blog_categories li a.selected,.breadcrumb a:hover,#categories_block_left li span.grower.OPEN:hover:before, #categories_block_left li span.grower.CLOSE:hover:before, #blog_categories li span.grower.OPEN:hover:before, #blog_categories li span.grower.CLOSE:hover:before,.button.button-medium:hover, .button.lnk_view:hover, .closefb:hover, .cart_navigation .button-medium:hover, .button.button-medium.exclusive:hover, .comment_form button:hover,#layered_price_range,#wt_reset:hover,.our_price_display .price,.pb-center-column p .editable,.box-info-product .exclusive:hover,.button.button-small, .cart_navigation .button-exclusive, .button.exclusive-medium,.price,#productscategory_list .product-name a:hover,div.out-content .product-name a:hover,#wt_scroll_top,.button.ajax_add_to_cart_button:hover,#cart_summary tbody td.cart_avail span,#login_form .button.button-medium,.address_add .button.button-small:hover,p.payment_module:hover span,#product_comments_block_tab .comment_author_infos strong,#product_comments_block_tab .comment_author_infos em,#my-account ul.myaccount-link-list li a:hover,#my-account ul.myaccount-link-list li a i,.block .list-block li a:hover,h5.post_title a:hover,.our-store-item-right ul li a i,div#productscategory_list .product-name a:hover,#layer_cart .layer_cart_product .title,#layer_cart .layer_cart_product .layer_cart_product_info #layer_cart_product_price,#layer_cart .cross:hover,#header .cart_block a:hover,div.wt-block-testimonial .testimonial-text h3,#subcategories ul li .subcategory-name:hover,h5.post_title a:hover',
				
				
				'border-color' =>'.product-container-img .button.ajax_add_to_cart_button:hover,.wishlist a:hover, .wishlist a.checked, .compare a:hover, .compare a.checked, .quick-view:hover, .quick-view.checked,.owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots.clickable .owl-dot:hover span,.our-store-item-right .readmore,#newsletter_block_left .form-group .button-small,.button.button-medium, .button.lnk_view, .closefb, .cart_navigation .button-medium, .button.button-medium.exclusive, .comment_form button,.top-pagination-content ul.pagination li.active, .bottom-pagination-content ul.pagination li.active,#wt_reset,.box-info-product .exclusive,#wishlist_button:hover, #wishlist_button.checked, #wishlist_button_nopop:hover, #wishlist_button_nopop.checked,.button.button-small, .cart_navigation .button-exclusive, .button.exclusive-medium,.button.ajax_add_to_cart_button,.top-pagination-content ul.pagination li:hover, .bottom-pagination-content ul.pagination li:hover,#wt_scroll_top',
				'background-color_10'=>'',
				'border-color_10' =>'',
				'background-color_-20' => ''
				),
			'default_val' => '#ffa334',
			'frontend' => true,
		),
		'primary1_color' => array(
			'text' => 'Primary color 1',
			'note' => '',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' =>'',
				'color' =>' body,h5.post_title a,.our-store-item-right h3,h5.post_title,a,.tags_block .block_content a,.footer-container #footer a,.copy-right-top p,.footer-container #footer,.btn-default,.table-data-sheet tr td:first-child,#product_comments_block_tab .title_block strong,.old-price,.form-control,.checkbox label,.footer-container #footer #block_contact_infos > div ul li i,.wt-menu-horizontal ul li.level-1 ul li a,#categories_block_left li a, #blog_categories li a,div.comment_form label,.copy-right,div.wt-block-testimonial .testimonial-text span.name,.cart_voucher #display_cart_vouchers span,.table td a.color-myaccount,.comments_advices a,#usefull_link_block li a',
				'border-color' =>'',
				'background-color_10'=>'',
				'border-color_10' =>'',
				'background-color_-20' => ''
				),
			'default_val' => '#666',
			'frontend' => true,
		),
		
		'primary2_color' => array(
			'text' => 'Primary color 2',
			'note' => '',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' =>'',
				'color' =>'.wt_category_feature h3, .interior > h3, .wt-bestseller-prod > h3, .testimonial-text h3, #blog_latest_new_home .container > h3 a, #newsletter_block_left .decs-title,ul#wt-prod-filter-tabs li a,div.out-wt-prod h3 a,.block .title_block, .block h4,.content_scene_cat span.category-name,.page-heading,#layered_block_left .layered_subtitle,.block .title_block a, .block h4 a,.block-home-title h3,.pb-center-column h1,div#wt-special-products .block-title h3,div.star.star_on:after,.our-store-item-right ul li a i:hover,.product-name, .product-name a,div#productscategory_list .product-name a,.page-heading span.heading-counter,.table > thead > tr > th,.cart_voucher h4,#cart_summary tfoot td.total_price_container span,#cart_summary tfoot td#total_price_container,.page-subheading,label,ul.step li.step_done a,#layer_cart .layer_cart_product .layer_cart_product_info #layer_cart_product_title,.dark,#layer_cart .layer_cart_cart .layer_cart_row.grand_total strong, #layer_cart .layer_cart_cart .layer_cart_row.grand_total span,#subcategories p.subcategory-heading,.page-title h1,#languages-block-top div.current:after,#header .shopping_cart > a:first-child:after,#subcategories ul li .subcategory-name,#layer_cart .layer_cart_cart .title,td.cart_voucher .title-offers,#my-account ul.myaccount-link-list li a,p.info-title,#pQuantityAvailable span,div.out-content .product-name a,#product_comments_block_extra .comments_note span, #product_comments_block_extra .star_content,.block-home-title h3 a,h5.post_title a',
				'border-color' =>'',
				'background-color_10'=>'',
				'border-color_10' =>'',
				'background-color_-20' => ''
				),
			'default_val' => '#222a2d',
			'frontend' => true,
		),
		
		'secondary_color' => array(
			'text' => 'Secondary color',
			'note' => '',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' =>'',
				'color' =>' .breadcrumb a,.wt-html-nav ul li a,div.wt_category_feature h4.title a,.home-title,.product-desc,div.wt-block-testimonial .testimonial-text,div#blog_latest_new_home .blog-content p,#newsletter_block_left .block_content p,.our-store-item-right p,.breadcrumb,.pb-center-column #short_description_block,ul.step li.step_todo span,#cart_summary tfoot td.text-right,#cart_summary tfoot td.price,#create-account_form p,#layer_cart .layer_cart_cart .layer_cart_row strong, #layer_cart .layer_cart_cart .layer_cart_row span,div.blog-content p, div#wt_post p,div#blog_comments li .comment-content,div.selector span,#footer #newsletter_block_left .form-group .form-control',
				'border-color' =>'',
				'background-color_10'=>'',
				'border-color_10' =>'',
				'background-color_-20' => ''
				),
			'default_val' => '#999',
			'frontend' => true,
		),
		
		'secondary1_color' => array(
			'text' => 'Secondary color 1',
			'note' => '',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' =>'',
				'color' =>'.search_hover,#currencies-block-top div.current strong,#currencies-block-top div.current:after,#currencies-block-top div.current,.search_hover:before,.shopping_cart > a:first-child,.wt-menu-horizontal ul li.level-1 > a,.header_user_info a,.wt-home-banner .wt-out-banner h3,.wt-home-banner,.footer-container #footer h4,.footer-container #footer h4 a',
				'border-color' =>'',
				'background-color_10'=>'',
				'border-color_10' =>'',
				'background-color_-20' => ''
				),
			'default_val' => '#fff',
			'frontend' => true,
		),
		
		'secondary2_color' => array(
			'text' => 'Secondary color 2',
			'note' => '',
			'attr_css' => 'color',
			'selector' => array(
				'background-color' =>'',
				'color' =>' .block-home-title .block-desc',
				'border-color' =>'',
				'background-color_10'=>'',
				'border-color_10' =>'',
				'background-color_-20' => ''
				),
			'default_val' => '#8eacbb',
			'frontend' => true,
		),
		
		
		'body_font' => array(
			'text' => 'Font of body',
			'note' => 'text desction, link footer,...',
			'attr_css' => 'font-family',
			'selector' => 'body,h5.post_title',
			'frontend' => true,
		),
		
		'second_font' => array(
			'text' => 'Second font',
			'note' => 'Menu,title product,price,...',
			'attr_css' => 'font-family',
			'selector' => '.wt-menu-horizontal ul li.level-1 > a,h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6,.out-wt-prod h3 a,.footer-container #footer h4,.footer-container #footer a,.copy-right-top p,.wt-menu-horizontal ul li.level-1 ul li a,.block .title_block, .block h4,.price.product-price,.button.ajax_add_to_cart_button,.page-heading,ul.step li a, ul.step li span, ul.step li.step_current span, ul.step li.step_current_end span,#cart_summary thead th.cart_product, #cart_summary thead th.cart_description, #cart_summary thead th.cart_unit, #cart_summary thead th.cart_quantity, #cart_summary thead th.cart_total, #cart_summary thead th.cart_avail,.page-subheading,.box label,div.g-blog-info,#subcategories p.subcategory-heading,#subcategories ul li .subcategory-name,.block-home-title h3,.block .title_block, .block h4',
			'frontend' => true,
		),
		'special_font' => array(
			'text' => 'Special font',
			'note' => '',
			'attr_css' => 'font-family',
			'selector' => '.our-store-item-right h3, div.wt-block-testimonial .testimonial-text p,div.wt-block-testimonial .testimonial-text p, div#blog_lastest_posts_displayLeftColumn li h5,.wt-home-banner .wt-out-banner h3',
			'frontend' => true,
		)
	);
