<?php
/**
 * Tooltipy_Settings : this class handles the Tooltipy settings page
 */
class Tooltipy_Settings {
    public function __construct() {
    	// Hook into the admin menu
		add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
		// Load setting fields
    	add_action( 'init', array( $this, 'load_main_settings' ) );
        // Add Settings and Fields
    	add_action( 'admin_init', array( $this, 'setup_sections' ) );
    	add_action( 'admin_init', array( $this, 'setup_settings' ) );
	}
	
	public function load_main_settings(){
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/settings/general_settings.php';
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/settings/style_settings.php';
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/settings/glossary_settings.php';
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/settings/scope_settings.php';
	}

    public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = __('Tooltipy settings','tooltipy-lang');
		$menu_title = __( 'Settings', 'tooltipy-lang' );
		$capability = 'manage_options';
		$slug = 'tooltipy_settings';
		$callback = array( $this, 'plugin_settings_page_content' );
		$post_type = Tooltipy::get_plugin_name();
	
		add_submenu_page( 'edit.php?post_type='.$post_type, $page_title, $menu_title, $capability, $slug, $callback );
	}
	
    public function plugin_settings_page_content() {?>
    	<div class="wrap">
    		<h2><?php echo __('Tooltipy settings','tooltipy-lang'); ?></h2>
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
										if( array_key_exists( 'name', $section ) && !empty( $section['name'] ) ){
											$section_name = ucfirst($section['name']);
										}else{
											$section_name = ucfirst($section['id']);
										}
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
						
						// to allow add settings or content before settings
						do_action( 'tltpy_tab_before_settings', $section_id );

						do_settings_sections( 'tooltipy_' . $section_id );

						// Too add settings or content after settings
						do_action( 'tltpy_tab_after_settings', $section_id );

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

	public static function get_tabs(){
		$setting_tabs = array(
			array(
				'id' => 'general',
				'sections' => array(
					array(
						'id' 			=> 'general',
												'title' 		=> __('Tooltips options','tooltipy-lang'),
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
					array(
						'id' 			=> 'standard_mode',
						'name' 			=> 'standard mode',
						'title' 		=> __('Standard mode','tooltipy-lang'),
						'description' 	=> __('Tooltip standard mode settings','tooltipy-lang'),
					),
					array(
						'id' 			=> 'icon_mode',
						'name' 			=> 'icon mode',
						'title' 		=> __('Icon mode','tooltipy-lang'),
						'description' 	=> __('Tooltip icon mode settings','tooltipy-lang'),
					),
					array(
						'id' 			=> 'title_mode',
						'name' 			=> 'title mode',
						'title' 		=> __('Title mode','tooltipy-lang'),
						'description' 	=> __('Tooltip title mode settings','tooltipy-lang'),
					),
					array(
						'id' 			=> 'link_mode',
						'name' 			=> 'link mode',
						'title' 		=> __('Link mode','tooltipy-lang'),
						'description' 	=> __('Tooltip link mode settings','tooltipy-lang'),
					),
					array(
						'id' 			=> 'footnote_mode',
						'name' 			=> 'Footnote mode',
						'title' 		=> __('Footnote mode','tooltipy-lang'),
						'description' 	=> __('Tooltip footnote mode settings','tooltipy-lang'),
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
			array(
				'id' => 'log',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __('Log','tooltipy-lang'),
						'description' 	=> __('Shows the log Tooltipy sections from the debug.log file','tooltipy-lang'),
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

	public static function get_settings(){
        $fields = array(
			/*
			// Main fields are added using the tltpy_setting_fields filter hook

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
		);

		// settings fields filter hook
		$fields = apply_filters( 'tltpy_setting_fields', $fields);
		
		foreach( $fields as $key=>$field ){
			$fields[$key]['uid'] = 'tltpy_' . $field['uid'];
		}

		return $fields;
	}

	/**
	 * @return : (array) the first setting array related to the id in parameter
	 * @param : $id (string) the setting identifier
	 */
	public static function get_setting_by_id( $id ){
		$setting = self::filter_settings( 'uid', $id );

		if( is_array( $setting ) && count( $setting ) ){
			reset( $setting );
			return current( $setting );
		}else{
			return $setting;
		}
	}

	/**
	 * @return : (array) list of settings where setting having the $key is equal to $value
	 */
	public static function filter_settings( $key, $value ){
		$tltpy_all_settings = Tooltipy_Settings::get_settings();

		$filtered = array();

		foreach ( $tltpy_all_settings as $setting ) {
			if( $setting[ $key ] == $value ){
				array_push( $filtered, $setting );
			}
		} 
		return $filtered;
	}

    public function setup_settings() {
		$fields = $this->get_settings();

    	foreach( $fields as $field ){

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