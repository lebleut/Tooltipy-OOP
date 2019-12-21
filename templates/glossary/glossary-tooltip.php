<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/glossary/glossary-tooltip.php
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		tooltipy_template_part( 'tooltip', 'content' );
	?>
</article><!-- #post-## -->