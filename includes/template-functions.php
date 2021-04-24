<?php
use Tooltipy\Tooltipy;

/**
 * Works like the theme Wordpress get_template_part()
 */
function tooltipy_template_part( $slug, $suffix = '', $args = [] ){
	// Theme templates part
	if( !empty( $suffix ) ){
		$template = $slug . '-' . $suffix . '.php';
	}else{
		$template = $slug . '.php';
	}

	$theme_template_dir =  'tooltipy';

	$located = locate_template( $theme_template_dir . '/' . $template , true, false, $args );

	if( $located ){
		return;
	}

	// Plugin templates part
	$template_dir = TOOLTIPY_PLUGIN_DIR . 'templates/';

	$template_file = $template_dir . $slug;
	if( !empty( $suffix ) ){
		$template_file .= '-' . $suffix;
	}
	$template_file .= '.php';

	load_template( $template_file, false, $args );
}

/**
 * Main popup content
 */
function tltpy_popup_add_main_section(){
	tooltipy_template_part( 'tooltip', 'content' );
}

/**
 * Adds the synonym section to the popup content
 */
function tltpy_popup_add_synonyms_section(){

	tooltipy_template_part( 'tooltip/tooltip', 'synonyms' );
}

/**
 * Adds video section to the popup content
 */
function tltpy_popup_add_video_section(){
	tooltipy_template_part( 'tooltip/tooltip', 'video' );
}

/**
 * Adds glossary link section to the popup content
 */
function tltpy_popup_add_glossary_link_section(){
	tooltipy_template_part( 'tooltip/tooltip', 'glossary' );
}

/**
 * Prepare the main template of the glossary and display it
 */
function tooltipy_main_glossary_template( $first_letter = '', $paged = '' ){
	global $wp_query, $tooltipy_is_glossary_page;
	$tooltipy_is_glossary_page = true;

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

	tooltipy_template_part( 'glossary' );

	/* Restore original Post Data */ 
	wp_reset_postdata();
}