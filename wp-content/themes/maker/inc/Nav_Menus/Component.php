<?php
/**
 * Parafa\Maker\Nav_Menus\Component class
 *
 * @package maker
 */

namespace Parafa\Maker\Nav_Menus;

use Parafa\Maker\Component_Interface;
use Parafa\Maker\Templating_Component_Interface;
use WP_Post;
use function add_action;
use function add_filter;
use function register_nav_menus;
use function esc_html__;
use function wp_nav_menu;

/**
 * Class for managing navigation menus.
 *
 * Exposes template tags:
 * * `maker()->display_primary_nav_menu( array $args = [] )`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	const PRIMARY_NAV_MENU_SLUG = 'primary';
	const FOOTER_NAV_MENU_SLUG  = 'footer';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'nav_menus';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_register_nav_menus' ) );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `maker()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return array(
			'display_primary_nav_menu' => array( $this, 'display_primary_nav_menu' ),
			'display_footer_nav_menu'  => array( $this, 'display_footer_nav_menu' ),
		);
	}

	/**
	 * Registers the navigation menus.
	 */
	public function action_register_nav_menus() {
		register_nav_menus(
			array(
				static::PRIMARY_NAV_MENU_SLUG => esc_html__( 'Primary', 'maker' ),
				static::FOOTER_NAV_MENU_SLUG  => esc_html__( 'Footer', 'maker' ),
			)
		);
	}

	/**
	 * Displays the primary navigation menu.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_nav_menu()` documentation for a list of supported
	 *                    arguments.
	 */
	public function display_primary_nav_menu( array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'container'      => false,
				'fallback_cb'    => false,
				'theme_location' => static::PRIMARY_NAV_MENU_SLUG,
			)
		);

		wp_nav_menu( $args );
	}

	/**
	 * Displays the footer navigation menu.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_nav_menu()` documentation for a list of supported
	 *                    arguments.
	 */
	public function display_footer_nav_menu( array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'container'      => false,
				'fallback_cb'    => false,
				'theme_location' => static::FOOTER_NAV_MENU_SLUG,
			)
		);

		wp_nav_menu( $args );
	}
}
