<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header><!-- .entry-header -->
    <div class="post-thumbnail">
        <?php the_post_thumbnail(); ?>                    
    </div>

    <div class="entry-content">
        <?php
            the_content();
        ?>
    </div><!-- .entry-content -->

    <footer class="tooltip-footer">
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->