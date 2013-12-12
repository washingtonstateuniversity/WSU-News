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
	 * : Number of posts to check.
	 *
	 * ## EXAMPLES
	 *
	 *     wp newsfix list_images --src=news.wsu.edu
	 *
	 * @synopsis <src-url> [--limit=<num>]
	 */
	function list_images( $args, $assoc_args ) {
		if ( isset( $assoc_args['limit'] ) ) {
			$limit = $assoc_args['limit'];
		}

		list( $src_url ) = $args;

		WP_CLI::success( $src_url );
	}
}

WP_CLI::add_command( 'newsfix', 'Content_Fix' );

}