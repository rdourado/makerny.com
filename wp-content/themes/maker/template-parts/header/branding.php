<?php
/**
 * Template part for displaying the header branding
 *
 * @package maker
 */

namespace Parafa\Maker;

?>

<?php if ( is_front_page() ) { ?>
	<h1 class="mkr-header__logo">
		<?php maker()->embed_svg( 'logo' ); ?>
	</h1>
<?php } else { ?>
	<p class="mkr-header__logo">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php maker()->embed_svg( 'logo' ); ?>
		</a>
	</p>
<?php } ?>
