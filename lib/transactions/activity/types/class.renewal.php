<?php
/**
 * Contains the renewal payment activity class.
 *
 * @since   1.34
 * @license GPLv2
 */

/**
 * Class IT_Exchange_Txn_Renewal_Activity
 */
class IT_Exchange_Txn_Renewal_Activity extends IT_Exchange_Txn_AbstractActivity {

	/**
	 * Retrieve a renewal activity item.
	 *
	 * This is used by the activity factory, and should not be called directly.
	 *
	 * @since 1.34
	 *
	 * @internal
	 *
	 * @param int                            $id
	 * @param IT_Exchange_Txn_Activity_Actor $actor
	 *
	 * @return IT_Exchange_Txn_Renewal_Activity|null
	 */
	public static function make( $id, IT_Exchange_Txn_Activity_Actor $actor = null ) {

		$post = get_post( $id );

		if ( ! $post instanceof WP_Post ) {
			return null;
		}

		return new self( $post, $actor );
	}

	/**
	 * Get this activity's actor.
	 *
	 * @since 1.34
	 *
	 * @return IT_Exchange_Txn_Activity_Actor
	 */
	public function get_actor() {
		return new IT_Exchange_Txn_Activity_Customer_Actor(
			it_exchange_get_transaction_customer( $this->get_transaction() )
		);
	}

	/**
	 * Is this activity public.
	 *
	 * The customer is notified for public activities.
	 *
	 * @since 1.34
	 *
	 * @return bool
	 */
	public function is_public() {
		return false;
	}

	/**
	 * Get the activity description.
	 *
	 * This is typically 1-2 sentences.
	 *
	 * @since 1.34
	 *
	 * @return string
	 */
	public function get_description() {
		/* translators: %1$s is transaction order number, %2$s is dollar amount. */
		return sprintf( __( 'Renewal payment %1$s of %2$s.', 'it-l10n-ithemes-exchange' ),
			$this->get_transaction()->get_order_number(),
			it_exchange_get_transaction_total( $this->get_transaction() ) );
	}

	/**
	 * Get the type of the activity.
	 *
	 * @since 1.34
	 *
	 * @return string
	 */
	public function get_type() {
		return 'renewal';
	}
}