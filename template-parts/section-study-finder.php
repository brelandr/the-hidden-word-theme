<?php
/**
 * Keyword study finder section.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

$study_ok = shortcode_exists( 'hwbl_study_finder' ) || shortcode_exists( 'thw_study_finder' );
if ( ! thw_theme_has_premium_plugin() || ! $study_ok ) {
	return;
}

$find_url = get_permalink( get_page_by_path( 'find-a-lesson', OBJECT, 'page' ) );
?>
<section class="thw-section thw-study-finder-section">
	<div class="thw-container">
		<h2 class="thw-section__title"><?php echo esc_html( thw_theme_schedule_phrase( 'find' ) ); ?></h2>
		<p class="thw-section__lead">
			<?php esc_html_e( 'Search the curriculum by topic—hope, peace, grief, divorce—and pick your faith tradition so AI recommendations follow curated digests and doctrinal sources when AI is enabled (including Non-denominational packs such as Destiny Leaders, ARC, and Churches In Covenant).', 'the-hidden-word-theme' ); ?>
		</p>
		<?php echo do_shortcode( '[hwbl_study_finder title="' . esc_attr__( 'Try a topic search', 'the-hidden-word-theme' ) . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( $find_url ) : ?>
			<p class="thw-section__link">
				<a class="thw-btn thw-btn-secondary" href="<?php echo esc_url( $find_url ); ?>">
					<?php esc_html_e( 'Open full study finder page', 'the-hidden-word-theme' ); ?>
				</a>
			</p>
		<?php endif; ?>
		<?php
		$ask_url = get_permalink( get_page_by_path( 'ask-a-question', OBJECT, 'page' ) );
		if ( $ask_url && ( shortcode_exists( 'hwbl_ask_question' ) || shortcode_exists( 'thw_ask_question' ) ) ) :
			?>
			<p class="thw-section__link">
				<a class="thw-btn thw-btn-secondary" href="<?php echo esc_url( $ask_url ); ?>">
					<?php esc_html_e( 'Ask a Bible question', 'the-hidden-word-theme' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>
