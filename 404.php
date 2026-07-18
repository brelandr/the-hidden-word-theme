<?php
/**
 * 404 template.
 *
 * @package The_Hidden_Word_Theme
 */

get_header();
?>

<main id="primary" class="thw-main thw-main--page">
	<div class="thw-container thw-content">
		<h1><?php esc_html_e( 'Page not found', 'the-hidden-word-theme' ); ?></h1>
		<p><?php esc_html_e( 'The page you requested could not be found.', 'the-hidden-word-theme' ); ?></p>
		<p><a class="thw-btn thw-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go home', 'the-hidden-word-theme' ); ?></a></p>
	</div>
</main>

<?php
get_footer();
