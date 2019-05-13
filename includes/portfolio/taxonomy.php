<?php

add_action( 'init', 'create_portfolio_categories' );

function create_portfolio_categories() {
	
	$labels = array(
		'name'                       => _x('Portfolio Categories', 'taxonomy general name', 'the-retailer-portfolio'),
		'singular_name'              => _x('Portfolio Category', 'taxonomy singular name', 'the-retailer-portfolio'),
		'search_items'               => __('Search Portfolio Categories', 'the-retailer-portfolio'),
		'popular_items'              => __('Popular Portfolio Categories', 'the-retailer-portfolio'),
		'all_items'                  => __('All Portfolio Categories', 'the-retailer-portfolio'),
		'edit_item'                  => __('Edit Portfolio Category', 'the-retailer-portfolio'),
		'update_item'                => __('Update Portfolio Category', 'the-retailer-portfolio'),
		'add_new_item'               => __('Add New Portfolio Category', 'the-retailer-portfolio'),
		'new_item_name'              => __('New Portfolio Category Name', 'the-retailer-portfolio'),
		'separate_items_with_commas' => __('Separate Portfolio Categories with commas', 'the-retailer-portfolio'),
		'add_or_remove_items'        => __('Add or remove Portfolio Categories', 'the-retailer-portfolio'),
		'choose_from_most_used'      => __('Choose from the most used Portfolio Categories', 'the-retailer-portfolio'),
		'not_found'                  => __('No Portfolio Category found.', 'the-retailer-portfolio'),
		'menu_name'                  => __('Portfolio Categories', 'the-retailer-portfolio'),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'show_in_rest'			=> true,
		'rest_base'				=> 'portfolio-category',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'portfolio-category' ),
	);

	register_taxonomy("portfolio_filter", "portfolio", $args);
}

add_filter( 'option_posts_per_page', 'tdd_tax_filter_posts_per_page' );

function tdd_tax_filter_posts_per_page( $value ) {
	
    return (is_tax('portfolio_filter')) ? 1 : $value;
}