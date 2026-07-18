<?php
/**
 * Today's verse section for marketing homepage.
 *
 * Prefers Bible.com Verse of the Day when available;
 * falls back to the scheduled curriculum verse.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! thw_theme_has_hidden_word() ) {
	return;
}

$lesson_url    = thw_theme_get_page_url( 'todays-lesson' );
$verse_url     = thw_theme_get_page_url( 'verse-of-the-week' );
$reader_url    = thw_theme_get_page_url( 'read-the-bible' );
$votd_page_url = thw_theme_has_premium_plugin() ? thw_theme_get_page_url( 'verse-of-the-day' ) : '';
$votd_tag      = shortcode_exists( 'hwbl_verse_of_the_day' )
	? 'hwbl_verse_of_the_day'
	: ( shortcode_exists( 'thw_verse_of_the_day' ) ? 'thw_verse_of_the_day' : '' );
$use_bible_com = thw_theme_has_premium() && '' !== $votd_tag;
$memorize_label = thw_theme_schedule_phrase( 'memorize' );
$heading_label  = thw_theme_schedule_phrase( 'heading' );
$compact_label  = thw_theme_schedule_phrase( 'compact' );
$blurb          = thw_theme_schedule_phrase( 'blurb' );
?>
<section class="thw-section thw-todays-verse" id="todays-verse">
	<div class="thw-container">
		<div class="thw-todays-verse__header">
			<?php if ( $use_bible_com ) : ?>
				<h2 class="thw-section__title"><?php esc_html_e( 'Verse of the Day', 'the-hidden-word-theme' ); ?></h2>
				<p>
					<?php
					printf(
						/* translators: %s: schedule-aware memorize CTA label */
						esc_html__( 'Today’s scripture from Bible.com — read it here, then open %s when you are ready.', 'the-hidden-word-theme' ),
						esc_html( $memorize_label )
					);
					?>
				</p>
			<?php else : ?>
				<h2 class="thw-section__title"><?php echo esc_html( $heading_label ); ?></h2>
				<p><?php echo esc_html( $blurb ); ?></p>
			<?php endif; ?>
		</div>
		<div class="thw-todays-verse__card<?php echo $use_bible_com ? ' thw-todays-verse__card--votd' : ''; ?>">
			<?php
			if ( $use_bible_com ) {
				$votd_html = do_shortcode( '[' . $votd_tag . ']' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				if ( false !== strpos( $votd_html, 'thw-votd-notice' ) && shortcode_exists( 'hwbl_verse_of_week' ) ) {
					echo do_shortcode( '[hwbl_verse_of_week]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					echo $votd_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			} elseif ( shortcode_exists( 'hwbl_verse_of_week' ) ) {
				echo do_shortcode( '[hwbl_verse_of_week]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
		<div class="thw-todays-verse__actions">
			<?php if ( $lesson_url ) : ?>
				<a class="thw-btn thw-btn--primary" href="<?php echo esc_url( $lesson_url ); ?>">
					<?php echo esc_html( $memorize_label ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $reader_url ) : ?>
				<a class="thw-btn thw-btn--outline" href="<?php echo esc_url( $reader_url ); ?>">
					<?php esc_html_e( 'Read the Bible', 'the-hidden-word-theme' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $use_bible_com ) : ?>
				<?php if ( $votd_page_url ) : ?>
					<a class="thw-btn thw-btn--outline" href="<?php echo esc_url( $votd_page_url ); ?>">
						<?php esc_html_e( 'Full Verse of the Day', 'the-hidden-word-theme' ); ?>
					</a>
				<?php endif; ?>
				<a class="thw-btn thw-btn--outline" href="https://www.bible.com/verse-of-the-day" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'On Bible.com', 'the-hidden-word-theme' ); ?>
				</a>
			<?php elseif ( $verse_url ) : ?>
				<a class="thw-btn thw-btn--outline" href="<?php echo esc_url( $verse_url ); ?>">
					<?php echo esc_html( $compact_label ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
