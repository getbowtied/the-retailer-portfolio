<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once 'functions/function-setup.php';
include_once 'functions/function-helpers.php';

//==============================================================================
//  Frontend Output
//==============================================================================
if( !function_exists('gbt_18_tr_render_frontend_portfolio') ) {
	function gbt_18_tr_render_frontend_portfolio( $attributes ) {

		global $theretailer_theme_options;
		
		$sliderrandomid = rand();
		$u = uniqid();
		
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
	    
	    $args = array(                  
	        'post_status'           => 'publish',
	        'post_type'             => 'portfolio',
	        'posts_per_page'        => $number
	    );

	    switch ( $orderby ) {
	        case 'date_asc' :
	            $args['orderby'] = 'date';
	            $args['order']   = 'asc';
	            break;
	        case 'date_desc' :
	            $args['orderby'] = 'date';
	            $args['order']   = 'desc';
	            break;
	        case 'title_asc' :
	            $args['orderby'] = 'title';
	            $args['order']   = 'asc';
	            break;
	        case 'title_desc':
	            $args['orderby'] = 'title';
	            $args['order']   = 'desc';
	            break;
	        default: break;
	    }

	    if( $categoriesSavedIDs != '' ) {
	        $args['tax_query'] = array(
	            array(
	                'taxonomy'  => 'portfolio_filter',
	                'field'     => 'term_id',
	                'terms'     => explode(",",$categoriesSavedIDs)
	            ),
	       );
	    }

	    $portfolioItems = get_posts( $args );

	    if ( !empty($portfolioItems) && $showFilters ) :

	    	$portfolio_categories_queried = [];

	    	if( $categoriesSavedIDs != '' ) :

		    	$categories = explode(",",$categoriesSavedIDs);
		    
		        foreach($categories as $catID) :
		            
		            $category = get_term( $catID, 'portfolio_filter' );
		            
		            if ( !empty( $category ) && !is_wp_error( $category ) ) {
		                $portfolio_categories_queried[$category->slug] = $category->name;
		            }
		            
		        endforeach;

		    else :
	    
		        foreach($portfolioItems as $post) :
		            
		            $terms = get_the_terms( $post->ID, 'portfolio_filter' );
		            
		            if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
		                foreach($terms as $term) {
		                    $portfolio_categories_queried[$term->slug] = $term->name;
		                }
		            }
		            
		        endforeach;

		    endif;

	        $portfolio_categories_queried = array_unique($portfolio_categories_queried);

		    if ( !empty( $portfolio_categories_queried ) && !is_wp_error( $portfolio_categories_queried ) ){
		        echo '<ul class="portfolio_categories">';
		            echo '<li class="filter controls-'.$u.'" data-filter="all">' . __("All", "theretailer-extender") . '</li>';
		        foreach ( $portfolio_categories_queried as $key => $value ) {
		            echo '<li class="filter controls-'.$u.'" data-filter=".'.$key.'">' . $value . '</li>';
		        }
		        echo '</ul>';
		    }
		endif;

		?>

		<div class="wp-block-gbt-portfolio <?php echo $align; ?>">

		    <div class="portfolio_section shortcode_portfolio mixitup-<?php echo $u;?>">		
		            
		        <div class="items_wrapper">
		        
		        <?php
	                        
		        foreach($portfolioItems as $post) :
		            $related_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
		            
		            $terms_slug = get_the_terms( $post->ID, 'portfolio_filter' );

		            $term_slug_class = "";
		            
		            if ( !empty( $terms_slug ) && !is_wp_error( $terms_slug ) ){
		                foreach ( $terms_slug as $term_slug ) {
		                    $term_slug_class .=  $term_slug->slug . " ";
		                }
		            }
		            
		        ?>

		            <div class="portfolio_<?php echo $columns; ?>_col_item_wrapper mix <?php echo $term_slug_class; ?>">
		                <div class="portfolio_item">
		                	<?php if($related_thumb[0]) : ?>
		                    <div class="portfolio_item_img_container">
		                        <a class="img_zoom_in" href="<?php echo get_permalink($post->ID); ?>">
		                            <img src="<?php echo $related_thumb[0]; ?>" alt="" />
		                        </a>
		                    </div>
		                    <?php endif; ?>
		                    <a class="portfolio-title" href="<?php echo get_permalink($post->ID); ?>"><h3><?php echo $post->post_title; ?></h3></a>
		                    <div class="portfolio_sep"></div>
		                    <div class="portfolio_item_cat">

		                    <?php 
		                    echo strip_tags (
		                        get_the_term_list( $post->ID, 'portfolio_filter', "",", " )
		                    );
		                    ?>
		                    
		                    </div>
		                </div>
		            </div>
		        
		        <?php endforeach; ?>
		        
		        </div>
		        
		        <div class="clr"></div>
		        
		    </div>

		</div>

	    <script type="text/javascript">
	    	jQuery(document).ready(function($) {
	        	jQuery('.mixitup-<?php echo $u; ?>').mixItUp({
			     	selectors: {
			       		filter: '.controls-<?php echo $u; ?>'
			     	}
		    	});
			});

	    </script>
	    
		<?php
		wp_reset_query();

		return ob_get_clean();
	}
}