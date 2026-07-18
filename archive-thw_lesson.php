<?php
/**
 * Lesson archive template.
 *
 * @package The_Hidden_Word_Theme
 */

get_header();
?>

<main id="primary" class="thw-main thw-main--archive">
	<div class="thw-container thw-container--wide thw-content">
		<header class="thw-archive-header">
			<h1 class="thw-archive-header__title"><?php post_type_archive_title(); ?></h1>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="thw-archive-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'thw-archive-item' ); ?>>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No lessons found.', 'the-hidden-word-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
