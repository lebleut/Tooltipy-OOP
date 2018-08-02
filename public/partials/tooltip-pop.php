<?php if( has_post_thumbnail( $id ) ): ?>
    <div class="tooltipy-pop-image"><?php echo get_the_post_thumbnail( $id, 'medium' ); ?></div>
<?php endif; ?>
<div class="tooltipy-pop-content"><?php echo $content; ?></div>