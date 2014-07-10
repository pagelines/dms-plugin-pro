<?php
class DMS_Hacks {

	function __construct() {
		add_action( 'wp_before_admin_bar_render', array( $this, 'show_template' ) );
		add_action( 'dmspro_extra_settings', array( $this, 'hacks_included' ) );
		add_filter('posts_where', array( $this, 'advanced_search_query' ) );
	}

	function hacks_included() {

		ob_start();
		?>
		<h2>Extra Hacks enabled by this plugin</h2>
		<ul>
		<li><kbd>Template: Feature</kbd> Show last loaded template name in WP adminbar.</li>
		</ul>

		<?php
		echo ob_get_clean();
	}
	function show_template() {
		if( is_admin() )
			return false;
		global $wp_admin_bar, $pldraft, $plpg, $pl_custom_template, $post;
		if( version_compare( CORE_VERSION, '2.1', '<' ) ) {

			if( ! is_object( $pldraft) || 'live' == $pldraft->mode || is_admin() )
				return;

			if( class_exists( 'PLDeveloperTools' ) )
				$template = ( isset( $pl_custom_template['name'] ) ) ? $pl_custom_template['name'] : 'None';
			else
				$template = ( false != $plpg->template && '' != $plpg->template ) ? $plpg->template : 'None';


			$wp_admin_bar->add_menu( array(
				'parent' => false,
				'id' => 'page_template',
				'title' => sprintf( 'Template: %s',  $template ),
				'href'	=> sprintf( '%s?tablink=page-setup', site_url() ),
				'meta'	=> false
			));
		} else {

			$page_handler = new PageLinesPage;
			if( $page_handler->is_special() )
				$id = $page_handler->special_index_lookup();
			else
				$id = $post->ID;

			$mode = get_post_meta( $id, 'pl_template_mode', true );

			if( ! $mode )
				$mode = 'type';

			if( 'local' != $mode )
				$id = $page_handler->special_index_lookup();

			$set = pl_meta( $id, PL_SETTINGS );
			$template = ( is_array( $set ) && isset( $set['live']['custom-map']['template']['ctemplate'] ) ) ? $set['live']['custom-map']['template']['ctemplate'] : 'Default';

			$wp_admin_bar->add_menu( array(
				'parent' => false,
				'id' => 'page_template',
				'title' => sprintf( 'Mode: %s | Template: %s', ucwords( $mode ), ucwords( $template ) ),
				'href'	=> sprintf( '%s?tablink=page-setup', site_url() ),
				'meta'	=> false
			));
		}
	}

	function advanced_search_query( $where ) {

		if( version_compare( CORE_VERSION, '2.0', '>' ) ) {
			return $where;
		}

		if( is_search() && '1' === wpsf_get_setting( wpsf_get_option_group( '../settings/settings-general.php' ), 'search', 'enabled' )) {

	    global $wpdb;
	    $query = get_search_query();
	    $query = like_escape( $query );

	    // include postmeta in search
	     $where .=" OR {$wpdb->posts}.ID IN (SELECT {$wpdb->postmeta}.post_id FROM {$wpdb->posts}, {$wpdb->postmeta} WHERE {$wpdb->postmeta}.meta_key = 'pl-settings' AND {$wpdb->postmeta}.meta_value LIKE '%$query%' AND {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)";

	     // include taxonomy in search
	    $where .=" OR {$wpdb->posts}.ID IN (SELECT {$wpdb->posts}.ID FROM {$wpdb->posts},{$wpdb->term_relationships},{$wpdb->terms} WHERE {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id AND {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->terms}.term_id AND {$wpdb->terms}.name LIKE '%$query%')";
		}
	    return $where;
	}
}
