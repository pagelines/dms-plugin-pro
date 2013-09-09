<?php
/*

Plugin Name: DMS Professional Tools
Plugin URI: http://www.pagelines.com/
Description: Pro member code and utilities for PageLines DMS.
Version: 1.0.0
Author: PageLines
PageLines: true

*/

class DMSPluginPro {
	
	function __construct() {
		
		$basename = basename( dirname( __FILE__ ) );
		$filename = basename( __FILE__ );
		
		$this->base_url = sprintf( '%s/%s', WP_PLUGIN_URL,  $basename );
		
		$this->base_dir = sprintf( '%s/%s', WP_PLUGIN_DIR,  $basename );
		
		$this->lib_dir = sprintf( '%s/%s', $this->base_dir,  '/libs' );
		
		$this->base_file = sprintf( '%s/%s/%s', WP_PLUGIN_DIR,  $basename, $filename );
		
		$this->init();
		
		add_action( 'after_setup_theme', array( $this, 'run_theme_overrides' ) );
	}

	function init(){
	
		require_once( $this->lib_dir . '/actions.php' );
		require_once( $this->lib_dir . '/shortcodes.php' ); 	
	}

	function run_theme_overrides() {
		new PLProShortcodes;
	}
}

new DMSPluginPro;