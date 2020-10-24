<?php
use Tooltipy\Tooltipy;
use Tooltipy\Posts_Metaboxes;

add_filter( 'tltpy_setting_fields', 'tltpy_get_general_serttings' );

function tltpy_get_general_serttings( $fields ){
	// Needed to know if the plugin is active
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

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
			
			'uid' 			=> 'tooltip_theme',
			'type' 			=> 'select',
	
			'label' 		=> __tooltipy( 'Tooltip theme' ),

			'options' 		=> array(
				'light' 		=> __tooltipy( 'Light' ),
				'light-border' 	=> __tooltipy( 'Light border' ),
				'material' 		=> __tooltipy( 'Material' ),
				'translucent' 	=> __tooltipy( 'Translucent' ),
			),
			'default' 		=> array( 'light' ),
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
				'auto' 		=> __tooltipy( 'Auto' ),
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
			
			'uid' 			=> 'generate_relationships',
			'type' 			=> 'button',
			'action_callback'	=> 'tltpy_ajx_generate_relationships',
			'js_callback'		=> 'tltpy_relationships_results',
	
			'label' 		=> __tooltipy( 'Generate relationships' ),
			'description' 	=> __tooltipy( 'Allows to regenerate the relationships between keywords and all the posts' )
								.'<br>'.__tooltipy( 'Useful when a lot of keywords are added/modified/deleted' )

		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'migrate_old_options',
			'type' 			=> 'button',
			'action_callback'	=> 'tltpy_ajx_migrate_old_options',
			'js_callback'		=> 'tltpy_old_options_results',
			
			'disabled'		=> !is_plugin_active( 'bluet-keywords-tooltip-generator/index.php' ),
			'label' 		=> __tooltipy( 'Migrate old options' ),
			'description' 	=>  '<span style="color:red;">' . __tooltipy( 'The old version of Tooltipy (KTTG) v5.2 or lower should be active so you can use it' ) . '</span>'
								. '<br/>' . __tooltipy( 'This is the tool that allows old users of Tooltipy (KTTG) to migrate from the old version to this new one' )
								. '<br/> - ' . __tooltipy( 'Migrates the Keywords' )
								. '<br/> - ' . __tooltipy( 'Updates related post meta datas' )
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

function tltpy_ajx_generate_relationships(){
	$ret =[
		'result' => 'SUCCESS',
		'updated_posts' => 0,
		'message' => ''
	];

	$related_posttypes = Tooltipy::get_related_post_types();

	$posts = get_posts( [
		'post_type' => $related_posttypes,
		'posts_per_page' => -1
	] );

	foreach( $posts as $post ){
		$new_value = Posts_Metaboxes::filter_matched_tooltips( $post->post_content );

		if( update_post_meta( $post->ID, 'tltpy_matched_tooltips', $new_value) ){
			$ret['updated_posts'] += 1;
		}
	}

	if( $ret['updated_posts'] > 1 ){
		$ret['message'] = '<span style="color:green;">'.$ret['updated_posts'].' posts updated</span>';
	}else if( $ret['updated_posts'] == 1 ){
		$ret['message'] = '<span style="color:green;">Only one post updated</span>';
	}else{
		$ret['message'] = '<span style="color:#03a9f4;">All posts are already up to date</span>';
	}

	echo json_encode($ret);
	die;
}

function tltpy_ajx_migrate_old_options(){
	$ret =[
		'success_migration' => [],
		'failure_migration' => [],
		'updated_posts' => [],
		'message' => []
	];

	$old_keywords = get_posts( [
		'posts_per_page' => -1,
		'post_type' => 'my_keywords'
	] );

	// Post type migration
	foreach( $old_keywords as $post ){
		if(set_post_type( $post->ID, 'tooltipy' )){
			$ret['success_migration'][] = $post->ID;

			$metas = get_post_meta( $post->ID );

			// Migrate metas (case sensitive, prefix, synonyms, youtube)
			// case sensitive
			if( isset( $metas['bluet_case_sensitive_word'] ) && count($metas['bluet_case_sensitive_word']) && $metas['bluet_case_sensitive_word'][0] == 'on' ){
				if( update_post_meta( $post->ID, 'tltpy_case_sensitive', 'on' ) ){
					delete_post_meta( $post->ID, 'bluet_case_sensitive_word' );
				}
			}
			
			// prefix
			if( isset( $metas['bluet_prefix_keywords'] ) && count($metas['bluet_prefix_keywords']) && $metas['bluet_prefix_keywords'][0] == 'on' ){
				if( update_post_meta( $post->ID, 'tltpy_is_prefix', 'on' ) ){
					delete_post_meta( $post->ID, 'bluet_prefix_keywords' );
				}
			}
			
			// synonyms
			if( isset( $metas['bluet_synonyms_keywords'] ) && count($metas['bluet_synonyms_keywords']) && !empty( trim($metas['bluet_synonyms_keywords'][0]) ) ){
				if( update_post_meta( $post->ID, 'tltpy_synonyms', trim($metas['bluet_synonyms_keywords'][0]) ) ){
					delete_post_meta( $post->ID, 'bluet_synonyms_keywords' );
				}
			}
			
			// youtube id
			if( isset( $metas['bluet_youtube_video_id'] ) && count($metas['bluet_youtube_video_id']) && !empty( trim($metas['bluet_youtube_video_id'][0]) ) ){
				if( update_post_meta( $post->ID, 'tltpy_youtube_id', trim($metas['bluet_youtube_video_id'][0]) ) ){
					delete_post_meta( $post->ID, 'bluet_youtube_video_id' );
				}
			}

		}else{
			$ret['failure_migration'][] = $post->ID;
		}
	}
	
	$related_post_types = Tooltipy::get_related_post_types();

	foreach( $related_post_types as $key => $pt ){
		if( 'my_keywords' == $pt ){
			unset( $related_post_types[$key] );
		}
	}

	// Rlated posts metas updates
	$related_posts = get_posts( [
		'posts_per_page' => -1,
		'post_type' => $related_post_types,
		'meta_query' =>[
			'compare' => 'OR',
			[
				'key' => 'bluet_exclude_post_from_matching',
				'compare' => 'EXISTS'
			],
			[
				'key' => 'bluet_exclude_keywords_from_matching',
				'compare' => 'EXISTS'
			]

		]
	] );

	foreach( $related_posts as $post ){
		$old_ex_post = get_post_meta( $post->ID, 'bluet_exclude_post_from_matching', true );
		$new_ex_post = get_post_meta( $post->ID, 'tltpy_exclude_me', true );

		$old_ex_keywords = get_post_meta( $post->ID, 'bluet_exclude_keywords_from_matching', true );
		$new_ex_keywords = get_post_meta( $post->ID, 'tltpy_exclude_tooltips', true );

		if( 
			( !empty($old_ex_keywords) && !$new_ex_keywords )
			|| ( !empty($old_ex_post) && !$new_ex_post )
		){
			update_post_meta( $post->ID, 'tltpy_exclude_me', $old_ex_post );
			update_post_meta( $post->ID, 'tltpy_exclude_tooltips', $old_ex_keywords );

			$ret['updated_posts'][] = $post->ID;
		}
	}

	// Message
	if( count($ret['success_migration']) ){
		$ret['message'][] = count($ret['success_migration']) . ' Keywords migrated';
	}
	
	if( count($ret['failure_migration']) ){
		$ret['message'][] = count($ret['failure_migration']) . ' Keywords migration failure';
	}

	if( count($ret['updated_posts']) ){
		$ret['message'][] = count($ret['updated_posts']) . ' updated posts';
	}
	
	if( !count($ret['message']) ){
		$ret['message'][] = 'Nothing changed!';
	}

	$ret['message'] = '<div>' . implode( '<br/>', $ret['message'] ) . '</div>';

	echo json_encode($ret);
	die;
}