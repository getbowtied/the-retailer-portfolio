<?php
/*
Template Name: Portfolio
*/

$portfolio_items_per_row = get_option( 'tr_portfolio_items_per_row', 3 );
if( isset( $_GET["portfolio_cols"] ) ) {
	$portfolio_items_per_row = $_GET["portfolio_cols"];
}

$wp_query = get_portfolio_items( '', $paged );
$paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;

get_header();

?>

<div class="global_content_wrapper">

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	    <header class="entry-header">
			<h1 class="entry-title portfolio_title"><?php the_title(); ?></h1>
		</header>

	    <div class="entry-content">
			<div class="content_wrapper">
				<?php portfolio_output( $wp_query, $portfolio_items_per_row ); ?>
	        </div>
		</div>

	</article>

    <div class="clear"></div>

</div>

<?php gbt_get_page_footer(); ?>
