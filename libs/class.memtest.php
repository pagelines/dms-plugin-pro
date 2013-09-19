<?php

class PL_Memcheck {
	
	function __construct() {
		add_action( 'template_redirect', array( $this, 'memcheck' ) );
	}
	function memcheck() {

		if( ! isset( $_GET['pl_memcheck'] ) )
			return;

		register_shutdown_function( array( $this, "fatal_handler" ) );
		set_error_handler(array( $this, "fatal_handler" ) );
		$step = 1;
		global $ram;
		while(TRUE) {
			$chunk = str_repeat('0123456789', 128*1024*$step++);
			$ram = round(memory_get_usage()/(1024));
			unset($chunk);
		}
	}

	function fatal_handler() {
		global $ram;
		echo sprintf( 'Ram test Results: %sKb or approx %sMb', $ram, round( $ram / 1024 ) );
	}
}


