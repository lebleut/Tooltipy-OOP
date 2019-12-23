<?php
namespace Tooltipy;

use Tooltipy\Settings;

class Metabox_Manager{
	
	public $prefix;
	public $post_types;
	public $label;
	public $slug;

	public function __construct( $post_types, $prefix, $label, $slug ){
		$this->post_types 	= (array)$post_types;
		$this->prefix 		= $prefix;
		$this->label 		= $label;
		$this->slug 		= $slug;

		// Actions and hooks
		add_action( 'do_meta_boxes', array( $this, 'add_metabox' ), 10, 3 );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}

	public function get_fields(){
		$fields = [
			[
				'uid' 			=> 'field1',
				'type' 			=> 'radio',
				
				'label' 		=> 'Field 1',
				'options' 		=> [
					'opt1' 	=> 'Option 1',
					'opt2'	=> 'Option 2',
					'opt3' 	=> 'Option 3',
					'opt4'	=> 'Option 4',
					'opt5' 	=> 'Option 5',
				],
				'description' => 'Just for test',

				'default' 		=> [ 'opt3' ],
			],
			[
				'uid' 			=> 'field2',
				'type' 			=> 'text',
				
				'label' 		=> 'Field 2',
				'placeholder'	=> 'Put text here',
				'default' 		=> '',
			],
			[
				'uid' 			=> 'field3',
				'type' 			=> 'checkbox',
				
				'label' 		=> 'Field 3',
				'placeholder'	=> 'Chouse',
				'options'		=> [
					'ck1'	=> 'val 1',
					'ck2'	=> 'val 2',
					'ck3'	=> 'val 3',
					'ck4'	=> 'val 4',
				],
				'default' 		=> '',
			],
		];

		// Add prefix to id
		$fields = array_map( function( $field ){ $field['uid'] = $this->prefix . $field['uid']; return $field; }, $fields );

		return $fields;
	}

	public function save_fields( $post_id ){

		if( !in_array($_POST['post_type'], $this->post_types) ){
			return false;
		}

		// editpost : to prevent bulk edit problems
		if( !empty($_POST['action']) && $_POST['action'] == 'editpost' ){

			$fields = $this->get_fields();
			foreach ( $fields as $field) {
				$this->save_field( $post_id, $field );
			}
		}
	}

	function save_field( $post_id, $field ){
		$field_id = $field[ 'uid' ];

		if( in_array( $field['type'], [ 'radio', 'checkbox', 'select', 'multiselect' ] ) && !isset($_POST[$field_id]) ){
			$value = [];
		}else if( isset($_POST[$field_id]) ){
			$value = $_POST[$field_id];
		}else if( !isset($_POST[$field_id]) ){
			return;
		}

		// Filter hook before saving meta field
		$value = apply_filters( $this->prefix . 'metabox_field_before_save', $value, $field, $_POST );

		update_post_meta( $post_id, $field_id, $value );
	}

	public function add_metabox( $post_type, $context, $post ){
		if( !in_array( $post_type, $this->post_types ) ){
			return false;
		}

		add_meta_box(
			$this->prefix . $this->slug,
			$this->label,
			array( $this, 'render_metabox' )
		);
	}

	public function render_metabox(){
		$fields = $this->get_fields();
		
		if( !count( $fields ) ) return;

		?>
		<table>
			<?php
			array_map( function($field){
				?>
				<tr>
					<td><?php echo $field[ 'label' ]; ?></td>
					<td><?php echo Settings::field_callback( $field, true ); ?></td>
				</tr>
				<?php
			}, $fields );
		?>
		</table>
		<?php
	}
}