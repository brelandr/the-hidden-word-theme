<?php
/**
 * Navigation menus.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register nav menus.
 *
 * @return void
 */
function thw_theme_register_nav_menus() {
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'the-hidden-word-theme' ),
			'footer'  => __( 'Footer Menu', 'the-hidden-word-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'thw_theme_register_nav_menus' );

/**
 * Label for the memorization submenu group in primary navigation.
 *
 * @return string
 */
function thw_theme_memorize_nav_heading() {
	return __( 'Bible Verses to Memorize', 'the-hidden-word-theme' );
}

/**
 * Page slugs grouped under Bible Verses to Memorize.
 *
 * @return array<string, string> slug => default label.
 */
function thw_theme_memorize_nav_pages() {
	$pages = array(
		'todays-lesson'          => thw_theme_schedule_phrase( 'memorize' ),
		'memorize-a-verse'       => __( 'Memorize a Verse', 'the-hidden-word-theme' ),
		'memorization-reviews'   => __( 'Memorization Reviews', 'the-hidden-word-theme' ),
		'verse-of-the-week'      => thw_theme_schedule_phrase( 'compact' ),
		'lesson-catalog'         => thw_theme_schedule_phrase( 'catalog' ),
	);

	if ( thw_theme_has_premium_plugin() ) {
		$pages = array(
			'todays-lesson'          => thw_theme_schedule_phrase( 'memorize' ),
			'memorize-a-verse'       => __( 'Memorize a Verse', 'the-hidden-word-theme' ),
			'memorization-reviews'   => __( 'Memorization Reviews', 'the-hidden-word-theme' ),
			'verse-of-the-day'       => __( 'Verse of the Day', 'the-hidden-word-theme' ),
			'verse-of-the-week'      => thw_theme_schedule_phrase( 'compact' ),
			'lesson-catalog'         => thw_theme_schedule_phrase( 'catalog' ),
		);
	}

	return $pages;
}

/**
 * Render memorization submenu links for the fallback menu.
 *
 * @return void
 */
function thw_theme_render_memorize_submenu_items() {
	foreach ( thw_theme_memorize_nav_pages() as $slug => $label ) {
		$url = thw_theme_get_page_url( $slug );
		if ( ! $url ) {
			continue;
		}
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}

	$archive = get_post_type_archive_link( 'hwbl_lesson' );
	if ( ! $archive ) {
		$archive = get_post_type_archive_link( 'thw_lesson' );
	}
	if ( $archive ) {
		echo '<li><a href="' . esc_url( $archive ) . '">' . esc_html__( 'All Verses', 'the-hidden-word-theme' ) . '</a></li>';
	}
}

/**
 * Fallback primary menu when none assigned.
 *
 * @return void
 */
function thw_theme_fallback_menu() {
	echo '<ul class="thw-nav__list">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'the-hidden-word-theme' ) . '</a></li>';

	if ( thw_theme_has_hidden_word() ) {
		$parent_url = thw_theme_get_page_url( 'todays-lesson' );
		if ( ! $parent_url ) {
			$parent_url = '#';
		}

		echo '<li class="menu-item-has-children">';
		echo '<a href="' . esc_url( $parent_url ) . '">' . esc_html( thw_theme_memorize_nav_heading() ) . '</a>';
		echo '<ul class="sub-menu">';
		thw_theme_render_memorize_submenu_items();
		echo '</ul>';
		echo '</li>';

		$read_url = thw_theme_get_page_url( 'read-the-bible' );
		if ( $read_url ) {
			echo '<li><a href="' . esc_url( $read_url ) . '">' . esc_html__( 'Read the Bible', 'the-hidden-word-theme' ) . '</a></li>';
		}

		if ( thw_theme_has_premium_plugin() ) {
			$find_url = thw_theme_get_page_url( 'find-a-lesson' );
			if ( $find_url ) {
				echo '<li><a href="' . esc_url( $find_url ) . '">' . esc_html( thw_theme_schedule_phrase( 'find' ) ) . '</a></li>';
			}
			$ask_url = thw_theme_get_page_url( 'ask-a-question' );
			if ( $ask_url ) {
				echo '<li><a href="' . esc_url( $ask_url ) . '">' . esc_html__( 'Ask a Question', 'the-hidden-word-theme' ) . '</a></li>';
			}
		}
	}

	if ( is_user_logged_in() ) {
		echo '<li><a href="' . esc_url( wp_logout_url( home_url( '/' ) ) ) . '">' . esc_html__( 'Log out', 'the-hidden-word-theme' ) . '</a></li>';
	} else {
		$login_url = thw_theme_get_page_url( 'login' );
		if ( $login_url ) {
			echo '<li><a href="' . esc_url( $login_url ) . '">' . esc_html__( 'Log in', 'the-hidden-word-theme' ) . '</a></li>';
		}
		$register_url = thw_theme_get_page_url( 'register' );
		if ( $register_url ) {
			echo '<li><a href="' . esc_url( $register_url ) . '">' . esc_html__( 'Register', 'the-hidden-word-theme' ) . '</a></li>';
		}
	}

	echo '</ul>';
}
