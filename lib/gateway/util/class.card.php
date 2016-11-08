<?php
/**
 * Credit Cart Util.
 *
 * @since   1.36.0
 * @license GPLv2
 */

/**
 * Class ITE_Gateway_Card
 */
class ITE_Gateway_Card {

	/** @var string */
	private $holder_name;

	/** @var string */
	private $number;

	/** @var int */
	private $expiration_month;

	/** @var int */
	private $expiration_year;

	/** @var int */
	private $cvc;

	/**
	 * ITE_Gateway_Card constructor.
	 *
	 * @param string $number
	 * @param int    $expiration_year
	 * @param int    $expiration_month
	 * @param int    $cvc
	 * @param string $holder_name
	 */
	public function __construct( $number, $expiration_year, $expiration_month, $cvc, $holder_name = '' ) {

		if ( $expiration_year < 100 ) {
			$expiration_year += 2000;
		}

		$this->holder_name      = $holder_name;
		$this->number           = $number;
		$this->expiration_month = $expiration_month;
		$this->expiration_year  = $expiration_year;
		$this->cvc              = $cvc;
	}

	/**
	 * Get the card holder's name.
	 *
	 * @since 1.36.0
	 *
	 * @return string
	 */
	public function get_holder_name() {
		return $this->holder_name;
	}

	/**
	 * Get the card number.
	 *
	 * @since 1.36.0
	 *
	 * @return string
	 */
	public function get_number() {
		return $this->number;
	}

	/**
	 * Get the card expiration month as two digits.
	 *
	 * @since 1.36.0
	 *
	 * @return int
	 */
	public function get_expiration_month() {
		return $this->expiration_month;
	}

	/**
	 * Get the card expiration year as four digits.
	 *
	 * @since 1.36.0
	 *
	 * @return int
	 */
	public function get_expiration_year() {
		return $this->expiration_year;
	}

	/**
	 * Get the card's cvc.
	 *
	 * @since 1.36.0
	 *
	 * @return int
	 */
	public function get_cvc() {
		return $this->cvc;
	}

	/**
	 * This obscures the card number and cvc on PHP 5.6 environments.
	 *
	 * @inheritDoc
	 */
	public function __debugInfo() {
		return array(
			'holder_name'      => $this->holder_name,
			'expiration_month' => $this->expiration_month,
			'expiration_year'  => $this->expiration_year,
			'number'           => substr( $this->number, - 4 ),
		);
	}
}