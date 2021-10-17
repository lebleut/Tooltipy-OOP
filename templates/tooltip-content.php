<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/tooltip-content.php
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $tooltipy_is_glossary_page;
$tt_synonyms			= get_post_meta( get_the_ID(), 'tltpy_synonyms', true);
$tt_is_prefix			= get_post_meta( get_the_ID(), 'tltpy_is_prefix', true);
$tt_is_wiki				= get_post_meta( get_the_ID(), 'tltpy_is_wiki', true);
$tt_is_case_sensitive	= get_post_meta( get_the_ID(), 'tltpy_case_sensitive', true);
$tt_wiki_term 			= get_post_meta( get_the_ID(), 'tltpy_wiki_term', true );
$tt_youtube_id			= get_post_meta( get_the_ID(), 'tltpy_youtube_id', true);

$post_cls = ['tooltipy-post'];

if( $tt_is_prefix ){
	$post_cls[] = 'tooltipy-post--prefix';
}
if( $tt_is_wiki ){
	$post_cls[] = 'tooltipy-post--wiki';
}
if( $tt_is_case_sensitive ){
	$post_cls[] = 'tooltipy-post--case-sensitive';
}

?>
<article
	class="<?php echo implode(' ', $post_cls);?>"
	data-tltpy="<?php the_ID(); ?>"
	data-synonyms="<?php echo $tt_synonyms?>"
	data-is_prefix="<?php echo $tt_is_prefix?>"
	data-is_wiki="<?php echo $tt_is_wiki?>"
	data-is_case_sensitive="<?php echo $tt_is_case_sensitive?>"
	data-wiki_term="<?php echo $tt_wiki_term?>"
	data-youtube_id="<?php echo $tt_youtube_id?>"
>
	<?php
		// init
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
			$thumb = '';
			if( $tt_is_wiki ){
				$thumb = __tooltipy('Loading image...');
			}else if( has_post_thumbnail( get_the_ID() ) ):
				$thumb = get_the_post_thumbnail( get_the_ID(), 'medium' );
			endif;
			?>
			<div class="tooltipy-pop__thumbnail">
				<?php echo $thumb; ?>                    
			</div>
			<?php
		}
	?>

	<!-- Content -->
	<?php
	if( $tt_is_wiki ){
		$content = __tooltipy('Loading...');
	}else{
		$content = get_the_content();
	}
	?>
	<div class="tooltipy-pop__content">
		<?php echo $content; ?>
	</div>
	<?php
	?>
</article><!-- #post-## -->