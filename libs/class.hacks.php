<?php
class DMS_Hacks {

	function __construct() {
		add_action( 'wp_before_admin_bar_render', array( $this, 'show_template' ) );
		add_filter( 'render_css_posix_', '__return_true' );
		add_action( 'dmspro_extra_settings', array( $this, 'hacks_included' ) );
	}

	function hacks_included() {

		ob_start();
		?>
		<h2>Extra Hacks enabled by this plugin</h2>
		<ul>
		<li><kbd>render_css_posix_</kbd> This filter forces the framework to write a CSS file if your server does not have POSIX extensions installed.</li>
		<li><kbd>Template: Feature</kbd> Show last loaded template name in WP adminbar.</li>
		</ul>
		
		<?php
		echo ob_get_clean();
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
