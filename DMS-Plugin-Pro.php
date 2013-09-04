<?php
/*

Plugin Name: DMS Professional Tools
Plugin URI: http://www.pagelines.com/
Description: Pro member code and utilities for PageLines DMS.
Version: 1.0.0
Author: PageLines
pagelines: true

*/

class DMSPluginPro {
	
	function __construct() {
		
		$basename = basename( dirname( __FILE__ ) );
		$filename = basename( __FILE__ );
		
		$this->base_url = sprintf( '%s/%s', WP_PLUGIN_URL,  $basename );
		
		$this->base_dir = sprintf( '%s/%s', WP_PLUGIN_DIR,  $basename );
		
		$this->base_file = sprintf( '%s/%s/%s', WP_PLUGIN_DIR,  $basename, $filename );
		
		$this->init();
		
	}

	function init(){
	
	
	}

}

new DMSPluginPro;