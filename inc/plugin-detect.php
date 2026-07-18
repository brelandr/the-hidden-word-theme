<?php
/**
 * Plugin detection helpers.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether The Hidden Word free plugin is active.
 *
 * @return bool
 */
function thw_theme_has_hidden_word() {
	return defined( 'HWBL_VERSION' )
		|| defined( 'THW_VERSION' )
		|| class_exists( 'HWBL_Plugin' )
		|| class_exists( 'THW_Plugin' );
}

/**
 * Whether advanced (formerly Premium) features are available.
 *
 * True for the integrated free plugin (HWBL_INTEGRATED_PREMIUM) or a licensed
 * standalone Premium install.
 *
 * @return bool
 */
function thw_theme_has_premium() {
	if ( defined( 'HWBL_INTEGRATED_PREMIUM' ) && HWBL_INTEGRATED_PREMIUM ) {
		return true;
	}

	return class_exists( 'THW_Premium_License' ) && THW_Premium_License::is_licensed();
}

/**
 * Whether advanced modules are loaded (integrated free or standalone Premium).
 *
 * @return bool
 */
function thw_theme_has_premium_plugin() {
	if ( defined( 'HWBL_INTEGRATED_PREMIUM' ) && HWBL_INTEGRATED_PREMIUM ) {
		return true;
	}

	return defined( 'THW_PREMIUM_VERSION' ) || class_exists( 'THW_Premium' );
}

/**
 * Whether Hello AO Bible API is enabled in the free plugin.
 *
 * @return bool
 */
function thw_theme_helloao_enabled() {
	return class_exists( 'HWBL_HelloAO_Provider' ) && HWBL_HelloAO_Provider::is_enabled();
}

/**
 * Whether the full chapter Bible reader is available.
 *
 * @return bool
 */
function thw_theme_bible_reader_available() {
	return class_exists( 'HWBL_Bible_Reader' ) && HWBL_Bible_Reader::is_enabled();
}
