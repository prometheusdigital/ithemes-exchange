<?php
/**
 * Redirect purchase request handler.
 *
 * @since   1.36
 * @license GPLv2
 */

/**
 * Class ITE_Redirect_Purchase_Request_Handler
 */
abstract class ITE_Redirect_Purchase_Request_Handler extends ITE_Purchase_Request_Handler {
	
	/**
	 * @inheritDoc
	 */
	public function __construct( \ITE_Gateway $gateway, \ITE_Gateway_Request_Factory $factory ) {
		parent::__construct( $gateway, $factory );

		add_action( 'init', array( $this, 'maybe_redirect' ), 20 );
	}

	/**
	 * @inheritDoc
	 */
	protected function get_form_action() {
		
		if ( it_exchange_is_multi_item_cart_allowed() ) {
			return it_exchange_get_page_url( 'checkout' );
		} else {
			return get_permalink( it_exchange_get_the_product_id() );
		}
	}

	/**
	 * Maybe perform a redirect to an external payment gateway.
	 *
	 * @since 1.36
	 */
	public function maybe_redirect() {

		if ( ! isset( $_POST["{$this->gateway->get_slug()}_purchase"] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], $this->get_nonce_action() ) ) {
			it_exchange_add_message( 'error', __( 'Request expired. Please try again.', 'it-l10n-ithemes-exchange' ) );

			return;
		}

		$this->redirect( $this->factory->make( 'purchase' ) );
	}

	/**
	 * Perform the redirect to an external gateway for payment.
	 *
	 * @since 1.36
	 */
	public function redirect( ITE_Gateway_Purchase_Request $request ) {
		wp_redirect( $this->get_redirect_url( $request ) );
		die();
	}

	/**
	 * Get the redirect URL.
	 *
	 * @since 1.36
	 *
	 * @param ITE_Gateway_Purchase_Request $request
	 *
	 * @return string
	 */
	protected abstract function get_redirect_url( ITE_Gateway_Purchase_Request $request );
}