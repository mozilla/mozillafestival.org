<?php
/*
Plugin Name: Search &amp; Replace
Text Domain: searchandreplace
Domain Path: /languages
Plugin URI:  http://bueltge.de/wp-suchen-und-ersetzen-de-plugin/114/
Description: A simple search for find strings in your database and replace the string. 
Author:      Frank B&uuml;ltge
Author URI:  http://bueltge.de/
Version:     2.6.4
License:     GPL
Donate URI:  http://bueltge.de/wunschliste/
*/

/**
License:
==============================================================================
Copyright 2009 Frank Bueltge  (email : frank@bueltge.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Requirements:
==============================================================================
This plugin requires WordPress >= 2.7 and was tested with PHP Interpreter >= 5.3 and WP 3.3
*/

//avoid direct calls to this file, because now WP core and framework has been used
if ( ! function_exists('add_action') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

// Pre-2.6 compatibility
if ( ! defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( ! defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	
// plugin definitions
define( 'FB_SAR_BASENAME', plugin_basename(__FILE__) );
define( 'FB_SAR_BASEDIR', dirname( plugin_basename(__FILE__) ) );
define( 'FB_SAR_TEXTDOMAIN', 'searchandreplace' );

function searchandreplace_textdomain() {

	load_plugin_textdomain( FB_SAR_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__) ) . '/languages');
}


/**
 * @version WP 2.8
 * Add action link(s) to plugins page
 *
 * @param $links, $file
 * @return $links
 */
function searchandreplace_on_load() {
	
	add_filter( 'plugin_action_links_' . FB_SAR_BASENAME, 'searchandreplace_filter_plugin_meta', 10, 2 );  
}

function searchandreplace_filter_plugin_meta($links, $file) {
	
	if ( empty($links) )
		return;
	
	/* create link */
	if ( $file == FB_SAR_BASENAME ) {
		array_unshift(
			$links,
			sprintf( '<a href="tools.php?page=%s">%s</a>', FB_SAR_BASENAME, __('Settings') )
		);
	}
	
	return $links;
}


/**
 * settings in plugin-admin-page
 */
function searchandreplace_add_settings_page() {

	if ( ! current_user_can('manage_options') )
		return;
	
	$pagehook = add_management_page( __( 'Search &amp; Replace', FB_SAR_TEXTDOMAIN ), __( 'Search &amp; Replace', FB_SAR_TEXTDOMAIN ), 'manage_options', FB_SAR_BASENAME, 'searchandreplace_page', '' );
	add_action( 'load-plugins.php', 'searchandreplace_on_load' );
}

/**
 * init on wordpress
 */
function searchandreplace_init() {
	
	add_action('admin_init', 'searchandreplace_textdomain');
	add_action('admin_menu', 'searchandreplace_add_settings_page');
	add_action('admin_print_scripts', 'searchandreplace_add_js_head' );
}
add_action( 'plugins_loaded', 'searchandreplace_init' );


/* this does the important stuff! */
function searchandreplace_doit(
	$search_text,
	$replace_text,
	$sall                 = TRUE,
	$content              = TRUE,
	$guid                 = TRUE,
	$id                   = TRUE,
	$title                = TRUE,
	$excerpt              = TRUE,
	$meta_value           = TRUE,
	$comment_content      = TRUE,
	$comment_author       = TRUE,
	$comment_author_email = TRUE,
	$comment_author_url   = TRUE,
	$comment_count        = TRUE,
	$cat_description      = TRUE,
	$tag                  = TRUE,
	$user_id              = TRUE,
	$user_login           = TRUE,
	$signups              = TRUE
	) {
	global $wpdb;
	
	$myecho = '';
	// slug string
	$search_slug  = strtolower($search_text);
	$replace_slug = strtolower($replace_text);
	
	if (!$sall && !$content && !$id && !$guid && !$title && !$excerpt && !$meta_value && 
		!$comment_content && !$comment_author && !$comment_author_email && !$comment_author_url && !$comment_count && 
		!$cat_description && !$tag && !$user_id && !$user_login &&
		!$signups ) {
		return '<div class="error"><p><strong>' . __('Nothing (Checkbox) selected to modify!', FB_SAR_TEXTDOMAIN). '</strong></p></div><br class="clear" />';
	}
	
	// search at all
	if ( 'sall' === $sall ) {
		$myecho .= "\n" . '<li>' . __('Looking @ all', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_sall($search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
	}
	
	// search/replace at all
	if ( 'srall' === $sall ) {
		$myecho .= "\n" . '<li>' . __('Looking & Replace @ all', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_sall($search_text, $replace_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
	}
	
	// post content
	if ($content) {
		$myecho .= "\n" . '<li>' . __('Looking @ post content', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('post_content', 'posts', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET post_content = ";
		$query .= "REPLACE(post_content, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}

	// post id
	if ($id) {
		$myecho .= "\n" . __('Looking @ ID', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('ID', 'posts', $search_text);
		$myecho .= searchandreplace_results('post_parent', 'posts', $search_text);
		$myecho .= searchandreplace_results('post_id', 'postmeta', $search_text);
		$myecho .= searchandreplace_results('object_id', 'term_relationships', $search_text);
		$myecho .= searchandreplace_results('comment_post_ID', 'comments', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET ID = ";
		$query .= "REPLACE(ID, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET post_parent = ";
		$query .= "REPLACE(post_parent, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);

		$query  = "UPDATE $wpdb->postmeta ";
		$query .= "SET post_id = ";
		$query .= "REPLACE(post_id, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);

		$query  = "UPDATE $wpdb->term_relationships ";
		$query .= "SET object_id = ";
		$query .= "REPLACE(object_id, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);

		$query  = "UPDATE $wpdb->comments ";
		$query .= "SET comment_post_ID = ";
		$query .= "REPLACE(comment_post_ID, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// post guid
	if ($guid) {
		$myecho .= "\n" . '<li>' . __('Looking @ GUID', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('guid', 'posts', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET guid = ";
		$query .= "REPLACE(guid, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// post title
	if ($title) {
		$myecho .= "\n" . '<li>' . __('Looking @ Titeln', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('post_title', 'posts', $search_text);
		$myecho .= searchandreplace_results('post_name', 'posts', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET post_title = ";
		$query .= "REPLACE(post_title, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET post_name = ";
		$query .= "REPLACE(post_name, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// post excerpt
	if ($excerpt) {
		$myecho .= "\n" . '<li>' . __('Looking @ post excerpts', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('post_excerpt', 'posts', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET post_excerpt = ";
		$query .= "REPLACE(post_excerpt, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// meta_value
	if ($meta_value) {
		$myecho .= "\n" . '<li>' . __('Looking @ Meta Daten', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('meta_value', 'postmeta', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->postmeta ";
		$query .= "SET meta_value = ";
		$query .= "REPLACE(meta_value, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// comment content
	if ($comment_content) {
		$myecho .= "\n" . '<li>' . __('Looking @ modifying comments text', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('comment_content', 'comments', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->comments ";
		$query .= "SET comment_content = ";
		$query .= "REPLACE(comment_content, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// comment_author
	if ($comment_author) {
		$myecho .= "\n" . '<li>' . __('Looking @ modifying comments author', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('comment_author', 'comments', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->comments ";
		$query .= "SET comment_author = ";
		$query .= "REPLACE(comment_author, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// comment_author_email
	if ($comment_author_email) {
		$myecho .= "\n" . '<li>' . __('Looking @ modifying comments author e-mail', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('comment_author_email', 'comments', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->comments ";
		$query .= "SET comment_author_email = ";
		$query .= "REPLACE(comment_author_email, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// comment_author_url
	if ($comment_author_url) {
		$myecho .= "\n" . '<li>' . __('Looking @ modifying comments author URLs', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('comment_author_url', 'comments', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->comments ";
		$query .= "SET comment_author_url = ";
		$query .= "REPLACE(comment_author_url, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}

	// comment_count
	if ($comment_count) {
		$myecho .= "\n" . '<li>' . __('Looking @ Comment-Count', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('comment_count', 'posts', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET comment_count = ";
		$query .= "REPLACE(comment_count, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}

	// category description
	if ($cat_description) {
		$myecho .= "\n" . '<li>' . __('Looking @ category description', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('description', 'term_taxonomy', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->term_taxonomy ";
		$query .= "SET description = ";
		$query .= "REPLACE(description, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	// tags and category
	if ($tag) {
		$myecho .= "\n" . '<li>' . __('Looking @ Tags', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('name', 'terms', $search_text);
		$myecho .= searchandreplace_results('slug', 'terms', $search_slug);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->terms ";
		$query .= "SET name = ";
		$query .= "REPLACE(name, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
		
		$query  = "UPDATE $wpdb->terms ";
		$query .= "SET slug = ";
		$query .= "REPLACE(slug, \"$search_slug\", \"$replace_slug\") ";
		$wpdb->get_results($query);
	}

	// user_id
	if ($user_id) {
		$myecho .= "\n" . '<li>' . __('Looking @ User-ID', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('ID', 'users', $search_text);
		$myecho .= searchandreplace_results('user_id', 'usermeta', $search_slug);
		$myecho .= searchandreplace_results('post_author', 'posts', $search_slug);
		$myecho .= searchandreplace_results('link_owner', 'links', $search_slug);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->users ";
		$query .= "SET ID = ";
		$query .= "REPLACE(ID, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
		
		$query  = "UPDATE $wpdb->usermeta ";
		$query .= "SET user_id = ";
		$query .= "REPLACE(user_id, \"$search_slug\", \"$replace_slug\") ";
		$wpdb->get_results($query);
		
		$query  = "UPDATE $wpdb->posts ";
		$query .= "SET post_author = ";
		$query .= "REPLACE(post_author, \"$search_slug\", \"$replace_slug\") ";
		$wpdb->get_results($query);
		
		$query  = "UPDATE $wpdb->links ";
		$query .= "SET link_owner = ";
		$query .= "REPLACE(link_owner, \"$search_slug\", \"$replace_slug\") ";
		$wpdb->get_results($query);
	}

	// user_login
	if ($user_login) {
		$myecho .= "\n" . '<li>' . __('Looking @ User Login', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('user_login', 'users', $search_text);
		$myecho .= searchandreplace_results('user_nicename', 'users', $search_slug);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->users ";
		$query .= "SET user_login = ";
		$query .= "REPLACE(user_login, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
		
		$query  = "UPDATE $wpdb->users ";
		$query .= "SET user_nicename = ";
		$query .= "REPLACE(user_nicename, \"$search_slug\", \"$replace_slug\") ";
		$wpdb->get_results($query);
	}
	
	// signups on WP multisite
	if ($signups) {
		$myecho .= "\n" . '<li>' . __('Looking @ signups', FB_SAR_TEXTDOMAIN) . ' ...';
		
		$myecho .= "\n" . '<ul>' . "\n";
		$myecho .= searchandreplace_results('user_login', 'signups', $search_text);
		$myecho .= "\n" . '</ul>' . "\n" . '</li>' . "\n";
		
		$query  = "UPDATE $wpdb->signups ";
		$query .= "SET user_login = ";
		$query .= "REPLACE(user_login, \"$search_text\", \"$replace_text\") ";
		$wpdb->get_results($query);
	}
	
	$echo  = '<div class="updated fade">' . "\n" . '<ul>';
	$echo .= $myecho;
	$echo .= "\n" . '</ul>' . "\n" . '</div><br class="clear"/>' . "\n";
	
	return $echo;
}

/**
 * View results
 * @var: $field, $tabel
 */
function searchandreplace_results($field, $table, $search_text) {
	global $wpdb;
	
	$myecho  = '';
	$results = '';

	$myecho .= "\n" . '<li>';
	$results = "SELECT $field FROM " . $wpdb->$table . " WHERE $field LIKE \"%$search_text%\"";
	//$myecho .= $results . '<br />';
	$myecho .= __('... in table', FB_SAR_TEXTDOMAIN) . ' ';
	$myecho .= '<code>' . $table . '</code>,' . ' field <code>' . $field . '</code>: ';
	$results = mysql_query($results);
	$total_results = (int)( @mysql_num_rows($results) );
	
	if (!$results) {
		$myecho .= __('The inquiry could not be implemented:', FB_SAR_TEXTDOMAIN) . ' ' . mysql_error();
	} else {
	
		if ($total_results == 0) {
			$myecho .= ' - <strong>' . $total_results . '</strong> ';
		} else {
			
			while ( $row = mysql_fetch_assoc($results) ) {
				//echo $row[$field] . "\n";
				$myecho .= '|';
			}
			$myecho .= ' - <strong>' . $total_results . '</strong> ';
		}
		$myecho .= __('entries found.', FB_SAR_TEXTDOMAIN);
		$myecho .= '</li>' . "\n";
	}
	return $myecho;
}


function searchandreplace_sall($search_text, $replace_text = FALSE) {
	global $wpdb;
	
	if ( empty($wpdb->dbname) )
		$wpdb->dbname = DB_NAME;
	
	$search_text = mysql_real_escape_string($search_text);
	if ( $replace_text )
		$replace_text = mysql_real_escape_string($replace_text);
	$result_in_tables = 0;
	
	$myecho = '
	<script language="JavaScript">
		var table_id = new Array();
	
		function hide_all() {
			for(i=0;i<table_id.length;i++){
				document.getElementById(table_id[i]).style.display = \'none\';
			}
		}
		
		function show_all() {
			for(i=0;i<table_id.length;i++){
				document.getElementById(table_id[i]).style.display = \'block\';
			}
		}
		
		function toggle(id) {
			if (get_style(id,\'display\') == \'block\') {
				document.getElementById(id).style.display = \'none\';
			} else {
				document.getElementById(id).style.display = \'block\';
			}
		}
		
		function get_style(el,styleProp) {
			var x = document.getElementById(el);
			if (x.currentStyle)
				var y = x.currentStyle[styleProp];
			else if (window.getComputedStyle)
				var y = document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
			return y;
		}
	</script>';
	
	$myecho .= '<p><a href="javascript:hide_all()">Collapse All</a> 
		 <a href="javascript:show_all()">Expand All</a></p>';
	$myecho .= '<p>Results for: <code>' . $search_text . '</code></p>';
	
	$sql = 'show tables';
	$res = mysql_query($sql);
	$tables = searchandreplace_fetch_array($res);
	
	for ($i=0; $i < sizeof($tables); $i++) {
		//@abstract querry bliding of each table
		$sql = 'select count(*) from ' . $tables[$i]['Tables_in_' . $wpdb->dbname];
		$res = mysql_query($sql);
		
		if (mysql_num_rows($res) > 0) {
			//@abstract taking the table data type information
			$sql = 'desc ' . $tables[$i]['Tables_in_' . $wpdb->dbname]; 
			$res = mysql_query($sql);
			$collum = searchandreplace_fetch_array($res);
			
			$search_sql = 'select * from ' . $tables[$i]['Tables_in_' . $wpdb->dbname] . ' where ';
			// replace string
			if ( $replace_text )
				$replace_sql  = 'UPDATE ' . $tables[$i]['Tables_in_' . $wpdb->dbname] . ' SET ';
			$no_varchar_field = 0;
			
			for ($j=0; $j < sizeof($collum); $j++) {
				if ($no_varchar_field != 0){
					$search_sql  .= 'or ' ;
					if ( $replace_text )
						$replace_sql .= ', ';
				}
				$search_sql .= '`' . $collum[$j]['Field'] . '` like \'%' . $search_text . '%\' ';
				// replace string
				if ( $replace_text ) {
					$replace_sql .= $collum[$j]['Field'] . ' = ';
					$replace_sql .= 'REPLACE(' . $collum[$j]['Field'] . ', "' . $search_text . '", "' . $replace_text . '")';
				}
				$no_varchar_field ++;
				//var_dump($search_sql); 
				//if ( $replace_text )
				//var_dump($replace_sql);
				//echo '<br>';
			}
			
			if ($no_varchar_field > 0) {
				$res = mysql_query($search_sql);
				$search_result = searchandreplace_fetch_array($res);
				if ( sizeof($search_result) ) {
					$result_in_tables ++;
				
					$myecho .= '<p><strong>Table: </strong><code>' . $tables[$i]['Tables_in_'.$wpdb->dbname] . '</code> ... ';
					$myecho .= 'Total Results for <code>"' . $search_text . '"</code>: <strong>'. mysql_affected_rows() . '</strong></p>';
					$myecho .= '<p><a href="javascript:toggle(\'' . $tables[$i]['Tables_in_'.$wpdb->dbname].'_sql'.'\')">SQL</a></p>';
					$myecho .= '<script language="JavaScript">
						table_id.push("' . $tables[$i]['Tables_in_' . $wpdb->dbname] . '_sql");
					</script>';
					$myecho .= '<div id="' . $tables[$i]['Tables_in_' . $wpdb->dbname] . '_sql" style="display:none;"><code>' . $search_sql . '</code></div>';
					$myecho .= '<p><a href="javascript:toggle(\'' . $tables[$i]['Tables_in_' . $wpdb->dbname] . '_wrapper' . '\')">Result</a></p>';
					$myecho .= '<script language="JavaScript">
						table_id.push("' . $tables[$i]['Tables_in_' . $wpdb->dbname] . '_wrapper");
					</script>';
					$myecho .= '<div id="' . $tables[$i]['Tables_in_' . $wpdb->dbname] . '_wrapper" style="display:none;">';
					
					$myecho .= searchandreplace_table_arrange($search_result);
					$myecho .= '</div>';
				}// @endof showing found search  
				
			}
			
			if ( $replace_text )
				$wpdb->get_results($replace_sql);
		}
	}
	
	if ( ! $result_in_tables ) {
		$myecho = '<p style="color:red;">Sorry, <code>'.
			stripslashes_deep( stripslashes_deep( htmlentities2( $search_text ) ) ) . '</code> ' . 
			__( 'is not found in this Database', FB_SAR_TEXTDOMAIN ) . 
			'(<code>' . $wpdb->dbname . '</code>)!</p>';
	}
	
	return $myecho;
}


/**
 * @method   searchandreplace_fetch_array
 * @abstract taking the mySQL $resource id and fetch and return the result array
 * @param    string| MySQL resouser 
 * @return   array  
 */
function searchandreplace_fetch_array($res) {
	$data = array();
	while ($row = mysql_fetch_assoc($res)) {
		$data[] = $row;
	}
	
	return $data;
}

/**
 * @method     searchandreplace_table_arrange
 * @abstract   taking the mySQL the result array and return html Table in a string. showing the search content in a diffrent css class.
 * @param      array 
 * @post_data  search_text
 * @return     string | html table
 */
function searchandreplace_table_arrange($array) {
	
	$table_data = ''; // @abstract	returning table
	$max = 0; // @abstract	max lenth of a row
	$max_i = 0; // @abstract	number of the row which is maximum max lenth of a row
	
	$search_text = $_POST["search_text"];
	
	for ($i=0; $i < sizeof($array); $i++) {
		//@abstract table row 
		$table_data .= '<tr class=' . ( ($i&1) ? '"alternate"' : '""' ) . ' >';
		$j=0;
		
		foreach($array[$i] as $key => $data) {
			$data = preg_replace("|($search_text)|Ui" , "<code style=\"background:#ffc516;padding:0 4px;\"><b>$1</b></code>" , htmlspecialchars($data));
			$table_data .= '<td>' . $data . ' &nbsp;</td>';
			$j++;
		}
		
		if ($max < $j) {
			$max = $j;
			$max_i = $i;
		}
		
		$table_data .= '</tr>' . "\n";
	}
	
	unset($data);
	// @endof html table
	
	//@abstract populating the table head
	
	// @varname $data_a
	//@abstract  taking the highest sized array and printing the key name.
	$data_a = $array[$max_i];
	
	$table_head = '<tr>';
		foreach($data_a as $key => $value) {
			$table_head .= '<th>' . $key.'</th>';
		}
			
	$table_head .= '</tr>' . "\n";
	
	// @abstract printing the table data
	return '<div class="table_bor">
		<table class="widefat">'
		 . '<thead>' . $table_head . '</thead>'
		 . '<tbody>' . $table_data . '</tbody>'
		 . '</table>
		</div>';
}


/**
 * add js to the head that fires thr 'new node' function
 */
function searchandreplace_add_js_head() {
	?>
	<script type="text/javascript">
	/* <![CDATA[ */
	function selectcb(thisobj,var1){
		var o = document.forms[thisobj].elements;
		if(o){
			for (i=0; i<o.length; i++){
				if (o[i].type == 'checkbox'){
					o[i].checked = var1;
				}
			}
		}
	}
	/* ]]> */
	</script>
	<?php
}


function searchandreplace_action() {

	if ( isset($_POST['submitted']) ) {
		check_admin_referer('searchandreplace_nonce');
		$myecho = '';
		if ( empty($_POST['search_text']) ) {
			$myecho .= '<div class="error"><p><strong>&raquo; ' . __('You must specify some text to replace!', FB_SAR_TEXTDOMAIN) . '</strong></p></div><br class="clear">';
		} else {
			$myecho .= '<div class="updated fade">';
			$myecho .= '<p><strong>&raquo; ' . __('Attempting to perform search and replace ...', FB_SAR_TEXTDOMAIN) . '</strong></p>';
			$myecho .= '<p>&raquo; ' . __('Search', FB_SAR_TEXTDOMAIN) . ' <code>' . stripslashes( htmlentities2( $_POST['search_text'] ) ) . '</code>';
			if ( isset($_POST['replace_text']) )
				$myecho .= ' ... ' . __('and replace with', FB_SAR_TEXTDOMAIN) . ' <code>' . stripslashes( htmlentities2( $_POST['replace_text'] ) ) . '</code></p>';
			$myecho .= '</div><br class="clear" />';
			
			if ( ! isset( $_POST['replace_text'] ) )
				$_POST['replace_text'] = NULL;
			
			if ( ! isset( $_POST['sall'] ) )
				$_POST['sall'] = NULL;
			
			$error = searchandreplace_doit(
				$_POST['search_text'],
				$_POST['replace_text'],
				$_POST['sall'],
				isset($_POST['content']),
				isset($_POST['guid']),
				isset($_POST['id']),
				isset($_POST['title']),
				isset($_POST['excerpt']),
				isset($_POST['meta_value']),
				isset($_POST['comment_content']),
				isset($_POST['comment_author']),
				isset($_POST['comment_author_email']),
				isset($_POST['comment_author_url']),
				isset($_POST['comment_count']),
				isset($_POST['cat_description']),
				isset($_POST['tag']),
				isset($_POST['user_id']),
				isset($_POST['user_login']),
				isset($_POST['singups'])
			);
			
			if ($error != '') {
				$myecho .= $error;
			} else {
				$myecho .= '<p>' . __('Completed successfully!', FB_SAR_TEXTDOMAIN) . '</p></div>';
			}
		}

		echo $myecho;
	}
}


function searchandreplace_page() {
	global $wpdb;
	
	if ( ! isset($wpdb) )
		$wpdb = NULL;
?>
	<div class="wrap" id="top">
		<h2><?php _e('Search &amp; Replace', FB_SAR_TEXTDOMAIN); ?></h2>

		<?php
		if ( defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT )
			$capability = 'manage_options';
		else
			$capability = 'edit_plugins';
		
		if ( current_user_can( $capability ) ) {
			searchandreplace_action();
		} else {
			wp_die('<div class="error"><p>' . __('You do not have sufficient permissions to edit plugins for this blog.', FB_SAR_TEXTDOMAIN) . '</p></div>');
		}
		?>

		<div id="poststuff" class="dlm">
			<div class="postbox">
				<h3><?php _e('Information Search &amp; Replace', FB_SAR_TEXTDOMAIN) ?></h3>
				<div class="inside">
					<p><?php _e('This plugin uses an standard SQL query so it modifies your database directly!<br /><strong>Attention: </strong>You <strong>cannot</strong> undo any changes made by this plugin. <strong>It is therefore advisable to backup your database before running this plugin.</strong> No legal claims to the author of this plugin! <strong>Activate</strong> the plugin <strong>only</strong>, if you want to use it!', FB_SAR_TEXTDOMAIN); ?></p>
					<p><?php _e('Text search is case sensitive and has no pattern matching capabilites. This replace function matchs raw text so it can be used to replace HTML tags too.', FB_SAR_TEXTDOMAIN); ?></p>
					<p><?php _e( '<strong>Step One:</strong> Use the follow search for a better information with return the sql-query and tables with the results. The search use alle fields in all tables! After this you have more informations and you can use the replace function.', FB_SAR_TEXTDOMAIN ); ?></p>
					<form name="search" action="" method="post">
						<?php wp_nonce_field('searchandreplace_nonce') ?>
						<table summary="config" class="widefat">
							<tr>
								<th><label for="sall_label"><?php _e('All - only search!', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td><input type='radio' name='sall' value='sall' id='sall_label' checked="checked" />
									<label for="sall_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>*</code> <?php _e('tables:', FB_SAR_TEXTDOMAIN); ?> <code>*</code></label>
								</td>
							</tr>
							<tr>
								<th><?php _e('Search for', FB_SAR_TEXTDOMAIN); ?></th>
								<td><input class="code" type="text" name="search_text" value="" size="80" /></td>
							</tr>
							<tr class="alternate">
								<th><label for="srall_label"><?php _e('All - search/replace!', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td><input type='radio' name='sall' value='srall' id='srall_label' />
									<label for="srall_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>*</code> <?php _e('tables:', FB_SAR_TEXTDOMAIN); ?> <code>*</code></label>
								</td>
							</tr>
							<tr class="alternate">
								<th><?php _e('Replaced with', FB_SAR_TEXTDOMAIN); ?></th>
								<td><input class="code" type="text" name="replace_text" value="" size="80" /></td>
							</tr>
						</table>
						<p class="submit">
							<input class="button" type="submit" value="<?php _e('Go', FB_SAR_TEXTDOMAIN); ?> &raquo;" />
							<input type="hidden" name="submitted" />
						</p>
					</form>
				</div>
			</div>
		</div>

		<div id="poststuff" class="dlm">
			<div class="postbox" >
				<h3><?php _e('Search in', FB_SAR_TEXTDOMAIN) ?></h3>
				<div class="inside">
					
					<form name="replace" action="" method="post">
						<?php wp_nonce_field('searchandreplace_nonce') ?>
						<table summary="config" class="widefat">
							<tr class="alternate">
								<th><label for="content_label"><?php _e('Content', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='content' id='content_label' /></td>
								<td><label for="content_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>post_content</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_posts</code></label></td>
							</tr>
							<tr>
								<th><label for="guid_label"><?php _e('GUID', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='guid' id='guid_label' /></td>
								<td><label for="guid_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>guid</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_posts</code></label></td>
							</tr>
							<tr class="alternate">
								<th><label for="title_label"><?php _e('Titles', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='title' id='title_label' /></td>
								<td><label for="title_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>post_title</code>, <code>post_name</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_posts</code></label></td>
							</tr>
							<tr>
								<th><label for="excerpt_label"><?php _e('Excerpts', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='excerpt' id='excerpt_label' /></td>
								<td><label for="excerpt_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>post_excerpt</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_posts</code></label></td>
							</tr>
							<tr class="alternate">
								<th><label for="meta_value_label"><?php _e('Meta Data', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='meta_value' id='meta_value_label' /></td>
								<td><label for="meta_value_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>meta_value</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_postmeta</code></label></td>
							</tr>
							<tr>
								<th><label for="comment_content_label"><?php _e('Comments content', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='comment_content' id='comment_content_label' /></td>
								<td><label for="comment_content_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>comment_content</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_comments</code></label></td>
							</tr>
							<tr class="alternate">
								<th><label for="comment_author_label"><?php _e('Comments author', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='comment_author' id='comment_author_label' /></td>
								<td><label for="comment_author_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>comment_author</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_comments</code></label></td>
							</tr>
							<tr>
								<th><label for="comment_author_email_label"><?php _e('Comments author e-mail', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='comment_author_email' id='comment_author_email_label' /></td>
								<td><label for="comment_author_email_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>comment_author_email</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_comments</code></label></td>
							</tr>
							<tr class="alternate">
								<th><label for="comment_author_url_label"><?php _e('Comments author URL', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='comment_author_url' id='comment_author_url_label' /></td>
								<td><label for="comment_author_url_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>comment_author_url</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_comments</code></label></td>
							</tr>
							<tr>
								<th><label for="comment_count_label"><?php _e('Comments-Counter', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='comment_count' id='comment_count_label' /></td>
								<td><label for="comment_count_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>comment_count</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_posts</code></label></td>
							</tr>
							<tr class="alternate">
								<th><label for="cat_description_label"><?php _e('Category description', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='cat_description' id='cat_description_label' /></td>
								<td><label for="cat_description_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>description</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_term_taxonomy</code></label></td>
							</tr>
							<tr>
								<th><label for="tag_label"><?php _e('Tags &amp; Categories', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='tag' id='tag_label' /></td>
								<td><label for="tag_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>name</code> <?php _e('and', FB_SAR_TEXTDOMAIN); ?> <code>slug</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_terms</code></label></td>
							</tr>
							<tr class="alternate">
								<th><label for="user_id_label"><?php _e('User-ID', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='user_id' id='user_id_label' /></td>
								<td><label for="user_id_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>ID</code>, <code>user_id</code>, <code>post_author</code> <?php _e('and', FB_SAR_TEXTDOMAIN); ?> <code>link_owner</code><br /><?php _e('table:', FB_SAR_TEXTDOMAIN); ?><code>_users</code>, <code>_usermeta</code>, <code>_posts</code> <?php _e('and', FB_SAR_TEXTDOMAIN); ?> <code>_links</code></label></td>
							</tr>
							<tr>
								<th><label for="user_login_label"><?php _e('User-login', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='user_login' id='user_login_label' /></td>
								<td><label for="user_login_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>user_login</code> <?php _e('and', FB_SAR_TEXTDOMAIN); ?> <code>user_nicename</code> table: <code>_users</code></label></td>
							</tr>
							<?php if ($wpdb && mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . $wpdb->prefix . 'terms'."'") ) == 1) { ?>
							<tr class="alternate">
								<th><label for="id_label"><?php _e('ID', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='id' id='id_label' /></td>
								<td><label for="id_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>ID</code>, <code>post_parent</code>, <code>post_id</code>, <code>object_id</code> <?php _e('and', FB_SAR_TEXTDOMAIN); ?> <code>comments</code><br /><?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_posts</code>, <code>_postmeta</code>, <code>_term_relationships</code> <?php _e('and', FB_SAR_TEXTDOMAIN); ?> <code>_comment_post_ID</code></label></td>
							</tr>
							<?php } ?>
							<?php if ($wpdb && mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . $wpdb->prefix . 'signups'."'") ) == 1) { ?>
							<tr class="alternate">
								<th><label for="signups_label"><?php _e('Signups', FB_SAR_TEXTDOMAIN); ?></label></th>
								<td colspan="2" style="text-align: center;"><input type='checkbox' name='signups' id='signups_label' /></td>
								<td><label for="signups_label"><?php _e('field:', FB_SAR_TEXTDOMAIN); ?> <code>user_login</code> <?php _e('table:', FB_SAR_TEXTDOMAIN); ?> <code>_signups</code></label></td>
							</tr>
							<?php } ?>
							<tr>
								<th>&nbsp;</th>
								<td colspan="2" style="text-align: center;">&nbsp;&nbsp; <a href="javascript:selectcb('replace', true);" title="<?php _e('Checkboxes to assign', FB_SAR_TEXTDOMAIN); ?>"><?php _e('all', FB_SAR_TEXTDOMAIN); ?></a> | <a href="javascript:selectcb('replace', false);" title="<?php _e('Checkboxes to unmask', FB_SAR_TEXTDOMAIN); ?>"><?php _e('none', FB_SAR_TEXTDOMAIN); ?></a></td>
								<td>&nbsp;</td>
							</tr>
						</table>

						<table summary="submit" class="form-table">
							<tr>
								<th><?php _e('Search for', FB_SAR_TEXTDOMAIN); ?></th>
								<td><input class="code" type="text" name="search_text" value="" size="80" /></td>
							</tr>
							<tr>
								<th><?php _e('Replaced with', FB_SAR_TEXTDOMAIN); ?></th>
								<td><input class="code" type="text" name="replace_text" value="" size="80" /></td>
							</tr>
						</table>
						<p class="submit">
							<input class="button" type="submit" value="<?php _e('Go', FB_SAR_TEXTDOMAIN); ?> &raquo;" />
							<input type="hidden" name="submitted" />
						</p>
					</form>

				</div>
			</div>
		</div>

		<div id="poststuff" class="dlm">
			<div class="postbox" >
				<h3><?php _e('Information on the plugin', FB_SAR_TEXTDOMAIN) ?></h3>
				<div class="inside">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float: right;">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="RHWH8VG798CSC">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
					</form>
					
					<p><?php _e('&quot;Search and Replace&quot; originalplugin (en) created by <a href="http://thedeadone.net/">Mark Cunningham</a> and provided (comments) by durch <a href="http://www.gonahkar.com">Gonahkar</a>.<br />&quot;Search &amp; Replace&quot;, current version provided by <a href="http://bueltge.de">Frank Bueltge</a>.', FB_SAR_TEXTDOMAIN); ?></p>
					<p><?php _e('Further information: Visit the <a href="http://bueltge.de/wp-suchen-und-ersetzen-de-plugin/114/">plugin homepage</a> for further information or to grab the latest version of this plugin.', FB_SAR_TEXTDOMAIN); ?><br />&copy; Copyright 2006 - <?php echo date("Y"); ?> <a href="http://bueltge.de">Frank B&uuml;ltge</a> | <?php _e('You want to thank me? Visit my <a href="http://bueltge.de/wunschliste">wishlist</a> or use the donate button.', FB_SAR_TEXTDOMAIN); ?></p>
				</div>
			</div>
		</div>

</div>
<?php } ?>
