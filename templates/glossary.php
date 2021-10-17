<?php
/**
 * Tooltipy Glossary template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/glossary.php
 */

 // If this file is called directly, abort.
 if ( ! defined( 'WPINC' ) ) {
	die;
}

use Tooltipy\Tooltipy;

?>
<div class="tooltipy-glossary-wrap wrap">
    <?php tooltipy_template_part( 'glossary/glossary', 'header' ); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php if ( have_posts() ) : ?>

            <header class="page-header" style="width: 100%;">
                <h1 class="page-title">Glossary page</h1>
            </header><!-- .page-header -->
            <div class="tooltipy-glossary-elems">
                <?php
                // Start the Loop.
                while ( have_posts() ) :
                    the_post();

                    tooltipy_template_part( 'glossary', 'content' );

                endwhile;
                $prev_opt = tooltipy_get_option( 'glossary_label_previous' );
                $next_opt = tooltipy_get_option( 'glossary_label_next' );

                $prev_label = $prev_opt && '' !== trim( $prev_opt ) ? trim( $prev_opt ) : __tooltipy( 'Previous page' );
                $next_label = $next_opt && '' !== trim( $next_opt ) ? trim( $next_opt ) : __tooltipy( 'Next page' );

                // Previous/next page navigation.
                the_posts_pagination(
                    array(
                        'base'               => Tooltipy::get_glossary_page_link() . '%_%',
                        'prev_text'          => $prev_label,
                        'next_text'          => $next_label,
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __tooltipy( 'Page' ) . ' </span>',
                    )
                );
                // If no content, include the "No posts found" template.
                ?>
            </div>
        <?php else : ?>
            <?php tooltipy_template_part( 'glossary/glossary', 'none' ); ?>
        <?php endif; ?>

        </main><!-- .site-main -->
    </div><!-- .content-area -->
</div>