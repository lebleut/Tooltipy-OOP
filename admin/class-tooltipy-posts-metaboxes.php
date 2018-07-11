<?php

class Tooltipy_Posts_Metaboxes{
    public function __construct() {
        add_action( 'do_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 3 );
        
        // Filter metabox fields before save if needed
        $this->filter_metabox_fields();

        add_action('save_post', array( $this, 'save_metabox_fields' ) );
    }

    // Filter metabox fields before save if needed
    public function filter_metabox_fields(){
        // Filter fields here
        add_filter('tltpy_posts_metabox_field_before_save_' . 'tltpy_matched_tooltips', array($this, 'filter_matched_tooltips'), 10, 2 );
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
    function filter_matched_tooltips( $old_value, $post_vars ){
        $content = $post_vars['post_content'];

        $tooltips = Tooltipy::get_tooltips();

        $matched_tooltips = array();
        foreach($tooltips as $tltp){
            preg_match( '/'.$tltp->post_title.'/i', $content, $matches);
            if( !empty($matches) ){
                $tltp_vector = array(
                    'tooltip_id'    => $tltp->ID,
                    'tooltip_title' => $tltp->post_title
                );
                array_push($matched_tooltips, $tltp_vector );
            }
        }

        return $matched_tooltips;
    }

    function save_metabox_fields( $post_id ){
        // Not for Tooltipy post type
        if( !empty($_POST['post_type']) && $_POST['post_type'] == Tooltipy::get_plugin_name() ){
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
        $value = call_user_func( $sanitize_function, $_POST[$meta_field_id] );

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
        $all_post_types = get_post_types();
        foreach($all_post_types as $screen) {
            add_meta_box(
                'tltpy_posts_metabox',
                __('Related tooltips settings','tooltipy-lang'),
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
            <h4><?php _e('Exclude this post from being matched', 'tooltipy-lang'); ?></h4>
            <Label><?php echo(__('Exclude this ','tooltipy-lang') . '<b>' . strtolower($post_type_label) . '</b>' ); ?>
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
        ?>
        <h4><?php _e('Tooltips in this post', 'tooltipy-lang'); ?></h4>
        <?php
        if( empty($matched_tooltips) ){
            ?>
            <p style="color:red;"><?php _e('No tooltips matched yet', 'tooltipy-lang'); ?></p>
            <?php
            return false;
        }
        ?>
        <ul style="padding: 0px 10px;">
            <?php
            foreach ($matched_tooltips as $tltp) {
              ?>
              <li style="color:green;"><?php echo($tltp['tooltip_title']); ?></li>
              <?php  
            }
            ?>
        </ul>
        <?php
    }

    function exclude_tooltips_field($meta_field_id){
        $excluded_tooltips = get_post_meta( get_the_id(), $meta_field_id, true );
        ?>
        <h4><?php _e('Tooltips to exclude', 'tooltipy-lang'); ?></h4>
        <input
            type="text"
            name="<?php echo($meta_field_id); ?>"
            placeholder="tooltip..."
            value="<?php echo( $excluded_tooltips ); ?>"
        >
        <?php
    }
}