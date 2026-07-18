<?php
/**
 * The Hidden Word marketing theme bootstrap.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

define( 'THW_THEME_VERSION', '1.3.3' );
define( 'THW_THEME_DIR', get_template_directory() );
define( 'THW_THEME_URI', get_template_directory_uri() );

require_once THW_THEME_DIR . '/inc/plugin-detect.php';
require_once THW_THEME_DIR . '/inc/nav-menus.php';
require_once THW_THEME_DIR . '/inc/auth.php';
require_once THW_THEME_DIR . '/inc/customizer.php';
require_once THW_THEME_DIR . '/inc/marketing-setup.php';

/**
 * Theme setup.
 *
 * @return void
 */
function thw_theme_setup() {
	load_theme_textdomain( 'the-hidden-word-theme', THW_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 280,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	add_image_size( 'thw_theme_hero', 1920, 900, true );
	add_image_size( 'thw_theme_screenshot', 1200, 900, false );
}
add_action( 'after_setup_theme', 'thw_theme_setup' );

/**
 * Register widget areas.
 *
 * @return void
 */
function thw_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'the-hidden-word-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Optional widgets for demo and blog pages.', 'the-hidden-word-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'thw_theme_widgets_init' );

/**
 * Asset version from filemtime.
 *
 * @param string $relative_path Relative path from theme root.
 * @return string
 */
function thw_theme_asset_version( $relative_path ) {
	$path = THW_THEME_DIR . '/' . ltrim( $relative_path, '/' );
	if ( is_readable( $path ) ) {
		return (string) filemtime( $path );
	}
	return THW_THEME_VERSION;
}

/**
 * Enqueue front-end assets.
 *
 * @return void
 */
function thw_theme_enqueue_assets() {
	wp_enqueue_style(
		'the-hidden-word-theme',
		THW_THEME_URI . '/assets/css/theme.css',
		array(),
		thw_theme_asset_version( 'assets/css/theme.css' )
	);

	wp_enqueue_script(
		'the-hidden-word-theme-navigation',
		THW_THEME_URI . '/assets/js/navigation.js',
		array(),
		THW_THEME_VERSION,
		true
	);

	if ( is_page( 'read-the-bible' ) || ( is_singular( 'page' ) && has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'hwbl_bible_reader' ) ) ) {
		wp_enqueue_style(
			'the-hidden-word-theme-bible-reader',
			THW_THEME_URI . '/assets/css/bible-reader.css',
			array( 'the-hidden-word-theme' ),
			thw_theme_asset_version( 'assets/css/bible-reader.css' )
		);
	}

	if ( is_page( 'memorize-a-verse' ) || ( is_singular( 'page' ) && has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'hwbl_memorize_verse' ) ) ) {
		wp_enqueue_style(
			'the-hidden-word-theme-verse-memorize',
			THW_THEME_URI . '/assets/css/bible-reader.css',
			array( 'the-hidden-word-theme' ),
			thw_theme_asset_version( 'assets/css/bible-reader.css' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'thw_theme_enqueue_assets' );

/**
 * Body classes.
 *
 * @param array $classes Body classes.
 * @return array
 */
function thw_theme_body_classes( $classes ) {
	$classes[] = 'thw-site';

	if ( is_page_template( 'page-templates/full-width-lesson.php' ) ) {
		$classes[] = 'thw-site--lesson-full';
	}

	if ( is_page( 'read-the-bible' ) ) {
		$classes[] = 'thw-site--bible-reader';
	}

	if ( is_page( 'memorize-a-verse' ) ) {
		$classes[] = 'thw-site--verse-memorize';
	}

	if ( thw_theme_has_hidden_word() ) {
		$classes[] = 'thw-site--plugin-active';
	}

	if ( thw_theme_has_premium() ) {
		$classes[] = 'thw-site--premium-active';
	}

	return $classes;
}
add_filter( 'body_class', 'thw_theme_body_classes' );

/**
 * Customizer CSS variables.
 *
 * @return void
 */
function thw_theme_customizer_css() {
	$primary = sanitize_hex_color( get_theme_mod( 'thw_theme_primary_color', '#1B3A4B' ) );
	$accent  = sanitize_hex_color( get_theme_mod( 'thw_theme_accent_color', '#C9A227' ) );

	if ( ! $primary ) {
		$primary = '#1B3A4B';
	}
	if ( ! $accent ) {
		$accent = '#C9A227';
	}

	$css = sprintf(
		':root { --thw-primary: %1$s; --thw-accent: %2$s; }',
		$primary,
		$accent
	);

	wp_add_inline_style( 'the-hidden-word-theme', $css );
}
add_action( 'wp_enqueue_scripts', 'thw_theme_customizer_css', 20 );

/**
 * Get theme mod with default.
 *
 * @param string $key     Mod key.
 * @param string $default Default value.
 * @return string
 */
function thw_theme_get_mod( $key, $default = '' ) {
	$value = get_theme_mod( $key, $default );
	return is_string( $value ) ? $value : (string) $default;
}

/**
 * Schedule-aware front-end phrase (memorize / heading / compact / etc.).
 *
 * @param string $kind Phrase kind.
 * @return string
 */
function thw_theme_schedule_phrase( $kind = 'memorize' ) {
	if ( class_exists( 'HWBL_Scheduler' ) && method_exists( 'HWBL_Scheduler', 'get_schedule_phrase' ) ) {
		return HWBL_Scheduler::get_schedule_phrase( $kind );
	}

	$fallback = array(
		'memorize' => __( "Today's Verse to Memorize", 'the-hidden-word-theme' ),
		'heading'  => __( "Today's Verse", 'the-hidden-word-theme' ),
		'compact'  => __( 'Verse of the Week', 'the-hidden-word-theme' ),
		'blurb'    => __( 'Read and memorize this scripture.', 'the-hidden-word-theme' ),
		'catalog'  => __( 'Verse Catalog', 'the-hidden-word-theme' ),
		'find'     => __( 'Find a Verse', 'the-hidden-word-theme' ),
	);
	$kind = sanitize_key( (string) $kind );
	return isset( $fallback[ $kind ] ) ? $fallback[ $kind ] : $fallback['memorize'];
}

/**
 * Dynamically label schedule pages in nav menus.
 *
 * @param string   $title Menu item title.
 * @param WP_Post  $item  Menu item.
 * @return string
 */
function thw_theme_nav_menu_item_title( $title, $item ) {
	if ( ! ( $item instanceof WP_Post ) || empty( $item->object_id ) ) {
		return $title;
	}

	$page = get_post( (int) $item->object_id );
	if ( ! ( $page instanceof WP_Post ) || 'page' !== $page->post_type ) {
		return $title;
	}

	$map = array(
		'todays-lesson'     => 'memorize',
		'verse-of-the-week' => 'compact',
		'lesson-catalog'    => 'catalog',
		'find-a-lesson'     => 'find',
	);

	if ( isset( $map[ $page->post_name ] ) ) {
		return thw_theme_schedule_phrase( $map[ $page->post_name ] );
	}

	return $title;
}
add_filter( 'nav_menu_item_title', 'thw_theme_nav_menu_item_title', 10, 2 );

/**
 * Dynamically label schedule page titles on the front end.
 *
 * @param string $title   Post title.
 * @param int    $post_id Post ID.
 * @return string
 */
function thw_theme_filter_schedule_page_title( $title, $post_id = 0 ) {
	if ( is_admin() || ! $post_id ) {
		return $title;
	}

	$page = get_post( (int) $post_id );
	if ( ! ( $page instanceof WP_Post ) || 'page' !== $page->post_type ) {
		return $title;
	}

	$map = array(
		'todays-lesson'     => 'memorize',
		'verse-of-the-week' => 'compact',
		'lesson-catalog'    => 'catalog',
		'find-a-lesson'     => 'find',
	);

	if ( isset( $map[ $page->post_name ] ) ) {
		return thw_theme_schedule_phrase( $map[ $page->post_name ] );
	}

	return $title;
}
add_filter( 'the_title', 'thw_theme_filter_schedule_page_title', 10, 2 );
