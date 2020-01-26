<?php
/**
 * The post/news template file
 *
 * @package maker
 */

namespace Parafa\Maker;

get_header();
?>
<main class="mkr-main">
	<h1 class="mkr-main__heading"><?php esc_html_e( 'What’s Happening?', 'maker' ); ?></h1>

	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content/entry', get_post_format() );
	}
	?>
</main>
<?php
get_footer();
