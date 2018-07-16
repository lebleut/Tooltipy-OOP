<?php
add_filter( 'tltpy_setting_fields', function( $fields ){
    // For animation field
    $animations = array(
        "none",
        "bounce", "bounceIn", "bounceInLeft", "bounceInRight", "bounceInDown", "bounceInUp",
        "fadeIn", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig",
        "flash",
        "flip", "flipInX", "flipInY",
        "lightSpeedIn",
        "pulse",				
        "rollIn",
        "rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight",
        "slideInDown", "slideInLeft", "slideInRight", "slideInUp",
        "swing", "shake", "tada",
        "wobble",
        "zoomIn", "zoomInDown", "zoomInLeft", "zoomInRight", "zoomInUp"
    );

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
    
    $settings = array(
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
            
            'uid' 			=> 'hide_tooltip_title',
            'type' 			=> 'checkbox',
    
            'label' 		=> __( 'Hide tooltip title', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'yes' 		=> __( 'Hide', 'tooltipy-lang' ),
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
        ),
        array(
            'section' 		=> 'general',
            
            'uid' 			=> 'tooltip_animation_speed',
            'type' 			=> 'select',
    
            'label' 		=> __( 'Animation speed', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'fast'			=> __( 'Fast', 'tooltipy-lang' ),
                'normal'		=> __( 'Normal', 'tooltipy-lang' ),
                'slow'			=> __( 'Slow', 'tooltipy-lang' ),
            ),
        ),
        array(
            'section' 		=> 'advanced',
            
            'uid' 			=> 'get_from_post_types',
            'type' 			=> 'multiselect',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox
    
            'label' 		=> __( 'Get tooltips from', 'tooltipy-lang' ),
            'description' 	=> __( 'Select post types from which you want to get tooltips', 'tooltipy-lang' ),		// Text description below the field
    
            'options' 		=> $get_from_post_types_arr,
        ),
        array(
            'section' 		=> 'advanced',
            
            'uid' 			=> 'load_all_tooltips',
            'type' 			=> 'checkbox',
    
            'label' 		=> __( 'Load all tooltips', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'yes' 		=> __( 'Use only if needed to load all keywords per page', 'tooltipy-lang' ),
            ),
        ),
        array(
            'section' 		=> 'advanced',
            
            'uid' 			=> 'custom_events',
            'type' 			=> 'text',
    
            'label' 		=> __( 'Events to fetch', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Events names saparated with (,)', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'advanced',
            
            'uid' 			=> 'prevent_plugins_filters',
            'type' 			=> 'checkbox',
    
            'label' 		=> __( 'Prevent other plugins filters', 'tooltipy-lang' ),
    
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
    );

    // Assign the GENERAL tab slug
    foreach ( $settings as $key => $setting ) {
        $settings[$key]["tab"] = "general";
    }

    $fields = array_merge( $fields, $settings );

    return $fields;
});