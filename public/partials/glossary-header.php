<?php
$glossary_letters = tooltipy_get_glossary_letters();
$glossary_link = Tooltipy::get_glossary_page_link();

?>
<ul>
    <?php foreach( $glossary_letters as $letter ): ?>
        <?php
        $letter_link = $glossary_link;

        if( !empty($letter[ "value" ]) ){
            $letter_link = add_query_arg( array(
                'letter' => $letter["value"],
            ), $glossary_link );
        }
        
        ?>
        <li><a href="<?php echo $letter_link; ?>" ><?php echo $letter["label"]; ?></a></li>
    <?php endforeach; ?>
</ul>