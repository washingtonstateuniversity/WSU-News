<?php
/*
Plugin Name: WSUWP News - TinyMCE Autolink
Plugin URI: http://news.wsu.edu
Description: Enables the TinyMCE autolink plugin
Author: washingtonstateuniversity, jeremyfelt
Version: 0.1
*/

add_filter( 'mce_external_plugins', 'wsunews_mce_external_plugins' );
function wsunews_mce_external_plugins( $plugins ) {
	if ( ! isset( $plugins['autolink'] ) ) {
		$plugins['autolink'] = plugins_url( '/js/plugin.min.js', __FILE__ );
	}
	return $plugins;
}