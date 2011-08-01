  </div><!-- end #doc -->
  
  <!-- start #footer -->
  <footer id="sub-footer">
    <div id="sub-footer-contents">
      <h3>Let&#8217;s be <span>friends!</span></h3>
      <ul>
        <li id="footer-twitter"><a href="http://twitter.com/firefox">Twitter</a></li>
        <li id="footer-facebook"><a href="http://Facebook.com/Firefox">Facebook</a></li>
        <li id="footer-connect"><a href="http://www.mozilla.com/firefox/connect/">More Ways to Connect</a></li>
      </ul>
      <p id="sub-footer-newsletter">
        <span class="intro">Want us to keep in touch?</span>
        <a href="http://www.mozilla.com/newsletter/">Get Monthly News <span>»</span></a>
      </p>
    </div>
  </footer>

  <footer id="footer">
    <div id="footer-contents" role="contentinfo">
      <h3 id="footer-logo"><a href="http://www.mozilla.com/firefox/" title="Back to home page">Firefox</a></h3>

      <nav id="footer-menu" role="navigation">
        <ul>
          <li class="first"><a href="http://www.mozilla.com/firefox/features/">Features</a>
            <ul>
              <li class="first"><a href="http://www.mozilla.com/firefox/features/">Features</a></li>
              <li><a href="http://www.mozilla.com/firefox/security/">Security</a></li>
              <li><a href="http://www.mozilla.com/firefox/performance/">Performance</a></li>
              <li><a href="http://www.mozilla.com/firefox/customize/">Customization</a></li>
              <li><a href="http://www.mozilla.com/firefox/video/">Videos</a></li>
              <li><a href="http://www.mozilla.com/firefox/central/">Tour</a></li>
              <li class="last"><a href="http://www.mozilla.com/firefox/channel/">Future Releases</a></li>
            </ul>
          </li>
          <li><a href="http://www.mozilla.com/mobile/">Mobile</a>
            <ul>
              <li class="first"><a href="http://www.mozilla.com/mobile/">Mobile Overview</a></li>
              <li><a href="http://www.mozilla.com/mobile/download/">Download</a></li>
              <li><a href="http://www.mozilla.com/mobile/features/">Features</a></li>
              <li><a href="https://addons.mozilla.org/en-US/mobile/?browse=featured">Customize</a></li>
              <li><a href="http://www.mozilla.com/mobile/sync/">Sync</a></li>
              <li><a href="https://developer.mozilla.org/en-US/mobile">Develop</a></li>
              <li><a href="http://www.mozilla.com/mobile/getinvolved/">Get Involved</a></li>
              <li><a href="http://www.mozilla.com/mobile/faq/">FAQ</a></li>
              <li><a href="https://blog.mozilla.com/mobile/">Blog</a></li>
              <li class="last"><a href="http://www.mozilla.com/firefox/channel/">Future Releases</a></li>
            </ul>

          </li>
          <li><a href="https://addons.mozilla.org/">Add-ons</a>
            <ul>
              <li class="first"><a href="https://addons.mozilla.org/firefox/">Firefox Add-ons</a></li>
              <li><a href="https://addons.mozilla.org/firefox/featured/">Featured Add-ons</a></li>
              <li><a href="https://addons.mozilla.org/firefox/extensions/">Extensions</a></li>
              <li><a href="https://addons.mozilla.org/firefox/themes/">Themes</a></li>
              <li><a href="http://www.getpersonas.com/">Personas</a></li>
              <li><a href="https://addons.mozilla.org/firefox/search-tools/">Search Tools</a></li>
              <li><a href="https://addons.mozilla.org/firefox/language-tools/">Language Support</a></li>
              <li><a href="https://addons.mozilla.org/firefox/collections/">Collections</a></li>
              <li><a href="https://addons.mozilla.org/mobile/">Mobile Add-ons</a></li>
              <li class="last"><a href="https://addons.mozilla.org/firefox/developers/">Developer Hub</a></li>
            </ul>
          </li>
          <li><a href="http://support.mozilla.com/">Support</a>
            <ul>
              <li class="first"><a href="http://support.mozilla.com/en-US/kb/">Firefox Support</a></li>
              <li><a href="http://support.mozilla.com/mobile">Mobile Support</a></li>
              <li class="last"><a href="http://www.mozilla.org/support/thunderbird/">Thunderbird Support</a></li>
            </ul>
          </li>
          <li class="current last"><a href="http://www.mozilla.com/about/">About</a>
            <ul>
              <li class="first"><a href="http://www.mozilla.com/about/">About Firefox</a></li>
              <li><a href="http://www.mozilla.com/about/participate/">Participate</a></li>
              <li><a href="http://www.mozilla.com/press/">Communications</a></li>
              <li><a href="http://www.mozilla.com/about/careers.html">Careers</a></li>
              <li><a href="http://www.mozilla.com/about/partnerships.html">Partnerships</a></li>
              <li><a href="http://www.mozilla.com/about/legal.html">Legal</a></li>
              <li><a href="http://www.mozilla.com/about/contact.html">Contact Us</a></li>
              <li class="last"><a href="<?php bloginfo('url'); ?>">Blog</a></li>
            </ul>

          </li>
        </ul>
      </nav><!-- end #footer-menu -->

      <div id="copyright">
        <p id="footer-links"><a href="http://www.mozilla.com/privacy-policy.html">Privacy Policy</a> &nbsp;|&nbsp; 
        <a href="http://www.mozilla.com/about/legal.html">Legal Notices</a> &nbsp;|&nbsp;
        <a href="http://www.mozilla.com/legal/fraud-report/index.html">Report Trademark Abuse</a></p>
        <p>Except where otherwise <a href="http://www.mozilla.com/about/legal.html#site">noted</a>, content on this site is licensed under the <br /><a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution Share-Alike License v3.0</a> or any later version.</p>
      </div>

    </div>
  </footer><!-- end #footer -->

  <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/superfish.js"></script>
  <script type="text/javascript">
    // <![CDATA[
    jQuery(document).ready(function() { 
      // Set up menus.
      jQuery("#nav-main #menu").superfish({
        delay: 0,
        animation: {opacity:'show'},
        speed: 50
      });
    });
    // ]]>
  </script>

  <?php wp_footer(); ?>
</body>
</html>
