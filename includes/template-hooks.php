<?php
use Tooltipy\Tooltipy;

/**
 * Template hooks
 */


// Add the related video if assigned
add_action( 'tltpy_popup_sections', 'tltpy_popup_add_video_section' );

// Add the popup main content
add_action( 'tltpy_popup_sections', 'tltpy_popup_add_main_section' );

// Add the synonyms after the main content
add_action( 'tltpy_popup_sections', 'tltpy_popup_add_synonyms_section' );

// Add the glossary lnk
add_action( 'tltpy_popup_sections', 'tltpy_popup_add_glossary_link_section' );