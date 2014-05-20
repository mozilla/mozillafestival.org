<?php

if (!current_user_can('manage_options'))  {
	wp_die(__('You do not have sufficient permissions to access this page.'));
}

function parse ($content) {
	$ical = array('events' => array());
	$event = null;

	foreach (explode("\n", $content) as $line) {
		list($key, $value) = explode(':', $line, 2);
		$key_parts = explode(';', $key);
		$key = strtolower(trim(array_shift($key_parts)));
		$value = stripslashes(str_replace('\n', "\n", trim($value)));
		switch ($key) {
			case "begin":
				if ($value === 'VEVENT') {
					$event = array();
				}
				break;
			case "end":
				if ($value === 'VEVENT') {
					$ical['events'][] = (object) $event;
					$event = null;
				}
				break;
			default:
				if (!is_null($event)) {
					if ($key == 'dtstart' || $key == 'dtend') {
						$value = strtotime($value);
						$event['is_date'] = !!count($key_parts);
					}
					$event[$key] = $value;
				} else {
					$ical[$key] = $value;
				}
				break;
		}
	}

	return (object) $ical;
}

function get_session_from_uid ($uid) {
	global $wpdb;

	$querystr = '
	SELECT post_id, count(post_id) FROM ' . $wpdb->postmeta .'
		WHERE meta_key = "uid" AND meta_value = "%s"
		GROUP BY post_id;
	';

	$id = $wpdb->get_var($wpdb->prepare($querystr, $uid));
	$session = null;

	if (!is_null($id)) {
		$session = get_post($id);
	}

	return $session;	
}

if (isset($_REQUEST['import'])) {
	if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'import')) die('Nonce failure.');
	$created = 0;
	$updated = 0;
	$failed = 0;
	foreach ($_REQUEST['sessions'] as $session) {
		$id = @$session['id'];
		$uid = $session['uid'];
		$meta = $session['meta'];
		$title = $session['title'];
		$description = $session['description'];
		$start = date('Y-m-d H:i', $session['start']);
		$end = date('Y-m-d H:i', $session['end']);
		$location_chain = unserialize(urldecode($session['location']));
		$include = !!@$session['include'];

		$post_meta = array(
			'ID' => $id,
			'post_title' => $title,
			'post_content' => $description,
			'post_modified' => date('Y-m-d H:i:s'),
			'post_modified_gmt' => date('Y-m-d H:i:s'),
			'post_type' => 'session',
			'post_status' => 'publish',
		);

		if ($include) {
			if ($pid = wp_insert_post($post_meta)) {
				if ($id) {
					$updated ++;
				} else {
					$created ++;
				}

				foreach ((array) $meta as $key => $value) {
					update_post_meta($pid, $key, $value);
				}
				update_post_meta($pid, 'start', $start);
				update_post_meta($pid, 'end', $end);
				update_post_meta($pid, 'uid', $uid);

				$parent = null;
				while (count($location_chain)) {
					$location = array_pop($location_chain);
					if (intval($location)) {
						$location = get_term($location, 'location');
					} else {
						$location = (object) wp_insert_term(
							$location,
							'location',
							array('parent' => $parent)
						);
					}
					// echo '<pre>'.print_r($location,1).'</pre>';
					$parent = @$location->term_id;
				}

				if (isset($location) && $location) {
					wp_set_post_terms($pid, @$location->term_id, 'location');
				}
			} else {
				$failed ++;
			}
		}
	}

	$message = array();
	if ($created) {
		$message[] = sprintf('%d session%s created.',
			$created,
			$created == 1 ? '' : 's'
		);
	}
	if ($updated) {
		$message[] = sprintf('%d session%s updated.',
			$updated,
			$updated == 1 ? '' : 's'
		);
	}
	if ($failed) {
		$message[] = sprintf('Failed to create %d session%s.',
			$failed,
			$failed == 1 ? '' : 's'
		);
	}
	$message = implode(' ', $message);
}

$source = @$_REQUEST['source'];

?>
<style>
	#import-form {
		padding-top: 10px;
	}
	#import-form input {
		font-size: 1.7em !important;
		padding: 6px 12px;
	}
	#import-form input[type="text"] {
		padding: 3px 8px;
		line-height: 100%;
		width: 50%;
		outline: 0;
	}
	#import-list {
		margin: 40px 0 0;
		padding: 0;
		list-style: none;
		max-width: 50em;
	}
	#import-list > li {
		padding: 10px 0 0 25px;
	}
	#import-list h3 {
		padding: 0 0 5px 25px;
		margin: 0 0 15px -25px;
		background: 0 0 no-repeat;
		border-bottom: solid 1px #CCC;
	}
	#import-list .new h3 {
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJlSURBVDjLpZNbaM9hGMc/v8NsTqF2yMhZKIexmpFyRZIiblwouZDauLYoTVEryg2uXIoIOUU5zJkh4e9QDmtJbWxrbUzz/72/9/m6+P3HLslbT9/3ufm+n/f7Pm8gif9ZMcDxe717JLZ62UQzwxukZnhveBOptyHl8anwZk/3b5pZEwOYtGNDzejSfzm58dTH+b8JvFkpwMizdSCBT8E8OJftkzy4BPIOnONHQzPO+eIhBoM5CCrLwNKslBZM8uDykCbwtgMAl/o/GXhvBYMA2rtAlpGYZSR+UIGKCgCSggGSOHy1Q/0DTifufZUknbr/RZJ0+mHWn3mU9edbMu3qG9DmQ08lKSNw3jCJOIKzjzqJopBzLZ3EEVx40smDr/u4e96QGUXPGpkzYQSJywjCwSsIiKOADUvKiUNYX1tOUQhra8oJg4hZ02cQhhGrqyuyp03tTwbOGzKIQ7j8rIsn3Qd4fEVIIn6+kzAMaH35Fn37wbZD68gnCUl+EbAkI3CpIYmiCNZUlwEwbfIUgiBg1cIyJqbzGFPiWbl8GXUb66mqnkrJ2IvUbq88GEI2dQBRGHDjZTcAbZ8+ERDQnOvm+fszVM1egA89C8avwAeO2nlLAeqRxK7j79TzPa/mXJck6darTG8XdM3uhbry+piGrou5I1pcP17h7wwk5k4aRUfPANMrhtP2pZ8J44bx7nMfff29vGl/SNP1LQA0XdtCa2cO4GdhkPRg78kPVYm3kS71uNTjU8N5I/UpxSWracndZOn8ZVx6dZRhQcz9F3cAjgR/+51rt1c2AXXAaOA7cLTlcHvDL6y6kIpO9lqsAAAAAElFTkSuQmCC);
	}
	#import-list .exists h3 {
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAKbSURBVDjLpZNLbIxRGIaf/9Lfpa1rZ0hNtYpEVRBiNkUiEZFoKjSxQmLBglizIQiJILEgWHQlgkpLGg1BU6E3imorLkElLmOmM1XV6mXm/8/5LH5GI7EQJ/lyztm85/ne872GiPA/ywa40NC3X4TtSnRIa43S4GmNUhqlBU/pUaVQnqC0fnRk89ywDaBFdpeHs3P+5eUDlW8XpgmU1jkAmdU7QQSUB1qB6/rnVBLcFCRdcF0G99bjumrMKIFfPgjkBkB7fon3UyQJbhK8FLyIAuB66rcHSumfAgZ8ToBon0Rrn0T5+6AzyKd5eVi3j7HDuUfnmchWRITTN6PyfdiViw3dIiJS2RgTEZErzf69qiUmg59rJFq/R/o6a0UGIvK1s0paTqyN2QCu0mgRbAuqW+JYlsnVB3FsC2pa4yQSuxjKLmbK3BJ6u17iGCmyJ0wna+rMiekWBLAtgw3hADWtCdaHg9Q+jrO64BVDmUVMKlxDMnoZZ7zB+/ZX9A+ZGMmeRWkC0WCbUPskQWvPcR7eEEI6xvDYPCbNKWMkcg7T8cjIysfxPnC+dwun95a/Nn1HNSJChgWlSwMAFNtDbJw+g4lzyhiJnMXMcEn1F9B9vwNZto/vTggAE/ypA7BMg7qOHnL6PrBYxhEsXkcqVoHlCMn+fLob2mDVSQIFC9M/ZwN4nud7YMKMgTpyJ8/GkyDRZ6eYHHQY6c2jp/Ul5qqTBHLz0VqwTH4TuMpvYcHMLDoaK5i/fAdjPjbyuqaJ9lu9PL/zFKPkEMFQoU9qGWgtetQgSdPBS28Wp5TOzO1KkHpey6xFK/iW+EJX2xvuhg7z5do34GE6C30DI9cBjD/jvGnllOElhdPscNEsJCPZPBxPbCs92vnub6H6AWmNdrgLt0FkAAAAAElFTkSuQmCC);
	}
	#import-list .failed h3 {
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJ4SURBVDjLpZNdaM1xGMc//5fDZqTZ2c6Y9mIkmZelll2hlCVF3FDKlZRyQWJXmsKNCzeLcqdE5D0mL/M2s6HlJSlhs2Rv51iYOZ3z+/2ex8X/zHZJfvX0/J6b7+/zfH/P46kq/3NCgNNt3w6ost2pzBYRnIAVwTnBiWKdTAiHs4oTeX5467y6EEBUd22qmxb/l5ebzn1Y/IfAicQBCi7uBFVwFsSBMdE9mwGThYwBYxhtvIcxbvIEgTEfFGYVg9go1OZEMmAyYLPwth8AY924B85JTsCDviSoRCQiEYkby0AiAUA2J4Cq0nyzX3+mjZ5pG1RV1XOPB1RV9fyTqL7QEdVXOqOc/J7Wbceeq6pGBMYJokoYwMWOIYLA51LnEGEAV58N0T54iEdXBBUh1tXEgrIpZE1E4I+1oEAYeGyqLyH0YePyEmI+rK8rwfcC5lfPxfcD1i5LRF9rZdwD4wQVCH243pXkWeooT1sUVSV8sR/f94i13KHh1Utam0+iRaUsKVwH1OcErKCqxAJoqC2mowXmVFTS3fuJhtpi8p6WUTTcxbyte5lctZD069tMb2vlzurYLh+iqQMIfI+7r1IA9PT24uFx73WKzP0TzF2xgbyPD/BObWFK92WqKgpRT3eHANbayAMfVi2KEwb7WFkT5+GbFCtq4rR+/UxeaRWs3TO+A00zCcSrHPdAlZryqfQPp6lO5NMz8JOywkm8+/wdnZHg18sbFFzbSSY9wC9g5EeAC+jLDZK2Hzz7fmnWSYGxDmMdzgrGCdZZ6ks3MrXzFhUz8gmDGCNJS8+gr4oc9/52nds3lzeODvftCJxX4QL9onBizW175DdAmHVGgBeCfwAAAABJRU5ErkJggg==);
	}
	#import-list h3 em {
		font-weight: normal
	}
	#import-list .failed h3 em {
		color: #633;
	}
	#import-list dl {
		background: #FCFCFC;
		border: solid 1px #EEE;
		border-radius: 5px;
		padding: 1em;
	}
	#import-list dt {
		float: left;
		width: 7em;
		text-align: right;
		font-weight: bold;
	}
	#import-list dt::after {
		content: ":";
	}
	#import-list dd {
		margin-left: 8em;
	}
	#import-list input[type="text"],
	#import-list textarea {
		width: 100%;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		margin-top: -4px;
	}
	#import-list img {
		vertical-align: middle;
	}
	#import-list p {
		text-align: right;
	}
	#import-list .failed p {
		text-align: center;
		color: #633;
	}
</style>
<div class="wrap" id="import-page">
	<div id="icon-edit" class="icon32 icon32-posts-session"><br></div>
	<h2>Import Sessions</h2>
	<?php if (isset($message) && !empty($message)): ?><div id="message" class="updated below-h2"><p><?php echo $message; ?></p></div><?php endif; ?>

	<form method="post" action="" id="import-form">
		<input type="text" name="source" value="<?php echo $source; ?>" placeholder="http://example.com/schedule.ics">
		<input type="submit" class="button" value="Import">
	</form>
<?php
	if (!empty($source)) {
		$content = @file_get_contents($source);
		if ($content === false) {
			echo '<p>Unable to load resource: ' . $source . '</p>';
		} else {
			$locations = get_terms('location',array('hide_empty'=>false,));
			// echo '<pre>'.print_r($locations,1).'</pre>';

			$ical = parse($content);
			// echo '<pre>'.print_r($ical,1).'</pre>';
			echo '<form method="post" action="">';
			echo '<ul id="import-list">';
			foreach ($ical->events as $i => $event) {
				$session = get_session_from_uid($event->uid);
				$valid = true;

				if (is_null($session)) {
					$state = 'New';
					$title = $event->summary;
					$import_message = 'Add this session';
				} else {
					$state = 'Exists';
					$import_message = 'Update this session';
					$title = $session->post_title;
				}

				if ($event->is_date) {
					$state = 'Failed';
					$valid = false;
				}

				$location_parts = preg_split('/\s*,\s*/', $event->location);
				$location_chain = array();
				$location_found = false;

				foreach ($location_parts as $location_part) {
					$location_slug = sanitize_title($location_part);
					foreach ($locations as $location) {
						if (stripos($location->name, $location_part) !== false
								|| stripos($location_part, $location->name) !== false
								|| $location->slug === $location_slug) {
							if (!count($location_chain)) $location_found = true;
							$location_chain[] = $location->term_id;
							break 2;
						}
					}
					$location_chain[] = $location_part;
				}

				// echo '<pre>'.print_r(array($location_parts, $location_chain),1).'</pre>';
				$link_host = parse_url($event->url, PHP_URL_HOST);

				echo '<li class="' . strtolower($state) . '">';
				echo '<h3>' . $title . ' <em>(' . $state . ')</em></h3>';
				if ($valid) {
					echo '<dl>';
					echo '	<dt>Title</dt>';
					echo '		<dd><input type="text" name="sessions['.$i.'][title]" value="' . $event->summary . '"></dd>';
					echo '	<dt>Description</dt>';
					echo '		<dd><textarea name="sessions['.$i.'][description]" rows="6">' . $event->description . '</textarea></dd>';
					echo '	<dt>Location</dt>';
					echo '		<dd>' . $event->location . '&nbsp;';
					if ($location_found) echo '<img src="data:image/gif;base64,R0lGODlhEAAQAMQfAHbDR0twLrPdlpHQaZGYjPn8983muYvSWIPNU3G/RNTtxTZZGn6TbVKCLJPmV4nkTOz35ajld8LmqV16Rn7VR4DLT4zlUExgPHGLXIvOYKTad4DZSGm4PlJnQv///////yH5BAEAAB8ALAAAAAAQABAAAAWE4Cd+Xml6Y0pORMswE6p6mGFIUR41skgbh+ABgag4eL4OkFisDAwZxwLl6QiGzQHEM3AEqFZmJbNVICxfkjWjgAwUHkECYJmqBQNJYSuf18ECAABafQkJD3ZVGhyChoYcHH8+FwcJkJccD2kjHpQPFKAbmh4FMxcNqKhTPSknJiqwsSMhADs=" alt="Location Found">';
					echo '</dd>';
					echo '	<dt>Start</dt>';
					echo '		<dd>' . date('H:i (D. jS)', $event->dtstart) . '</dd>';
					echo '	<dt>End</dt>';
					echo '		<dd>' . date('H:i (D. jS)', $event->dtend) . '</dd>';
					echo '</dl>';

					if (!is_null($session)) {
						echo '<input type="hidden" name="sessions['.$i.'][id]" value="' . $session->ID . '">';
					}
					echo '<input type="hidden" name="sessions['.$i.'][start]" value="' . $event->dtstart . '">';
					echo '<input type="hidden" name="sessions['.$i.'][end]" value="' . $event->dtend . '">';
					echo '<input type="hidden" name="sessions['.$i.'][location]" value="' . urlencode(serialize($location_chain)) . '">';
					echo '<input type="hidden" name="sessions['.$i.'][meta]['.$link_host.']" value="' . $event->url . '">';
					echo '<input type="hidden" name="sessions['.$i.'][uid]" value="' . $event->uid . '">';

					$default_import = ($state == 'New') ? ' checked="checked"' : '';
					echo '<p><label><input type="checkbox" name="sessions['.$i.'][include]" value="1"'.$default_import.'> ' . $import_message . '</label></p>';
				} else {
					echo '<p>Could not import this session.</p>';
				}
				echo '</li>';
				// echo '<pre>'.print_r($session,1).'</pre>';
			}
			echo '</ul>';

			echo '<input type="hidden" name="import" value="1">';
			wp_nonce_field('import');
			echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Update"></p>';
		}
	}
?>
</div>
