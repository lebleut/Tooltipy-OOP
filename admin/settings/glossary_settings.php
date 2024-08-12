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
		'' => __( 'Select a page', 'tooltipy' )
	);
	foreach ($pages as $page) {
		$all_pages[$page->ID] = $page->post_title;
	}

	$settings = array(
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_page_id',
			'type' 			=> 'select',

			'label' 		=> __( 'Glossary page', 'tooltipy' ),
			'helper' 		=> __( 'Select the page on which the glossary will show up', 'tooltipy' ),

			'options' 		=> $all_pages,
			'default' 		=> array( '' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_tooltips_per_page',
			'type' 			=> 'number',

			'label' 		=> __( 'Tooltips per page', 'tooltipy' ),

			'placeholder' 	=> __( 'Default', 'tooltipy' ),
			'helper' 		=> __( 'Keywords per glossary page', 'tooltipy' ),
			'default'		=> '',
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_all',
			'type' 			=> 'text',

			'label' 		=> __( 'ALL label', 'tooltipy' ),
			'placeholder' 	=> __( 'ALL', 'tooltipy' ),
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_previous',
			'type' 			=> 'text',

			'label' 		=> __( 'Previous label', 'tooltipy' ),
			'placeholder' 	=> __( 'Previous', 'tooltipy' ),
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_next',
			'type' 			=> 'text',

			'label' 		=> __( 'Next label', 'tooltipy' ),
			'placeholder' 	=> __( 'Next', 'tooltipy' ),
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_select_category',
			'type' 			=> 'text',

			'label' 		=> __( 'Select a category label', 'tooltipy' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __( 'Select a category', 'tooltipy' ),
		),
		array(
			'section' 		=> 'labels',
			
			'uid' 			=> 'glossary_label_all_categories',
			'type' 			=> 'text',

			'label' 		=> __( 'All categories label', 'tooltipy' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __( 'All categories', 'tooltipy' ),
		),

		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_show_thumbnails',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Glossary thumbnails', 'tooltipy' ),

			'options' 		=> array(
				'yes' 		=> __( 'Show thumbnails on the glossary page', 'tooltipy' ),
			),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_link_titles',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Titles', 'tooltipy' ),

			'options' 		=> array(
				'yes' 		=> __( 'Add links to titles', 'tooltipy' ),
			),
			'default'		=> array( 'yes' ),
		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'glossary_ajax',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Ajax', 'tooltipy' ),

			'options' 		=> array(
				'yes' 		=> __( 'Load glossary with Ajax', 'tooltipy' ),
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
		$option == 'tltpy_' . 'glossary_page_id'
		&& serialize($value) != serialize($old_value)
	){
        add_option( 'tltpy_' . 'flush_rewrite_rules',true);
    }
}