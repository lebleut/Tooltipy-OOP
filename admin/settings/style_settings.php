<?php
use Tooltipy\Tooltipy;

add_filter( 'tltpy_setting_fields', 'tltpy_get_style_settings' );

function tltpy_get_style_settings( $fields ){
	$settings = array(
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_max_width',
			'type' 			=> 'number',	
			'helper' 		=> 'px',
			'label' 		=> __tooltipy( 'Tooltip max width' ),
			'default'       => 350,
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'description_font_size',
			'type' 			=> 'number',

			'label' 		=> __tooltipy( 'Description tooltip Font size' )
							.'<div style="color:red;">Not yet implemented</div>',
			'helper' 		=> __tooltipy( 'px' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'image_alt',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Activate tooltips for images ?' )
								.'<div style="color:red;">Not yet implemented</div>',

			'options' 		=> array(
				'yes' 		=> __tooltipy( 'alt property of the images will be displayed as a tooltip' ),
			),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'keyword_css_classes',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Custom CSS classes for inline keywords' )
								.'<div style="color:red;">Not yet implemented</div>',

			'placeholder' 	=> __tooltipy( 'Separated with spaces' ),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'tooltip_css_classes',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Custom CSS classes for tooltips' )
								.'<div style="color:red;">Not yet implemented</div>',

			'placeholder' 	=> __tooltipy( 'Separated with spaces' ),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'custom_style',
			'type' 			=> 'checkbox',

			'label' 		=> __tooltipy( 'Custom style' )
								.'<div style="color:red;">Not yet implemented</div>',

			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Apply custom style sheet' ),
			),
			'default' 		=> array( '' ),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'custom_style_sheet_url',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Custom style sheet URL' )
								.'<div style="color:red;">Not yet implemented</div>',

			'placeholder' 	=> __tooltipy( 'CSS URL here' ),
		),
		array(
			'section' 		=> 'icon_mode',
			
			'uid' 			=> 'icon_background_color',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Icon background' ),
		),
		array(
			'section' 		=> 'icon_mode',
			
			'uid' 			=> 'icon_text_color',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Icon text color' ),
		),

	);
		
	// Assign the STYLE tab slug
	foreach ( $settings as $key => $setting ) {
		$settings[$key]["tab"] = "style";
	}

	$fields = array_merge( $fields, $settings );

	return $fields;
}