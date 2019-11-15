<?php
use Tooltipy\Tooltipy;

add_filter( 'tltpy_setting_fields', 'tltpy_get_scope_settings' );

function tltpy_get_scope_settings( $fields ){
	$settings = array(
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'cover_classes',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Cover CSS classes' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __( 'Class ...' ),
			'helper' 		=> __tooltipy( 'Choose CSS classes to cover with tooltips' ),
			'description' 	=> __( 'NB : Please avoid overlapped classes !<br>If you leave Classes AND Tags blank the whole page will be affected' ),

		),
		array(
			'section' 		=> 'general',
			
			'uid' 			=> 'cover_html_tags',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Cover HTML TAGS' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __( 'HTML tag ...' ),
			'helper' 		=> __( 'Choose HTML TAGS (like h1, h2, strong, p, ... ) to cover with tooltips' ),
		),
		array(
			'section' 		=> 'exclude',
			
			'uid' 			=> 'exclude_classes',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Exclude CSS classes' )
								.'<div style="color:red;">Not yet implemented</div>',
			'placeholder' 	=> __( 'Class ...' ),
			'helper' 		=> __tooltipy( 'Choose CSS classes to exclude' ),
		),
		array(
			'section' 		=> 'exclude',
			
			'uid' 			=> 'exclude_links',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude links ?' )
								.'<div style="color:red;">Not yet implemented</div>',

			'options' 		=> array(
				'yes' 		=> __tooltipy( 'Yes' ),
			),
		),
		array(
			'section' 		=> 'exclude',
			
			'uid' 			=> 'exclude_heading_tags',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude Headings ?' )
								.'<div style="color:red;">Not yet implemented</div>',

			'options' 		=> array(
				'h1' 		=> 'H1',
				'h2' 		=> 'H2',
				'h3' 		=> 'H3',
				'h4' 		=> 'H4',
				'h5' 		=> 'H5',
				'h6' 		=> 'H6',
			),
		),
		array(
			'section' 		=> 'exclude',
			
			'uid' 			=> 'exclude_common_tags',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude Common Tags ?' )
								.'<div style="color:red;">Not yet implemented</div>',

			'options' 		=> array(
				'strong' 		=> '<&zwnj;strong &zwnj;/>',
				'b' 			=> '<&zwnj;b &zwnj;/>',
				'abbr' 			=> '<&zwnj;abbr &zwnj;/>',
				'button' 		=> '<&zwnj;button &zwnj;/>',
				'dfn' 			=> '<&zwnj;dfn &zwnj;/>',
				'em' 			=> '<&zwnj;em &zwnj;/>',
				'i' 			=> '<&zwnj;i &zwnj;/>',
				'label' 		=> '<&zwnj;label &zwnj;/>',
			),
		),
	);
	
	// Assign the COVER tab slug
	foreach ( $settings as $key => $setting ) {
		$settings[$key]["tab"] = "cover";
	}

	$fields = array_merge( $fields, $settings );

	return $fields;
}