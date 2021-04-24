<?php
use Tooltipy\Tooltipy;

/**
 * The template for displaying the Tooltipy Gloassary page
 */

get_header(); ?>

<?php

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$first_letter = isset($_GET['letter']) ? $_GET['letter'] : '';

tooltipy_main_glossary_template( $first_letter, $paged );
?>

<?php get_footer(); ?>