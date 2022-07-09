<?php
if ( ! function_exists( 'uranus_support' ) ) {
	function uranus_support() {
		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'custom-background', array(
			'default-color' => 'F5EFE0',
		) );

		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 640;
		}

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 9999 );
		add_image_size( 'uranus-fullscreen', 1980, 9999 );

		$logo_width  = 120;
		$logo_height = 90;

		if ( get_theme_mod( 'retina_logo', false ) ) {
			$logo_width  = floor( $logo_width * 2 );
			$logo_height = floor( $logo_height * 2 );
		}

		add_theme_support( 'custom-logo', array(
			'height'      => $logo_height,
			'width'       => $logo_width,
			'flex-height' => true,
			'flex-width'  => true,
		) );

		add_theme_support( 'title-tag' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
				'navigation-widgets',
			)
		);

		load_theme_textdomain( 'uranus' );

		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );

		if ( is_customize_preview() ) {

		}

		add_theme_support( 'customize-selective-refresh-widgets' );

		$loader = new UranusScriptLoader();
		add_filter( 'script_loader_tag', array( $loader, 'filter_script_loader_tag' ), 10, 2 );
	}
}

add_action( 'after_setup_theme', 'uranus_support' );

require_once get_template_directory() . '/classes/UranusScriptLoader.class.php';
require_once get_template_directory() . '/classes/UranusNonLatinLanguages.class.php';
require_once get_template_directory() . '/inc/custom-css.php';

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
		wp_enqueue_script( 'uranus-script', get_template_directory_uri() . '/assets/js/app.js', array( 'jquery' ), $theme_version );
		wp_script_add_data( 'uranus-script', 'async', true );
	}
}

add_action( 'wp_enqueue_scripts', 'uranus_scripts' );

if ( ! function_exists( 'uranus_skip_link_focus_fix' ) ) {
	function uranus_skip_link_focus_fix() {
		?>
        <script>
            /(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function () {
                var t, e = location.hash.substring(1);
                /^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus())
            }, !1);
        </script>
		<?php
	}
}

add_action( 'wp_print_footer_scripts', 'uranus_skip_link_focus_fix' );

if ( ! function_exists( 'uranus_non_latin_languages' ) ) {
	function uranus_non_latin_languages() {
		$custom_css = UranusNonLatinLanguages::get_non_latin_css( 'front-end' );

		if ( $custom_css ) {
			wp_add_inline_style( 'venus-style', $custom_css );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'uranus_non_latin_languages' );

if ( ! function_exists( 'uranus_menus' ) ) {
	function uranus_menus() {
		$locations = array(
			'primary' => __( 'Primary', 'uranus' )
		);
		register_nav_menus( $locations );
	}
}

add_action( 'init', 'uranus_menus' );

