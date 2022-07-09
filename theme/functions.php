<?php
if ( ! function_exists( 'uranus_support' ) ) {
	function uranus_support() {
		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		
		load_theme_textdomain( 'uranus' );
	}
}

add_action( 'after_setup_theme', 'uranus_support' );

if ( ! function_exists( 'uranus_styles' ) ) {
	function uranus_styles() {
		$theme_version = wp_get_theme()->get( 'Version' );
		wp_enqueue_style( 'uranus-style', get_stylesheet_uri(), array(), $theme_version );
		wp_style_add_data( 'uranus-style', 'rtl', 'replace' );
		// Add print css
		wp_enqueue_style( 'uranus-print-style', get_template_directory_uri() . '/print.css', array(), $theme_version, 'print' );
	}
}

add_action( 'wp_enqueue_scripts', 'uranus_styles' );

if ( ! function_exists( 'uranus_scripts' ) ) {
	function uranus_scripts() {
		$theme_version = wp_get_theme()->get( 'Version' );
		wp_enqueue_script( 'uranus-script', get_template_directory_uri() . '/assets/js/app.js', array(), $theme_version );
		wp_script_add_data( 'uranus-script', 'async', true );
	}
}
add_action( 'wp_enqueue_scripts', 'uranus_scripts' );