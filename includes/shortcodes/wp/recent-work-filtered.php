<?php

// [recent_work_filtered]
function tr_ext_shortcode_recent_work_filtered( $atts, $content = null ) {

	extract( shortcode_atts( array(
		  'items_per_row' => '4'
	   ), $atts )
    );
    
	ob_start();
    
    $wp_query = get_portfolio_items();

    if( $wp_query ) {
        portfolio_output( $wp_query, $items_per_row ); 
    }

	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode("recent_work_filtered", "tr_ext_shortcode_recent_work_filtered");