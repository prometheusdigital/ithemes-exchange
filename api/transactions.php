<?php
/**
 * API Functions for Transaction Method Add-ons
 *
 * In addition to the functions found below, iThemes Exchange offers the following actions related to transactions
 * - it_exchange_save_transaction_unvalidated                       // Runs every time a transaction is saved.
 * - it_exchange_save_transaction_unavalidated-[txn-method] // Runs every time a specific transaction method is saved.
 * - it_exchange_save_transaction                           // Runs every time a transaction is saved if not an autosave and if user has permission to save post
 * - it_exchange_save_transaction-[txn-method]             // Runs every time a specific transaction method is saved if not an autosave and if user has permission to save transaction
 *
 * @package IT_Exchange
 * @since 0.3.3
*/


/**
 * Grabs the transaction method of a transaction
 *
 * @since 0.3.3
 * @return string the transaction method
*/
function it_exchange_get_transaction_method( $transaction=false ) {
	if ( is_object( $transaction ) && 'IT_Exchange_Transaction' == get_class( $transaction ) )
		return $transaction->transaction_method;

	if ( ! $transaction ) {
		global $post;
		$transaction = $post;
	}

	// Return value from IT_Exchange_Transaction if we are able to locate it
	$transaction = it_exchange_get_transaction( $transaction );
	if ( is_object( $transaction ) && ! empty ( $transaction->transaction_method ) && ! is_null( $transaction->transaction_method ) )
		return $transaction->transaction_method;

	// Return query arg if is present
	if ( ! empty ( $_GET['transaction-method'] ) )
		return $_GET['transaction-method'];

	return false;
}

/**
 * Returns the options array for a registered transaction-method
 *
 * @since 0.3.3
 * @param string $transaction_method  slug for the transaction-method
*/
function it_exchange_get_transaction_method_options( $transaction_method ) {
	if ( $addon = it_exchange_get_addon( $transaction_method ) )
		return $addon['options'];
	
	return false;
}

/**
 * Retreives a transaction object by passing it the WP post object or post id
 *
 * @since 0.3.3
 * @param mixed $post  post object or post id
 * @rturn object IT_Exchange_Transaction object for passed post
*/
function it_exchange_get_transaction( $post ) {
	if ( is_object( $post ) && 'IT_Exchange_Transaction' == get_class( $post ) )
		return $post;

	return new IT_Exchange_Transaction( $post );
}

/**
 * Get IT_Exchange_Transactions
 *
 * @since 0.3.3
 * @return array  an array of IT_Exchange_Transaction objects
*/
function it_exchange_get_transactions( $args=array() ) {
	$defaults = array(
		'post_type' => 'it_exchange_tran',
	);
	
	// Different defaults depending on where we are.
	if ( $transaction_hash = get_query_var('confirmation') ) {
		if ( $transaction_id = it_exchange_get_transaction_id_from_hash( $transaction_hash ) )
			$defaults['p'] = $transaction_id;
		else
			return array();
	}

	$args = wp_parse_args( $args, $defaults );
	$args['meta_query'] = empty( $args['meta_query'] ) ? array() : $args['meta_query'];

	// Fold in transaction_method
	if ( ! empty( $args['transaction_method'] ) ) {
		$meta_query = array( 
			'key'   => '_it_exchange_transaction_method',
			'value' => $args['transaction_method'],
		);
		$args['meta_query'] = array_merge( $args['meta_query'], $meta_query );
	}

	// Fold in transaction_status
	if ( ! empty( $args['transaction_status'] ) ) {
		$meta_query = array( 
			'key'   => '_it_exchange_transaction_status',
			'value' => $args['transaction_status'],
		);
		$args['meta_query'] = array_merge( $args['meta_query'], $meta_query );
	}

	// Fold in customer 
	if ( ! empty( $args['customer_id'] ) ) {
		$meta_query = array( 
			'key'   => '_it_exchange_customer_id',
			'value' => $args['customer_id'],
		);
		$args['meta_query'] = array_merge( $args['meta_query'], $meta_query );
	}

	if ( $get_transactions = get_posts( $args ) ) {
		foreach( $get_transactions as $key => $transaction ) {
			$transactions[$key] = it_exchange_get_transaction( $transaction );
		}
		return $transactions;
	}

	return array();
}

/**
 * Adds a transaction post_type to WP
 *
 * @since 0.3.3
 * @param array $args same args passed to wp_insert_post plus any additional needed
 * @param object $cart_object passed cart object
 * @return mixed post id or false
*/
function it_exchange_add_transaction( $method, $method_id, $status = 'pending', $customer_id = false, $cart_object, $args = array() ) {
	$defaults = array(
		'post_type'          => 'it_exchange_tran',
		'post_status'        => 'publish',
	);
	$args = wp_parse_args( $args, $defaults );
	
	if ( !$customer_id )
		$customer_id = it_exchange_get_current_customer_id();

	// If we don't have a title, create one
	if ( empty( $args['post_title'] ) )
		$args['post_title'] = $method . '-' . $method_id . '-' . date_i18n( 'Y-m-d-H:i:s' );

	if ( $transaction_id = wp_insert_post( $args ) ) {
		update_post_meta( $transaction_id, '_it_exchange_transaction_method',    $method );
		update_post_meta( $transaction_id, '_it_exchange_transaction_method_id', $method_id );
		update_post_meta( $transaction_id, '_it_exchange_transaction_status',    $status );
		update_post_meta( $transaction_id, '_it_exchange_customer_id',           $customer_id );
		update_post_meta( $transaction_id, '_it_exchange_cart_object',           $cart_object );

		// Transaction Hash for confirmation lookup
		update_post_meta( $transaction_id, '_it_exchange_transaction_hash', it_exchange_generate_transaction_hash( $transaction_id, $customer_id ) );
		
		/**
		HEAD
		$transaction_object = apply_filters( 'it_exchange_add_transaction_success', $transaction_object, $transaction_id, $customer_id );
		do_action( 'it_exchange_add_transaction_completed', $transaction_object, $transaction_id, $customer_id );
		**/
		do_action( 'it_exchange_add_transaction_success', $transaction_id );
		return $transaction_id;
	}
	do_action( 'it_exchange_add_transaction_failed', $args, $transaction_object );
	return false;
}

/**
 * Generates a unique transaction ID for receipts
 *
 * @since 0.4.0
 *
 * @param integer   $transaction_id the wp_post ID for the transaction
 * @param interger  $user_id the wp_users ID for the customer
 * @return string
*/
function it_exchange_generate_transaction_hash( $transaction_id, $customer_id ) {
	// Targeted hash
	$hash = wp_hash( time() . $transaction_id . $customer_id );
	if ( it_exchange_get_transaction_id_from_hash( $hash ) )
		$hash = it_exchange_generate_transaction_hash( $transaction_id, $customer_id );
	
	return apply_filters( 'it_exchange_generate_transaction_hash', $hash, $transaction_id, $customer_id );
}

/**
 * Return the transaction ID provided by the gateway (transaction method)
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return mixed
*/
function it_exchange_get_gateway_id_for_transaction( $transaction ) {
	if ( ! $transaction = it_exchange_get_transaction( $transaction ) )
		return;

	$gateway_transaction_id = $transaction->get_gateway_id_for_transaction();
	return apply_filters( 'it_exchange_get_gateway_id_for_transaction', $gateway_transaction_id, $transaction );
}

/**
 * Returns a transaction ID based on the hash
 *
 * @since 0.4.0
 *
 * @param string $hash
 * @return integer transaction id
*/
function it_exchange_get_transaction_id_from_hash( $hash ) {
	global $wpdb;
	if ( $transaction_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s LIMIT 1;", '_it_exchange_transaction_hash', $hash ) ) ) {
		return $transaction_id;
	}

	return false;
}

/**
 * Returns the transaction hash from an ID
 *
 * @since 0.4.0
 *
 * @param integer $id transaction_id
 * @return mixed ID or false
*/
function it_exchange_get_transaction_hash( $id ) {
	return get_post_meta( $id, '_it_exchange_transaction_hash', true );
}

/**
 * Updates a transaction
 *
 * @since 0.3.3
 * @param array transaction args. Must include ID of a valid transaction post
 * @return object transaction object
*/
function it_exchange_update_transaction( $args ) {
	$id = empty( $args['id'] ) ? false : $args['id'];
	$id = ( empty( $id ) && ! empty( $args['ID'] ) ) ? $args['ID']: $id;

	if ( 'it_exchange_tran' != get_post_type( $id ) )
		return false;

	$args['ID'] = $id;

	$result = wp_update_post( $args );
	$transaction_method = it_exchange_get_transaction_method( $id );

	do_action( 'it_exchange_update_transaction', $args );
	do_action( 'it_exchange_update_transaction_' . $transaction_method, $args );

	if ( ! empty( $args['_it_exchange_transaction_status'] ) )
		it_exchange_update_transaction_status( $id, $args['_it_exchange_transaction_status'] );

	return $result;
}

/**
 * Updates the transaction status of a transaction
 *
 * @since 0.3.3
 * @param mixed $transaction the transaction id or object
 * @param string $status the new transaction status
*/
function it_exchange_update_transaction_status( $transaction, $status ) {

	if ( 'IT_Exchange_Transaction' != get_class( $transaction ) ) {
		$transaction = it_exchange_get_transaction( $transaction );
	}

	if ( ! $transaction->ID )
		return false;

	$old_status = $transaction->get_status();
	$transaction->update_status( $status );

	do_action( 'it_exchange_update_transaction_status', $transaction, $old_status );
	do_action( 'it_exchange_update_transaction_status_' . $transaction->transaction_method, $transaction, $old_status );
	return $transaction->get_status();
}

/**
 * Returns the transaction status for a specific transaction
 *
 * @since 0.3.3
 * @param mixed $transaction the transaction id or object
 * @return string the transaction status
*/
function it_exchange_get_transaction_status( $transaction ) {
    $transaction = it_exchange_get_transaction( $transaction );
    if ( !empty( $transaction->status ) )
        return $transaction->status;
    return false;
}

/**
 * Returns the label for a transaction status (provided by addon)
 *
 * @since 0.4.0
 *
 * @param string $transaction_method the transaction method
 * @return string
*/
function it_exchange_get_transaction_status_label( $transaction ){
	$transaction = it_exchange_get_transaction( $transaction );
	return apply_filters( 'it_exchange_transaction_status_label_' . $transaction->transaction_method, $transaction->status );
}

/**
 * Returns the instructions for a transaction instructions (provided by addon)
 *
 * @since 0.4.0
 *
 * @param string $transaction_method the transaction method
 * @return string
*/
function it_exchange_get_transaction_instructions( $transaction ){
	$transaction = it_exchange_get_transaction( $transaction );
	return apply_filters( 'it_exchange_transaction_instructions_' . $transaction->transaction_method, '' );
}

/**
 * Return the transaction date
 *
 * @since 0.4.0
 *
 * @param mixed   $transaction ID or object
 * @param string  $format php date format
 * @param boolean $gmt return the gmt date?
 * @return string date
*/
function it_exchange_get_transaction_date( $transaction, $format=false, $gmt=false ) {
	$format = empty( $format ) ? get_option( 'date_format' ) : $format;

	// Try to locate the IT_Exchange_Transaction object from the var
	if ( $transaction = it_exchange_get_transaction( $transaction ) ) {
		if ( $date = $transaction->get_date() )
			return date_i18n( $format, strtotime( $date ), $gmt );
	}

	return false;
}

/**
 * Return the transaction subtotal
 *
 * @since 0.4.0
 *
 * @param mixed   $transaction ID or object
 * @param string  $format php date format
 * @param boolean $gmt return the gmt date?
 * @return string date
*/
function it_exchange_get_transaction_subtotal( $transaction, $format_currency=true ) {

	// Try to locate the IT_Exchange_Transaction object from the var
	if ( $transaction = it_exchange_get_transaction( $transaction ) ) {
		if ( $subtotal = $transaction->get_subtotal() )
			return $format_currency ? it_exchange_format_price( $subtotal ) : $subtotal;
	}

	return false;
}

/**
 * Return the transaction total
 *
 * @since 0.4.0
 *
 * @param mixed   $transaction ID or object
 * @param boolean $format format the price?
 * @param boolean $subtract_refunds if refunds are present, subtract the difference?
 * @return string date
*/
function it_exchange_get_transaction_total( $transaction, $format_currency=true, $subtract_refunds=true ) {

	// Try to locate the IT_Exchange_Transaction object from the var
	if ( $transaction = it_exchange_get_transaction( $transaction ) ) {
		$total = $transaction->get_total( $subtract_refunds );
		return $format_currency ? it_exchange_format_price( $total ) : $total;
	}

	return false;
}

/**
 * Return the currency used in the transaction
 *
 * @since 0.4.0
 *
 * @param mixed   $transaction ID or object
 * @return string date
*/
function it_exchange_get_transaction_currency( $transaction ) {

	// Try to locate the IT_Exchange_Transaction object from the var
	if ( $transaction = it_exchange_get_transaction( $transaction ) ) {
		$currency = $transaction->get_currency();
	}

	return empty( $currency ) ? false: $currency;
}

/**
 * Returns an array of all coupons applied to a given transaction
 *
 * @since 0.4.0
 *
 * @param mixed   $transaction ID or object
 * @return string date
*/
function it_exchange_get_transaction_coupons( $transaction ) {

	// Try to locate the IT_Exchange_Transaction object from the var
	if ( ! $transaction = it_exchange_get_transaction( $transaction ) )
		return false;

	return $transaction->get_coupons();
}

/**
 * Return the total discount of all coupons applied to a given transaction
 *
 * @since 0.4.0
 *
 * @param mixed   $transaction ID or object
 * @param bool $format Format the price
 * @return string date
*/
function it_exchange_get_transaction_coupons_total_discount( $transaction, $format = true ) {
	if ( ! $transaction = it_exchange_get_transaction( $transaction ) )
		return false;

	return ( $format ) ? it_exchange_format_price( $transaction->get_coupons_total_discount() ) : $transaction->get_coupons_total_discount();
}

/**
 * Adds a refund to a transaction
 *
 * @since 0.4.0
 *
 * @param string $method slug for transaction_method
 * @param mixed $options
*/
function it_exchange_add_refund_to_transaction( $transaction, $amount, $date=false, $options=array() ) {
	if ( ! $transaction = it_exchange_get_transaction( $transaction ) )
		return false;

	$transaction->add_refund( $amount, $date, $options );
}

/**
 * Grab refunds for a transaction
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return array
*/
function it_exchange_get_transaction_refunds( $transaction ) {
	if ( ! $transaction = it_exchange_get_transaction( $transaction ) )
		return false;

	return $transaction->get_transaction_refunds();
}

/**
 * Checks if there are refunds for a transaction
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return array
*/
function it_exchange_has_transaction_refunds( $transaction ) {
	if ( $transaction = it_exchange_get_transaction( $transaction ) )
		return true;
	
	return false;
}

/**
 * Returns the a sum of all the applied refund amounts for this transaction
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @param bool $format Format the price
 * @return numeric
*/
function it_exchange_get_transaction_refunds_total( $transaction, $format = true ) {
	$refunds = it_exchange_get_transaction_refunds( $transaction );
	$total_refund = 0;
	foreach ( $refunds as $refund ) {
		$total_refund += $refund['amount'];
	}
	return ( $format ) ? it_exchange_format_price( $total_refund ) : $total_refund;
}

/**
 * Returns the transaction description
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return string
*/
function it_exchange_get_transaction_description( $transaction ) {
	if ( $transaction = it_exchange_get_transaction( $transaction ) )
		return $transaction->get_description();
}

/**
 * Returns the customer object associated with a transaction
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return object
*/
function it_exchange_get_transaction_customer( $transaction ) {
	if ( $transaction = it_exchange_get_transaction( $transaction ) ) {
		return empty( $transaction->customer_id ) ? false : it_exchange_get_customer( $transaction->customer_id );
	}
	return false;
}

/**
 * Returns the transaction customer's Display Name
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return string
*/
function it_exchange_get_transaction_customer_display_name( $transaction ) {
	if ( ! $customer = it_exchange_get_transaction_customer( $transaction ) )
		return __( 'Unknown', 'LION' );

	return empty( $customer->wp_user->display_name ) ? __( 'Unknown', 'LION' ) : $customer->wp_user->display_name;	
}

/**
 * Returns the transaction customer's ID
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return string
*/
function it_exchange_get_transaction_customer_id( $transaction ) {
	if ( ! $customer = it_exchange_get_transaction_customer( $transaction ) )
		return 0;

	return empty( $customer->wp_user->ID ) ? 0 : $customer->wp_user->ID;	
}

/**
 * Returns the transaction customer's email 
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return string
*/
function it_exchange_get_transaction_customer_email( $transaction ) {
	if ( ! $customer = it_exchange_get_transaction_customer( $transaction ) )
		return __( 'Unknown', 'LION' );

	return empty( $customer->wp_user->user_email ) ? __( 'Unknown', 'LION' ) : $customer->wp_user->user_email;	
}

/**
 * Returns the transaction customer's profile URL
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return string
*/
function it_exchange_get_transaction_customer_admin_profile_url( $transaction, $options=array() ) {
	if ( ! $customer = it_exchange_get_transaction_customer( $transaction ) )
		return __( 'Unknown', 'LION' );

	$defaults = array(
		'tab' => 'transactions',
	);
	$options = ITUtility::merge_defaults( $options, $defaults );

	$url = add_query_arg( array( 'user_id' => $customer->id, 'it_exchange_customer_data' => 1, 'tab' => $options['tab'] ), get_admin_url() . 'user-edit.php' );	
	return $url;
}

/**
 * Get Transaction Order Number
 *
 * @since 0.4.0
 *
 * @param mixed $transaction id or object
 * @return string
*/
function it_exchange_get_transaction_order_number( $transaction, $prefix='#' ) {
	if ( ! $transaction = it_exchange_get_transaction( $transaction ) )
		return false;

	// Translate default prefix
	$prefix = ( '#' == $prefix ) ? __( '#', 'LION' ) : $prefix;

	$order_number = sprintf( '%06d', $transaction->ID );
	$order_number = empty( $prefix ) ? $order_number : $prefix . $order_number;

	return apply_filters( 'it_exchange_get_transaction_order_number', $order_number, $transaction );
}

/**
 * Returns an array of product objects as they existed when added to the transaction
 *
 * @since 0.4.0
 *
 * @param mixed $transaction id or objec
 * @return array
*/
function it_exchange_get_transaction_products( $transaction ) {
	if ( ! $transaction = it_exchange_get_transaction( $transaction ) )
		return array();

	if ( ! $transaction_products = $transaction->get_products() )
		return array();

	// There is a filter in transaction class: it_exchange_get_transaction_products
	return $transaction_products;
}

/**
 * Returns a specific product from a transaction based on the product_cart_id
 *
 * @since 0.4.0
 *
 * @param string $product_cart_id 
 * @return object
*/
function it_exchange_get_transaction_product( $transaction, $product_cart_id ) {
	if ( ! $products = it_exchnage_get_transaction_products( $transaction ) )
		return false;

	return empty( $products[$product_cart_id] ) ? false : $products[$product_cart_id];
}

/**
 * Returns data from the transaction product
 *
 * @since 0.4.0
 *
 * @param object $transaction_product
 *
*/
function it_exchange_get_transaction_product_feature( $product, $feature ) {
	$return = false;

	if ( 'title' == $feature || 'name' == $feature )
		$feature = 'product_name';

	$feature_value = isset( $product[$feature] ) ? $product[$feature] : '';

	return apply_filters( 'it_exchange_get_transaction_product_feature', $feature_value, $product, $feature );
}

/**
 * Returns the transaction method name from the add-on's slug
 *
 * @since 0.3.7
 * @return string
*/
function it_exchange_get_transaction_method_name_from_slug( $slug ) {
	if ( ! $method = it_exchange_get_addon( $slug ) )
		return false;

	$name = apply_filters( 'it_exchange_get_transaction_method_name_' . $method['slug'], $method['name'] );
	return $name;
}

/**
 * Returns the name of a transaction method used for a specific transaction
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return string
*/
function it_exchange_get_transaction_method_name( $transaction ) {
	if ( ! $slug = it_exchange_get_transaction_method( $transaction ) )
		return false;

	return it_exchange_get_transaction_method_name_from_slug( $slug );
}

/**
 * Returns the name of a transaction method used for a specific transaction
 *
 * @since 0.4.0
 *
 * @param mixed $transaction ID or object
 * @return string
*/

function it_exchange_get_transaction_method_id( $transaction ){
	$transaction = it_exchange_get_transaction( $transaction );
	return get_post_meta( $transaction->ID, '_it_exchange_transaction_method_id', true );
}

/**
 * For processing a transaction
 *
 * @since 0.3.7
 * @return mixed
*/
function it_exchange_do_transaction( $method, $transaction_object ) {
	return apply_filters( 'it_exchange_do_transaction_' . $method, false, $transaction_object );
}

/**
 * Returns an array of transaction statuses that translate as good for delivery
 *
 * @since 0.4.0
 *
 * @return array
*/
function it_exchange_get_successfull_transaction_stati( $transaction_method ) {
	return apply_filters( 'it_exchange_get_successufll_transaction_stati_' . $transaction_method, array() );
}

/**
 * Returns the make-payment action
 *
 * Leans on tranasction_method to actually provide it.
 *
 * @since 0.4.0
 *
 * @param string $tranasction_method slug registered with addon
 * @param array $options
 * @return mixed
*/
function it_exchange_get_transaction_method_make_payment_button ( $transaction_method, $options=array() ) {
	return apply_filters( 'it_exchange_get_' . $transaction_method . '_make_payment_button', '', $options );
}

/**
 * Grab all registered webhook / IPN keys
 *
 * @since 0.4.0
 * @return array
*/
function it_exchange_get_webhooks() {
	return empty( $GLOBALS['it_exchange']['webhooks'] ) ? array() : (array) $GLOBALS['it_exchange']['webhooks'];
}

/**
 * Register a webhook / IPN key
 *
 * @since 0.4.0
 *
 * @param string $key   the addon slug or ID
 * @param string $param the REQUEST param we are listening for
 * @return void
*/
function it_exchange_register_webhook( $key, $param ) {
	$GLOBALS['it_exchange']['webhooks'][$key] = $param;
}

/**
 * Grab a specific registered webhook / IPN param
 *
 * @since 0.4.0
 *
 * @param string $key the key for the param we are looking for
 * @return string or false 
*/
function it_exchange_get_webhook( $key ) {
	$webhooks = it_exchange_get_webhooks();
	return empty( $GLOBALS['it_exchange']['webhooks'][$key] ) ? false : $GLOBALS['it_exchange']['webhooks'][$key];
}

/**
 * Get the confirmation URL for a transaction
 *
 * @since 0.4.0
 *
 * @param integer $transaction_id id of the transaction
 * @return string url
*/
function it_exchange_get_transaction_confirmation_url( $transaction_id ) {
	// If we can't grab the hash, return false
	if ( ! $transaction_hash = it_exchange_get_transaction_hash( $transaction_id ) )
		return false;

	// Get base page URL
	$confirmation_url = it_exchange_get_page_url( 'confirmation' );

	if ( '' != get_option( 'permalink_structure' ) ) {
		$confirmation_url = trailingslashit( $confirmation_url ) . $transaction_hash;
	} else {
		$pages = it_exchange_get_option( 'settings_pages' );
		$slug  = $pages['confirmation-slug'];
		$confirmation_url = remove_query_arg( $slug, $confirmation_url );
		$confirmation_url = add_query_arg( $slug, $transaction_hash, $confirmation_url );
	}
	return $confirmation_url;
}
