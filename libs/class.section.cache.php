<?php

class Sections_Cache {
	
	function __construct() {
		add_filter( 'pagelines_render_section', array( $this, 'section_cache_init' ), 10, 2 );
	}

	function section_cache_init( $s, $class ) {
	
		if( is_user_logged_in() || 'pl_area' == $s->id || 'plcolumn' == $s->id ) {
			ob_start();
			$class->section_template_load( $s );
			return ob_get_clean();
		}
	return $this->section_cache( $s, 3600, $class );
	}


	function section_cache( $s, $ttl = 3600, $class ) {
	
		global $post;
		$cache_key = pl_get_cache_key();
		$id = $s->meta['clone'];
		$name = $s->id;

		$key = sprintf( 'section_cache_%s_%s_%s', $cache_key, $id, $post->ID );
	
		// do cache...
		$output = get_transient( $key );
	
		if( '' != $output  ) {
			echo "<!-- section cache hit -->\n";
			return $output;
		} 

		echo "<!-- sections cache miss -->\n";
		ob_start();
		$class->section_template_load( $s );
		$output = ob_get_clean();
		set_transient( $key, $output, $ttl );
		return $output;
	}
}