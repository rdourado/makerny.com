<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until main content
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package maker
 */

namespace Parafa\Maker;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php if ( ! maker()->is_amp() ) { ?>
		<script>
			function debounce(func, wait, immediate) {
				var timeout;
				return function() {
					var context = this, args = arguments;
					var later = function() {
						timeout = null;
						if (!immediate) func.apply(context, args);
					};
					var callNow = immediate && !timeout;
					clearTimeout(timeout);
					timeout = setTimeout(later, wait);
					if (callNow) func.apply(context, args);
				};
			};
			(function(w, h){
				function setVP() {
					h.style.setProperty('--vh', (w.innerHeight / 1600) + 'rem');
					h.style.setProperty('--vw', (w.innerWidth / 1600) + 'rem');
				}
				w.addEventListener('resize', debounce(setVP, 200));
				h.classList.remove( 'no-js' );
				setVP();
			})(window, document.documentElement)
		</script>
	<?php } ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'mkr' ); ?>>
	<?php wp_body_open(); ?>

	<div id="grid">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
	</div>

	<header class="mkr-header">
		<?php get_template_part( 'template-parts/header/branding' ); ?>
		<?php get_template_part( 'template-parts/header/navigation' ); ?>
	</header>
