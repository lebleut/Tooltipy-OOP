<?php
$add_to_popup = tooltipy_get_option( 'add_to_popup', false, false );
$label = tooltipy_get_option( 'glossary_link_label', false );
$glossary_page_id = tooltipy_get_option( 'glossary_page', false );

if( $glossary_page_id && is_array( $add_to_popup ) && in_array( 'glossary', $add_to_popup ) ){
	$glossary_permalink = get_page_link( $glossary_page_id );
	?>
	<div class="tooltipy-glossary-link">
		<a href="<?php echo $glossary_permalink; ?>"><?php echo $label; ?></a>
	</div>
	<?php
}