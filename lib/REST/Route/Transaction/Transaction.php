<?php
/**
 * Transaction Endpoint.
 *
 * @since   2.0.0
 * @license GPLv2
 */

namespace iThemes\Exchange\REST\Route\Transaction;

use iThemes\Exchange\REST\Getable;
use iThemes\Exchange\REST\Postable;
use iThemes\Exchange\REST\Putable;
use iThemes\Exchange\REST\Request;
use iThemes\Exchange\REST\Route\Base;

/**
 * Class Transaction
 *
 * @package iThemes\Exchange\REST\Route\Transaction
 */
class Transaction extends Base implements Getable, Putable {

	/** @var Serializer */
	private $serializer;

	/**
	 * Transaction constructor.
	 *
	 * @param \iThemes\Exchange\REST\Route\Transaction\Serializer $serializer
	 */
	public function __construct( Serializer $serializer ) { $this->serializer = $serializer; }

	/**
	 * @inheritDoc
	 */
	public function handle_get( Request $request ) {

		$t        = it_exchange_get_transaction( $request->get_param( 'transaction_id', 'URL' ) );
		$user     = it_exchange_get_current_customer();
		$response = new \WP_REST_Response( $this->serializer->serialize( $t, $user ) );

		foreach ( $this->serializer->generate_links( $t, $this->get_manager(), $user ) as $rel => $links ) {
			foreach ( $links as $link ) {
				$response->add_link( $rel, $link['href'], $link );
			}
		}

		return $response;
	}

	/**
	 * @inheritDoc
	 */
	public function user_can_get( Request $request, \IT_Exchange_Customer $user = null ) {

		$cap = $request['context'] === 'view' ? 'read_it_transaction' : 'edit_it_transaction';

		if ( ! $user || ! user_can( $user->wp_user, $cap, $request->get_param( 'transaction_id', 'URL' ) ) ) {
			return new \WP_Error(
				'it_exchange_rest_forbidden_context',
				__( 'Sorry, you are not allowed to access this transaction.', 'it-l10n-ithemes-exchange' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function handle_put( Request $request ) {

		$t = it_exchange_get_transaction( $request->get_param( 'transaction_id', 'URL' ) );

		if ( isset( $request['status'] ) ) {
			$t->update_status( is_array( $request['status'] ) ? $request['status']['slug'] : $request['status'] );
		}

		$user     = it_exchange_get_current_customer();
		$response = new \WP_REST_Response( $this->serializer->serialize( $t, $user ) );

		foreach ( $this->serializer->generate_links( $t, $this->get_manager(), $user ) as $rel => $links ) {
			foreach ( $links as $link ) {
				$response->add_link( $rel, $link['href'], $link );
			}
		}

		return $response;

	}

	/**
	 * @inheritDoc
	 */
	public function user_can_put( Request $request, \IT_Exchange_Customer $user = null ) {

		if ( ! $user || ! user_can( $user->wp_user, 'edit_it_transaction', $request->get_param( 'transaction_id', 'URL' ) ) ) {
			return new \WP_Error(
				'it_exchange_rest_forbidden_context',
				__( 'Sorry, you are not allowed to access this transaction.', 'it-l10n-ithemes-exchange' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function get_version() { return 1; }

	/**
	 * @inheritDoc
	 */
	public function get_path() { return '(?P<transaction_id>\d+)/'; }

	/**
	 * @inheritDoc
	 */
	public function get_query_args() { return array(); }

	/**
	 * @inheritDoc
	 */
	public function get_schema() { return $this->serializer->get_schema(); }
}
