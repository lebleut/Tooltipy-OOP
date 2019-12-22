<?php
/**
 * The main Tooltipy single template to replace the default single.php for Tooltipy post_type
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			// Start the loop.
			while ( have_posts() ) :
				the_post();
				
				tooltipy_template_part( 'tooltip', 'content' );
				tooltipy_template_part( 'tooltip/tooltip', 'synonyms' );
				tooltipy_template_part( 'tooltip/tooltip', 'related' );

				// End of the loop.
			endwhile;
			?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
</div>
<?php get_footer(); ?>
