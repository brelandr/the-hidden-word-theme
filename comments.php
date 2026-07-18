<?php
/**
 * Comments template.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="thw-comments-area comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$count = get_comments_number();
			printf(
				/* translators: 1: comment count */
				esc_html( _n( '%1$s comment', '%1$s comments', $count, 'the-hidden-word-theme' ) ),
				esc_html( number_format_i18n( $count ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'the-hidden-word-theme' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>
