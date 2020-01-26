<?php
/**
 * Parafa\Maker\Shortcodes\Component class
 *
 * @package maker
 */

namespace Parafa\Maker\Shortcodes;

use Parafa\Maker\Component_Interface;
use function add_action;
use function add_theme_support;
use function add_image_size;

/**
 * Class for managing post thumbnail support.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'shortcodes';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_shortcode( 'year', array( $this, 'shortcode_year' ) );
		add_shortcode( 'title', array( $this, 'shortcode_title' ) );
		add_shortcode( 'category', array( $this, 'shortcode_category' ) );
		add_shortcode( 'more', array( $this, 'shortcode_read_more' ) );
		add_shortcode( 'read_more', array( $this, 'shortcode_read_more' ) );
	}

	/**
	 * Adds a shortcode to post title.
	 */
	public function shortcode_year() {
		return gmdate( 'Y' );
	}

	/**
	 * Adds a shortcode to post title.
	 */
	public function shortcode_title() {
		return get_the_title();
	}

	/**
	 * Adds a shortcode to post category.
	 */
	public function shortcode_category() {
		return get_the_category_list( __( ', ', 'maker' ) );
	}

	/**
	 * Adds a shortcode to show a Read More link.
	 */
	public function shortcode_read_more() {
		$press_more = null;
		$press_link = esc_url( get_post_meta( get_the_ID(), 'press_link', true ) );

		if ( ! empty( $press_link ) ) {
			$press_domain = wp_parse_url( $press_link, PHP_URL_HOST );
			$press_domain = ucwords( $press_domain );
			$press_more   = sprintf(
				/* translators: 1: Publisher URL, 2: Publisher domain, 3: Element classname. */
				__( '<a href="%1$s" class="%3$s" target="_blank"><b>Read More</b> at %2$s</a>', 'maker' ),
				$press_link,
				$press_domain,
				'mkr-entry__more'
			);
		}

		return $press_more;
	}
}
