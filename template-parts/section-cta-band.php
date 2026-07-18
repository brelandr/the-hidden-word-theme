<?php
/**
 * CTA band.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

$download_url = thw_theme_get_mod(
	'thw_theme_free_plugin_url',
	'https://wordpress.org/plugins/hidden-word-bible-lessons/'
);
?>
<section class="thw-section thw-cta-band">
	<div class="thw-container thw-cta-band__inner">
		<h2><?php esc_html_e( 'Ready to disciple your congregation?', 'the-hidden-word-theme' ); ?></h2>
		<p><?php esc_html_e( 'Install Hidden Word Bible Lessons on your own WordPress site—memorization, Bible tools, AI faith-tradition study features, and more, completely free.', 'the-hidden-word-theme' ); ?></p>
		<div class="thw-cta-band__actions">
			<?php if ( $download_url ) : ?>
				<a class="thw-btn thw-btn--inverse" href="<?php echo esc_url( $download_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Get the free plugin', 'the-hidden-word-theme' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
