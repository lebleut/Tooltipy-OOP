<?php
use Tooltipy\Tooltipy;

$glossary_letters = tooltipy_get_glossary_letters();
$glossary_link = Tooltipy::get_glossary_page_link();

?>
<ul class="tltpy-glossary-header-list">
    <?php foreach( $glossary_letters as $letter ): ?>
        <?php
        $letter_link = $glossary_link;

        if( !empty($letter[ "value" ]) ){
            $letter_link = $glossary_link . "letter/" . $letter["value"];
        }
        
        ?>
        <li><a href="<?php echo $letter_link; ?>" ><?php echo $letter["label"]; ?></a></li>
    <?php endforeach; ?>
</ul>