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