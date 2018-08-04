<?php
/**
 * TOOLTIPY_MIGRATOR will allow to migrate from the old procedural version to the OOP version of Tooltipy
 * Github issue : https://github.com/lebleut/Tooltipy-OOP/issues/2
 * 
 * Note : Make sure to instanciate this class once Tooltipy is activated
 */

class TOOLTIPY_MIGRATOR{

    private $options = array();
    private $old_post_type = '';
    private $new_post_type = '';

    function __construct(){

        // Options structure
        // 'options_array' => array( 
        //      'old_option_1' => 'new_option_1',
        //      'old_option_2' => 'new_option_2',
        //      'old_option_3' => 'new_option_3',
        // )
        //

        $this->options = array(
            'bluet_kw_settings' => array(
                'bt_kw_hide_title'      => 'tltpy_' . 'hide_tooltip_title',
                'bt_kw_position'        => 'tltpy_' . 'tooltip_position',
                'bt_kw_animation_type'  => 'tltpy_' . 'tooltip_animation',
                'bt_kw_animation_speed' => 'tltpy_' . 'tooltip_animation_speed',
                'bt_kw_match_all'       => 'tltpy_' . 'match_all_occurrences',

                // TOBE ADDED
                // '' => 'tltpy_' . 'match_all_occurrences',
            ),
            'bluet_kw_advanced' => array(
                'kttg_cover_areas'          => 'tltpy_' . 'cover_classes',
                'kttg_cover_tags'           => 'tltpy_' . 'cover_html_tags',
                'kttg_exclude_areas'        => 'tltpy_' . 'exclude_classes',
                'kttg_exclude_heading_tags' => 'tltpy_' . 'exclude_heading_tags',
                'kttg_custom_events'        => 'tltpy_' . 'custom_events',

                // TOBE ADDED
                // 'kttg_exclude_anchor_tags'   => 'tltpy_' . '',
                // 'bt_kw_adv_style'            => 'tltpy_' . '',
                // ''                           => 'tltpy_' . 'exclude_links',
                // ''                           => 'tltpy_' . 'exclude_common_tags',
            ),
            'bluet_kw_style' => array(
                // TOBE ADDED
                // 'bt_kw_fetch_mode'      => 'tltpy_' . '',
                // 'bt_kw_tooltip_width'   => 'tltpy_' . '',
                // 'bt_kw_desc_font_size'  => 'tltpy_' . '',
                // 'bt_kw_add_css_classes' => 'tltpy_' . '',
                // 'bt_kw_on_background'   => 'tltpy_' . '',
                // 'bt_kw_tt_bg_color'     => 'tltpy_' . '',
                // 'bt_kw_tt_color'        => 'tltpy_' . '',
                // 'bt_kw_desc_bg_color'   => 'tltpy_' . '',
                // 'bt_kw_desc_color'      => 'tltpy_' . '',
            ),
            'bluet_glossary_options' => array(
                'kttg_kws_per_page'             => 'tltpy_' . 'glossary_tooltips_per_page',
                'bluet_kttg_show_glossary_link' => 'tltpy_' . 'add_glossary_link',
                'kttg_link_glossary_label'      => 'tltpy_' . 'glossary_link_label',
                'link_titles'                   => 'tltpy_' . 'glossary_link_titles',
                
                // TOBE ADDED
                // 'kttg_glossary_text'             => 'tltpy_' . '',
                // 'kttg_link_glossary_page_link'   => 'tltpy_' . '',
                // ''                               => 'tltpy_' . 'glossary_label_all',
                // ''                               => 'tltpy_' . 'glossary_label_previous',
                // ''                               => 'tltpy_' . 'glossary_label_next',
                // ''                               => 'tltpy_' . 'glossary_label_select_category',
                // ''                               => 'tltpy_' . 'glossary_label_all_categories',
                // ''                               => 'tltpy_' . 'glossary_show_thumbnails',
            ),
        );
        // Old Tooltipy post type name
        $this->old_post_type = 'my_keywords';

        // New Tooltipy post type name
        $this->new_post_type = Tooltipy::get_plugin_name();

        // Perform the migrations
        add_action( 'admin_init', array( $this, 'migrate_options' ) );
        add_action( 'admin_init', array( $this, 'migrate_post_type' ) );
    }

    /**
     * Migrate old Tooltipy options
     */
    public function migrate_options(){
        foreach( $this->options as $old_option_name => $sub_options) {
            if( $option = get_option( $old_option_name, false ) ){
                foreach( $sub_options as $old_sub_option => $new_option ) {
                    if( array_key_exists( $old_sub_option, $option ) && !empty( $option[$old_sub_option] ) ){

                        $setting = Tooltipy_Settings::get_setting_by_id( $new_option );

                        echo $new_option . ' : <pre>'.print_r( $option[$old_sub_option],true ).'</pre>';

                        $setting_type = $setting[ 'type' ];
                        
                        // echo '[' .$setting_type . ']<br>';
                        
                        $new_val = '';

                        switch ( $setting_type ) {
                            case 'radio':
                                echo 'radio<br>';
                                break;

                            case 'checkbox':
                                echo 'checkbox<br>';
                                $new_val = $option[$old_sub_option] == 'on' ? array( 'yes' ) : array() ;
                                break;

                            case 'select':
                                echo 'select<br>';
                                $new_val = $option[$old_sub_option] ? array( $option[$old_sub_option] ) : array() ;
                                break;

                            case 'multiselect':
                                echo 'multiselect<br>';

                                break;

                            default:
                            echo 'Other<br>';
                                echo $new_option . ' : ' . print_r( $option[$old_sub_option], true ) . '<br>';
                                $new_val = $option[$old_sub_option];
                                //update_option( $new_option, $option[$old_sub_option] );
                                break;
                        }

                        update_option( $new_option, $new_val );
                        echo '----------------------------------------<br>';
                    }
                }
            }
        }
    }

    /**
     * Migrate old Tooltipy post type posts
     */
    public function migrate_post_type(){
        
    }
}