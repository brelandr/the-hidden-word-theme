<?php
/**
 * Theme Customizer settings.
 *
 * @package The_Hidden_Word_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 * @return void
 */
function thw_theme_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'thw_theme_marketing',
		array(
			'title'    => __( 'The Hidden Word Marketing', 'the-hidden-word-theme' ),
			'priority' => 30,
		)
	);

	$settings = array(
		'thw_theme_hero_heading'    => array(
			'default' => __( 'Hide God\'s Word in your heart.', 'the-hidden-word-theme' ),
			'label'   => __( 'Hero heading', 'the-hidden-word-theme' ),
			'type'    => 'text',
		),
		'thw_theme_tagline'         => array(
			'default' => __( 'Bible discipleship for churches and small groups.', 'the-hidden-word-theme' ),
			'label'   => __( 'Hero tagline', 'the-hidden-word-theme' ),
			'type'    => 'text',
		),
		'thw_theme_free_plugin_url' => array(
			'default' => 'https://wordpress.org/plugins/hidden-word-bible-lessons/',
			'label'   => __( 'Plugin download URL', 'the-hidden-word-theme' ),
			'type'    => 'url',
		),
		'thw_theme_primary_color'   => array(
			'default' => '#1B3A4B',
			'label'   => __( 'Primary color', 'the-hidden-word-theme' ),
			'type'    => 'color',
		),
		'thw_theme_accent_color'    => array(
			'default' => '#C9A227',
			'label'   => __( 'Accent color', 'the-hidden-word-theme' ),
			'type'    => 'color',
		),
	);

	foreach ( $settings as $id => $config ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $config['default'],
				'sanitize_callback' => 'color' === $config['type'] ? 'sanitize_hex_color' : ( 'url' === $config['type'] ? 'esc_url_raw' : 'sanitize_text_field' ),
				'transport'         => 'refresh',
			)
		);

		if ( 'color' === $config['type'] ) {
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$id,
					array(
						'label'   => $config['label'],
						'section' => 'thw_theme_marketing',
					)
				)
			);
		} else {
			$wp_customize->add_control(
				$id,
				array(
					'label'   => $config['label'],
					'section' => 'thw_theme_marketing',
					'type'    => $config['type'],
				)
			);
		}
	}
}
add_action( 'customize_register', 'thw_theme_customize_register' );
