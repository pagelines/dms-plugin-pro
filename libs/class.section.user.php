<?php

class Sections_User {

	function __construct() {
		add_filter( 'pagelines_render_section', array( $this, 'section_user_check' ), 10, 2 );
		add_filter( 'pl_standard_section_options', array( $this, 'add_option' ) );
	}

	function section_user_check( $s, $class ) {

		$hide = false;

		if( '1' === $s->opt( 'pl_standard_nouser_hide' ) && ! is_user_logged_in() )
			$hide = true;

		if( '1' === $s->opt( 'pl_standard_user_hide' ) && is_user_logged_in() )
			$hide = true;

		if( '1' === $s->opt( 'pl_standard_mobile_hide' ) && wp_is_mobile() )
			$hide = true;

		if( '1' === $s->opt( 'pl_standard_desktop_hide' ) && ! wp_is_mobile() )
			$hide = true;

		if( true === $hide )
			return false;

		ob_start();
		$class->section_template_load( $s );
		return ob_get_clean();
	}

	function add_option( $options ) {

		$extra = array();
		$opts = $options['standard']['opts'];
		
		
		$extra[] = array(
				'key'	=> 'pro_extra_standard_opts',
				'help'	=> 'Extra Options (Pro Tools)<br />These extra options will <strong>NOT</strong> work properly if you are using a cache plugin and have not configured it correctly.',
				'type'	=> 'multi',
				'opts'	=> array (
					array(
						'key'		=> 'pl_standard_nouser_hide',
						'type' 		=> 'check',
						'default'	=> false,
						'label' 	=> __( 'Hide this section for logged out users', 'pagelines' ),
					),
					array(
						'key'		=> 'pl_standard_user_hide',
						'type' 		=> 'check',
						'default'	=> false,
						'label' 	=> __( 'Hide this section for logged in users', 'pagelines' ),
					),
					array(
						'key'		=> 'pl_standard_mobile_hide',
						'type' 		=> 'check',
						'default'	=> false,
						'label' 	=> __( 'Hide this section for mobile users', 'pagelines' ),
					),
					array(
						'key'		=> 'pl_standard_desktop_hide',
						'type' 		=> 'check',
						'default'	=> false,
						'label' 	=> __( 'Hide this section for desktop users', 'pagelines' ),
					)
				)
			);
		$opts = array_merge( $extra, $opts );
		$options['standard']['opts'] = $opts;
		return $options;
	}
}