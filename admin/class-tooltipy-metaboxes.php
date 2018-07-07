<?php

class Tooltipy_Metaboxes{
    public function __construct() {
    	// Hook after the Tooltipy edit title
        add_action( 'edit_form_after_title', array( $this, 'meta_box_after_title' ) );
        
        add_action( 'do_meta_boxes', array( $this, 'add_tooltipy_meta_boxes' ), 10, 3 );

        add_action( 'do_meta_boxes', array( $this, 'add_other_meta_boxes' ), 10, 3 );
        
        // Filter metabox fields before save if needed
        $this->filter_metabox_fields();

        add_action('save_post', array( $this, 'save_tooltip_metabox_fields' ) );
    }

    // Filter metabox fields before save if needed
    public function filter_metabox_fields(){
        add_filter( 'tltpy_tooltip_metabox_field_before_save_tltpy_tooltip_synonyms', array( $this, 'filter_synonyms_field') );
    }
    public function filter_synonyms_field( $syn_value ){
        // replace ||||||| by only one
        $syn_value = preg_replace('(\|{2,100})','|',$syn_value);
        
        // Eliminate spaces special caracters
        $syn_value = preg_replace('(^\||\|$|[\s]{2,100})','',$syn_value);

        return $syn_value;
    }
    
    public function meta_box_after_title(){
        global $tooltipy_obj;
    
        do_meta_boxes(
            $tooltipy_obj->get_plugin_name(),
            'tooltipy_after_title',
            '' 
        );
    }

    function add_tooltipy_meta_boxes( $post_type, $context, $post ){
        global $tooltipy_obj;

        // Only for Tooltipy
        if( $tooltipy_obj->get_plugin_name() != $post_type ){
            return false;
        }

        add_meta_box(
            'tltpy_tooltip_metabox',
            __('Tooltip settings','tooltipy-lang'),
            array( $this, 'tooltip_metabox_render' ) ,
            null,
            'tooltipy_after_title'
        );
    }

    function save_tooltip_metabox_fields( $post_id ){
        global $tooltipy_obj;

        if( !empty($_POST['post_type']) and $_POST['post_type'] != $tooltipy_obj->get_plugin_name() ){
            return false;
        }

        // editpost : to prevent bulk edit problems
        if( !empty($_POST['action']) && $_POST['action'] == 'editpost' ){

            $tooltip_metabox_fields = $this->get_tooltip_metabox_fields();
            foreach ( $tooltip_metabox_fields as $field) {
                $this->save_tooltip_metabox_field( $post_id, $field['meta_field_id']);
            }
        }
    }

    function add_other_meta_boxes( $post_type, $context, $post ){
        global $tooltipy_obj;

        // For all posts except Tooltipy
        if( $tooltipy_obj->get_plugin_name() == $post_type ){
            return false;
        }

        add_meta_box(
            'tltpy_posts_metabox',
            __('Related tooltips settings','tooltipy-lang'),
            array( $this, 'posts_metabox_render' ) ,
            null,
            'side',
            'high'
        );
    }
    function get_tooltip_metabox_fields(){
        $tooltip_fields = array(
            array(
                'meta_field_id' => 'tltpy_tooltip_synonyms',
                'callback'      => array( $this, 'tooltip_synonyms_field' )
            ),
            array(
                'meta_field_id' => 'tltpy_tooltip_case_sensitive',
                'callback'      => array( $this, 'tooltip_case_field' )
            ),
            array(
                'meta_field_id' => 'tltpy_tooltip_is_prefix',
                'callback'      => array( $this, 'tooltip_prefix_field' )
            ),
            array(
                'meta_field_id' => 'tltpy_tooltip_youtube_id',
                'callback'      => array( $this, 'tooltip_video_field' )
            )
        );
        
        // Filter hook
        $tooltip_fields = apply_filters( 'tltpy_tooltip_metabox_fields', $tooltip_fields);

        return $tooltip_fields;
    }
    function tooltip_metabox_render(){

        $tooltip_metabox_fields = $this->get_tooltip_metabox_fields();

        foreach ($tooltip_metabox_fields as $field) {
            call_user_func( $field['callback'], $field['meta_field_id'] );
        }
    }

    function posts_metabox_render(){
        echo "This is a metabox for posts other than tooltipy...";
    }
    
    function tooltip_synonyms_field( $meta_field_id ){
        ?>
        <p>
            <Label><?php _e('Synonyms','tooltipy-lang');?>
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

    function tooltip_case_field( $meta_field_id ){
        $is_checked = get_post_meta( get_the_id(), $meta_field_id ,true) ? 'checked' : '';
        ?>
        <p>
            <label><?php _e('Make this keyword <b>Case Sensitive</b>','tooltipy-lang');?>
                <input type="checkbox" 
                    name="<? echo( $meta_field_id ); ?>"
                    <?php echo ( $is_checked ); ?> 
                />
            </label>
        </p>
        <?php
    }
    function tooltip_prefix_field( $meta_field_id ){
        $is_checked = get_post_meta( get_the_id(), $meta_field_id, true) ? 'checked' : '';
        ?>
        <p>
            <label><?php _e('This Keyword is a <b>Prefix</b>','tooltipy-lang');?>
                <input
                    name="<? echo( $meta_field_id ); ?>"
                    type="checkbox"
                    <?php echo ( $is_checked ); ?>
                />
            </label>
        </p>
        <?php
    }

    function tooltip_video_field( $meta_field_id ){
		?>
        <p>
            <label>
                <b><?php _e('Youtube video ID','tooltipy-lang');?></b><br>
                WWW.Youtube.com/watch?v=
                <input
                    name="<? echo( $meta_field_id ); ?>"
                    type="text"
                    value="<?php echo( get_post_meta( get_the_id(), $meta_field_id, true ) ); ?>"
                />
                <img src="<?php echo( TOOLTIPY_PLUGIN_URL . 'assets/youtube_icon.png');?>" style="position: relative; top: 5px;"/>
            </label>
        </p>
        <?php
    }

    function save_tooltip_metabox_field( $post_id, $meta_field_id, $sanitize_function = 'sanitize_text_field' ){
        $value = call_user_func( $sanitize_function, $_POST[$meta_field_id] );

        // Filter hook before saving meta field
        $value = apply_filters( 'tltpy_tooltip_metabox_field_before_save_' . $meta_field_id, $value);

        update_post_meta( $post_id, $meta_field_id, $value);
    }
}