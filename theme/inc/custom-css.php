<?php

if ( ! function_exists( 'uranus_generate_css' ) ) {
	function uranus_generate_css( $selector, $style, $value, $prefix = '', $suffix = '', $display = true ) {
		$return = '';
		if ( ! $value || ! $selector ) {

			return;
		}

		$return = sprintf( '%s { %s: %s; }', $selector, $style, $prefix . $value . $suffix );

		if ( $display ) {
			echo $return;
		}

		return $return;
	}
}