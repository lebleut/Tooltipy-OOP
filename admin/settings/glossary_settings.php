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
		'' => __tooltipy( 'Select a page' )
	);
	foreach ($pages as $page) {
		$all_pages[$page->ID] = $page->post_title;
	}

	$settings = array(
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_page',
			'type' 			=> 'select',

			'label' 		=> __tooltipy( 'Glossary page' ),
			'helper' 		=> __tooltipy( 'Select the page on which the glossary will show up' ),

			'options' 		=> $all_pages,
			'default' 		=> array( '' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_tooltips_per_page',
			'type' 			=> 'number',

			'label' 		=> __tooltipy( 'Tooltips per page' ),

			'placeholder' 	=> __tooltipy( 'Default' ),
			'helper' 		=> __tooltipy( 'Keywords per glossary page' ),
			'default'		=> '',
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_all',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'ALL label' ),
			'placeholder' 	=> __tooltipy( 'ALL' ),
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_previous',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Previous label' ),
			'placeholder' 	=> __tooltipy( 'Previous' ),
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_next',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Next label' ),
			'placeholder' 	=> __tooltipy( 'Next' ),
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_select_category',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Select a category label' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __tooltipy( 'Select a category' ),
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_all_categories',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'All categories label' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __tooltipy( 'All categories' ),
		),

		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_show_thumbnails',
			'type' 			=> 'checkbox',

			'label' 		=> __tooltipy( 'Glossary thumbnails' ),

			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Show thumbnails on the glossary page' ),
			),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_link_titles',
			'type' 			=> 'checkbox',

			'label' 		=> __tooltipy( 'Titles' ),

			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Add links to titles' ),
			),
			'default'		=> array( 'yes' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_ajax',
			'type' 			=> 'checkbox',

			'label' 		=> __tooltipy( 'Ajax' ),

			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Load glossary with Ajax' ),
			),
			'default'		=> array( 'yes' ),
		),
	);
	
	// Assign the GLOSSARY tab slug
	foreach ( $settings as $key => $setting ) {
		$settings[$key]["tab"] = "glossary";
	}
	
	$fields = array_merge( $fields, $settings );

	return $fields;
}

add_action( 'updated_option', 'tltpy_updated_glossary_serttings', 10, 3 );

function tltpy_updated_glossary_serttings( $option, $old_value, $value ){
	// Need to flush the rewrite rules if glossary page changed
    if(
		$option == 'tltpy_' . 'glossary_page'
		&& serialize($value) != serialize($old_value)
	){
        add_option( 'tltpy_' . 'flush_rewrite_rules',true);
    }
}