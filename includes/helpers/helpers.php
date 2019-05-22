<?php

/*
* Get Portfolio Items
*/
function get_portfolio_items( $categories = '', $paged = null, $posts_per_page = 99999, $order = '', $orderby = '', $exclude = '' ) {

	$portfolio_items_order_by 	= !empty($orderby) ? $orderby : get_option( 'tr_portfolio_items_order_by', 'date' );
	$portfolio_items_order 		= !empty($order) ? $order : get_option( 'tr_portfolio_items_order', 'DESC' );

	$args = array(
        'post_type' 	 => 'portfolio',
        'posts_per_page' => $posts_per_page,
        'orderby' 		 => $portfolio_items_order_by,
        'order' 		 => $portfolio_items_order 
    );

    if( !empty($categories) ) {
    	$args['portfolio_filter'] = $categories;
    }

    if( isset($paged) && !empty($paged) && is_numeric($paged) ) {
    	$args['paged'] = $paged;
    }

    if( isset($exclude) && !empty($exclude) ) {
    	$args['post__not_in'] = $exclude;
    }

	$wp_query = new WP_Query( $args );

	return $wp_query;
}

/*
* Get Portfolio Output
*/
function portfolio_output( $wp_query, $items_per_row = 3, $filters = true, $extra_class = '', $categories = array() ) { ?>

	<?php if( $wp_query->have_posts() ) : ?>

		<div class="content-area portfolio_section mixitup gbt_portfolio_wrapper <?php echo $extra_class; ?>">

			<?php

				if( $filters ) {
					if( !empty( $categories ) ) {
						$terms = array();
						foreach( $categories as $cat ) {
							$category = get_term($cat);
							$new_term['slug'] = $category->slug;
							$new_term['name'] = $category->name;
							$terms[] = $new_term;
						}
					} else {
						$terms = array();
						$categories = get_terms( 'portfolio_filter' );
						foreach( $categories as $cat ) {
							$new_term['slug'] = $cat->slug;
							$new_term['name'] = $cat->name;
							$terms[] = $new_term;
						}
					}

				    if ( !empty( $terms ) && !is_wp_error( $terms ) ){
				        echo '<ul class="portfolio_categories">';
				            echo '<li class="filter controls" data-filter="all">' . __("All", "the-retailer-portfolio") . '</li>';
				        foreach ( $terms as $term ) {
				            echo '<li class="filter controls" data-filter=".' . strtolower($term['slug']) . '">' . $term['name'] . '</li>';
				        }
				        echo '</ul>';
				    }
				}

			?>

	    	<div class="content_wrapper">

				<?php while ($wp_query->have_posts()) : $wp_query->the_post();

		            $related_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
		            $categories = '';
		            $terms = get_the_terms( get_the_ID(), 'portfolio_filter' );
					foreach ( $terms as $term ) {
						$categories .= strtolower($term->slug) . ' ';
					}
				?>

					<div class="portfolio_item portfolio_<?php echo $items_per_row; ?>_col_item_wrapper mix <?php echo $categories; ?>">
	                    
                    	<a class="img_zoom_in" href="<?php echo get_permalink(get_the_ID()); ?>">
                            <div class="portfolio_item_img_container" style="background-image:url(<?php echo $related_thumb[0]; ?>)"></div>
						</a>

                        <a class="portfolio-title" href="<?php echo get_permalink(get_the_ID()); ?>">
                        	<h3><?php the_title(); ?></h3>
                        </a>

                        <div class="portfolio_sep"></div>

                        <div class="portfolio_item_cat">
                            <?php echo strip_tags ( get_the_term_list( get_the_ID(), 'portfolio_filter', "",", " ) ); ?>
                        </div>

	                </div>
				
				<?php endwhile; ?>

	            <div class="clr"></div>

			</div>
		</div>

	<?php endif; ?>

<?php
}

/*
* Get Page Footer
*/
function gbt_get_page_footer() {

	// Mobile trigger footer widgets
	$dark_footer = get_theme_mod('dark_footer_all_site', '0');
	if ( $dark_footer == '0' ) { ?>
		<div class="trigger-footer-widget-area">
			<i class="getbowtied-icon-more-retailer"></i>
		</div>
	<?php } ?>

	<div class="gbtr_widgets_footer_wrapper">

	<?php

	get_template_part("dark_footer");

	get_footer();
}