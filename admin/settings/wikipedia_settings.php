<?php
use Tooltipy\Tooltipy;

add_filter( 'tltpy_setting_fields', 'tltpy_get_wikipedia_settings' );

function tltpy_get_wikipedia_settings( $fields ){

	$settings = array(
		// Language
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'wikipedia_lang',
			'type' 			=> 'select',
	
			'label' 		=> __( 'Wikipedia language', 'tooltipy' ),
	
			'options' 		=> array(
				'en'			=> __( 'English', 'tooltipy' ),
				'fr'			=> __( 'French', 'tooltipy' ),
				'ar'			=> __( 'Arabic', 'tooltipy' ),
			),

			'default' 	=> array( 'en' ),
		),
	);
	
	// Assign the Wikipedia tab slug
	foreach ( $settings as $key => $setting ) {
		$settings[$key]["tab"] = "wikipedia";
	}
	
	$fields = array_merge( $fields, $settings );

	return $fields;
}