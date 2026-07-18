<?php
/**
 * Features section.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

$features = array(
	array(
		'title' => thw_theme_schedule_phrase( 'memorize' ),
		'desc'  => __( 'Auto-scheduled weekly, daily, or monthly verses with text, historical context, and discussion questions.', 'the-hidden-word-theme' ),
	),
	array(
		'title' => __( 'Read the Bible', 'the-hidden-word-theme' ),
		'desc'  => __( 'Full chapter reader with translation, book, and chapter pickers plus Hello AO audio narration — no API key required.', 'the-hidden-word-theme' ),
	),
	array(
		'title' => __( 'Spaced memorization', 'the-hidden-word-theme' ),
		'desc'  => __( 'SM-2 spaced repetition with daily reviews, quality ratings, and practice modes: hide, recall, first-letter, and scramble.', 'the-hidden-word-theme' ),
	),
	array(
		'title' => __( 'Hello AO Bible API', 'the-hidden-word-theme' ),
		'desc'  => __( 'Free Use Bible API by AO Lab: open JSON on AWS, no rate limits, no registration. Powers extra translations and the chapter reader.', 'the-hidden-word-theme' ),
	),
	array(
		'title' => thw_theme_schedule_phrase( 'catalog' ),
		'desc'  => __( 'Browse all 500 verses by book, testament, or flat list with pagination and archive pages.', 'the-hidden-word-theme' ),
	),
	array(
		'title' => thw_theme_schedule_phrase( 'compact' ),
		'desc'  => __( 'Compact verse widget and shortcode for sidebars, homepages, and bulletin boards.', 'the-hidden-word-theme' ),
	),
);
?>
<section class="thw-section thw-features">
	<div class="thw-container">
		<h2 class="thw-section__title"><?php esc_html_e( 'Core highlights', 'the-hidden-word-theme' ); ?></h2>
		<p class="thw-section__lead thw-features__lead">
			<?php esc_html_e( 'Memorization, Bible reading, and Hello AO ship in Hidden Word Bible Lessons—ready for churches and organizations to run on their own WordPress site.', 'the-hidden-word-theme' ); ?>
		</p>
		<div class="thw-features__grid">
			<?php foreach ( $features as $feature ) : ?>
				<article class="thw-card">
					<h3 class="thw-card__title"><?php echo esc_html( $feature['title'] ); ?></h3>
					<p><?php echo esc_html( $feature['desc'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
