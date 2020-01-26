<?php
/**
 * Parafa\Maker\News\Component class
 *
 * @package maker
 */

namespace Parafa\Maker\News;

use Parafa\Maker\Component_Interface;
use WP_Post;
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
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'news';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'post_link', array( $this, 'filter_permalink' ), 10, 2 );
		add_action( 'add_meta_boxes', array( $this, 'action_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'action_save_post_meta' ) );
		add_action( 'rest_api_init', array( $this, 'action_register_rest_fields' ), 0 );
	}

	/**
	 * Undocumented function
	 */
	public function filter_permalink( string $permalink, WP_Post $post ) : string {
		if ( 'post' === $post->post_type ) {
			$page_for_posts = get_option( 'page_for_posts' );
			$page_link      = empty( $page_for_posts ) ? home_url( '/' ) : get_permalink( $page_for_posts );
			$permalink      = $page_link . '#post-' . $post->ID;
		}

		return $permalink;
	}

	/**
	 * Undocumented function
	 */
	public function action_add_meta_boxes() {
		add_meta_box(
			'maker_press_meta',
			__( 'Publication Data', 'maker' ),
			array( $this, 'press_meta_box' ),
			'post',
			'side',
			'high'
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param WP_Post $post Post object.
	 */
	public function press_meta_box( WP_Post $post ) {
		$title = get_post_meta( $post->ID, 'press_title', true );
		$date  = get_post_meta( $post->ID, 'press_date', true );
		$link  = esc_url( get_post_meta( $post->ID, 'press_link', true ) );

		wp_nonce_field( 'maker_press', 'maker_press_nonce' );

		?>
		<div class="components-base-control">

			<div class="components-base-control__field" tabindex="-1">
				<label for="press-title" class="components-base-control__label"><?php esc_html_e( 'Name', 'maker' ); ?></label>
				<input name="press_title" type="text" id="press-title" value="<?php echo esc_attr( $title ); ?>" class="components-text-control__input">
			</div>

			<div class="components-base-control__field" tabindex="-1">
				<label for="press-date" class="components-base-control__label"><?php esc_html_e( 'Date', 'maker' ); ?></label>
				<input name="press_date" type="text" id="press-date" value="<?php echo esc_attr( $date ); ?>" class="components-text-control__input">
			</div>

			<div class="components-base-control__field" tabindex="-1">
				<label for="press-link" class="components-base-control__label"><?php esc_html_e( 'External link', 'maker' ); ?></label>
				<input name="press_link" type="url" id="press-link" value="<?php echo esc_attr( $link ); ?>" class="components-text-control__input">
			</div>

		</div>
		<?php
	}

	/**
	 * Undocumented function
	 *
	 * @param integer $post_id Post ID.
	 */
	public function action_save_post_meta( $post_id ) {
		$nonce = sanitize_key( $_REQUEST['maker_press_nonce'] ?? '' );
		if ( ! wp_verify_nonce( $nonce, 'maker_press' ) ) {
				return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
		}

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
		}

		$title = sanitize_text_field( wp_unslash( $_POST['press_title'] ?? '' ) );
		$date  = sanitize_text_field( wp_unslash( $_POST['press_date'] ?? '' ) );
		$link  = esc_url( sanitize_text_field( wp_unslash( $_POST['press_link'] ?? '' ) ) );

		update_post_meta( $post_id, 'press_title', $title );
		update_post_meta( $post_id, 'press_date', $date );
		update_post_meta( $post_id, 'press_link', $link );
	}

	/**
	 * Undocumented function
	 */
	public function action_register_rest_fields() {
		register_rest_field(
			'post',
			'meta',
			array(
				'get_callback' => array( $this, 'get_post_meta' ),
				'schema'       => null,
			)
		);

		register_rest_field(
			'post',
			'category_names',
			array(
				'get_callback' => array( $this, 'get_post_categories' ),
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
	public function get_post_meta( $post ) : array {
		$meta_keys = array(
			'press_date',
			'press_link',
			'press_title',
		);
		return array_reduce(
			$meta_keys,
			function( $carry, $key ) use ( $post ) {
				$carry[ $key ] = get_post_meta( $post['id'], $key, true );
				return $carry;
			},
			array()
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param array $post Post object.
	 * @return array
	 */
	public function get_post_categories( array $post ) : array {
		return wp_get_post_categories( $post['id'], array( 'fields' => 'names' ) );
	}
}
