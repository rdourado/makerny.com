<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package maker
 */

namespace Parafa\Maker;

?>

<nav class="mkr-nav" aria-label="<?php esc_attr_e( 'Main menu', 'maker' ); ?>"
	<?php if ( maker()->is_amp() ) { ?>
		[class]=" siteNavigationMenu.expanded ? 'mkr-nav mkr-nav--toggled' : 'mkr-nav' "
	<?php } ?>
>
	<?php if ( maker()->is_amp() ) { ?>
		<amp-state id="siteNavigationMenu">
			<script type="application/json">
				{ "expanded": false }
			</script>
		</amp-state>
	<?php } ?>

	<button class="mkr-nav__toggle" aria-label="<?php esc_attr_e( 'Open menu', 'maker' ); ?>" aria-controls="primary-menu" aria-expanded="false"
		<?php if ( maker()->is_amp() ) { ?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
		<?php } ?>
	>
		<?php esc_html_e( 'Menu', 'maker' ); ?>
	</button>

	<div class="mkr-nav__menu">
		<?php maker()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
		<?php get_template_part( 'template-parts/header/cases', 'nav' ); ?>
	</div>
</nav>
