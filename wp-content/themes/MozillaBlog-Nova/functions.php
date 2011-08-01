<?php
/*********
* Add jQuery
*/
function fc_add_jquery() {
  wp_enqueue_script('jquery');
}
add_action( 'init', 'fc_add_jquery' );

/*********
* Allow uploading some additional MIME types
*/
function fc_add_mimes( $mimes=array() ) {
  $mimes['ogv'] = 'video/ogg';
  $mimes['mp4'] = 'video/mp4';
  $mimes['m4v'] = 'video/mp4';
  $mimes['flv'] = 'video/x-flv';
  return $mimes;
}
add_filter('upload_mimes', 'fc_add_mimes');

/*********
* Customize the login screen
*/
function fc_custom_login() { 
  echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/css/login.css">'; 
}
add_action('login_head', 'fc_custom_login');

/*********
* Style the visual editor to match the theme styles
*/
add_filter('mce_css', 'my_editor_style');
function my_editor_style($url) {
  if ( !empty($url) ) {
    $url .= ',';
  }
  $url .= trailingslashit( get_stylesheet_directory_uri() ) . '/css/editor-style.css';
  return $url;
}

/*********
* Register widget areas
*/
if ( function_exists('register_sidebars') ) :

  /** Sidebar */
  register_sidebar(array(
  'name' => 'Sidebar',
  'id' => 'sidebar',
  'description' => 'Appears at the bottom of the global sidebar.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

endif;


/*********
* Get separate counts for pings, trackbacks, and comments
* Plugin Name: Ping/Track/Comment Count
* Author: Mark Jaquith
*/

function fc_get_comment_type_count($type='all', $post_id = 0) {
	global $cjd_comment_count_cache, $id, $post;
	if ( !$post_id )
		$post_id = $post->ID;
	if ( !$post_id )
		return;

	if ( !isset($cjd_comment_count_cache[$post_id]) ) {
		$p = get_post($post_id);
		$p = array($p);
		fc_update_comment_type_cache($p);
	}

	if ( $type == 'pingback' || $type == 'trackback' || $type == 'comment' )
		return $cjd_comment_count_cache[$post_id][$type];
	elseif ( $type == 'ping' )
		return $cjd_comment_count_cache[$post_id]['pingback'] + $cjd_comment_count_cache[$post_id]['trackback'];
	else
		return array_sum((array) $cjd_comment_count_cache[$post_id]);

}

function fc_comment_type_count($type = 'all', $post_id = 0) {
		echo fc_get_comment_type_count($type, $post_id);
}


function fc_update_comment_type_cache(&$queried_posts) {
	global $cjd_comment_count_cache, $wpdb;

	if ( !$queried_posts )
		return $queried_posts;


	foreach ( (array) $queried_posts as $post )
		if ( !isset($cjd_comment_count_cache[$post->ID]) )
			$post_id_list[] = $post->ID;

	if ( $post_id_list ) {
		$post_id_list = implode(',', $post_id_list);

		foreach ( array('', 'pingback', 'trackback') as $type ) {
			$counts = $wpdb->get_results("SELECT ID, COUNT( comment_ID ) AS ccount
			FROM $wpdb->posts
			LEFT JOIN $wpdb->comments ON ( comment_post_ID = ID AND comment_approved = '1' AND comment_type='$type' )
			WHERE post_status = 'publish' AND ID IN ($post_id_list)
			GROUP BY ID");

			if ( $counts ) {
				if ( '' == $type )
					$type = 'comment';
				foreach ( $counts as $count )
					$cjd_comment_count_cache[$count->ID][$type] = $count->ccount;
			}
		}
	}
	return $queried_posts;
}
add_filter('the_posts', 'fc_update_comment_type_cache');


/*********
* Determine whether or not to show share/trackbacks/comments
* Just a series of tests. If all of these fail, return false.
*/
function fc_show_spread() {
// If ShareThis is turned on.
	if (function_exists('sharethis_button')) { return true; }
// If pings are open or there is at least one ping to show.
	if ( pings_open() || (fc_get_comment_type_count('ping') > 0 ) ) { return true; }
// If comments are open or there is at least one comment to show.
	if ( comments_open() || (fc_get_comment_type_count('comment') > 0) ) { return true; }
// Else all of these have failed, so there's no need to show the links.
	else { return false; }
}

/**********
* Determine if the page is paged and should show posts navigation
*/
function fc_show_posts_nav() {
 global $wp_query;
 return ($wp_query->max_num_pages > 1) ? TRUE : FALSE;
}

/*********
* Determine if a previous post exists (i.e. that this isn't the first one)
*
* @param bool $in_same_cat Optional. Whether link should be in same category.
* @param string $excluded_categories Optional. Excluded categories IDs.
*/
function fc_previous_post($in_same_cat = false, $excluded_categories = '') {
  if ( is_attachment() )
    $post = & get_post($GLOBALS['post']->post_parent);
  else
    $post = get_previous_post($in_same_cat, $excluded_categories);
  if ( !$post )
    return false;
  else
    return true;
}

/*********
* Determine if a next post exists (i.e. that this isn't the last post)
*
* @param bool $in_same_cat Optional. Whether link should be in same category.
* @param string $excluded_categories Optional. Excluded categories IDs.
*/
function fc_next_post($in_same_cat = false, $excluded_categories = '') {
  $post = get_next_post($in_same_cat, $excluded_categories);
  if ( !$post )
    return false;
  else
    return true;
}

/*********
* Determines if the current page is the result of paged comments.
* This lets us prevent search engines from indexing lots of duplicate pages 
* (since the post is repeated on every paged comment page).
*/
function is_comments_paged_url() {
  $pos = strpos($_SERVER['REQUEST_URI'], "comment-page");
  if ($pos === false) { return false; }
  else { return true; }
}

/*********
* Prints the page number currently being browsed, with a pipe before it.
* Used in header.php to add the page number to the <title>.
*/
if ( ! function_exists( 'fc_page_number' ) ) :
function fc_page_number() {
  global $paged; // Contains page number.
  if ( $paged >= 2 )
    echo ' | ' . sprintf( __( 'Page %s' , 'mozblog' ), $paged );
}
endif;

/*********
* Provide a custom default gravatar
*/
function fc_avatar ($avatar_defaults) {
  $myavatar = get_bloginfo('template_directory') . '/img/avatar.png';
  $avatar_defaults[$myavatar] = "Mozilla Blog";
  return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'fc_avatar' );

/*********
* Add more-links to excerpts
*/
function fc_excerpt_more($post) {
  return '&hellip; <a class="more-link" href="'.get_permalink($post->ID).'" title="Read the rest of &#8220;'.get_the_title_rss($post->ID).'&#8221;">'.'Read more'.'</a>';
}
add_filter('excerpt_more', 'fc_excerpt_more');


/*********
* Comment Template for the Mozilla blog theme
*/
if ( ! function_exists( 'mozblog_comment' ) ) :
function mozblog_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
  $comment_type = get_comment_type();
?>

 <li id="comment-<?php comment_ID(); ?>" <?php comment_class('hentry'); ?>>
  <?php if ( $comment_type == 'trackback' ) : ?>
    <h3 class="entry-title"><?php _e( 'Trackback from ', 'mozblog' ); ?> <cite><?php comment_author_link(); ?></cite>
      <span class="comment-meta">on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title=" <?php _e('Permanent link to this comment by ','mozblog'); comment_author(); ?>"><time class="published" datetime="<?php comment_date('Y-m-d'); ?>" title="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></time> at <?php comment_time(); ?></a>:</span>
    </h3>
  <?php elseif ( $comment_type == 'pingback' ) : ?>
    <h3 class="entry-title"><?php _e( 'Pingback from ', 'mozblog' ); ?> <cite><?php comment_author_link(); ?></cite>
      <span class="comment-meta">on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title="<?php _e('Permanent link to this comment by ','mozblog'); comment_author(); ?>"><time class="published" datetime="<?php comment_date('Y-m-d'); ?>" title="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></time> at <?php comment_time(); ?></a>:</span>
    </h3>
  <?php else : ?>
    <?php if ( ( $comment->comment_author_url != "http://" ) && ( $comment->comment_author_url != "" ) ) : // if author has a link ?>
     <h3 class="entry-title vcard">
       <a href="<?php comment_author_url(); ?>" class="url" rel="nofollow external" title="<?php comment_author_url(); ?>">
         <?php if (function_exists('get_avatar')) : echo ('<span class="photo">'.get_avatar( $comment, 36 ).'</span>'); endif; ?>
         <cite class="author fn"><?php comment_author(); ?></cite>
       </a>
       <span class="comment-meta">wrote on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title="<?php _e('Permanent link to this comment by ','mozblog'); comment_author(); ?>"><time class="published" datetime="<?php comment_date('Y-m-d'); ?>" title="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></time> at <?php comment_time(); ?></a>:</span>
     </h3>
    <?php else : // author has no link ?>
      <h3 class="entry-title vcard">
        <?php if (function_exists('get_avatar')) : echo ('<span class="photo">'.get_avatar( $comment, 36 ).'</span>'); endif; ?>
        <cite class="author fn"><?php comment_author(); ?></cite>
        <span class="comment-meta">wrote on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title="<?php _e('Permanent link to this comment by ','mozblog'); comment_author(); ?>"><time class="published" datetime="<?php comment_date('Y-m-d'); ?>" title="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></time> at <?php comment_time(); ?></a>:</span>
      </h3>
    <?php endif; ?>
  <?php endif; ?>

    <?php if ($comment->comment_approved == '0') : ?>
      <p class="mod"><strong><?php _e('Your comment is awaiting moderation.'); ?></strong></p>
    <?php endif; ?>

    <blockquote class="entry-content">
      <?php comment_text(); ?>
    </blockquote>

  <?php if ( (get_option('thread_comments') == true) || (current_user_can('edit_post', $comment->comment_post_ID)) ) : ?>
    <p class="comment-util"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?> <?php if ( current_user_can('edit_post', $comment->comment_post_ID) ) : ?><span class="edit"><?php edit_comment_link(__('Edit Comment','mozblog'),'',''); ?></span><?php endif; ?></p>
  <?php endif; ?>
<?php
} /* end mozblog_comment */
endif;


/*
Plugin Name: Smart Archives
Version: 1.9.2
Plugin URI: http://justinblanton.com/projects/smartarchives/
Description: A simple, clean, and future-proof way to present your archives.
Author: Justin Blanton
Author URI: http://justinblanton.com
*/

function fc_archives($format='both', $catID='') {

    global $wpdb, $PHP_SELF;
    setlocale(LC_ALL,WPLANG); // set localization language
    $now = gmdate("Y-m-d H:i:s",(time()+((get_settings('gmt_offset'))*3600)));  // get the current GMT date
    $bogusDate = "/01/2001"; // used for the strtotime() function below	
	
	$yearsWithPosts = $wpdb->get_results("
	    SELECT distinct year(post_date) AS `year`, count(ID) as posts
	    FROM $wpdb->posts 
	    WHERE post_type = 'post' 
	    AND post_status = 'publish' 
	    GROUP BY year(post_date) 
	    ORDER BY post_date DESC");
	
	foreach ($yearsWithPosts as $currentYear) {
		
		for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
			
			$monthsWithPosts[$currentYear->year][$currentMonth] = $wpdb->get_results("
			SELECT ID, post_title FROM $wpdb->posts 
			WHERE post_type = 'post'
			AND post_status = 'publish' 
			AND year(post_date) = '$currentYear->year' 
			AND month(post_date) = '$currentMonth'			
			ORDER BY post_date DESC");
		}
	}
	
	if (($format == 'both') || ($format == 'block')) { // check to see if we are supposed to display the block
		
		// get the shortened month name; strftime() should localize
    	for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) $shortMonths[$currentMonth] = ucfirst(strftime("%b", strtotime("$currentMonth"."$bogusDate")));
		
		if ($yearsWithPosts) {
      echo '<ol class="archive-cal">';
			
			foreach ($yearsWithPosts as $currentYear) {	
				echo ('<li class="archive-year"><strong><a href="'.get_year_link($currentYear->year, $currentYear->year).'">'.$currentYear->year.'</a>:</strong> ');
				for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
					if ($monthsWithPosts[$currentYear->year][$currentMonth]) echo ('<a href="'.get_month_link($currentYear->year, $currentMonth).'">'.$shortMonths[$currentMonth].'</a> ');
					else echo '<span class="empty-month">'.$shortMonths[$currentMonth].'</span> ';
				}
				echo '</li>';
			}			
			echo '</ol>';		
		}	
	}
	
	if (($format == 'both') || ($format == 'list')) { //check to see if we are supposed to display the list
		
		// get the month name; strftime() should localize
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) $monthNames[$currentMonth] = ucfirst(strftime("%B", strtotime("$currentMonth"."$bogusDate")));
		
		if ($yearsWithPosts) {
			
			if ($catID != '') { // at least one category was specified to be excluded
			    $catIDs = explode(" ", $catID); // put the category(ies) into an array
			    foreach($yearsWithPosts as $currentYear) {
    				for ($currentMonth = 12; $currentMonth >= 1; $currentMonth--) {
    					if ($monthsWithPosts[$currentYear->year][$currentMonth]) {
    					    echo ('<h2><a href="'.get_month_link($currentYear->year, $currentMonth).'">'.$monthNames[$currentMonth].' '.$currentYear->year.'</a></h2>');
    					    echo '<ul class="archive-list">';
    						foreach ($monthsWithPosts[$currentYear->year][$currentMonth] as $post) { 
    						    if ($post->post_date <= $now) {
    						        $cats = wp_get_post_categories($post->ID);
        						    $found = false;
        						    foreach ($cats as $cat) if (in_array($cat, $catIDs)) $found = true;
        	                 if (!$found) echo ('<li><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></li>');
        	          } 
	              }   
    						echo '</ul>';  
        		  }
        		}
    			}
    		}
    		
    		else { // we don't need to exclude any categories
		        foreach($yearsWithPosts as $currentYear) {
    				for ($currentMonth = 12; $currentMonth >= 1; $currentMonth--) {
    					if ($monthsWithPosts[$currentYear->year][$currentMonth]) {
    						echo ('<h2><a href="'.get_month_link($currentYear->year, $currentMonth).'">'.$monthNames[$currentMonth].' '.$currentYear->year.'</a></h2>'); 
    						echo '<ul class="archive-list">';
    						foreach ($monthsWithPosts[$currentYear->year][$currentMonth] as $post) echo ('<li><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></li>');
    						echo '</ul>';
    					}
    				}
    			}
		    }   		    				
		}
	}
}

?>
