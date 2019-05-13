<?php
	
	if (get_post_meta( $post->ID, 'portfolio_title_meta_box_check', true )) {
		$portfolio_title_option = get_post_meta( $post->ID, 'portfolio_title_meta_box_check', true );
	} else {
		$portfolio_title_option = "on";
	}
	
?>

<?php get_header(); ?>

<div class="full-width-page page-portfolio-single <?php echo ( (isset($portfolio_title_option)) && ($portfolio_title_option == "on") ) ? 'page-title-shown':'page-title-hidden';?>">
		
    <div id="primary" class="content-area">
	   
		<div id="content" class="site-content" role="main">

			<header class="entry-header entry-header-portfolio-single">
	
				<div class="row">
					<div class="large-10 large-centered columns">
		
						<?php while ( have_posts() ) : the_post(); ?>
    
                            <?php if ( (isset($portfolio_title_option)) && ($portfolio_title_option == "off") ) : ?>
                                <h1 class="entry-title portfolio_item_title"><?php the_title(); ?></h1>
                            <?php endif; ?>
						
						<?php endwhile; // end of the loop. ?>				
						
					</div><!--.large-->
				</div><!--.row-->
	
			</header><!-- .entry-header -->
    
			<div class="entry-content entry-content-portfolio">
				
				<?php while ( have_posts() ) : the_post(); ?>
                	<?php the_content(); ?>
                <?php endwhile; // end of the loop. ?>
				
            </div>

		</div><!-- #content .site-content -->
	
		
		<div class="portfolio_content_nav">
			<?php mr_tailor_content_nav( 'nav-below' ); ?>
		</div>
	
	
	</div><!-- #primary .content-area -->

</div><!--.full-width-page-->

<?php get_footer(); ?>