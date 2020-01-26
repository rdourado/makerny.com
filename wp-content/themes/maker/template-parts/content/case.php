<?php
/**
 * Template part for displaying a post
 *
 * @package maker
 */

namespace Parafa\Maker;

?>

<article <?php post_class( 'mkr-case' ); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
		<figure class="mkr-case__thumbnail">
			<?php the_post_thumbnail( 'full' ); ?>
		</figure>
	<?php } ?>

	<?php the_content(); ?>

	<?php get_template_part( 'template-parts/content/next-entry' ); ?>
</article>
