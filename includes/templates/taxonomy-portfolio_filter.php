<?php

$term 			= $wp_query->queried_object;
$wp_query 		= get_portfolio_items( $term->slug, $paged );
$paged 			= ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
$items_per_row 	= get_option( 'tr_portfolio_items_per_row', 3 );

get_header();

?>

<div class="global_content_wrapper">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	    <header class="entry-header">
			<h1 class="entry-title portfolio_title"><?php echo $term->name; ?></h1>
		</header>

	    <div class="entry-content">
			<div class="content_wrapper">
				<?php portfolio_output( $wp_query, $items_per_row, false ); ?>
	        </div>
		</div>

	</article>

    <div class="clear"></div>

</div>

<?php gbt_get_page_footer(); ?>
