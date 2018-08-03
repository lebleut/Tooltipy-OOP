<?php if( has_post_thumbnail( get_the_ID() ) ): ?>
    <div class="tooltipy-pop-image"><?php echo get_the_post_thumbnail( get_the_ID(), 'medium' ); ?></div>
<?php endif; ?>
<div class="tooltipy-pop-content"><?php the_content(); ?></div>