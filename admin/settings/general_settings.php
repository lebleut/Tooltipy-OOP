<?php
use Tooltipy\Tooltipy;

add_filter( 'tltpy_setting_fields', 'tltpy_get_general_serttings' );

function tltpy_get_general_serttings( $fields ){
	// For animation field
	$animations = array(
		"none"              => __( 'None', 'tooltipy-lang' ),
		
		"bounce"            => "bounce",
		"bounceIn"          => "bounceIn",
		"bounceInLeft"      => "bounceInLeft",
		"bounceInRight"     => "bounceInRight",
		"bounceInDown"      => "bounceInDown",
		"bounceInUp"        => "bounceInUp",
		
		"fadeIn"            => "fadeIn",
		"fadeInLeft"        => "fadeInLeft",
		"fadeInLeftBig"     => "fadeInLeftBig",
		"fadeInRight"       => "fadeInRight",
		"fadeInRightBig"    => "fadeInRightBig",
		"fadeInUp"          => "fadeInUp",
		"fadeInUpBig"       => "fadeInUpBig",
		
		"flash"             => "flash",
		
		"flip"              => "flip",
		"flipInX"           => "flipInX",
		"flipInY"           => "flipInY",
		
		"lightSpeedIn"      => "lightSpeedIn",
		
		"pulse"             => "pulse",

		"rollIn"            => "rollIn",
		
		"rotateIn"          => "rotateIn",
		"rotateInDownLeft"  => "rotateInDownLeft",
		"rotateInDownRight" => "rotateInDownRight",
		"rotateInUpLeft"    => "rotateInUpLeft",
		"rotateInUpRight"   => "rotateInUpRight",
		
		"slideInDown"       => "slideInDown",
		"slideInLeft"       => "slideInLeft",
		"slideInRight"      => "slideInRight",
		"slideInUp"         => "slideInUp",
		
		"swing"             => "swing",
		"shake"             => "shake",
		"tada"              => "tada",
		
		"wobble"            => "wobble",
		
		"zoomIn"            => "zoomIn",
		"zoomInDown"        => "zoomInDown",
		"zoomInLeft"        => "zoomInLeft",
		"zoomInRight"       => "zoomInRight",
		"zoomInUp"          => "zoomInUp"
	);

	// Uppercase first character
	$animations = array_map('ucfirst', $animations);
	
	// for get_from_post_types field
	$get_from_post_types_arr = array();
	foreach(get_post_types() as $psttp){
		$get_from_post_types_arr[$psttp] = $psttp;
	}
	$style_tab = add_query_arg( array(
		'post_type' => 'tooltipy',
		'page'      => 'tooltipy_settings',
		'tab'       => 'style'
	), admin_url() . '/edit.php' );
	
	$standard_style_link    = '<a href="' . add_query_arg( 'section', 'standard_mode' , $style_tab ) . '">' . __( 'Style' ) . '</a>';
	$icon_style_link        = '<a href="' . add_query_arg( 'section', 'icon_mode' , $style_tab ) . '">' . __( 'Style' ) . '</a>';
	$title_style_link       = '<a href="' . add_query_arg( 'section', 'title_mode' , $style_tab ) . '">' . __( 'Style' ) . '</a>';
	$link_style_link        = '<a href="' . add_query_arg( 'section', 'link_mode' , $style_tab ) . '">' . __( 'Style' ) . '</a>';
	$footnote_style_link    = '<a href="' . add_query_arg( 'section', 'footnote_mode' , $style_tab ) . '">' . __( 'Style' ) . '</a>';

	$general_serttings = array(
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_mode',
			'type' 			=> 'radio',
			
			'label' 		=> __( 'Tooltip mode', 'tooltipy-lang' ),
			'options' 		=> array(
				'standard' 	=> __( 'Standard mode', 'tooltipy-lang').' ' . $standard_style_link,
				'icon'		=> __( 'Icon mode', 'tooltipy-lang').' ' . $icon_style_link,
				'title' 	=> __( 'Title attrib mode', 'tooltipy-lang').' ' . $title_style_link,
				'link' 	    => __( 'Link mode', 'tooltipy-lang').' ' . $link_style_link,
				'footnote' 	    => __( 'Footnote mode', 'tooltipy-lang').' ' . $footnote_style_link,
			),
			'default' 		=> array('standard'),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'match_all_occurrences',
			'type' 			=> 'checkbox',
	
			'label' 		=> __( 'Match all occurrences', 'tooltipy-lang' ),
	
			'options' 		=> array(
				'yes' 		=> __( 'All', 'tooltipy-lang' ),
			),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_position',
			'type' 			=> 'select',
	
			'label' 		=> __( 'Tooltip position', 'tooltipy-lang' ),
	
			'options' 		=> array(
				'top' 		=> __( 'Top', 'tooltipy-lang' ),
				'bottom' 	=> __( 'Bottom', 'tooltipy-lang' ),
				'right' 	=> __( 'Right', 'tooltipy-lang' ),
				'left' 		=> __( 'Left', 'tooltipy-lang' ),
			),
			'default' 		=> array( 'bottom' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_animation',
			'type' 			=> 'select',
	
			'label' 		=> __( 'Animation', 'tooltipy-lang' ),
	
			'options' 		=> $animations,
			'default'       => array( 'fadeIn' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_animation_speed',
			'type' 			=> 'select',
	
			'label' 		=> __( 'Animation speed', 'tooltipy-lang' ),
	
			'options' 		=> array(
				'fast'			=> __( 'Fast', 'tooltipy-lang' ),
				'faster'		=> __( 'Faster', 'tooltipy-lang' ),
				'normal'		=> __( 'Normal', 'tooltipy-lang' ),
				'slow'			=> __( 'Slow', 'tooltipy-lang' ),
				'slower'		=> __( 'Slower', 'tooltipy-lang' ),
			),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'get_from_post_types',
			'type' 			=> 'multiselect',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox
	
			'label' 		=> __( 'Get tooltips from', 'tooltipy-lang' ),
			'description' 	=> __( 'Select post types from which you want to get tooltips', 'tooltipy-lang' )		// Text description below the field
								. '<div style="color:red;">TODO: consider to tell the user that he should recalculate matched tooltips if this option is changed</div>',
	
			'options' 		=> $get_from_post_types_arr,
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'load_all_tooltips',
			'type' 			=> 'checkbox',
	
			'label' 		=> __( 'Load all tooltips', 'tooltipy-lang' )
							.'<div style="color:red;">Not yet implemented</div>',
	
			'options' 		=> array(
				'yes' 		=> __( 'Use only if needed to load all keywords per page', 'tooltipy-lang' ),
			),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'custom_events',
			'type' 			=> 'text',
	
			'label' 		=> __( 'Events to fetch', 'tooltipy-lang' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __( 'Events names saparated with (,)', 'tooltipy-lang' ),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'prevent_plugins_filters',
			'type' 			=> 'checkbox',
	
			'label' 		=> __( 'Prevent other plugins filters', 'tooltipy-lang' )
								.'<div style="color:red;">Not yet implemented</div>',
	
			'options' 		=> array(
				'yes' 		=> __( 'Prevent any 3rd party plugin to filter or change the keywords content', 'tooltipy-lang' ),
			),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'debug_mode',
			'type' 			=> 'checkbox',
	
			'label' 		=> __( 'Activate the debug mode', 'tooltipy-lang' ),
			'helper'        => __( 'Shows debug in the footer of each page if you are administrator and add the Tooltipy log in the ../wp-content/debug.log file', 'tooltipy-lang' ),
			'description'   => __( 'Note : You should set the WP_DEBUG_LOG & WP_DEBUG constants to true in the wp-config.php file to see the error_log messages.', 'tooltipy-lang' ),

			'options' 		=> array(
				'yes' 		=> __( 'Debug mode', 'tooltipy-lang' ),
			),
		),

		// add_to_popup
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'add_to_popup',
			'type' 			=> 'checkbox',
	
			'label' 		=> __( 'Add to tooltip popup', 'tooltipy-lang' ),

			'options' 		=> array(
				'title' 		=> __( 'Title', 'tooltipy-lang' ),
				'synonyms' 		=> __( 'Synonyms section', 'tooltipy-lang' ),
				'glossary' 		=> __( 'Add glossary link page in the tooltips footer', 'tooltipy-lang' ),
			),

			'default'       => array( 'title' )
		),

		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_link_label',
			'type' 			=> 'text',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

			'label' 		=> __( 'Glossary link label', 'tooltipy-lang' ),
			'placeholder' 	=> __( 'View glossary', 'tooltipy-lang' ),

			'default' 	=> __( 'View glossary', 'tooltipy-lang' ),
		),
	);

	// Assign the GENERAL tab slug
	foreach ( $general_serttings as $key => $setting ) {
		$general_serttings[$key]["tab"] = "general";
	}

	$fields = array_merge( $fields, $general_serttings );

	return $fields;
}