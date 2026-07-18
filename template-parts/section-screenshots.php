<?php
/**
 * Screenshots section.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

$screenshot_dir = WP_PLUGIN_DIR . '/the-hidden-word/docs/screenshots/';
$screenshots    = array();

if ( is_dir( $screenshot_dir ) ) {
	for ( $i = 1; $i <= 5; $i++ ) {
		$file = $screenshot_dir . 'screenshot-' . $i . '.png';
		if ( is_readable( $file ) ) {
			$screenshots[] = array(
				'url' => plugins_url( 'docs/screenshots/screenshot-' . $i . '.png', 'the-hidden-word/the-hidden-word.php' ),
				'alt' => sprintf(
					/* translators: %d: screenshot number */
					__( 'The Hidden Word screenshot %d', 'the-hidden-word-theme' ),
					$i
				),
			);
		}
	}
}
?>
<section class="thw-section thw-screenshots">
	<div class="thw-container">
		<h2 class="thw-section__title"><?php esc_html_e( 'See it in action', 'the-hidden-word-theme' ); ?></h2>
		<?php if ( ! empty( $screenshots ) ) : ?>
			<div class="thw-screenshots__grid">
				<?php foreach ( $screenshots as $shot ) : ?>
					<figure class="thw-screenshot">
						<img src="<?php echo esc_url( $shot['url'] ); ?>" alt="<?php echo esc_attr( $shot['alt'] ); ?>" loading="lazy" width="1200" height="900" />
					</figure>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Install The Hidden Word plugin to display product screenshots here.', 'the-hidden-word-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
