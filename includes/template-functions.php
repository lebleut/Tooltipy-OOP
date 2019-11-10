<?php

/**
 * Works like the theme Wordpress get_template_part()
 */
function tooltipy_template_part( $file_prefix, $file_suffix = '' ){

    $template_file = TOOLTIPY_PLUGIN_DIR . 'public/partials/' . $file_prefix;
    if( !empty( $file_suffix ) ){
        $template_file .= '-' . $file_suffix;
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