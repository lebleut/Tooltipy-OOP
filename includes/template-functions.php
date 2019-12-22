<?php
use Tooltipy\Tooltipy;

/**
 * Works like the theme Wordpress get_template_part()
 */
function tooltipy_template_part( $slug, $suffix = '' ){
	// Theme templates part
	if( !empty( $suffix ) ){
		$template = $slug . '-' . $suffix . '.php';
	}else{
		$template = $slug . '.php';
	}

	$theme_template_dir =  'tooltipy';

	$located = locate_template( $theme_template_dir . '/' . $template , true, false );

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

	include $template_file;
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