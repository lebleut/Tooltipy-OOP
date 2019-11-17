<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$add_to_popup = tooltipy_get_option( 'add_to_popup', false, false );

	if( is_array( $add_to_popup ) && in_array( 'title', $add_to_popup ) ){
		?>
		<header class="tooltipy-pop__title">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .tooltipy-pop__title -->
		<?php
	}
	?>
	<?php if( has_post_thumbnail( get_the_ID() ) ): ?>
		<div class="tooltipy-pop__thumbnail">
			<?php the_post_thumbnail( 'medium' ); ?>                    
		</div>
	<?php endif; ?>

	<div class="tooltipy-pop__content">
		<?php
			the_content();
		?>
	</div><!-- .tooltipy-pop__content -->
</article><!-- #post-## -->

.tooltipy-pop