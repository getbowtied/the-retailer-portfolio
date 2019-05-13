<?php

$portfolio_items_per_row 	= get_option( 'tr_portfolio_items_per_row', 3 );
$portfolio_items_order_by 	= get_option( 'tr_portfolio_items_order_by', 'date' );
$portfolio_items_order 		= get_option( 'tr_portfolio_items_order', 'DESC' );

?>

<?php get_header(); ?>

<div class="global_content_wrapper listing_portfolio_no_sidebar">

<div class="container_12">

    <div class="grid_12">
    
    	<?php $term = $wp_query->queried_object; ?>
    
    	<h1 class="entry-title portfolio_title"><?php echo $term->name; ?></h1>
  
        <div class="content-area portfolio_section">
        	<div class="content_wrapper">				
				
                <div class="items_wrapper">
				
				<?php
				
				$number_of_portfolio_items = new WP_Query(array(
					'post_type' => 'portfolio',
					'portfolio_filter' => $term->slug,
					'posts_per_page' => 99999,
				));
				
				$portfolio_items = $number_of_portfolio_items->post_count;
				
				$temp = $wp_query;
				$wp_query = null;
				$post_counter = 0;

				$wp_query = new WP_Query(array(
					'post_type' => 'portfolio',
					'portfolio_filter' => $term->slug,
					'posts_per_page' => 99999,
					'orderby' => $portfolio_items_order_by,
					'order' => $portfolio_items_order,
					'paged' => $paged
				));

				// Detect page and page limit
				$paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
								
				while ($wp_query->have_posts()) : $wp_query->the_post();
					$post_counter++;
					$related_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
				?>

					<div class="portfolio_<?php echo $portfolio_items_per_row; ?>_col_item_wrapper mix <?php echo strtolower(strip_tags(get_the_term_list( get_the_ID(), 'portfolio_filter', ""," " ))); ?>">
                        <div class="portfolio_item">
                            <div class="portfolio_item_img_container">
								<a class="img_zoom_in" href="<?php echo get_permalink(get_the_ID()); ?>">
									<img src="<?php echo $related_thumb[0]; ?>" alt="" />
								</a>
							</div>
                            <a class="portfolio-title" href="<?php echo get_permalink(get_the_ID()); ?>"><h3><?php the_title(); ?></h3></a>
                            <div class="portfolio_sep"></div>
                            <div class="portfolio_item_cat">
    
                            <?php 
                            echo strip_tags (
                                get_the_term_list( get_the_ID(), 'portfolio_filter', "",", " )
                            );
                            ?>
                            
                            </div>
                        </div>
                    </div>
				
				<?php endwhile; // end of the loop. ?>
                
                </div>
                            
                <?php $wp_query = null; $wp_query = $temp;?>
                
                <div class="clr"></div>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->
        
        <?php if( function_exists( 'theretailer_content_nav' ) ) {
    		theretailer_content_nav( 'nav-below' ); 
    	} ?>
        
	</div>

</div>

</div>

<!--Mobile trigger footer widgets-->
<?php $dark_footer = get_theme_mod('dark_footer_all_site', 0); ?>

<?php if ( $dark_footer == 0 ) : ?>
	<div class="trigger-footer-widget-area">
		<i class="getbowtied-icon-more-retailer"></i>
	</div>
<?php endif; ?>

<div class="gbtr_widgets_footer_wrapper">

<?php get_template_part("dark_footer"); ?>

<?php get_footer(); ?>