<?php
/*
Plugin Name: WSU News Image Content Fix
Plugin URI: http://web.wsu.edu/
Description: Used at the server level to help fix image URLs from old content.
Author: washingtonstateuniversity, jeremyfelt
Version: 0.1
*/

if ( defined('WP_CLI') && WP_CLI ) {

/**
 * Fix image content in posts.
 */
class Image_Content_Fix extends WP_CLI_Command {

	/**
	 * Lists images in use on news.wsu.edu.
	 *
	 * ## OPTIONS
	 *
	 * <src-url>
	 * : A specific source URL to check
	 * --limit
	 * : Number of posts to check
	 * --offset
	 * : Offset of posts when running a limit query
	 *
	 * ## EXAMPLES
	 *
	 *     wp image-fix list
	 *     wp image-fix list news.wsu.edu --limit=10
	 *
	 * @subcommand list
	 * @synopsis [<src-url>] [--limit=<num>]
	 */
	public function _list( $args, $assoc_args ) {
		/**
		 * @var WPDB $wpdb
		 */
		global $wpdb;

		// A general query for all of the posts.
		$query = "SELECT ID, post_content FROM {$wpdb->posts} WHERE 1=1";

		// If a source URL has been specified, we can limit the query.
		if ( isset( $args[0] ) ) {
			$src_url = like_escape( $args[0] );
			$query .= " AND post_content LIKE '%src=\"$src_url%'";
		}

		// If a limit has been specified, we'll add it as well.
		if ( isset( $assoc_args['limit'] ) ) {
			$limit = absint( $assoc_args['limit'] );
			$query .= " LIMIT $limit";
		}

		$results = $wpdb->get_results( $query );

		// General incrementor for total number of images.
		$image_counter = 0;

		// Incementor for tracking a per host count.
		$host_counter = array();

		// Incrementor for tracking a per path count.
		$path_counter = array();

		/**
		 * Use the XML Parser to parse the text of each post_content returned and look for
		 * instances of image tags.
		 */
		foreach( $results as $result ) {
			$xml_parser = xml_parser_create();
			xml_parse_into_struct( $xml_parser, $result->post_content, $pieces );
			foreach( $pieces as $piece ) {
				if ( 'IMG' === $piece['tag'] ) {
					$url = parse_url( $piece['attributes']['SRC'] );
					if ( false === strpos( $url['path'], '/wp-content/' ) ) {
						$host_counter[ $url['host'] ]++;
						$path_counter[ $url['path'] ]++;
						$image_counter++;
					}
				}
			}

		}

		// Sort the path count in ascending order and display.
		asort( $path_counter );
		echo "\nImage by path:\n";
		foreach( $path_counter as $path => $count ) {
			echo zeroise( $count, 4 ) . ' - ' . $path . "\n";
		}

		// Sort the host count in ascending order and display.
		asort( $host_counter );
		echo "\n\nImages by domain:\n";
		foreach( $host_counter as $host => $count ) {
			echo zeroise( $count, 4 ) . ' - ' . $host . "\n";
		}

		WP_CLI::success( $image_counter . ' images found.' );
	}

	/**
	 * Sideload images into WordPress.
	 *
	 * ## OPTIONS
	 *
	 * <src-url>
	 * : A specific source URL for images to be replaced.
	 * --limit
	 * : Number of posts to check for images.
	 *
	 * ## EXAMPLES
	 *
	 *     wp image-fix sideload news.wsu.edu --limit=10
	 *
	 * @synopsis <src-url> [--limit=<num>]
	 */
	public function sideload( $args, $assoc_args ) {
		WP_CLI::success( 'sideloaded!' );
	}
}

WP_CLI::add_command( 'image-fix', 'Image_Content_Fix' );

}