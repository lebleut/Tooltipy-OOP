<?php
use Tooltipy\Tooltipy;

add_filter( 'tltpy_setting_fields', 'tltpy_get_scope_settings' );

function tltpy_get_scope_settings( $fields ){
	$settings = array(
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'exclude_classes',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Exclude CSS classes' ),
			'placeholder' 	=> __( 'Class ...' ),
			'helper' 		=> __tooltipy( 'Choose CSS classes to exclude' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'exclude_links',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude links ?' ),

			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Yes' ),
			),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'exclude_heading_tags',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude Headings ?' ),

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
			'section' 		=> 'general',
			
			'uid' 			=> 'exclude_common_tags',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude Common Tags ?' ),

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
	
	// Assign the exclude tab slug
	foreach ( $settings as $key => $setting ) {
		$settings[$key]["tab"] = "exclude";
	}

	$fields = array_merge( $fields, $settings );

	return $fields;
}