<?php

$video_id = get_post_meta( get_the_ID(), 'tltpy_youtube_id', true );

if( $video_id ):
    ?>
        <div class="tltpy_video">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo($video_id); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    <?php
endif;