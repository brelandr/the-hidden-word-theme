<?php
/**
 * Blog index fallback.
 *
 * @package The_Hidden_Word_Theme
 */

get_header();
?>

<main id="primary" class="thw-main thw-main--page">
	<div class="thw-container thw-content">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'thw-article' ); ?>>
					<h2 class="thw-article__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="thw-article__body entry-content">
						<?php the_excerpt(); ?>
					</div>
				</article>
				<?php
			endwhile;
			the_posts_navigation();
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'No posts found.', 'the-hidden-word-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
