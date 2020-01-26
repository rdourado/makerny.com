<?php
/**
 * Template part for displaying a post
 *
 * @package maker
 */

namespace Parafa\Maker;

?>

<?php
$next_post = ! empty( get_previous_post() ) ? get_previous_post() : get_next_post();
if ( ! empty( $next_post ) ) {
	$thumbnail = get_the_post_thumbnail( $next_post, 'large', array( 'class' => 'mkr-next-entry__thumbnail' ) );
	?>
	<figure class="mkr-next-entry">
		<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
			<?php echo wp_kses_post( $thumbnail ); ?>
			<p class="mkr-next-entry__meta">
				<?php esc_html_e( 'Featured Work', 'maker' ); ?>
			</p>
			<h4 class="mkr-next-entry__title">
				<?php echo wp_kses_post( $next_post->post_title ); ?>
			</h4>
		</a>
	</figure>
	<?php
}
