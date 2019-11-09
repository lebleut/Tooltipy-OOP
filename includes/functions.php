<?php

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

        $all_vector = array(
            array(
                "label" => __( "All", "tooltipy-lang" ), "value" => ""
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
 * @param  bool $cache
 * @param  bool $unique_option
 *
 * @return void
 */
function tooltipy_get_option( $field_id, $default = false, $cache = true, $unique_option = true ){

    $option_id = 'tltpy_' . $field_id;

    $option_value = get_option( $option_id, $default );

    $field = Tooltipy_Settings::get_field( $field_id );

	// If the option doesn't exist return the default value of the field
	if( false === $option_value ){

		if( $field &&  array_key_exists( 'default', $field ) ){
			$option_value = $field['default'];
		}
	}
    
    $field_type = isset( $field[ 'type' ] ) ? $field[ 'type' ] : 'text' ;

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