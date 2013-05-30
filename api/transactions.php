<?php
/**
 * API Functions for Transaction Method Add-ons
 *
 * In addition to the functions found below, iThemes Exchange offers the following actions related to transactions
 * - it_exchange_save_transaction_unvalidated		                 // Runs every time an iThemes Exchange transaction is saved.
 * - it_exchange_save_transaction_unavalidate-[transaction-method] // Runs every time a specific iThemes Exchange transaction method is saved.
 * - it_exchange_save_transaction                                  // Runs every time an iThemes Exchange transaction is saved if not an autosave and if user has permission to save post
 * - it_exchange_save_transaction-[transaction-method]             // Runs every time a specific iThemes Exchange transaction method is saved if not an autosave and if user has permission to save transaction
 *
 * @package IT_Exchange
 * @since 0.3.3
*/

/**
 * Hook for processing webhooks from services like PayPal IPN, Stripe, etc.
 *
 * @since 0.4.0
*/
function it_exchange_process_webhooks() {
	
	$webhook_keys = apply_filters( 'it_exchange_webhook_keys', array() );
	
	foreach( $webhook_keys as $key ) {
	
		if ( !empty( $_REQUEST[$key] ) )
			do_action( 'it_exchange_webhook_' . $key, $_REQUEST );
		
	}
	
	do_action( 'it_exchange_webhooks_processed' );
	
}
add_action( 'wp', 'it_exchange_process_webhooks' );

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
	if ( is_object( $transaction ) && ! empty ( $transaction->transaction_method ) )
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
	}

	$args = wp_parse_args( $args, $defaults );
	$meta_query = empty( $args['meta_query'] ) ? array() : $args['meta_query'];

	// Fold in transaction_method
	if ( ! empty( $args['transaction_method'] ) ) {
		$args['meta_query'][] = array( 
			'key'   => '_it_exchange_transaction_method',
			'value' => $args['transaction_method'],
		);
		unset( $args['transaction_method'] );
	}

	// Fold in transaction_status
	if ( ! empty( $args['transaction_status'] ) ) {
		$args['meta_query'][] = array( 
			'key'   => '_it_exchange_transaction_status',
			'value' => $args['transaction_status'],
		);
		unset( $args['transaction_status'] );
	}

	if ( $transactions = get_posts( $args ) ) {
		foreach( $transactions as $key => $transaction ) {
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
 * @param object $transaction_object passed cart object
 * @return mixed post id or false
*/
function it_exchange_add_transaction( $method, $method_id, $status = 'pending', $customer_id = false, $transaction_object, $args = array() ) {
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
		update_post_meta( $transaction_id, '_it_exchange_transaction_object',    $transaction_object );

		// Transaction Hash for confirmation lookup
		update_post_meta( $transaction_id, '_it_exchange_transaction_hash', it_exchange_generate_transaction_hash( $transaction_id, $customer_id ) );
		
		$transaction_object = apply_filters( 'it_exchange_add_transaction_success', $transaction_object, $transaction_id );
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
	$hash = md5( $transaction_id . $customer_id . wp_generate_password( 12, false ) );
	if ( it_exchange_get_transaction_id_from_hash( $hash ) )
		$hash = it_exchange_generate_transaction_hash( $transaction_id, $customer_id );
	
	return apply_filters( 'it_exchange_generate_transaction_hash', $hash, $transaction_id, $customer_id );
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
    $transaction = new IT_Exchange_Transaction( $transaction );
    if ( $transaction->ID )
        return $transaction;
    return false;
}

/**
 * Returns the label for a transaction status (provided by addon)
 *
 * @since 0.4.0
 *
 * @param string $transaction_method the transaction method
 * @param string $status the transaction status
 * @return string
*/
function it_exchange_get_transaction_status_label( $transaction ){
	$transaction = it_exchange_get_transaction( $transaction );
	return apply_filters( 'it_exchange_transaction_status_label_' . $transaction->transaction_method, $transaction->status );
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
 * Return the transaction total
 *
 * @since 0.4.0
 *
 * @param mixed   $transaction ID or object
 * @param string  $format php date format
 * @param boolean $gmt return the gmt date?
 * @return string date
*/
function it_exchange_get_transaction_total( $transaction, $format_currency=true ) {

	// Try to locate the IT_Exchange_Transaction object from the var
	if ( $transaction = it_exchange_get_transaction( $transaction ) ) {
		if ( $total = $transaction->get_total() )
			return $format_currency ? it_exchange_format_price( $total ) : $total;
	}

	return false;
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

	// Loop through the products, grab the IT_Exchange_Product object, and update with transaction data
	$products = array();
	foreach( $transaction_products as $key => $product ) {
		$db_prod                  = it_exchange_get_product( $product['product_id'] );
		$db_prod->cart_id         = $product['product_cart_id'];
		$db_prod->cart_name       = $product['product_name'];
		$db_prod->base_price      = $product['product_base_price'];
		$db_prod->subtotal        = $product['product_subtotal'];
		$db_prod->itemized_data   = $product['itemized_data'];
		$db_prod->additional_data = $product['additional_data'];
		$db_prod->count           = $product['count'];
ITUtility::print_r($db_prod);die( 'DIED IN ' . __FILE__ . ' on line ' . __LINE__);
		$products[$product['product_cart_id']] = $db_prod;
	}
	return $products;
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
 * Returns the transaction method name
 *
 * @since 0.3.7
 * @return string
*/
function it_exchange_get_transaction_method_name( $slug ) {
	if ( ! $method = it_exchange_get_addon( $slug ) )
		return false;

	$name = apply_filters( 'it_exchange_get_transaction_method_name_' . $method['slug'], $method['name'] );
	return $name;
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
