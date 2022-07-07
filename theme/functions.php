<?php
if ( ! function_exists( 'uranus_support' ) ) {
	function uranus_support() {
		add_theme_support( 'wp-block-styles' );
		add_editor_style( 'style.css' );
	}
}

add_action( 'after_setup_theme', 'uranus_support' );

if ( ! function_exists( 'uranus_styles' ) ) {
	function uranus_styles() {
		$theme_version  = wp_get_theme()->get( 'Version' );
		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style( 'uranus-style', get_template_directory_uri() . '/style.css', array(), $version_string );
		wp_enqueue_style( 'venus-style' );
	}
}

add_action( 'wp_enqueue_scripts', 'uranus_styles' );

if ( ! function_exists( 'uranus_scripts' ) ) {
	function uranus_scripts() {
		$theme_version  = wp_get_theme()->get( 'Version' );
		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_script( 'uranus-script', get_template_directory_uri() . '/assets/js/app.js', array(), $version_string );
		wp_enqueue_script( 'venus-script' );
	}
}
add_action( 'wp_enqueue_scripts', 'uranus_scripts' );

require get_template_directory() . '/inc/block-patterns.php';