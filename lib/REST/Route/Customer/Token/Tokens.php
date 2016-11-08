<?php
/**
 * Tokens route.
 *
 * @since   1.36.0
 * @license GPLv2
 */

namespace iThemes\Exchange\REST\Route\Customer\Token;

use iThemes\Exchange\REST\Getable;
use iThemes\Exchange\REST\Postable;
use iThemes\Exchange\REST\Request;
use iThemes\Exchange\REST\Route\Base;

/**
 * Class Tokens
 *
 * @package iThemes\Exchange\REST\Customer\Token
 */
class Tokens extends Base implements Getable, Postable {

	/** @var Serializer */
	private $serializer;

	/** @var \ITE_Gateway_Request_Factory */
	private $request_factory;

	/**
	 * Tokens constructor.
	 *
	 * @param \iThemes\Exchange\REST\Route\Customer\Token\Serializer $serializer
	 * @param \ITE_Gateway_Request_Factory                           $request_factory
	 */
	public function __construct( Serializer $serializer, \ITE_Gateway_Request_Factory $request_factory ) {
		$this->serializer      = $serializer;
		$this->request_factory = $request_factory;
	}

	/**
	 * @inheritDoc
	 */
	public function handle_get( Request $request ) {

		$customer = it_exchange_get_customer( $request->get_param( 'customer_id', 'URL' ) );

		$tokens = $customer->get_tokens( $request['gateway'] );
		$data   = array_map( array( $this->serializer, 'serialize' ), $tokens->getValues() );

		return new \WP_REST_Response( $data );
	}

	/**
	 * @inheritDoc
	 */
	public function user_can_get( Request $request, \IT_Exchange_Customer $user = null ) {
		if ( ( $r = $this->permissions_check( $request, $user ) ) !== true ) {
			return $r;
		}

		if ( ! user_can( $user->wp_user, 'it_list_payment_tokens', $request->get_param( 'customer_id', 'URL' ) ) ) {
			return new \WP_Error(
				'it_exchange_rest_forbidden_context',
				__( "Sorry, you are not allowed to view this customer's payment tokens.", 'it-l10n-ithemes-exchange' ),
				array( 'status' => \WP_Http::FORBIDDEN )
			);
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function handle_post( Request $request ) {

		$gateway = \ITE_Gateways::get( $request['gateway'] );

		if ( ! $gateway || ! $gateway->can_handle( 'tokenize' ) ) {
			return new \WP_Error(
				'it_exchange_rest_invalid_gateway',
				__( 'Invalid gateway.', 'it-l10n-ithemes-exchange' ),
				array( 'status' => \WP_Http::BAD_REQUEST )
			);
		}

		$tokenize = $this->request_factory->make( 'tokenize', array(
			'customer' => $request['customer_id'],
			'source'   => $request['source'],
			'label'    => $request['label'],
			'primary'  => $request['primary'],
		) );

		$token = $gateway->get_handler_for( $tokenize )->handle( $tokenize );

		return new \WP_REST_Response( $this->serializer->serialize( $token ) );
	}

	/**
	 * @inheritDoc
	 */
	public function user_can_post( Request $request, \IT_Exchange_Customer $user = null ) {
		if ( ( $r = $this->permissions_check( $request, $user ) ) !== true ) {
			return $r;
		}

		if ( ! user_can( $user->wp_user, 'it_create_payment_tokens', $request->get_param( 'customer_id', 'URL' ) ) ) {
			return new \WP_Error(
				'it_exchange_rest_forbidden_context',
				__( 'Sorry, you are not allowed to create payment tokens for this customer.', 'it-l10n-ithemes-exchange' ),
				array( 'status' => \WP_Http::FORBIDDEN )
			);
		}

		return true;
	}

	/**
	 * Perform a permissions check.
	 *
	 * @since 1.36.0
	 *
	 * @param \iThemes\Exchange\REST\Request $request
	 * @param \IT_Exchange_Customer|null     $user
	 *
	 * @return bool|\WP_Error
	 */
	protected function permissions_check( Request $request, \IT_Exchange_Customer $user = null ) {

		if ( ! $user || $user instanceof \IT_Exchange_Guest_Customer ) {
			return new \WP_Error(
				'it_exchange_rest_forbidden_context',
				__( 'Sorry, you are not allowed to access this customer.', 'it-l10n-ithemes-exchange' ),
				array( 'status' => \WP_Http::UNAUTHORIZED )
			);
		}

		$customer = it_exchange_get_customer( $request->get_param( 'customer_id', 'URL' ) );

		if ( ! $customer ) {
			return new \WP_Error(
				'it_exchange_rest_invalid_customer',
				__( 'Invalid customer.', 'it-l10n-ithemes-exchange' ),
				array( 'status' => \WP_Http::NOT_FOUND )
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
	public function get_path() { return 'tokens/'; }

	/**
	 * @inheritDoc
	 */
	public function get_query_args() {
		return array(
			'context' => array(
				'description' => __( 'Scope under which the request is made; determines fields present in response.', 'it-l10n-ithemes-exchange' ),
				'type'        => 'string',
				'default'     => 'view',
				'enum'        => array( 'view', 'edit' )
			),
			'gateway' => array(
				'description' => __( 'Gateway the payment token belongs to.', 'it-l10n-ithemes-exchange' ),
				'type'        => 'string',
				'enum'        => array_map( function ( $gateway ) { return $gateway->get_slug(); }, \ITE_Gateways::handles( 'tokenize' ) ),
			),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function get_schema() { return $this->serializer->get_schema(); }
}