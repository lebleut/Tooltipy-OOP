<?php
use Tooltipy\Tooltipy;

add_filter( 'tltpy_setting_fields', 'tltpy_get_scope_settings' );

function tltpy_get_scope_settings( $fields ){
	$settings = [
		[
			'section' 		=> 'general',
			
			'uid' 			=> 'exclude_classes',
			'type' 			=> 'text',

			'label' 		=> __tooltipy( 'Exclude CSS classes' ),
			'placeholder' 	=> __( 'Class ...' ),
			'helper' 		=> __tooltipy( 'Choose CSS classes to exclude that should be nested only in the post content' ),
		],
		[
			'section' 		=> 'general',
			
			'uid' 			=> 'exclude_links',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude links ?' ),

			'options' 		=> [
				'yes' 		=> __tooltipy( 'Yes' ),
			],
		],
		[
			'section' 		=> 'general',
			
			'uid' 			=> 'exclude_heading_tags',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude Headings ?' ),

			'options' 		=> [
				'h1' 		=> 'H1',
				'h2' 		=> 'H2',
				'h3' 		=> 'H3',
				'h4' 		=> 'H4',
				'h5' 		=> 'H5',
				'h6' 		=> 'H6',
			],
		],
		[
			'section' 		=> 'general',
			
			'uid' 			=> 'exclude_common_tags',
			'type' 			=> 'checkbox',

			'label' 		=> __( 'Exclude Common Tags ?' ),

			'options' 		=> [
				'strong' 		=> '<&zwnj;strong &zwnj;/>',
				'b' 			=> '<&zwnj;b &zwnj;/>',
				'abbr' 			=> '<&zwnj;abbr &zwnj;/>',
				'button' 		=> '<&zwnj;button &zwnj;/>',
				'dfn' 			=> '<&zwnj;dfn &zwnj;/>',
				'em' 			=> '<&zwnj;em &zwnj;/>',
				'i' 			=> '<&zwnj;i &zwnj;/>',
				'label' 		=> '<&zwnj;label &zwnj;/>',
			],
		],

		[
			'section' 		=> 'excluded_posts',
			
			'uid' 			=> 'excluded_list',
			'type' 			=> 'custom',
	
			'label' 		=> __tooltipy( 'Excluded post list' ),

			'callback'		=> 'tltpy_excluded_posts_list'
		]
	];
	
	// Assign the exclude tab slug
	foreach ( $settings as $key => $setting ) {
		$settings[$key]["tab"] = 'exclude';
	}

	$fields = array_merge( $fields, $settings );

	return $fields;
}

function tltpy_excluded_posts_list(){
	$excluded_posts = get_posts([
		'posts_per_page'=> -1,
		'post_type'		=> Tooltipy::get_related_post_types(),
		'meta_query' =>[
			'relation'	=> 'AND',
			[
				'key'		=> 'tltpy_exclude_me',
				'compare'	=> 'EXISTS',
			],
			[
				'key'		=> 'tltpy_exclude_me',
				'value'		=> 'on',
				'compare'	=> '==',
			],
		]
	]);

	// Sort by post type
	uasort($excluded_posts, function($p1, $p2){
		if($p1->post_type == $p2->post_type){
			return 0;
		}

		$arr = [$p1->post_type, $p2->post_type];
		sort($arr);

		if( reset($arr) == $p1->post_type ){
			return -1;
		}else{
			return 1;
		}
	});

	if( count($excluded_posts) ){
		echo '<table class="tltpy_excluded_posts">';
			foreach( $excluded_posts as $post ): ?>
				<tr>
					<td><i><?php echo $post->post_type ?></i></td>
					<td><strong><?php echo $post->post_title ?></strong></td>
					<td><a target="_blank" href="<?php the_permalink( $post->ID ) ?>">View</a></td>
					<td><a target="_blank" href="<?php echo get_edit_post_link( $post->ID ) ?>">Edit</a></td>
				</tr>
			<?php endforeach;
		echo '</table>';
	}else{
		_e_tooltipy('No posts excluded');
	}
}