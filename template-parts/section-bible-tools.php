<?php
/**
 * Bible reader and API tools promo (homepage).
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! thw_theme_has_hidden_word() ) {
	return;
}

$reader_url = thw_theme_get_page_url( 'read-the-bible' );
$votd_url   = thw_theme_has_premium_plugin() ? thw_theme_get_page_url( 'verse-of-the-day' ) : '';
?>
<section class="thw-section thw-bible-tools" id="bible-tools">
	<div class="thw-container">
		<h2 class="thw-section__title"><?php esc_html_e( 'Read & listen to Scripture', 'the-hidden-word-theme' ); ?></h2>
		<p class="thw-bible-tools__lead">
			<?php esc_html_e( 'The free plugin connects to the Hello AO Free Use Bible API — no keys, no rate limits, hosted on AWS. Read any chapter, hear narration when available, and optionally add your own Biblia.com or API.Bible keys for more translations.', 'the-hidden-word-theme' ); ?>
		</p>
		<div class="thw-features__grid thw-bible-tools__grid">
			<article class="thw-card thw-card--highlight">
				<h3 class="thw-card__title"><?php esc_html_e( 'Read the Bible', 'the-hidden-word-theme' ); ?></h3>
				<p><?php esc_html_e( 'Pick any book and chapter. Switch translations, listen to chapter audio, and move prev/next through the Bible.', 'the-hidden-word-theme' ); ?></p>
				<?php if ( $reader_url ) : ?>
					<p><a class="thw-btn thw-btn--primary" href="<?php echo esc_url( $reader_url ); ?>"><?php esc_html_e( 'Open Bible reader', 'the-hidden-word-theme' ); ?></a></p>
				<?php endif; ?>
			</article>
			<article class="thw-card">
				<h3 class="thw-card__title"><?php esc_html_e( 'Hello AO (free)', 'the-hidden-word-theme' ); ?></h3>
				<p>
					<?php
					printf(
						/* translators: %s: Hello AO URL */
						wp_kses_post( __( 'Open-source JSON API from AO Lab at <a href="%s" target="_blank" rel="noopener noreferrer">bible.helloao.org</a>. Powers verse text, chapter reading, and audio in the free plugin.', 'the-hidden-word-theme' ) ),
						esc_url( 'https://bible.helloao.org/' )
					);
					?>
				</p>
			</article>
			<?php if ( $votd_url ) : ?>
				<article class="thw-card">
					<h3 class="thw-card__title"><?php esc_html_e( 'Verse of the Day', 'the-hidden-word-theme' ); ?></h3>
					<p><?php esc_html_e( 'Daily verse from Bible.com or your curriculum schedule, with your chosen translation and optional AI explain—included in the free plugin.', 'the-hidden-word-theme' ); ?></p>
					<p><a class="thw-btn thw-btn--outline" href="<?php echo esc_url( $votd_url ); ?>"><?php esc_html_e( 'Verse of the Day page', 'the-hidden-word-theme' ); ?></a></p>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>
