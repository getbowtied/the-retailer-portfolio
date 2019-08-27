<?php get_header(); ?>

<div class="global_content_wrapper">

	<?php while( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		    <header class="entry-header">
				<h1 class="entry-title portfolio_item_title"><?php the_title(); ?></h1>
			</header>

		    <div class="entry-content">
				<div class="content_wrapper">
					<div class="portfolio_details_item_cat">
						<?php echo get_the_term_list( get_the_ID(), 'portfolio_filter', "", "/" ); ?>
					</div>

					<?php the_content(); ?>

		            <div class="clr"></div>

					<?php

						$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
						$next = get_adjacent_post( false, '', false );
						if ( $next || $previous ) { ?>

						<nav role="navigation" id="nav-below" class="post-navigation">
							<div class="nav-previous-single"><?php previous_post_link( '%link', '<span class="meta-nav"></span> %title' ); ?></div>
							<div class="nav-next-single"><?php next_post_link( '%link', '%title <span class="meta-nav"></span>' ); ?></div>
						</nav>

					<?php } ?>

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

					if( $related ) {
						portfolio_output( $related, 4, false, 'portfolio_related' );
					}

					?>

		        </div>
			</div>

		</article>

	<?php endwhile; ?>

</div>

<?php gbt_get_page_footer(); ?>
