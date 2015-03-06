<?php
/**
 * Plugin database schema
 * WARNING: 
 * 	dbDelta() doesn't like empty lines in schema string, so don't put them there;
 *  WPDB doesn't like NULL values so better not to have them in the tables;
 */

/**
 * The database character collate.
 * @var string
 * @global string
 * @name $charset_collate
 */
$charset_collate = '';

// Declare these as global in case schema.php is included from a function.
global $wpdb, $plugin_queries;

if ( ! empty($wpdb->charset))
	$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
if ( ! empty($wpdb->collate))
	$charset_collate .= " COLLATE $wpdb->collate";
	
$table_prefix = PMXI_Plugin::getInstance()->getTablePrefix();

$plugin_queries = <<<SCHEMA
CREATE TABLE {$table_prefix}templates (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	options TEXT,
	scheduled VARCHAR(64) NOT NULL DEFAULT '',
	name VARCHAR(200) NOT NULL DEFAULT '',
	title TEXT,
	content LONGTEXT,
	is_keep_linebreaks TINYINT(1) NOT NULL DEFAULT 0,
	is_leave_html TINYINT(1) NOT NULL DEFAULT 0,
	fix_characters TINYINT(1) NOT NULL DEFAULT 0,
	meta LONGTEXT,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$table_prefix}imports (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	parent_import_id BIGINT(20) NOT NULL DEFAULT 0,
	name VARCHAR(255) NOT NULL DEFAULT '',
	friendly_name VARCHAR(255) NOT NULL DEFAULT '',
	type VARCHAR(32) NOT NULL DEFAULT '',
	feed_type ENUM('xml','csv','zip','gz','') NOT NULL DEFAULT '',	
	path TEXT,	
	xpath TEXT,
	template LONGTEXT,
	options TEXT,
	scheduled VARCHAR(64) NOT NULL DEFAULT '',
	registered_on DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',	
	large_import ENUM('Yes','No') NOT NULL DEFAULT 'No',	  	
  	root_element VARCHAR(255) DEFAULT '',
  	processing BOOL NOT NULL DEFAULT 0,
  	triggered BOOL NOT NULL DEFAULT 0,
  	queue_chunk_number BIGINT(20) NOT NULL DEFAULT 0,
  	current_post_ids LONGBLOB,  	
  	first_import TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,	
  	count BIGINT(20) NOT NULL DEFAULT 0,
  	imported BIGINT(20) NOT NULL DEFAULT 0,
  	created BIGINT(20) NOT NULL DEFAULT 0,
  	updated BIGINT(20) NOT NULL DEFAULT 0,
  	skipped BIGINT(20) NOT NULL DEFAULT 0,
	PRIMARY KEY  (id)
) $charset_collate;
CREATE TABLE {$table_prefix}posts (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	post_id BIGINT(20) UNSIGNED NOT NULL,
	import_id BIGINT(20) UNSIGNED NOT NULL,
	unique_key TEXT,
	product_key TEXT,
	PRIMARY KEY  (id)	
) $charset_collate;
CREATE TABLE {$table_prefix}files (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	import_id BIGINT(20) UNSIGNED NOT NULL,
	name VARCHAR(255) NOT NULL DEFAULT '',
	path TEXT,
	registered_on DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY  (id)
) $charset_collate;
SCHEMA;
