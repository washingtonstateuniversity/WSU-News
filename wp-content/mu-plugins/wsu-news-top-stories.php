<?php
/*
Plugin Name: WSU News - Show Top Stories on Home Page
Plugin URI: http://news.wsu.edu/
Description: Shows only posts from the Top Stories category on the home page.
Author: washingtonstateuniversity, jeremyfelt
Version: 0.1
*/

add_action( 'pre_get_posts', 'wsu_news_top_stories' );
/**
 * Set the home page query to only include posts from the Top Stories category.
 *
 * @param WP_Query $query Current query object to be modified.
 */
function wsu_news_top_stories( $query ) {
	if ( is_home() && $query->is_main_query() )
		$query->set( 'category_name', 'top-stories' );

}
