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
		$page_title = __('Tooltipy Settings Page', 'tooltipy');
		$menu_title = __('Settings', 'tooltipy');
		$capability = 'manage_options';
		$slug = 'tooltipy_settings';
		$callback = array( $this, 'plugin_settings_page_content' );
		$post_type = $tooltipy_obj->get_plugin_name();
	
		add_submenu_page( 'edit.php?post_type='.$post_type, $page_title, $menu_title, $capability, $slug, $callback );
    }
    public function plugin_settings_page_content() {?>
    	<div class="wrap">
    		<h2>Tooltipy Settings</h2>
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
						'id' => 'general',
						'title' => 'General options',
						'description' => 'This is the general section in options tab',
					),
					array(
						'id' => 'advanced',
						'title' => 'Advanced options',
						'description' => 'This is the advanced section in options tab',
					),
				)
			),
			array(
				'id' => 'style',
				'sections' => array(
					array(
						'id' => 'general',
						'title' => 'The style',
						'description' => 'This is the style section in options tab',
					),
				)
			),
			array(
				'id' => 'glossary',
				'sections' => array(
					array(
						'id' => 'general',
						'title' => 'Glossary options',
						'description' => 'This is the general section in glossary tab',
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
        $fields = array(
        	array(
				'uid' => 'awesome_text_field',
				'label' => 'Sample Text Field',
				'tab' => 'general',
        		'section' => 'general',
        		'type' => 'text',
        		'placeholder' => 'Some text',
        		'helper' => 'Does this help?',
        		'supplimental' => 'I am underneath!',
                'default' => '',
        	),
        	array(
        		'uid' => 'awesome_password_field',
        		'label' => 'Sample Password Field',
				'tab' => 'style',
        		'type' => 'password',
                'default' => '',
        	),
        	array(
        		'uid' => 'awesome_number_field',
				'label' => 'Sample Number Field',
				'tab' => 'style',
        		'section' => 'advanced',
        		'type' => 'number',
                'default' => '',
        	),
        	array(
        		'uid' => 'awesome_textarea',
        		'label' => 'Sample Text Area',
        		'tab' => 'glossary',
        		'type' => 'textarea',
                'default' => '',
        	),
        	array(
        		'uid' => 'awesome_select',
        		'label' => 'Sample Select Dropdown',
        		'tab' => 'general',
        		'section' => 'advanced',
        		'type' => 'select',
        		'options' => array(
        			'option1' => 'Option 1',
        			'option2' => 'Option 2',
        			'option3' => 'Option 3',
        			'option4' => 'Option 4',
        			'option5' => 'Option 5',
        		),
                'default' => array()
        	),
        	array(
        		'uid' => 'awesome_multiselect',
        		'label' => 'Sample Multi Select',
        		'tab' => 'glossary',
        		'type' => 'multiselect',
        		'options' => array(
        			'option1' => 'Option 1',
        			'option2' => 'Option 2',
        			'option3' => 'Option 3',
        			'option4' => 'Option 4',
        			'option5' => 'Option 5',
        		),
                'default' => array()
        	),
        	array(
        		'uid' => 'awesome_radio',
        		'label' => 'Sample Radio Buttons',
        		'tab' => 'glossary',
        		'type' => 'radio',
        		'options' => array(
        			'option1' => 'Option 1',
        			'option2' => 'Option 2',
        			'option3' => 'Option 3',
        			'option4' => 'Option 4',
        			'option5' => 'Option 5',
        		),
                'default' => array()
        	),
        	array(
        		'uid' => 'awesome_checkboxes',
        		'label' => 'Sample Checkboxes',
				'tab' => 'glossary',
        		'section' => 'advanced',
        		'type' => 'checkbox',
        		'options' => array(
        			'option1' => 'Option 1',
        			'option2' => 'Option 2',
        			'option3' => 'Option 3',
        			'option4' => 'Option 4',
        			'option5' => 'Option 5',
        		),
                'default' => array()
        	)
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
		$default = !empty($arguments['default']) || is_array($arguments['default'])  ? $arguments['default'] : '' ;
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
											'<label for="%1$s_%6$s"><input id="%1$s__id_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
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
		$supplimental = !empty($arguments['supplimental']) ? $arguments['supplimental'] : '';

        if( $helper ){
            printf( '<span class="helper"> %s</span>', $helper );
        }
        if( $supplimental ){
            printf( '<p class="description">%s</p>', $supplimental );
        }
    }
}