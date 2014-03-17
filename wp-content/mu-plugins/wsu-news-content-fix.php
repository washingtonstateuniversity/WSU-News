<?php
/*
Plugin Name: WSU News Image Content Fix
Plugin URI: http://web.wsu.edu/
Description: Used at the server level to help fix image URLs from old content.
Author: washingtonstateuniversity, jeremyfelt
Version: 0.1
*/

add_filter( 'sanitize_file_name', 'wsu_sanitize_image_sideload', 10, 2 );
function wsu_sanitize_image_sideload( $filename, $filename_raw ) {
	return str_replace( '%20', '-', $filename );
}

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
		$post_args = array();

		if ( isset( $args[0] ) ) {
			$post_args['src-url'] = $args[0];
		}

		if ( isset( $assoc_args['limit'] ) ) {
			$post_args['limit'] = $assoc_args['limit'];
		}

		$results = $this->get_posts( $post_args );

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
					if ( ! isset( $url['host'] ) ) {
						$url['host'] = 'news.wsu.edu';
					}
					if ( false === strpos( $url['path'], '/wp-content/' ) ) {
						if ( ! isset( $host_counter[ $url['host'] ] ) ) {
							$host_counter[ $url['host'] ] = 1;
						} else {
							$host_counter[ $url['host'] ]++;
						}
						if ( ! isset( $path_counter[ $url['path'] ] ) ) {
							$path_counter[ $url['path'] ] = 1;
						} else {
							$path_counter[ $url['path'] ]++;
						}

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
	 * --replace-limit
	 * : Number of images to replace before bailing.
	 *
	 * ## EXAMPLES
	 *
	 *     wp image-fix sideload news.wsu.edu --limit=10 --replace-limit=10
	 *
	 * @synopsis <src-url> [--limit=<num>] [--replace-limit]
	 */
	public function sideload( $args, $assoc_args ) {
		$post_args = array();

		if ( isset( $args[0] ) ) {
			$post_args['src-url'] = $args[0];
		}

		if ( isset( $assoc_args['limit'] ) ) {
			$post_args['limit'] = $assoc_args['limit'];
		}

		if ( isset( $assoc_args['replace-limit'] ) ) {
			$replace_limit = $assoc_args['replace-limit'];
		} else {
			$replace_limit = 10;
		}

		$results = $this->get_posts( $post_args );
		$replaced_images = array();
		$replaced_image_count = 0;

		/**
		 * Use the XML Parser to parse the text of each post_content returned and look for
		 * instances of image tags.
		 */
		foreach( $results as $result ) {
			$xml_parser = xml_parser_create();
			xml_parse_into_struct( $xml_parser, $result->post_content, $pieces );
			add_filter( 'http_request_host_is_external', '__return_true' );
			foreach( $pieces as $piece ) {
				if ( 'IMG' === $piece['tag'] && isset( $piece['attributes'] ) && ! isset( $replaced_images[ $piece['attributes']['SRC'] ] ) ) {
					$url = parse_url( $piece['attributes']['SRC'] );
					if ( ! isset( $url['host'] ) ) {
						$url['host'] = 'news.wsu.edu';
					}
					if ( 0 === strpos( $url['host'], str_replace( 'http://', '', $post_args['src-url'] ) ) && false === strpos( $url['path'], '/wp-content/' ) ) {
						// This image should be replaced.
						$sideload_result = media_sideload_image( str_replace( ' ', '%20', 'http://news.wsu.edu' . $piece['attributes']['SRC'] ), $result->ID );
						if ( is_wp_error( $sideload_result ) ) {
							//echo 'FAIL: ' . $piece['attributes']['SRC'] . ' ... ' . $sideload_result->get_error_message() . "\n";
						} else {
							echo 'SUCCESS: ' . $piece['attributes']['SRC'] . "\n";
							$sideload_result = str_replace( "<img src='http://news.wsu.edu", '', $sideload_result );
							$sideload_result = str_replace( "' alt='' />", '', $sideload_result );
							$sideload_result = trim( $sideload_result );
							$result->post_content = str_replace( $piece['attributes']['SRC'], $sideload_result, $result->post_content );
							wp_update_post( $result );
							$replaced_images[ $piece['attributes']['SRC'] ] = $sideload_result;
							echo "$sideload_result\n";
						}
						$replaced_image_count++;
					}
				} elseif ( 'IMG' === $piece['tag'] && isset( $piece['attributes'] ) &&  isset( $replaced_images[ $piece['attributes']['SRC'] ] ) ) {
					$result->post_content = str_replace( $piece['attributes']['SRC'], $replaced_images[ $piece['attributes']['SRC'] ], $result->post_content );
					wp_update_post( $result );
				}

				if ( $replaced_image_count > $replace_limit ) {
					break 2;
				}
			}
			remove_filter( 'http_request_host_is_external', '__return_true' );

		}

		WP_CLI::success( 'sideloaded!' );
	}

	/**
	 * Get posts matching a very basic set of criteria to match posts with images
	 * from specific URLs.
	 *
	 * @param array $post_args Array of arguments to pass to the posts query.
	 *
	 * @return mixed Results of the query.
	 */
	private function get_posts( $post_args ) {
		/**
		 * @var WPDB $wpdb
		 */
		global $wpdb;

		// A general query for all of the posts.
		$query = "SELECT ID, post_content FROM {$wpdb->posts} WHERE 1=1 AND post_type='post'";

		// If a source URL has been specified, we can limit the query.
		if ( isset( $post_args['src-url'] ) ) {
			$src_url = like_escape( $post_args['src-url'] );
			$query .= " AND post_content LIKE '%src=\"/Content/Photos/%'";
		}

		// If a limit has been specified, we'll add it as well.
		if ( isset( $post_args['limit'] ) ) {
			$limit = absint( $post_args['limit'] );
			$query .= " LIMIT $limit";
		}

		return $wpdb->get_results( $query );
	}
}

WP_CLI::add_command( 'image-fix', 'Image_Content_Fix' );

}