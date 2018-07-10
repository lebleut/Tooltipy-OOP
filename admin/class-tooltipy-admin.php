<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since      4.0.0
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/admin
 * @author     Jamel Eddine Zarga <jamel.zarga@gmail.com>
 */
class Tooltipy_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_filter( 'manage_' . Tooltipy::get_plugin_name() . '_posts_columns', array($this, 'manage_columns') );
		add_filter( 'manage_' . Tooltipy::get_plugin_name() . '_posts_custom_column', array($this, 'manage_column_content'), 10, 2 );

		// Make sortable prefix & case sensitive
		add_filter( 'manage_edit-' . Tooltipy::get_plugin_name() . '_sortable_columns', array($this, 'sortable_columns') );
		add_action( 'pre_get_posts', array($this, 'tooltips_orderby') );

		// Settings
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/class-tooltipy-settings.php';
		new Tooltipy_Settings();

		// Meta boxe
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/class-tooltipy-tooltip-metaboxes.php';
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/class-tooltipy-posts-metaboxes.php';
		new Tooltipy_Tooltip_Metaboxes();
		new Tooltipy_Posts_Metaboxes();

		// Script needed for edit page (quick edit& bulk edit ...)
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_edits_script' ) );

		// Quick edit
		add_filter('quick_edit_custom_box', array( $this, 'quick_edit_add' ), 10, 2 );
		add_action( 'save_post', array( $this, 'quick_edit_save') );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_quick_edit_population' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tooltipy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tooltipy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tooltipy-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tooltipy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tooltipy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tooltipy-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function manage_columns( $columns ){

		$columns = array(
			'cb' 						=> $columns['cb'],
			'title' 					=> __( 'Title' ),
			'tltpy_synonyms'			=> __( 'Synonyms', 'tooltipy-lang' ),
			'tltpy_case_sensitive'	=> __( 'Case sensitive', 'tooltipy-lang' ),
			'tltpy_is_prefix'			=> __( 'Prefix', 'tooltipy-lang' ),
			'tltpy_youtube_id'			=> __( 'Youtube ID', 'tooltipy-lang' ),
			'image' 					=> __( 'Image' ),
			'author' 					=> __( 'Author' ),
			'date' 						=> __( 'Date' ),
		  );
		return $columns;
	}
	public function manage_column_content( $column, $post_id ){

		switch ($column) {
			case 'tltpy_synonyms':
				$this->column_synonyms_content( $post_id );
				break;

			case 'tltpy_case_sensitive':
				$this->column_case_sensitive_content( $post_id );
				break;

			case 'tltpy_is_prefix':
				$this->column_prefix_content( $post_id );
				break;

			case 'tltpy_youtube_id':
				$this->column_youtube_content( $post_id );
				break;
			
			case 'image':
				echo get_the_post_thumbnail( $post_id, array(80, 80) );
				break;
			
			default:
				break;
		}
	}

	function column_synonyms_content($post_id){
		$synonyms = get_post_meta($post_id, 'tltpy_synonyms', true );
		echo "<div class='data' style='display:none;'>" . $synonyms . "</div>";

		if( $synonyms ){
			$synonyms_arr = explode( '|', $synonyms );
			$synonyms_arr = array_map( 'trim', $synonyms_arr );
			$syn_style = 'style="background: grey;color: white;padding: 4px;margin-bottom: 4px; display: inline-block;"';
			echo "<span $syn_style>".implode( "</span>&nbsp;<span $syn_style>", $synonyms_arr )."</span>";
		} 
	}

	function column_case_sensitive_content($post_id){
		$case_sensitive = get_post_meta($post_id, 'tltpy_case_sensitive', true );
		echo "<div class='data' style='display:none;'>" . $case_sensitive . "</div>";

		if( $case_sensitive ){
			$style = 'style="color:white; background: #2ECC71; padding: 4px;"';
			?>
			<span <?php echo $style; ?> >
				<?php _e('is case sensitive', 'tooltipy-lang' ); ?>
			</span>
			<?php
		}else{
			$style = 'style="color:white; background: #D91E18; padding: 4px;"';
			?>
			<span <?php echo $style; ?> >
				<?php _e('NOT case sensitive', 'tooltipy-lang' ); ?>
			</span>
			<?php
		}
	}

	function column_prefix_content($post_id){
		$is_prefix = get_post_meta($post_id, 'tltpy_is_prefix', true );
		echo "<div class='data' style='display:none;'>" . $is_prefix . "</div>";

		if( $is_prefix ){
			$style = 'style="color:white; background: #2ECC71; padding: 4px;"';
			?>
			<span <?php echo $style; ?> >
				<?php _e('is a prefix', 'tooltipy-lang' ); ?>
			</span>
			<?php
		}else{
			$style = 'style="color:white; background: #D91E18; padding: 4px;"';
			?>
			<span <?php echo $style; ?> >
				<?php _e('NOT prefix', 'tooltipy-lang' ); ?>
			</span>
			<?php
		}
	}

	function column_youtube_content( $post_id ){
		$youtube_id = get_post_meta($post_id, 'tltpy_youtube_id', true );
		echo "<div class='data' style='display:none;'>" . $youtube_id . "</div>";

		if( !empty($youtube_id) ){
			$link = 'https://www.youtube.com/watch?v='.$youtube_id;
			$youtube_icon_src = TOOLTIPY_PLUGIN_URL . 'assets/youtube_icon.png';
			?>
			<a href="<?php echo($link); ?>" target="_blank"><img src="<?php echo($youtube_icon_src);?>"> <?php echo($youtube_id); ?></a>
			<?php
		}
	}

	function sortable_columns( $columns ){
		$columns['tltpy_is_prefix'] = 'is_prefix';
		$columns['tltpy_case_sensitive'] = 'case_sensitive';
  		return $columns;
	}

	function tooltips_orderby( $query ){
		if( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}
		
		if ( 'is_prefix' === $query->get( 'orderby') ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'tltpy_is_prefix' );
			$query->set( 'meta_type', 'char' );
		}elseif ( 'case_sensitive' === $query->get( 'orderby') ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'tltpy_case_sensitive' );
			$query->set( 'meta_type', 'char' );
		}
	}

	function quick_edit_add( $column_name, $post_type ){
		// Only for Tooltipy post type
		if( Tooltipy::get_plugin_name() != $post_type ){
			return false;
		}

		switch ( $column_name ) {
			case 'tltpy_synonyms':
				// You can also print Nonce here, do not do it ouside the switch() because it will be printed many times
				wp_nonce_field( 'tltpy_quick_edit_nonce', 'tooltipy_nonce' );

				// for the FIRST column only, it opens <fieldset> element, all our fields will be there
				echo '<fieldset class="inline-edit-col-right">
						<div class="inline-edit-col">
							<div class="inline-edit-group wp-clearfix">';
				?>
							<label class="alignleft">
								<span class="title"><?php _e( 'Synonyms', 'tooltipy-lang' ); ?></span>
								<span class="input-text-wrap">
									<input type="text" name="tltpy_synonyms" value="">
								</span>
							</label>
				<?php
				break;
			case 'tltpy_case_sensitive':
				?>
						<label class="alignleft">
							<span class="title"><?php _e( 'Case sensitive', 'tooltipy-lang' ); ?></span>
							<input type="checkbox" name="tltpy_case_sensitive" value="">
						</label>
				<?php
				break;

			case 'tltpy_is_prefix':
				?>
							<label class="alignleft">
								<span class="title"><?php _e( 'Prefix', 'tooltipy-lang' ); ?></span>
								<input type="checkbox" name="tltpy_is_prefix" value="">
							</label>
				<?php
						// for the LAST column only - closing the fieldset element
						echo('</div></div></fieldset>');
				break;

			default:
				break;
		}
	}
 
	function quick_edit_save( $post_id ){
	
		// check user capabilities
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	
		// check nonce
		if ( !wp_verify_nonce( $_POST['tooltipy_nonce'], 'tltpy_quick_edit_nonce' ) ) {
			return;
		}
	
		// update the price
		if ( isset( $_POST['tltpy_synonyms'] ) ) {
			$synonyms = $_POST['tltpy_synonyms'];
			$synonyms = apply_filters( 'tltpy_tooltip_metabox_field_before_save_' . 'tltpy_synonyms', $synonyms, $_POST);
			
			update_post_meta( $post_id, 'tltpy_synonyms', $synonyms );
		}
	
		// update checkbox
		$case_sensitive = isset( $_POST['tltpy_case_sensitive'] ) ? 'on' : '';
		$case_sensitive = apply_filters( 'tltpy_tooltip_metabox_field_before_save_' . 'tltpy_case_sensitive', $case_sensitive, $_POST);

		update_post_meta( $post_id, 'tltpy_case_sensitive', $case_sensitive );

		$is_prefix = isset( $_POST['tltpy_is_prefix'] ) ? 'on' : '';
		$is_prefix = apply_filters( 'tltpy_tooltip_metabox_field_before_save_' . 'tltpy_is_prefix', $is_prefix, $_POST);

		update_post_meta( $post_id, 'tltpy_is_prefix', $is_prefix );
	}

	function enqueue_edits_script( $page_hook ){

		// do nothing if we are not on the target pages
		if ( 'edit.php' != $page_hook ) {
			return;
		}
	
		wp_enqueue_script( 'tltpy_edits', plugin_dir_url( __FILE__ ) . 'js/edits.js', array( 'jquery' ) );
	}
}
