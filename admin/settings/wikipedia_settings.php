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
	
			'label' 		=> __tooltipy( 'Wikipedia language' ),
	
			'options' 		=> array(
				'en'			=> __tooltipy( 'English' ),
				'fr'			=> __tooltipy( 'French' ),
				'ar'			=> __tooltipy( 'Arabic' ),
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