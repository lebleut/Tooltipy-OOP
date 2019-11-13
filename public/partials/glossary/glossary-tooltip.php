<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h2 class="entry-title">
            <?php the_title(); ?>
        </h2><!-- .entry-header -->
    <div class="entry-content">
        <?php
            the_content();
        ?>
    </div><!-- .entry-content -->
</article><!-- #post-## -->