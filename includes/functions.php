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