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
	
	private $plugin_path;
    private $plugin_url;
    private $l10n;
    private $plpro;
	private $settings;

	function __construct() {
		
		global $dmspro_plugin_url;
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
		$dmspro_plugin_url = $this->plugin_url;
		
		$this->l10n = 'wp-settings-framework';
		$this->load_libs();
		
		
        add_action( 'admin_menu', array( $this, 'admin_menu'), 99 );
		add_action( 'template_redirect', array( $this, 'section_cache' ) );
		add_action( 'template_redirect', array( $this, 'browsercss' ) );
		add_action( 'init', array( $this, 'memcheck' ) );
		
		add_action( 'after_setup_theme', array( $this, 'do_hacks' ) );
		
		$this->plpro = new WordPressSettingsFramework( $this->plugin_path .'settings/settings-general.php' );
		$this->settings = wpsf_get_settings( $this->plugin_path .'settings/settings-general.php' );
		add_filter( $this->plpro->get_option_group() .'_settings_validate', array( $this, 'validate_settings' ) );

		// has to be mega early...
		if( '1' === $this->settings['settingsgeneral_cdn_cdn-enabled'] ) {			
			define( 'WP_STACK_CDN_DOMAIN', $this->settings['settingsgeneral_cdn_cdn-url'] );
			define( 'WP_STAGE', 'production' );
			new WP_Stack_CDN_Plugin;
		}
	}

	function do_hacks() {
		new DMS_Hacks;
	}

	function section_cache() {
		if( '1' === wpsf_get_setting( wpsf_get_option_group( '../settings/settings-general.php' ), 'section_cache', 'cache-enabled' ) ) {
			new Sections_Cache;
		}
	}
	
	function browsercss() {
		if( '1' === wpsf_get_setting( wpsf_get_option_group( '../settings/settings-general.php' ), 'browsercss', 'enabled' ) ) {
			new Browser_Specific_CSS;
		}
	}

	function memcheck() {
		if( '1' === wpsf_get_setting( wpsf_get_option_group( '../settings/settings-general.php' ), 'memtest', 'enabled' ) ) {
			new PL_Memcheck;
		}
	}

	function load_libs(){

		require_once( $this->plugin_path . 'libs/class.hacks.php' );
		require_once( $this->plugin_path . 'libs/class.cdn.libs.php' );
		require_once( $this->plugin_path . 'libs/wp-settings-framework.php' );
		require_once( $this->plugin_path . 'libs/class.section.cache.php' );
		require_once( $this->plugin_path . 'libs/class.memtest.php' );
		require_once( $this->plugin_path . 'libs/class.browsercss.php' );
	}

    function admin_menu() {
        $page_hook = add_menu_page( __( 'PageLines PRO', $this->l10n ), __( 'PageLines PRO', $this->l10n ), 'update_core', 'plpro', array(&$this, 'settings_page') );
        add_submenu_page( 'plpro', __( 'Settings', $this->l10n ), __( 'Settings', $this->l10n ), 'update_core', 'plpro', array(&$this, 'settings_page') );
    }
    function settings_page()
	{
	    // Your settings page
	    ?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>PageLines PRO Plugin Settings</h2>
			<?php 
			// Output your settings form
			$this->plpro->settings(); 
		echo '</div>';
	}

	function validate_settings( $input )
	{
	    // Do your settings validation here
	    // Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
    	return $input;
	}

}

new DMSPluginPro;