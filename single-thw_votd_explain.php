<?php
/**
 * Single VOTD explanation template.
 *
 * @package The_Hidden_Word_Theme
 */

get_header();
?>

<main id="primary" class="thw-main thw-main--page">
	<div class="thw-container thw-content">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'thw-article thw-article--votd-explain' ); ?>>
				<header class="thw-article__header">
					<h1 class="thw-article__title"><?php the_title(); ?></h1>
				</header>
				<div class="thw-article__body entry-content thw-votd-explain">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
