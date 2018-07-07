<?php

class Tooltipy_Metaboxes{
    public function __construct() {
    	// Hook after the Tooltipy edit title
        add_action( 'edit_form_after_title', array( $this, 'meta_box_after_title' ) );
        
        add_action( 'do_meta_boxes', array( $this, 'add_tooltipy_meta_boxes' ), 10, 3 );

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

    function tooltip_metabox_render(){

        $tooltip_metabox_fields = array(
            array( $this, 'tooltip_synonyms_field' ),
            array( $this, 'tooltip_case_field' ),
            array( $this, 'tooltip_prefix_field' ), 
            array( $this, 'tooltip_video_field' )
        );
        
        // Filter hook
        $tooltip_metabox_fields = apply_filters( 'tltpy_tooltip_metabox_fields', $tooltip_metabox_fields);

        foreach ($tooltip_metabox_fields as $field_callback) {
            call_user_func( $field_callback );
        }
    }
    
    function tooltip_synonyms_field(){
        ?>
        <p>
            <Label><?php _e('Synonyms','tooltipy-lang');?>
                <input type="text" 
                    name="tltpy_tooltip_synonyms" 
                    value="<?php echo( get_post_meta( get_the_id(), 'tltpy_tooltip_synonyms', true ) ); ?>" 
                    placeholder = "<?php _e("Type here the tooltip's synonyms separated with '|'", "tooltipy-lang" ); ?>" 
                    style="width:100%;" 
                />
            </label>
        </p>
        <?php
    }

    function tooltip_case_field(){
        $is_checked = get_post_meta( get_the_id(),'tltpy_tooltip_case_sensitive',true) ? 'checked' : '';
        ?>
        <p>
            <label><?php _e('Make this keyword <b>Case Sensitive</b>','tooltipy-lang');?>
                <input type="checkbox" 
                    name="tltpy_tooltip_case_sensitive"
                    <?php echo ( $is_checked ); ?> 
                />
            </label>
        </p>
        <?php
    }
    function tooltip_prefix_field(){
        $is_checked = get_post_meta( get_the_id(),'tltpy_tooltip_is_prefix',true) ? 'checked' : '';
        ?>
        <p>
            <label><?php _e('This Keyword is a <b>Prefix</b>','tooltipy-lang');?>
                <input
                    name="tltpy_tooltip_is_prefix"
                    type="checkbox"
                    <?php echo ( $is_checked ); ?>
                />
            </label>
        </p>
        <?php
    }

    function tooltip_video_field(){
		?>
        <p>
            <label>
                <b><?php _e('Youtube video ID','tooltipy-lang');?></b><br>
                WWW.Youtube.com/watch?v=
                <input
                    name="tltpy_tooltip_youtube_id"
                    type="text"
                    value="<?php echo( get_post_meta( get_the_id(), 'tltpy_tooltip_youtube_id', true ) ); ?>"
                />
                <img src="<?php echo( TOOLTIPY_PLUGIN_URL . 'assets/youtube_icon.png');?>" style="position: relative; top: 5px;"/>
            </label>
        </p>
        <?php
    }
}