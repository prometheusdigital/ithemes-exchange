<?php
/**
 * This file contains the session class
 *
 * @since 0.3.3
 * @package IT_Exchange
*/

/**
 * The IT_Exchange_Session class holds cart and purchasing details
 *
 * @since 0.3.3
*/
class IT_Exchange_Session {

	/**
	 * @param string $_token  session token
	 * @since 0.3.3
	*/
	private $_token;

	/**
	 * @param array $_products an array of items currently in the user's shopping cart
	 * @since 0.3.3
	*/
	private $_products;

	/**
	 * @param array $_cart_data  an array of any additional data needed by the cart
	 * @since 0.3.3
	*/
	private $_session_data;
	
	function IT_Exchange_Session() {
		$this->set_session_token();
		$this->init_session();
		$this->register_hooks();
	}

	/**
	 * Inits the session
	 *
	 * Starts a new one or loads the current one into this object
	 *
	 * @since 0.3.3
	*/
	function init_session() {
		if ( '' == session_id() )
			$this->start_php_session();

		if ( empty( $_SESSION['it_exchange']['_session'] ) || $this->_token !== $_SESSION['it_exchange']['_session'] )
			$this->regenerate_session_id();

		$this->load_products();
		$this->load_data();
	}

	/**
	 * Starts a PHP session with some basic safety mechanisms.
	 *
	 * @since 0.3.3
	 * @return void
	*/
	function start_php_session() {
		session_start();
	}

	/**
	 * Add's actions and filters used with Sessions
	 *
	 * @since 0.3.3
	 * @return void
	*/
	function register_hooks() {
		//add_action( 'init', array( $this, 'show_data' ) );
	}


	/**
	 * Generates a session token
	 *
	 * @since 0.3.3
	 * @return void
	*/
	function set_session_token() {
		$token  = ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$token .= '-it-exchange-' . AUTH_SALT;
		$this->_token = md5( $token );
	}

	/**
	 * Regenerates the session id for a added level of security
	 *
	 * @since 0.3.3.
	 * @return void
	*/
	function regenerate_session_id() {
		session_regenerate_id();
		$_SESSION['it_exchange']['_session'] = $this->_token;
	}

	/**
	 * Deletes the Session
	 *
	 * @since 0.3.3
	 * @param boolean $restart  try to restart the session after destroying it? default is yes
	 * @return void
	*/
	function delete_session( $restart=true ) {
		if ( ! empty( $_SESSION['it_exchange'] ) )
			unset( $_SESSION['it_exchange'] );
		if ( $restart )
			$this->init_session();
	}

	/**
	 * Loads $_SESSION['it_exchange']['products'] into $this->_products
	 *
	 * @since 0.3.3
	 * @return void
	*/
	private function load_products() {
		$products = empty( $_SESSION['it_exchange']['products'] ) ? array() : $_SESSION['it_exchange']['products'];
		$this->_products = $products;
	}

	/**
	 * Loads $_SESSION['it_exchange']['data'] into $this->_session_data
	 *
	 * @since 0.3.3
	 * @return void
	*/
	private function load_data() {
		$data = empty( $_SESSION['it_exchange']['data'] ) ? array() : $_SESSION['it_exchange']['data'];
		$this->_session_data = $data;
	}

	/**
	 * Get Session Data 
	 *
	 * @since 0.3.3
	 * @return array $_session_data property
	*/
	function get_data() {
		return $this->_session_data;
	}

	/**
	 * Adds sesson data to the array
	 *
	 * This will add it directly to the SESSION's data array and reload the object's variable
	 *
	 * @since 0.3.7
	 * @param mixed $data data as passed by the shopping cart
	 * @param mixed $key optional identifier for the data.
	 * @return void 
	*/
	function add_data( $data, $key=false ) {

		if ( ! empty( $key ) )
			$_SESSION['it_exchange']['data'][$key] = $data;
		else
			$_SESSION['it_exchange']['data'][] = $data;
		$this->load_data();
	}

	/**
	 * Updates session data if a valid key is provided
	 *
	 * @since 0.3.7
	 * @param mixed $key which array are we updating? The entire array value for the key will be replaced.
	 * @return boolean
	*/
	function update_data( $key, $data ) {

		$this->remove_data( $key );
		$this->add_data( $data, $key );
		
		return true;
	}

	/**
	 * Removes data from session_data array in the PHP Session
	 *
	 * @since 0.3.7
	 * @param mixed $key the array key storing the data 
	 * @return boolean
	*/
	function remove_data( $key ) {
		if ( isset( $_SESSION['it_exchange']['data'][$key] ) ) {
			unset( $_SESSION['it_exchange']['data'][$key] );
			$this->load_data();
			return true;
		}
		return false;
	}

	/**
	 * Removes all data from the session
	 *
	 * @since 0.3.7
	 * @return array the $_session_data property
	*/
	function clear_data() {
		$_SESSION['it_exchange']['data'] = array();
		$this->load_data();
		return true;
	}

	/**
	 * Get products
	 *
	 * @since 0.3.3
	 * @return array $_products property
	*/
	function get_products() {
		if ( ! empty( $this->_products ) )
			return $this->_products;
		return false;
	}

	/**
	 * Adds a product to the product array
	 *
	 * This will add it directly to the SESSION array and reload the object variable
	 *
	 * @since 0.3.3
	 * @param mixed $product product data as passed by the shopping cart
	 * @param mixed $key optional identifier for the product.
	 * @return void 
	*/
	function add_product( $product, $key=false ) {

		if ( ! empty( $key ) )
			$_SESSION['it_exchange']['products'][$key] = $product;
		else
			$_SESSION['it_exchange']['products'][] = $product;
		$this->load_products();
	}

	/**
	 * Updates a product if a valid key is provided
	 *
	 * @since 0.3.7
	 * @param mixed $key which array are we updating? The entire product will be replaced.
	 * @return boolean
	*/
	function update_product( $key, $product ) {
		if ( empty( $key ) || ! isset( $this->_products[$key] ) )
			return false;

		$this->remove_product( $key );
		$this->add_product( $product, $key );
		
		return true;
	}

	/**
	 * Removes a product from products array in the PHP Session
	 *
	 * @since 0.3.3
	 * @param mixed $key the array key storing the product
	 * @return boolean
	*/
	function remove_product( $key ) {
		if ( isset( $_SESSION['it_exchange']['products'][$key] ) ) {
			unset( $_SESSION['it_exchange']['products'][$key] );
			$this->load_products();
			return true;
		}
		return false;
	}

	/**
	 * Removes all products from the session
	 *
	 * @since 0.3.3
	 * @return array the $_products_property
	*/
	function clear_products() {
		$_SESSION['it_exchange']['products'] = array();
		$this->load_products();
		return true;
	}
}
$GLOBALS['it_exchange']['session'] = new IT_Exchange_Session();
