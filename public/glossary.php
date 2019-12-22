<?php
use Tooltipy\Tooltipy;

/**
 * The template for displaying the Tooltipy Gloassary page
 */

get_header(); ?>

<?php
global $wp_query, $tooltipy_is_glossary_page;

$tooltipy_is_glossary_page = true;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$first_letter = get_query_var( 'letter' );

$postids = tooltipy_get_posts_id_start_with( $first_letter );

$args = array(
	'post_type' 	=> Tooltipy::get_plugin_name(),
	'post__in' 		=> $postids,
	'paged' 		=> $paged,
	'post_status' 	=> 'publish',
);

// posts per page
$posts_per_page = tooltipy_get_option( 'glossary_tooltips_per_page', false );

if( !empty($posts_per_page) && intval($posts_per_page) > 0 ){
	$args['posts_per_page'] = $posts_per_page;
}

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
					$prev_opt = tooltipy_get_option( 'glossary_label_previous' );
					$next_opt = tooltipy_get_option( 'glossary_label_next' );

					$prev_label = $prev_opt && '' !== trim( $prev_opt ) ? trim( $prev_opt ) : __tooltipy( 'Previous page' );
					$next_label = $next_opt && '' !== trim( $next_opt ) ? trim( $next_opt ) : __tooltipy( 'Next page' );

					// Previous/next page navigation.
					the_posts_pagination(
						array(
							'prev_text'          => $prev_label,
							'next_text'          => $next_label,
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __tooltipy( 'Page' ) . ' </span>',
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