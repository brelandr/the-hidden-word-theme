<?php
/**
 * Free WordPress plugin callout for churches and organizations.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

$download_url = thw_theme_get_mod(
	'thw_theme_free_plugin_url',
	'https://wordpress.org/plugins/hidden-word-bible-lessons/'
);
?>
<section class="thw-section thw-free-for-churches">
	<div class="thw-container thw-free-for-churches__inner">
		<p class="thw-free-for-churches__eyebrow"><?php esc_html_e( '100% free for your site', 'the-hidden-word-theme' ); ?></p>
		<h2 class="thw-section__title"><?php esc_html_e( 'Free WordPress plugin for churches and organizations', 'the-hidden-word-theme' ); ?></h2>
		<p class="thw-free-for-churches__lead">
			<?php esc_html_e( 'Hidden Word Bible Lessons is a 100% free WordPress plugin you can install on your own church, ministry, school, or nonprofit site. Memorization, Bible reading, Verse of the Day, AI study tools, digests, and group features are all included—no separate add-on required.', 'the-hidden-word-theme' ); ?>
		</p>
		<ul class="thw-free-for-churches__points">
			<li><?php esc_html_e( 'Install from WordPress.org and keep full control of your content and hosting', 'the-hidden-word-theme' ); ?></li>
			<li><?php esc_html_e( 'Includes AI Explain, Study Finder, Ask a Question, Verse of the Day, and more', 'the-hidden-word-theme' ); ?></li>
			<li><?php esc_html_e( 'Built for congregations, small groups, and discipleship ministries', 'the-hidden-word-theme' ); ?></li>
		</ul>
		<div class="thw-free-for-churches__actions">
			<?php if ( $download_url ) : ?>
				<a class="thw-btn thw-btn--primary" href="<?php echo esc_url( $download_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Get the free plugin', 'the-hidden-word-theme' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
