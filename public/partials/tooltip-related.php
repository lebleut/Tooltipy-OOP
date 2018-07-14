<h2>Related posts</h2>
<?php
global $post;

$current_tooltip_id = get_the_ID();

$args = array(
    'post_per_page' => -1,
    'post_status' => 'publish',
);
$posts = get_posts( $args );
$posts_array = array();
?>

<?php if( count( $posts ) ): ?>
    <?php foreach ($posts as $post): setup_postdata( $post ); ?>
        <?php
        $matched_tooltips = get_post_meta( get_the_ID(), 'tltpy_matched_tooltips', true );
        $matched = false;
        foreach ($matched_tooltips as $ttp) {
            if( $ttp['tooltip_id'] == $current_tooltip_id ){
                $matched = true;
                break;
            }
        }
        if( $matched ){
            array_push( $posts_array, array(
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
            ) );
        }
        ?>
    <?php endforeach; wp_reset_postdata(); ?>

    <?php if( !empty( $posts_array ) ): ?>
        <ul>
            <?php foreach ($posts_array as $related): ?>
                <li><a href="<?php echo $related['permalink']; ?>"><?php echo $related['title']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div>
            <i><?php _e( 'No related posts for this tooltip.', 'tooltipy-lang' ); ?></i>
        </div>
    <?php endif;?>

<?php endif; ?>