<?php 

global $wp_query, $post;

get_header(); 

?>

<div class="global_content_wrapper">

	<div class="container_12">

	    <div class="grid_4 push_8">
	    
			<div class="aside_portfolio">
				
				<div class="entry-content-aside">
					<?php while( have_posts() ) : the_post(); ?>
	                
		                <h1 class="entry-title portfolio_item_title"><?php the_title(); ?></h1>
		                
		                <div class="portfolio_details_sep"></div>
		                
		                <div class="portfolio_details_item_cat">                    
		                    <?php echo get_the_term_list( get_the_ID(), 'portfolio_filter', "", "/" ); ?>
		                </div>
		                
						<div><?php if( !empty( $post->post_excerpt ) ) { the_excerpt(); } ?></div>
	    
	                <?php endwhile; ?>
	            </div>

	        </div>
	        
	    </div>
	    
	    <div class="grid_8 pull_4">

			<div id="primary" class="content-area">
				
				<div id="content" class="site-content" role="main">

				<div class="entry-content entry-content-portfolio">
					<?php while( have_posts() ) { the_post(); the_content(); } ?>
	            </div>

				</div>

			</div>

		</div>
	    
	    <div class="clr"></div>
	    
	</div>

</div>

<div class="container_12 portfolio_content_nav">
	<?php

		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );
		if ( $next || $previous ) { ?>

		<nav role="navigation" id="nav-below" class="post-navigation">
			<div class="nav-previous-single"><?php previous_post_link( '%link', '<span class="meta-nav"></span> %title' ); ?></div>
			<div class="nav-next-single"><?php next_post_link( '%link', '%title <span class="meta-nav"></span>' ); ?></div>
		</nav>

	<?php } ?>
</div>

<?php

$terms 		= get_the_terms( $post->ID, 'portfolio_filter');
$related 	= array();

if ($terms) {
	$terms_array = array();
	foreach ($terms as $term) {
		$terms_array[] = $term->slug;
	}

	$related = get_portfolio_items( $terms_array, null, 4, '', 'rand', array( $post->ID ) );
}

if( $related ) { ?>

	<div class="container_12">
		<?php portfolio_output( $related, 4, false, 'portfolio_related' ); ?>
    </div>

<?php } ?>
           
<?php gbt_get_page_footer(); ?>