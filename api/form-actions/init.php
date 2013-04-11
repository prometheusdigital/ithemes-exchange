<?php
/**
 * These are hooks that add-ons should use for form actions
 * @since 0.3.7
 * @package IT_Exchange
*/

/**
 * Fires a WP action hook when any of the registered iThemes Exchange link / form action are set 
 *
 * @since 0.3.7
 * @reutn void
*/
function it_exchange_cart_actions() {
	
	// Fires whena a product is being added to product to a cart
	foreach( (array) it_exchange_get_action_vars() as $slug => $var ) {
		if ( isset( $_REQUEST[$var] ) ) {
			do_action( 'it_exchange_' . $slug, $_REQUEST[$var] );
		}
	}
}
add_action( 'template_redirect', 'it_exchange_cart_actions' );

/**
 * Returns an action var used in links and forms
 *
 * @since 0.3.7
 * @param string $var var being requested
 * @return string var used in links / forms for different actions
*/
function it_exchange_get_action_var( $var ) {
	$vars = it_exchange_get_action_vars();
	$value  = empty( $vars[$var] ) ? false : $vars[$var];
	return apply_filters( 'it_exchange_get_action_var', $value, $var );
}

/**
 * Returns an array of all action vars registered with iThemes Exchange
 *
 * @since 0.3.7
 * @return array
*/
function it_exchange_get_action_vars() {
	// Default vars
	$defaults = array(
		'add_product_to_cart'      => 'it_exchange_add_product_to_cart',
		'remove_product_from_cart' => 'it_exchange_remove_product_from_cart',
		'update_cart_action'       => 'it_exchange_update_cart_request',
		'empty_cart'               => 'it_exchange_empty_cart',
		'proceed_to_checkout'      => 'it_exchange_proceed_to_checkout',
		'view_cart'                => 'it_exchange_view_cart',
		'purchase_cart'            => 'it_exchange_purchase_cart',
		'alert_message'            => 'it_exchange_messages',
		'error_message'            => 'it_exchange_errors',
		'transaction_id'           => 'it_exchange_transaction_id',
		'transaction_method'       => 'it_exchange_transaction_method',
	);
	return apply_filters( 'it_exchange_get_action_vars', $defaults );
}
