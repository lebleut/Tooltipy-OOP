<?php
use Tooltipy\Tooltipy;

add_filter( 'tltpy_setting_fields', 'tltpy_get_general_serttings' );

function tltpy_get_general_serttings( $fields ){
	// For animation field
	$animations = array(
		"none"              => __tooltipy( 'None' ),
		
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
			
			'label' 		=> __tooltipy( 'Tooltip mode' ),
			'options' 		=> array(
				'standard' 	=> __tooltipy( 'Standard mode' ) .' ' . $standard_style_link,
				'icon'		=> __tooltipy( 'Icon mode' ) .' ' . $icon_style_link,
				'title' 	=> __tooltipy( 'Title attrib mode' ) .' ' . $title_style_link,
				'link' 	    => __tooltipy( 'Link mode' ) .' ' . $link_style_link,
				'footnote' 	    => __tooltipy( 'Footnote mode' ) .' ' . $footnote_style_link,
			),
			'default' 		=> array('standard'),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_trigger',
			'type' 			=> 'radio',
			
			'label' 		=> __tooltipy( 'Trigger on' ),
			'options' 		=> array(
				'mouseenter' 	=> __tooltipy( 'On hover' ),
				'click'			=> __tooltipy( 'On click' ),
			),
			'default' 		=> array('mouseenter'),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_cursor',
			'type' 			=> 'radio',
			
			'label' 		=> __tooltipy( 'Mouse cursor' ),
			'options' 		=> array(
				'auto' 			=> __tooltipy( 'Auto' ),
				'pointer' 		=> __tooltipy( 'Pointer' ),
				'zoom-in'		=> __tooltipy( 'Zoom In' ),
				'help' 			=> __tooltipy( 'Help' ),
				'context-menu'	=> __tooltipy( 'Context Menu' ),
			),
			
			'default' 		=> array('pointer'),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'match_all_occurrences',
			'type' 			=> 'checkbox',
	
			'label' 		=> __tooltipy( 'Match all occurrences' ),
	
			'options' 		=> array(
				'yes' 		=> __tooltipy( 'All' ),
			),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_position',
			'type' 			=> 'select',
	
			'label' 		=> __tooltipy( 'Tooltip position' ),
	
			'options' 		=> array(
				'top' 		=> __tooltipy( 'Top' ),
				'bottom' 	=> __tooltipy( 'Bottom' ),
				'right' 	=> __tooltipy( 'Right' ),
				'left' 		=> __tooltipy( 'Left' ),
			),
			'default' 		=> array( 'bottom' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_animation',
			'type' 			=> 'select',
	
			'label' 		=> __tooltipy( 'Animation' ),
	
			'options' 		=> $animations,
			'default'       => array( 'fadeIn' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_animation_speed',
			'type' 			=> 'select',
	
			'label' 		=> __tooltipy( 'Animation speed' ),
	
			'options' 		=> array(
				'fast'			=> __tooltipy( 'Fast' ),
				'faster'		=> __tooltipy( 'Faster' ),
				'normal'		=> __tooltipy( 'Normal' ),
				'slow'			=> __tooltipy( 'Slow' ),
				'slower'		=> __tooltipy( 'Slower' ),
			),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'get_from_post_types',
			'type' 			=> 'multiselect',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox
	
			'label' 		=> __tooltipy( 'Get tooltips from' ),
			'description' 	=> __tooltipy( 'Select post types from which you want to get tooltips' )		// Text description below the field
								. '<div style="color:red;">TODO: consider to tell the user that he should recalculate matched tooltips if this option is changed</div>',
	
			'options' 		=> $get_from_post_types_arr,
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'load_all_tooltips',
			'type' 			=> 'checkbox',
	
			'label' 		=> __tooltipy( 'Load all tooltips' )
							.'<div style="color:red;">Not yet implemented</div>',
	
			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Use only if needed to load all keywords per page' ),
			),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'custom_events',
			'type' 			=> 'text',
	
			'label' 		=> __tooltipy( 'Events to fetch' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __( 'Events names saparated with (,)' ),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'prevent_plugins_filters',
			'type' 			=> 'checkbox',
	
			'label' 		=> __tooltipy( 'Prevent other plugins filters' )
								.'<div style="color:red;">Not yet implemented</div>',
	
			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Prevent any 3rd party plugin to filter or change the keywords content' ),
			),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'debug_mode',
			'type' 			=> 'checkbox',
	
			'label' 		=> __tooltipy( 'Activate the debug mode' ),
			'helper'        => __( 'Shows debug in the footer of each page if you are administrator and add the Tooltipy log in the ../wp-content/debug.log file' ),
			'description'   => __( 'Note : You should set the WP_DEBUG_LOG & WP_DEBUG constants to true in the wp-config.php file to see the error_log messages.' ),

			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Debug mode' ),
			),
		),

		// add_to_popup
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'add_to_popup',
			'type' 			=> 'checkbox',
	
			'label' 		=> __tooltipy( 'Add to tooltip popup' ),

			'options' 		=> array(
				'title' 		=> __tooltipy( 'Title' ),
				'synonyms' 		=> __tooltipy( 'Synonyms section' ),
				'glossary' 		=> __tooltipy( 'Add glossary link page in the tooltips footer' ),
			),

			'default'       => array( 'title' )
		),

		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_link_label',
			'type' 			=> 'text',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

			'label' 		=> __tooltipy( 'Glossary link label' ),
			'placeholder' 	=> __tooltipy( 'View glossary' ),

			'default' 	=> __tooltipy( 'View glossary' ),
		),
	);

	// Assign the GENERAL tab slug
	foreach ( $general_serttings as $key => $setting ) {
		$general_serttings[$key]["tab"] = "general";
	}

	$fields = array_merge( $fields, $general_serttings );

	return $fields;
}