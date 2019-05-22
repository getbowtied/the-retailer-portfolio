<?php

add_action( 'init', 'create_tr_portfolio_item' );

function create_tr_portfolio_item() {

	$labels = array(
		'name' 					=> _x('Portfolio', 'post type general name', 'the-retailer-portfolio'),
		'singular_name' 		=> _x('Portfolio Item', 'post type singular name', 'the-retailer-portfolio'),
		'add_new' 				=> _x('Add New', 'Portfolio Item', 'the-retailer-portfolio'),
		'add_new_item' 			=> __('Add New Portfolio item', 'the-retailer-portfolio'),
		'edit_item' 			=> __('Edit Portfolio item', 'the-retailer-portfolio'),
		'new_item' 				=> __('New Portfolio item', 'the-retailer-portfolio'),
		'all_items' 			=> __('All Portfolio items', 'the-retailer-portfolio'),
		'view_item' 			=> __('View Portfolio item', 'the-retailer-portfolio'),
		'search_items' 			=> __('Search Portfolio item', 'the-retailer-portfolio'),
		'not_found' 			=>  __('No Portfolio item found', 'the-retailer-portfolio'),
		'not_found_in_trash' 	=> __('No Portfolio item found in Trash', 'the-retailer-portfolio'), 
		'parent_item_colon' 	=> '',
		'menu_name' 			=> 'Portfolio'
	);

	$args = array(
		'labels' 				=> $labels,
		'public' 				=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> true,
		'show_ui' 				=> true, 
		'show_in_menu'			=> true, 
		'show_in_rest' 			=> true,
		'show_in_nav_menus' 	=> true,
		'query_var' 			=> true,
		'rewrite' 				=> true,
		'capability_type' 		=> 'post',
		'rest_base'				=> 'portfolio-item',
		'has_archive' 			=> true, 
		'hierarchical' 			=> true,
		'menu_position' 		=> 4,
		'menu_icon'  			=> 'dashicons-category',
		'supports' 				=> array('title', 'editor', 'block-editor', 'thumbnail'),
		'rewrite' 				=> array('slug' => 'portfolio-item'),
		'with_front' 			=> FALSE
	);
	
	register_post_type('portfolio',$args);
}