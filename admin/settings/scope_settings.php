<?php
add_filter( 'tltpy_setting_fields', function( $fields ){
    $scope_settings = array(
        array(
            'tab' 			=> 'cover',
            'section' 		=> 'general',
            
            'uid' 			=> 'cover_classes',
            'type' 			=> 'text',

            'label' 		=> __( 'Cover CSS classes', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Class ...', 'tooltipy-lang' ),
            'helper' 		=> __( 'Choose CSS classes to cover with tooltips', 'tooltipy-lang' ),
            'description' 	=> __( 'NB : Please avoid overlapped classes !<br>If you leave Classes AND Tags blank the whole page will be affected', 'tooltipy-lang' ),

        ),
        array(
            'tab' 			=> 'cover',
            'section' 		=> 'general',
            
            'uid' 			=> 'cover_html_tags',
            'type' 			=> 'text',

            'label' 		=> __( 'Cover HTML TAGS', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'HTML tag ...', 'tooltipy-lang' ),
            'helper' 		=> __( 'Choose HTML TAGS (like h1, h2, strong, p, ... ) to cover with tooltips', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'cover',
            'section' 		=> 'exclude',
            
            'uid' 			=> 'exclude_classes',
            'type' 			=> 'text',

            'label' 		=> __( 'Exclude CSS classes', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Class ...', 'tooltipy-lang' ),
            'helper' 		=> __( 'Choose CSS classes to exclude', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'cover',
            'section' 		=> 'exclude',
            
            'uid' 			=> 'exclude_links',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Exclude links ?', 'tooltipy-lang' ),

            'options' 		=> array(
                'yes' 		=> __( 'Yes', 'tooltipy-lang' ),
            ),
        ),
        array(
            'tab' 			=> 'cover',
            'section' 		=> 'exclude',
            
            'uid' 			=> 'exclude_heading_tags',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Exclude Headings ?', 'tooltipy-lang' ),

            'options' 		=> array(
                'h1' 		=> 'H1',
                'h2' 		=> 'H2',
                'h3' 		=> 'H3',
                'h4' 		=> 'H4',
                'h5' 		=> 'H5',
                'h6' 		=> 'H6',
            ),
        ),
        array(
            'tab' 			=> 'cover',
            'section' 		=> 'exclude',
            
            'uid' 			=> 'exclude_common_tags',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Exclude Common Tags ?', 'tooltipy-lang' ),

            'options' 		=> array(
                'strong' 		=> '<&zwnj;strong &zwnj;/>',
                'b' 			=> '<&zwnj;b &zwnj;/>',
                'abbr' 			=> '<&zwnj;abbr &zwnj;/>',
                'button' 		=> '<&zwnj;button &zwnj;/>',
                'dfn' 			=> '<&zwnj;dfn &zwnj;/>',
                'em' 			=> '<&zwnj;em &zwnj;/>',
                'i' 			=> '<&zwnj;i &zwnj;/>',
                'label' 		=> '<&zwnj;label &zwnj;/>',
            ),
        ),
    );

    $fields = array_merge( $fields, $scope_settings );

    return $fields;
});