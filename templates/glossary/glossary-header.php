<?php
/**
 * Tooltipy template file
 * This template can be overridden by copying it to YOUR_THEME/tooltipy/glossary/glossary-header.php
 */

use Tooltipy\Tooltipy;

?>
<ul class="tltpy-glossary-header-list">
	<?php foreach( tooltipy_get_glossary_letters() as $letter ): ?>
		<?php
		$letter_link = Tooltipy::get_glossary_page_link();

		if( !empty($letter[ "value" ]) ){
			$letter_link = Tooltipy::get_glossary_page_link() . "letter/" . $letter["value"];
		}
		
		?>
		<li><a href="<?php echo $letter_link; ?>" ><?php echo $letter["label"]; ?></a></li>
	<?php endforeach; ?>
</ul>