<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.parafernalia.net.br/
 * @since      1.0.0
 *
 * @package    Maker_Blocks
 * @subpackage Maker_Blocks/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Maker_Blocks
 * @subpackage Maker_Blocks/admin
 * @author     Parafernália Interativa <rafael.dourado@parafernalia.net.br>
 */
class Maker_Blocks_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name  The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version  The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name  The name of this plugin.
	 * @param string $version      The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_editor_assets() {

		$asset_file = include plugin_dir_path( __FILE__ ) . 'js/maker-blocks-admin.asset.php';

		wp_enqueue_script(
			'maker-blocks',
			plugins_url( 'js/maker-blocks-admin.js', __FILE__ ),
			$asset_file['dependencies'],
			$asset_file['version'],
			false
		);

	}

	/**
	 * Undocumented function
	 */
	public function register_blocks() {

		register_block_type(
			'maker/taxonomy-list',
			array( 'render_callback' => array( $this, 'render_taxonomy_list' ) )
		);

		register_block_type(
			'maker/cases',
			array( 'render_callback' => array( $this, 'render_cases' ) )
		);

		register_block_type(
			'maker/news',
			array( 'render_callback' => array( $this, 'render_news' ) )
		);

	}

	/**
	 * Undocumented function
	 *
	 * @param array  $attributes  aaa.
	 * @param string $content  bbb.
	 * @return string
	 */
	public function render_taxonomy_list( $attributes, $content ) {

		$defaults = array(
			'taxonomy' => 'client',
			'depth'    => 1,
			'title_li' => false,
			'echo'     => false,
		);

		$args     = wp_parse_args( $attributes, $defaults );
		$tax_slug = isset( $attributes['taxonomy'] ) ? $attributes['taxonomy'] : 'client';
		$taxonomy = get_taxonomy( $tax_slug );
		$tax_name = isset( $taxonomy->labels->name ) ? $taxonomy->labels->name : __( 'Clients', 'maker' );

		return sprintf(
			'<h3 class="%s">%s</h3><ul class="%s">%s</ul>',
			'mkr-tax__heading is-style-tiny-strong',
			$tax_name,
			'mkr-tax__entries is-style-tiny-light',
			wp_list_categories( $args )
		);

	}

	/**
	 * Undocumented function
	 *
	 * @param array  $attributes  aaa.
	 * @param string $content  bbb.
	 * @return string
	 */
	public function render_cases( $attributes, $content ) {

		global $post;

		$posts_to_show = isset( $attributes['postsToShow'] ) ? $attributes['postsToShow'] : 5;
		$order         = isset( $attributes['order'] ) ? $attributes['order'] : 'desc';
		$order_by      = isset( $attributes['orderBy'] ) ? $attributes['orderBy'] : 'date';

		$args = array(
			'posts_per_page' => $posts_to_show,
			'order'          => $order,
			'orderBy'        => $order_by,
			'post_type'      => 'case',
		);

		$posts = array();
		$query = new WP_Query( $args );

		while ( $query->have_posts() ) {
			$query->the_post();
			$terms   = wp_get_post_terms( $post->ID, 'client', array( 'fields' => 'names' ) );
			$posts[] = sprintf(
				'<a href="%s">%s &nbsp;—&nbsp; <strong>%s</strong></a>',
				get_permalink( $post ),
				get_the_title( $post ),
				isset( $terms[0] ) ? $terms[0] : ''
			);
		}

		return '<ul><li>' . implode( '</li><li>', $posts ) . '</li></ul>';

	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $attributes
	 * @param [type] $content
	 */
	public function render_news( $attributes, $content ) {

		global $post;

		$posts_per_page = isset( $attributes['numberOfItems'] ) ? $attributes['numberOfItems'] : 5;
		$order          = isset( $attributes['order'] ) ? $attributes['order'] : 'desc';
		$order_by       = isset( $attributes['orderBy'] ) ? $attributes['orderBy'] : 'date';

		$args = array(
			'posts_per_page' => $posts_per_page,
			'order'          => $order,
			'orderBy'        => $order_by,
			'post_type'      => 'post',
		);

		$posts = array();
		$query = new WP_Query( $args );

		while ( $query->have_posts() ) {
			$query->the_post();
			$title    = get_post_meta( $post->ID, 'press_title', true );
			$date     = get_post_meta( $post->ID, 'press_date', true );
			$datetime = $date;
			$link     = get_permalink();

			if ( empty( $title ) ) {
				$categories = wp_get_post_categories( $post->ID, array( 'fields' => 'names' ) );
				$title      = $categories[0];
			}

			if ( empty( $date ) ) {
				$date_format = get_option( 'date_format' );
				$date        = get_the_time( $date_format );
				$datetime    = get_the_time( 'c' );
			}

			$posts[] = sprintf(
				'<a href="%s" rel="bookmark"><time class="%s" datetime="%s">%s, %s</time><h4 class="%s">%s</h4></a>',
				$link,
				'mkr-press__meta',
				$datetime,
				$title,
				$date,
				'mkr-press__title',
				get_the_title( $post )
			);
		}

		$posts = implode( sprintf( '</article><article class="%s">', 'mkr-press__entry' ), $posts );

		return sprintf( '<div class="%s"><article class="%s">%s</article></div>', 'mkr-press', 'mkr-press__entry', $posts );

	}

}
