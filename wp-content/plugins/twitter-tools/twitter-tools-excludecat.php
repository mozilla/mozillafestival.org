<?php
/*
Plugin Name: Twitter Tools - Exclude Category 
Plugin URI: http://crowdfavorite.com/wordpress/ 
Description: Exclude posts in certain categories from being tweeted by Twitter Tools. This plugin relies on Twitter Tools, configure it on the Twitter Tools settings page.
Version: 2.4
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

// ini_set('display_errors', '1'); ini_set('error_reporting', E_ALL);

if (!defined('PLUGINDIR')) {
	define('PLUGINDIR','wp-content/plugins');
}

load_plugin_textdomain('twitter-tools-excludecat');

function aktt_excludecat_request_handler() {
	if (!empty($_POST['cf_action'])) {
		switch ($_POST['cf_action']) {
			case 'aktt_excludecat_update_settings':
				if (!wp_verify_nonce($_POST['_wpnonce'], 'aktt_excludecat_update_settings')) {
					wp_die('Oops, please try again.');
				}
				aktt_excludecat_save_settings();
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&updated=true'));
				die();
				break;
		}
	}
}
add_action('init', 'aktt_excludecat_request_handler');

function aktt_excludecat_do_blog_post_tweet($tweet, $post) {
	$cats = get_option('aktt_excludecats');
	if (is_array($cats) && count($cats)) {
		foreach ($cats as $cat) {
			if (in_category($cat, $post)) {
				return false;
			}
		}
	}
	return $tweet;
}
add_filter('aktt_do_blog_post_tweet', 'aktt_excludecat_do_blog_post_tweet', 10, 2);

function aktt_excludecat_settings_form() {
	print('
<style class="text/css">
#aktt_exclude_cat_options {
	list-style: none;
}
#aktt_exclude_cat_options li {
	float: left;
	margin: 0 0 5px 0;
	padding: 3px;
	width: 200px;
}
</style>
<script type="text/javascript">
jQuery(function() {
	jQuery("#aktt_excludecat_select").click(function() {
		jQuery("#aktt_exclude_cat_options input[type=checkbox]").attr("checked", true);
		return false;
	});
	jQuery("#aktt_excludecat_unselect").click(function() {
		jQuery("#aktt_exclude_cat_options input[type=checkbox]").attr("checked", false);
		return false;
	});
});
</script>
<div class="wrap">
	<h2>'.__('Exclude Categories for Twitter Tools', 'twitter-tools-excludecat').'</h2>
	<form id="aktt_excludecat_settings_form" name="aktt_excludecat_settings_form" action="'.admin_url('options-general.php').'" method="post">
		<input type="hidden" name="cf_action" value="aktt_excludecat_update_settings" />
		<fieldset class="options">
			<p>'.__('Posts in selected categories will be excluded from blog post tweets', 'twitter-tools-excludecat').'</p>
			<p><a href="#" id="aktt_excludecat_select">'.__('Select All', 'twitter-tools-excludecat').'</a> | <a href="#" id="aktt_excludecat_unselect">'.__('Unselect All', 'twitter-tools-excludecat').'</a></p>
			<ul id="aktt_exclude_cat_options">
	');

	$categories = get_categories('hide_empty=0');
	$exclude_cats = get_option('aktt_excludecats');
	if (!is_array($exclude_cats)) {
		$exclude_cats = array();
	}
	foreach ($categories as $cat) {
		$id = 'aktt_exclude_cat_'.$cat->term_id;
		in_array($cat->term_id, $exclude_cats) ? $checked = ' checked="checked"' : $checked = '';
		print('
				<li>
					<input type="checkbox" name="aktt_exclude_cats[]" value="'.$cat->term_id.'" id="'.$id.'" '.$checked.' />
					<label for="'.$id.'">'.htmlspecialchars($cat->name).'</label>
				</li>
		');
	}
	print('
			</ul>
			<div class="clear"></div>
		</fieldset>
		<p class="submit">
			<input type="submit" name="submit" value="'.__('Save Settings', 'twitter-tools-excludecat').'" class="button-primary" />
		</p>
		'.wp_nonce_field('aktt_excludecat_update_settings', '_wpnonce', true, false).wp_referer_field(false).'
	</form>
</div>
	');
}
add_action('aktt_options_form', 'aktt_excludecat_settings_form');

function aktt_excludecat_save_settings() {
	if (!current_user_can('manage_options')) {
		return;
	}
	$cats = array();
	if (isset($_POST['aktt_exclude_cats']) && is_array($_POST['aktt_exclude_cats'])) {
		foreach ($_POST['aktt_exclude_cats'] as $cat_id) {
			$cat_id = intval($cat_id);
			if ($cat_id) {
				$cats[] = $cat_id;
			}
		}
	}
	update_option('aktt_excludecats', $cats);
}

//a:21:{s:11:"plugin_name";s:34:"Twitter Tools - Exclude Categories";s:10:"plugin_uri";s:35:"http://crowdfavorite.com/wordpress/";s:18:"plugin_description";s:72:"Exclude posts in certain categories from being tweeted by Twitter Tools.";s:14:"plugin_version";s:3:"1.0";s:6:"prefix";s:15:"aktt_excludecat";s:12:"localization";s:24:"twitter-tools-excludecat";s:14:"settings_title";s:34:"Exclude Category for Twitter Tools";s:13:"settings_link";s:34:"Exclude Category for Twitter Tools";s:4:"init";b:0;s:7:"install";b:0;s:9:"post_edit";b:0;s:12:"comment_edit";b:0;s:6:"jquery";b:0;s:6:"wp_css";b:0;s:5:"wp_js";b:0;s:9:"admin_css";b:0;s:8:"admin_js";b:0;s:15:"request_handler";s:1:"1";s:6:"snoopy";b:0;s:11:"setting_cat";s:1:"1";s:14:"setting_author";b:0;}

?>