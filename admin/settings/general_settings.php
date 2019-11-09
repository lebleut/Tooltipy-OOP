<?php
add_filter( 'tltpy_setting_fields', 'tltpy_add_general_serttings' );

function tltpy_add_general_serttings( $fields ){
    $settings = Tooltipy_Settings::get_fields();

    // Assign the GENERAL tab slug
    foreach ( $settings as $key => $setting ) {
        $settings[$key]["tab"] = "general";
    }

    $fields = array_merge( $fields, $settings );

    return $fields;
}