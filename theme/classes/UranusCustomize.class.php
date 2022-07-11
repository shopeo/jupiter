<?php

if ( ! class_exists( 'UranusCustomize' ) ) {
	class UranusCustomize {
		public static function register( $wp_customize ) {
			$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => 'uranus_customize_partial_blogname',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => 'uranus_customize_partial_blogdescription',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'custom_logo',
				array(
					'selector'            => '.header-titles [class*=site-]:not(.site-description)',
					'render_callback'     => 'uranus_customize_partial_site_logo',
					'container_inclusive' => true,
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'retina_logo',
				array(
					'selector'        => '.header-titles [class*=site-]:not(.site-description)',
					'render_callback' => 'uranus_customize_partial_site_logo',
				)
			);

			$wp_customize->add_setting(
				'retina_logo',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'retina_logo',
				array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'priority'    => 10,
					'label'       => __( 'Retina logo', 'uranus' ),
					'description' => __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'uranus' ),
				)
			);

			/**
			 * Header Options
			 */
			$wp_customize->add_section( 'header', array(
				'title'      => __( 'Header', 'venus' ),
				'priority'   => 80,
				'capability' => 'edit_theme_options',
			) );

			$wp_customize->add_setting( 'enable_header_search', array(
				'capability'        => 'edit_theme_options',
				'default'           => false,
				'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
			) );

			$wp_customize->add_control( 'enable_header_search', array(
				'type'     => 'checkbox',
				'section'  => 'header',
				'priority' => 10,
				'label'    => __( 'Show search in header', 'venus' ),
			) );
		}

		public static function sanitize_accent_accessible_colors( $value ) {
			$value = is_array( $value ) ? $value : array();

			// Loop values.
			foreach ( $value as $area => $values ) {
				foreach ( $values as $context => $color_val ) {
					$value[ $area ][ $context ] = sanitize_hex_color( $color_val );
				}
			}

			return $value;
		}

		public static function sanitize_select( $input, $setting ) {
			$input   = sanitize_key( $input );
			$choices = $setting->manager->get_control( $setting->id )->choices;

			return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
		}

		public static function sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true === $checked ) ? true : false );
		}
	}

	add_action( 'customize_register', array( 'UranusCustomize', 'register' ) );
}

if ( ! function_exists( 'uranus_customize_partial_blogname' ) ) {
	function uranus_customize_partial_blogname() {
		bloginfo( 'name' );
	}
}

if ( ! function_exists( 'uranus_customize_partial_blogdescription' ) ) {
	function uranus_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
}

if ( ! function_exists( 'uranus_customize_partial_site_logo' ) ) {
	function uranus_customize_partial_site_logo() {
		uranus_site_logo();
	}
}

if ( ! function_exists( 'uranus_customize_opacity_range' ) ) {
	function uranus_customize_opacity_range() {
		return apply_filters(
			'uranus_customize_opacity_range',
			array(
				'min'  => 0,
				'max'  => 90,
				'step' => 5,
			)
		);
	}
}