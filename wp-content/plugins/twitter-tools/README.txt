=== Twitter Tools ===
Tags: twitter, tweet, integration, post, digest, notify, integrate, archive, widget
Contributors: alexkingorg, crowdfavorite
Requires at least: 2.9
Tested up to: 3.0.1
Stable tag: 2.4

Twitter Tools is a plugin that creates a complete integration between your WordPress blog and your Twitter account.

== Details ==

Twitter Tools integrates with Twitter by giving you the following functionality:

* Archive your Twitter tweets (downloaded every 10 minutes)
* Create a blog post from each of your tweets
* Create a daily or weekly digest post of your tweets
* Create a tweet on Twitter whenever you post in your blog, with a link to the blog post
* Post a tweet from your sidebar
* Post a tweet from the WP Admin screens
* Pass your tweets along to another service (via API hook)


== Installation ==

1. Download the plugin archive and expand it (you've likely already done this).
2. Put the 'twitter-tools' directory into your wp-content/plugins/ directory.
3. Go to the Plugins page in your WordPress Administration area and click 'Activate' for Twitter Tools.
4. Go to the Twitter Tools Options page (Settings > Twitter Tools) to set up your Twitter information and preferences.


== Configuration ==

There are a number of configuration options for Twitter Tools. You can find these in Options > Twitter Tools.

== Showing Your Tweets ==

= Widget Friendly =

If you are using widgets, you can drag Twitter Tools to your sidebar to display your latest tweets.


= Shortcode =

Use:

`[aktt_tweets]`

to show your latest tweets. This will show the number of tweets set in your Settings. If you want to control how many tweets are shown explicitly, you can do so by adding a 'count' parameter like this:

`[aktt_tweets count=5]`


= Template Tags =

If you are not using widgest, you can use a template tag to add your latest tweets to your sidebar.

`<?php aktt_sidebar_tweets(); ?>`


If you just want your latest tweet, use this template tag.

`<?php aktt_latest_tweet(); ?>`


== Plugins ==

Twitter Tools supports plugins, several are included. You can find more here:

http://delicious.com/alexkingorg/twitter-tools+plugin


== Hooks/API ==

Twitter Tools contains a hook that can be used to pass along your tweet data to another service (for example, some folks have wanted to be able to update their Facebook status). To use this hook, create a plugin and add an action to:

`aktt_add_tweet` (action)

Your plugin function will receive an `aktt_tweet` object as the first parameter.

Example psuedo-code:

`function my_status_update($tweet) { // do something here }`
`add_action('aktt_add_tweet', 'my_status_update')`

---

Twitter Tools also provides a filter on the URL sent to Twitter so that you can run it through an URL-shortening service if you like.

`tweet_blog_post_url` (filter)

Your plugin function will receive the URL as the first parameter.

Example psuedo-code:

`function my_short_url($long_url) {
	// do something here - return the shortened URL 
	$short_url = my_short_url_func($long_url);
	return $short_url;
}`
`add_filter('tweet_blog_post_url', 'my_short_url')`

---

`aktt_do_tweet` (filter)

Returning false in this hook will prevent a tweet from being sent. One parameter is sent, the Tweet object to be sent to Twitter.

Example psuedo-code:

`function dont_tweet($tweet) {
	if (some condition) {
		// will not tweet
		return false;
	}
	else {
		// must return the $tweet to send it
		return $tweet;
	}
}`
`add_filter('aktt_do_tweet', 'dont_tweet')`

---

`aktt_do_blog_post_tweet` (filter)

Returning false in this hook will prevent a blog post Tweet from being sent. Two parameters are passed, the Tweet object to be sent to Twitter and the Post generating the Tweet.

Example psuedo-code:

`function dont_post_tweet($tweet, $post) {
	if (some condition) {
		// will not tweet
		return false;
	}
	else {
		// must return the $tweet to send it
		return $tweet;
	}
}`
`add_filter('aktt_do_blog_post_tweet', 'dont_post_tweet', 10, 2)`

---

`aktt_do_tweet_post` (filter)

Returning false in this hook will prevent a blog post from being created from a Tweet. Two parameters are passed, the data to be used in the post and the Tweet object.

Example psuedo-code:

`function dont_tweet_post($post, $data) {
	if (some condition) {
		// will not post
		return false;
	}
	else {
		// must return the $data for a post to be created
		return $data;
	}
}`
`add_filter('aktt_do_tweet_post', 'dont_tweet_post', 10, 2)`

---

`aktt_tweets_to_digest_post` (filter)

Allows you to make changes the tweets that will be included in a digest post.

---

`aktt_options_form` (action)

Allows you to add to the Twitter Tools settings page.

---

`aktt_post_options` (action)

Allows you to add to the Twitter Tools box on the New Post page (requires the option to tweet on blog posts to be enabled).

== Known Issues ==

* If you change your blog post notification tweet prefix, the previous blog post notification might not be correctly recognized as a blog post tweet. This is only under very rare conditions due to timing issues.
* Only one Twitter account is supported (not one account per author).
* Tweets are not deleted from the tweet table in your WordPress database when they are deleted from Twitter. To delete from your WordPress database, use a database admin tool like phpMyAdmin.


== Frequently Asked Questions ==

= Who is allowed to post a Tweet from within WordPress? =

Anyone who has a 'publish_post' permission. Basically, if you can post to the blog, you can also post to Twitter (using the account info in the Twitter Tools configuration).

= What happens if I have both my tweets posting to my blog as posts and my posts sent to Twitter? Will it cause the world to end in a spinning fireball of death? = 

Actually, Twitter Tools has taken this into account and you can safely enable both creating posts from your tweets and tweets from your posts without duplicating them in either place.

= Does Twitter Tools use a URL shortening service by default? =

No, Twitter Tools sends your long URL to Twitter and Twitter chooses to shorten it or not.

As of version 2.0 a plugin to do this with the Bit.ly service is included as an option.

= Can Twitter Tools use a URL shortening service? =

Yes, Twitter Tools includes a filter:

`tweet_blog_post_url`

as of version 1.6. Plugins for this filter may already exist, or you can create your own. The plugin needs to attach to this filter using the standard WordPress `add_filter()` function and return a URL that will then be passed with your blog post tweet.

As of version 2.0 a plugin to do this with the Bit.ly service is included as an option.

= Is there any way to change the 'New Blog Post:' prefix when my new posts get tweeted? =

Yes, as of version 2.0 you can change this on the Options page.

= Can I remove the 'New Blog Post:' prefix entirely? =

No, this is not a good idea. Twitter Tools needs to be able to look at the beginning of the tweet and identify if it's a notification from your blog or not. Otherwise, Twitter Tools and Twitter could keep passing the blog posts and resulting tweets back and forth resulting in the 'spinning fireball of death' mentioned above.


== Changelog ==

= 2.4 =

* Replaced 401 authentication with OAuth.
* Now relies on WordPress to provide JSON encode/decode functions.
* WP 3.0 compatibility fix for hashtags plugin (set default hashtags properly).
* WP 3.0 compatibility fix for creating duplicate post meta.
* Added support form to settings page.


= 2.3.1 =

* Fixed a typo that was breaking the latest tweet template tag.


= 2.3 =

* Added nonces
* Patched several potential security issues (thanks Mark Jaquith)
* Load JS and CSS in separate process to possibly avoid some race conditions


= 2.2.1 =

* Typo-fix that should allow resetting digests properly (not sure when this broke, thanks lionel_chollet).


= 2.2 =

* The use of the native `json_encode()` function, required by the changes in WordPress 2.9 (version 2.1) created a problem for users with servers running 32-bit PHP. the `json_decode()` function treats the tweet ID field as an integer instead of a string, which causes the issues. Thanks to Joe Tortuga and Ciaran Walsh for sending in the fix.


= 2.1.2 =

* Missed one last(?) instance of Services_JSON


= 2.1.1 =

* Missed replacing a couple of instances of Services_JSON


= 2.1 =

* Make install code a little smarter
* Add unique index on tweet ID columns, remove duplicates and optimize table
* Track the currently installed version for easier upgrades in the future
* Cleanup around login test code
* Add action on Update Tweets (aktt_update_tweets)
* Add a shortcode to display recent tweets
* Exclude replies in aktt_latest_tweet() function (if option selected)
* Better RegEx for username and hashtag linking
* Use site_url() and admin_url(), losing backward compatibility but gaining SSL compatibility
* Added WordPress HelpCenter contact info to settings page
* Use standard meta boxes (not backwards compatible) for post screen settings
* Change how Services_JSON is included to be compatible with changes in WP 2.9 and PHP < 5.2
* Digest functionality is marked as experimental, they need to be fundamentally rewritten to avoid race conditions experienced by some users 
* Misc code cleanup and bug fixes
* Added language dir and .pot file

Bit.ly plugin

* Changed RegEx for finding URLs in tweet content (thanks Porter Maus)
* Added a j.mp option
* Cleaned up the settings form
* Added a trim() on the API Key for people that struggle with copy/paste
* Use admin_url(), losing backward compatibility but gaining SSL compatibility

Exclude Category plugin

* Use admin_url(), losing backward compatibility but gaining SSL compatibility

Hashtags plugin

* Use admin_url(), losing backward compatibility but gaining SSL compatibility


= 2.0 =

* Added various hooks and filters to enable other plugins to interact with Twitter Tools.
* Added option to set blog post tweet prefix
* Added CSS classes for elements in tweet list
* Initial release of Bit.ly for Twitter Tools - enables shortening your URLs and tracking them on your Bit.ly account.
* Initial release of #hashtags for Twitter Tools - enables adding hashtags to your blog post tweets.
* Initial release of Exclude Category for Twitter Tools - enables not tweeting posts in chosen categories.
