<?php
namespace Tooltipy;

class Tooltip_Metaboxes{
	public function __construct() {
		// Hook after the Tooltipy edit title
		add_action( 'edit_form_after_title', array( $this, 'meta_box_after_title' ) );
		
		add_action( 'do_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 3 );

		// Filter metabox fields before save if needed
		$this->filter_metabox_fields();

		add_action('save_post', array( $this, 'save_metabox_fields' ) );
	}

	// Filter metabox fields before save if needed
	public function filter_metabox_fields(){
		add_filter( 'tltpy_tooltip_metabox_field_before_save_tltpy_synonyms', array( $this, 'filter_synonyms_field') );
	}

	public function filter_synonyms_field( $syn_value ){
		// replace ||||||| by only one
		$syn_value = preg_replace('(\|{2,100})','|',$syn_value);
		
		// Eliminate spaces special caracters
		$syn_value = preg_replace('(^\||\|$|[\s]{2,100})','',$syn_value);

		return $syn_value;
	}
	
	public function meta_box_after_title(){
		do_meta_boxes(
			Tooltipy::get_plugin_name(),
			'tooltipy_after_title',
			'' 
		);
	}

	function add_meta_boxes( $post_type, $context, $post ){
		// Only for Tooltipy
		if( Tooltipy::get_plugin_name() != $post_type ){
			return false;
		}

		add_meta_box(
			'tltpy_tooltip_metabox',
			__tooltipy( 'Tooltip settings' ),
			array( $this, 'metabox_render' ) ,
			null,
			'tooltipy_after_title'
		);
	}

	function save_metabox_fields( $post_id ){

		if( !empty($_POST['post_type']) && $_POST['post_type'] != Tooltipy::get_plugin_name() ){
			return false;
		}

		// editpost : to prevent bulk edit problems
		if( !empty($_POST['action']) && $_POST['action'] == 'editpost' ){

			$metabox_fields = $this->get_metabox_fields();
			foreach ( $metabox_fields as $field) {
				$this->save_metabox_field( $post_id, $field['meta_field_id']);
			}
		}
	}

	function save_metabox_field( $post_id, $meta_field_id, $sanitize_function = 'sanitize_text_field' ){
		if( isset($_POST[$meta_field_id]) ){
			$value = call_user_func( $sanitize_function, $_POST[$meta_field_id] );
		}else{
			$value = '';
		}

		// Filter hook before saving meta field
		$value = apply_filters( 'tltpy_tooltip_metabox_field_before_save_' . $meta_field_id, $value);

		update_post_meta( $post_id, $meta_field_id, $value);
	}

	static function get_metabox_fields(){
		$fields = array(
			array(
				'meta_field_id' => 'synonyms',
				'callback'      => array( __CLASS__, 'synonyms_field' )
			),
			array(
				'meta_field_id' => 'case_sensitive',
				'callback'      => array( __CLASS__, 'case_sensitive_field' )
			),
			array(
				'meta_field_id' => 'is_prefix',
				'callback'      => array( __CLASS__, 'prefix_field' )
			),
			array(
				'meta_field_id' => 'youtube_id',
				'callback'      => array( __CLASS__, 'video_field' )
			),
			array(
				'meta_field_id' => 'is_wiki',
				'callback'      => array( __CLASS__, 'is_wiki_field' )
			),
			array(
				'meta_field_id' => 'wiki_term',
				'callback'      => array( __CLASS__, 'wiki_term_field' )
			)
		);
		
		// Filter hook
		$fields = apply_filters( 'tltpy_tooltip_metabox_fields', $fields);
		
		// Add metadata prefix
		foreach( $fields as $key => $field ){
			$fields[$key]['meta_field_id'] = 'tltpy_' . $field['meta_field_id'];
		}

		return $fields;
	}

	function metabox_render(){

		$metabox_fields = $this->get_metabox_fields();

		foreach ($metabox_fields as $field) {
			call_user_func( $field['callback'], $field['meta_field_id'] );
		}
	}

	function synonyms_field( $meta_field_id ){
		?>
		<p>
			<Label><?php _e_tooltipy( 'Synonyms' );?>
				<input type="text" 
					name="<? echo( $meta_field_id ); ?>" 
					value="<?php echo( get_post_meta( get_the_id(), $meta_field_id, true ) ); ?>" 
					placeholder = "<?php _e("Type here the tooltip's synonyms separated with '|'", "tooltipy-lang" ); ?>" 
					style="width:100%;" 
				/>
			</label>
		</p>
		<?php
	}

	function case_sensitive_field( $meta_field_id ){
		$is_checked = get_post_meta( get_the_id(), $meta_field_id ,true) ? 'checked' : '';
		?>
		<p>
			<label><?php _e_tooltipy( 'Make this keyword <b>Case Sensitive</b>' );?>
				<input type="checkbox" 
					name="<? echo( $meta_field_id ); ?>"
					<?php echo ( $is_checked ); ?> 
				/>
			</label>
		</p>
		<?php
	}
	
	function prefix_field( $meta_field_id ){
		$is_checked = get_post_meta( get_the_id(), $meta_field_id, true) ? 'checked' : '';
		?>
		<p>
			<label><?php _e_tooltipy( 'This Keyword is a <b>Prefix</b>' );?>
				<input
					name="<? echo( $meta_field_id ); ?>"
					type="checkbox"
					<?php echo ( $is_checked ); ?>
				/>
			</label>
		</p>
		<?php
	}

	function video_field( $meta_field_id ){
		$video_id = get_post_meta( get_the_id(), $meta_field_id, true );
		?>
		<p>
			<label>
				<b><?php _e_tooltipy( 'Youtube video ID' );?></b><br>
				www.youtube.com/watch?v=
				<input
					name="<? echo( $meta_field_id ); ?>"
					type="text"
					value="<?php echo( $video_id ); ?>"
				/>
				<img src="<?php echo( TOOLTIPY_PLUGIN_URL . 'assets/youtube_icon.png');?>" style="position: relative; top: 5px;"/>
			</label>
		</p>
		<?php
		if( !empty($video_id) ){
			?>
			<p>
				<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo($video_id); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
			</p>
			<?php
		}
	}

	/**
	 * Renders the 'is_wiki' meta field
	 *
	 * @param  mixed $meta_field_id
	 *
	 * @return void
	 */
	public function is_wiki_field( $meta_field_id ){
		$is_checked = get_post_meta( get_the_id(), $meta_field_id, true) ? 'checked' : '';
		?>
		<p>
			<label><?php _e_tooltipy( 'This is a <b>Wikipedia term</b>' );?>
				<input
					name="<? echo( $meta_field_id ); ?>"
					type="checkbox"
					<?php echo ( $is_checked ); ?>
				/>
			</label>
		</p>
		<?php
	}

	/**
	 * Renders the 'wiki_term' meta field
	 *
	 * @param  mixed $meta_field_id
	 *
	 * @return void
	 */
	public function wiki_term_field( $meta_field_id ){
		$wiki_term = get_post_meta( get_the_id(), $meta_field_id, true );

		if( !$wiki_term ){
			$wiki_term = get_the_title();
		}

		?>
		<p>
			<label>
				<b><?php _e_tooltipy( 'Wikipedia term' );?></b><br>
				<input
					name="<? echo( $meta_field_id ); ?>"
					type="text"
					value="<?php echo( $wiki_term ); ?>"
				/>
			</label>
		</p>
		<?php
		$wiki_data = tooltipy_get_post_wiki_data( get_the_id() );

		if( $wiki_data ){
			echo $wiki_data->extract_html;
		}
	}
}