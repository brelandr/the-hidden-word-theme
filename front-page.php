<?php
/**
 * Front page template.
 *
 * @package The_Hidden_Word_Theme
 */

get_header();
?>

<main id="primary" class="thw-main">
	<?php get_template_part( 'template-parts/hero', 'home' ); ?>
	<?php get_template_part( 'template-parts/section', 'todays-verse' ); ?>
	<?php get_template_part( 'template-parts/section', 'free-for-churches' ); ?>
	<?php get_template_part( 'template-parts/section', 'bible-tools' ); ?>
	<?php get_template_part( 'template-parts/section', 'features' ); ?>
	<?php get_template_part( 'template-parts/section', 'study-finder' ); ?>
	<?php get_template_part( 'template-parts/section', 'free-vs-premium' ); ?>
	<?php get_template_part( 'template-parts/section', 'screenshots' ); ?>
	<?php get_template_part( 'template-parts/section', 'cta-band' ); ?>
</main>

<?php
get_footer();
