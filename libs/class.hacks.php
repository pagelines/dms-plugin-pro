<?php
class DMS_Hacks {

	function __construct() {
		add_action( 'wp_before_admin_bar_render', array( $this, 'show_template' ) );
		add_filter( 'render_css_posix_', '__return_true' );
	}

	function show_template() {
		global $wp_admin_bar, $pldraft, $plpg;
		if( 'live' == $pldraft->mode )
			return;

		$wp_admin_bar->add_menu( array(
			'parent' => false,
			'id' => 'page_template',
			'title' => sprintf( '%s : %s',  __( 'Last Imported Template', 'pagelines' ), ucfirst( $plpg->template ) ),
			'href'	=> sprintf( '%s?tablink=page-setup', site_url() ),
			'meta'	=> false
		));
	}
}
