<?php

/**
 * Works like the theme Wordpress get_template_part()
 */
function tooltipy_template_part( $file_prefix, $file_suffix = '', $args = array() ){
    
    if( !empty( $args ) ){
        // specific variables could be passed and used here
        extract( $args );
    }

    $template_file = TOOLTIPY_PLUGIN_DIR . 'public/partials/' . $file_prefix;
    if( !empty( $file_suffix ) ){
        $template_file .= '-' . $file_suffix;
    }
    $template_file .= '.php';

    include $template_file;
}

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

function tltpy_popup_add_main_section( $id, $content ){
    $tmpl_args = array(
        'id' => $id,
        'content' => $content
     );
    tooltipy_template_part( 'tooltip', 'pop', $tmpl_args );
}

function tltpy_popup_add_synonyms_section( $id ){
    $tmpl_args = array(
        'id' => $id
    );

    tooltipy_template_part( 'tooltip', 'synonyms', $tmpl_args );
}