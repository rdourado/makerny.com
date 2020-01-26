<?php
/**
 * Parafa\Maker\Cases\Component class
 *
 * @package maker
 */

namespace Parafa\Maker\Cases;

use Parafa\Maker\Component_Interface;
use function add_action;
use function add_theme_support;
use function add_image_size;

/**
 * Class for managing post type support.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-types/
 */
class Component implements Component_Interface {

	/**
	 * Custom post type name.
	 *
	 * @var string
	 */
	private $post_type = 'case';

	/**
	 * Custom taxonomy name.
	 *
	 * @var string
	 */
	private $taxonomy = 'client';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'cases';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'init', array( $this, 'action_register_post_type' ), 0 );
		add_action( 'init', array( $this, 'action_register_taxonomy' ), 0 );
		add_action( 'rest_api_init', array( $this, 'action_register_rest_fields' ), 0 );
		add_action( 'add_meta_boxes', array( $this, 'action_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'action_save_post_meta' ) );
		add_filter( 'body_class', array( $this, 'filter_body_class' ) );
	}

	/**
	 * Register Case custom post type.
	 */
	public function action_register_post_type() {
		$labels = array(
			'name'                  => _x( 'Cases', 'post type general name', 'maker' ),
			'singular_name'         => _x( 'Case', 'post type singular name', 'maker' ),
			'menu_name'             => __( 'Cases', 'maker' ),
			'name_admin_bar'        => __( 'Case', 'maker' ),
			'archives'              => __( 'Case Archives', 'maker' ),
			'attributes'            => __( 'Case Attributes', 'maker' ),
			'parent_item_colon'     => __( 'Parent Case:', 'maker' ),
			'all_items'             => __( 'All Cases', 'maker' ),
			'add_new_item'          => __( 'Add New Case', 'maker' ),
			'add_new'               => __( 'Add New', 'maker' ),
			'new_item'              => __( 'New Case', 'maker' ),
			'edit_item'             => __( 'Edit Case', 'maker' ),
			'update_item'           => __( 'Update Case', 'maker' ),
			'view_item'             => __( 'View Case', 'maker' ),
			'view_items'            => __( 'View Cases', 'maker' ),
			'search_items'          => __( 'Search Case', 'maker' ),
			'not_found'             => __( 'Not found', 'maker' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'maker' ),
			'featured_image'        => __( 'Featured Image', 'maker' ),
			'set_featured_image'    => __( 'Set featured image', 'maker' ),
			'remove_featured_image' => __( 'Remove featured image', 'maker' ),
			'use_featured_image'    => __( 'Use as featured image', 'maker' ),
			'insert_into_item'      => __( 'Insert into case', 'maker' ),
			'uploaded_to_this_item' => __( 'Uploaded to this case', 'maker' ),
			'items_list'            => __( 'Cases list', 'maker' ),
			'items_list_navigation' => __( 'Cases list navigation', 'maker' ),
			'filter_items_list'     => __( 'Filter cases list', 'maker' ),
		);

		$args = array(
			'label'               => __( 'Case', 'maker' ),
			'description'         => __( 'Case’s Gallery or Case Study', 'maker' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'post-formats', 'thumbnail' ),
			'taxonomies'          => array( 'category', $this->taxonomy ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-index-card',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Register Client custom taxonomy.
	 */
	public function action_register_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Clients', 'taxonomy general name', 'maker' ),
			'singular_name'              => _x( 'Client', 'taxonomy singular name', 'maker' ),
			'menu_name'                  => __( 'Clients', 'maker' ),
			'all_items'                  => __( 'All Clients', 'maker' ),
			'parent_item'                => __( 'Parent Client', 'maker' ),
			'parent_item_colon'          => __( 'Parent Client:', 'maker' ),
			'new_item_name'              => __( 'New Client Name', 'maker' ),
			'add_new_item'               => __( 'Add New Client', 'maker' ),
			'edit_item'                  => __( 'Edit Client', 'maker' ),
			'update_item'                => __( 'Update Client', 'maker' ),
			'view_item'                  => __( 'View Client', 'maker' ),
			'separate_items_with_commas' => __( 'Separate clients with commas', 'maker' ),
			'add_or_remove_items'        => __( 'Add or remove clients', 'maker' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'maker' ),
			'popular_items'              => __( 'Popular Clients', 'maker' ),
			'search_items'               => __( 'Search Clients', 'maker' ),
			'not_found'                  => __( 'Not Found', 'maker' ),
			'no_terms'                   => __( 'No clients', 'maker' ),
			'items_list'                 => __( 'Clients list', 'maker' ),
			'items_list_navigation'      => __( 'Clients list navigation', 'maker' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
			'show_in_rest'      => true,
		);

		register_taxonomy( $this->taxonomy, array( $this->post_type ), $args );
	}

	/**
	 * Undocumented function
	 */
	public function action_register_rest_fields() {
		register_rest_field(
			'case',
			'terms',
			array(
				'get_callback' => array( $this, 'get_all_post_terms' ),
				'schema'       => null,
			)
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param array $post Post object.
	 * @return array
	 */
	public function get_all_post_terms( $post ) : array {
		$taxonomies = get_post_taxonomies( $post['id'] );
		return array_reduce(
			$taxonomies,
			function( $carry, $tax ) use ( $post ) {
				$carry[ $tax ] = wp_get_post_terms( $post['id'], $tax, array( 'fields' => 'names' ) );
				return $carry;
			},
			array()
		);
	}

	/**
	 * Undocumented function
	 */
	public function action_add_meta_boxes() {
		add_meta_box(
			'maker_case_meta',
			__( 'Presentation', 'maker' ),
			array( $this, 'case_meta_box' ),
			'case',
			'side',
			'high'
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param WP_Post $post Post object.
	 */
	public function case_meta_box( $post ) {
		$color = get_post_meta( $post->ID, 'case_color', true );

		wp_nonce_field( 'maker_case', 'maker_case_nonce' );

		?>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scole="row">
						<label for="case-color"><?php esc_html_e( 'Menu Color Scheme', 'maker' ); ?></label>
					</th>
					<td>
						<select name="case_color" id="case-color" value="<?php echo esc_attr( $title ); ?>">
							<option value="white" <?php selected( $color, 'white' ); ?>>White</option>
							<option value="grey" <?php selected( $color, 'grey' ); ?>>Grey</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Undocumented function
	 *
	 * @param integer $post_id
	 */
	public function action_save_post_meta( $post_id ) {
		$nonce = sanitize_key( $_REQUEST['maker_case_nonce'] ?? '' );
		if ( ! wp_verify_nonce( $nonce, 'maker_case' ) ) {
				return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
		}

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
		}

		$color = sanitize_text_field( wp_unslash( $_POST['case_color'] ?? '' ) );

		update_post_meta( $post_id, 'case_color', $color );
	}

	/**
	 * Undocumented function
	 *
	 * @param array $classes
	 */
	public function filter_body_class( $classes ) {
		if ( is_singular( 'case' ) ) {
			$color = get_post_meta( get_the_ID(), 'case_color', true );
			if ( 'grey' !== $color ) {
				$classes[] = 'mkr--alt-menu-n-logo';
			}
		}

		return $classes;
	}
}
