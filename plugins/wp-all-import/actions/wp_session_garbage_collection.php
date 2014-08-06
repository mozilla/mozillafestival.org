<?php 

function pmxi_wp_session_garbage_collection() {
	global $wpdb;	

	if ( defined( 'WP_SETUP_CONFIG' ) ) {
		return;
	}

	$session_mode = PMXI_Plugin::getInstance()->getOption('session_mode');

	if ( ! defined( 'WP_INSTALLING' ) ) {
		if ($session_mode == 'database'){
			$expiration_keys = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '_pmxi_session_expires_%'" );

			$now = time();
			$expired_sessions = array();

			foreach( $expiration_keys as $expiration ) {
				// If the session has expired
				if ( $now > intval( $expiration->option_value ) ) {
					// Get the session ID by parsing the option_name
					$session_id = str_replace("_pmxi_session_expires_", "", $expiration->option_name);

					$expired_sessions[] = $expiration->option_name;
					$expired_sessions[] = "_pmxi_session_$session_id";
				}
			}

			// Delete all expired sessions in a single query
			if ( ! empty( $expired_sessions ) ) {
				$option_names = implode( "','", $expired_sessions );
				$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name IN ('$option_names')" );
			}
		}
		elseif ($session_mode == 'files'){
			$session_files = scandir( PMXI_ROOT_DIR . '/sessions');
			
			if (!empty($session_files)){
				$now = time();
				$expired_sessions = array();
				foreach ($session_files as $key => $file) {
					if ( strpos($file, "_pmxi_session_expires_") !== false){
						$expiration_value = @file_get_contents( PMXI_ROOT_DIR . "/sessions/" . $file );

						if ($now > intval($expiration_value)){
							$session_id = str_replace("_pmxi_session_expires_", "", $file);
							$expired_sessions[] = $file;
							$expired_sessions[] = "_pmxi_session_$session_id";
						}
					}
				}
				// Delete all expired sessions in a single query
				if ( ! empty( $expired_sessions ) ) {
					foreach ($expired_sessions as $key => $file) {
						@unlink( PMXI_ROOT_DIR . "/sessions/" . $file );
					}					
				}
			}
		}
	}

	// Allow other plugins to hook in to the garbage collection process.
	do_action( 'pmxi_session_cleanup' );
}