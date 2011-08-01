  <aside id="content-sub" role="complementary">

    <?php include (TEMPLATEPATH . '/searchform.php'); ?>

    <div id="categories">
      <h2 class="widgettitle">Categories</h2>
      <ul>
        <?php wp_list_categories('show_count=0&title_li='); ?>
      </ul>
    </div>

    <div id="archives">
      <h2 class="widgettitle">Archives</h2>
      <ul>
      <?php if (function_exists('fc_archives') && get_page_by_path('archives') ) : ?>
        <?php wp_get_archives('type=monthly&limit=12'); ?>
        <li><a href="<?php echo get_permalink(get_page_by_path('archives')->ID); ?>"><strong>Complete Archives</strong></a></li>
      <?php else : ?>
        <?php wp_get_archives('type=monthly'); ?>
      <?php endif; ?>
      </ul>
    </div>

    <div class="more">
      <div id="externals">
        <h2 class="widgettitle">More from <span>Mozilla</span></h2>
        <ul>
          <li><a rel="external" href="http://developer.mozilla.org">Mozilla Developer Center</a></li>
          <li><a rel="external" href="http://labs.mozilla.com">Mozilla Labs</a></li>
          <li><a rel="external" href="http://planet.mozilla.org">Planet Mozilla Blogs</a></li>
          <li><a rel="external" href="http://mozilla.com/en-US/press">Press Center</a></li>
          <li><a rel="external" href="http://spreadfirefox.com">Spread Firefox</a></li>
          <li><a rel="external" href="http://blog.mozilla.com/security">Mozilla Security Blog</a></li>
          <li><a rel="external" href="http://air.mozilla.com">Air Mozilla</a></li>
          <li><a rel="external" href="http://blog.mozilla.com/mobile/">Mozilla Mobile Blog</a></li>
        </ul>
      </div>

      <div id="side-social">
        <h2 class="widgettitle">Connect <span>With Us</span></h2>
        <ul>
          <li class="newsletter"><a href="http://www.mozilla.com/newsletter/">Newsletter</a></li>
          <li class="twitter"><a href="http://twitter.com/firefox">Twitter</a></li>
          <li class="facebook"><a href="http://www.facebook.com/Firefox?v=wall">Facebook</a></li>
        </ul>
      </div>
    </div>

  <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('sidebar') ) : else : endif; ?>

  </aside><!-- end #content-sub -->
