<?php
/**
 * Template Name: Auth (Login / Register)
 * Description: Centered layout for login and registration forms.
 *
 * @package The_Hidden_Word_Theme
 */

get_header();
?>

<main id="primary" class="thw-main thw-main--auth">
	<div class="thw-container thw-auth-page">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'thw-auth-card' ); ?>>
				<header class="thw-auth-card__header">
					<h1 class="thw-auth-card__title"><?php the_title(); ?></h1>
				</header>
				<div class="thw-auth-card__body entry-content">
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
