<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/tooltip/tooltip-readmore.php
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$add_to_popup = tooltipy_get_option( 'add_to_popup', false, false );

if( is_array( $add_to_popup ) && in_array( 'readmore', $add_to_popup ) ){
	?>
	<div class="tooltipy-readmore-link">
		<a href="<?php echo get_the_permalink(); ?>"><?php echo __tooltipy( 'Read more' ); ?></a>
	</div>
	<?php
}