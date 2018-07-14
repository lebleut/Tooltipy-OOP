<?php
/**
 * The main Tooltipy single template to replace the default single.php for Tooltipy post_type
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) :
            the_post();
            
            tooltipy_template_part( 'tooltip', 'content' );
            tooltipy_template_part( 'tooltip', 'synonyms' );
            tooltipy_template_part( 'tooltip', 'related' );

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
