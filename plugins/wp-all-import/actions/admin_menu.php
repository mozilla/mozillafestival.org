<?php
/**
 * Register plugin specific admin menu
 */

function pmxi_admin_menu() {
	global $menu, $submenu;
	
	if (current_user_can('manage_options')) { // admin management options
		
		add_menu_page(__('WP All Import', 'pmxi_plugin'), __('All Import', 'pmxi_plugin'), 'manage_options', 'pmxi-admin-home', array(PMXI_Plugin::getInstance(), 'adminDispatcher'), PMXI_Plugin::ROOT_URL . '/static/img/xmlicon.png');
		// workaround to rename 1st option to `Home`
		$submenu['pmxi-admin-home'] = array();		
		add_submenu_page('pmxi-admin-home', __('Import XML', 'pmxi_plugin') . ' &lsaquo; ' . __('WP All Import', 'pmxi_plugin'), __('New Import', 'pmxi_plugin'), 'manage_options', 'pmxi-admin-import', array(PMXI_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmxi-admin-home', __('Manage Previous Imports', 'pmxi_plugin') . ' &lsaquo; ' . __('WP All Import', 'pmxi_plugin'), __('Manage Imports', 'pmxi_plugin'), 'manage_options', 'pmxi-admin-manage', array(PMXI_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmxi-admin-home', __('Settings', 'pmxi_plugin') . ' &lsaquo; ' . __('WP All Import', 'pmxi_plugin'), __('Settings', 'pmxi_plugin'), 'manage_options', 'pmxi-admin-settings', array(PMXI_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmxi-admin-home', __('Support', 'pmxi_plugin') . ' &lsaquo; ' . __('WP All Import', 'pmxi_plugin'), __('Support', 'pmxi_plugin'), 'manage_options', 'pmxi-admin-help', array(PMXI_Plugin::getInstance(), 'adminDispatcher'));
		//add_submenu_page('pmxi-admin-home', __('Scheduled Imports', 'pmxi_plugin') . ' &lsaquo; ' . __('WP All Import', 'pmxi_plugin'), __('Scheduled Imports', 'pmxi_plugin'), 'manage_options', 'pmxi-admin-cron', array(PMXI_Plugin::getInstance(), 'adminDispatcher'));
		
	}	
}

