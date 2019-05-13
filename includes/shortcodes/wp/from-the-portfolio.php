<?php

// [from_the_portfolio]
function shortcode_from_the_portfolio($atts, $content = null) {

	extract(shortcode_atts(array(
		"posts" => '4'
	), $atts));
	ob_start();
	?> 

    <div class="from_the_portfolio">

            <?php
    
            $args = array(
                'post_status' => 'publish',
                'post_type' => 'portfolio',
                'posts_per_page' => $posts
            );
            
            $recentPosts = new WP_Query( $args );
            
            if ( $recentPosts->have_posts() ) : ?>
                        
                <?php while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>
            
                    <div class="from_the_portfolio_item">
                        <a class="from_the_portfolio_img" href="<?php the_permalink() ?>">
                            <?php if ( has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('portfolio_4_col') ?>
                            <?php else : ?>
                            <span class="from_the_portfolio_noimg"></span>
                            <?php endif; ?>
                        </a>
                        
                        <a class="from_the_portfolio_title" href="<?php the_permalink() ?>"><h3><?php the_title(); ?></h3></a>
                        
                        <div class="portfolio_sep"></div>	
                                                    
                        <div class="from_the_portfolio_cats">
                            <?php 
                            echo strip_tags (
                                get_the_term_list( get_the_ID(), 'portfolio_filter', "",", " )
                            );
                            ?>
                        </div>
                    </div>
        
                <?php endwhile; // end of the loop. ?>
                
                <div class="clr"></div>
                
            <?php

            endif;
            
            ?>   
    </div>


	<?php
	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("from_the_portfolio", "shortcode_from_the_portfolio");