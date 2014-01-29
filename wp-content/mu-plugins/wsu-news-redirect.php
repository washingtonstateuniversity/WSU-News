<?php
/*
Plugin Name: WSU Redirect Legacy URLs
Plugin URI: http://web.wsu.edu/
Description: Redirect article detail pages to the new URL structure for news.wsu.edu
Author: washingtonstateuniversity, jeremyfelt
Version: 0.1
*/

add_action( 'init', 'wsu_redirect_publication_id', 10 );
/**
 * Redirect old PublicationID based detail pages for articles to the corresponding
 * article's new URL at news.wsu.edu.
 */
function wsu_redirect_publication_id() {
	/* @var WPDB $wpdb */
	global $wpdb;

    //pattern: 
    //http://news.wsu.edu/pages/publications.asp?Action=Detail&PublicationID=36331&TypeID=1
	if ( isset( $_GET['PublicationID'] ) && isset( $_GET['Action'] ) && 'Detail' === $_GET['Action'] && 0 !== absint( $_GET['PublicationID'] ) ) {
		$publication_id = absint( $_GET['PublicationID'] );
		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM wp_postmeta WHERE meta_key = '_publication_id' AND meta_value = %s", $publication_id ) );
		if ( 0 !== absint( $post_id ) ) {
			wp_safe_redirect( get_permalink( $post_id ), 301 );
			exit;
		}
	}

    //pattern: 
    //http://news.wsu.edu/articles/36828/1/New-cyber-security-firm-protects-Seattle-businesses
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
	if ( strpos( $actual_link,'/articles/')>-1 ) {
        $urlparts = explode('/',$actual_link);
		$publication_id = absint( $urlparts[4] );
		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM wp_postmeta WHERE meta_key = '_publication_id' AND meta_value = %s", $publication_id ) );
		if ( 0 !== absint( $post_id ) ) {
			wp_safe_redirect( get_permalink( $post_id ), 301 );
			exit;
		}
	}    

	return;
}