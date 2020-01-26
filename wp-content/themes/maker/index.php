<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package maker
 */

namespace Parafa\Maker;

get_header();
?>
<main class="mkr-main">
	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content/case', get_post_format() );
	}
	?>
</main>
<?php
get_footer();
