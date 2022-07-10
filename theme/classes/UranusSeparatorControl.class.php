<?php

if ( class_exists( 'WP_Customize_Control' ) ) {
	if ( ! class_exists( 'UranusSeparatorControl' ) ) {
		class UranusSeparatorControl extends WP_Customize_Control {
			public function render_content() {
				echo '<hr/>';
			}
		}
	}
}
