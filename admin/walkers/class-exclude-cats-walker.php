<?php
namespace Tooltipy;

require_once ABSPATH . '/wp-admin/includes/class-walker-category-checklist.php';

class Exclude_Cats_Walker extends \Walker_Category_Checklist {

	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        if ( empty( $args['taxonomy'] ) ) {
            $taxonomy = 'category';
        } else {
            $taxonomy = $args['taxonomy'];
        }
 
        if ( 'category' === $taxonomy ) {
            $name = 'post_category';
        } else {
            $name = 'tltpy_exclude_cats';
        }
 
        $args['popular_cats'] = ! empty( $args['popular_cats'] ) ? array_map( 'intval', $args['popular_cats'] ) : array();
 
        $class = in_array( $category->term_id, $args['popular_cats'], true ) ? ' class="popular-category"' : '';
 
        $args['selected_cats'] = ! empty( $args['selected_cats'] ) ? array_map( 'intval', $args['selected_cats'] ) : array();
 
        if ( ! empty( $args['list_only'] ) ) {
            $aria_checked = 'false';
            $inner_class  = 'category';
 
            if ( in_array( $category->term_id, $args['selected_cats'], true ) ) {
                $inner_class .= ' selected';
                $aria_checked = 'true';
            }
 
            $output .= "\n" . '<li' . $class . '>' .
                '<div class="' . $inner_class . '" data-term-id=' . $category->term_id .
                ' tabindex="0" role="checkbox" aria-checked="' . $aria_checked . '">' .
                /** This filter is documented in wp-includes/category-template.php */
                esc_html( apply_filters( 'the_category', $category->name, '', '' ) ) . '</div>';
        } else {
            $is_selected = in_array( $category->term_id, $args['selected_cats'], true );
            $is_disabled = ! empty( $args['disabled'] );
 
            $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
                '<label class="selectit"><input value="' . $category->term_id . '" type="checkbox" name="' . $name . '[]" id="in-' . $taxonomy . '-' . $category->term_id . '"' .
                checked( $is_selected, true, false ) .
                disabled( $is_disabled, true, false ) . ' /> ' .
                /** This filter is documented in wp-includes/category-template.php */
                esc_html( apply_filters( 'the_category', $category->name, '', '' ) ) . '</label>';
        }
    }
}