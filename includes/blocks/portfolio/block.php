<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once 'functions/function-setup.php';
include_once 'functions/function-helpers.php';

//==============================================================================
//  Frontend Output
//==============================================================================
if( !function_exists('gbt_18_tr_render_frontend_portfolio') ) {
	function gbt_18_tr_render_frontend_portfolio( $attributes ) {
		
		extract(shortcode_atts(array(
	        'number'                    => '12',
	        'categoriesSavedIDs'        => '',
	        'showFilters'               => false,
	        'columns'                   => '3',
	        'align'                     => 'center',
	        'orderby'                   => 'date_desc',
	    ), $attributes));
		ob_start();

		if( substr($categoriesSavedIDs, - 1) == ',' ) {
	        $categoriesSavedIDs = substr( $categoriesSavedIDs, 0, -1);
	    }

	    if( substr($categoriesSavedIDs, 0, 1) == ',' ) {
	        $categoriesSavedIDs = substr( $categoriesSavedIDs, 1);
	    }

	    switch ( $orderby ) {
	        case 'date_asc' :
	            $orderby = 'date';
	            $order   = 'asc';
	            break;
	        case 'date_desc' :
	            $orderby = 'date';
	            $order   = 'desc';
	            break;
	        case 'title_asc' :
	            $orderby = 'title';
	            $order   = 'asc';
	            break;
	        case 'title_desc':
	            $orderby = 'title';
	            $order   = 'desc';
	            break;
	        default:
	        	break;
	    }

	    $terms_array = array();
		$categories = explode(",",$categoriesSavedIDs);
		foreach( $categories as $cat ) {
			$terms_array[] = get_term($cat)->slug;
		}

		$portfolioItems = get_portfolio_items( $terms_array, null, $number, $order, $orderby, '' );
		portfolio_output( $portfolioItems, $columns, $showFilters, '', $categories );

		wp_reset_query();

		return ob_get_clean();
	}
}