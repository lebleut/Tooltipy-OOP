<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/glossary/glossary-header.php
 */

use Tooltipy\Tooltipy;
global $glossary_first_letter;
?>
<ul class="tltpy-glossary-header-list">
	<?php foreach( tooltipy_get_glossary_letters() as $letter ): ?>
		<?php
		$is_active = false;
		$letter_link = Tooltipy::get_glossary_page_link();

		if( !empty($letter[ "value" ]) ){
			$letter_link = Tooltipy::get_glossary_page_link() . "letter/" . $letter["value"];
		}

		$classes = ['tltpy-glossary-header-letter'];

		if( $glossary_first_letter == $letter["value"] ){
			$classes[] = 'tltpy-glossary-header-letter--active';
		}

		?>
		<li>
			<a
				href="<?php echo $letter_link; ?>"
				class="<?php echo implode( ' ', $classes ); ?>"
				data-letter="<?php echo $letter[ "value" ]; ?>"
			><?php echo $letter["label"]; ?></a>
		</li>
	<?php endforeach; ?>
</ul>