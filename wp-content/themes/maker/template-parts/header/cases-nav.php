<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package maker
 */

namespace Parafa\Maker;

use WP_Query;

$args  = array(
	'posts_per_page' => 6,
	'post_type'      => 'case',
);
$query = new WP_Query( $args );

?>

<section class="mkr-cases">
	<h2 class="mkr-cases__heading">
		<?php esc_html_e( 'Case Studies', 'maker' ); ?>
	</h2>
	<ul class="mkr-cases__entries">
		<?php
		while ( $query->have_posts() ) {
			$query->the_post();
			$clients = wp_get_post_terms( get_the_ID(), 'client', array( 'fields' => 'names' ) );
			?>
			<li class="mkr-cases__entry">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php the_title(); ?><strong> – <?php echo wp_kses_post( $clients[0] ); ?></strong>
				</a>
			</li>
			<li class="mkr-cases__thumbnail" role="presentation">
				<?php the_post_thumbnail( 'large' ); ?>
			</li>
			<?php
		}
		wp_reset_postdata();
		?>
	</ul>
</section>
