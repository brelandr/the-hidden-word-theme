<?php
/**
 * Included advanced features (all part of the free plugin).
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

$download_url = thw_theme_get_mod(
	'thw_theme_free_plugin_url',
	'https://wordpress.org/plugins/hidden-word-bible-lessons/'
);

$included_features = array(
	__( 'Verse of the Day — Bible.com or site curriculum, with translation picker', 'the-hidden-word-theme' ),
	__( 'Bible API providers: Hello AO (free), plus optional Biblia.com and API.Bible (BYOK)', 'the-hidden-word-theme' ),
	__( 'Multi-translation switcher (ESV, NLT, NASB, BSB, WEB, and more)', 'the-hidden-word-theme' ),
	__( 'Custom reading tracks and schedule modes (week, day, month, manual)', 'the-hidden-word-theme' ),
	__( 'Small-group cohorts with leader rosters and engagement tools', 'the-hidden-word-theme' ),
	__( 'Account progress, badges, and streak sync', 'the-hidden-word-theme' ),
	__( 'Email lesson and Verse of the Day digest subscriptions', 'the-hidden-word-theme' ),
	__( 'Audio, video, and leader guide PDF exports', 'the-hidden-word-theme' ),
	__( 'AI Explain, Study Finder, and Ask a Question with faith-tradition routing', 'the-hidden-word-theme' ),
	__( 'Faith tradition packs: Catholic, SBC, Methodist, Pentecostal, UPCI, Orthodox, and more', 'the-hidden-word-theme' ),
	__( 'Non-denominational packs including Destiny Leaders, ARC, and Churches In Covenant', 'the-hidden-word-theme' ),
	__( 'Visitor faith-tradition picker with curated digests and doctrinal stance gates', 'the-hidden-word-theme' ),
);
?>
<section class="thw-section thw-premium">
	<div class="thw-container thw-premium__grid">
		<div>
			<p class="thw-premium__eyebrow"><?php esc_html_e( 'Included free', 'the-hidden-word-theme' ); ?></p>
			<h2 class="thw-section__title"><?php esc_html_e( 'Study tools built into the free plugin', 'the-hidden-word-theme' ); ?></h2>
			<p><?php esc_html_e( 'AI shaped by your faith tradition, flexible scheduling, digests, progress tracking, and more ship inside Hidden Word Bible Lessons—no separate purchase or add-on.', 'the-hidden-word-theme' ); ?></p>
			<?php if ( $download_url ) : ?>
				<p class="thw-premium__cta">
					<a class="thw-btn thw-btn--primary" href="<?php echo esc_url( $download_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Get the free plugin', 'the-hidden-word-theme' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
		<ul class="thw-premium__list">
			<?php foreach ( $included_features as $item ) : ?>
				<li><?php echo esc_html( $item ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
