<?php
/**
 * Tooltipy_Settings : this class handles the Tooltipy settings page
 */
class Tooltipy_Settings {
    public function __construct() {
    	// Hook into the admin menu
    	add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
        // Add Settings and Fields
    	add_action( 'admin_init', array( $this, 'setup_sections' ) );
    	add_action( 'admin_init', array( $this, 'setup_fields' ) );
    }
    public function create_plugin_settings_page() {
		global $tooltipy_obj;

		// Add the menu item and page
		$page_title = __('Tooltips settings :','tooltipy-lang');
		$menu_title = __( 'Settings', 'tooltipy-lang' );
		$capability = 'manage_options';
		$slug = 'tooltipy_settings';
		$callback = array( $this, 'plugin_settings_page_content' );
		$post_type = $tooltipy_obj->get_plugin_name();
	
		add_submenu_page( 'edit.php?post_type='.$post_type, $page_title, $menu_title, $capability, $slug, $callback );
    }
    public function plugin_settings_page_content() {?>
    	<div class="wrap">
    		<h2><?php echo __('Tooltips settings :','tooltipy-lang'); ?></h2>
			<?php
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ){
                  $this->admin_notice();
			}

			$tabs = $this->get_tabs();
			// tabs buttons here
			?>
			<h2 class="nav-tab-wrapper">
				<?php
					$current_tab_id = !empty($_GET['tab']) ? $_GET['tab'] : 'general';
					$current_section_id = !empty($_GET['section']) ? $_GET['section'] : 'general';

					$section_id = $current_tab_id . '__' . $current_section_id;

					foreach ($tabs as $tab) {
						
						$tab_link = esc_url(
							add_query_arg(
								array(
									'post_type' => 'tooltipy',
									'page' => 'tooltipy_settings',
									'tab' => $tab['id'],
								),
								admin_url( 'edit.php') 
							)
						);

						$tab_name = ucfirst($tab['id']);
						$is_current_tab = $current_tab_id == $tab['id'] ? 'nav-tab-active' : '';
						?>
							<a class="nav-tab <?php echo $is_current_tab; ?>" href="<?php echo $tab_link; ?>" ><?php echo $tab_name; ?></a>
						<?php
					}
				?>
			</h2>
			<?php
				// tabs buttons here
				foreach ($tabs as $tab) {
					if( $tab['id'] == $current_tab_id ){
						if( !empty($tab['sections']) && count($tab['sections']) > 1 ){
							?>
							<div>
								<ul class="subsubsub">
									<?php
									foreach ($tab['sections'] as $key => $section) {
										$section_link = esc_url(
											add_query_arg(
												array(
													'post_type' => 'tooltipy',
													'page' => 'tooltipy_settings',
													'tab' => $tab['id'],
													'section' => $section['id'],
												),
												admin_url( 'edit.php') 
											)
										);
										$section_name = ucfirst($section['id']);
										$is_current_section = $current_section_id == $section['id'] ? 'current' : '';
										$sections_separator = ($key + 1 < count($tab['sections']) ) ? " | " : "";
										?>
										<li><a class="<?php echo $is_current_section; ?>" href="<?php echo $section_link; ?>"><?php echo $section_name; ?></a><?php echo $sections_separator; ?></li>
										<?php
									}
									?>
								</ul>
							</div>
							<hr style="clear: both;">
							<?php
						}
					}
				}
			?>
			<div id="tab_container" style="clear: both;">
				<form method="POST" action="options.php">
					<?php
						// Fields
						settings_fields( 'tooltipy_' . $section_id );
						do_settings_sections( 'tooltipy_' . $section_id );
						submit_button();
					?>
				</form>
			</div>
    	</div> <?php
    }
    
    public function admin_notice() { ?>
        <div class="notice notice-success is-dismissible">
            <p>Your settings have been updated!</p>
        </div><?php
	}

	public function get_tabs(){
		$setting_tabs = array(
			array(
				'id' => 'general',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __('Tooltips settings :','tooltipy-lang'),
						'description' 	=> __('General tooltips settings','tooltipy-lang'),
					),
					array(
						'id' 			=> 'advanced',
						'title' 		=> __('Advanced','tooltipy-lang'),
						'description' 	=> __('Advanced options','tooltipy-lang'),
					),
				)
			),
			array(
				'id' => 'style',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __('Customise the tooltip style :','tooltipy-lang'),
						'description' 	=> __('Make your own style.','tooltipy-lang'),
					),
					array(
						'id' 			=> 'advanced',
						'title' 		=> __('Advanced style','tooltipy-lang'),
						'description' 	=> __('Advanced style settings','tooltipy-lang'),
					),
				)
			),
			array(
				'id' => 'glossary',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __('Glossary settings :','tooltipy-lang'),
						'description' 	=> __('Choose settings for your glossary.','tooltipy-lang'),
					),
					array(
						'id' 			=> 'labels',
						'title' 		=> __('Glossary page labels','tooltipy-lang'),
						'description' 	=> __('','tooltipy-lang'),
					),
					array(
						'id' 			=> 'page',
						'title' 		=> __('Glossary link page','tooltipy-lang'),
						'description' 	=> __('','tooltipy-lang'),
					),
				)
			),
			array(
				'id' => 'cover',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __('Cover','tooltipy-lang'),
						'description' 	=> __('Sections to cover','tooltipy-lang'),
					),
					array(
						'id' 			=> 'exclude',
						'title' 		=> __('Exclude','tooltipy-lang'),
						'description' 	=> __('Sections to exclude','tooltipy-lang'),
					),
				)
			),
		);

		// settings tabs filter hook
		$setting_tabs = apply_filters( 'tltpy_setting_tabs', $setting_tabs);

		return $setting_tabs;
	}
    public function setup_sections() {
		$tabs = $this->get_tabs();
		
		foreach ($tabs as $tab) {
			foreach ($tab['sections'] as $section) {
				$section_id = $tab['id'] . '__' . $section['id'];
				add_settings_section(
					$section_id,
					$section['title'],
					array(
						$this,
						'section_header_callback'
					),
					'tooltipy_' . $section_id
				);
			}
		}
    }
    public function section_header_callback( $arguments ) {
		$tabs = $this->get_tabs();
		foreach ($tabs as $tab) {
			foreach ($tab['sections'] as $section) {
				$section_id = $tab['id'] . '__' . $section['id'];
				if( $arguments['id'] == $section_id ){
					?>
					<i><?php echo $section['description']; ?></i>
					<?php
				}
			}
		}
    }
    public function setup_fields() {

		// for got_from_post_types field
		$got_from_post_types_arr = array();
		foreach(get_post_types() as $psttp){
			$got_from_post_types_arr[$psttp] = $psttp;
		}
		// For animation field
		$animations = array(
			"none",
			"bounce", "bounceIn", "bounceInLeft", "bounceInRight", "bounceInDown", "bounceInUp",
			"fadeIn", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig",
			"flash",
			"flip", "flipInX", "flipInY",
			"lightSpeedIn",
			"pulse",				
			"rollIn",
			"rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight",
			"slideInDown", "slideInLeft", "slideInRight", "slideInUp",
			"swing", "shake", "tada",
			"wobble",
			"zoomIn", "zoomInDown", "zoomInLeft", "zoomInRight", "zoomInUp"
		);

        $fields = array(
			/*
			// Here is How to add a new Tooltipy setting field
        	array(
				'tab' 			=> '',
				'section' 		=> '',
				
				'uid' 			=> '',
        		'type' 			=> '',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

				'label' 		=> __( '______', 'tooltipy-lang' ),
        		'placeholder' 	=> __( '______', 'tooltipy-lang' ),
        		'helper' 		=> __( '______', 'tooltipy-lang' ),		// Text helper beside the field
        		'description' 	=> __( '______', 'tooltipy-lang' ),		// Text description below the field

				'options' 		=> array(
        			'option1' 		=> __( '______', 'tooltipy-lang' ),
        		),
                'default' 		=> array( '' ), 	// String or array
			),
			*/

			// General tab
			array(
				'tab' 			=> 'general',
				'section' 		=> 'general',
				
				'uid' 			=> 'match_all_occurrences',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Match all occurrences', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'All', 'tooltipy-lang' ),
        		),
			),
			array(
				'tab' 			=> 'general',
				'section' 		=> 'general',
				
				'uid' 			=> 'hide_tooltip_title',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Hide tooltip title', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'Hide', 'tooltipy-lang' ),
        		),
			),
			array(
				'tab' 			=> 'general',
				'section' 		=> 'general',
				
				'uid' 			=> 'tooltip_position',
        		'type' 			=> 'select',

				'label' 		=> __( 'Tooltip position', 'tooltipy-lang' ),

				'options' 		=> array(
        			'top' 		=> __( 'Top', 'tooltipy-lang' ),
        			'bottom' 	=> __( 'Bottom', 'tooltipy-lang' ),
        			'right' 	=> __( 'Right', 'tooltipy-lang' ),
        			'left' 		=> __( 'Left', 'tooltipy-lang' ),
        		),
                'default' 		=> array( 'bottom' ),
			),
			array(
				'tab' 			=> 'general',
				'section' 		=> 'general',
				
				'uid' 			=> 'tooltip_animation',
        		'type' 			=> 'select',

				'label' 		=> __( 'Animation', 'tooltipy-lang' ),

				'options' 		=> $animations,
			),
			array(
				'tab' 			=> 'general',
				'section' 		=> 'general',
				
				'uid' 			=> 'tooltip_animation_speed',
        		'type' 			=> 'select',

				'label' 		=> __( 'Animation speed', 'tooltipy-lang' ),

				'options' 		=> array(
					'fast'			=> __( 'Fast', 'tooltipy-lang' ),
					'normal'		=> __( 'Normal', 'tooltipy-lang' ),
					'slow'			=> __( 'Slow', 'tooltipy-lang' ),
				),
			),
			array(
				'tab' 			=> 'general',
				'section' 		=> 'advanced',
				
				'uid' 			=> 'got_from_post_types',
        		'type' 			=> 'multiselect',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

				'label' 		=> __( 'Get tooltips from', 'tooltipy-lang' ),
        		'description' 	=> __( 'Select post types from which you want to get tooltips', 'tooltipy-lang' ),		// Text description below the field

				'options' 		=> $got_from_post_types_arr,
			),
			array(
				'tab' 			=> 'general',
				'section' 		=> 'advanced',
				
				'uid' 			=> 'load_all_keywords',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Load all keywords', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'Use only if needed to load all keywords per page', 'tooltipy-lang' ),
        		),
			),
			array(
				'tab' 			=> 'general',
				'section' 		=> 'advanced',
				
				'uid' 			=> 'custom_events',
        		'type' 			=> 'text',

				'label' 		=> __( 'Events to fetch', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'Events names saparated with (,)', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'general',
				'section' 		=> 'advanced',
				
				'uid' 			=> 'prevent_plugins_filters',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Prevent other plugins filters', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'Prevent any 3rd party plugin to filter or change the keywords content', 'tooltipy-lang' ),
        		),
			),

			// Style tab
        	array(
				'tab' 			=> 'style',
				'section' 		=> 'general',
				
				'uid' 			=> 'fetch_mode',
				'type' 			=> 'radio',
				
				'label' 		=> __( 'Fetch mode', 'tooltipy-lang' ),
				'options' 		=> array(
					'highlight' 	=> 'Highlight Mode',
					'icon' 			=> 'Icon Mode',
        		),
                'default' 		=> array('highlight'),
			),
        	array(
				'tab' 			=> 'style',
				'section' 		=> 'general',
				
				'uid' 			=> 'tooltip_width',
				'type' 			=> 'number',	
				'helper' 		=> 'px',
				'label' 		=> __( 'Tooltip width', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'style',
				'section' 		=> 'general',
				
				'uid' 			=> 'description_font_size',
        		'type' 			=> 'number',

				'label' 		=> __( 'Description tooltip Font size', 'tooltipy-lang' ),
        		'helper' 		=> __( 'px', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'style',
				'section' 		=> 'general',
				
				'uid' 			=> 'image_alt',
        		'type' 			=> 'checkbox',

				'label' 		=> __('Activate tooltips for images ?','tooltipy-lang'),

				'options' 		=> array(
        			'yes' 		=> __( 'alt property of the images will be displayed as a tooltip', 'tooltipy-lang' ),
        		),
			),
			array(
				'tab' 			=> 'style',
				'section' 		=> 'advanced',
				
				'uid' 			=> 'keywords_css_classes',
        		'type' 			=> 'text',

				'label' 		=> __( 'Custom CSS classes for inline keywords', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'Separated with spaces', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'style',
				'section' 		=> 'advanced',
				
				'uid' 			=> 'tooltip_css_classes',
        		'type' 			=> 'text',

				'label' 		=> __( 'Custom CSS classes for tooltips', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'Separated with spaces', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'style',
				'section' 		=> 'advanced',
				
				'uid' 			=> 'custom_style_sheet',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Custom style', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'Apply custom style sheet', 'tooltipy-lang' ),
        		),
                'default' 		=> array( '' ),

			),
			array(
				'tab' 			=> 'style',
				'section' 		=> 'advanced',
				
				'uid' 			=> 'custom_style_sheet_url',
        		'type' 			=> 'text',

				'label' 		=> __( 'Custom style sheet URL', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'CSS URL here', 'tooltipy-lang' ),
			),

			// Glossary tab
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'general',
				
				'uid' 			=> 'tooltips_per_page',
        		'type' 			=> 'number',

				'label' 		=> __( 'Tooltips per page', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'ALL', 'tooltipy-lang' ),
        		'helper' 		=> __( 'Keywords Per Page (leave blank for unlimited keywords per page)', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'labels',
				
				'uid' 			=> 'glossary_label_all',
        		'type' 			=> 'text',

				'label' 		=> __( 'ALL label', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'ALL', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'labels',
				
				'uid' 			=> 'glossary_label_previous',
        		'type' 			=> 'text',

				'label' 		=> __( 'Previous label', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'Previous', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'labels',
				
				'uid' 			=> 'glossary_label_next',
        		'type' 			=> 'text',

				'label' 		=> __( 'Next label', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'Next', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'labels',
				
				'uid' 			=> 'glossary_label_select_category',
        		'type' 			=> 'text',

				'label' 		=> __( 'Select a category label', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'Select a category', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'labels',
				
				'uid' 			=> 'glossary_label_all_categories',
        		'type' 			=> 'text',

				'label' 		=> __( 'All categories label', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'All categories', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'page',
				
				'uid' 			=> 'add_glossary_link',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Add glossary link page in the tooltips footer', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'Add glossary link', 'tooltipy-lang' ),
        		),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'page',
				
				'uid' 			=> 'glossary_link',
        		'type' 			=> 'text',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

				'label' 		=> __( 'Glossary page link', 'tooltipy-lang' ),
        		'placeholder' 	=> 'http://...',
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'page',
				
				'uid' 			=> 'glossary_link_label',
        		'type' 			=> 'text',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

				'label' 		=> __( 'Glossary link label', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'View glossary', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'general',
				
				'uid' 			=> 'glossary_show_thumbnails',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Glossary thumbnails', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'Show thumbnails on the glossary page', 'tooltipy-lang' ),
        		),
			),
			array(
				'tab' 			=> 'glossary',
				'section' 		=> 'general',
				
				'uid' 			=> 'glossary_link_titles',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Titles', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'Add links to titles', 'tooltipy-lang' ),
        		),
			),
			// Scope
			array(
				'tab' 			=> 'cover',
				'section' 		=> 'general',
				
				'uid' 			=> 'cover_classes',
        		'type' 			=> 'text',

				'label' 		=> __( 'Cover CSS classes', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'Class ...', 'tooltipy-lang' ),
        		'helper' 		=> __( 'Choose CSS classes to cover with tooltips', 'tooltipy-lang' ),
        		'description' 	=> __( 'NB : Please avoid overlapped classes !<br>If you leave Classes AND Tags blank the whole page will be affected', 'tooltipy-lang' ),

			),
			array(
				'tab' 			=> 'cover',
				'section' 		=> 'general',
				
				'uid' 			=> 'cover_html_tags',
        		'type' 			=> 'text',

				'label' 		=> __( 'Cover HTML TAGS', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'HTML tag ...', 'tooltipy-lang' ),
        		'helper' 		=> __( 'Choose HTML TAGS (like h1, h2, strong, p, ... ) to cover with tooltips', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'cover',
				'section' 		=> 'exclude',
				
				'uid' 			=> 'exclude_classes',
        		'type' 			=> 'text',

				'label' 		=> __( 'Exclude CSS classes', 'tooltipy-lang' ),
        		'placeholder' 	=> __( 'Class ...', 'tooltipy-lang' ),
        		'helper' 		=> __( 'Choose CSS classes to exclude', 'tooltipy-lang' ),
			),
			array(
				'tab' 			=> 'cover',
				'section' 		=> 'exclude',
				
				'uid' 			=> 'exclude_links',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Exclude links ?', 'tooltipy-lang' ),

				'options' 		=> array(
        			'yes' 		=> __( 'Yes', 'tooltipy-lang' ),
        		),
			),
			array(
				'tab' 			=> 'cover',
				'section' 		=> 'exclude',
				
				'uid' 			=> 'exclude_heading_tags',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Exclude Headings ?', 'tooltipy-lang' ),

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
				'tab' 			=> 'cover',
				'section' 		=> 'exclude',
				
				'uid' 			=> 'exclude_common_tags',
        		'type' 			=> 'checkbox',

				'label' 		=> __( 'Exclude Common Tags ?', 'tooltipy-lang' ),

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

		// settings fields filter hook
		$fields = apply_filters( 'tltpy_setting_fields', $fields);

    	foreach( $fields as $field ){
			$field['uid'] = 'tltpy_' . $field['uid'];

			$tab = !empty($field['tab']) ? $field['tab'] : 'general';
			$section_id = !empty($field['section']) ? $tab .'__'. $field['section'] : $tab .'__general';

			add_settings_field(
				$field['uid'],
				$field['label'],
				array( $this, 'field_callback' ),
				'tooltipy_' . $section_id,
				$section_id,
				$field
			);
            register_setting( 'tooltipy_' . $section_id, $field['uid'] );
    	}
    }
    public function field_callback( $arguments ) {
        $value = get_option( $arguments['uid'], false );
		$uid = !empty($arguments['uid']) ? $arguments['uid'] : '' ;
		$default = !empty($arguments['default']) || ( array_key_exists('default', $arguments) && is_array($arguments['default']) )  ? $arguments['default'] : '' ;
		$type = !empty($arguments['type']) ? $arguments['type'] : '' ;
		$placeholder = !empty($arguments['placeholder']) ? $arguments['placeholder'] : '' ;
		
		if( !$value ) {
            $value = $default;
		}

        switch( $arguments['type'] ){
            case 'text':
            case 'password':
            case 'number':
                printf(
					'<input name="%1$s" id="%1$s__id" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$uid,
					$type,
					$placeholder,
					$value
				);
                break;
            case 'textarea':
				printf(
					'<textarea name="%1$s" id="%1$s__id" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
					$uid,
					$placeholder,
					$value
				);
                break;
            case 'select':
            case 'multiselect':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $attributes = '';
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
						$is_selected = !empty($value) ? selected( $value[ array_search( $key, $value, true ) ], $key, false ) : '';

						$options_markup .= sprintf(
												'<option value="%s" %s>%s</option>',
												$key,
												$is_selected,
												$label
											);
                    }
                    if( $type === 'multiselect' ){
                        $attributes = ' multiple="multiple" ';
                    }
                    printf(
						'<select name="%1$s[]" id="%1$s__id" %2$s>%3$s</select>',
						$uid,
						$attributes,
						$options_markup
					);
                }
                break;
            case 'radio':
            case 'checkbox':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $options_markup = '';
                    $iterator = 0;
                    foreach( $arguments['options'] as $key => $label ){
						$iterator++;
						$is_checked = !empty($value) ? checked( $value[ array_search( $key, $value, true ) ], $key, false ) : '';

                        $options_markup .= sprintf(
											'<label><input id="%1$s__id_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
											$uid,
											$type,
											$key,
											$is_checked,
											$label,
											$iterator
										);
                    }
                    printf( '<fieldset>%s</fieldset>', $options_markup );
                }
                break;
		}
		
		$helper = !empty($arguments['helper']) ? $arguments['helper'] : '';
		$field_description = !empty($arguments['description']) ? $arguments['description'] : '';

        if( $helper ){
            printf( '<span class="helper"> %s</span>', $helper );
        }
        if( $field_description ){
            printf( '<p class="description">%s</p>', $field_description );
        }
    }
}