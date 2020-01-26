<?php
/**
 * Parafa\Maker\Gallery\Component class
 *
 * @package maker
 */

namespace Parafa\Maker\Gallery;

use Parafa\Maker\Component_Interface;
use function Parafa\Maker\maker;
use WP_Post;
use function add_action;
use function add_filter;
use function register_block_style;
use function wp_enqueue_script;
use function get_post_meta;
use function update_post_meta;
use function delete_post_meta;
use function wp_cache_get;
use function wp_cache_add;

/**
 * Class for integrating with the block gallery.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'gallery';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_init', array( $this, 'action_register_block_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_scripts' ) );
		add_filter( 'attachment_fields_to_edit', array( $this, 'filter_add_attachment_fields' ), 10, 2 );
		add_filter( 'attachment_fields_to_save', array( $this, 'filter_save_attachment_fields' ), 10, 2 );
		add_filter( 'render_block', array( $this, 'filter_gallery_block' ), 10, 2 );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'set_mobile_srcset' ), 10, 5 );
		add_filter( 'wp_calculate_image_sizes', array( $this, 'set_mobile_sizes' ), 10, 5 );
	}

	/**
	 * Adds support for various gallery features.
	 */
	public function action_register_block_styles() {

		if ( ! function_exists( 'register_block_style' ) ) {
			return;
		}

		register_block_style(
			'core/gallery',
			array(
				'name'  => 'carousel-slick',
				'label' => __( 'Slick Carousel', 'maker' ),
			)
		);

		register_block_style(
			'core/gallery',
			array(
				'name'  => 'carousel-default',
				'label' => __( 'Default Carousel', 'maker' ),
			)
		);

	}

	/**
	 * Enqueues a script that improves navigation menu accessibility.
	 */
	public function action_enqueue_scripts() {

		if ( maker()->is_amp() ) {
			return;
		}

		$slick_url = '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js';
		wp_register_script( 'slick-carousel', $slick_url, array( 'jquery' ), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion

		wp_enqueue_script(
			'maker-navigation',
			get_theme_file_uri( '/assets/js/bundle.js' ),
			array( 'jquery', 'slick-carousel' ),
			maker()->get_asset_version( get_theme_file_path( '/assets/js/bundle.js' ) ),
			true
		);

	}

	/**
	 * Undocumented function
	 *
	 * @param array   $form_fields An array of attachment form fields.
	 * @param WP_Post $post        The WP_Post attachment object.
	 * @return array
	 */
	public function filter_add_attachment_fields( array $form_fields, WP_Post $post ) : array {

		$layout       = (int) get_post_meta( $post->ID, 'layout', true );
		$layout_input = '<label for="attachments-%2$d-layout" class="mkr-attach-layout__icon mkr-attach-layout__icon--%2$d">'
			. '<input type="radio" name="attachments[%1$d][layout]" id="attachments-%2$d-layout" value="%2$d" %3$s />'
			. '<span></span></label>';

		$layout_html = array();
		for ( $i = 0; $i < 7; $i++ ) {
			$layout_html[] = sprintf( $layout_input, $post->ID, $i, $i === $layout ? 'checked="checked"' : '' );
		}
		$layout_html = '<span class="mkr-attach-layout">' . implode( ' ', $layout_html ) . '</span>';

		$form_fields['layout'] = array(
			'value' => $layout ? $layout : '',
			'input' => 'html',
			'html'  => $layout_html,
			'label' => __( 'Slide layout', 'maker' ),
			'helps' => __( 'Exclusive for Slick Carousel style', 'maker' ),
		);

		$mobile_src = (string) get_post_meta( $post->ID, 'mobile_src', true );

		$form_fields['mobile_src'] = array(
			'value' => $mobile_src,
			'label' => __( 'Mobile', 'maker' ),
			'helps' => __( 'Image ID (< 1242px)', 'maker' ),
		);

		return $form_fields;

	}

	/**
	 * Adds support for various gallery features.
	 *
	 * @param array $post       Post attributes.
	 * @param array $attachment Attachment fields.
	 */
	public function filter_save_attachment_fields( array $post, array $attachment ) {

		if ( isset( $attachment['layout'] ) ) {
			$layout = (int) $attachment['layout'];
			update_post_meta( $post['ID'], 'layout', $layout );
		} else {
			delete_post_meta( $post['ID'], 'layout' );
		}

		if ( ! empty( $attachment['mobile_src'] ?? null ) ) {
			$mobile_src = wp_unslash( $attachment['mobile_src'] );
			$mobile_src = is_numeric( $mobile_src ) ? intval( $mobile_src ) : esc_url_raw( $mobile_src );
			update_post_meta( $post['ID'], 'mobile_src', $mobile_src );
		} else {
			delete_post_meta( $post['ID'], 'mobile_src' );
		}

	}

	/**
	 * Undocumented function
	 *
	 * @param string $block_content The block content about to be appended.
	 * @param array  $block         The full block, including name and attributes.
	 * @return string
	 */
	public function filter_gallery_block( string $block_content, array $block ) : string {

		global $wpdb;

		if ( 'core/gallery' !== $block['blockName'] ) {
			return $block_content;
		}

		$attach_ids = $block['attrs']['ids'] ?? array();
		$cache_key  = md5( $block['innerContent'][0] ?? '' );
		$content    = wp_cache_get( $cache_key, 'maker' );

		if ( ! empty( $content ) ) {
			return $content;
		}

		$class_names = array(
			'blocks-gallery-item--full',
			'blocks-gallery-item--right',
			'blocks-gallery-item--center',
			'blocks-gallery-item--left',
			'blocks-gallery-item--right-off',
			'blocks-gallery-item--bottom-off',
			'blocks-gallery-item--center-off',
		);

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL
		$in     = join( ',', array_fill( 0, count( $attach_ids ), '%d' ) );
		$result = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_id, meta_value, meta_key FROM $wpdb->postmeta WHERE post_id IN ($in) AND meta_key = %s",
				array_merge( $attach_ids, array( 'layout' ) )
			),
			ARRAY_A
		);
		// phpcs:enable

		$layouts = array_reduce(
			$attach_ids,
			function( $carry, $id ) use ( $result, $class_names ) {
				$key     = array_search( strval( $id ), array_column( $result, 'post_id' ), true );
				$layout  = (int) ( $result[ $key ]['meta_value'] ?? 0 );
				$carry[] = $class_names[ $layout ];
				return $carry;
			},
			array()
		);

		$index   = 0;
		$content = preg_replace_callback(
			'/"blocks-gallery-item"/',
			function( $matches ) use ( $layouts, &$index ) {
				$layout = $layouts[ $index++ ] ?? '';
				return '"blocks-gallery-item ' . $layout . '"';
			},
			$block_content
		);

		wp_cache_add( $cache_key, $content, 'maker' );

		return $content;

	}

	/**
	 * Undocumented function
	 *
	 * @param array   $sources       One or more arrays of source data to include in the 'srcset'.
	 * @param array   $size_array    Array of width and height values in pixels (in that order).
	 * @param string  $image_src     The 'src' of the image.
	 * @param array   $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
	 * @param integer $attachment_id Image attachment ID or 0.
	 * @return array
	 */
	public function set_mobile_srcset( array $sources, array $size_array, string $image_src, array $image_meta, int $attachment_id ) : array {
		$value = get_post_meta( $attachment_id, 'mobile_src', true );
		if ( empty( $value ) ) {
			return $sources;
		}

		$width = 414 * 3;
		$url   = is_numeric( $value )
			? wp_get_attachment_image_url( intval( $value ), 'large' )
			: esc_url( $value );

		$sources = array_filter(
			$sources,
			function ( $source ) use ( $width ) {
				return $source['value'] > $width;
			}
		);

		$sources[ $width ] = array(
			'url'        => $url,
			'descriptor' => 'w',
			'value'      => $width,
		);

		return $sources;
	}

	/**
	 * Filters the output of 'wp_calculate_image_sizes()'.
	 *
	 * @param string  $sizes         A source size value for use in a 'sizes' attribute.
	 * @param mixed   $size          Requested size. Image size or array of width and height values in pixels (in that order).
	 * @param string  $image_src     The URL to the image file or null.
	 * @param array   $image_meta    The image meta data as returned by wp_get_attachment_metadata() or null.
	 * @param integer $attachment_id Image attachment ID of the original image or 0.
	 * @return string
	 */
	public function set_mobile_sizes( string $sizes, $size, string $image_src, array $image_meta, int $attachment_id ) : string {
		return '100vw';
	}

}
