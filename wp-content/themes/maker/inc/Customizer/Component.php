<?php
/**
 * Parafa\Maker\Customizer\Component class
 *
 * @package maker
 */

namespace Parafa\Maker\Customizer;

use Parafa\Maker\Component_Interface;
use function Parafa\Maker\maker;
use WP_Customize_Manager;
use function add_action;
use function bloginfo;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function wp_parse_args;

/**
 * Class for managing Customizer integration.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'customizer';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'action_customize_register' ) );
	}

	/**
	 * Adds a custom Theme Options section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 *
	 * @link https://developer.wordpress.org/themes/customize-api/
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ) {
		$section = 'theme_options';

		$wp_customize->add_section(
			$section,
			array(
				'title'    => __( 'Contact', 'maker' ),
				'priority' => 130, // Before Additional CSS.
			)
		);

		$this->add_setting(
			$wp_customize,
			array(
				'label'   => __( 'Email', 'maker' ),
				'id'      => 'email',
				'type'    => 'email',
				'section' => $section,
				'default' => MAKER_DEFAULT_EMAIL,
			)
		);

		$this->add_setting(
			$wp_customize,
			array(
				'label'   => __( 'Phone', 'maker' ),
				'id'      => 'phone',
				'type'    => 'tel',
				'section' => $section,
				'default' => MAKER_DEFAULT_PHONE,
			)
		);

		$this->add_setting(
			$wp_customize,
			array(
				'label'   => __( 'Instagram account', 'maker' ),
				'id'      => 'instagram',
				'type'    => 'text',
				'section' => $section,
				'default' => MAKER_DEFAULT_ACCOUNT,
			)
		);

		$this->add_setting(
			$wp_customize,
			array(
				'label'       => __( 'Copyright notice', 'maker' ),
				'description' => __( '[year] will be replace by current year.', 'maker' ),
				'id'          => 'copyright',
				'type'        => 'text',
				'section'     => $section,
				'default'     => '&copy; Maker Creative Inc. [year]',
			)
		);
	}

	/**
	 * Helps adding new settings to a theme customizer section
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array                $args New settings params.
	 */
	protected function add_setting( WP_Customize_Manager $wp_customize, array $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'type'        => 'text',
				'description' => '',
				'default'     => '',
			)
		);

		$wp_customize->add_setting(
			$args['id'],
			array(
				'default'   => $args['default'],
				'type'      => 'theme_mod',
				'transport' => 'refresh',
			)
		);

		$wp_customize->add_control(
			'marram_' . $args['id'],
			array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'settings'    => $args['id'],
				'type'        => $args['type'],
			)
		);
	}
}
