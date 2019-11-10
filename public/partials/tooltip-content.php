<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    $add_to_popup = tooltipy_get_option( 'add_to_popup', false, false );

    if( is_array( $add_to_popup ) && in_array( 'title', $add_to_popup ) ){
        ?>
        <header class="entry-header">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        </header><!-- .entry-header -->
        <?php
    }
    ?>
    <?php if( has_post_thumbnail( get_the_ID() ) ): ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'medium' ); ?>                    
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
            the_content();
        ?>
    </div><!-- .entry-content -->
</article><!-- #post-## -->