<?php
/**
 * Template part for displaying the footer info
 *
 * @package maker
 */

namespace Parafa\Maker;

$email         = sanitize_email( get_theme_mod( 'email', MAKER_DEFAULT_EMAIL ) );
$email_raw     = strtolower( $email );
$phone         = get_theme_mod( 'phone', MAKER_DEFAULT_PHONE );
$phone_raw     = str_replace( array( '+', '-', '–', '—' ), '', filter_var( $phone, FILTER_SANITIZE_NUMBER_INT ) );
$instagram     = get_theme_mod( 'instagram', MAKER_DEFAULT_ACCOUNT );
$instagram_url = esc_url( 'https://www.instagram.com/' . strtolower( $instagram ) . '/', array( 'https' ) );

?>

<address class="mkr-contact">
	<p class="mkr-contact__email">
		<a href="mailto:<?php echo esc_attr( antispambot( $email_raw ) ); ?>"><?php echo wp_kses_post( antispambot( $email ) ); ?></a>
	</p>
	<p class="mkr-contact__phone">
		<a href="tel:+1<?php echo esc_attr( $phone_raw ); ?>"><?php echo wp_kses_post( $phone ); ?></a>
	</p>
	<p class="mkr-contact__social">
		<a href="<?php echo esc_attr( $instagram_url ); ?>">@<?php echo wp_kses_post( $instagram ); ?></a>
	</p>
</address>
