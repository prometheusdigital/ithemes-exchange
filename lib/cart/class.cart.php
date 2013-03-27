<?php
/**
 * Shopping cart class. 
 * @since 0.3.8
 * @package IT_Cart_Buddy
*/
class IT_Cart_Buddy_Shopping_Cart {

	/**
	 * Class constructor.
	 *
	 * Hooks default filters and actions for cart
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function IT_Cart_Buddy_Shopping_Cart() {
		add_action( 'it_cart_buddy_add_product_to_cart', array( $this, 'handle_add_product_to_cart_request' ), 9 );
		add_action( 'it_cart_buddy_empty_cart', array( $this, 'handle_empty_shopping_cart_request' ), 9 );
		add_action( 'it_cart_buddy_remove_product_from_cart', array( $this, 'handle_remove_product_from_cart_request' ), 9 );
		add_action( 'it_cart_buddy_update_cart', array( $this, 'handle_update_cart_quantity_request' ), 9 );
		add_action( 'it_cart_buddy_update_cart_action', array( $this, 'handle_update_cart_request' ), 9 );
		add_action( 'it_cart_buddy_purchase_cart', array( $this, 'handle_purchase_cart_request' ) );
		add_action( 'it_cart_buddy_proceed_to_checkout', array( $this, 'proceed_to_checkout' ), 9 );
		add_action( 'template_redirect', array( $this, 'redirect_checkout_if_empty_cart' ) );
		add_filter( 'it_cart_buddy_get_error_messages', array( $this, 'register_cart_error_messages' ) );
		add_filter( 'it_cart_buddy_get_alert_messages', array( $this, 'register_cart_alert_messages' ) );
	}

	/**
	 * Listens for $_REQUESTs to add a product to the cart and processes
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function handle_add_product_to_cart_request() {

		$add_to_cart_var = it_cart_buddy_get_action_var( 'add_product_to_cart' );
		$product_id = empty( $_REQUEST[$add_to_cart_var] ) ? 0 : $_REQUEST[$add_to_cart_var];
		$product    = it_cart_buddy_get_product( $product_id );

		// Vefify legit product
		if ( ! $product )
			$error = 'bad-product';

		// Verify nonce
		$nonce_var = apply_filters( 'it_cart_buddy_add_product_to_cart_nonce_var', '_wpnonce' );
		if ( empty( $_REQUEST[$nonce_var] ) || ! wp_verify_nonce( $_REQUEST[$nonce_var], 'it_cart_buddy_add_product_to_cart-' . $product_id ) )
			$error = 'product-not-added-to-cart';

		// Add product
		if ( empty( $error ) && it_cart_buddy_add_product_to_shopping_cart( $product_id ) ) {
			$url = add_query_arg( array( it_cart_buddy_get_action_var( 'alert_message' ) => 'product-added-to-cart' ) );
			wp_redirect( $url );
			die();
		}

		$error_var = it_cart_buddy_get_action_var( 'error_message' );
		$error = empty( $error ) ? 'product-not-added-to-cart' : $error;
		$url  = add_query_arg( array( $error_var => $error ), $cart );
		wp_redirect( $url );
		die();
	}

	/**
	 * Empty the Cart Buddy shopping cart
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function handle_empty_shopping_cart_request() {

		// Verify nonce
		$nonce_var   = apply_filters( 'it_cart_buddy_cart_action_nonce_var', '_wpnonce' );
		$error_var   = it_cart_buddy_get_action_var( 'error_message' );
		$message_var = it_cart_buddy_get_action_var( 'alert_message' );
		$cart        = it_cart_buddy_get_page_url( 'cart' );
		if ( empty( $_REQUEST[$nonce_var] ) 
				|| ! wp_verify_nonce( $_REQUEST[$nonce_var], 'it_cart_buddy_cart_action-' . session_id() ) 
				|| ! it_cart_buddy_empty_shopping_cart()
		) {
			$url  = add_query_arg( array( $error_var => 'cart-not-emptied' ), $cart );
			wp_redirect( $url );
			die();
		} else {
			$url = remove_query_arg( $error_var, $cart );
			$url = add_query_arg( array( $message_var => 'cart-emptied' ), $url );
			wp_redirect( $url );
			die();
		}
	}

	/**
	 * Removes a single product from the shopping cart
	 *
	 * This listens for REQUESTS to remove a product from the cart, verifies the request, and passes it along to the correct function
	 *
	 * @todo Move to /lib/framework dir
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function handle_remove_product_from_cart_request() {
		$var        = it_cart_buddy_get_action_var( 'remove_product_from_cart' );
		$product_id = empty( $_REQUEST[$var] ) ? false : $_REQUEST[$var];
		$cart_url   = it_cart_buddy_get_page_url( 'cart' );

		// Verify nonce
		$nonce_var = apply_filters( 'it_cart_buddy_remove_product_from_cart_nonce_var', '_wpnonce' );
		if ( empty( $_REQUEST[$nonce_var] ) || ! wp_verify_nonce( $_REQUEST[$nonce_var], 'it_cart_buddy_remove_product_from_cart-' . $product_id ) || ! it_cart_buddy_remove_product_from_shopping_cart( $product_id ) ) {
			$var = it_cart_buddy_get_action_var( 'error_message' );
			$url  = add_query_arg( array( $var => 'product-not-removed' ), $cart_url );
			wp_redirect( $url );
			die();
		}

		$var = it_cart_buddy_get_action_var( 'alert_message' );
		$url = add_query_arg( array( $var => 'product-removed' ), $cart_url );
		wp_redirect( $url );
		die();
	}

	/**
	 * Listens for the REQUEST to update the shopping cart, verifies it, and calls the correct function
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function handle_update_cart_request() {
		// Verify nonce
		$nonce_var = apply_filters( 'it_cart_buddy_cart_action_nonce_var', '_wpnonce' );
		if ( empty( $_REQUEST[$nonce_var] ) || ! wp_verify_nonce( $_REQUEST[$nonce_var], 'it_cart_buddy_cart_action-' . session_id() ) || ! it_cart_buddy_update_shopping_cart() ) {
			$var = it_cart_buddy_get_action_var( 'error_message' );
			$cart = it_cart_buddy_get_page_url( 'cart' );
			$url  = add_query_arg( array( $var => 'cart-not-updated' ), $cart );
			wp_redirect( $url );
			die();
		}

		$message_var = it_cart_buddy_get_action_var( 'alert_message' );
		if ( ! empty ( $message_var ) ) {
			$page = it_cart_buddy_get_page_url( 'cart' );
			$url = add_query_arg( array( $message_var => 'cart-updated' ), $page );
			wp_redirect( $url );
			die();
		}
	}

	/**
	 * Advances the user to the checkout screen after updating the cart
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function proceed_to_checkout() {

		// Update cart info
		do_action( 'it_cart_buddy_update_cart', false );

		// Redirect to Checkout
		if ( $checkout = it_cart_buddy_get_page_url( 'checkout' ) ) {
			wp_redirect( $checkout );
			die();
		}
	}

	/**
	 * Process checkout
	 *
	 * Formats data and hands it off to the appropriate tranaction method
	 *
	 * @since 0.3.8
	 * @return boolean 
	*/
	function handle_purchase_cart_request() {

		// Verify nonce
		$nonce_var = apply_filters( 'it_cart_buddy_checkout_action_nonce_var', '_wpnonce' );
		if ( empty( $_REQUEST[$nonce_var] ) || ! wp_verify_nonce( $_REQUEST[$nonce_var], 'it_cart_buddy_checkout_action-' . session_id() ) ) {
			it_cart_buddy_notify_failed_transaction( 'failed-transaction' );
			return false;
		}

		// Verify products exist
		$products = it_cart_buddy_get_cart_products();
		if ( count( $products ) < 1 ) {
			do_action( 'it_cart_buddy_error-no_products_to_purchase' );
			it_cart_buddy_notify_failed_transaction( 'no-products-in-cart' );
			return false;
		}

		// Verify transaction method exists
		$method_var = it_cart_buddy_get_action_var( 'transaction_method' );
		$requested_transaction_method = empty( $_REQUEST[$method_var] ) ? false : $_REQUEST[$method_var];
		$enabled_addons = it_cart_buddy_get_enabled_addons( array( 'category' => 'transaction-methods' ) );
		if ( ! $requested_transaction_method || empty( $enabled_addons[$requested_transaction_method] ) ) {
			do_action( 'it_cart_buddy_error-bad_transaction_method_at_purchase', $requested_transaction_method );
			it_cart_buddy_notify_failed_transaction( 'bad-transaction-method' );
			return false;
		}

		// Verify cart total is a positive number
		$cart_total = number_format( it_cart_buddy_get_cart_total(), 2);
		if ( $cart_total < 0.01 ) {
			do_action( 'it_cart_buddy_error-negative_cart_total_on_checkout', $cart_total );
			it_cart_buddy_notify_failed_transaction( 'negative-cart-total' );
			return false;
		}

		// Add subtotal to each product
		foreach( $products as $key => $product ) {
			$products[$key]['product_baseline'] = it_cart_buddy_get_cart_product_base_price( $product );
			$products[$key]['product_subtotal'] = it_cart_buddy_get_cart_product_subtotal( $product );
			$products[$key]['product_name']     = it_cart_buddy_get_cart_product_title( $product );
		}

		// Package it up and send it to the transaction method add-on
		$transaction_object = new stdClass();
		$transaction_object->products = $products;
		$transaction_object->data     = it_cart_buddy_get_cart_data();
		$transaction_object->total    = $cart_total;

		// Setup actions for success / failure
		add_action( 'it_cart_buddy_add_transaction_success-' . $requested_transaction_method, 'it_cart_buddy_empty_shopping_cart' );
		add_action( 'it_cart_buddy_add_transaction_success-' . $requested_transaction_method, 'it_cart_buddy_do_confirmation_redirect' );
		add_action( 'it_cart_buddy_add_transaction_failed-' . $requested_transaction_method, 'it_cart_buddy_notify_failed_transaction' );

		// Do the transaction
		it_cart_buddy_do_transaction( $requested_transaction_method, $transaction_object );

		// If we made it this far, the transaction failed or the transaction-method add-on did not hook into success/fail actions
		it_cart_buddy_notify_failed_transaction();
	}

	/**
	 * Updates the quantity of a product on the update_cart (and proceed to checkout) actions
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function handle_update_cart_quantity_request() {

		// Get Quantities form REQUEST
		$quantities = empty( $_POST['product_quantity'] ) ? false : (array) $_POST['product_quantity'];
		if ( ! $quantities )
			return;
		
		// Get cart products
		$cart_products = it_cart_buddy_get_session_products();

		// Update quantities
		foreach( $quantities as $product => $quantity ) {
			if ( ! empty( $cart_products[$product] ) && is_numeric( $quantity ) ) {
				$cart_product = $cart_products[$product];
				if ( empty( $quantity ) || $quantity < 1 ) {
					it_cart_buddy_remove_session_product( $product );
				} else {
					$cart_product['count'] = $quantity;
					it_cart_buddy_update_session_product( $product, $cart_product );
				}
			}
		}
	}

	/**
	 * Redirect from checkout to cart if there are no items in the cart
	 *
	 * @since 0.3.8
	 * @return void
	*/
	function redirect_checkout_if_empty_cart() {
		$cart     = it_cart_buddy_get_page_url( 'cart' );
		$checkout = it_cart_buddy_get_page_id( 'checkout' );

		if ( empty( $checkout ) || ! is_page( $checkout ) ) 
			return;

		$products = it_cart_buddy_get_cart_products();
		if ( empty( $products ) ){
			wp_redirect( $cart );
			die();
		}   
	}

	/**
	 * Register error messages used with this add-on
	 *
	 * @since 0.3.8
	 * @param array $messages existing messages
	 * @return array
	*/
	function register_cart_error_messages( $messages ) {
		$messages['bad-transaction-method'] = __( 'Please select a payment method', 'LION' );
		$messages['failed-transaction']     = __( 'There was an error processing your transaction. Please try again.', 'LION' );
		$messages['negative-cart-total']    = __( 'The cart total must be greater than 0 for you to checkout. Please try again.', 'LION' );
		$messages['no-products-in-cart']    = __( 'You cannot checkout without any items in your cart.', 'LION' );
		$messages['product-not-removed']    = __( 'Product not removed from cart. Please try again.', 'LION' );
		$messages['cart-not-emptied']       = __( 'There was an error emptying your cart. Please try again.', 'LION' );
		$messages['cart-not-updated']       = __( 'There was an error updating your cart. Please try again.', 'LION' );
		return $messages;
	}

	/**
	 * Register alert messages used with this add-on
	 *
	 * @since 0.3.8
	 * @param array $messages existing messages
	 * @return array
	*/
	function register_cart_alert_messages( $messages ) {
		$messages['cart-updated']          = __( 'Cart Updated.', 'LION' );
		$messages['cart-emptied']          = __( 'Cart Emptied', 'LION' );
		$messages['product-removed']       = __( 'Product removed from cart.', 'LION' );
		$messages['product-added-to-cart'] = __( 'Product added to cart', 'LION' );
		return $messages;
	}
}

if ( ! is_admin() ) {
	$IT_Cart_Buddy_Shopping_Cart = new IT_Cart_Buddy_Shopping_Cart();
}
