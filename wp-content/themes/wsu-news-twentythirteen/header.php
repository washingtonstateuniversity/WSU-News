<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
    
	<title><?php wp_title( ' |', true, 'right' ); ?> Washington State University</title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->

	<!--Cougar Head favicon-->
	<link rel="shortcut icon" href="//news.wsu.edu/favicon.ico" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<!--Beginning of the WSU Identifier Zone. Refer to http://identity.wsu.edu/ for more information. -->
	<div id = "wrapper">
		<noscript><div id='globalnav'><ul><li>Washington State University</li></ul></div><div id='logo'><a href='http://www.wsu.edu'><img src='<?php echo get_stylesheet_directory_uri() . '/css/images/bg-logo.jpg'; ?>' alt="WSU Logo"/></a></div></noscript>
		<script language="Javascript" src="//repo.wsu.edu/identity/wsuidentifierV2.js?20140320-05'; ?>" type="text/javascript"></script>
		<script language="javascript" type="text/javascript"> var strHeader; strHeader = getHeader(); document.write(strHeader);</script>
	<!--End of the WSU Identifier Zone. -->
		<div id="siteID">
		    <h1><?php bloginfo( 'name' ); ?></h1>
		</div>
		<div id="toolbar"><!-- Empty for visual spacing purposes --></div>
		<div id="content">
			<div id="nav">
			<!-- Optional Javascript navtool insert location. Visit http://identity.wsu.edu/web/downloads/add-ons/nav-java.aspx for more info -->
				<!-- Featured Navigation -->
				<div id="featured">
					<?php wp_nav_menu( array( 'theme_location' => 'featured', 'menu_class' => '' ) ); ?>
				</div>
				<!-- Main Navigation -->
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
			</div><!-- #nav -->

			<div id="main" class="site-main">
