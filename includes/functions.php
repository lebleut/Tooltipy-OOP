<?php
use Tooltipy\Tooltipy;
use Tooltipy\Settings;

require_once TOOLTIPY_PLUGIN_DIR . 'admin/class-settings.php';

function tooltipy_get_glossary_letters(){
	$posts = get_posts( array(
		'post_type' 	=> Tooltipy::get_plugin_name(),
		'post_status' 	=> 'publish',
	));
	$letters = array();
	foreach ($posts as $key => $current_post) {
		$char = substr( $current_post->post_title, 0, 1);
		$char = strtolower( $char );
		//...
		if( !in_array( $char, $letters ) ){
			array_push( $letters, $char );
		}
	}
	
	if( count($letters ) ){
		foreach ($letters as $key => $letter) {
			$letters[$key] = array(
				"label" => $letter, "value" => $letter
			);
		}

		$all_label = __tooltipy( 'All' );
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
		$postids = $wpdb->get_col(
				$wpdb->prepare("
					SELECT      ID
					FROM        $wpdb->posts
					WHERE       SUBSTR($wpdb->posts.post_title,1,1) = %s
					ORDER BY    $wpdb->posts.post_title",
					$first_letter
				)
			); 
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
function tooltipy_get_option( $field_id, $default = false, $unique_option = true ){

	$option_id = 'tltpy_' . $field_id;

	$option_value = get_option( $option_id, $default );

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
 * __tooltipy()
 * Returns the internationalized message according to tooltipy
 *
 * @param  mixed $msg
 *
 * @return string
 */
function __tooltipy( $msg ){
	return __( $msg, 'tooltipy-lang' );
}

/**
 * _e_tooltipy
 * Echo out the internationalized message according to tooltipy
 * 
 * @param  mixed $msg
 *
 * @return void
 */
function _e_tooltipy( $msg ){
	echo __tooltipy( $msg );
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
 * Returns the wikipedia data depending on the wiki term of the post
 *
 * @param  mixed $post_id
 *
 * @return void
 */
function tooltipy_get_post_wiki_data( $post_id ){
	$wiki_term = get_post_meta( $post_id, 'tltpy_wiki_term', true );
	$wiki_lang = tooltipy_get_option( 'wikipedia_lang', 'en' );

	if( !$wiki_term || '' == trim($wiki_term) ){
		$wiki_term = get_the_title( $post_id );
	}

	$wiki_term = trim( $wiki_term );
	
	// remove spaces and add underscores
	$wiki_term_arr = explode( ' ', str_replace( '_', ' ', $wiki_term ) );

	$wiki_term = implode( '_', array_map( function($word){
		return ucfirst( strtolower( trim($word) ) );
	}, $wiki_term_arr ) );

	$url = 'https://' . $wiki_lang . '.wikipedia.org/api/rest_v1/page/summary/' . $wiki_term;
	$headers = get_headers($url);

	$http_response_code = substr($headers[0], 9, 3);
	
	// if redirection
	if( in_array( $http_response_code, array( '301','302' ) ) ){
		if( '301' == $http_response_code ){
			$url = 'https://' . $wiki_lang . '.wikipedia.org/api/rest_v1/page/summary/'. substr($headers[1], 10 );
		}
		
		if( '302' == $http_response_code ){
			$url = 'https://' . $wiki_lang . '.wikipedia.org/api/rest_v1/page/summary/'. substr($headers[2], 10 );
		}

		$headers = get_headers($url);

		$http_response_code = substr($headers[0], 9, 3);
	}

	if( in_array( $http_response_code, array( "200", "304" )) ){
		$json = file_get_contents($url);
		$obj = json_decode($json);

		return $obj;
	}

	return false;
}


/**
 * tooltipy_get_related_posts
 * Returns the list of related posts for the current tooltip
 *
 * @return void
 */
function tooltipy_get_related_posts(){
	global $post;

	$related_posts = [];

	$args = array(
		'post_type'		=> 'any',
		'posts_per_page'	=> -1,
		'post_status'	=> 'publish',
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
				if( $ttp['tooltip_id'] == $post->ID ){
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