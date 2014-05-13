<?php

class MF_Widget_Twitter extends WP_Widget {

	static $TYPES = array(
		'user' => 'User name',
		'search' => 'Search query',
	);

	function __construct() {
		$widget_ops = array( 'description' => __('Show latest Twitter entries') );
		$control_ops = array(); // 'width' => 400, 'height' => 200 );
		parent::__construct( 'twitter', __('Twitter'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		if (isset($instance['error']) && $instance['error'])
			return;

		extract($args, EXTR_SKIP);

		$url = ! empty($instance['url']) ? $instance['url'] : '';
		while (stristr($url, 'http') != $url)
			$url = substr($url, 1);

		if (empty($url))
			return;

		// self-url destruction sequence
		if (in_array(untrailingslashit($url), array(site_url(), home_url())))
			return;

		$rss = fetch_feed($url);
		$title = $instance['title'];
		$desc = '';
		$link = '';

		if (is_wp_error($rss)) return;

		$title = esc_html(strip_tags($title));
		$link = esc_url(strip_tags($rss->get_permalink()));
		while (stristr($link, 'http') != $link)
			$link = substr($link, 1);

		$icon = 'https://dev.twitter.com/sites/default/files/images_documentation/bird_black_16_0.png';
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		$title = '<img src="'.$icon.'" alt=""> <a class="twitter-widget" href="'.$link.'">'.$title.'</a>';
		$url = esc_url(strip_tags($url));

		echo $before_widget;
			echo $before_title . $title . $after_title;

		self::render_widget($rss, $instance, $instance['query'].':');

		echo $after_widget;

		if (!is_wp_error($rss))
			$rss->__destruct();
		unset($rss);
	}

	function update($new_instance, $old_instance) {
		$urls = array(
			'user' => 'http://twitter.com/statuses/user_timeline/%s.rss',
			'search' => 'http://search.twitter.com/search.rss?q=%s',
		);

		$old_url = @sprintf($urls[$old_instance['type']], urlencode($old_instance['query']));
		$new_url = @sprintf($urls[$new_instance['type']], urlencode($new_instance['query']));

		$new_instance['url'] = $new_url;
		$validate_feed = (!empty($new_url) || $new_url != $old_url);

		return self::process_form($new_instance, $validate_feed);
	}

	function form($instance) {
		if (empty($instance))
			$instance = array('title' => '', 'default_title' => '', 'set_title' => '', 'type' => 'user', 'items' => 1, 'error' => false);

		$instance['number'] = $this->number;
		
		return self::render_form($instance);
	}

	static function render_widget ($rss, $args=array(), $strip='') {
		if ( is_string( $rss ) ) {
			$rss = fetch_feed($rss);
		} elseif (is_array($rss) && isset($rss['url'])) {
			$args = $rss;
			$rss = fetch_feed($rss['url']);
		} elseif (!is_object($rss)) {
			return;
		}

		if (is_wp_error($rss)) {
			echo $rss->get_error_message();
			return;
		}

		extract($args, EXTR_SKIP);

		$items = (int) $items;
		if ( $items < 1 || $items > 10)
			$items = 10;

		if ( !$rss->get_item_quantity() ) {
			echo '<ul><li>' . __( 'An error has occurred; Twitter may well be down.' ) . '</li></ul>';
			$rss->__destruct();
			unset($rss);
			return;
		}

		echo '<ul>';
		foreach ($rss->get_items(0, $items) as $item) {
			$link = $item->get_link();
			while ( stristr($link, 'http') != $link )
				$link = substr($link, 1);
			$link = esc_url(strip_tags($link));

			$tweet = esc_html(str_replace(array("\n", "\r"), ' ', esc_attr(strip_tags(@html_entity_decode($item->get_description(), ENT_QUOTES, get_option('blog_charset'))))));
			$tweet = trim(preg_replace('/^'.preg_quote($strip, '/').'/', '', $tweet));

			$tweet = preg_replace('#(https?://.*?)(\s|$)#', '<a href="$1" rel="nofollow">$1</a>', $tweet);
			$tweet = preg_replace('/@(\w+)(\b)/', '<a href="https://twitter.com/$1" rel="nofollow">@$1</a>$2', $tweet);
			$tweet = preg_replace('/(\s)#(\w+)(\b)/', '$1<a href="https://twitter.com/search/?q=%23$2" rel="nofollow">#$2</a>$3', $tweet);

			echo '<li>'.$tweet.' <a href="'.$link.'" class="link">&rarr;</a></li>';
		}
		echo '</ul>';
		$rss->__destruct();
		unset($rss);
	}

	static function process_form ($form, $validate_feed) {
		$set_title = @trim(strip_tags($form['set_title']));
		$default_title = @trim(strip_tags($form['default_title']));
		$query = trim(strip_tags($form['query']));
		$type = $form['type'];
		$items = (int) $form['items'];
		$error = false;
		$link = '';
		$url = $form['url'];

		if ($items < 1 || $items > 10)
			$items = 10;

		if ($validate_feed) {
			$rss = fetch_feed($url);
			if (is_wp_error($rss)) {
				$error = $rss->get_error_message();
			} else {
				$link = esc_url(strip_tags($rss->get_permalink()));
				while ( stristr($link, 'http') != $link )
					$link = substr($link, 1);
				$default_title = $rss->get_description();

				$rss->__destruct();
				unset($rss);
			}
		}

		$title = empty($set_title) ? $default_title : $set_title;
		$title = empty($title) ? $query : $title;

		return compact('title', 'set_title', 'default_title', 'query', 'type', 'items', 'error', 'link', 'url');
	}

	static function render_form ($args, $inputs=null) {
		$default_inputs = array('show_type' => true, 'show_count' => true);
		$inputs = wp_parse_args($inputs, $default_inputs);

		extract($args);
		extract($inputs, EXTR_SKIP);

		$number = @esc_attr($number);
		$set_title  = @esc_attr($set_title);
		$default_title = @esc_attr($default_title);
		$query  = @esc_attr($query);
		$type   = @esc_attr($type);
		$items  = @(int) $items;

		if ( $items < 1 || $items > 10 )
			$items = 10;

		echo '<p><label for="twitter-title-'.$number.'">Title (optional):</label>';
		echo '<input class="widefat" type="text" name="widget-twitter['.$number.'][set_title]" value="'.$set_title.'" id="twitter-title-'.$number.'" placeholder="'.$default_title.'"></p>';
		if ($show_type) {
			echo '<p><label>Input type: <select name="widget-twitter['.$number.'][type]">';
			foreach (self::$TYPES as $type_name => $type_label) {
				$selected = ($type_name === $type) ? ' selected="selected"' : '';
				echo '<option value="'.$type_name.'"'.$selected.'> '.$type_label.'</option>';
			}
			echo '</select></label></p>';
		}
		echo '<p><input class="widefat" name="widget-twitter['.$number.'][query]" type="text" value="'.$query.'"></p>';
		if ($show_count) {
			echo '<p><label for="twitter-items-'.$number.'">';
			_e('How many items would you like to display?');
			echo '</label>';
			echo '<select id="twitter-items-'.$number.'" name="widget-twitter['.$number.'][items]">';
			for ($i = 1; $i <= 10; ++$i)
				echo '<option value="'.$i.'"'.($items == $i ? " selected='selected'" : '').'">'.$i.'</option>';
			echo '</select></p>';
		}
	}
}

class MF_Widget_Flickr extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'description' => __('Show latest Flickr favourites') );
		$control_ops = array(); // 'width' => 400, 'height' => 200 );
		parent::__construct( 'flickr', __('Flickr'), $widget_ops, $control_ops );
	}

	static function nsid_from_username ($username) {
		if (empty($username)) return false;

		$url = 'http://api.flickr.com/services/rest/?method=flickr.urls.lookupUser&api_key=%s&url=flickr.com%%2Fphotos%%2F%s&format=json&nojsoncallback=1';
		$api_key = '9d27bf158c25fc36e43015bd5b0b1827';
		$data = json_decode(file_get_contents(sprintf($url, $api_key, $username)));

		if ($data->stat != 'ok') {
			throw new Exception($data->message);
		}

		return $data->user->id;
	}

	function widget ($args, $instance) {
		if (isset($instance['error']) && $instance['error'])
			return;

		extract($args, EXTR_SKIP);

		$url = ! empty($instance['url']) ? $instance['url'] : '';
		while (stristr($url, 'http') != $url)
			$url = substr($url, 1);

		if (empty($url))
			return;

		// self-url destruction sequence
		if (in_array(untrailingslashit($url), array(site_url(), home_url())))
			return;

		$rss = fetch_feed($url);
		$title = $instance['title'];
		$desc = '';
		$link = '';

		if (is_wp_error($rss)) return;

		$title = esc_html(strip_tags($title));
		$link = esc_url(strip_tags($rss->get_permalink()));
		while (stristr($link, 'http') != $link)
			$link = substr($link, 1);

		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		$url = esc_url(strip_tags($url));

		echo $before_widget;
		echo $before_title . $title . $after_title;

		self::render_widget($rss, $instance, $instance['query'].':');

		echo $after_widget;

		if (!is_wp_error($rss))
			$rss->__destruct();

		unset($rss);
	}

	function update ($new_instance, $old_instance) {
		try {
			$nsid = self::nsid_from_username($new_instance['query']);

			if (@$old_instance['query'] == @$new_instance['query']) {
				$validate_feed = false;
				$new_instance['url'] = $old_instance['url'];
			} else {
				if ($nsid) {
					$validate_feed = true;
					$url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=%s&format=rss_200';
					$new_instance['url'] = sprintf($url, $nsid);
				} else {
					$validate_feed = false;
				}
			}
		} catch (Exception $e) {
			$new_instance['url'] = null;
			$new_instance['error'] = $e->getMessage();
			$validate_feed = false;
		}

		return self::process_form($new_instance, $validate_feed);
	}

	function form ($instance) {
		if (empty($instance))
			$instance = array('title' => '', 'default_title' => '', 'set_title' => '', 'type' => 'user', 'items' => 1, 'error' => false);

		$instance['number'] = $this->number;

		return self::render_form($instance);
	}

	static function render_widget ($rss, $args=array()) {
		if ( is_string( $rss ) ) {
			$rss = fetch_feed($rss);
		} elseif (is_array($rss) && isset($rss['url'])) {
			$args = $rss;
			$rss = fetch_feed($rss['url']);
		} elseif (!is_object($rss)) {
			return;
		}

		if (is_wp_error($rss)) {
			echo $rss->get_error_message();
			return;
		}

		extract($args, EXTR_SKIP);

		$items = (int) $items;
		if ( $items < 1 || $items > 10)
			$items = 10;

		if ( !$rss->get_item_quantity() ) {
			echo '<ul><li>' . __( 'An error has occurred; Flickr may well be down.' ) . '</li></ul>';
			// echo '<pre>'.print_r($rss,1).'</pre>';
			$rss->__destruct();
			unset($rss);
			return;
		}

		echo '<ul>';
		foreach ($rss->get_items(0, $items) as $item) {
			if ($enclosure = $item->get_enclosure()) {
				$src = preg_replace('/_b.jpg$/', '_n.jpg', $enclosure->link);
				echo '<li><a href="'.$rss->get_link().'"><img src="'.$src.'"></img></a></li>';
			}
		}
		echo '</ul>';
		$rss->__destruct();
		unset($rss);
	}

	static function process_form ($form, $validate_feed) {
		$set_title = @trim(strip_tags($form['set_title']));
		$default_title = @trim(strip_tags($form['default_title']));
		$query = trim(strip_tags($form['query']));
		$items = (int) $form['items'];
		$error = @$form['error'];
		$link = '';
		$url = $form['url'];

		if ($items < 1 || $items > 10)
			$items = 10;

		if ($validate_feed) {
			$rss = fetch_feed($url);
			if (is_wp_error($rss)) {
				$error = $rss->get_error_message();
			} else {
				$link = esc_url(strip_tags($rss->get_permalink()));
				while ( stristr($link, 'http') != $link )
					$link = substr($link, 1);
				$default_title = $rss->get_description();

				$rss->__destruct();
				unset($rss);
			}
		}

		$title = empty($set_title) ? $default_title : $set_title;
		$title = empty($title) ? $query : $title;

		return compact('title', 'set_title', 'default_title', 'query', 'items', 'error', 'link', 'url');
	}

	static function render_form ($args, $inputs=null) {
		$default_inputs = array('show_count' => true);
		$inputs = wp_parse_args($inputs, $default_inputs);

		extract($args);
		extract($inputs, EXTR_SKIP);

		$number = @esc_attr($number);
		$set_title  = @esc_attr($set_title);
		$default_title = @esc_attr($default_title);
		$query  = @esc_attr($query);
		$type   = @esc_attr($type);
		$items  = @(int) $items;
		$error  = @esc_attr($error);

		if ( $items < 1 || $items > 10 )
			$items = 10;

		if (!empty($error))
			echo '<p class="widget-error"><strong>' . sprintf( __('Flickr Error: %s'), $error) . '</strong></p>';

		echo '<p><label for="flickr-title-'.$number.'">Title (optional):</label>';
		echo '<input class="widefat" type="text" name="widget-flickr['.$number.'][set_title]" value="'.$set_title.'" id="flickr-title-'.$number.'" placeholder="'.$default_title.'"></p>';

		echo '<p><label for="flickr-query-'.$number.'">Username (required):</label>';
		echo '<input class="widefat" name="widget-flickr['.$number.'][query]" type="text" value="'.$query.'" id="flickr-query-'.$number.'"></p>';

		if ($show_count) {
			echo '<p><label for="flickr-items-'.$number.'">';
			_e('How many items would you like to display?');
			echo '</label>';
			echo '<select id="flickr-items-'.$number.'" name="widget-flickr['.$number.'][items]">';
			for ($i = 1; $i <= 10; ++$i)
				echo '<option value="'.$i.'"'.($items == $i ? " selected='selected'" : '').'">'.$i.'</option>';
			echo '</select></p>';
		}
	}
}

function mf2012_widgets_init () {
	if (!is_blog_installed())
		return;

	register_widget('MF_Widget_Twitter');
	register_widget('MF_Widget_Flickr');

	do_action('widgets_init');
}

add_action('init', 'mf2012_widgets_init', 2);
