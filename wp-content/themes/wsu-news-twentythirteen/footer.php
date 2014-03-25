<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

</div><!-- #main -->
<div id="secondary">
	<?php
	// Show a search form on every page.
	get_search_form();

	// @global WSU_News_Announcements $wsu_news_announcements
	global $wsu_content_type_announcement;
	// Load a sidebar when we're dealing with the display of announcements.
	if ( isset( $wsu_content_type_announcement->post_type ) && ( is_singular( $wsu_content_type_announcement->post_type ) || is_post_type_archive( $wsu_content_type_announcement->post_type ) ) )
		get_sidebar();
	?>
	<?php if ( is_singular( 'post' ) ) : ?>
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_floating_style addthis_counter_style">
			<a class="addthis_button_facebook_like" fb:like:layout="box_count"></a>
			<a class="addthis_button_tweet" tw:count="vertical"></a>
			<a class="addthis_button_google_plusone" g:plusone:size="tall"></a>
			<a class="addthis_counter"></a>
		</div>
		<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51c227791d6b6af5"></script>
		<!-- AddThis Button END -->
	<?php endif; ?>
</div>
<div class="clear"></div>

</div><!-- #content-->
</div><!-- #wrapper -->
<script type='text/javascript' src='//repo.wsu.edu/bootstrap/bootstrap_v3.js?gacode=UA-6322839-2&amp;loading=element_v2&amp;domainName=news.wsu.edu/'  id='tracker_agent'></script>
<?php wp_footer(); ?>

<noscript><div id="wsufooter">Copyright &copy;  Board of Regents, <a href="http://www.wsu.edu">Washington State University</a></div>
</noscript>
<script language="javascript" type="text/javascript">var strFooter;strFooter = getFooter();document.write(strFooter);</script>
<!--End of the WSU Global Footer Zone. -->

</body>
</html>