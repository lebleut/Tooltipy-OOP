<?php
namespace Tooltipy;

/**
 * Tooltipy\Settings : this class handles the Tooltipy settings page
 */
class Settings {
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
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/settings/wikipedia_settings.php';
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/settings/style_settings.php';
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/settings/glossary_settings.php';
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/settings/scope_settings.php';
	}

    public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = __tooltipy( 'Tooltipy settings' );
		$menu_title = __tooltipy( 'Settings' );
		$capability = 'manage_options';
		$slug = 'tooltipy_settings';
		$callback = array( $this, 'plugin_settings_page_content' );
		$post_type = Tooltipy::get_plugin_name();
	
		add_submenu_page( 'edit.php?post_type='.$post_type, $page_title, $menu_title, $capability, $slug, $callback );
	}
	
    public function plugin_settings_page_content() {?>
    	<div class="wrap">
    		<h2><?php echo __tooltipy( 'Tooltipy settings' ); ?></h2>
			<?php
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ){
                  $this->admin_notice( __tooltipy( 'Your settings have been successfully updated') );
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
    
    public function admin_notice( $msg, $type = 'success', $is_dismissible = true) {
		$dismissible_cls = $is_dismissible ? 'is-dismissible' : '';
		$type_cls = 'notice-' . $type;
		?>
        <div class="notice <?php echo $type_cls ?> <?php echo $dismissible_cls ?>">
            <p><?php echo $msg ?></p>
        </div><?php
	}

	public static function get_tabs(){
		$setting_tabs = array(
			array(
				'id' => 'general',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __tooltipy( 'Tooltips options' ),
						'description' 	=> __tooltipy( 'General tooltips settings' ),
					),
					array(
						'id' 			=> 'advanced',
						'title' 		=> __tooltipy( 'Advanced' ),
						'description' 	=> __tooltipy( 'Advanced options' ),
					),
				)
			),
			array(
				'id' => 'wikipedia',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __tooltipy( 'Wikipedia' ),
						'description' 	=> __tooltipy( 'Wikipedia API settings' ),
					),
				)
			),
			array(
				'id' => 'style',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __( 'Customise the tooltip style :' ),
						'description' 	=> __( 'Make your own style.' ),
					),
					array(
						'id' 			=> 'advanced',
						'title' 		=> __tooltipy( 'Advanced style' ),
						'description' 	=> __tooltipy( 'Advanced style settings' ),
					),
					array(
						'id' 			=> 'standard_mode',
						'name' 			=> 'standard mode',
						'title' 		=> __tooltipy( 'Standard mode' ),
						'description' 	=> __tooltipy( 'Tooltip standard mode settings' ),
					),
					array(
						'id' 			=> 'icon_mode',
						'name' 			=> 'icon mode',
						'title' 		=> __tooltipy( 'Icon mode' ),
						'description' 	=> __tooltipy( 'Tooltip icon mode settings' ),
					),
					array(
						'id' 			=> 'title_mode',
						'name' 			=> 'title mode',
						'title' 		=> __tooltipy( 'Title mode' ),
						'description' 	=> __tooltipy( 'Tooltip title mode settings' ),
					),
					array(
						'id' 			=> 'link_mode',
						'name' 			=> 'link mode',
						'title' 		=> __tooltipy( 'Link mode' ),
						'description' 	=> __tooltipy( 'Tooltip link mode settings' ),
					),
					array(
						'id' 			=> 'footnote_mode',
						'name' 			=> 'Footnote mode',
						'title' 		=> __tooltipy( 'Footnote mode' ),
						'description' 	=> __tooltipy( 'Tooltip footnote mode settings' ),
					),
				)
			),
			array(
				'id' => 'glossary',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __( 'Glossary settings :' ),
						'description' 	=> __( 'Choose settings for your glossary.' ),
					),
					array(
						'id' 			=> 'labels',
						'title' 		=> __tooltipy( 'Glossary page labels' ),
						'description' 	=> __tooltipy( '' ),
					),
				)
			),
			array(
				'id' => 'exclude',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __tooltipy( 'Exclude' ),
						'description' 	=> __tooltipy( 'Sections to exclude' ),
					),
				)
			),
			array(
				'id' => 'log',
				'sections' => array(
					array(
						'id' 			=> 'general',
						'title' 		=> __tooltipy( 'Log' ),
						'description' 	=> __( 'Shows the log Tooltipy sections from the debug.log file' ),
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


	/**
	 * Returns the list of plugin option fields
	 *
	 * @return void
	 */
	public static function get_fields(){
		$fields = array();

		// Fields filter hook
		$fields = apply_filters( 'tltpy_setting_fields', $fields);

		do_action( 'tltpy_setting_fields_assigned', $fields );

		return $fields;
	}

	/**
	 * get_field
	 *
	 * @param  mixed $field_id
	 *
	 * @return array|boolean
	 */
	public static function get_field( $field_id ){
		$field = false;

		foreach( self::get_fields() as $field ){
			if( $field_id == $field['uid'] ){
				return $field;
			}
		}

		return $field;
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
        		'type' 			=> '',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox or even 'custom'

				'label' 		=> __tooltipy( '______' ),
        		'placeholder' 	=> __tooltipy( '______' ),
        		'helper' 		=> __tooltipy( '______' ),		// Text helper beside the field
        		'description' 	=> __tooltipy( '______' ),		// Text description below the field

				'options' 		=> array(
        			'option1' 		=> __tooltipy( '______' ),
        		),
				'default' 		=> array( '' ), 	// String or array
				'callback'		=> array( '' ),		// String or array : the function that will render the custom field
			),
			*/
		);

		$fields = self::get_fields();
		
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
		$tltpy_all_settings = Settings::get_settings();

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
		
		if( false === $value ) {
            $value = $default;
		}

		// If custom field
		if( 'custom' == $arguments['type'] ){
			// Call the callback function to render the custom section field
			if( !empty( $arguments[ 'callback' ] ) && function_exists( $arguments[ 'callback' ] ) ){
				call_user_func( $arguments[ 'callback' ] );
			}

			// Important instruction
			return;
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

			case 'button':
				$is_disabled = isset($arguments['disabled']) && true === $arguments['disabled'] ? 'disabled' : '';

				printf( '<input type="submit" name="%1$s" id="%1$s" value="%2$s" class="button button-secondary" %3$s >', $uid, $arguments['label'], $is_disabled );
				?>
				<script>
				(function ($) {
					$(document).ready(function () {
						$('#<?php echo $uid ?>').on('click', function(ev){
							$button = $('#<?php echo $uid ?>')
							ev.preventDefault()
							
							if( confirm( 'Are you sure you want to <?php echo $arguments['label'] ?> ?' ) ){
								// Wait please
								$button.attr('disabled', true)

								let ajax_action = '<?php echo isset($arguments['uid'])
									? $arguments['uid']
									: '' ?>'

								if( '' == ajax_action.trim() ){
									alert('No Ajax action assigned to this button!')
									return
								}

								$.ajax({
									url: ajaxurl,
									type: "POST",
									data: {
										'action': ajax_action,
									}
								}).done(function(response) {
									response = JSON.parse(response)
									$button.attr('disabled', false)

									<?php if(isset($arguments['js_callback']) && !empty($arguments['js_callback'])): ?>
										if( typeof <?php echo $arguments['js_callback'] ?> === "function" ){
											<?php echo $arguments['js_callback'] ?>(response, $button)
										}else{
											alert('JS callback not defined, Check console for results')
											console.log(response)
										}
									<? else: ?>
										alert('No JS callback, Check console for results')
										console.log(response)
									<? endif; ?>
									
								});
							}

							
						})
					});
				})(jQuery);
				</script>
				<?php
			break;

			default:
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