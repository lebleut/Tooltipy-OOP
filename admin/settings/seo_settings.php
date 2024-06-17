<?php
use Tooltipy\Tooltipy;
use Tooltipy\Posts_Metaboxes;

add_filter( 'tltpy_setting_fields', 'tltpy_get_seo_serttings' );

function tltpy_get_seo_serttings( $fields ){
	$seo_serttings = array(
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'rewrite_tooltip',
			'type' 			=> 'text',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

			'label' 		=> __tooltipy( 'Tooltip slug' ),
			'placeholder' 	=> Tooltipy::get_plugin_name(),

			'default' 	=> Tooltipy::get_plugin_name(),
			'description'   => __( 'The slug that will show up in a single tooltip URL' )
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'rewrite_cat',
			'type' 			=> 'text',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

			'label' 		=> __tooltipy( 'Category slug' ),
			'placeholder' 	=> Tooltipy::get_plugin_name(),

			'default' 	=> 'tooltips',
			'description'   => __( 'The slug that will show up in the tooltip category URL' )
		),
	);

	// Assign the SEO tab slug
	foreach ( $seo_serttings as $key => $setting ) {
		$seo_serttings[$key]["tab"] = "seo";
	}

	$fields = array_merge( $fields, $seo_serttings );

	return $fields;
}

add_action( 'updated_option', 'tltpy_updated_seo_serttings', 10, 3 );

function tltpy_updated_seo_serttings( $option, $old_value, $value ){
    if( $option == 'tltpy_' . 'rewrite_tooltip' && trim($value) != trim($old_value) ){
        add_option( 'tltpy_' . 'flush_rewrite_rules',true);
    }
}