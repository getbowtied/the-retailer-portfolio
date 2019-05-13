<?php
/*
Template Name: Portfolio
*/

$portfolio_items_per_row 	= get_option( 'tr_portfolio_items_per_row', 3 );
$portfolio_items_order_by 	= get_option( 'tr_portfolio_items_order_by', 'date' );
$portfolio_items_order 		= get_option( 'tr_portfolio_items_order', 'DESC' );

if (isset($_GET["portfolio_cols"])) $portfolio_items_per_row = $_GET["portfolio_cols"];

$u = uniqid();
?>

<?php get_header(); ?>

<div class="global_content_wrapper">

<div class="container_12">

    <div class="grid_12">
    
    	<h1 class="entry-title portfolio_title"><?php the_title(); ?></h1>
        
        <?php
        
		$terms = get_terms("portfolio_filter");
		if ( !empty( $terms ) && !is_wp_error( $terms ) ){
			echo '<ul class="portfolio_categories">';
            	echo '<li class="filter controls-'.$u.'" data-filter="all">' . __("All", "the-retailer-portfolio") . '</li>';
    		foreach ( $terms as $term ) {
            	echo '<li class="filter controls-'.$u.'" data-filter=".' . strtolower($term->slug) . '">' . $term->name . '</li>';
			}
			echo '</ul>';
		}
		
		?>
  
        <div class="content-area portfolio_section mixitup mixitup-<?php echo $u;?>">
        	<div class="content_wrapper">				
				
                <div class="items_wrapper shortcode_portfolio">
				
				<?php
				
				$temp = $wp_query;
				$wp_query = null;
				$post_counter = 0;
				
				$wp_query = new WP_Query(array(
					'post_type' => 'portfolio',
					'posts_per_page' => 99999,
					'orderby' => $portfolio_items_order_by,
					'order' => $portfolio_items_order,
					'paged' => $paged
				));
				
				// Detect page and page limit
				$max = $wp_query->max_num_pages;
				$paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
												
				while ($wp_query->have_posts()) : $wp_query->the_post();
					$post_counter++;
					$related_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
					
					$terms_slug = get_the_terms( get_the_ID(), 'portfolio_filter' ); // get an array of all the terms as objects.

					$term_slug_class = "";
					
					if ( !empty( $terms_slug ) && !is_wp_error( $terms_slug ) ){
						foreach ( $terms_slug as $term_slug ) {
							$term_slug_class .=  $term_slug->slug . " ";
						}
					}
					
				?>

					<div class="portfolio_<?php echo $portfolio_items_per_row; ?>_col_item_wrapper mix <?php echo $term_slug_class; ?>">
                        <div class="portfolio_item">
                            <div class="portfolio_item_img_container">
								<a class="img_zoom_in" href="<?php echo get_permalink(get_the_ID()); ?>">
									<img src="<?php echo $related_thumb[0]; ?>" alt="" />
								</a>
							</div>
                            <a  class="portfolio-title" href="<?php echo get_permalink(get_the_ID()); ?>"><h3><?php the_title(); ?></h3></a>
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

	    <script type="text/javascript">
	    	jQuery(document).ready(function($) {
	        	jQuery('.mixitup-<?php echo $u; ?>').mixItUp({
			     	selectors: {
			       		filter: '.controls-<?php echo $u; ?>'
			     	}
		    	});
			});

	    </script>
        
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