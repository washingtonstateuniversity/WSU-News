<?php

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
	 *     wp image-fix list_images --src=news.wsu.edu
	 *
	 * @synopsis [<src-url>] [--limit=<num>] [--offset=<num>]
	 */
	function list_images( $args, $assoc_args ) {
		/**
		 * @var WPDB $wpdb
		 */
		global $wpdb;

		list( $src_url ) = $args;
		$src_url = like_escape( $src_url );

		$query = "SELECT ID, post_content FROM {$wpdb->posts} WHERE 1=1";

		if ( isset( $args[0] ) ) {
			$src_url = like_escape( $args[0] );
			$query .= " AND post_content LIKE '%src=\"$src_url%'";
		}
		if ( isset( $assoc_args['limit'] ) ) {
			$limit = absint( $assoc_args['limit'] );
			$query .= " LIMIT $limit";
		}

		echo $query;
		echo "\n";
		$results = $wpdb->get_results( $query );
		$inc = 0;
		$counter = array();
		$path_counter = array();
		foreach( $results as $result ) {
			$xml_parser = xml_parser_create();
			xml_parse_into_struct( $xml_parser, $result->post_content, $pieces );
			foreach( $pieces as $piece ) {
				if ( 'IMG' === $piece['tag'] ) {
					$url = parse_url( $piece['attributes']['SRC'] );
					if ( false === strpos( $url['path'], '/wp-content/' ) ) {
						$counter[ $url['host'] ]++;
						$path_counter[ $url['path'] ]++;
						$inc++;
					}
				}
			}

		}

		asort( $path_counter );
		echo "\nImage by path:\n";
		foreach( $path_counter as $path => $count ) {
			echo zeroise( $count, 4 ) . ' - ' . $path . "\n";
		}

		asort( $counter );
		echo "\n\nImages by domain:\n";
		foreach( $counter as $host => $count ) {
			echo zeroise( $count, 4 ) . ' - ' . $host . "\n";
		}

		WP_CLI::success( $inc . ' images' );
	}
}

WP_CLI::add_command( 'image-fix', 'Image_Content_Fix' );

}