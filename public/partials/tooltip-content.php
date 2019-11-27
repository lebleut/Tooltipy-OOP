<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// init
		global $tooltipy_is_glossary_page;
		$wiki_data = tooltipy_get_post_wiki_data( get_the_id() );
		$is_wiki = get_post_meta( get_the_ID(), 'tltpy_is_wiki', true );
	?>
	<?php $add_to_popup = tooltipy_get_option( 'add_to_popup', false, false ); ?>

	<!-- Title -->
	<?php
	if( is_array( $add_to_popup ) && in_array( 'title', $add_to_popup ) ){
		$before_title = '<h1 class="entry-title">';
		$after_title = '</h1>';

		if( $tooltipy_is_glossary_page ){
			$add_glossary_link_titles = tooltipy_get_option( 'glossary_link_titles', false );

			if( 'yes' == $add_glossary_link_titles ){
				$before_title = $before_title . '<a href="'. get_the_permalink() .'">';
				$after_title = '</a>' . $after_title;
			}
		}
		?>
		<header class="tooltipy-pop__title">
			<?php the_title( $before_title, $after_title ); ?>
		</header>
		<?php
	}
	?>

	<!-- Thumbnail -->
	<?php
		$glossary_show_thumbnails = tooltipy_get_option( 'glossary_show_thumbnails', false );

		if( 
			( $tooltipy_is_glossary_page && 'yes' == $glossary_show_thumbnails )
			||
			$tooltipy_is_glossary_page !== true
		){
			if( $is_wiki && isset($wiki_data->thumbnail) && isset($wiki_data->thumbnail->source) ){
				// Wiki thumbnail
				?>
				<div class="tooltipy-pop__thumbnail tooltipy-pop__thumbnail--wiki">
					<img src="<?php echo $wiki_data->thumbnail->source; ?>">
				</div>
				<?php
			}else if( has_post_thumbnail( get_the_ID() ) ):
				// Post thumbnail
				?>
				<div class="tooltipy-pop__thumbnail">
					<?php the_post_thumbnail( 'medium' ); ?>                    
				</div>
				<?php
			endif;
		}
	?>

	<!-- Content -->
	<div class="tooltipy-pop__content">
		<?php
			echo $is_wiki ? $wiki_data->extract_html : get_the_content();
		?>
	</div><!-- .tooltipy-pop__content -->
</article><!-- #post-## -->