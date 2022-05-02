<?php
function get_tooltips(){
	global
		$wpdb,
		$tooltipydb,
		$cached_tooltips;

	if( empty($cached_tooltips) ){
		$cached_tooltips = [];
	}

	if( isset( $cached_tooltips ) && !empty( $cached_tooltips ) ){
		return $cached_tooltips;
	}

	$table_name = $tooltipydb->get_table_name();

	$cached_tooltips = $wpdb->get_results( "SELECT * FROM " . $table_name );

	return $cached_tooltips;
}

function get_tooltip( $tooltip_id ){
	global
		$wpdb,
		$tooltipydb;

	$table_name = $tooltipydb->get_table_name();
	
	if( !is_numeric($tooltip_id) ){
		return false;
	}

	$tooltip = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE ID = $tooltip_id" );
	return $tooltip;
}

function add_tooltip( $args ){
	return update_tooltip( '', $args );
}

function update_tooltip( $id, $args ){
	global $wpdb, $tooltipydb;

	$default_args = [
		'ID'				=> '',
		'title'             => '',
		'name'             	=> '',
		'content'           => '',
		'author'            => 0,
		'date'              => date("Y-m-d H:i:s"),
		'updated'           => date("Y-m-d H:i:s"),
		'status'            => 'publish',
		'synonyms'          => '',
		'case_sensitive'    => NULL,
		'is_prefix'         => NULL,
		'is_wiki'           => NULL,
		'video_url'         => '',
		'wiki_term'         => ''
	];

	$tooltip = false;
	if( !empty($id) ){
		$tooltip = get_tooltip( $id );
	}

	if( !empty($tooltip) && is_object($tooltip) ){
		$default_args = [
			'ID'				=> $tooltip->ID,
			'title'             => $tooltip->title,
			'name'             	=> $tooltip->name,
			'content'           => $tooltip->content,
			'author'            => $tooltip->author,
			'date'              => $tooltip->date,
			'updated'           => date("Y-m-d H:i:s"),
			'status'            => $tooltip->status,
			'synonyms'          => $tooltip->synonyms,
			'case_sensitive'    => $tooltip->case_sensitive,
			'is_prefix'         => $tooltip->is_prefix,
			'is_wiki'           => $tooltip->is_wiki,
			'video_url'         => $tooltip->video_url,
			'wiki_term'         => $tooltip->wiki_term
		];
	}

	$args = wp_parse_args( $args, $default_args );
	extract( $args );

	$args['title'] = trim($args['title']);

	if( empty( $args['title'] ) ){
		return false;
	}

	if( empty($args['name']) ){
		$args['name'] = generate_tooltip_slug( $title );
	}

	if( empty($args['ID']) ){
		unset($args['ID']);
		$inserted = $wpdb->insert( $tooltipydb->get_table_name(), $args );
		
		if( $updated ){
			return $wpdb->insert_id;
		}
	}else{
		$updated = $wpdb->update( $tooltipydb->get_table_name(), $args, array( 'ID' => $args['ID'] ) );
		if( false === $updated ){
			return false;
		}else{
			return $args['ID'];
		}
	}

	return false;
}

/**
 * 
 */
function generate_tooltip_slug( $title ){
	$new_name = sanitize_title_with_dashes( trim( $title ) );

	$tooltips_slugs = get_tooltips();
	$tooltips_slugs = array_map(function( $elem ){
		return $elem->name;
	}, $tooltips_slugs );

	$i = 0;
	do {
		$str_i = empty($i) ? '' : '-' . $i;
		$stug_exists = in_array( $new_name . $str_i, $tooltips_slugs );
		$i++;
	} while ( $stug_exists );

	return $new_name . $str_i;
}