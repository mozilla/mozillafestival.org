<?php // Do not delete these lines
  if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');
  if ( post_password_required() ) {
    echo '<p class="nocomments">This post is password protected. Enter the password to view comments.</p>';
    return;
  }

  /* This variable is for alternating comment background */
  $oddcomment = 'alt';
?>
<?php /* You can start editing here. */ ?>

<?php if ( have_comments() || comments_open() ) : // If there are comments OR comments are open ?>
<section id="comments">
  <header class="comments-head">
    <h3><?php comments_number('No responses', 'One response', '% responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>
    <?php if (comments_open()) { ?><a class="cmt-post" href="#respond"><?php _e('Post a comment','mozblog'); ?></a><?php } ?>
  </header>

<?php if ( have_comments() ) : // If there are comments ?>
  <ol id="comment-list" class="hfeed <?php if (get_option('show_avatars')) echo 'av'; // provides a style hook when avatars are enabled ?>">
  <?php wp_list_comments('type=all&style=ol&callback=mozblog_comment'); // Comment template is in functions.php ?>
  </ol>

  <?php if ( get_comment_pages_count() > 1 ) : // If comment paging is enabled and there are enough comments to paginate, show the comment paging ?>
    <p class="pages">More comments: <?php paginate_comments_links(); ?></p>
  <?php endif; ?>

<?php endif; ?>

<?php if (comments_open()) : ?>
  <div id="respond">
    <?php if ( get_option('comment_registration') && !$user_ID ) : // If registration is required and you're not logged in, show a message ?>
    <p><strong>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</strong></p>

    <?php else : // else show the form ?>
    <form id="comment-form" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
      <fieldset>
        <legend><span><?php comment_form_title( __('Post Your Comment'), __('Reply to %s' ) ); ?></span></legend>
        <p id="cancel-comment-reply"><?php cancel_comment_reply_link('Cancel Reply'); ?></p>
        <ol>
        <?php if ( $user_ID ) : ?>
          <li class="self">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?>  </a> <a class="logout" href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out</a></li>
        <?php else : ?>
          <li id="cmt-name"><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="25" /> <label for="author">Your name <?php if ($req) echo "<abbr title='required'>*</abbr>"; ?></label> </li>
          <li id="cmt-email"><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="25" /> <label for="email">Your e-mail <?php if ($req) echo "<abbr title='required'>*</abbr>"; ?> <span class="note">(not published)</span></label></li>
          <li id="cmt-web"><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="25" /> <label for="url">Your website</label></li>
        <?php endif; ?>
          <li id="cmt-cmt"><label for="comment">Your comment</label> <textarea name="comment" id="comment" cols="50" rows="10"></textarea></li>
          <li id="comment-submit"><button name="submit" type="submit" id="submit"><?php _e('Submit Comment', 'mozblog'); ?></button>
          <?php comment_id_fields(); ?>
          <?php do_action('comment_form', $post->ID); ?></li>
        </ol>
      </fieldset>
    </form>
    <?php endif; // end if reg required and not logged in ?>
  </div><?php // end #respond ?>

  <?php if (get_option('require_name_email')) : ?>
  <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/fc-checkcomment.js"></script>
  <script type="text/javascript">jQuery("#comment-form").submit(function() { return fc_checkform(<?php if ($req) : echo "'req'"; endif; ?>); });</script>
  <?php endif; ?>

<?php endif; // end if comments open ?>

</section><?php // end #comments ?>

<?php endif; // if you delete this the sky will fall on your head ?>
