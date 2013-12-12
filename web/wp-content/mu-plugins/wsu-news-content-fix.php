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

		$like_query = "LIKE '%$src_url%'";
		$query   = "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_content {$like_query} LIMIT $limit";
		$results = $wpdb->get_results( $query );

		foreach( $results as $result ) {
			echo $result->post_content;
			echo "\n";
		}

		WP_CLI::success( $src_url );
	}
}

WP_CLI::add_command( 'newsfix', 'Content_Fix' );

}