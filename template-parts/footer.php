<?php
/**
 * Site footer.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="thw-footer" role="contentinfo">
	<div class="thw-container thw-footer__inner">
		<div class="thw-footer__brand">
			<strong><?php bloginfo( 'name' ); ?></strong>
			<p><?php bloginfo( 'description' ); ?></p>
		</div>
		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="thw-footer__nav" aria-label="<?php esc_attr_e( 'Footer', 'the-hidden-word-theme' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'thw-footer__list',
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>
		<p class="thw-footer__plugin">
			<?php
			$download_url = thw_theme_get_mod(
				'thw_theme_free_plugin_url',
				'https://wordpress.org/plugins/hidden-word-bible-lessons/'
			);
			printf(
				/* translators: %s: free plugin URL */
				wp_kses_post( __( 'Free for churches & organizations: <a href="%s" target="_blank" rel="noopener noreferrer">Hidden Word Bible Lessons</a>', 'the-hidden-word-theme' ) ),
				esc_url( $download_url )
			);
			?>
		</p>
		<p class="thw-footer__copy">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
			<a href="https://landtechwebdesigns.com"><?php esc_html_e( 'Land Tech Web Designs', 'the-hidden-word-theme' ); ?></a>
		</p>
	</div>
</footer>
