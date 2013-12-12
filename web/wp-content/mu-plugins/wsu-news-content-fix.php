<?php

if ( defined('WP_CLI') && WP_CLI ) {

/**
 * Fix content in posts.
 */
class Content_Fix extends WP_CLI_Command {

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
	 *     wp newsfix list_images --src=news.wsu.edu
	 *
	 * @synopsis <src-url> [--limit=<num>] [--offset=<num>]
	 */
	function list_images( $args, $assoc_args ) {
		/**
		 * @var WPDB $wpdb
		 */
		global $wpdb;

		list( $src_url ) = $args;
		$src_url = like_escape( $src_url );

		if ( isset( $assoc_args['limit'] ) ) {
			$limit = absint( $assoc_args['limit'] );
		} else {
			$limit = 10;
		}

		$like_query = "LIKE '%src=\"$src_url%'";
		$query   = "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_content {$like_query} LIMIT $limit";
		echo $query;
		echo "\n";
		$results = $wpdb->get_results( $query );var_dump( $results );
		$inc = 0;
		foreach( $results as $result ) {
			$xml_parser = xml_parser_create();
			xml_parse_into_struct( $xml_parser, $result->post_content, $pieces );
			foreach( $pieces as $piece ) {
				if ( 'IMG' === $piece['tag'] ) {
					echo $result->ID . ': ' . $piece['attributes']['SRC'];
					echo "\n";
					$inc++;
				}
			}

		}

		WP_CLI::success( $inc . ' images' );
	}
}

WP_CLI::add_command( 'newsfix', 'Content_Fix' );

}