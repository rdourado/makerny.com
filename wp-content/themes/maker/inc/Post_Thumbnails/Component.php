<?php
/**
 * Parafa\Maker\Post_Thumbnails\Component class
 *
 * @package maker
 */

namespace Parafa\Maker\Post_Thumbnails;

use Parafa\Maker\Component_Interface;
use Parafa\Maker\Templating_Component_Interface;
use function add_action;
use function add_theme_support;
use function get_template_directory;
use function wp_kses_allowed_html;
use function wp_kses;

/**
 * Class for managing post thumbnail support.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'post_thumbnails';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_post_thumbnail_support' ) );
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
			'embed_svg' => array( $this, 'embed_svg' ),
		);
	}

	/**
	 * Adds support for post thumbnails.
	 */
	public function action_add_post_thumbnail_support() {
		add_theme_support( 'post-thumbnails', array( 'post' ) );
	}

	/**
	 * Adds custom image sizes.
	 *
	 * @param string $filename File name without extension and inside `assets/images/` folder.
	 */
	public function embed_svg( string $filename ) {
		$filepath = get_template_directory() . '/assets/images/' . $filename . '.svg';

		if ( file_exists( $filepath ) ) {
			$svg_tags = array(
				'svg'     => array(
					'class'           => true,
					'aria-label'      => true,
					'aria-labelledby' => true,
					'aria-hidden'     => true,
					'role'            => true,
					'xmlns'           => true,
					'viewbox'         => true,
				),
				'title'   => array( 'title' => true ),
				'g'       => array(
					'fill'      => true,
					'transform' => true,
				),
				'path'    => array(
					'd'         => true,
					'fill'      => true,
					'transform' => true,
				),
				'polygon' => array(
					'points'    => true,
					'fill'      => true,
					'transform' => true,
				),
				'rect'    => array(
					'x'         => true,
					'y'         => true,
					'width'     => true,
					'height'    => true,
					'fill'      => true,
					'transform' => true,
				),
			);

			$kses_defaults = wp_kses_allowed_html( 'post' );
			$allowed_tags  = array_merge( $kses_defaults, $svg_tags );
			$content       = file_get_contents( $filepath ); // phpcs:disable WordPress.WP.AlternativeFunctions
			$content       = preg_replace(
				'/(aria-label)="(.+?)">/i',
				'role="img" $1="$2"><title>$2</title>',
				$content
			);

			echo wp_kses( $content, $allowed_tags );
		}
	}
}
