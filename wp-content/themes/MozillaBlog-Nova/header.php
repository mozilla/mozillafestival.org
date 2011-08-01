<!DOCTYPE html>
<html <?php language_attributes(); ?> id="blog-mozilla-com">
<head>
  <meta name="viewport" content="width=1024">
  <meta name="og:image" content="<?php bloginfo('stylesheet_directory'); ?>/img/firefox-100.jpg">
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="title" content="<?php bloginfo('name'); ?>">
  <meta name="description" content="<?php bloginfo('description'); ?>">
  <meta name="Copyright" content="(c) 2008-<?php echo date('Y');?> Mozilla. All rights reserved.">
  <meta name="Rating" content="General">
  <!--[if IE]>
  <meta name="MSSmartTagsPreventParsing" content="true">
  <meta http-equiv="imagetoolbar" content="no">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <link rel="copyright" href="#copyright">
  <link rel="shortcut icon" type="image/ico" href="<?php bloginfo('stylesheet_directory'); ?>/img/favicon.ico">
  <link rel="stylesheet" type="text/css" media="screen,projection" href="<?php bloginfo('stylesheet_url'); ?>">
  <link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('stylesheet_directory'); ?>/css/print.css">
  <!--[if IE]><link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie.css"><![endif]-->
  <!--[if lte IE 7]><link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie7.css"><![endif]-->

  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>">
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

  <title><?php
    if ( is_single() ) { single_post_title(); echo ' | '; bloginfo('name'); } 
    elseif ( is_home() || is_front_page() ) { bloginfo('name'); echo ' | '; bloginfo('description'); fc_page_number(); } 
    elseif ( is_page() ) { single_post_title(''); echo ' | '; bloginfo('name'); } 
    elseif ( is_search() ) { printf( __('Search results for "%s"', 'mozblog'), esc_html( $s ) ); fc_page_number(); echo ' | '; bloginfo('name'); }
    elseif ( is_day() ) { $post = $posts[0]; _e('Posts for ', 'mozblog'); echo the_time('F jS, Y'); echo ' | '; bloginfo('name'); fc_page_number(); }
    elseif ( is_month() ) { $post = $posts[0]; _e('Posts for ', 'mozblog'); echo the_time('F, Y'); echo ' | '; bloginfo('name'); fc_page_number(); }
    elseif ( is_year() ) { $post = $posts[0]; _e('Posts for ', 'mozblog'); echo the_time('Y'); echo ' | '; bloginfo('name'); fc_page_number(); }
    elseif ( is_404() ) { _e('Not Found', 'mozblog'); echo ' | '; bloginfo('name'); } 
    else { wp_title(''); echo ' | '; bloginfo('name'); fc_page_number(); } 
  ?></title>

  <?php if (is_singular()) wp_enqueue_script( 'comment-reply' ); ?>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> role="document">
<div id="doc">
  <ul id="nav-access" role="navigation">
    <li><a href="#content-main">Skip to content</a></li>
    <li><a href="#search">Skip to blog search</a></li>
  </ul>

  <header id="branding">
    <div id="header">
      <h4><a href="http://www.mozilla.com/" title="Back to the Mozilla Firefox home page">Mozilla Firefox</a></h4>
      <a href="http://www.mozilla.org/" class="mozilla">visit <span>mozilla</span></a>
      <?php include (TEMPLATEPATH . "/navbar.php"); ?>
    </div><!-- end #header -->

    <p id="breadcrumbs"><a class="home" href="http://www.mozilla.com/">Home</a> <b>&raquo;</b> <a href="http://www.mozilla.com/about/">About</a> <b>&raquo;</b> 
    <?php if ( (is_front_page()) && ($paged < 1) ) : ?><span>Blog</span><?php else : ?><a href="<?php bloginfo('url'); ?>">Blog</a><?php endif; ?>
    </p>

    <h1 id="site-title">The Mozilla <span>Blog</span></h1>
    <p id="tagline"><?php bloginfo('description'); ?></p>
  </header><!-- end #branding -->
