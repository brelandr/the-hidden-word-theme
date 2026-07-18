<?php
/**
 * Single lesson template.
 *
 * @package The_Hidden_Word_Theme
 */

get_header();
?>

<main id="primary" class="thw-main thw-main--lesson">
	<div class="thw-container thw-container--wide thw-content">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'thw-article thw-article--lesson' ); ?>>
				<header class="thw-article__header">
					<h1 class="thw-article__title"><?php the_title(); ?></h1>
				</header>
				<div class="thw-article__body entry-content">
					<?php the_content(); ?>
				</div>
				<?php comments_template(); ?>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
