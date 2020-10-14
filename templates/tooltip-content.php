<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/tooltip-content.php
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<article>
	<?php
		// init
		global $tooltipy_is_glossary_page;
		$is_wiki = get_post_meta( get_the_ID(), 'tltpy_is_wiki', true );
		$wiki_data = false;
		if( $is_wiki ){
			$wiki_data = tooltipy_get_post_wiki_data( get_the_id() );
		}
	?>
	<?php $add_to_popup = tooltipy_get_option( 'add_to_popup', false, false ); ?>

	<!-- Title -->
	<?php
	if( is_array( $add_to_popup ) && in_array( 'title', $add_to_popup ) ){
		$before_title = '<h3>';
		$after_title = '</h3>';

		if( $tooltipy_is_glossary_page ){
			$add_glossary_link_titles = tooltipy_get_option( 'glossary_link_titles', false );

			if( 'yes' == $add_glossary_link_titles ){
				$before_title = $before_title . '<a href="'. get_the_permalink() .'">';
				$after_title = '</a>' . $after_title;
			}
		}
		?>
		<div class="tooltipy-pop__title">
			<?php the_title( $before_title, $after_title ); ?>
		</div>
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
			if( $is_wiki && $wiki_data && isset($wiki_data->thumbnail) && isset($wiki_data->thumbnail->source) ){
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
			echo $is_wiki && $wiki_data && isset($wiki_data->extract_html) ? $wiki_data->extract_html : get_the_content();
		?>
	</div><!-- .tooltipy-pop__content -->
</article><!-- #post-## -->