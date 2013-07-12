<?php
/**
 * Contains the class or the customer object
 * @since 0.3.8
 * @package IT_Exchange
*/

/**
 * The IT_Exchange_Customer class holds all important data for a specific customer
 *
 * @since 0.3.8
*/
class IT_Exchange_Customer {
	
	/**
	 * @var integer $id the customer id. corresponds with the WP user id
	 * @since 0.3.8
	*/
	var $id;

	/**
	 * @var object $wp_user the wp_user or false
	 * @since 0.3.8
	*/
	var $wp_user;

	/**
	 * @var object $customer_data customer information
	 * @since 0.3.8
	*/
	var $data;

	/**
	 * @var array $transaction_history an array of all transactions the user has ever created
	 * @since 0.3.8
	*/
	var $transaction_history;

	/**
	 * @var array $purchase_history an array of all products ever purchased
	 * @since 0.3.8
	*/
	var $purchase_history;

	/**
	 * Constructor. Sets up the customer
	 *
	 * @since 0.3.8
	 * @param integer $id customer id
	 * @return mixed false if no customer is found. self if customer is located
	*/
	function IT_Exchange_Customer( $id ) {
		
		// Set the ID
		$this->id = $id;

		// Set properties
		$this->init();

		// Return false if not a WP User
		if ( ! $this->is_wp_user() )
			return false;
		
		// Return object if found a WP user
		return $this;
	}

	/**
	 * Sets up the class
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function init() {
		$this->set_wp_user();
		$this->set_customer_data();
		
		//We want to do this last
		add_action( 'it_exchange_add_transaction_success', array( $this, 'add_transaction_to_user' ), 999 );
	}

	/**
	 * Sets the $wp_user property
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function set_wp_user() {
		$this->wp_user = new WP_User( $this->id );

		if ( is_wp_error( $this->wp_user ) )
			$this->wp_user = false;
	}

	/**
	 * Sets customer data
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function set_customer_data() {
		$data = (object) $this->data;
		
		$wp_user_data = get_object_vars( $this->wp_user->data );
		foreach( (array) $wp_user_data as $key => $value ) {
			$data->$key = $value;
		}
		$data->first_name   = get_user_meta( $this->id, 'first_name', true );
		$data->last_name    = get_user_meta( $this->id, 'last_name', true );

		$data = apply_filters( 'it_exchange_set_customer_data', $data, $this->id );
		$this->data = $data;
	}
	
    /** 
     * Tack transaction_id to user_meta of customer
     *
     * @since 0.4.0
     *
     * @param integer $transaction_id id of the transaction
     * @return void
    */
	function add_transaction_to_user( $transaction_id ) {
		add_user_meta( $this->id, '_it_exchange_transaction_id', $transaction_id );
	}
	
    /** 
     * Tack transaction_id to user_meta of customer
     *
     * @since 0.4.0
     *
     * @param integer $transaction_id id of the transaction
     * @return void
    */
	function has_transaction( $transaction_id ) {
		$transaction_ids = get_user_meta( $this->id, '_it_exchange_transaction_id' );
		return ( in_array( $transaction_id, $transaction_ids ) );
	}

	/**
	 * Returns true or false based on whether the $id property is a WP User id
	 *
	 * @since 0.3.8
	 * @return boolean
	*/
	function is_wp_user() {
		return (bool) $this->wp_user;
	}

	/**
	 * Returns the purchase history
	 *
	 * @since 0.3.8
	 * @return mixed purchase_history or false
	*/
	function get_purchase_history() {
		$history = empty( $this->purchase_history ) ? false : $this->purchase_history;
		return apply_filters( 'it_exchange_get_customer_purchase_history', $history, $this->id );
	}
}

/**
 * Handles $_REQUESTs and submits them to the registration for processing
 *
 * @since 0.4.0
 * @return void
*/
function handle_it_exchange_customer_registration_action() {

    // Grab action and process it.
    if ( isset( $_POST['it-exchange-register-customer'] ) ) {

        do_action( 'before_handle_it_exchange_customer_registration_action' );

        $user_id = it_exchange_register_user();

        if ( is_wp_error( $user_id ) )
            return it_exchange_add_message( 'error', $user_id->get_error_message());

        wp_new_user_notification( $user_id, $_POST['pass1'] );

        $creds = array(
            'user_login'    => $_POST['user_login'],
            'user_password' => $_POST['pass1'],
        );

        $user = wp_signon( $creds );

        if ( is_wp_error( $user ) )
            return it_exchange_add_message( 'error', $result->get_error_message() );

        $reg_page = it_exchange_get_page_url( 'registration' );
        // Set redirect to profile page if they were on the registration page
        $redirect = ( trailingslashit( $reg_page ) == trailingslashit( wp_get_referer() ) ) ? it_exchange_get_page_url( 'profile' ) : it_exchange_clean_query_args( array(), array( 'ite-sw-state' ) );

        do_action( 'handle_it_exchange_customer_registration_action' );
        do_action( 'after_handle_it_exchange_customer_registration_action' );

        wp_redirect( $redirect );
        die();

    }

}
add_action( 'template_redirect', 'handle_it_exchange_customer_registration_action', 5 );
