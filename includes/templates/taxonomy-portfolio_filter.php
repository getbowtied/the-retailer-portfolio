<?php

$term 			= $wp_query->queried_object;
$wp_query 		= get_portfolio_items( $term->slug, $paged );
$paged 			= ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
$items_per_row 	= get_option( 'tr_portfolio_items_per_row', 3 );

get_header();

?>

<div class="global_content_wrapper listing_portfolio_no_sidebar">
	<div class="container_12">
	    <div class="grid_12">
	    
	    	<h1 class="entry-title portfolio_title"><?php echo $term->name; ?></h1>

	        <?php portfolio_output( $wp_query, $items_per_row, false ); ?>
	        
		</div>
	</div>
</div>

<?php gbt_get_page_footer(); ?>