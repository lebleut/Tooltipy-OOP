<?php
use Tooltipy\Tooltipy;
use Tooltipy\Posts_Metaboxes;

add_filter( 'tltpy_setting_fields', 'tltpy_get_general_serttings' );

function tltpy_get_general_serttings( $fields ){
	// Needed to know if the plugin is active
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// For animation field
	$animations = array(
		"none"              => __( 'None', 'tooltipy' ),
		
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
	
	$standard_style_link    = '<a href="' . add_query_arg( 'section', 'standard_mode' , $style_tab ) . '">' . __( 'Style', 'tooltipy' ) . '</a>';
	$icon_style_link        = '<a href="' . add_query_arg( 'section', 'icon_mode' , $style_tab ) . '">' . __( 'Style', 'tooltipy' ) . '</a>';
	$title_style_link       = '<a href="' . add_query_arg( 'section', 'title_mode' , $style_tab ) . '">' . __( 'Style', 'tooltipy' ) . '</a>';
	$link_style_link        = '<a href="' . add_query_arg( 'section', 'link_mode' , $style_tab ) . '">' . __( 'Style', 'tooltipy' ) . '</a>';
	$footnote_style_link    = '<a href="' . add_query_arg( 'section', 'footnote_mode' , $style_tab ) . '">' . __( 'Style', 'tooltipy' ) . '</a>';

	$general_serttings = array(
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_mode',
			'type' 			=> 'radio',
			
			'label' 		=> __( 'Tooltip mode', 'tooltipy' ),
			'options' 		=> array(
				'standard' 	=> __( 'Standard mode', 'tooltipy' ) .' ' . $standard_style_link,
				'icon'		=> __( 'Icon mode', 'tooltipy' ) .' ' . $icon_style_link,
				'title' 	=> __( 'Title attrib mode', 'tooltipy' ) .' ' . $title_style_link,
				'link' 	    => __( 'Link mode', 'tooltipy' ) .' ' . $link_style_link,
				'footnote' 	    => __( 'Footnote mode', 'tooltipy' ) .' ' . $footnote_style_link,
			),
			'default' 		=> array('standard'),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_theme',
			'type' 			=> 'select',
	
			'label' 		=> __( 'Tooltip theme', 'tooltipy' ),

			'options' 		=> array(
				'light' 		=> __( 'Light', 'tooltipy' ),
				'light-border' 	=> __( 'Light border', 'tooltipy' ),
				'material' 		=> __( 'Material', 'tooltipy' ),
				'translucent' 	=> __( 'Translucent', 'tooltipy' ),
			),
			'default' 		=> array( 'light', 'tooltipy' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_trigger',
			'type' 			=> 'radio',
			
			'label' 		=> __( 'Trigger on', 'tooltipy' ),
			'options' 		=> array(
				'mouseenter' 	=> __( 'On hover', 'tooltipy' ),
				'click'			=> __( 'On click', 'tooltipy' ),
			),
			'default' 		=> array('mouseenter'),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_cursor',
			'type' 			=> 'radio',
			
			'label' 		=> __( 'Mouse cursor', 'tooltipy' ),
			'options' 		=> array(
				'auto' 			=> __( 'Auto', 'tooltipy' ),
				'pointer' 		=> __( 'Pointer', 'tooltipy' ),
				'zoom-in'		=> __( 'Zoom In', 'tooltipy' ),
				'help' 			=> __( 'Help', 'tooltipy' ),
				'context-menu'	=> __( 'Context Menu', 'tooltipy' ),
			),
			
			'default' 		=> array('pointer'),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'match_all_occurrences',
			'type' 			=> 'checkbox',
	
			'label' 		=> __( 'Match all occurrences', 'tooltipy' ),
	
			'options' 		=> array(
				'yes' 		=> __( 'All', 'tooltipy' ),
			),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_position',
			'type' 			=> 'select',
	
			'label' 		=> __( 'Tooltip position', 'tooltipy' ),
	
			'options' 		=> array(
				'auto' 		=> __( 'Auto', 'tooltipy' ),
				'top' 		=> __( 'Top', 'tooltipy' ),
				'bottom' 	=> __( 'Bottom', 'tooltipy' ),
				'right' 	=> __( 'Right', 'tooltipy' ),
				'left' 		=> __( 'Left', 'tooltipy' ),
			),
			'default' 		=> array( 'bottom' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_animation',
			'type' 			=> 'select',
	
			'label' 		=> __( 'Animation', 'tooltipy' ),
	
			'options' 		=> $animations,
			'default'       => array( 'fadeIn' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'tooltip_animation_speed',
			'type' 			=> 'select',
	
			'label' 		=> __( 'Animation speed', 'tooltipy' ),
	
			'options' 		=> array(
				'fast'			=> __( 'Fast', 'tooltipy' ),
				'faster'		=> __( 'Faster', 'tooltipy' ),
				'normal'		=> __( 'Normal', 'tooltipy' ),
				'slow'			=> __( 'Slow', 'tooltipy' ),
				'slower'		=> __( 'Slower', 'tooltipy' ),
			),
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'generate_relationships',
			'type' 			=> 'button',
			'action_callback'	=> 'tltpy_ajx_generate_relationships',
			'js_callback'		=> 'tltpy_relationships_results',
	
			'label' 		=> __( 'Generate relationships', 'tooltipy' ),
			'description' 	=> __( 'Allows to regenerate the relationships between keywords and all the posts', 'tooltipy' )
								.'<br>'.__( 'Useful when a lot of keywords are added/modified/deleted', 'tooltipy' )

		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'migrate_old_options',
			'type' 			=> 'button',
			'action_callback'	=> 'tltpy_ajx_migrate_old_options',
			'js_callback'		=> 'tltpy_old_options_results',
			'ajx_args'			=> [
				'last_done_id' => '0'
			],
			
			'label' 		=> __( 'Migrate old options', 'tooltipy' ),
			'description' 	=> __( 'This tool allows old users of Tooltipy (KTTG) to migrate from the old version to this new one', 'tooltipy' )
								. '<br/> - ' . __( 'Migrates the Keywords', 'tooltipy' )
								. '<br/> - ' . __( 'Updates related post meta data', 'tooltipy' )
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'get_from_post_types',
			'type' 			=> 'multiselect',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox
	
			'label' 		=> __( 'Get tooltips from', 'tooltipy' ),
			'description' 	=> __( 'Select post types from which you want to get tooltips', 'tooltipy' )		// Text description below the field
								. '<div style="color:red;">TODO: consider to tell the user that he should recalculate matched tooltips if this option is changed</div>',
	
			'options' 		=> $get_from_post_types_arr,
		),
		array(
			'section' 		=> 'advanced',
			
			'uid' 			=> 'debug_mode',
			'type' 			=> 'checkbox',
	
			'label' 		=> __( 'Activate the debug mode', 'tooltipy' ),
			'helper'        => __( 'Shows debug in the footer of each page if you are administrator and add the Tooltipy log in the ../wp-content/debug.log file', 'tooltipy' ),
			'description'   => __( 'Note : You should set the WP_DEBUG_LOG & WP_DEBUG constants to true in the wp-config.php file to see the error_log messages.', 'tooltipy' ),

			'options' 		=> array(
				'yes' 		=> __( 'Debug mode', 'tooltipy' ),
			),
		),

		// add_to_popup
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'add_to_popup',
			'type' 			=> 'checkbox',
	
			'label' 		=> __( 'Add to tooltip popup', 'tooltipy' ),

			'options' 		=> array(
				'title' 		=> __( 'Title', 'tooltipy' ),
				'synonyms' 		=> __( 'Synonyms section', 'tooltipy' ),
				'glossary' 		=> __( 'Add glossary link page in the tooltips footer', 'tooltipy' ),
			),

			'default'       => array( 'title' )
		),

		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_link_label',
			'type' 			=> 'text',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

			'label' 		=> __( 'Glossary link label', 'tooltipy' ),
			'placeholder' 	=> __( 'View glossary', 'tooltipy' ),

			'default' 	=> __( 'View glossary', 'tooltipy' ),
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
		$new_matched_tooltips = Posts_Metaboxes::filter_matched_tooltips( $post->post_content );

		if( update_post_meta( $post->ID, 'tltpy_matched_tooltips', $new_matched_tooltips) ){
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
	global $wpdb;

	$ret =[
		'success_migration' => [],
		'failure_migration' => [],
		'exist_migration' => [],
		'updated_posts' => [],
		'message' => [],
		'last_queried' => 0,
		'queried' => 0,
		'to_be_done' => 0,
	];

	$posts_per_page = 5;

	$done_id = 0;
	if( isset($_POST['last_done_id']) && !empty($_POST['last_done_id']) ){
		$done_id = intval($_POST['last_done_id']);
	}

	$post_ids = [];
	$count_sql = $wpdb->prepare(
		"
			SELECT ID
			FROM {$wpdb->posts}
			WHERE post_type = %s AND post_status = 'publish'
		",
		'my_keywords'
	);
	$count_results = $wpdb->get_results( $count_sql );
	$ret['to_be_done'] = count($count_results);

	// Get all the IDs you want to choose from
	$sql = $wpdb->prepare(
			"
				SELECT ID
				FROM {$wpdb->posts}
				WHERE post_type = %s
					AND ID > %d
					AND post_status = 'publish'
				LIMIT %d
			",
			'my_keywords',
			$done_id,
			$posts_per_page
		);

	$results = $wpdb->get_results( $sql );

	$ret['queried'] = count($results);

	// Convert the IDs from row objects to an array of IDs
	foreach ( $results as $row ) {
		array_push( $post_ids, $row->ID );
	}

	// Post type migration
	foreach( $post_ids as $post_id ){
		$ret['last_queried'] = $post_id;
		$post = get_post( $post_id );

		// Don't duplicate if the post slug already exists then
		$exist_post = get_posts( [
			'title'		=> $post->post_title,
			'post_type' => Tooltipy::get_plugin_name(),
			'post_status' => 'publish',
			'posts_per_page' => 1
		]);
		if( !empty($exist_post) ){
			$ret['exist_migration'][] = $post->ID;
			continue;
		}

		$post_toduplicate = (array)get_post( $post->ID );
		unset($post_toduplicate['ID']); // Remove id, wp will create new post if not set.

		$new_post_id = wp_insert_post($post_toduplicate);

		if( $new_post_id && set_post_type( $new_post_id, 'tooltipy' )){
			$ret['success_migration'][] = $post->ID;

			// categories
			$terms = get_the_terms( $post->ID, Tooltipy::get_taxonomy() );
			if( !empty($terms) ){
				$term_ids = array_map(function($term){
					return $term->term_id;
				}, $terms );

				wp_set_post_terms( $new_post_id, $term_ids, Tooltipy::get_taxonomy() );
			}

			// Thumbnail
			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			if( $thumbnail_id ){
				set_post_thumbnail( $new_post_id, $thumbnail_id );
			}

			$metas = get_post_meta( $post->ID );

			// Migrate metas (case sensitive, prefix, synonyms, youtube)
			// case sensitive
			if( isset( $metas['bluet_case_sensitive_word'] ) && count($metas['bluet_case_sensitive_word']) && $metas['bluet_case_sensitive_word'][0] == 'on' ){
				update_post_meta( $new_post_id, 'tltpy_case_sensitive', 'on' );
			}
			
			// prefix
			if( isset( $metas['bluet_prefix_keywords'] ) && count($metas['bluet_prefix_keywords']) && $metas['bluet_prefix_keywords'][0] == 'on' ){
				update_post_meta( $new_post_id, 'tltpy_is_prefix', 'on' );
			}
			
			// synonyms
			if( isset( $metas['bluet_synonyms_keywords'] ) && count($metas['bluet_synonyms_keywords']) && !empty( trim($metas['bluet_synonyms_keywords'][0]) ) ){
				update_post_meta( $new_post_id, 'tltpy_synonyms', trim($metas['bluet_synonyms_keywords'][0]) );
			}
			
			// youtube id
			if( isset( $metas['bluet_youtube_video_id'] ) && count($metas['bluet_youtube_video_id']) && !empty( trim($metas['bluet_youtube_video_id'][0]) ) ){
				update_post_meta( $new_post_id, 'tltpy_youtube_id', trim($metas['bluet_youtube_video_id'][0]) );
			}

		}else{
			$ret['failure_migration'][] = $new_post_id;
		}
	}
	
	$related_post_types = Tooltipy::get_related_post_types();

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
	
	if( count($ret['exist_migration']) ){
		$ret['message'][] = count($ret['exist_migration']) . ' Keywords already exist';
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

	$ret['message'] = implode( ', ', $ret['message'] );

	echo json_encode($ret);
	die;
}