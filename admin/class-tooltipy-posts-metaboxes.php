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
    }

    function save_metabox_fields( $post_id ){
        global $tooltipy_obj;

        // Not for Tooltipy post type
        if( !empty($_POST['post_type']) && $_POST['post_type'] == $tooltipy_obj->get_plugin_name() ){
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
        $value = apply_filters( 'tltpy_posts_metabox_field_before_save_' . $meta_field_id, $value);

        update_post_meta( $post_id, $meta_field_id, $value);
    }

    function add_meta_boxes( $post_type, $context, $post ){
        global $tooltipy_obj;

        // For all posts except Tooltipy
        if( $tooltipy_obj->get_plugin_name() == $post_type ){
            return false;
        }

        add_meta_box(
            'tltpy_posts_metabox',
            __('Related tooltips settings','tooltipy-lang'),
            array( $this, 'metabox_render' ) ,
            null,
            'side',
            'high'
        );
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
            <h3><?php _e('Exclude this post from being matched', 'tooltipy-lang'); ?></h3>
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
        ?>
        <p style="color:green;">TODO : here the matched tooltips in this post should show up.</p>
        <?php
    }

    function exclude_tooltips_field($meta_field_id){
        $excluded_tooltips = get_post_meta( get_the_id(), $meta_field_id, true );
        ?>
        <h3><?php _e('Tooltips to exclude', 'tooltipy-lang'); ?></h3>
        <input
            type="text"
            name="<?php echo($meta_field_id); ?>"
            placeholder="tooltip..."
            value="<?php echo( $excluded_tooltips ); ?>"
        >
        <?php
    }
}