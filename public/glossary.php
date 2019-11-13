<?php
/**
 * The template for displaying the Tooltipy Gloassary page
 */

get_header(); ?>

<?php
global $wp_query;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$first_letter = get_query_var( 'letter' );

$postids = tooltipy_get_posts_id_start_with( $first_letter );

$args = array(
	'post_type' 	=> Tooltipy::get_plugin_name(),
	'post__in' 		=> $postids,
	'paged' 		=> $paged,
	'post_status' 	=> 'publish',
);

// The Query
$wp_query = new WP_Query( $args );

?>
	<div class="wrap">
		<?php tooltipy_template_part( 'glossary/glossary', 'header' ); ?>

		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header" style="width: 100%;">
					<h1 class="page-title">Glossary page</h1>
				</header><!-- .page-header -->
				<div class="tooltipy-glossary-elems">
					<?php
					// Start the Loop.
					while ( have_posts() ) :
						the_post();

						tooltipy_template_part( 'glossary', 'content' );

					endwhile;

					// Previous/next page navigation.
					the_posts_pagination(
						array(
							'prev_text'          => __( 'Previous page', 'tooltipy-lang' ),
							'next_text'          => __( 'Next page', 'tooltipy-lang' ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'tooltipy-lang' ) . ' </span>',
						)
					);
					// If no content, include the "No posts found" template.
					?>
				</div>
			<?php else : ?>
				<?php tooltipy_template_part( 'glossary/glossary', 'none' ); ?>
			<?php endif; ?>

			</main><!-- .site-main -->
		</div><!-- .content-area -->
	</div>
    <?php
    /* Restore original Post Data */
    
    wp_reset_postdata(); ?>

<?php get_footer(); ?>