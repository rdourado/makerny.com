<?php
/**
 * Template part for displaying the footer branding
 *
 * @package maker
 */

namespace Parafa\Maker;

?>

<?php if ( is_front_page() ) { ?>
	<h4 class="mkr-footer__logo">
		<?php maker()->embed_svg( 'logo' ); ?>
	</h4>
<?php } else { ?>
	<p class="mkr-footer__logo">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php maker()->embed_svg( 'logo' ); ?>
		</a>
	</p>
<?php } ?>
