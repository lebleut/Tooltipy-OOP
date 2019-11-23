<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// init
		$wiki_data = tooltipy_get_post_wiki_data( get_the_id() );
		$is_wiki = get_post_meta( get_the_ID(), 'tltpy_is_wiki', true );
	?>
	<?php $add_to_popup = tooltipy_get_option( 'add_to_popup', false, false ); ?>

	<!-- Title -->
	<?php
	if( is_array( $add_to_popup ) && in_array( 'title', $add_to_popup ) ){
		?>
		<header class="tooltipy-pop__title">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>
		<?php
	}
	?>

	<!-- Thumbnail -->
	<?php
	
		if( $is_wiki && isset($wiki_data->thumbnail) && isset($wiki_data->thumbnail->source) ){
			?>
			<div class="tooltipy-pop__thumbnail tooltipy-pop__thumbnail--wiki">
				<img src="<?php echo $wiki_data->thumbnail->source; ?>">
			</div>
			<?php
		}

		if( has_post_thumbnail( get_the_ID() ) ): ?>
			<div class="tooltipy-pop__thumbnail">
				<?php the_post_thumbnail( 'medium' ); ?>                    
			</div>
			<?php
		endif;
	?>

	<!-- Content -->
	<div class="tooltipy-pop__content">
		<?php
			echo $is_wiki ? $wiki_data->extract_html : get_the_content();
		?>
	</div><!-- .tooltipy-pop__content -->
</article><!-- #post-## -->