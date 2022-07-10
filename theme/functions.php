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

require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/classes/UranusCustomize.class.php';
require_once get_template_directory() . '/classes/UranusSeparatorControl.class.php';
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
			wp_add_inline_style( 'uranus-style', $custom_css );
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

if ( ! function_exists( 'uranus_get_custom_logo' ) ) {
	function uranus_get_custom_logo( $html ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( ! $logo_id ) {
			return $html;
		}
		$logo = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $logo ) {
			$logo_width  = esc_attr( $logo[1] );
			$logo_height = esc_attr( $logo[2] );

			if ( get_theme_mod( 'retina_logo', false ) ) {
				$logo_width  = floor( $logo_width / 2 );
				$logo_height = floor( $logo_height / 2 );
				$search      = array(
					'/width=\"\d+\"/iU',
					'/height=\"\d+\"/iU',
				);

				$replace = array(
					"width=\"{$logo_width}\"",
					"height=\"{$logo_height}\"",
				);

				if ( strpos( $html, ' style=' ) === false ) {
					$search[]  = '/(src=)/';
					$replace[] = "style=\"height: {$logo_height}px;\" src=";
				} else {
					$search[]  = '/(style="[^"]*)/';
					$replace[] = "$1 height: {$logo_height}px;";
				}

				$html = preg_replace( $search, $replace, $html );
			}
		}

		return $html;
	}
}

add_filter( 'get_custom_logo', 'uranus_get_custom_logo' );

if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if ( ! function_exists( 'uranus_customize_controls_enqueue_scripts' ) ) {
	function uranus_customize_controls_enqueue_scripts() {
		$theme_version = wp_get_theme()->get( 'Version' );
		wp_enqueue_script( 'uranus-customize', get_template_directory_uri() . '/assets/js/customize.js', array( 'jquery' ), $theme_version, false );
		wp_enqueue_script( 'uranus-color-calculations', get_template_directory_uri() . '/assets/js/color-calculations.js', array( 'wp-color-picker' ), $theme_version, false );
		wp_enqueue_script( 'uranus-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array(
			'uranus-color-calculations',
			'customize-controls',
			'underscore',
			'jquery'
		), $theme_version, false );
		wp_localize_script( 'uranus-customize-controls', 'uranusBgColors', uranus_get_customizer_color_vars() );
	}
}

add_action( 'customize_controls_enqueue_scripts', 'uranus_customize_controls_enqueue_scripts' );

if ( ! function_exists( 'uranus_customize_preview_init' ) ) {
	function uranus_customize_preview_init() {
		$theme_version = wp_get_theme()->get( 'Version' );
		wp_enqueue_script( 'uranus-customize-preview', get_theme_file_uri( '/assets/js/customize-preview.js' ), array(
			'customize-preview',
			'customize-selective-refresh',
			'jquery'
		), $theme_version, true );
		wp_localize_script( 'uranus-customize-preview', 'uranusBgColors', uranus_get_customizer_color_vars() );
		wp_localize_script( 'uranus-customize-preview', 'uranusPreviewEls', uranus_get_elements_array() );
		wp_add_inline_script( 'uranus-customize-preview',
			sprintf(
				'wp.customize.selectiveRefresh.partialConstructor[ %1$s ].prototype.attrs = %2$s;',
				wp_json_encode( 'cover_opacity' ),
				wp_json_encode( uranus_customize_opacity_range() )
			)
		);
	}
}

add_action( 'customize_preview_init', 'uranus_customize_preview_init' );

if ( ! function_exists( 'uranus_get_color_for_area' ) ) {
	function uranus_get_color_for_area( $area = 'content', $context = 'text' ) {

		// Get the value from the theme-mod.
		$settings = get_theme_mod(
			'accent_accessible_colors',
			array(
				'content'       => array(
					'text'      => '#000000',
					'accent'    => '#cd2653',
					'secondary' => '#6d6d6d',
					'borders'   => '#dcd7ca',
				),
				'header-footer' => array(
					'text'      => '#000000',
					'accent'    => '#cd2653',
					'secondary' => '#6d6d6d',
					'borders'   => '#dcd7ca',
				),
			)
		);

		// If we have a value return it.
		if ( isset( $settings[ $area ] ) && isset( $settings[ $area ][ $context ] ) ) {
			return $settings[ $area ][ $context ];
		}

		// Return false if the option doesn't exist.
		return false;
	}
}

if ( ! function_exists( 'uranus_get_customizer_color_vars' ) ) {
	function uranus_get_customizer_color_vars() {
		$colors = array(
			'content'       => array(
				'setting' => 'background_color',
			),
			'header-footer' => array(
				'setting' => 'header_footer_background_color',
			),
		);

		return $colors;
	}
}

if ( ! function_exists( 'uranus_get_elements_array' ) ) {
	function uranus_get_elements_array() {
		$elements = array(
			'content'       => array(
				'accent'     => array(
					'color'            => array(
						'.color-accent',
						'.color-accent-hover:hover',
						'.color-accent-hover:focus',
						':root .has-accent-color',
						'.has-drop-cap:not(:focus):first-letter',
						'.wp-block-button.is-style-outline',
						'a'
					),
					'border-color'     => array(
						'blockquote',
						'.border-color-accent',
						'.border-color-accent-hover:hover',
						'.border-color-accent-hover:focus'
					),
					'background-color' => array(
						'button',
						'.button',
						'.faux-button',
						'.wp-block-button__link',
						'.wp-block-file .wp-block-file__button',
						'input[type="button"]',
						'input[type="reset"]',
						'input[type="submit"]',
						'.bg-accent',
						'.bg-accent-hover:hover',
						'.bg-accent-hover:focus',
						':root .has-accent-background-color',
						'.comment-reply-link'
					),
					'fill'             => array( '.fill-children-accent', '.fill-children-accent *' ),
				),
				'background' => array(
					'color'            => array(
						':root .has-background-color',
						'button',
						'.button',
						'.faux-button',
						'.wp-block-button__link',
						'.wp-block-file__button',
						'input[type="button"]',
						'input[type="reset"]',
						'input[type="submit"]',
						'.wp-block-button',
						'.comment-reply-link',
						'.has-background.has-primary-background-color:not(.has-text-color)',
						'.has-background.has-primary-background-color *:not(.has-text-color)',
						'.has-background.has-accent-background-color:not(.has-text-color)',
						'.has-background.has-accent-background-color *:not(.has-text-color)'
					),
					'background-color' => array( ':root .has-background-background-color' ),
				),
				'text'       => array(
					'color'            => array( 'body', '.entry-title a', ':root .has-primary-color' ),
					'background-color' => array( ':root .has-primary-background-color' ),
				),
				'secondary'  => array(
					'color'            => array(
						'cite',
						'figcaption',
						'.wp-caption-text',
						'.post-meta',
						'.entry-content .wp-block-archives li',
						'.entry-content .wp-block-categories li',
						'.entry-content .wp-block-latest-posts li',
						'.wp-block-latest-comments__comment-date',
						'.wp-block-latest-posts__post-date',
						'.wp-block-embed figcaption',
						'.wp-block-image figcaption',
						'.wp-block-pullquote cite',
						'.comment-metadata',
						'.comment-respond .comment-notes',
						'.comment-respond .logged-in-as',
						'.pagination .dots',
						'.entry-content hr:not(.has-background)',
						'hr.styled-separator',
						':root .has-secondary-color'
					),
					'background-color' => array( ':root .has-secondary-background-color' ),
				),
				'borders'    => array(
					'border-color'        => array(
						'pre',
						'fieldset',
						'input',
						'textarea',
						'table',
						'table *',
						'hr'
					),
					'background-color'    => array(
						'caption',
						'code',
						'code',
						'kbd',
						'samp',
						'.wp-block-table.is-style-stripes tbody tr:nth-child(odd)',
						':root .has-subtle-background-background-color'
					),
					'border-bottom-color' => array( '.wp-block-table.is-style-stripes' ),
					'border-top-color'    => array( '.wp-block-latest-posts.is-grid li' ),
					'color'               => array( ':root .has-subtle-background-color' ),
				),
			),
			'header-footer' => array(
				'accent'     => array(
					'color'            => array(
						'body:not(.overlay-header) .primary-menu > li > a',
						'body:not(.overlay-header) .primary-menu > li > .icon',
						'.modal-menu a',
						'.footer-menu a, .footer-widgets a',
						'#site-footer .wp-block-button.is-style-outline',
						'.wp-block-pullquote:before',
						'.singular:not(.overlay-header) .entry-header a',
						'.archive-header a',
						'.header-footer-group .color-accent',
						'.header-footer-group .color-accent-hover:hover'
					),
					'background-color' => array(
						'.social-icons a',
						'#site-footer button:not(.toggle)',
						'#site-footer .button',
						'#site-footer .faux-button',
						'#site-footer .wp-block-button__link',
						'#site-footer .wp-block-file__button',
						'#site-footer input[type="button"]',
						'#site-footer input[type="reset"]',
						'#site-footer input[type="submit"]'
					),
				),
				'background' => array(
					'color'            => array(
						'.social-icons a',
						'body:not(.overlay-header) .primary-menu ul',
						'.header-footer-group button',
						'.header-footer-group .button',
						'.header-footer-group .faux-button',
						'.header-footer-group .wp-block-button:not(.is-style-outline) .wp-block-button__link',
						'.header-footer-group .wp-block-file__button',
						'.header-footer-group input[type="button"]',
						'.header-footer-group input[type="reset"]',
						'.header-footer-group input[type="submit"]'
					),
					'background-color' => array(
						'#site-header',
						'.footer-nav-widgets-wrapper',
						'#site-footer',
						'.menu-modal',
						'.menu-modal-inner',
						'.search-modal-inner',
						'.archive-header',
						'.singular .entry-header',
						'.singular .featured-media:before',
						'.wp-block-pullquote:before'
					),
				),
				'text'       => array(
					'color'               => array(
						'.header-footer-group',
						'body:not(.overlay-header) #site-header .toggle',
						'.menu-modal .toggle'
					),
					'background-color'    => array( 'body:not(.overlay-header) .primary-menu ul' ),
					'border-bottom-color' => array( 'body:not(.overlay-header) .primary-menu > li > ul:after' ),
					'border-left-color'   => array( 'body:not(.overlay-header) .primary-menu ul ul:after' ),
				),
				'secondary'  => array(
					'color' => array(
						'.site-description',
						'body:not(.overlay-header) .toggle-inner .toggle-text',
						'.widget .post-date',
						'.widget .rss-date',
						'.widget_archive li',
						'.widget_categories li',
						'.widget cite',
						'.widget_pages li',
						'.widget_meta li',
						'.widget_nav_menu li',
						'.powered-by-wordpress',
						'.to-the-top',
						'.singular .entry-header .post-meta',
						'.singular:not(.overlay-header) .entry-header .post-meta a'
					),
				),
				'borders'    => array(
					'border-color'     => array(
						'.header-footer-group pre',
						'.header-footer-group fieldset',
						'.header-footer-group input',
						'.header-footer-group textarea',
						'.header-footer-group table',
						'.header-footer-group table *',
						'.footer-nav-widgets-wrapper',
						'#site-footer',
						'.menu-modal nav *',
						'.footer-widgets-outer-wrapper',
						'.footer-top'
					),
					'background-color' => array(
						'.header-footer-group table caption',
						'body:not(.overlay-header) .header-inner .toggle-wrapper::before'
					),
				),
			),
		);

		return apply_filters( 'uranus_get_elements_array', $elements );
	}
}
