<?php
/**
 * Parafa\Maker\Editor\Component class
 *
 * @package maker
 */

namespace Parafa\Maker\Editor;

use Parafa\Maker\Component_Interface;
use function add_action;
use function add_theme_support;

/**
 * Class for integrating with the block editor.
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
		return 'editor';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_support' ) );
	}

	/**
	 * Adds support for various editor features.
	 */
	public function action_add_editor_support() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for wide-aligned images.
		add_theme_support( 'align-wide' );

		/**
		 * Add support for color palettes.
		 *
		 * To preserve color behavior across themes, use these naming conventions:
		 * - Use primary and secondary color for main variations.
		 * - Use `theme-[color-name]` naming standard for standard colors (red, blue, etc).
		 * - Use `custom-[color-name]` for non-standard colors.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-colors' );
		 */
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Black', 'maker' ),
					'slug'  => 'theme-black',
					'color' => '#010101',
				),
				array(
					'name'  => __( 'Grey', 'maker' ),
					'slug'  => 'theme-grey',
					'color' => '#434343',
				),
				array(
					'name'  => __( 'Grey light', 'maker' ),
					'slug'  => 'theme-grey-light',
					'color' => '#b3b3b3',
				),
				array(
					'name'  => __( 'Grey lighter', 'maker' ),
					'slug'  => 'theme-grey-lighter',
					'color' => '#f9f8f6',
				),
				array(
					'name'  => __( 'White', 'maker' ),
					'slug'  => 'theme-white',
					'color' => '#ECF0F1',
				),
			)
		);

		/*
		 * Add support custom font sizes.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-font-sizes' );
		 */
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'maker' ),
					'shortName' => __( 'S', 'maker' ),
					'size'      => 10,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Medium', 'maker' ),
					'shortName' => __( 'M', 'maker' ),
					'size'      => 14,
					'slug'      => 'medium',
				),
				array(
					'name'      => __( 'Large', 'maker' ),
					'shortName' => __( 'L', 'maker' ),
					'size'      => 24,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Larger', 'maker' ),
					'shortName' => __( 'XL', 'maker' ),
					'size'      => 32,
					'slug'      => 'larger',
				),
			)
		);
	}
}
