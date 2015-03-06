<?php
/**
 * WordPress session managment.
 *
 * Standardizes WordPress session data using database-backed options for storage.
 * for storing user session information.
 *
 * @package WordPress
 * @subpackage Session
 * @since   3.7.0
 */

/**
 * WordPress Session class for managing user session data.
 *
 * @package WordPress
 * @since   3.7.0
 */
final class PMXI_Session extends PMXI_ArrayAccess implements Iterator, Countable {
	/**
	 * ID of the current session.
	 *
	 * @var string
	 */
	protected $session_id;

	/**
	 * Unix timestamp when session expires.
	 *
	 * @var int
	 */
	protected $expires;

	/**
	 * Unix timestamp indicating when the expiration time needs to be reset.
	 *
	 * @var int
	 */
	protected $exp_variant;

	/**
	 * Singleton instance.
	 *
	 * @var bool|WP_Session
	 */
	private static $instance = false;

	public $data = array();

	public $session_mode = '';
	/**
	 * Retrieve the current session instance.
	 *
	 * @param bool $session_id Session ID from which to populate data.
	 *
	 * @return bool|WP_Session
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	/**
	 * Default constructor.
	 * Will rebuild the session collection from the given session ID if it exists. Otherwise, will
	 * create a new session with that ID.
	 *
	 * @param $session_id
	 * @uses apply_filters Calls `wp_session_expiration` to determine how long until sessions expire.
	 */
	protected function __construct() {
		
		$this->session_mode = PMXI_Plugin::getInstance()->getOption('session_mode');
		
		if ($this->session_mode != 'default'){		
		
			if ( isset( $_COOKIE[PMXI_SESSION_COOKIE] ) ) {			

				$cookie = stripslashes( $_COOKIE[PMXI_SESSION_COOKIE] );
				$cookie_crumbs = explode( '||', $cookie );

				$this->session_id = (!empty($cookie_crumbs[0])) ? $cookie_crumbs[0] : $this->generate_id();
				$this->expires = $cookie_crumbs[1];
				$this->exp_variant = $cookie_crumbs[2];
				
				// Update the session expiration if we're past the variant time
				if ( time() > $this->exp_variant ) {
					$this->set_expiration();				
					if ($this->session_mode == 'database'){
						update_option( "_pmxi_session_expires_{$this->session_id}", $this->expires );				
					}
					elseif ($this->session_mode == 'files'){
						@file_put_contents(PMXI_ROOT_DIR . "/sessions/_pmxi_session_expires_{$this->session_id}.txt", $this->expires);				
					}
				}
				
			} else {
				$this->session_id = $this->generate_id();
				$this->set_expiration();
			}
		}								
		else{
			try{
				$path = @session_save_path(); 
				if ( ! @is_dir($path) or ! @is_writable($path)){
					@ini_set("session.save_handler", "files");
					@session_save_path(sys_get_temp_dir());	
				}
			} catch (XmlImportException $e) {
				
			}
			
			// enable sessions
			if ( ! session_id()) @session_start();
		}		

		$this->read_data();

		$this->set_cookie();
	}

	/**
	 * Set both the expiration time and the expiration variant.
	 *
	 * If the current time is below the variant, we don't update the session's expiration time. If it's
	 * greater than the variant, then we update the expiration time in the database.  This prevents
	 * writing to the database on every page load for active sessions and only updates the expiration
	 * time if we're nearing when the session actually expires.
	 *
	 * By default, the expiration time is set to 30 minutes.
	 * By default, the expiration variant is set to 24 minutes.
	 *
	 * As a result, the session expiration time - at a maximum - will only be written to the database once
	 * every 24 minutes.  After 30 minutes, the session will have been expired. No cookie will be sent by
	 * the browser, and the old session will be queued for deletion by the garbage collector.
	 *
	 * @uses apply_filters Calls `wp_session_expiration_variant` to get the max update window for session data.
	 * @uses apply_filters Calls `wp_session_expiration` to get the standard expiration time for sessions.
	 */
	protected function set_expiration() {
		$this->exp_variant = time() + (int) apply_filters( 'wp_session_expiration_variant', 24 * 60 );
		$this->expires = time() + (int) apply_filters( 'wp_session_expiration', 30 * 60 );
	}

	/**
	 * Set the session cookie
	 */
	protected function set_cookie() {
		@setcookie( PMXI_SESSION_COOKIE, $this->session_id . '||' . $this->expires . '||' . $this->exp_variant , $this->expires, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * Generate a cryptographically strong unique ID for the session token.
	 *
	 * @return string
	 */
	protected function generate_id() {
		require_once( ABSPATH . 'wp-includes/class-phpass.php');
		$hasher = new PasswordHash( 8, false );

		return md5( $hasher->get_random_bytes( 32 ) );
	}

	/**
	 * Read data from a transient for the current session.
	 *
	 * Automatically resets the expiration time for the session transient to some time in the future.
	 *
	 * @return array
	 */
	protected function read_data() {
		if ($this->session_mode == 'database'){
			$this->container = get_option( "_pmxi_session_{$this->session_id}", array() );
		}
		elseif ($this->session_mode == 'files'){			
			$container = @file_get_contents(PMXI_ROOT_DIR . "/sessions/_pmxi_session_{$this->session_id}.txt");
			$this->container = unserialize( (!empty($container)) ? $container : '' );			
		}
		else{			
			$this['pmxi_import'] = ( ! empty($_SESSION['pmxi_import']) ) ? $_SESSION['pmxi_import'] : array();			
		}
		
		$this->data = $this->toArray();		

		return $this->container;
	}

	/**
	 * Write the data from the current session to the data storage system.
	 */
	public function write_data() {

		$option_key = "_pmxi_session_{$this->session_id}";
		
		$this->data = $this->toArray();

		// Only write the collection to the DB if it's changed.
		if ($this->session_mode == "database"){
			if ( false === get_option( $option_key ) ) {
				add_option( "_pmxi_session_{$this->session_id}", $this->container, '', 'no' );
				add_option( "_pmxi_session_expires_{$this->session_id}", $this->expires, '', 'no' );
			} else {								
				delete_option("_pmxi_session_{$this->session_id}");
				add_option( "_pmxi_session_{$this->session_id}", $this->container, '', 'no' );
			}
		}
		elseif ($this->session_mode == 'files'){			
			if ( @file_exists( PMXI_ROOT_DIR . "/sessions/" . $option_key . ".txt") ){									
				@file_put_contents( PMXI_ROOT_DIR . "/sessions/" . $option_key . ".txt", (string) serialize($this->container) );				
			}
			else{
				@file_put_contents( PMXI_ROOT_DIR . "/sessions/" . $option_key . ".txt", (string) serialize($this->container) );
				@file_put_contents( PMXI_ROOT_DIR . "/sessions/_pmxi_session_expires_{$this->session_id}.txt", $this->expires );
			}
		}
		else{			
			$session = $this->toArray(); $_SESSION['pmxi_import'] = (!empty($session['pmxi_import'])) ? $session['pmxi_import'] : array();
		}	

	}

	/**
	 * Output the current container contents as a JSON-encoded string.
	 *
	 * @return string
	 */
	public function json_out() {
		return json_encode( $this->container );
	}

	/**
	 * Decodes a JSON string and, if the object is an array, overwrites the session container with its contents.
	 *
	 * @param string $data
	 *
	 * @return bool
	 */
	public function json_in( $data ) {
		$array = json_decode( $data );

		if ( is_array( $array ) ) {
			$this->container = $array;
			return true;
		}

		return false;
	}

	/**
	 * Regenerate the current session's ID.
	 *
	 * @param bool $delete_old Flag whether or not to delete the old session data from the server.
	 */
	public function regenerate_id( $delete_old = false ) {
		if ( $delete_old ) {
			if ($this->session_mode == "database"){
				delete_option( "_pmxi_session_{$this->session_id}" );
			}
			elseif ($this->session_mode == 'files'){
				@unlink( PMXI_ROOT_DIR . "/sessions/_pmxi_session_{$this->session_id}.txt");
			}
		}

		$this->session_id = $this->generate_id();

		$this->set_cookie();
	}

	/**
	 * Check if a session has been initialized.
	 *
	 * @return bool
	 */
	public function session_started() {
		return !!self::$instance;
	}

	/**
	 * Return the read-only cache expiration value.
	 *
	 * @return int
	 */
	public function cache_expiration() {
		return $this->expires;
	}

	/**
	 * Flushes all session variables.
	 */
	public function reset() {
		$this->container = array();
		if ($this->session_mode == "default") unset($_SESSION['pmxi_import']);
	}

	/*****************************************************************/
	/*                     Iterator Implementation                   */
	/*****************************************************************/

	/**
	 * Current position of the array.
	 *
	 * @link http://php.net/manual/en/iterator.current.php
	 *
	 * @return mixed
	 */
	public function current() {
		return current( $this->container );
	}

	/**
	 * Key of the current element.
	 *
	 * @link http://php.net/manual/en/iterator.key.php
	 *
	 * @return mixed
	 */
	public function key() {
		return key( $this->container );
	}

	/**
	 * Move the internal point of the container array to the next item
	 *
	 * @link http://php.net/manual/en/iterator.next.php
	 *
	 * @return void
	 */
	public function next() {
		next( $this->container );
	}

	/**
	 * Rewind the internal point of the container array.
	 *
	 * @link http://php.net/manual/en/iterator.rewind.php
	 *
	 * @return void
	 */
	public function rewind() {
		reset( $this->container );
	}

	/**
	 * Is the current key valid?
	 *
	 * @link http://php.net/manual/en/iterator.rewind.php
	 *
	 * @return bool
	 */
	public function valid() {
		return $this->offsetExists( $this->key() );
	}

	/*****************************************************************/
	/*                    Countable Implementation                   */
	/*****************************************************************/

	/**
	 * Get the count of elements in the container array.
	 *
	 * @link http://php.net/manual/en/countable.count.php
	 *
	 * @return int
	 */
	public function count() {
		return count( $this->container );
	}
}
