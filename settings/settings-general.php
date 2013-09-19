<?php

global $wpsf_settings;

$wpsf_settings[] = array(
    'section_id' => 'cdn',
    'section_title' => 'CDN Settings',
    'section_order' => 1,
    'fields' => array(
	    array(
            'id' => 'cdn-enabled',
            'title' => 'CDN',
            'desc' => 'Enable the simple CDN.',
            'type' => 'checkbox',
            'std' => 0
        ),
        array(
            'id' => 'cdn-url',
            'title' => 'Your CDN PULL zone.',
            'desc' => sprintf( 'An example of your pullzone might be: <kbd>cdn.%s</kbd>', str_replace( 'http://', '', str_replace( 'www', '', str_replace( 'https://', '', site_url() ) ) ) ),
            'type' => 'text',
            'std' => site_url()
        ),
    )
);
$wpsf_settings[] = array(
    'section_id' => 'section_cache',
    'section_title' => 'Section Caching.',
    'section_order' => 2,
    'fields' => array(
	    array(
            'id' => 'cache-enabled',
            'title' => 'Section Cache',
            'desc' => '<p>This simple cache uses wp_transients to store rendered sections HTML saving a few db queries.<br />If you are using a PHP OP Cache like APC/Memcached this can make quite a difference.<br .><kbd>DISCLOSURE: THIS IS NOT GOING TO BE A MAGIC FIX FOR SERVERS MADE FROM DOGSHIT</kbd></p>',
            'type' => 'checkbox',
            'std' => 0
        )
    )
);

$wpsf_settings[] = array(
    'section_id' => 'memtest',
    'section_title' => 'Memory Test.',
    'section_order' => 3,
    'fields' => array(
	    array(
            'id' => 'enabled',
            'title' => 'MemTest',
            'desc' => pl_memtest_enabled(),
            'type' => 'checkbox',
            'std' => 0
        )
    )
);

function pl_memtest_enabled() {
	if ( '1' == wpsf_get_setting( wpsf_get_option_group( '../settings/settings-general.php' ), 'memtest', 'enabled' ) )
		return sprintf( 'Use this link to test your actual server ram. <a href="%s">CLICK HERE TO TEST NOW</a><br /><kbd>DO NOT LEAVE THIS ENABLED!!!</kbd>', add_query_arg( array('pl_memcheck' => 1), site_url() ) );
	else
		return '<p>Most cheap hosts although they say 256M of RAM is allocated to your account in reality it is limited in other ways.<br />This simple test will reveal your true account limitations.</p>';

}