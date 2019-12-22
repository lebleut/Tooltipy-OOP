<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/tooltip/tooltip-video.php
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$video_id = get_post_meta( get_the_ID(), 'tltpy_youtube_id', true );

if( $video_id ):
	?>
		<div class="tltpy_video">
			<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo($video_id); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
		</div>
	<?php
endif;