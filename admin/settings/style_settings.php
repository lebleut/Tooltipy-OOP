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

			'label' 		=> __tooltipy( 'Description tooltip Font size' ),

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

			'label' 		=> __tooltipy( 'Custom CSS classes for inline keywords' ),

			'placeholder' 	=> __tooltipy( 'Separated with spaces' ),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'tooltip_css_classes',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Custom CSS classes for tooltips' ),

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
			
			'uid' 			=> 'icon_image',
			'type' 			=> 'radio',
			'options'		=>[
				'dashicons-editor-help'		=> '<span class="dashicons dashicons-editor-help"></span>',
				'dashicons-search'			=> '<span class="dashicons dashicons-search"></span>',
				'dashicons-admin-customizer'=> '<span class="dashicons dashicons-admin-customizer"></span>',
				'dashicons-admin-comments'	=> '<span class="dashicons dashicons-admin-comments"></span>',
				'dashicons-admin-links'		=> '<span class="dashicons dashicons-admin-links"></span>',
				'dashicons-format-status'	=> '<span class="dashicons dashicons-format-status"></span>',
				'dashicons-format-quote'	=> '<span class="dashicons dashicons-format-quote"></span>',
				'dashicons-visibility'		=> '<span class="dashicons dashicons-visibility"></span>',
				'dashicons-info'			=> '<span class="dashicons dashicons-info"></span>',
				'dashicons-warning'			=> '<span class="dashicons dashicons-warning"></span>',
				'dashicons-search'			=> '<span class="dashicons dashicons-search"></span>',
				'dashicons-testimonial'		=> '<span class="dashicons dashicons-testimonial"></span>',
				'dashicons-lightbulb'		=> '<span class="dashicons dashicons-lightbulb"></span>',
				'dashicons-paperclip'		=> '<span class="dashicons dashicons-paperclip"></span>',
			],
			'default'		=> ['dashicons-editor-help'],
			'label' 		=> __tooltipy( 'Icon' ),
		),
		array(
			'section' 		=> 'icon_mode',
			
			'uid' 			=> 'icon_text_color',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Icon text color' ),
		),
		array(
			'section' 		=> 'icon_mode',
			
			'uid' 			=> 'icon_background_color',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Icon background' ),
		),

	);
		
	// Assign the STYLE tab slug
	foreach ( $settings as $key => $setting ) {
		$settings[$key]["tab"] = "style";
	}

	$fields = array_merge( $fields, $settings );

	return $fields;
}