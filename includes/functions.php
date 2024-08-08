<?php
use Tooltipy\Tooltipy;
use Tooltipy\Settings;

require_once TOOLTIPY_PLUGIN_DIR . 'admin/class-settings.php';

function tooltipy_get_glossary_letters(){
	$posts = get_posts( array(
		'post_type' 	=> Tooltipy::get_plugin_name(),
		'post_status' 	=> 'publish',
		'posts_per_page' => -1,
	));
	$letters = array();
	foreach ($posts as $key => $current_post) {
		$chars = tooltipy_str_split_unicode( $current_post->post_title);
		$char = strtolower( reset($chars) );
		//...
		if( !in_array( $char, $letters ) ){
			array_push( $letters, $char );
		}
	}
	
	if( count($letters ) ){
		sort( $letters );
		
		foreach ($letters as $key => $letter) {
			$letters[$key] = array(
				"label" => $letter, "value" => $letter
			);
		}

		$all_label = __( 'All', 'tooltipy' );
		$all_option_lab = tooltipy_get_option( 'glossary_label_all', false );

		if( $all_option_lab && '' != trim( $all_option_lab ) ){
			$all_label = trim( $all_option_lab );
		}

		$all_vector = array(
			array(
				"label" => $all_label,
				"value" => ""
			)
		);

		// Add all label in the top
		$letters = array_merge( $all_vector, $letters );
	}

	return $letters;
}

function tooltipy_get_posts_id_start_with( $first_letter ){
	global $wpdb;

	$first_letter = strtolower( $first_letter );
	$postids = array();

	if( !empty( $first_letter ) ){
		$posts = get_posts([
			'posts_per_page'	=> -1,
			'post_type'			=> Tooltipy::get_plugin_name()
		]);

		foreach( $posts as $post ){
			$chars = tooltipy_str_split_unicode( $post->post_title );

			if( strtolower( reset( $chars ) ) == strtolower( $first_letter ) ){
				array_push( $postids, $post->ID );
			}
		}
	}
	return $postids;
}

/**
 * tooltipy_get_option
 *
 * @param  string $field_id
 * @param  mixed $default
 * @param  bool $unique_option
 *
 * @return void
 */
function tooltipy_get_option( $field_id, $default = false, $unique_option = true, $in_settings = true ){

	$option_id = 'tltpy_' . $field_id;

	$option_value = get_option( $option_id, $default );

	if( !$in_settings ){
		return $option_value;
	}

	$field = Settings::get_field( $field_id );

	$field_type = isset( $field[ 'type' ] ) ? $field[ 'type' ] : 'text' ;

	// If the option doesn't exist return the default value of the field
	if( 
		( 
			!in_array( $field_type, array( 'radio', 'select', 'checkbox' ) )
			&&
			empty($option_value)
		)
		||
		(
			in_array( $field_type, array( 'radio', 'select', 'checkbox' ) )
			&&
			false === $option_value
		)
	){

		if( $field &&  array_key_exists( 'default', $field ) ){
			$option_value = $field['default'];
		}
	}
	

	// If field type is radio or select return first elem in array result
	if( 
		$unique_option // Should be FALSE only for export
		&& in_array( $field_type, array( 'radio', 'select', 'checkbox' ) )
		&& is_array( $option_value )
		&& count($option_value)
	){
		$option_value = $option_value[0];
	}	
	
	return $option_value;
}

/**
 * tooltipy_add_option
 *
 * @param  string $option
 * @param  mixed $value
 * @param  string $deprecated
 * @param  string|bool $autoload
 *
 * @return bool
 */
function tooltipy_add_option( $option, $value = '', $deprecated = '', $autoload = 'yes' ){
	return add_option( 'tltpy_' . $option, $value, $deprecated, $autoload );
}

/**
 * tooltipy_update_option
 *
 * @param  string $option
 * @param  mixed $value
 * @param  string|bool $autoload
 *
 * @return bool
 */
function tooltipy_update_option( $option, $value, $autoload = null ){
	return update_option( 'tltpy_' . $option, $value, $autoload = null );
}

/**
 * tooltipy_debug
 *
 * @param  mixed $var
 *
 * @return void
 */
function tooltipy_debug( $var ){
	echo '<pre class="tooltipy-debug">'. print_r( $var, true ) .'</pre>';
}

/**
 * tooltipy_get_related_posts
 * Returns the list of related posts for the current tooltip
 *
 * @return void
 */
function tooltipy_get_related_posts(){
	global $post;

	$tooltip_post = $post;

	$related_posts = [];

	$args = array(
		'post_type'			=> Tooltipy::get_related_post_types(),
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish',
		'meta_key' 			=> 'tltpy_exclude_me',
		'meta_value' 		=> 'on',
		'meta_compare'		=> '!=',
	);
	$all_posts = get_posts( $args );

	if( count( $all_posts ) ){
		foreach ($all_posts as $related_post){
			$matched_tooltips = get_post_meta( $related_post->ID, 'tltpy_matched_tooltips', true );
			$matched = false;

			if( !is_array($matched_tooltips) ){
				continue;
			}
			
			foreach ($matched_tooltips as $ttp) {
				if( $ttp['tooltip_id'] == $tooltip_post->ID ){
					$matched = true;
					break;
				}
			}
			if( $matched ){
				array_push( $related_posts, array(
					'id'        => $related_post->ID,
					'title'     => $related_post->post_title,
					'permalink' => get_the_permalink( $related_post ),
				) );
			}
		}
	}
	return $related_posts;
}

/**
 * Print a custom message in the ../wp-content/debug.log file if the debug_mode option is activated
 * Note : you should set the 'WP_DEBUG_LOG' constant to true in the wp-config.php file :
 * define( 'WP_DEBUG_LOG', true );
 */
function tooltipy_log( $msg ){
	$debug_mode_setting = tooltipy_get_option( 'debug_mode' );

	if( !$debug_mode_setting ){
		return false;
	}
	
	$backtrace = debug_backtrace();
	$caller = array_shift( $backtrace );

	$caller_file = preg_replace( '/.*\/wp-content\//', '.../wp-content/', $caller['file'] );
	$caller_line = $caller['line'];

	error_log( '--- TOOLTIPY ---' );
	error_log( ' * File: ' .$caller_file );
	error_log( ' * line : ' .$caller_line);

	error_log( '<pre>' . print_r( $msg, true ) . '</pre>' );

	error_log( '--------' );
}

function tooltipy_str_split_unicode($str, $l = 0){
    return preg_split('/(.{'.$l.'})/us', $str, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
}