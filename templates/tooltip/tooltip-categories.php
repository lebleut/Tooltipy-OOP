<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/tooltip/tooltip-categories.php
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
use Tooltipy\Tooltipy;

?>
<h2>Categories</h2>
<?php
global $post;

$terms = get_the_terms( $post, Tooltipy::get_taxonomy());

if( !empty( $terms ) ): ?>
	<ul>
		<?php foreach ($terms as $term): ?>
			<li><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a></li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
	<div>
		<i><?php _e( 'No categories for this tooltip.', 'tooltipy' ); ?></i>
	</div>
<?php endif;?>