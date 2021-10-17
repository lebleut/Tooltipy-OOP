<?php
namespace Tooltipy\Widgets;
use Tooltipy\Tooltipy;
use Tooltipy\Tooltipy_Public;

defined('ABSPATH') or die("No script kiddies please!");

class PostKeywords extends \wp_widget{
	function __construct(){
		$params = [
			'description'=>'contains keywords used in the current single post.',
			'name'=>'Tooltipy - Related keywords'
		];
		parent::__construct( 'tooltipy_related_keywords_widget', '', $params );
	}
	
	public function widget( $args, $instance ) {
		global $post;
		$post_type = get_post_type();

		// Only related post types
		if( !in_array( $post_type, Tooltipy::get_related_post_types() ) )
			return;
		
		$matched_tooltips = Tooltipy_Public::get_active_matched_tooltips( $post->ID );

		if( $matched_tooltips && count($matched_tooltips) ){
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_widget'];
			if ( ! empty( $title ) )
				echo $args['before_title'] . $title . $args['after_title'];
			?>
			<ul>
			<?php
				foreach( $matched_tooltips as $tooltip ){
					if( isset($tooltip['tooltip_title']) ){
						if( isset($instance['links']) && !empty($instance['links']) ){
							?>
							<li><a href="<?php the_permalink( $tooltip['tooltip_id'] ) ?>"><?=$tooltip['tooltip_title']?></a></li>
							<?php
						}else{
							?>
							<li><?=$tooltip['tooltip_title']?></li>
							<?php
						}
					}
				}
			?>
			</ul>
			<?php
			echo $args['after_widget'];
		}
	}

	public function form( $instance ) {
        ?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>" >Title : </label>
				<input
					class="widefat"
					id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>"
					value="<?php if(isset($instance['title'])) echo esc_attr($instance['title']); ?>"
				/>
			</p>
			<p>
				<label>
					Add links
					<input
						type="checkbox"
						class="widefat"
						name="<?php echo $this->get_field_name('links'); ?>"
						<?php echo isset($instance['links']) && !empty($instance['links']) ? 'checked' : ''; ?>
					/>
				</label>
			</p>
        <?php
	}
}
?>