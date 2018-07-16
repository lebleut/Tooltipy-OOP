<?php
add_filter( 'tltpy_setting_fields', function( $fields ){
    $settings = array(
        array(
            'section' 		=> 'general',
            
            'uid' 			=> 'tooltip_width',
            'type' 			=> 'number',	
            'helper' 		=> 'px',
            'label' 		=> __( 'Tooltip width', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'general',
            
            'uid' 			=> 'description_font_size',
            'type' 			=> 'number',

            'label' 		=> __( 'Description tooltip Font size', 'tooltipy-lang' ),
            'helper' 		=> __( 'px', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'general',
            
            'uid' 			=> 'image_alt',
            'type' 			=> 'checkbox',

            'label' 		=> __('Activate tooltips for images ?','tooltipy-lang'),

            'options' 		=> array(
                'yes' 		=> __( 'alt property of the images will be displayed as a tooltip', 'tooltipy-lang' ),
            ),
        ),
        array(
            'section' 		=> 'advanced',
            
            'uid' 			=> 'keyword_css_classes',
            'type' 			=> 'text',

            'label' 		=> __( 'Custom CSS classes for inline keywords', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Separated with spaces', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'advanced',
            
            'uid' 			=> 'tooltip_css_classes',
            'type' 			=> 'text',

            'label' 		=> __( 'Custom CSS classes for tooltips', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Separated with spaces', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'advanced',
            
            'uid' 			=> 'custom_style',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Custom style', 'tooltipy-lang' ),

            'options' 		=> array(
                'yes' 		=> __( 'Apply custom style sheet', 'tooltipy-lang' ),
            ),
            'default' 		=> array( '' ),

        ),
        array(
            'section' 		=> 'advanced',
            
            'uid' 			=> 'custom_style_sheet_url',
            'type' 			=> 'text',

            'label' 		=> __( 'Custom style sheet URL', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'CSS URL here', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'icon_mode',
            
            'uid' 			=> 'icon_background_color',
            'type' 			=> 'text',

            'label' 		=> __( 'Icon background', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'icon_mode',
            
            'uid' 			=> 'icon_text_color',
            'type' 			=> 'text',

            'label' 		=> __( 'Icon text color', 'tooltipy-lang' ),
        ),

    );
        
    // Assign the STYLE tab slug
    foreach ( $settings as $key => $setting ) {
        $settings[$key]["tab"] = "style";
    }

    $fields = array_merge( $fields, $settings );

    return $fields;
});