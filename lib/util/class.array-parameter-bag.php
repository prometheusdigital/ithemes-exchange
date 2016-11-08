<?php
/**
 * Parameter Bag class.
 *
 * @since   1.36
 * @license GPLv2
 */

/**
 * Class ITE_Array_Parameter_Bag
 */
class ITE_Array_Parameter_Bag implements ITE_Parameter_Bag {

	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * ITE_Array_Parameter_Bag constructor.
	 *
	 * @param array $params
	 */
	public function __construct( array $params = array() ) {
		$this->params = $params;
	}
	
	/**
	 * @inheritDoc
	 */
	public function has_param( $param ) {
		return array_key_exists( $param, $this->params );
	}

	/**
	 * @inheritDoc
	 */
	public function get_param( $param ) {
		if ( ! $this->has_param( $param ) ) {
			throw new OutOfBoundsException( "No param exists for '$param'." );
		}

		return $this->params[ $param ];
	}

	/**
	 * @inheritDoc
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * @inheritDoc
	 */
	public function set_param( $param, $value ) {

		$this->params[ $param ] = $value;

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function remove_param( $param ) {

		unset( $this->params[ $param ] );

		return true;
	}
}