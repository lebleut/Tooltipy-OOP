<?php
use Tooltipy\Tooltipy;

add_filter( 'tltpy_setting_fields', 'tltpy_get_glossary_settings' );

function tltpy_get_glossary_settings( $fields ){
    $args = array(
        'sort_order' => 'asc',
        'sort_column' => 'post_title',
        'post_type' => 'page',
    );

    $pages = get_pages( $args );

    $all_pages = array(
        '' => __( 'Select a page', 'tooltipy-lang' )
    );
    foreach ($pages as $page) {
        $all_pages[$page->ID] = $page->post_title;
    }

    $settings = array(
        array(
            'section' 		=> 'general',
            
            'uid' 			=> 'glossary_page',
            'type' 			=> 'select',

            'label' 		=> __( 'Glossary page', 'tooltipy-lang' ),
            'helper' 		=> __( 'Select the page on which the glossary will show up', 'tooltipy-lang' ),

            'options' 		=> $all_pages,
            'default' 		=> array( '' ),
        ),
        array(
            'section' 		=> 'general',
            
            'uid' 			=> 'glossary_tooltips_per_page',
            'type' 			=> 'number',

            'label' 		=> __( 'Tooltips per page', 'tooltipy-lang' )
                                .'<div style="color:red;">Not yet implemented</div>',

            'placeholder' 	=> __( 'ALL', 'tooltipy-lang' ),
            'helper' 		=> __( 'Keywords Per Page (leave blank for unlimited keywords per page)', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_all',
            'type' 			=> 'text',

            'label' 		=> __( 'ALL label', 'tooltipy-lang' )
                                .'<div style="color:red;">Not yet implemented</div>',
            'placeholder' 	=> __( 'ALL', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_previous',
            'type' 			=> 'text',

            'label' 		=> __( 'Previous label', 'tooltipy-lang' )
                                .'<div style="color:red;">Not yet implemented</div>',
            'placeholder' 	=> __( 'Previous', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_next',
            'type' 			=> 'text',

            'label' 		=> __( 'Next label', 'tooltipy-lang' )
                                .'<div style="color:red;">Not yet implemented</div>',
            'placeholder' 	=> __( 'Next', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_select_category',
            'type' 			=> 'text',

            'label' 		=> __( 'Select a category label', 'tooltipy-lang' )
                                .'<div style="color:red;">Not yet implemented</div>',
            'placeholder' 	=> __( 'Select a category', 'tooltipy-lang' ),
        ),
        array(
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_all_categories',
            'type' 			=> 'text',

            'label' 		=> __( 'All categories label', 'tooltipy-lang' )
                                .'<div style="color:red;">Not yet implemented</div>',
            'placeholder' 	=> __( 'All categories', 'tooltipy-lang' ),
        ),

        array(
            'section' 		=> 'general',
            
            'uid' 			=> 'glossary_show_thumbnails',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Glossary thumbnails', 'tooltipy-lang' )
                                .'<div style="color:red;">Not yet implemented</div>',

            'options' 		=> array(
                'yes' 		=> __( 'Show thumbnails on the glossary page', 'tooltipy-lang' ),
            ),
        ),
        array(
            'section' 		=> 'general',
            
            'uid' 			=> 'glossary_link_titles',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Titles', 'tooltipy-lang' )
                                .'<div style="color:red;">Not yet implemented</div>',

            'options' 		=> array(
                'yes' 		=> __( 'Add links to titles', 'tooltipy-lang' ),
            ),
        ),
    );
    
    // Assign the GLOSSARY tab slug
    foreach ( $settings as $key => $setting ) {
        $settings[$key]["tab"] = "glossary";
    }
    
    $fields = array_merge( $fields, $settings );

    return $fields;
}