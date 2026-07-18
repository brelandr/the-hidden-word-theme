<?php
/**
 * Site header.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="thw-header" role="banner">
	<div class="thw-container thw-header__inner">
		<div class="thw-header__brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="thw-header__title" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php bloginfo( 'name' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<button class="thw-nav-toggle" type="button" aria-expanded="false" aria-controls="thw-primary-nav">
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'the-hidden-word-theme' ); ?></span>
			<span aria-hidden="true"></span>
		</button>
		<nav id="thw-primary-nav" class="thw-nav" aria-label="<?php esc_attr_e( 'Primary', 'the-hidden-word-theme' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'thw-nav__list',
					'depth'          => 2,
					'fallback_cb'    => 'thw_theme_fallback_menu',
				)
			);
			?>
		</nav>
	</div>
</header>
