<?php
/**
 * API functions to deal with customer data and actions
 *
 * @since 0.3.7
 * @package IT_Exchange
*/

/**
 * Registers a customer
 *
 * @since 0.3.7
 * @param array $customer_data array of customer data to be processed by the customer management add-on when creating a customer
 * @param array $args optional array of arguments. not used by all add-ons
 * @return mixed
*/
function it_exchange_register_customer( $customer_data, $args=array() ) {
	return do_action( 'it_exchange_register_customer', $customer_data, $args );
}

/**
 * Get a customer
 *
 * Will return customer data formated by the active customer management add-on
 *
 * @since 0.3.7
 * @param integer $customer_id id for the customer
 * @return mixed customer data
*/
function it_exchange_get_customer( $customer_id ) {
    // Grab the WP User
	$customer = new IT_Exchange_Customer( $customer_id );
	return apply_filters( 'it_exchange_get_customer', $customer, $customer_id );
}

/**
 * Get the currently logged in customer or return false
 *
 * @since 0.3.7
 * @return mixed customer data
*/
function it_exchange_get_current_customer() {
	if ( ! is_user_logged_in() )
		return false;
		
	$customer = it_exchange_get_customer( get_current_user_id() );
	return apply_filters( 'it_exchange_get_current_customer', $customer );
}

/**
 * Get the currently logged in customer ID or return false
 *
 * @since 0.4.0
 * @return mixed customer data
*/
function it_exchange_get_current_customer_id() {
	if ( ! is_user_logged_in() )
		return false;
		
	return get_current_user_id();
}

/**
 * Update a customer's data
 *
 * @since 0.3.7
 * @param integer $customer_id id for the customer
 * @param mixed $customer_data data to be updated
 * @param array $args optional array of arguments. not used by all add-ons
 * @return mixed
*/
function it_exchange_update_customer( $customer_id, $customer_data, $args ) {
	return add_action( 'it_exchange_update_customer', $customer_id, $customer_data, $args );
}

/**
 * Returns an array of form fields for customer registration 
 *
 * Add-ons hooking onto this need to return an array with the following schema so that 
 * functions relying on this data may process it correctly:
 *
 * Add-ons calling this function should use ITForm to generate the form fields.
 *
 * $fields['password'] = array(
 *    'var'   => 'password',
 *    'type'  => 'password',
 *    'label' => 'Password:',
 * );
 *
 *
 * @since 0.3.7
 * @return array
*/
function it_exchange_get_customer_registration_fields() {
	$profile_fields = it_exchange_get_customer_profile_fields();

	$fields['username']  = array(
		'type'  => 'text_box',
		'var'   => 'user-login',
		'label' => __( 'Username', 'LION' ),
	);
	$fields['password1'] = array(
		'type'  => 'password',
		'var'   => 'password1',
		'label' => __( 'Password', 'LION' ),
	);
	$fields['password2'] = array(
		'type'  => 'password',
		'var'   => 'password2',
		'label' => __( 'Re-type Password', 'LION' ),
	);

	$fields = array_merge( $profile_fields, $fields );
	return apply_filters( 'it_exchange_get_customer_registration_fields', $fields );
}

/**
 * Returns an array of form fields for customer profile
 *
 * Add-ons hooking onto this need to return an array with the following schema so that 
 * functions relying on this data may process it correctly:
 *
 * Add-ons calling this function should use ITForm to generate the form fields.
 *
 * $fields['first_name'] = array(
 *    'var'   => 'first-name',
 *    'type'  => 'text_box',
 *    'label' => 'First Name:',
 * );
 *
 * @since 0.3.7
 * @return array 
*/
function it_exchange_get_customer_profile_fields() {
	$fields['first_name']  = array(
		'type'  => 'text_box',
		'var'   => 'first-name',
		'label' => __( 'First Name', 'LION' ),
	);  
	$fields['last_name'] = array(
		'type'  => 'text_box',
		'var'   => 'last-name',
		'label' => __( 'Last Name', 'LION' ),
	);  
	$fields['email'] = array(
		'type'  => 'text_box',
		'var'   => 'user-email',
		'label' => __( 'Email', 'LION' ),
	);  
	return apply_filters( 'it_exchange_get_customer_profile_fields', $fields );
}

/**
 * Returns the customer login form
 *
 * @since 0.3.7
 * @return string HTML
*/
function it_exchange_get_customer_login_form() {
	$args = array(
		'echo' => false,
		'form_id' => 'exchange_login_form',
	);
	$form = wp_login_form( $args );
	return apply_filters( 'it_exchange_get_customer_login_form', $form );
}

/**
 * Handles $_REQUESTs and submits them to the profile for processing
 *
 * @since 0.4.0
 * @return void
*/
function handle_it_exchange_save_profile_action() {
	
	// Grab action and process it.
	if ( isset( $_REQUEST['it-exchange-save-profile'] ) ) {

		//WordPress builtin
		require_once(ABSPATH . 'wp-admin/includes/user.php');
		$customer = it_exchange_get_current_customer();
		$result = edit_user( $customer->id );
		
		if ( is_wp_error( $result ) ) {
			it_exchange_add_message( 'error', $result->get_error_message() );
		} else {
			it_exchange_add_message( 'notice', __( 'Successfully saved profile!', 'LION' ) );
		}
		
	}
	
}
add_action( 'template_redirect', 'handle_it_exchange_save_profile_action', 5 );

/**
 * Register's an exchange user
 *
 * @since 0.4.0
 * @param array $user_data optional. Overwrites POST data
 * @return mixed WP_Error or WP_User object
*/
function it_exchange_register_user( $user_data=array() ) {

	// Include WP file
	require_once( ABSPATH . 'wp-admin/includes/user.php' );

	// If any data was passed in through param, inject into POST variable
	foreach( $user_data as $key => $value ) {
		$_POST[$key] = $value;
	}

	// Register user via WP function
	return edit_user();
}

/**
 * Handles $_REQUESTs and submits them to the registration for processing
 *
 * @todo Move to to lib/customers
 * @since 0.4.0
 * @return void
*/
function handle_it_exchange_customer_registration_action() {
	
	// Grab action and process it.
	if ( isset( $_REQUEST['it-exchange-register-customer'] ) ) {

		$result = it_exchange_register_user();
		
		if ( is_wp_error( $result ) )
			return it_exchange_add_message( 'error', $result->get_error_message());
		
		$user_id = $result;
			
		//else
		
		$creds = array( 
			'user_login'    => $_REQUEST['user_login'],
			'user_password' => $_REQUEST['pass1'],
		);
		
		$result = wp_signon( $creds );
		
		if ( is_wp_error( $result ) )
			return it_exchange_add_message( 'error', $result->get_error_message() );
			
		wp_new_user_notification( $user_id, $_REQUEST['pass1'] );
			
		$reg_page = it_exchange_get_page_url( 'registration' );
		// Set redirect to profile page if they were on the registration page
		$redirect = ( trailingslashit( $reg_page ) == trailingslashit( wp_get_referer() ) ) ? it_exchange_get_page_url( 'profile' ) : clean_it_exchange_query_args( array(), array( 'ite-sw-state' ) );
		wp_redirect( $redirect );
		die();
		
	}
	
}
add_action( 'template_redirect', 'handle_it_exchange_customer_registration_action', 5 );
