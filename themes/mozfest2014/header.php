<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); echo (is_home() ? '' : wp_title('&raquo;',false)); ?></title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/media/css/core.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <link href="//www.mozilla.org/tabzilla/media/css/tabzilla.css" rel="stylesheet" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <div id="header">
      <div class="constrained" id="tabzilla-container">
        <a href="http://www.mozilla.org/" id="tabzilla">mozilla</a>
      </div>
      <div class="constrained">
        <header>
          <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?><span></span></a></h1>
          <p class="site-desciption"><?php bloginfo('description'); ?></p>
        </header>
        <a id="jump-to-content" href="#content">Jump to content</a>
        <div class="sign-up-for-updates">
          <button onclick="location.href='https://sendto.mozilla.org/page/signup/mozfest2014-save-the-date'">Sign up for updates</button>
        </div>
      </div>
    </div>
    <nav id="top-nav">
      <div class="constrained">
        <input type="checkbox" role="presentation" id="show-navigation">
        <ul class="menu menu-count-<?php echo mf2012_menu_meta('header')->count + 1; ?>">
          <?php if (esc_attr(get_theme_mod('mf2012_header_button_label')) != '') : ?>
          <li class="register"><a href="<?php echo esc_attr( get_theme_mod( 'mf2012_header_button_target' ) ); ?>" class="button"><?php echo esc_attr( get_theme_mod( 'mf2012_header_button_label' ) ); ?></a></li>
          <?php endif; ?>
          <?php
            if (has_nav_menu('header')) {
              wp_nav_menu(array(
                'theme_location' => 'header',
                'container' => false,
                'items_wrap' => '%3$s',
                'depth' => 1,
              ));
            }
          ?>
        </ul>
        <label for="show-navigation" title="Toggle Menu"></label>
      </div>
    </nav>
