<?php
/**
 * Gateway API class.
 *
 * @since   1.36
 * @license GPLv2
 */

/**
 * Class ITE_Gateway
 */
abstract class ITE_Gateway {

	/**
	 * Get the name of the gateway.
	 *
	 * @since 1.36
	 *
	 * @return string
	 */
	public abstract function get_name();

	/**
	 * Get the gateway slug.
	 *
	 * @since 1.36
	 *
	 * @return string
	 */
	public abstract function get_slug();

	/**
	 * Get the add-on slug.
	 *
	 * @since 1.36.0
	 *
	 * @return string
	 */
	public abstract function get_addon();

	/**
	 * Get the request handlers this gateway provides.
	 *
	 * @since 1.36
	 *
	 * @return ITE_Gateway_Request_Handler[]
	 */
	public abstract function get_handlers();

	/**
	 * Get the handler for a given request.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Gateway_Request $request
	 *
	 * @return \ITE_Gateway_Request_Handler|null
	 */
	public function get_handler_for( ITE_Gateway_Request $request ) {
		foreach ( $this->get_handlers() as $handler ) {
			if ( $handler::can_handle( $request::get_name() ) ) {
				return $handler;
			}
		}

		return null;
	}

	/**
	 * Can the gateway handle a given request.
	 *
	 * @since 1.36.0
	 *
	 * @param string $request_name
	 *
	 * @return bool
	 */
	final public function can_handle( $request_name ) {
		foreach ( $this->get_handlers() as $handler ) {
			if ( $handler::can_handle( $request_name ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Is the gateway in sandbox mode.
	 *
	 * @since 1.36
	 *
	 * @return bool
	 */
	public abstract function is_sandbox_mode();

	/**
	 * Get the webhook param name.
	 *
	 * @since 1.36
	 *
	 * @return string
	 */
	public abstract function get_webhook_param();

	/**
	 * Get settings fields configuration.
	 *
	 * @since 1.36
	 *
	 * @return array
	 */
	protected abstract function get_settings_fields();

	/**
	 * Get the settings form controller.
	 *
	 * @since 1.36
	 *
	 * @return IT_Exchange_Admin_Settings_Form
	 */
	public function get_settings_form() {
		return new IT_Exchange_Admin_Settings_Form( array(
			'form-fields' => $this->get_settings_fields(),
			'prefix'      => $this->get_settings_name(),
		) );
	}

	/**
	 * Get the name of the settings key for `it_exchange_get_option()`.
	 *
	 * @since 1.36
	 *
	 * @return string
	 */
	protected abstract function get_settings_name();

	/**
	 * Retrieve the settings controller for this gateway.
	 *
	 * @since 1.36
	 *
	 * @return ITE_Settings_Controller
	 */
	public function settings() {
		return new ITE_Settings_Controller( $this->get_settings_name() );
	}
}