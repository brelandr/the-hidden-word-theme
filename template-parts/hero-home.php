<?php
/**
 * Homepage hero.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

$heading      = thw_theme_get_mod( 'thw_theme_hero_heading', __( 'Hide God\'s Word in your heart.', 'the-hidden-word-theme' ) );
$tagline      = thw_theme_get_mod( 'thw_theme_tagline', __( 'Bible discipleship for churches and small groups.', 'the-hidden-word-theme' ) );
$download_url = thw_theme_get_mod(
	'thw_theme_free_plugin_url',
	'https://wordpress.org/plugins/hidden-word-bible-lessons/'
);
$lesson_url   = thw_theme_has_hidden_word() ? thw_theme_get_page_url( 'todays-lesson' ) : '';
$reader_url   = thw_theme_has_hidden_word() ? thw_theme_get_page_url( 'read-the-bible' ) : '';
?>
<section class="thw-hero">
	<div class="thw-container thw-hero__content">
		<p class="thw-hero__eyebrow"><?php echo esc_html( $tagline ); ?></p>
		<h1 class="thw-hero__title"><?php echo esc_html( $heading ); ?></h1>
		<p class="thw-hero__lead">
			<?php esc_html_e( 'A 100% free WordPress plugin for churches and organizations—500 verses, spaced memorization, Bible reader, Verse of the Day, AI study tools with faith-tradition routing, digests, and more. Everything in one plugin.', 'the-hidden-word-theme' ); ?>
		</p>
		<div class="thw-hero__actions">
			<?php if ( $lesson_url ) : ?>
				<a class="thw-btn thw-btn--primary" href="<?php echo esc_url( $lesson_url ); ?>">
					<?php echo esc_html( thw_theme_schedule_phrase( 'memorize' ) ); ?>
				</a>
			<?php elseif ( $download_url ) : ?>
				<a class="thw-btn thw-btn--primary" href="<?php echo esc_url( $download_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Get the free plugin', 'the-hidden-word-theme' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $reader_url ) : ?>
				<a class="thw-btn thw-btn--outline thw-btn--on-dark" href="<?php echo esc_url( $reader_url ); ?>">
					<?php esc_html_e( 'Read the Bible', 'the-hidden-word-theme' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $download_url && $lesson_url ) : ?>
				<a class="thw-btn thw-btn--outline thw-btn--on-dark" href="<?php echo esc_url( $download_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Get the free plugin', 'the-hidden-word-theme' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<div class="thw-hero__stats">
			<div class="thw-hero__stat">
				<strong>500</strong>
				<span><?php esc_html_e( 'Verses to memorize', 'the-hidden-word-theme' ); ?></span>
			</div>
			<div class="thw-hero__stat">
				<strong><?php esc_html_e( '100%', 'the-hidden-word-theme' ); ?></strong>
				<span><?php esc_html_e( 'Free plugin', 'the-hidden-word-theme' ); ?></span>
			</div>
			<div class="thw-hero__stat">
				<strong><?php esc_html_e( 'AI', 'the-hidden-word-theme' ); ?></strong>
				<span><?php esc_html_e( 'Faith-tradition tools', 'the-hidden-word-theme' ); ?></span>
			</div>
		</div>
	</div>
</section>
