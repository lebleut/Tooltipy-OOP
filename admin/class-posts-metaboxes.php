<?php
namespace Tooltipy;

class Posts_Metaboxes{
	public function __construct() {
		add_action( 'do_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 3 );
		
		// Filter metabox fields before save if needed
		$this->filter_metabox_fields();

		add_action('save_post', array( $this, 'save_metabox_fields' ) );
		add_action('save_post', array( $this, 'regenerate_matched_tooltips' ) );

		// Regenerate matched tooltips when restoring a post revision
		add_action( 'wp_restore_post_revision', array( $this, 'regenerate_matched_tooltips'), 10 );
	}

	function is_related_posttype( $post_id ){
		$related_posttypes = Tooltipy::get_related_post_types();
		$current_post = get_post( $post_id );

		if( in_array( $current_post->post_type, $related_posttypes ) ){
			return true;
		}else{
			return false;
		}
	}

	function regenerate_matched_tooltips( $post_id ){
		if( !$this->is_related_posttype( $post_id ) ){
			return false;
		}

		// Not for Tooltipy post type
		if( !empty($_POST['post_type']) && $_POST['post_type'] == Tooltipy::get_plugin_name() ){
			return false;
		}

		// editpost : to prevent bulk edit problems
		if( !empty($_POST['action']) && $_POST['action'] == 'editpost' ){


			$current_post = get_post( $post_id );

			$new_value = self::filter_matched_tooltips( $current_post->post_content );

			update_post_meta( $post_id, 'tltpy_matched_tooltips', $new_value);
		}
	}

	// Filter metabox fields before save if needed
	public function filter_metabox_fields(){
		// Filter fields here
		add_filter('tltpy_posts_metabox_field_before_save_' . 'tltpy_matched_tooltips', __CLASS__ . '::filter_matched_tooltips', 10, 1 );
		add_filter('tltpy_posts_metabox_field_before_save_' . 'tltpy_exclude_tooltips', array($this, 'filter_exclude_tooltips'), 10, 2 );
	}

	function filter_exclude_tooltips( $old_value, $post_vars ){
		$new_value = '';

		$arr_value = explode( ',', $old_value);
		$arr_value = array_map( 'trim', $arr_value );
		$arr_value = array_map( 'strtolower', $arr_value );

		foreach ($arr_value as $key => $value) {
			if( empty($value) ){
				unset( $arr_value[$key] );
			}
		}
		$new_value = implode( ', ', $arr_value );

		return $new_value;
	}

	public static function filter_matched_tooltips( $data ){
		$content = $data;

		if( is_array( $data ) ){
			$content = $data['post_content'];
		}        

		$tooltips = Tooltipy::get_tooltips();

		$matched_tooltips = array();
		foreach($tooltips as $tltp){
			$keywords = array( $tltp->post_title );
			$syn_meta = get_post_meta( $tltp->ID, 'tltpy_synonyms', true );
			$is_prefix = get_post_meta( $tltp->ID, 'tltpy_is_prefix', true );
			$is_case = get_post_meta( $tltp->ID, 'tltpy_case_sensitive', true );

			if( $syn_meta ){
				$keywords = array_merge( $keywords, explode( '|', $syn_meta ) );
			}

			// Quote regular expression characters
			$keywords = array_map( 'preg_quote', $keywords );

			if( $is_prefix ){
				$keywords = array_map(function( $kw ){ return $kw."\w*"; }, $keywords );
			}

			$pattern = '/(\W|^)'.'('. implode( '|', $keywords ) .')'.'(\W|$)/';
			
			if( !$is_case || $is_case != 'on' ){
				$pattern = $pattern . 'i';
			}

			preg_match( $pattern, $content, $matches, PREG_OFFSET_CAPTURE);

			if( is_array($matches) && count($matches) == 1 && empty($matches[0]) ){
				$matches = array();
			}

			if( !empty($matches) ){
				$tltp_vector = array(
					'tooltip_id'    => $tltp->ID,
					'tooltip_title' => $tltp->post_title,
					'tooltip_offset'=> $matches[0][1]
				);
				array_push($matched_tooltips, $tltp_vector );
			}
		}

		// Sort matched tooltips according to tooltip offset
		usort( $matched_tooltips, function( $a, $b ){
			if ($a['tooltip_offset'] == $b['tooltip_offset']) {
				return 0;
			}
			return ($a['tooltip_offset'] < $b['tooltip_offset']) ? -1 : 1; 
		} );

		return $matched_tooltips;
	}

	function save_metabox_fields( $post_id ){
		// Not for Tooltipy post type
		if( !empty($_POST['post_type']) && $_POST['post_type'] == Tooltipy::get_plugin_name() ){
			return false;
		}

		// editpost : to prevent bulk edit problems
		if( !empty($_POST['action']) && $_POST['action'] == 'editpost' ){

			// Save metabox fields
			$metabox_fields = $this->get_metabox_fields();
			foreach ( $metabox_fields as $field) {
				$this->save_metabox_field( $post_id, $field['meta_field_id']);
			}
		}
	}

	function save_metabox_field( $post_id, $meta_field_id, $sanitize_function = 'sanitize_text_field' ){
		// Don't save these meta fields
		if( in_array( $meta_field_id, [ 'tltpy_matched_tooltips' ] ) )
			return;

		if(  !isset($_POST[$meta_field_id]) ){
			$value = call_user_func( $sanitize_function, '' );
		}else{
			if( is_array($_POST[$meta_field_id]) ){
				$value = $_POST[$meta_field_id];
			}else{
				$value = call_user_func( $sanitize_function, $_POST[$meta_field_id] );
			}
		}

		// Filter hook before saving meta field
		$value = apply_filters( 'tltpy_posts_metabox_field_before_save_' . $meta_field_id, $value, $_POST);

		update_post_meta( $post_id, $meta_field_id, $value);
	}

	function add_meta_boxes( $post_type, $context, $post ){
		// For all posts except Tooltipy
		if( Tooltipy::get_plugin_name() == $post_type ){
			return false;
		}

		//for post types except my_keywords
		$all_post_types = Tooltipy::get_related_post_types();
		foreach($all_post_types as $screen) {
			add_meta_box(
				'tltpy_posts_metabox',
				__( 'Tooltipy' ),
				array( $this, 'metabox_render' ) ,
				$screen,
				'side',
				'high'
			);
		}
	}

	static function get_metabox_fields(){
		$tooltip_fields = array(
			array(
				'meta_field_id' => 'exclude_me',
				'callback'      => array( __CLASS__, 'exclude_me_field' )
			),
			array(
				'meta_field_id' => 'matched_tooltips',
				'callback'      => array( __CLASS__, 'matched_tooltips_field' )
			),
			array(
				'meta_field_id' => 'exclude_tooltips',
				'callback'      => array( __CLASS__, 'exclude_tooltips_field' )
			),
			array(
				'meta_field_id' => 'exclude_cats',
				'callback'      => array( __CLASS__, 'exclude_tooltip_cats_field' )
			),
		);
		
		// Filter hook
		$tooltip_fields = apply_filters( 'tltpy_posts_metabox_fields', $tooltip_fields);
		
		// Add metadata prefix
		foreach( $tooltip_fields as $key => $field ){
			$tooltip_fields[$key]['meta_field_id'] = 'tltpy_' . $field['meta_field_id'];
		}
		return $tooltip_fields;
	}

	function metabox_render(){
		$metabox_fields = $this->get_metabox_fields();

		foreach ($metabox_fields as $field) {
			call_user_func( $field['callback'], $field['meta_field_id'] );
		}
	}
	
	function exclude_me_field( $meta_field_id ){
		global $post_type;
		$post_type_label = $post_type;
		$currentPostType = get_post_type_object(get_post_type());

		if ($currentPostType) {
			$post_type_label = esc_html($currentPostType->labels->singular_name);
		}

		$is_checked = get_post_meta( get_the_id(), $meta_field_id ,true) ? 'checked' : '';
		?>
		<p>
			<h4><?php _e( 'Exclude this post from being matched', 'tooltipy' ); ?></h4>
			<Label><?php echo( __( 'Exclude this ', 'tooltipy' ) . '<b>' . strtolower($post_type_label) . '</b>' ); ?>
				<input type="checkbox" 
					name="<? echo( $meta_field_id ); ?>" 
					<?php echo ( $is_checked ); ?> 
				/>
			</label>
		</p>
		<?php
	}
	
	function matched_tooltips_field($meta_field_id){
		$matched_tooltips = get_post_meta( get_the_id(), $meta_field_id, true );
		$excluded_tooltips = get_post_meta( get_the_id(), 'tltpy_exclude_tooltips', true );
		$excluded_tooltips = explode( ',', $excluded_tooltips );
		$excluded_tooltips = array_map( 'strtolower', $excluded_tooltips );
		$excluded_tooltips = array_map( 'trim', $excluded_tooltips );

		?>
		<h4><?php _e( 'Tooltips in this post', 'tooltipy' ); ?></h4>
		<?php
		if( empty($matched_tooltips) ){
			?>
			<p style="color:red;"><?php _e( 'No tooltips matched yet', 'tooltipy' ); ?></p>
			<?php
			return false;
		}
		?>
		<ul style="padding: 0px 10px;">
			<?php
			foreach ($matched_tooltips as $tltp) {
				$class = in_array( strtolower($tltp['tooltip_title']), $excluded_tooltips) ? "tltpy-excluded" : "tltpy-matched";
			  ?>
			  <li class="<?php echo $class; ?>"><?php echo($tltp['tooltip_title']); ?></li>
			  <?php  
			}
			?>
		</ul>
		<?php
	}

	function exclude_tooltips_field($meta_field_id){
		$excluded_tooltips = get_post_meta( get_the_id(), $meta_field_id, true );
		?>
		<h4><?php _e( 'Tooltips to exclude', 'tooltipy' ); ?></h4>
		<input
			type="text"
			name="<?php echo($meta_field_id); ?>"
			placeholder="tooltip 1, tooltip 2,..."
			value="<?php echo( $excluded_tooltips ); ?>"
		>
		<?php
	}
	function exclude_tooltip_cats_field($meta_field_id){
		$tooltip_cats = get_terms( Tooltipy::get_taxonomy() );

		$excluded_tooltip_cats = get_post_meta( get_the_id(), $meta_field_id, true );
		?>
		<h4><?php _e( 'Categories to exclude', 'tooltipy' ); ?></h4>

		<div class="tltpy_check_all_cats_wrap">
			<label>
				<input type="checkbox" id="tltpy-check-all-cats">
				Exclude all
			</label>
			<span class="tltpy_excluded_cats_nbr" ></span>
		</div>
		
		<?php
			require_once TOOLTIPY_BASE_DIR . '/admin/walkers/class-exclude-cats-walker.php';
			$selected_cats = get_post_meta( get_the_id(), 'tltpy_exclude_cats', true );
			
			if( !is_array( $selected_cats ) ){
				$selected_cats = [];
			}

			$args = array(
				'descendants_and_self'  => 0,
				'selected_cats'         => $selected_cats,
				'popular_cats'          => false,
				'walker'                => new Exclude_Cats_Walker,
				'taxonomy'              => Tooltipy::get_taxonomy(),
				'checked_ontop'         => false
			);
		?>
	
		<div class="tltpy-exclude-cats">
			<?php wp_terms_checklist( 0, $args ); ?>
		</div>

		<style>
			.tltpy-exclude-cats {
				height: 100px;
				overflow-y: auto;
				border: 1px solid lightgrey;
				padding: 0.5rem;
			}
			.tltpy-exclude-cats li{
				list-style: none;
			}
			.tltpy-exclude-cats ul.children li{
				margin-left: 2rem;
			}
		</style>
		<?php
	}
}