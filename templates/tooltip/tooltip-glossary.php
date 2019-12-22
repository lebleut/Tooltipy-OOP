<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/tooltip/tooltip-glossary.php
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$add_to_popup = tooltipy_get_option( 'add_to_popup', false, false );
$glossary_page_id = tooltipy_get_option( 'glossary_page', false );

if( $glossary_page_id && is_array( $add_to_popup ) && in_array( 'glossary', $add_to_popup ) ){
	$glossary_permalink = get_page_link( $glossary_page_id );
	?>
	<div class="tooltipy-glossary-link">
		<a href="<?php echo $glossary_permalink; ?>"><?php echo tooltipy_get_option( 'glossary_link_label', false ); ?></a>
	</div>
	<?php
}