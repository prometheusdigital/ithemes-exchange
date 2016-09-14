<?php
/**
 * Load the Session model.
 *
 * @since   1.36
 * @license GPLv2
 */

/**
 * Class ITE_Session_Model
 *
 * @property string         $ID
 * @property string         $cart_id
 * @property \WP_User       $customer
 * @property array          $data
 * @property \DateTime      $expires_at
 * @property-read \DateTime $created_at
 * @property-read \DateTime $updated_at
 */
class ITE_Session_Model extends \IronBound\DB\Model {

	public function get_pk() {
		return $this->ID;
	}

	/**
	 * Retrieve a session by cart ID.
	 *
	 * @since 1.36.0
	 *
	 * @param string $cart_id
	 *
	 * @return \ITE_Session_Model|null
	 */
	public static function from_cart_id( $cart_id ) {

		$id = wp_cache_get( $cart_id, static::get_cache_group() . '-cart-id' );

		if ( ! $id ) {
			$model = self::query()->where( 'cart_id', '=', $cart_id )->first();

			if ( ! $model ) {
				return null;
			}

			wp_cache_set( $cart_id, $model->ID, static::get_cache_group() . '-cart-id' );
		} else {
			$model = static::get( $id );

			if ( ! $model || $model->cart_id !== $cart_id ) {
				wp_cache_delete( $cart_id, static::get_cache_group() . '-cart-id' );

				return static::from_cart_id( $cart_id );
			}
		}

		return $model;
	}

	protected function _access_data( $data ) {
		return $data ? unserialize( $data ) : array();
	}

	protected function _mutate_data( $data ) {
		return serialize( $data );
	}

	protected static function get_table() {
		return static::$_db_manager->get( 'ite-sessions' );
	}
}