<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/tooltip/tooltip-related.php
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<h2>Related posts</h2>
<?php
global $post;

$related_posts = tooltipy_get_related_posts();

if( !empty( $related_posts ) ): ?>
	<ul>
		<?php foreach ($related_posts as $related): ?>
			<li><a href="<?php echo $related['permalink']; ?>"><?php echo $related['title']; ?></a></li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
	<div>
		<i><?php _e( 'No related posts for this tooltip.', 'tooltipy' ); ?></i>
	</div>
<?php endif;?>