<?php
/*
Plugin Name: Twitter Tools
Plugin URI: http://crowdfavorite.com/wordpress/plugins/twitter-tools/
Description: A complete integration between your WordPress blog and <a href="http://twitter.com">Twitter</a>. Bring your tweets into your blog and pass your blog posts to Twitter. Show your tweets in your sidebar, and post tweets from your WordPress admin.
Version: 2.4
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

// Copyright (c) 2007-2010 Crowd Favorite, Ltd., Alex King. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// This is an add-on for WordPress
// http://wordpress.org/
//
// Thanks to John Ford ( http://www.aldenta.com ) for his contributions.
// Thanks to Dougal Campbell ( http://dougal.gunters.org ) for his contributions.
// Thanks to Silas Sewell ( http://silas.sewell.ch ) for his contributions.
// Thanks to Greg Grubbs for his contributions.
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************

/* TODO

- update widget to new WP widget class
- what should retweet support look like?
- refactor digests to use WP-CRON
- truncate super-long post titles so that full tweet content is < 140 chars

*/

define('AKTT_VERSION', '2.4');

load_plugin_textdomain('twitter-tools', false, dirname(plugin_basename(__FILE__)) . '/language');

if (!defined('PLUGINDIR')) {
	define('PLUGINDIR','wp-content/plugins');
}

if (is_file(trailingslashit(ABSPATH.PLUGINDIR).'twitter-tools.php')) {
	define('AKTT_FILE', trailingslashit(ABSPATH.PLUGINDIR).'twitter-tools.php');
}
else if (is_file(trailingslashit(ABSPATH.PLUGINDIR).'twitter-tools/twitter-tools.php')) {
	define('AKTT_FILE', trailingslashit(ABSPATH.PLUGINDIR).'twitter-tools/twitter-tools.php');
}

define('AKTT_API_POST_STATUS', 'http://twitter.com/statuses/update.json');
define('AKTT_API_USER_TIMELINE', 'http://twitter.com/statuses/user_timeline.json');
define('AKTT_API_STATUS_SHOW', 'http://twitter.com/statuses/show/###ID###.json');
define('AKTT_PROFILE_URL', 'http://twitter.com/###USERNAME###');
define('AKTT_STATUS_URL', 'http://twitter.com/###USERNAME###/statuses/###STATUS###');
define('AKTT_HASHTAG_URL', 'http://search.twitter.com/search?q=###HASHTAG###');

function aktt_install() {
	global $wpdb;
	
	$aktt_install = new twitter_tools;
	$wpdb->aktt = $wpdb->prefix.'ak_twitter';
	$tables = $wpdb->get_col("
		SHOW TABLES LIKE '$wpdb->aktt'
	");
	if (!in_array($wpdb->aktt, $tables)) {
		$charset_collate = '';
		if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
			if (!empty($wpdb->charset)) {
				$charset_collate .= " DEFAULT CHARACTER SET $wpdb->charset";
			}
			if (!empty($wpdb->collate)) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}
		}
		$result = $wpdb->query("
			CREATE TABLE `$wpdb->aktt` (
			`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`tw_id` VARCHAR( 255 ) NOT NULL ,
			`tw_text` VARCHAR( 255 ) NOT NULL ,
			`tw_reply_username` VARCHAR( 255 ) DEFAULT NULL ,
			`tw_reply_tweet` VARCHAR( 255 ) DEFAULT NULL ,
			`tw_created_at` DATETIME NOT NULL ,
			`modified` DATETIME NOT NULL ,
			UNIQUE KEY `tw_id_unique` ( `tw_id` )
			) $charset_collate
		");
	}
	foreach ($aktt_install->options as $option) {
		add_option('aktt_'.$option, $aktt_install->$option);
	}
	add_option('aktt_update_hash', '');
}
register_activation_hook(AKTT_FILE, 'aktt_install');

class twitter_tools {
	function twitter_tools() {
		$this->options = array(
			'twitter_username'
			, 'create_blog_posts'
			, 'create_digest'
			, 'create_digest_weekly'
			, 'digest_daily_time'
			, 'digest_weekly_time'
			, 'digest_weekly_day'
			, 'digest_title'
			, 'digest_title_weekly'
			, 'blog_post_author'
			, 'blog_post_category'
			, 'blog_post_tags'
			, 'notify_twitter'
			, 'sidebar_tweet_count'
			, 'tweet_from_sidebar'
			, 'give_tt_credit'
			, 'exclude_reply_tweets'
			, 'tweet_prefix'
			, 'last_tweet_download'
			, 'doing_tweet_download'
			, 'doing_digest_post'
			, 'install_date'
			, 'js_lib'
			, 'digest_tweet_order'
			, 'notify_twitter_default'
			, 'app_consumer_key'
			, 'app_consumer_secret'
			, 'oauth_token'
			, 'oauth_token_secret'
		);
		$this->twitter_username = '';
		$this->create_blog_posts = '0';
		$this->create_digest = '0';
		$this->create_digest_weekly = '0';
		$this->digest_daily_time = null;
		$this->digest_weekly_time = null;
		$this->digest_weekly_day = null;
		$this->digest_title = __("Twitter Updates for %s", 'twitter-tools');
		$this->digest_title_weekly = __("Twitter Weekly Updates for %s", 'twitter-tools');
		$this->blog_post_author = '1';
		$this->blog_post_category = '1';
		$this->blog_post_tags = '';
		$this->notify_twitter = '0';
		$this->notify_twitter_default = '0';
		$this->sidebar_tweet_count = '3';
		$this->tweet_from_sidebar = '1';
		$this->give_tt_credit = '1';
		$this->exclude_reply_tweets = '0';
		$this->install_date = '';
		$this->js_lib = 'jquery';
		$this->digest_tweet_order = 'ASC';
		$this->tweet_prefix = 'New blog post';
		$this->app_consumer_key = '';
		$this->app_consumer_secret = '';
		$this->oauth_token = '';
		$this->oauth_token_secret ='';
		// not included in options
		$this->update_hash = '';
		$this->tweet_format = $this->tweet_prefix.': %s %s';
		$this->last_digest_post = '';
		$this->last_tweet_download = '';
		$this->doing_tweet_download = '0';
		$this->doing_digest_post = '0';
		$this->oauth_hash = '';
		$this->version = AKTT_VERSION;
	}
	
	function upgrade() {
		global $wpdb;
		$wpdb->aktt = $wpdb->prefix.'ak_twitter';

		$col_data = $wpdb->get_results("
			SHOW COLUMNS FROM $wpdb->aktt
		");
		$cols = array();
		foreach ($col_data as $col) {
			$cols[] = $col->Field;
		}
		// 1.2 schema upgrade
		if (!in_array('tw_reply_username', $cols)) {
			$wpdb->query("
				ALTER TABLE `$wpdb->aktt`
				ADD `tw_reply_username` VARCHAR( 255 ) DEFAULT NULL
				AFTER `tw_text`
			");
		}
		if (!in_array('tw_reply_tweet', $cols)) {
			$wpdb->query("
				ALTER TABLE `$wpdb->aktt`
				ADD `tw_reply_tweet` VARCHAR( 255 ) DEFAULT NULL
				AFTER `tw_reply_username`
			");
		}
		$this->upgrade_default_tweet_prefix();
		// upgrade indexes 2.1
		$index_data = $wpdb->get_results("
			SHOW INDEX FROM $wpdb->aktt
		");
		$indexes = array();
		foreach ($index_data as $index) {
			$indexes[] = $index->Key_name;
		}
		if (in_array('tw_id', $indexes)) {
			$wpdb->query("
				ALTER TABLE `$wpdb->aktt`
				DROP INDEX `tw_id`
			");
		}
		if (!in_array('tw_id_unique', $indexes)) {
			$wpdb->query("
				ALTER IGNORE TABLE `$wpdb->aktt`
				ADD UNIQUE KEY `tw_id_unique` ( `tw_id` )
			");
			$wpdb->query("
				OPTIMIZE TABLE `$wpdb->aktt`
			");
		}
	}
	
	function upgrade_default_tweet_prefix() {
		$prefix = get_option('aktt_tweet_prefix');
		if (empty($prefix)) {
			$aktt_defaults = new twitter_tools;
			update_option('aktt_tweet_prefix', $aktt_defaults->tweet_prefix);
		}
	}

	function get_settings() {
		foreach ($this->options as $option) {
			$value = get_option('aktt_'.$option);
			if ($option != 'tweet_prefix' || !empty($value)) {
				$this->$option = $value;
			}
		}
		$this->tweet_format = $this->tweet_prefix.': %s %s';
	}
	
	// puts post fields into object propps
	function populate_settings() {
		foreach ($this->options as $option) {
			$value = stripslashes($_POST['aktt_'.$option]);
			if (isset($_POST['aktt_'.$option]) && ($option != 'tweet_prefix' || !empty($value))) {
				$this->$option = $value;
			}
		}
	}
	
	// puts object props into wp option storage
	function update_settings() {
		if (current_user_can('manage_options')) {
			$this->sidebar_tweet_count = intval($this->sidebar_tweet_count);
			if ($this->sidebar_tweet_count == 0) {
				$this->sidebar_tweet_count = '3';
			}
			foreach ($this->options as $option) {
				update_option('aktt_'.$option, $this->$option);
			}
			if (empty($this->install_date)) {
				update_option('aktt_install_date', current_time('mysql'));
			}
			$this->initiate_digests();
			$this->upgrade();
			$this->upgrade_default_tweet_prefix();
			update_option('aktt_installed_version', AKTT_VERSION);
			delete_option('aktt_twitter_password');
		}
	}
	
	// figure out when the next weekly and daily digests will be
	function initiate_digests() {
		$next = ($this->create_digest) ? $this->calculate_next_daily_digest() : null;
		$this->next_daily_digest = $next;
		update_option('aktt_next_daily_digest', $next);
		
		$next = ($this->create_digest_weekly) ? $this->calculate_next_weekly_digest() : null;
		$this->next_weekly_digest = $next;
		update_option('aktt_next_weekly_digest', $next);
	}
	
	function calculate_next_daily_digest() {
		$optionDate = strtotime($this->digest_daily_time);
		$hour_offset = date("G", $optionDate);
		$minute_offset = date("i", $optionDate);
		$next = mktime($hour_offset, $minute_offset, 0);
		
		// may have to move to next day
		$now = time();
		while($next < $now) {
			$next += 60 * 60 * 24;
		}
		return $next;
	}
	
	function calculate_next_weekly_digest() {
		$optionDate = strtotime($this->digest_weekly_time);
		$hour_offset = date("G", $optionDate);
		$minute_offset = date("i", $optionDate);
		
		$current_day_of_month = date("j");
		$current_day_of_week = date("w");
		$current_month = date("n");
		
		// if this week's day is less than today, go for next week
		$nextDay = $current_day_of_month - $current_day_of_week + $this->digest_weekly_day;
		$next = mktime($hour_offset, $minute_offset, 0, $current_month, $nextDay);
		if ($this->digest_weekly_day <= $current_day_of_week) {
			$next = strtotime('+1 week', $next);
		}
		return $next;
	}
	
	function ping_digests() {
		// still busy
		if (get_option('aktt_doing_digest_post') == '1') {
			return;
		}
		// check all the digest schedules
		if ($this->create_digest == 1) {
			$this->ping_digest('aktt_next_daily_digest', 'aktt_last_digest_post', $this->digest_title, 60 * 60 * 24 * 1);
		}
		if ($this->create_digest_weekly == 1) {
			$this->ping_digest('aktt_next_weekly_digest', 'aktt_last_digest_post_weekly', $this->digest_title_weekly, 60 * 60 * 24 * 7);
		}
		return;
	}
	
	function ping_digest($nextDateField, $lastDateField, $title, $defaultDuration) {

		$next = get_option($nextDateField);
		
		if ($next) {		
			$next = $this->validateDate($next);
			$rightNow = time();
			if ($rightNow >= $next) {
				$start = get_option($lastDateField);
				$start = $this->validateDate($start, $rightNow - $defaultDuration);
				if ($this->do_digest_post($start, $next, $title)) {
					update_option($lastDateField, $rightNow);
					update_option($nextDateField, $next + $defaultDuration);
				} else {
					update_option($lastDateField, null);
				}
			}
		}
	}
	
	function validateDate($in, $default = 0) {
		if (!is_numeric($in)) {
			// try to convert what they gave us into a date
			$out = strtotime($in);
			// if that doesn't work, return the default
			if (!is_numeric($out)) {
				return $default;
			}
			return $out;	
		}
		return $in;
	}

	function do_digest_post($start, $end, $title) {
		
		if (!$start || !$end) return false;

		// flag us as busy
		update_option('aktt_doing_digest_post', '1');
		remove_action('publish_post', 'aktt_notify_twitter', 99);
		remove_action('publish_post', 'aktt_store_post_options', 1, 2);
		remove_action('save_post', 'aktt_store_post_options', 1, 2);
		// see if there's any tweets in the time range
		global $wpdb;
		
		$startGMT = gmdate("Y-m-d H:i:s", $start);
		$endGMT = gmdate("Y-m-d H:i:s", $end);
		
		// build sql
		$conditions = array();
		$conditions[] = "tw_created_at >= '{$startGMT}'";
		$conditions[] = "tw_created_at <= '{$endGMT}'";
		$conditions[] = "tw_text NOT LIKE '".$wpdb->escape($this->tweet_prefix)."%'";
		if ($this->exclude_reply_tweets) {
			$conditions[] = "tw_text NOT LIKE '@%'";
		}
		$where = implode(' AND ', $conditions);
		
		$sql = "
			SELECT * FROM {$wpdb->aktt}
			WHERE {$where}
			ORDER BY tw_created_at {$this->digest_tweet_order}
		";

		$tweets = $wpdb->get_results($sql);

		if (count($tweets) > 0) {
		
			$tweets_to_post = array();
			foreach ($tweets as $data) {
				$tweet = new aktt_tweet;
				$tweet->tw_text = $data->tw_text;
				$tweet->tw_reply_tweet = $data->tw_reply_tweet;
				if (!$tweet->tweet_is_post_notification() || ($tweet->tweet_is_reply() && $this->exclude_reply_tweets)) {
					$tweets_to_post[] = $data;
				}
			}
			
			$tweets_to_post = apply_filters('aktt_tweets_to_digest_post', $tweets_to_post); // here's your chance to alter the tweet list that will be posted as the digest

			if (count($tweets_to_post) > 0) {
				$content = '<ul class="aktt_tweet_digest">'."\n";
				foreach ($tweets_to_post as $tweet) {
					$content .= '	<li>'.aktt_tweet_display($tweet, 'absolute').'</li>'."\n";
				}
				$content .= '</ul>'."\n";
				if ($this->give_tt_credit == '1') {
					$content .= '<p class="aktt_credit">'.__('Powered by <a href="http://alexking.org/projects/wordpress">Twitter Tools</a>', 'twitter-tools').'</p>';
				}
				$post_data = array(
					'post_content' => $wpdb->escape($content),
					'post_title' => $wpdb->escape(sprintf($title, date('Y-m-d'))),
					'post_date' => date('Y-m-d H:i:s', $end),
					'post_category' => array($this->blog_post_category),
					'post_status' => 'publish',
					'post_author' => $wpdb->escape($this->blog_post_author)
				);
				$post_data = apply_filters('aktt_digest_post_data', $post_data); // last chance to alter the digest content

				$post_id = wp_insert_post($post_data);

				add_post_meta($post_id, 'aktt_tweeted', '1', true);
				wp_set_post_tags($post_id, $this->blog_post_tags);
			}

		}
		add_action('publish_post', 'aktt_notify_twitter', 99);
		add_action('publish_post', 'aktt_store_post_options', 1, 2);
		add_action('save_post', 'aktt_store_post_options', 1, 2);
		update_option('aktt_doing_digest_post', '0');
		return true;
	}
	
	function tweet_download_interval() {
		return 600;
	}
	
	function do_tweet($tweet = '') {		
		if (empty($tweet) || empty($tweet->tw_text)) {
			return;
		}
		$tweet = apply_filters('aktt_do_tweet', $tweet); // return false here to not tweet
		if (!$tweet) {
			return;
		}

		if (aktt_oauth_test() && ($connection = aktt_oauth_connection())) {
			$connection->post(
				AKTT_API_POST_STATUS
				, array(
					'status' => $tweet->tw_text
					, 'source' => 'twittertools'
				)
			);
			if (strcmp($connection->http_code, '200') == 0) {
				update_option('aktt_last_tweet_download', strtotime('-28 minutes'));
				return true;
			}
		}
		return false;
	}
	
	function do_blog_post_tweet($post_id = 0) {
// this is only called on the publish_post hook
		if ($this->notify_twitter == '0'
			|| $post_id == 0
			|| get_post_meta($post_id, 'aktt_tweeted', true) == '1'
			|| get_post_meta($post_id, 'aktt_notify_twitter', true) == 'no'
		) {
			return;
		}
		$post = get_post($post_id);
		// check for an edited post before TT was installed
		if ($post->post_date <= $this->install_date) {
			return;
		}
		// check for private posts
		if ($post->post_status == 'private') {
			return;
		}
		$tweet = new aktt_tweet;
		$url = apply_filters('tweet_blog_post_url', get_permalink($post_id));
		$tweet->tw_text = sprintf(__($this->tweet_format, 'twitter-tools'), @html_entity_decode($post->post_title, ENT_COMPAT, 'UTF-8'), $url);
		$tweet = apply_filters('aktt_do_blog_post_tweet', $tweet, $post); // return false here to not tweet
		if (!$tweet) {
			return;
		}
		$this->do_tweet($tweet);
		add_post_meta($post_id, 'aktt_tweeted', '1', true);
	}
	
	function do_tweet_post($tweet) {
		global $wpdb;
		remove_action('publish_post', 'aktt_notify_twitter', 99);
		$data = array(
			'post_content' => $wpdb->escape(aktt_make_clickable($tweet->tw_text))
			, 'post_title' => $wpdb->escape(trim_add_elipsis($tweet->tw_text, 30))
			, 'post_date' => get_date_from_gmt(date('Y-m-d H:i:s', $tweet->tw_created_at))
			, 'post_category' => array($this->blog_post_category)
			, 'post_status' => 'publish'
			, 'post_author' => $wpdb->escape($this->blog_post_author)
		);
		$data = apply_filters('aktt_do_tweet_post', $data, $tweet); // return false here to not make a blog post
		if (!$data) {
			return;
		}
		$post_id = wp_insert_post($data);
		add_post_meta($post_id, 'aktt_twitter_id', $tweet->tw_id, true);
		wp_set_post_tags($post_id, $this->blog_post_tags);
		add_action('publish_post', 'aktt_notify_twitter', 99);
	}
}

class aktt_tweet {
	function aktt_tweet(
		$tw_id = ''
		, $tw_text = ''
		, $tw_created_at = ''
		, $tw_reply_username = null
		, $tw_reply_tweet = null
	) {
		$this->id = '';
		$this->modified = '';
		$this->tw_created_at = $tw_created_at;
		$this->tw_text = $tw_text;
		$this->tw_reply_username = $tw_reply_username;
		$this->tw_reply_tweet = $tw_reply_tweet;
		$this->tw_id = $tw_id;
	}
	
	function twdate_to_time($date) {
		$parts = explode(' ', $date);
		$date = strtotime($parts[1].' '.$parts[2].', '.$parts[5].' '.$parts[3]);
		return $date;
	}
	
	function tweet_post_exists() {
		global $wpdb;
		$test = $wpdb->get_results("
			SELECT *
			FROM $wpdb->postmeta
			WHERE meta_key = 'aktt_twitter_id'
			AND meta_value = '".$wpdb->escape($this->tw_id)."'
		");
		if (count($test) > 0) {
			return true;
		}
		return false;
	}
	
	function tweet_is_post_notification() {
		global $aktt;
		if (substr($this->tw_text, 0, strlen($aktt->tweet_prefix)) == $aktt->tweet_prefix) {
			return true;
		}
		return false;
	}
	
	function tweet_is_reply() {
// Twitter data changed - users still expect anything starting with @ is a reply
//		return !empty($this->tw_reply_tweet);
		return (substr($this->tw_text, 0, 1) == '@');
	}
	
	function add() {
		global $wpdb, $aktt;
		$wpdb->query("
			INSERT
			INTO $wpdb->aktt
			( tw_id
			, tw_text
			, tw_reply_username
			, tw_reply_tweet
			, tw_created_at
			, modified
			)
			VALUES
			( '".$wpdb->escape($this->tw_id)."'
			, '".$wpdb->escape($this->tw_text)."'
			, '".$wpdb->escape($this->tw_reply_username)."'
			, '".$wpdb->escape($this->tw_reply_tweet)."'
			, '".date('Y-m-d H:i:s', $this->tw_created_at)."'
			, NOW()
			)
		");
		do_action('aktt_add_tweet', $this);
		if ($aktt->create_blog_posts == '1' && !$this->tweet_post_exists() && !$this->tweet_is_post_notification() && (!$aktt->exclude_reply_tweets || !$this->tweet_is_reply())) {
			$aktt->do_tweet_post($this);
		}
	}
}

function aktt_api_status_show_url($id) {
	return str_replace('###ID###', $id, AKTT_API_STATUS_SHOW);
}

function aktt_profile_url($username) {
	return str_replace('###USERNAME###', $username, AKTT_PROFILE_URL);
}

function aktt_profile_link($username, $prefix = '', $suffix = '') {
	return $prefix.'<a href="'.aktt_profile_url($username).'" class="aktt_username">'.$username.'</a>'.$suffix;
}

function aktt_hashtag_url($hashtag) {
	$hashtag = urlencode('#'.$hashtag);
	return str_replace('###HASHTAG###', $hashtag, AKTT_HASHTAG_URL);
}

function aktt_hashtag_link($hashtag, $prefix = '', $suffix = '') {
	return $prefix.'<a href="'.aktt_hashtag_url($hashtag).'" class="aktt_hashtag">'.htmlspecialchars($hashtag).'</a> '.$suffix;
}

function aktt_status_url($username, $status) {
	return str_replace(
		array(
			'###USERNAME###'
			, '###STATUS###'
		)
		, array(
			$username
			, $status
		)
		, AKTT_STATUS_URL
	);
}
function aktt_oauth_test() {
	global $aktt;
	return ( aktt_oauth_credentials_to_hash() == get_option('aktt_oauth_hash') );
}

function aktt_ping_digests() {
	global $aktt;
	$aktt->ping_digests();
}

function aktt_update_tweets() {
	global $aktt;
	
	// let the last update run for 10 minutes
	if (time() - intval(get_option('aktt_doing_tweet_download')) < $aktt->tweet_download_interval()) {
		return;
	}
	// wait 10 min between downloads
	if (time() - intval(get_option('aktt_last_tweet_download')) < $aktt->tweet_download_interval()) {
		return;
	}
	update_option('aktt_doing_tweet_download', time());
	global $wpdb, $aktt;
	if (empty($aktt->twitter_username)) {
		update_option('aktt_doing_tweet_download', '0');
		return;
	}

	if ( aktt_oauth_test() && ($connection = aktt_oauth_connection()) ) {
		$data = $connection->get(AKTT_API_USER_TIMELINE);
		if ($connection->http_code != '200') {
			update_option('aktt_doing_tweet_download', '0');
			return;
		}
	}
	else {
		return;
	}
	// hash results to see if they're any different than the last update, if so, return

	$hash = md5($data);
	if ($hash == get_option('aktt_update_hash')) {
		update_option('aktt_last_tweet_download', time());
		update_option('aktt_doing_tweet_download', '0');
		do_action('aktt_update_tweets');
		return;
	}
	$data = preg_replace('/"id":(\d+)/', '"id":"$1"', $data); // hack for json_decode on 32-bit PHP
	$tweets = json_decode($data);

	if (is_array($tweets) && count($tweets)) {
		$tweet_ids = array();
		foreach ($tweets as $tweet) {
			$tweet_ids[] = $wpdb->escape($tweet->id);
		}
		$existing_ids = $wpdb->get_col("
			SELECT tw_id
			FROM $wpdb->aktt
			WHERE tw_id
			IN ('".implode("', '", $tweet_ids)."')
		");
		foreach ($tweets as $tw_data) {
			if (!$existing_ids || !in_array($tw_data->id, $existing_ids)) {
				$tweet = new aktt_tweet(
					$tw_data->id
					, $tw_data->text
				);
				$tweet->tw_created_at = $tweet->twdate_to_time($tw_data->created_at);
				if (!empty($tw_data->in_reply_to_status_id)) {
					$tweet->tw_reply_tweet = $tw_data->in_reply_to_status_id;
					$url = aktt_api_status_show_url($tw_data->in_reply_to_status_id);
					$data = $connection->get($url);
					if (strcmp($connection->http_code, '200') == 0) {
						$status = json_decode($data);
						$tweet->tw_reply_username = $status->user->screen_name;
					}
				}
				// make sure we haven't downloaded someone else's tweets - happens sometimes due to Twitter hiccups
				if (strtolower($tw_data->user->screen_name) == strtolower($aktt->twitter_username)) {
					$tweet->add();
				}
			}
		}
	}
	aktt_reset_tweet_checking($hash, time());
	do_action('aktt_update_tweets');
}

function aktt_reset_tweet_checking($hash = '', $time = 0) {
	if (!current_user_can('manage_options')) {
		return;
	}
	update_option('aktt_update_hash', $hash);
	update_option('aktt_last_tweet_download', $time);
	update_option('aktt_doing_tweet_download', '0');
}

function aktt_reset_digests() {
	if (!current_user_can('manage_options')) {
		return;
	}
	update_option('aktt_doing_digest_post', '0');
}

function aktt_notify_twitter($post_id) {
	global $aktt;
	$aktt->do_blog_post_tweet($post_id);
}
add_action('publish_post', 'aktt_notify_twitter', 99);

function aktt_sidebar_tweets($limit = null, $form = null) {
	global $wpdb, $aktt;
	if (!$limit) {
		$limit = $aktt->sidebar_tweet_count;
	}
	if ($aktt->exclude_reply_tweets) {
		$where = "AND tw_text NOT LIKE '@%' ";
	}
	else {
		$where = '';
	}
	$tweets = $wpdb->get_results("
		SELECT *
		FROM $wpdb->aktt
		WHERE tw_text NOT LIKE '".$wpdb->escape($aktt->tweet_prefix.'%')."'
		$where
		ORDER BY tw_created_at DESC
		LIMIT $limit
	");
	$output = '<div class="aktt_tweets">'."\n"
		.'	<ul>'."\n";
	if (count($tweets) > 0) {
		foreach ($tweets as $tweet) {
			$output .= '		<li>'.aktt_tweet_display($tweet).'</li>'."\n";
		}
	}
	else {
		$output .= '		<li>'.__('No tweets available at the moment.', 'twitter-tools').'</li>'."\n";
	}
	if (!empty($aktt->twitter_username)) {
  		$output .= '		<li class="aktt_more_updates"><a href="'.aktt_profile_url($aktt->twitter_username).'">'.__('More updates...', 'twitter-tools').'</a></li>'."\n";
	}
	$output .= '</ul>';
	if ($form !== false && $aktt->tweet_from_sidebar == '1' && aktt_oauth_test()) {
  		$output .= aktt_tweet_form('input', 'onsubmit="akttPostTweet(); return false;"');
		  $output .= '	<p id="aktt_tweet_posted_msg">'.__('Posting tweet...', 'twitter-tools').'</p>';
	}
	if ($aktt->give_tt_credit == '1') {
		$output .= '<p class="aktt_credit">'.__('Powered by <a href="http://alexking.org/projects/wordpress">Twitter Tools</a>', 'twitter-tools').'</p>';
	}
	$output .= '</div>';
	print($output);
}

function aktt_shortcode_tweets($args) {
	extract(shortcode_atts(array(
		'count' => null
	), $args));
	ob_start();
	aktt_sidebar_tweets($count, false);
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode('aktt_tweets', 'aktt_shortcode_tweets');

function aktt_latest_tweet() {
	global $wpdb, $aktt;
	if ($aktt->exclude_reply_tweets) {
		$where = "AND tw_text NOT LIKE '@%' ";
	}
	else {
		$where = '';
	}
	$tweets = $wpdb->get_results("
		SELECT *
		FROM $wpdb->aktt
		WHERE tw_text NOT LIKE '".$wpdb->escape($aktt->tweet_prefix)."%'
		$where
		ORDER BY tw_created_at DESC
		LIMIT 1
	");
	if (count($tweets) == 1) {
		foreach ($tweets as $tweet) {
			$output = aktt_tweet_display($tweet);
		}
	}
	else {
		$output = __('No tweets available at the moment.', 'twitter-tools');
	}
	print($output);
}

function aktt_tweet_display($tweet, $time = 'relative') {
	global $aktt;
	$output = aktt_make_clickable(wp_specialchars($tweet->tw_text));
	if (!empty($tweet->tw_reply_username)) {
		$output .= 	' <a href="'.aktt_status_url($tweet->tw_reply_username, $tweet->tw_reply_tweet).'" class="aktt_tweet_reply">'.sprintf(__('in reply to %s', 'twitter-tools'), $tweet->tw_reply_username).'</a>';
	}
	switch ($time) {
		case 'relative':
			$time_display = aktt_relativeTime($tweet->tw_created_at, 3);
			break;
		case 'absolute':
			$time_display = '#';
			break;
	}
	$output .= ' <a href="'.aktt_status_url($aktt->twitter_username, $tweet->tw_id).'" class="aktt_tweet_time">'.$time_display.'</a>';
	$output = apply_filters('aktt_tweet_display', $output, $tweet); // allows you to alter the tweet display output
	return $output;
}

function aktt_make_clickable($tweet) {
	$tweet .= ' ';
	$tweet = preg_replace_callback(
			'/(^|\s)@([a-zA-Z0-9_]{1,})(\W)/'
			, create_function(
				'$matches'
				, 'return aktt_profile_link($matches[2], \' @\', $matches[3]);'
			)
			, $tweet
	);
	$tweet = preg_replace_callback(
		'/(^|\s)#([a-zA-Z0-9_]{1,})(\W)/'
		, create_function(
			'$matches'
			, 'return aktt_hashtag_link($matches[2], \' #\', \'\');'
		)
		, $tweet
	);
	
	if (function_exists('make_chunky')) {
		return make_chunky($tweet);
	}
	else {
		return make_clickable($tweet);
	}
}

function aktt_tweet_form($type = 'input', $extra = '') {
	$output = '';
	if (current_user_can('publish_posts') && aktt_oauth_test()) {
		$output .= '
<form action="'.site_url('index.php').'" method="post" id="aktt_tweet_form" '.$extra.'>
	<fieldset>
		';
		switch ($type) {
			case 'input':
				$output .= '
		<p><input type="text" size="20" maxlength="140" id="aktt_tweet_text" name="aktt_tweet_text" onkeyup="akttCharCount();" /></p>
		<input type="hidden" name="ak_action" value="aktt_post_tweet_sidebar" />
		<script type="text/javascript">
		//<![CDATA[
		function akttCharCount() {
			var count = document.getElementById("aktt_tweet_text").value.length;
			if (count > 0) {
				document.getElementById("aktt_char_count").innerHTML = 140 - count;
			}
			else {
				document.getElementById("aktt_char_count").innerHTML = "";
			}
		}
		setTimeout("akttCharCount();", 500);
		document.getElementById("aktt_tweet_form").setAttribute("autocomplete", "off");
		//]]>
		</script>
				';
				break;
			case 'textarea':
				$output .= '
		<p><textarea type="text" cols="60" rows="5" maxlength="140" id="aktt_tweet_text" name="aktt_tweet_text" onkeyup="akttCharCount();"></textarea></p>
		<input type="hidden" name="ak_action" value="aktt_post_tweet_admin" />
		<script type="text/javascript">
		//<![CDATA[
		function akttCharCount() {
			var count = document.getElementById("aktt_tweet_text").value.length;
			if (count > 0) {
				document.getElementById("aktt_char_count").innerHTML = (140 - count) + "'.__(' characters remaining', 'twitter-tools').'";
			}
			else {
				document.getElementById("aktt_char_count").innerHTML = "";
			}
		}
		setTimeout("akttCharCount();", 500);
		document.getElementById("aktt_tweet_form").setAttribute("autocomplete", "off");
		//]]>
		</script>
				';
				break;
		}
		$output .= '
		<p>
			<input type="submit" id="aktt_tweet_submit" name="aktt_tweet_submit" value="'.__('Post Tweet!', 'twitter-tools').'" class="button-primary" />
			<span id="aktt_char_count"></span>
		</p>
		<div class="clear"></div>
	</fieldset>
	'.wp_nonce_field('aktt_new_tweet', '_wpnonce', true, false).wp_referer_field(false).'
</form>
		';
	}
	return $output;
}

function aktt_widget_init() {
	if (!function_exists('register_sidebar_widget')) {
		return;
	}
	function aktt_widget($args) {
		extract($args);
		$options = get_option('aktt_widget');
		$title = $options['title'];
		if (empty($title)) {
		}
		echo $before_widget . $before_title . $title . $after_title;
		aktt_sidebar_tweets();
		echo $after_widget;
	}
	register_sidebar_widget(array(__('Twitter Tools', 'twitter-tools'), 'widgets'), 'aktt_widget');
	
	function aktt_widget_control() {
		$options = get_option('aktt_widget');
		if (!is_array($options)) {
			$options = array(
				'title' => __("What I'm Doing...", 'twitter-tools')
			);
		}
		if (isset($_POST['ak_action']) && $_POST['ak_action'] == 'aktt_update_widget_options') {
			$options['title'] = strip_tags(stripslashes($_POST['aktt_widget_title']));
			update_option('aktt_widget', $options);
			// reset checking so that sidebar isn't blank if this is the first time activating
			aktt_reset_tweet_checking();
			aktt_update_tweets();
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		print('
			<p style="text-align:right;"><label for="aktt_widget_title">' . __('Title:') . ' <input style="width: 200px;" id="aktt_widget_title" name="aktt_widget_title" type="text" value="'.$title.'" /></label></p>
			<p>'.__('Find additional Twitter Tools options on the <a href="options-general.php?page=twitter-tools.php">Twitter Tools Options page</a>.', 'twitter-tools').'
			<input type="hidden" id="ak_action" name="ak_action" value="aktt_update_widget_options" />
		');
	}
	register_widget_control(array(__('Twitter Tools', 'twitter-tools'), 'widgets'), 'aktt_widget_control', 300, 100);

}
add_action('widgets_init', 'aktt_widget_init');

function aktt_init() {
	global $wpdb, $aktt;
	$wpdb->aktt = $wpdb->prefix.'ak_twitter';
	$aktt = new twitter_tools;
	$aktt->get_settings();
	if (($aktt->last_tweet_download + $aktt->tweet_download_interval()) < time()) {
		add_action('shutdown', 'aktt_update_tweets');
		add_action('shutdown', 'aktt_ping_digests');
	}
	if (!is_admin() && $aktt->tweet_from_sidebar && current_user_can('publish_posts')) {
		switch ($aktt->js_lib) {
			case 'jquery':
				wp_enqueue_script('jquery');
				break;
			case 'prototype':
				wp_enqueue_script('prototype');
				break;
		}
	}
	if (is_admin()) {
		global $wp_version;
		$update = false;
		if (isset($wp_version) && version_compare($wp_version, '2.5', '>=') && empty ($aktt->install_date)) {
			$update = true;
		}
		if (!get_option('aktt_tweet_prefix')) {
			update_option('aktt_tweet_prefix', $aktt->tweet_prefix);
			$update = true;
		}
		$installed_version = get_option('aktt_installed_version');
		if ($installed_version != AKTT_VERSION) {
			$update = true;
		}
		if (!empty($installed_version) && version_compare($installed_version, '2.4', '<') && !aktt_oauth_test()) {
			add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>".sprintf(__('Twitter recently changed how it authenticates its users, you will need you to update your <a href="%s">Twitter Tools settings</a>. We apologize for any inconvenience these new authentication steps may cause.', 'twitter-tools'), admin_url('options-general.php?page=twitter-tools.php'))."</p></div>';" ) );
		}
		else if ($update) {
			add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>".sprintf(__('Please update your <a href="%s">Twitter Tools settings</a>', 'twitter-tools'), admin_url('options-general.php?page=twitter-tools.php'))."</p></div>';" ) );
		}
	}
}
add_action('init', 'aktt_init');

function aktt_head() {
	global $aktt;
	if ($aktt->tweet_from_sidebar) {
		print('
			<link rel="stylesheet" type="text/css" href="'.site_url('/index.php?ak_action=aktt_css&v='.AKTT_VERSION).'" />
			<script type="text/javascript" src="'.site_url('/index.php?ak_action=aktt_js&v='.AKTT_VERSION).'"></script>
		');
	}
}
add_action('wp_head', 'aktt_head');

function aktt_head_admin() {
	print('
		<link rel="stylesheet" type="text/css" href="'.admin_url('index.php?ak_action=aktt_css_admin&v='.AKTT_VERSION).'" />
		<script type="text/javascript" src="'.admin_url('index.php?ak_action=aktt_js_admin&v='.AKTT_VERSION).'"></script>
	');
}
if (isset($_GET['page']) && $_GET['page'] == 'twitter-tools.php') {
	add_action('admin_head', 'aktt_head_admin');
}

function aktt_resources() {
	if (!empty($_GET['ak_action'])) {
		switch($_GET['ak_action']) {
			case 'aktt_js':
				header("Content-type: text/javascript");
				switch ($aktt->js_lib) {
					case 'jquery':
?>
function akttPostTweet() {
	var tweet_field = jQuery('#aktt_tweet_text');
	var tweet_form = tweet_field.parents('form');
	var tweet_text = tweet_field.val();
	if (tweet_text == '') {
		return;
	}
	var tweet_msg = jQuery("#aktt_tweet_posted_msg");
	var nonce = jQuery(tweet_form).find('input[name=_wpnonce]').val();
	var refer = jQuery(tweet_form).find('input[name=_wp_http_referer]').val();
	jQuery.post(
		"<?php echo site_url('index.php'); ?>",
		{
			ak_action: "aktt_post_tweet_sidebar", 
			aktt_tweet_text: tweet_text,
			_wpnonce: nonce,
			_wp_http_referer: refer
		},
		function(data) {
			tweet_msg.html(data);
			akttSetReset();
		}
	);
	tweet_field.val('').focus();
	jQuery('#aktt_char_count').html('');
	jQuery("#aktt_tweet_posted_msg").show();
}
function akttSetReset() {
	setTimeout('akttReset();', 2000);
}
function akttReset() {
	jQuery('#aktt_tweet_posted_msg').hide();
}
<?php
						break;
					case 'prototype':
?>
function akttPostTweet() {
	var tweet_field = $('aktt_tweet_text');
	var tweet_text = tweet_field.value;
	if (tweet_text == '') {
		return;
	}
	var tweet_msg = $("aktt_tweet_posted_msg");
	var nonce = $('_wpnonce').value;
	var refer = $('_wpnonce').next('input').value;
	var akttAjax = new Ajax.Updater(
		tweet_msg,
		"<?php echo site_url('index.php'); ?>",
		{
			method: "post",
			parameters: "ak_action=aktt_post_tweet_sidebar&aktt_tweet_text=" + tweet_text + '&_wpnonce=' + nonce + '&_wp_http_referer=' + refer,
			onComplete: akttSetReset
		}
	);
	tweet_field.value = '';
	tweet_field.focus();
	$('aktt_char_count').innerHTML = '';
	tweet_msg.style.display = 'block';
}
function akttSetReset() {
	setTimeout('akttReset();', 2000);
}
function akttReset() {
	$('aktt_tweet_posted_msg').style.display = 'none';
}
<?php
						break;
				}
				die();
				break;
			case 'aktt_css':
				header("Content-Type: text/css");
?>
#aktt_tweet_form {
	margin: 0;
	padding: 5px 0;
}
#aktt_tweet_form fieldset {
	border: 0;
}
#aktt_tweet_form fieldset #aktt_tweet_submit {
	float: right;
	margin-right: 10px;
}
#aktt_tweet_form fieldset #aktt_char_count {
	color: #666;
}
#aktt_tweet_posted_msg {
	background: #ffc;
	display: none;
	margin: 0 0 5px 0;
	padding: 5px;
}
#aktt_tweet_form div.clear {
	clear: both;
	float: none;
}
<?php
				die();
				break;
			case 'aktt_js_admin':
				header("Content-Type: text/javascript");
?>

jQuery(function($) {
	$("#aktt_authentication_showhide").click(function(){
		$("#aktt_authentication_display").slideToggle();
	});
});

(function($){

	jQuery.fn.timepicker = function(){
	
		var hrs = new Array();
		for(var h = 1; h <= 12; hrs.push(h++));

		var mins = new Array();
		for(var m = 0; m < 60; mins.push(m++));

		var ap = new Array('am', 'pm');

		function pad(n) {
			n = n.toString();
			return n.length == 1 ? '0' + n : n;
		}
	
		this.each(function() {

			var v = $(this).val();
			if (!v) v = new Date();

			var d = new Date(v);
			var h = d.getHours();
			var m = d.getMinutes();
			var p = (h >= 12) ? "pm" : "am";
			h = (h > 12) ? h - 12 : h;

			var output = '';

			output += '<select id="h_' + this.id + '" class="timepicker">';				
			for (var hr in hrs){
				output += '<option value="' + pad(hrs[hr]) + '"';
				if(parseInt(hrs[hr], 10) == h || (parseInt(hrs[hr], 10) == 12 && h == 0)) output += ' selected';
				output += '>' + pad(hrs[hr]) + '</option>';
			}
			output += '</select>';
	
			output += '<select id="m_' + this.id + '" class="timepicker">';				
			for (var mn in mins){
				output += '<option value="' + pad(mins[mn]) + '"';
				if(parseInt(mins[mn], 10) == m) output += ' selected';
				output += '>' + pad(mins[mn]) + '</option>';
			}
			output += '</select>';				
	
			output += '<select id="p_' + this.id + '" class="timepicker">';				
			for(var pp in ap){
				output += '<option value="' + ap[pp] + '"';
				if(ap[pp] == p) output += ' selected';
				output += '>' + ap[pp] + '</option>';
			}
			output += '</select>';
			
			$(this).after(output);
			
			var field = this;
			$(this).siblings('select.timepicker').change(function() {
				var h = parseInt($('#h_' + field.id).val(), 10);
				var m = parseInt($('#m_' + field.id).val(), 10);
				var p = $('#p_' + field.id).val();
	
				if (p == "am") {
					if (h == 12) {
						h = 0;
					}
				} else if (p == "pm") {
					if (h < 12) {
						h += 12;
					}
				}
				
				var d = new Date();
				d.setHours(h);
				d.setMinutes(m);
				
				$(field).val(d.toUTCString());
			}).change();

		});

		return this;
	};
	
	jQuery.fn.daypicker = function() {
		
		var days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		
		this.each(function() {
			
			var v = $(this).val();
			if (!v) v = 0;
			v = parseInt(v, 10);
			
			var output = "";
			output += '<select id="d_' + this.id + '" class="daypicker">';				
			for (var i = 0; i < days.length; i++) {
				output += '<option value="' + i + '"';
				if (v == i) output += ' selected';
				output += '>' + days[i] + '</option>';
			}
			output += '</select>';
			
			$(this).after(output);
			
			var field = this;
			$(this).siblings('select.daypicker').change(function() {
				$(field).val( $(this).val() );
			}).change();
		
		});
		
	};
	
	jQuery.fn.forceToggleClass = function(classNames, bOn) {
		return this.each(function() {
			jQuery(this)[ bOn ? "addClass" : "removeClass" ](classNames);
		});
	};
	
})(jQuery);

jQuery(function() {

	// add in the time and day selects
	jQuery('form#ak_twittertools input.time').timepicker();
	jQuery('form#ak_twittertools input.day').daypicker();
	
	// togglers
	jQuery('.time_toggle .toggler').change(function() {
		var theSelect = jQuery(this);
		theSelect.parent('.time_toggle').forceToggleClass('active', theSelect.val() === "1");
	}).change();
	
});
<?php
				die();
				break;
			case 'aktt_css_admin':
				header("Content-Type: text/css");
?>
#aktt_tweet_form {
	margin: 0;
	padding: 5px 0;
}
#aktt_tweet_form fieldset {
	border: 0;
}
#aktt_tweet_form fieldset textarea {
	width: 95%;
}
#aktt_tweet_form fieldset #aktt_tweet_submit {
	float: right;
	margin-right: 50px;
}
#aktt_tweet_form fieldset #aktt_char_count {
	color: #666;
}
#ak_readme {
	height: 300px;
	width: 95%;
}

form.aktt .options,
#ak_twittertools .options,
#ak_twittertools_disconnect .options {
	overflow: hidden;
	border: none;
}
form.aktt .option,
#ak_twittertools .option,
#ak_twittertools_disconnect .option {
	overflow: hidden;
	padding-bottom: 9px;
	padding-top: 9px;
}
form.aktt .option label,
#ak_twittertools .option label,
#ak_twittertools_disconnect .option label {
	display: block;
	float: left;
	width: 200px;
	margin-right: 24px;
	text-align: right;
}
form.aktt .option span,
#ak_twittertools .option span {
	color: #666;
	display: block;
	float: left;
	margin-left: 230px;
	margin-top: 6px;
	clear: left;
}
form.aktt select,
form.aktt input,
#ak_twittertools select,
#ak_twittertools input,
#ak_twittertools_disconnect input {
	float: left;
	display: block;
	margin-right: 6px;
}
form.aktt p.submit,
#ak_twittertools p.submit,
#ak_twittertools_disconnect p.submit {
	overflow: hidden;
}

#ak_twittertools fieldset.options .option span.aktt_login_result_wait {
	background: #ffc;
}
#ak_twittertools fieldset.options .option span.aktt_login_result {
	background: #CFEBF7;
	color: #000;
}
#ak_twittertools .timepicker,
#ak_twittertools .daypicker {
	display: none;
}
#ak_twittertools .active .timepicker,
#ak_twittertools .active .daypicker {
	display: block
}
#ak_twittertools_disconnect .auth_information_link{
	margin-left: 6px;
}
.aktt_experimental {
	background: #eee;
	border: 2px solid #ccc;
}
.aktt_experimental h4 {
	color: #666;
	margin: 0;
	padding: 10px;
	text-align: center;
}
#aktt_sub_instructions ul {
	list-style-type: circle;
	padding-top:0px;
}
#aktt_sub_instructions ul li{
	margin-left: 20px;
}
#aktt_authentication_display {
	display: none;
}
#ak_twittertools_disconnect .auth_label {
	display: block;
	float: left;
	width: 200px;
	margin-right: 24px;
	text-align: right;
}
#ak_twittertools_disconnect .auth_code {
	
}

.help {
	color: #777;
	font-size: 11px;
}
.txt-center {
	text-align: center;
}
#cf {
	width: 90%;
}

/* Developed by and Support by callouts */
#cf-callouts {
	background: url(http://cloud.wphelpcenter.com/resources/wp-admin-0001/border-fade-sprite.gif) 0 0 repeat-x;
	float: left;
	margin: 18px 0;
}
.cf-callout {
	float: left;
	margin-top: 18px;
	max-width: 500px;
	width: 50%;
}
#cf-callout-credit {
	margin-right: 9px;
}
#cf-callout-credit .cf-box-title {
	background: #193542 url(http://cloud.wphelpcenter.com/resources/wp-admin-0001/box-sprite.png) 0 0 repeat-x;
	border-bottom: 1px solid #0C1A21;
}
#cf-callout-support {
	margin-left: 9px;
}
#cf-callout-support .cf-box-title {
	background: #8D2929 url(http://cloud.wphelpcenter.com/resources/wp-admin-0001/box-sprite.png) 0 -200px repeat-x;
	border-bottom: 1px solid #461414;
}

/* General cf-box styles */
.cf-box { 
	background: #EFEFEF url(http://cloud.wphelpcenter.com/resources/wp-admin-0001/box-sprite.png) 0 -400px repeat-x;
	border: 1px solid #E3E3E3;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-khtml-border-radius: 5px;
}
.cf-box .cf-box-title {
	color: #fff;
	font-size: 14px;
	font-weight: normal;
	padding: 6px 15px;
	margin: 0 0 12px 0;
	-moz-border-radius-topleft: 5px;
	-webkit-border-top-left-radius: 5px;
	-khtml-border-top-left-radius: 5px;
	border-top-left-radius: 5px;
	-moz-border-radius-topright: 5px;
	-webkit-border-top-right-radius: 5px;
	-khtml-border-top-right-radius: 5px;
	border-top-right-radius: 5px;
	text-align: center;
	text-shadow: #333 0 1px 1px;
}
.cf-box .cf-box-title a {
	display: block;
	color: #fff;
}
.cf-box .cf-box-title a:hover {
	color: #E1E1E1;
}
.cf-box .cf-box-content {
	margin: 0 15px 15px 15px;
}
.cf-box .cf-box-content p {
	font-size: 11px;
}


<?php
				die();
				break;
		}
	}
}
add_action('init', 'aktt_resources', 1);

function aktt_oauth_connection() {
	global $aktt;
	if ( !empty($aktt->app_consumer_key) && !empty($aktt->app_consumer_secret) && !empty($aktt->oauth_token) && !empty($aktt->oauth_token_secret) ) {	
		require_once('twitteroauth.php');
		$connection = new TwitterOAuth(
			$aktt->app_consumer_key, 
			$aktt->app_consumer_secret, 
			$aktt->oauth_token, 
			$aktt->oauth_token_secret
		);
		$connection->useragent = 'Twitter Tools http://alexking.org/projects/wordpress';
		return $connection;
	}
	else {
		return false;
	}
}

function aktt_oauth_credentials_to_hash() {
	global $aktt;
	$hash = md5($aktt->app_consumer_key.$aktt->app_consumer_secret.$aktt->oauth_token.$aktt->oauth_token_secret);
	return $hash;		
}

function aktt_request_handler() {
	global $wpdb, $aktt;
	if (!empty($_GET['ak_action'])) {
		switch($_GET['ak_action']) {
			case 'aktt_update_tweets':
				if (!wp_verify_nonce($_GET['_wpnonce'], 'aktt_update_tweets')) {
					wp_die('Oops, please try again.');
				}
				aktt_update_tweets();
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&tweets-updated=true'));
				die();
				break;
			case 'aktt_reset_tweet_checking':
				if (!wp_verify_nonce($_GET['_wpnonce'], 'aktt_update_tweets')) {
					wp_die('Oops, please try again.');
				}
				aktt_reset_tweet_checking();
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&tweet-checking-reset=true'));
				die();
				break;
			case 'aktt_reset_digests':
				if (!wp_verify_nonce($_GET['_wpnonce'], 'aktt_update_tweets')) {
					wp_die('Oops, please try again.');
				}
				aktt_reset_digests();
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&digest-reset=true'));
				die();
				break;
		}
	}
	if (!empty($_POST['ak_action'])) {
		switch($_POST['ak_action']) {
			case 'aktt_update_settings':
				if (!wp_verify_nonce($_POST['_wpnonce'], 'aktt_settings')) {
					wp_die('Oops, please try again.');
				}
				$aktt->populate_settings();
				$aktt->update_settings();
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&updated=true'));
				die();
				break;
			case 'aktt_post_tweet_sidebar':
				if (!empty($_POST['aktt_tweet_text']) && current_user_can('publish_posts')) {
					if (!wp_verify_nonce($_POST['_wpnonce'], 'aktt_new_tweet')) {
						wp_die('Oops, please try again.');
					}
					$tweet = new aktt_tweet();
					$tweet->tw_text = stripslashes($_POST['aktt_tweet_text']);
					if ($aktt->do_tweet($tweet)) {
						die(__('Tweet posted.', 'twitter-tools'));
					}
					else {
						die(__('Tweet post failed.', 'twitter-tools'));
					}
				}
				break;
			case 'aktt_post_tweet_admin':
				if (!empty($_POST['aktt_tweet_text']) && current_user_can('publish_posts')) {
					if (!wp_verify_nonce($_POST['_wpnonce'], 'aktt_new_tweet')) {
						wp_die('Oops, please try again.');
					}
					$tweet = new aktt_tweet();
					$tweet->tw_text = stripslashes($_POST['aktt_tweet_text']);
					if ($aktt->do_tweet($tweet)) {
						wp_redirect(admin_url('post-new.php?page=twitter-tools.php&tweet-posted=true'));
					}
					else {
						wp_die(__('Oops, your tweet was not posted. Please check your blog is connected to Twitter and Twitter is up and running happily.', 'twitter-tools'));
					}
					die();
				}
				break;
			case 'aktt_oauth_test':
				if (!wp_verify_nonce($_POST['_wpnonce'], 'aktt_oauth_test')) {
					wp_die('Oops, please try again.');
				}
				$auth_test = false;
				if ( !empty($_POST['aktt_app_consumer_key'])
					&& !empty($_POST['aktt_app_consumer_secret'])
					&& !empty($_POST['aktt_oauth_token'])
					&& !empty($_POST['aktt_oauth_token_secret'])
				) {
					$aktt->populate_settings();
					$aktt->update_settings();
					$message = 'fail';
					if ($connection = aktt_oauth_connection()) {
						$data = $connection->get('account/verify_credentials');
						if ($connection->http_code == '200') {
							$data = json_decode($data);
							update_option('aktt_twitter_username', stripslashes($data->screen_name));
							$oauth_hash = aktt_oauth_credentials_to_hash();
							update_option('aktt_oauth_hash', $oauth_hash);
							$message = 'success';
						}
					}
				}
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&oauth='.$message));
			break;
			case 'aktt_twitter_disconnect':
				if (!wp_verify_nonce($_POST['_wpnonce'], 'aktt_twitter_disconnect')) {
					wp_die('Oops, please try again.');
				}
				
				update_option('aktt_app_consumer_key', '');
				update_option('aktt_app_consumer_secret', '');
				update_option('aktt_oauth_token', '');
				update_option('aktt_oauth_token_secret', '');
				wp_redirect(admin_url('options-general.php?page=twitter-tools.php&updated=true'));
			break;
		}
	}
}
add_action('init', 'aktt_request_handler', 10);

function aktt_admin_tweet_form() {
	global $aktt;
	if ( $_GET['tweet-posted'] ) {
		print('
			<div id="message" class="updated fade">
				<p>'.__('Tweet posted.', 'twitter-tools').'</p>
			</div>
		');
	}
	if ( aktt_oauth_test() ) {
		print('
			<div class="wrap" id="aktt_write_tweet">
			<h2>'.__('Write Tweet', 'twitter-tools').'</h2>
			<p>'.__('This will create a new \'tweet\' in <a href="http://twitter.com">Twitter</a> using the account information in your <a href="options-general.php?page=twitter-tools.php">Twitter Tools Options</a>.', 'twitter-tools').'</p>
			'.aktt_tweet_form('textarea').'
			</div>
		');
	} 
}

function aktt_options_form() {
	global $wpdb, $aktt, $wp_version;

	$categories = get_categories('hide_empty=0');
	$cat_options = '';
	foreach ($categories as $category) {
// WP < 2.3 compatibility
		!empty($category->term_id) ? $cat_id = $category->term_id : $cat_id = $category->cat_ID;
		!empty($category->name) ? $cat_name = $category->name : $cat_name = $category->cat_name;
		if ($cat_id == $aktt->blog_post_category) {
			$selected = 'selected="selected"';
		}
		else {
			$selected = '';
		}
		$cat_options .= "\n\t<option value='$cat_id' $selected>$cat_name</option>";
	}

	global $current_user;
	$authors = get_editable_user_ids($current_user->ID);
	$author_options = '';
	if (count($authors)) {
		foreach ($authors as $user_id) {
			$usero = new WP_User($user_id);
			$author = $usero->data;
			// Only list users who are allowed to publish
			if (! $usero->has_cap('publish_posts')) {
				continue;
			}
			if ($author->ID == $aktt->blog_post_author) {
				$selected = 'selected="selected"';
			}
			else {
				$selected = '';
			}
			$author_options .= "\n\t<option value='$author->ID' $selected>$author->user_nicename</option>";
		}
	}
	
	$js_libs = array(
		'jquery' => 'jQuery'
		, 'prototype' => 'Prototype'
	);
	$js_lib_options = '';
	foreach ($js_libs as $js_lib => $js_lib_display) {
		if ($js_lib == $aktt->js_lib) {
			$selected = 'selected="selected"';
		}
		else {
			$selected = '';
		}
		$js_lib_options .= "\n\t<option value='$js_lib' $selected>$js_lib_display</option>";
	}
	$digest_tweet_orders = array(
		'ASC' => __('Oldest first (Chronological order)', 'twitter-tools'),
		'DESC' => __('Newest first (Reverse-chronological order)', 'twitter-tools')
	);
	$digest_tweet_order_options = '';
	foreach ($digest_tweet_orders as $digest_tweet_order => $digest_tweet_order_display) {
		if ($digest_tweet_order == $aktt->digest_tweet_order) {
			$selected = 'selected="selected"';
		}
		else {
			$selected = '';
		}
		$digest_tweet_order_options .= "\n\t<option value='$digest_tweet_order' $selected>$digest_tweet_order_display</option>";
	}	
	$yes_no = array(
		'create_blog_posts'
		, 'create_digest'
		, 'create_digest_weekly'
		, 'notify_twitter'
		, 'notify_twitter_default'
		, 'tweet_from_sidebar'
		, 'give_tt_credit'
		, 'exclude_reply_tweets'
	);
	foreach ($yes_no as $key) {
		$var = $key.'_options';
		if ($aktt->$key == '0') {
			$$var = '
				<option value="0" selected="selected">'.__('No', 'twitter-tools').'</option>
				<option value="1">'.__('Yes', 'twitter-tools').'</option>
			';
		}
		else {
			$$var = '
				<option value="0">'.__('No', 'twitter-tools').'</option>
				<option value="1" selected="selected">'.__('Yes', 'twitter-tools').'</option>
			';
		}
	}
	if ( $_GET['tweets-updated'] ) {
		print('
			<div id="message" class="updated fade">
				<p>'.__('Tweets updated.', 'twitter-tools').'</p>
			</div>
		');
	}
	if ( $_GET['tweet-checking-reset'] ) {
		print('
			<div id="message" class="updated fade">
				<p>'.__('Tweet checking has been reset.', 'twitter-tools').'</p>
			</div>
		');
	}
	
	if ( strcmp($_GET['oauth'], "success" ) == 0 ) {
		print('
			<div id="message" class="updated fade">
				<p>'.__('Yay! We connected with Twitter.', 'twitter-tools').'</p>
			</div>

		');
	}
	else if (strcmp($_GET['oauth'], "fail" ) == 0 ) {
		print('
			<div id="message" class="updated fade">
				<p>'.__('Authentication Failed. Please check your credentials and make sure <a href="http://www.twitter.com/" title="Twitter" target="_blank">Twitter</a> is up and running.', 'twitter-tools').'</p>
			</div>

		');
	}
	
	print('	
			<div class="wrap" id="aktt_options_page">
			<h2>'.__('Twitter Tools Options', 'twitter-tools').' &nbsp; <script type="text/javascript">var WPHC_AFF_ID = "14303"; var WPHC_POSITION = "c1"; var WPHC_PRODUCT = "Twitter Tools ('.AKTT_VERSION.')"; var WPHC_WP_VERSION = "'.$wp_version.'";</script><script type="text/javascript" src="http://cloud.wphelpcenter.com/support-form/0001/deliver-a.js"></script></h2>'
	);
	if ( !aktt_oauth_test() ) {
		print('	
			<h3>'.__('Connect to Twitter','twitter-tools').'</h3>
			<p style="width: 700px;">'.__('In order to get started, we need to follow some steps to get this site registered with Twitter. This process is awkward and more complicated than it should be. We hope to have a better solution for this in a future release, but for now this system is what Twitter supports. If you have any trouble, please use the Support button above to contact <a href="http://wphelpcenter.com" target="_blank">WordPress HelpCenter</a> and provide code 14303.', 'twitter-tools').'</p> 
			<form id="ak_twittertools" name="ak_twittertools" action="'.admin_url('options-general.php').'" method="post">
				<fieldset class="options">
					<h4>'.__('1. Register this site as an application on ', 'twitter-tools') . '<a href="http://dev.twitter.com/apps/new" title="'.__('Twitter App Registration','twitter-tools').'" target="_blank">'.__('Twitter\'s app registration page','twitter-tools').'</a></h4>
					<div id="aktt_sub_instructions">
						<ul>
						<li>'.__('If you\'re not logged in, you can use your Twitter username and password' , 'twitter-tools').'</li>
						<li>'.__('Your Application\'s Name will be what shows up after "via" in your twitter stream' , 'twitter-tools').'</li>
						<li>'.__('Application Type should be set on ' , 'twitter-tools').'<strong>'.__('Browser' , 'twitter-tools').'</strong></li>
						<li>'.__('The Callback URL should be ' , 'twitter-tools').'<strong>'.  get_bloginfo( 'url' ) .'</strong></li>
						<li>'.__('Default Access type should be set to ' , 'twitter-tools').'<strong>'.__('Read &amp; Write' , 'twitter-tools').'</strong> '.__('(this is NOT the default)' , 'twitter-tools').'</li>
						</ul>
					<p>'.__('Once you have registered your site as an application, you will be provided with a consumer key and a comsumer secret.' , 'twitter-tools').'</p>
					</div>
					<h4>'.__('2. Copy and paste your consumer key and consumer secret into the fields below' , 'twitter-tools').'</h4>
				
					<div class="option">
						<label for="aktt_app_consumer_key">'.__('Twitter Consumer Key', 'twitter-tools').'</label>
						<input type="text" size="25" name="aktt_app_consumer_key" id="aktt_app_consumer_key" value="'.esc_attr($aktt->app_consumer_key).'" autocomplete="off">
					</div>
					<div class="option">
						<label for="aktt_app_consumer_secret">'.__('Twitter Consumer Secret', 'twitter-tools').'</label>
						<input type="text" size="25" name="aktt_app_consumer_secret" id="aktt_app_consumer_secret" value="'.esc_attr($aktt->app_consumer_secret).'" autocomplete="off">
					</div>
					<h4>3. Copy and paste your Access Token and Access Token Secret into the fields below</h4>
					<p>On the right hand side of your application page, click on \'My Access Token\'.</p>
					<div class="option">
						<label for="aktt_oauth_token">'.__('Access Token', 'twitter-tools').'</label>
						<input type="text" size="25" name="aktt_oauth_token" id="aktt_oauth_token" value="'.esc_attr($aktt->oauth_token).'" autocomplete="off">
					</div>
					<div class="option">
						<label for="aktt_oauth_token_secret">'.__('Access Token Secret', 'twitter-tools').'</label>
						<input type="text" size="25" name="aktt_oauth_token_secret" id="aktt_oauth_token_secret" value="'.esc_attr($aktt->oauth_token_secret).'" autocomplete="off">
					</div>
				</fieldset>
				<p class="submit">
					<input type="submit" name="submit" class="button-primary" value="'.__('Connect to Twitter', 'twitter-tools').'" />
				</p>
				<input type="hidden" name="ak_action" value="aktt_oauth_test" class="hidden" style="display: none;" />
				'.wp_nonce_field('aktt_oauth_test', '_wpnonce', true, false).wp_referer_field(false).'
			</form>
				
				');
	}
	else if ( aktt_oauth_test() ) {
		print('	
			<form id="ak_twittertools_disconnect" name="ak_twittertools_disconnect" action="'.admin_url('options-general.php').'" method="post">
				<p><a href="#" id="aktt_authentication_showhide" class="auth_information_link">Account Information</a></p>
				<div id="aktt_authentication_display">
					<fieldset class="options">
						<div class="option"><span class="auth_label">'.__('Twitter Username ', 'twitter-tools').'</span><span class="auth_code">'.$aktt->twitter_username.'</span></div>
						<div class="option"><span class="auth_label">'.__('Consumer Key ', 'twitter-tools').'</span><span class="auth_code">'.$aktt->app_consumer_key.'</span></div>
						<div class="option"><span class="auth_label">'.__('Consumer Secret ', 'twitter-tools').'</span><span class="auth_code">'.$aktt->app_consumer_secret.'</span></div>
						<div class="option"><span class="auth_label">'.__('Access Token ', 'twitter-tools').'</span><span class="auth_code">'.$aktt->oauth_token.'</span></div>
						<div class="option"><span class="auth_label">'.__('Access Token Secret ', 'twitter-tools').'</span><span class="auth_code">'.$aktt->oauth_token_secret.'</span></div>
					</fieldset>
					<p class="submit">
					<input type="submit" name="submit" class="button-primary" value="'.__('Disconnect Your WordPress and Twitter Account', 'twitter-tools').'" />
					</p>
					<input type="hidden" name="ak_action" value="aktt_twitter_disconnect" class="hidden" style="display: none;" />
					'.wp_nonce_field('aktt_twitter_disconnect', '_wpnonce', true, false).wp_referer_field(false).' 
				</div>		
			</form>
					
			<form id="ak_twittertools" name="ak_twittertools" action="'.admin_url('options-general.php').'" method="post">
				<fieldset class="options">			
					<div class="option">
						<label for="aktt_notify_twitter">'.__('Enable option to create a tweet when you post in your blog?', 'twitter-tools').'</label>
						<select name="aktt_notify_twitter" id="aktt_notify_twitter">'.$notify_twitter_options.'</select>
					</div>
					<div class="option">
						<label for="aktt_tweet_prefix">'.__('Tweet prefix for new blog posts:', 'twitter-tools').'</label>
						<input type="text" size="30" name="aktt_tweet_prefix" id="aktt_tweet_prefix" value="'.esc_attr($aktt->tweet_prefix).'" /><span>'.__('Cannot be left blank. Will result in <b>{Your prefix}: Title URL</b>', 'twitter-tools').'</span>
					</div>
					<div class="option">
						<label for="aktt_notify_twitter_default">'.__('Set this on by default?', 'twitter-tools').'</label>
						<select name="aktt_notify_twitter_default" id="aktt_notify_twitter_default">'.$notify_twitter_default_options.'</select><span>'							.__('Also determines tweeting for posting via XML-RPC', 'twitter-tools').'</span>
					</div>
					<div class="option">
						<label for="aktt_create_blog_posts">'.__('Create a blog post from each of your tweets?', 'twitter-tools').'</label>
						<select name="aktt_create_blog_posts" id="aktt_create_blog_posts">'.$create_blog_posts_options.'</select>
					</div>
					<div class="option">
						<label for="aktt_blog_post_category">'.__('Category for tweet posts:', 'twitter-tools').'</label>
						<select name="aktt_blog_post_category" id="aktt_blog_post_category">'.$cat_options.'</select>
					</div>
					<div class="option">
						<label for="aktt_blog_post_tags">'.__('Tag(s) for your tweet posts:', 'twitter-tools').'</label>
						<input name="aktt_blog_post_tags" id="aktt_blog_post_tags" value="'.esc_attr($aktt->blog_post_tags).'">
						<span>'.__('Separate multiple tags with commas. Example: tweets, twitter', 'twitter-tools').'</span>
					</div>
					<div class="option">
						<label for="aktt_blog_post_author">'.__('Author for tweet posts:', 'twitter-tools').'</label>
						<select name="aktt_blog_post_author" id="aktt_blog_post_author">'.$author_options.'</select>
					</div>
					<div class="option">
						<label for="aktt_exclude_reply_tweets">'.__('Exclude @reply tweets in your sidebar, digests and created blog posts?', 'twitter-tools').'</label>
						<select name="aktt_exclude_reply_tweets" id="aktt_exclude_reply_tweets">'.$exclude_reply_tweets_options.'</select>
					</div>
					<div class="option">
						<label for="aktt_sidebar_tweet_count">'.__('Tweets to show in sidebar:', 'twitter-tools').'</label>
						<input type="text" size="3" name="aktt_sidebar_tweet_count" id="aktt_sidebar_tweet_count" value="'.esc_attr($aktt->sidebar_tweet_count).'" />
						<span>'.__('Numbers only please.', 'twitter-tools').'</span>
					</div>
					<div class="option">
						<label for="aktt_tweet_from_sidebar">'.__('Create tweets from your sidebar?', 'twitter-tools').'</label>
						<select name="aktt_tweet_from_sidebar" id="aktt_tweet_from_sidebar">'.$tweet_from_sidebar_options.'</select>
					</div>
					<div class="option">
						<label for="aktt_js_lib">'.__('JS Library to use?', 'twitter-tools').'</label>
						<select name="aktt_js_lib" id="aktt_js_lib">'.$js_lib_options.'</select>
					</div>
					<div class="option">
						<label for="aktt_give_tt_credit">'.__('Give Twitter Tools credit?', 'twitter-tools').'</label>
						<select name="aktt_give_tt_credit" id="aktt_give_tt_credit">'.$give_tt_credit_options.'</select>
					</div>
				
					<div class="aktt_experimental">
						<h4>'.__('- Experimental -', 'twitter-tools').'</h4>
				
					<div class="option time_toggle">
						<label>'.__('Create a daily digest blog post from your tweets?', 'twitter-tools').'</label>
						<select name="aktt_create_digest" class="toggler">'.$create_digest_options.'</select>
						<input type="hidden" class="time" id="aktt_digest_daily_time" name="aktt_digest_daily_time" value="'.esc_attr($aktt->digest_daily_time).'" />
					</div>
					<div class="option">
						<label for="aktt_digest_title">'.__('Title for daily digest posts:', 'twitter-tools').'</label>
						<input type="text" size="30" name="aktt_digest_title" id="aktt_digest_title" value="'.$aktt->digest_title.'" />
						<span>'.__('Include %s where you want the date. Example: Tweets on %s', 'twitter-tools').'</span>
					</div>
					<div class="option time_toggle">
						<label>'.__('Create a weekly digest blog post from your tweets?', 'twitter-tools').'</label>
						<select name="aktt_create_digest_weekly" class="toggler">'.$create_digest_weekly_options.'</select>
						<input type="hidden" class="time" name="aktt_digest_weekly_time" id="aktt_digest_weekly_time" value="'.esc_attr($aktt->digest_weekly_time).'" />
						<input type="hidden" class="day" name="aktt_digest_weekly_day" value="'.$aktt->digest_weekly_day.'" />
					</div>
					<div class="option">
						<label for="aktt_digest_title_weekly">'.__('Title for weekly digest posts:', 'twitter-tools').'</label>
						<input type="text" size="30" name="aktt_digest_title_weekly" id="aktt_digest_title_weekly" value="'.esc_attr($aktt->digest_title_weekly).'" />
						<span>'.__('Include %s where you want the date. Example: Tweets on %s', 'twitter-tools').'</span>
					</div>
					<div class="option">
						<label for="aktt_digest_tweet_order">'.__('Order of tweets in digest?', 'twitter-tools').'</label>
						<select name="aktt_digest_tweet_order" id="aktt_digest_tweet_order">'.$digest_tweet_order_options.'</select>
					</div>
				
					</div>
				
				</fieldset>
				<p class="submit">
					<input type="submit" name="submit" class="button-primary" value="'.__('Update Twitter Tools Options', 'twitter-tools').'" />
				</p>
				<input type="hidden" name="ak_action" value="aktt_update_settings" class="hidden" style="display: none;" />
				'.wp_nonce_field('aktt_settings', '_wpnonce', true, false).wp_referer_field(false).'
			</form>
			<h2>'.__('Update Tweets / Reset Checking and Digests', 'twitter-tools').'</h2>
			<form name="ak_twittertools_updatetweets" action="'.admin_url('options-general.php').'" method="get">
				<p>'.__('Use these buttons to manually update your tweets or reset the checking settings.', 'twitter-tools').'</p>
				<p class="submit">
					<input type="submit" name="submit-button" value="'.__('Update Tweets', 'twitter-tools').'" />
					<input type="submit" name="reset-button-1" value="'.__('Reset Tweet Checking', 'twitter-tools').'" onclick="document.getElementById(\'ak_action_2\').value = \'aktt_reset_tweet_checking\';" />
					<input type="submit" name="reset-button-2" value="'.__('Reset Digests', 'twitter-tools').'" onclick="document.getElementById(\'ak_action_2\').value = \'aktt_reset_digests\';" />
					<input type="hidden" name="ak_action" id="ak_action_2" value="aktt_update_tweets" />
				</p>
				'.wp_nonce_field('aktt_update_tweets', '_wpnonce', true, false).wp_referer_field(false).'
			</form>
	');
	} //end elsif statement
	do_action('aktt_options_form');
?>
	<div id="cf">
		<div id="cf-callouts">
			<div class="cf-callout">
				<div id="cf-callout-credit" class="cf-box">
					<h3 class="cf-box-title">Plugin Developed By</h3>
					<div class="cf-box-content">
						<p class="txt-center"><a href="http://crowdfavorite.com/" title="Crowd Favorite : Elegant WordPress and Web Application Development"><img src="http://cloud.wphelpcenter.com/resources/wp-admin-0001/cf-logo.png" alt="Crowd Favorite"></a></p>
						<p>An independent development firm specializing in WordPress development and integrations, sophisticated web applications, Open Source implementations and user experience consulting. If you need it to work, trust Crowd Favorite to build it.</p>
					</div><!-- .cf-box-content -->
				</div><!-- #cf-callout-credit -->						
			</div>
			<div class="cf-callout">
				<div id="cf-callout-support" class="cf-box">
					<h3 class="cf-box-title">Professional Support From</h3>
					<div class="cf-box-content">
						<p class="txt-center"><a href="http://wphelpcenter.com/" title="WordPress HelpCenter"><img src="http://cloud.wphelpcenter.com/resources/wp-admin-0001/wphc-logo.png" alt="WordPress HelpCenter"></a></p>
						<p>Need help with WordPress right now? That's what we're here for. We can help with anything from how-to questions to server troubleshooting, theme customization to upgrades and installs. Give us a call - 303-395-1346.</p>
					</div><!-- .cf-box-content -->
				</div><!-- #cf-callout-support -->						
			</div>
		</div><!-- #cf-callouts -->
	</div><!-- #cf -->
<?php
	print('
				
			</div>
	');
}

function aktt_meta_box() {
	global $aktt, $post;
	if ($aktt->notify_twitter) {
		$notify = get_post_meta($post->ID, 'aktt_notify_twitter', true);
		if ($notify == '') {
			switch ($aktt->notify_twitter_default) {
				case '1':
					$notify = 'yes';
					break;
				case '0':
					$notify = 'no';
					break;
			}
		}
		echo '
			<p>'.__('Send post to Twitter?', 'twitter-tools').'
		&nbsp;
		<input type="radio" name="aktt_notify_twitter" id="aktt_notify_twitter_yes" value="yes" '.checked('yes', $notify, false).' /> <label for="aktt_notify_twitter_yes">'.__('Yes', 'twitter-tools').'</label> &nbsp;&nbsp;
		<input type="radio" name="aktt_notify_twitter" id="aktt_notify_twitter_no" value="no" '.checked('no', $notify, false).' /> <label for="aktt_notify_twitter_no">'.__('No', 'twitter-tools').'</label>
		';
		echo '
			</p>
		';
		do_action('aktt_post_options');
	}
}
function aktt_add_meta_box() {
	global $aktt;
	if ($aktt->notify_twitter) {
		add_meta_box('aktt_post_form', __('Twitter Tools', 'twitter-tools'), 'aktt_meta_box', 'post', 'side');
	}
}
add_action('admin_init', 'aktt_add_meta_box');

function aktt_store_post_options($post_id, $post = false) {
	global $aktt;
	$post = get_post($post_id);
	if (!$post || $post->post_type == 'revision') {
		return;
	}

	$notify_meta = get_post_meta($post_id, 'aktt_notify_twitter', true);
	$posted_meta = $_POST['aktt_notify_twitter'];

	$save = false;
	if (!empty($posted_meta)) {
		$posted_meta == 'yes' ? $meta = 'yes' : $meta = 'no';
		$save = true;
	}
	else if (empty($notify_meta)) {
		$aktt->notify_twitter_default ? $meta = 'yes' : $meta = 'no';
		$save = true;
	}
	
	if ($save) {
		update_post_meta($post_id, 'aktt_notify_twitter', $meta);
	}
}
add_action('draft_post', 'aktt_store_post_options', 1, 2);
add_action('publish_post', 'aktt_store_post_options', 1, 2);
add_action('save_post', 'aktt_store_post_options', 1, 2);

function aktt_menu_items() {
	if (current_user_can('manage_options')) {
		add_options_page(
			__('Twitter Tools Options', 'twitter-tools')
			, __('Twitter Tools', 'twitter-tools')
			, 10
			, basename(__FILE__)
			, 'aktt_options_form'
		);
	}
	if (current_user_can('publish_posts')) {
		add_submenu_page(
			'post-new.php'
			, __('New Tweet', 'twitter-tools')
			, __('Tweet', 'twitter-tools')
			, 2
			, basename(__FILE__)
			, 'aktt_admin_tweet_form'
		);
	}
}
add_action('admin_menu', 'aktt_menu_items');

function aktt_plugin_action_links($links, $file) {
	$plugin_file = basename(__FILE__);
	if (basename($file) == $plugin_file) {
		$settings_link = '<a href="options-general.php?page='.$plugin_file.'">'.__('Settings', 'twitter-tools').'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'aktt_plugin_action_links', 10, 2);

if (!function_exists('trim_add_elipsis')) {
	function trim_add_elipsis($string, $limit = 100) {
		if (strlen($string) > $limit) {
			$string = substr($string, 0, $limit)."...";
		}
		return $string;
	}
}

if (!function_exists('ak_gmmktime')) {
	function ak_gmmktime() {
		return gmmktime() - get_option('gmt_offset') * 3600;
	}
}

/**

based on: http://www.gyford.com/phil/writing/2006/12/02/quick_twitter.php

	 * Returns a relative date, eg "4 hrs ago".
	 *
	 * Assumes the passed-in can be parsed by strtotime.
	 * Precision could be one of:
	 * 	1	5 hours, 3 minutes, 2 seconds ago (not yet implemented).
	 * 	2	5 hours, 3 minutes
	 * 	3	5 hours
	 *
	 * This is all a little overkill, but copied from other places I've used it.
	 * Also superfluous, now I've noticed that the Twitter API includes something
	 * similar, but this version is more accurate and less verbose.
	 *
	 * @access private.
	 * @param string date In a format parseable by strtotime().
	 * @param integer precision
	 * @return string
	 */
function aktt_relativeTime ($date, $precision=2)
{

	$now = time();

	$time = gmmktime(
		substr($date, 11, 2)
		, substr($date, 14, 2)
		, substr($date, 17, 2)
		, substr($date, 5, 2)
		, substr($date, 8, 2)
		, substr($date, 0, 4)
	);

	$time = strtotime(date('Y-m-d H:i:s', $time));

	$diff 	=  $now - $time;

	$months	=  floor($diff/2419200);
	$diff 	-= $months * 2419200;
	$weeks 	=  floor($diff/604800);
	$diff	-= $weeks*604800;
	$days 	=  floor($diff/86400);
	$diff 	-= $days * 86400;
	$hours 	=  floor($diff/3600);
	$diff 	-= $hours * 3600;
	$minutes = floor($diff/60);
	$diff 	-= $minutes * 60;
	$seconds = $diff;

	if ($months > 0) {
		return date_i18n( __('Y-m-d', 'twitter-tools'), $time);
	} else {
		$relative_date = '';
		if ($weeks > 0) {
			// Weeks and days
			$relative_date .= ($relative_date?', ':'').$weeks.' '.__ngettext('week', 'weeks', $weeks, 'twitter-tools');
			if ($precision <= 2) {
				$relative_date .= $days>0? ($relative_date?', ':'').$days.' '.__ngettext('day', 'days', $days, 'twitter-tools'):'';
				if ($precision == 1) {
					$relative_date .= $hours>0?($relative_date?', ':'').$hours.' '.__ngettext('hr', 'hrs', $hours, 'twitter-tools'):'';
				}
			}
		} elseif ($days > 0) {
			// days and hours
			$relative_date .= ($relative_date?', ':'').$days.' '.__ngettext('day', 'days', $days, 'twitter-tools');
			if ($precision <= 2) {
				$relative_date .= $hours>0?($relative_date?', ':'').$hours.' '.__ngettext('hr', 'hrs', $hours, 'twitter-tools'):'';
				if ($precision == 1) {
					$relative_date .= $minutes>0?($relative_date?', ':'').$minutes.' '.__ngettext('min', 'mins', $minutes, 'twitter-tools'):'';
				}
			}
		} elseif ($hours > 0) {
			// hours and minutes
			$relative_date .= ($relative_date?', ':'').$hours.' '.__ngettext('hr', 'hrs', $hours, 'twitter-tools');
			if ($precision <= 2) {
				$relative_date .= $minutes>0?($relative_date?', ':'').$minutes.' '.__ngettext('min', 'mins', $minutes, 'twitter-tools'):'';
				if ($precision == 1) {
					$relative_date .= $seconds>0?($relative_date?', ':'').$seconds.' '.__ngettext('sec', 'secs', $seconds, 'twitter-tools'):'';
				}
			}
		} elseif ($minutes > 0) {
			// minutes only
			$relative_date .= ($relative_date?', ':'').$minutes.' '.__ngettext('min', 'mins', $minutes, 'twitter-tools');
			if ($precision == 1) {
				$relative_date .= $seconds>0?($relative_date?', ':'').$seconds.' '.__ngettext('sec', 'secs', $seconds, 'twitter-tools'):'';
			}
		} else {
			// seconds only
			$relative_date .= ($relative_date?', ':'').$seconds.' '.__ngettext('sec', 'secs', $seconds, 'twitter-tools');
		}
	}

	// Return relative date and add proper verbiage
	return sprintf(__('%s ago', 'twitter-tools'), $relative_date);
}

?>