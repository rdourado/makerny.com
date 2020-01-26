<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package maker
 */

namespace Parafa\Maker;

$copyright = do_shortcode( get_theme_mod( 'copyright', MAKER_DEFAULT_COPYRIGHT ) );

?>

<footer class="mkr-footer">
	<?php get_template_part( 'template-parts/footer/branding' ); ?>
	<?php maker()->display_footer_nav_menu( array( 'menu_class' => 'mkr-footer__menu' ) ); ?>
	<?php get_template_part( 'template-parts/footer/contact' ); ?>
	<p class="mkr-footer__copy"><?php echo wp_kses_post( $copyright ); ?></p>
</footer>

<?php wp_footer(); ?>

</body>
</html>
