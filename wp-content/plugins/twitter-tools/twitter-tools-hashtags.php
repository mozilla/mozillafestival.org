<?php
/*
Plugin Name: Twitter Tools - Hashtags 
Plugin URI: http://crowdfavorite.com/wordpress/ 
Description: Set #hashtags for blog post tweets sent by Twitter Tools. This plugin relies on Twitter Tools, configure it on the Twitter Tools settings page.
Version: 2.4
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

// ini_set('display_errors', '1'); ini_set('error_reporting', E_ALL);

if (!defined('PLUGINDIR')) {
	define('PLUGINDIR','wp-content/plugins');
}

load_plugin_textdomain('twitter-tools-hash');

function aktt_hash_post_options() {
	global $self, $post;
	$self == 'post-new.php' ? $hashtags = get_option('aktt_hash_tags') : $hashtags = get_post_meta($post->ID, '_aktt_hash_meta', true);
	echo '<p>
		<label for="_aktt_hash_meta">'.__('#hashtags:', 'twitter-tools-hash').'</label>
		<input type="text" id="_aktt_hash_meta" name="_aktt_hash_meta" value="'.attribute_escape($hashtags).'" />
	</p>';
}
add_action('aktt_post_options', 'aktt_hash_post_options');

function aktt_hash_do_blog_post_tweet($tweet, $post) {
	$hashtags = get_post_meta($post->ID, '_aktt_hash_meta', true);
	$overall_len = strlen($tweet->tw_text.' '.$hashtags);
	if ($overall_len <= 140) {
		$tweet->tw_text .= ' '.$hashtags;
	}
	else {
// check overall needed length
		$needed = $overall_len - 140;
		$title = html_entity_decode($post->post_title, ENT_COMPAT, 'UTF-8');
		$title_len = strlen($title);
// try to regain the space by truncating the title
// if we need more than half of the title, only take half - we'll trim some hashtags later
		($needed + 3) >= (ceil($title_len / 2) + 3) ? $title_trim = substr($title, 0, ceil($title_len / 2)).'...' : $title_trim = substr($title, 0, strlen($title) - ($needed + 3)).'...';
// reconstruct the tweet
		global $aktt;
		$tweet_text = sprintf(__($aktt->tweet_format, 'twitter-tools'), $title_trim, apply_filters('tweet_blog_post_url', get_permalink($post->ID)));
// if hashtags now fit, add hashtags
		if (strlen($tweet_text.' '.$hashtags) <= 140) {
// yay, we're done!
			$tweet->tw_text = $tweet_text.' '.$hashtags;
		}
		else {
			$tagged_tweet = $tweet_text;
// drop hashtags if multiple
			if (strpos($hashtags, ' ') !== false) {
				$hashtags_array = explode(' ', $hashtags);
				foreach ($hashtags_array as $hashtag) {
					$test = $tagged_tweet.' '.$hashtag;
					if (strlen($test) <= 140) {
						$tagged_tweet = $test;
					}
				}
				$tweet->tw_text = $tagged_tweet;
			}
		}
	}
	return $tweet;
}
add_filter('aktt_do_blog_post_tweet', 'aktt_hash_do_blog_post_tweet', 10, 2);

function aktt_hash_save_post($post_id, $post) {
	if (current_user_can('edit_post', $post_id)) {
		update_post_meta($post_id, '_aktt_hash_meta', $_POST['_aktt_hash_meta']);
	}
}
add_action('save_post', 'aktt_hash_save_post', 10, 2);

function aktt_hash_request_handler() {
	if (!empty($_POST['cf_action'])) {
		switch ($_POST['cf_action']) {
			case 'aktt_hash_update_settings':
				if (!wp_verify_nonce($_POST['_wpnonce'], 'aktt_hash_update_settings')) {
					wp_die('Oops, please try again.');
				}
				aktt_hash_save_settings();
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&updated=true'));
				die();
				break;
		}
	}
}
add_action('init', 'aktt_hash_request_handler');

$aktt_hash_settings = array(
	'aktt_hash_tags' => array(
		'type' => 'string',
		'label' => __('Default #hashtags for blog post tweets', 'twitter-tools-hash'),
		'default' => '',
		'help' => __('include the #, example: #twittertools', 'twitter-tools-hash'),
	),
);

function aktt_hash_setting($option) {
	$value = get_option($option);
	if (empty($value)) {
		global $aktt_hash_settings;
		$value = $aktt_hash_settings[$option]['default'];
	}
	return $value;
}

if (!function_exists('cf_settings_field')) {
	function cf_settings_field($key, $config) {
		$option = get_option($key);
		if (empty($option) && !empty($config['default'])) {
			$option = $config['default'];
		}
		$label = '<label for="'.$key.'">'.$config['label'].'</label>';
		$help = '<span class="help">'.$config['help'].'</span>';
		switch ($config['type']) {
			case 'select':
				$output = $label.'<select name="'.$key.'" id="'.$key.'">';
				foreach ($config['options'] as $val => $display) {
					$option == $val ? $sel = ' selected="selected"' : $sel = '';
					$output .= '<option value="'.$val.'"'.$sel.'>'.htmlspecialchars($display).'</option>';
				}
				$output .= '</select>'.$help;
				break;
			case 'textarea':
				$output = $label.'<textarea name="'.$key.'" id="'.$key.'">'.htmlspecialchars($option).'</textarea>'.$help;
				break;
			case 'string':
			case 'int':
			default:
				$output = $label.'<input name="'.$key.'" id="'.$key.'" value="'.htmlspecialchars($option).'" />'.$help;
				break;
		}
		return '<div class="option">'.$output.'<div class="clear"></div></div>';
	}
}

function aktt_hash_settings_form() {
	global $aktt_hash_settings;

	print('
<div class="wrap">
	<h2>'.__('#hashtags for Twitter Tools', 'twitter-tools-hash').'</h2>
	<form id="aktt_hash_settings_form" name="aktt_hash_settings_form" class="aktt" action="'.admin_url('options-general.php').'" method="post">
		<input type="hidden" name="cf_action" value="aktt_hash_update_settings" />
		<fieldset class="options">
	');
	foreach ($aktt_hash_settings as $key => $config) {
		echo cf_settings_field($key, $config);
	}
	print('
		</fieldset>
		<p class="submit">
			<input type="submit" name="submit" class="button-primary" value="'.__('Save Settings', 'twitter-tools-hash').'" />
		</p>
		'.wp_nonce_field('aktt_hash_update_settings', '_wpnonce', true, false).wp_referer_field(false).'
	</form>
</div>
	');
}
add_action('aktt_options_form', 'aktt_hash_settings_form');

function aktt_hash_save_settings() {
	if (!current_user_can('manage_options')) {
		return;
	}
	global $aktt_hash_settings;
	foreach ($aktt_hash_settings as $key => $option) {
		$value = '';
		switch ($option['type']) {
			case 'int':
				$value = intval($_POST[$key]);
				break;
			case 'select':
				$test = stripslashes($_POST[$key]);
				if (isset($option['options'][$test])) {
					$value = $test;
				}
				break;
			case 'string':
			case 'textarea':
			default:
				$value = stripslashes($_POST[$key]);
				break;
		}
		update_option($key, $value);
	}
}

//a:21:{s:11:"plugin_name";s:26:"Hashtags for Twitter Tools";s:10:"plugin_uri";s:35:"http://crowdfavorite.com/wordpress/";s:18:"plugin_description";s:46:"Set #hastags for tweets sent by Twitter Tools.";s:14:"plugin_version";s:3:"1.0";s:6:"prefix";s:9:"aktt_hash";s:12:"localization";s:18:"twitter-tools-hash";s:14:"settings_title";s:27:"#hashtags for Twitter Tools";s:13:"settings_link";s:27:"#hashtags for Twitter Tools";s:4:"init";b:0;s:7:"install";b:0;s:9:"post_edit";s:1:"1";s:12:"comment_edit";b:0;s:6:"jquery";b:0;s:6:"wp_css";b:0;s:5:"wp_js";b:0;s:9:"admin_css";b:0;s:8:"admin_js";b:0;s:15:"request_handler";s:1:"1";s:6:"snoopy";b:0;s:11:"setting_cat";b:0;s:14:"setting_author";b:0;}

?>