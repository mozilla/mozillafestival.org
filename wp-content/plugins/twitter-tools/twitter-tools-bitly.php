<?php
/*
Plugin Name: Twitter Tools - Bit.ly URLs 
Plugin URI: http://crowdfavorite.com/wordpress/ 
Description: Use Bit.ly for URL shortening with Twitter Tools. This plugin relies on Twitter Tools, configure it on the Twitter Tools settings page.
Version: 2.4
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

// Thanks to Porter Maus for his contributions.

// ini_set('display_errors', '1'); ini_set('error_reporting', E_ALL);

if (!defined('PLUGINDIR')) {
	define('PLUGINDIR','wp-content/plugins');
}

load_plugin_textdomain('twitter-tools-bitly');

define('AKTT_BITLY_API_SHORTEN_URL', 'http://api.bit.ly/shorten');
define('AKTT_BITLY_API_SHORTEN_URL_JMP', 'http://api.j.mp/shorten');
define('AKTT_BITLY_API_VERSION', '2.0.1');

function aktt_bitly_shorten_url($url) {
	$parts = parse_url($url);
	if (!in_array($parts['host'], array('j.mp', 'bit.ly'))) {
		$snoop = get_snoopy();
		$api_urls = array(
			'bitly' => AKTT_BITLY_API_SHORTEN_URL,
			'jmp' => AKTT_BITLY_API_SHORTEN_URL_JMP,
		);
		$api = $api_urls[aktt_bitly_setting('aktt_bitly_api_url')].'?version='.AKTT_BITLY_API_VERSION.'&longUrl='.urlencode($url);
		$login = get_option('aktt_bitly_api_login');
		$key = get_option('aktt_bitly_api_key');
		if (!empty($login) && !empty($key)) {
			$api .= '&login='.urlencode($login).'&apiKey='.urlencode($key).'&history=1';
		}
		$snoop->agent = 'Twitter Tools http://alexking.org/projects/wordpress';
		$snoop->fetch($api);
		$result = json_decode($snoop->results);
		if (!empty($result->results->{$url}->shortUrl)) {
			$url = $result->results->{$url}->shortUrl;
		}
	}
	return $url;
}
add_filter('tweet_blog_post_url', 'aktt_bitly_shorten_url');

function aktt_bitly_shorten_tweet($tweet) {
	if (strpos($tweet->tw_text, 'http') !== false) {
		preg_match_all('$\b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|]$i', $test, $urls);
		if (isset($urls[0]) && count($urls[0])) {
			foreach ($urls[0] as $url) {
// borrowed from WordPress's make_clickable code
				if ( in_array(substr($url, -1), array('.', ',', ';', ':', ')')) === true ) {
					$url = substr($url, 0, strlen($url)-1);
				}
				$tweet->tw_text = str_replace($url, aktt_bitly_shorten_url($url), $tweet->tw_text);
			}
		}
	}
	return $tweet;
}
add_filter('aktt_do_tweet', 'aktt_bitly_shorten_tweet');

function aktt_bitly_request_handler() {
	if (!empty($_POST['cf_action'])) {
		switch ($_POST['cf_action']) {
			case 'aktt_bitly_update_settings':
				if (!wp_verify_nonce($_POST['_wpnonce'], 'aktt_bitly_save_settings')) {
					wp_die('Oops, please try again.');
				}
				aktt_bitly_save_settings();
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&updated=true'));
				die();
				break;
		}
	}
}
add_action('init', 'aktt_bitly_request_handler');

$aktt_bitly_settings = array(
	'aktt_bitly_api_login' => array(
		'type' => 'string',
		'label' => __('Bit.ly Username', 'twitter-tools-bitly'),
		'default' => '',
		'help' => '',
	),
	'aktt_bitly_api_key' => array(
		'type' => 'string',
		'label' => __('Bit.ly API key', 'twitter-tools-bitly'),
		'default' => '',
		'help' => '',
	),
	'aktt_bitly_api_url' => array(
		'type' => 'select',
		'label' => __('URL to use', 'twitter-tools-bitly'),
		'default' => 'bitly',
		'options' => array(
			'bitly' => 'bit.ly',
			'jmp' => 'j.mp',
		),
		'help' => '',
	),
);

function aktt_bitly_setting($option) {
	$value = get_option($option);
	if (empty($value)) {
		global $aktt_bitly_settings;
		$value = $aktt_bitly_settings[$option]['default'];
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

function aktt_bitly_settings_form() {
	global $aktt_bitly_settings;

	print('
<div class="wrap">
	<h2>'.__('Bit.ly for Twitter Tools', 'twitter-tools-bitly').'</h2>
	<form id="aktt_bitly_settings_form" class="aktt" name="aktt_bitly_settings_form" action="'.admin_url('options-general.php').'" method="post">
		<input type="hidden" name="cf_action" value="aktt_bitly_update_settings" />
		<fieldset class="options">
	');
	foreach ($aktt_bitly_settings as $key => $config) {
		echo cf_settings_field($key, $config);
	}
	print('
		</fieldset>
		<p class="submit">
			<input type="submit" name="submit" class="button-primary" value="'.__('Save Settings', 'twitter-tools-bitly').'" />
		</p>
		'.wp_nonce_field('aktt_bitly_save_settings', '_wpnonce', true, false).wp_referer_field(false).'
	</form>
</div>
	');
}
add_action('aktt_options_form', 'aktt_bitly_settings_form');

function aktt_bitly_save_settings() {
	if (!current_user_can('manage_options')) {
		return;
	}
	global $aktt_bitly_settings;
	foreach ($aktt_bitly_settings as $key => $option) {
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
		$value = trim($value);
		update_option($key, $value);
	}
}


if (!function_exists('get_snoopy')) {
	function get_snoopy() {
		include_once(ABSPATH.'/wp-includes/class-snoopy.php');
		return new Snoopy;
	}
}

//a:21:{s:11:"plugin_name";s:29:"Bit.ly URLs for Twitter Tools";s:10:"plugin_uri";s:35:"http://crowdfavorite.com/wordpress/";s:18:"plugin_description";s:49:"Use Bit.ly for URL shortening with Twitter Tools.";s:14:"plugin_version";s:3:"1.0";s:6:"prefix";s:10:"aktt_bitly";s:12:"localization";s:19:"twitter-tools-bitly";s:14:"settings_title";s:24:"Bit.ly for Twitter Tools";s:13:"settings_link";s:24:"Bit.ly for Twitter Tools";s:4:"init";b:0;s:7:"install";b:0;s:9:"post_edit";b:0;s:12:"comment_edit";b:0;s:6:"jquery";b:0;s:6:"wp_css";b:0;s:5:"wp_js";b:0;s:9:"admin_css";b:0;s:8:"admin_js";b:0;s:15:"request_handler";s:1:"1";s:6:"snoopy";s:1:"1";s:11:"setting_cat";b:0;s:14:"setting_author";b:0;}

?>