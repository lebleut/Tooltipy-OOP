<?php
use Tooltipy\Tooltipy;

/**
 * The template for displaying the Tooltipy Gloassary page
 */

get_header(); ?>

<?php

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// use 'urldecode()' to consider Unicode chars
$glossary_first_letter = urldecode(get_query_var('letter'));

tooltipy_main_glossary_template( $glossary_first_letter, $paged );
?>

<?php get_footer(); ?>