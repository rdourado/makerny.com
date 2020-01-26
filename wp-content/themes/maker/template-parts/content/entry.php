<?php
/**
 * Template part for displaying a post
 *
 * @package maker
 */

namespace Parafa\Maker;

?>

<hr class="wp-block-separator is-style-wide"/>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'mkr-entry' ); ?>>
	<header class="mkr-entry__header">
		<div class="mkr-entry__meta">
			<?php the_category( ', ' ); ?>
			—
			<time class="mkr-entry__date" datetime="<?php the_time( 'c' ); ?>">
				<?php the_time( get_option( 'date_format' ) ); ?>
			</time>
		</div>
		<h2 class="mkr-entry__title"><?php the_title(); ?></h2>
	</header>
	<?php the_content(); ?>
</article>
