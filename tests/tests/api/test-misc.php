<?php
/**
 * Contains tests for the misc API functions.
 *
 * @since   1.35
 * @license GPLv2
 */

/**
 * Class IT_Exchange_API_Misc_Test
 *
 * @group misc-api
 */
class IT_Exchange_API_Misc_Test extends IT_Exchange_UnitTestCase {

	public function _dp_convert_to_database_number_usd_before_comma_per() {
		return array(
			array( '$2.00', 200 ),
			array( '$2.50', 250 ),
			array( '$20.00', 2000 ),
			array( '$20.40', 2040 ),
			array( '$200.00', 20000 ),
			array( '$200.22', 20022 ),
			array( '$2,000.00', 200000 ),
			array( '$2,100.00', 210000 ),
			array( '$2,150.00', 215000 ),
			array( '$2,153.00', 215300 ),
			array( '$2,153.22', 215322 ),
			array( '$2,153.02', 215302 ),
			array( '$2,230,153.02', 223015302 ),
			array( 2230153.02, 223015302 ),
			array( '2230153.02', 223015302 ),
			array( '2230153', 223015300 ),
			array( 2230153, 223015300 )
		);
	}

	/**
	 * @dataProvider _dp_convert_to_database_number_usd_before_comma_per
	 */
	public function test_convert_to_database_number_usd_before_comma_per( $price, $expected ) {

		add_filter( 'it_exchange_get_option-settings_general', function () {
			return array(
				'default-currency'             => 'USD',
				'currency-symbol-position'     => 'before',
				'currency-thousands-separator' => ',',
				'currency-decimals-separator'  => '.',
			);
		} );

		$this->assertEquals( $expected, it_exchange_convert_to_database_number( $price ) );
	}

	public function _dp_convert_to_database_number_usd_after_comma_per() {
		return array(
			array( '2.00$', 200 ),
			array( '2.50$', 250 ),
			array( '20.00$', 2000 ),
			array( '20.40$', 2040 ),
			array( '200.00$', 20000 ),
			array( '200.22$', 20022 ),
			array( '2,000.00$', 200000 ),
			array( '2,100.00$', 210000 ),
			array( '2,150.00$', 215000 ),
			array( '2,153.00$', 215300 ),
			array( '2,153.22$', 215322 ),
			array( '2,153.02$', 215302 ),
			array( '2,230,153.02$', 223015302 ),
			array( 2230153.02, 223015302 ),
			array( '2230153.02', 223015302 ),
			array( '2230153', 223015300 ),
			array( 2230153, 223015300 )
		);
	}

	/**
	 * @dataProvider _dp_convert_to_database_number_usd_after_comma_per
	 */
	public function test_convert_to_database_number_usd_after_comma_per( $price, $expected ) {

		add_filter( 'it_exchange_get_option-settings_general', function () {
			return array(
				'default-currency'             => 'USD',
				'currency-symbol-position'     => 'after',
				'currency-thousands-separator' => ',',
				'currency-decimals-separator'  => '.',
			);
		} );

		$this->assertEquals( $expected, it_exchange_convert_to_database_number( $price ) );
	}

	public function _dp_convert_to_database_number_usd_before_per_comma() {
		return array(
			array( '$2,00', 200 ),
			array( '$2,50', 250 ),
			array( '$20,00', 2000 ),
			array( '$20,40', 2040 ),
			array( '$200,00', 20000 ),
			array( '$200,22', 20022 ),
			array( '$2.000,00', 200000 ),
			array( '$2.100,00', 210000 ),
			array( '$2.150,00', 215000 ),
			array( '$2.153,00', 215300 ),
			array( '$2.153,22', 215322 ),
			array( '$2.153,02', 215302 ),
			array( '$2.230.153,02', 223015302 ),
			array( '2230153,02', 223015302 ),
			array( '2230153', 223015300 ),
			array( 2230153, 223015300 )
		);
	}

	/**
	 * @dataProvider _dp_convert_to_database_number_usd_before_per_comma
	 */
	public function test_convert_to_database_number_usd_before_per_comma( $price, $expected ) {

		add_filter( 'it_exchange_get_option-settings_general', function () {
			return array(
				'default-currency'             => 'USD',
				'currency-symbol-position'     => 'before',
				'currency-thousands-separator' => '.',
				'currency-decimals-separator'  => ',',
			);
		} );

		$this->assertEquals( $expected, it_exchange_convert_to_database_number( $price ) );
	}

	public function _dp_convert_to_database_number_usd_after_per_comma() {
		return array(
			array( '2,00$', 200 ),
			array( '2,50$', 250 ),
			array( '20,00$', 2000 ),
			array( '20,40$', 2040 ),
			array( '200,00$', 20000 ),
			array( '200,22$', 20022 ),
			array( '2.000,00$', 200000 ),
			array( '2.100,00$', 210000 ),
			array( '2.150,00$', 215000 ),
			array( '2.153,00$', 215300 ),
			array( '2.153,22$', 215322 ),
			array( '2.153,02$', 215302 ),
			array( '2.230.153,02$', 223015302 ),
			array( '2230153,02', 223015302 ),
			array( '2230153', 223015300 ),
			array( 2230153, 223015300 )
		);
	}

	/**
	 * @dataProvider _dp_convert_to_database_number_usd_after_per_comma
	 */
	public function test_convert_to_database_number_usd_after_per_comma( $price, $expected ) {

		add_filter( 'it_exchange_get_option-settings_general', function () {
			return array(
				'default-currency'             => 'USD',
				'currency-symbol-position'     => 'after',
				'currency-thousands-separator' => '.',
				'currency-decimals-separator'  => ',',
			);
		} );

		$this->assertEquals( $expected, it_exchange_convert_to_database_number( $price ) );
	}

	public function _dp_convert_to_database_number_inr_before_comma_per() {
		return array(
			array( '₉2.00', 200 ),
			array( '₉2.50', 250 ),
			array( '₉20.00', 2000 ),
			array( '₉20.40', 2040 ),
			array( '₉200.00', 20000 ),
			array( '₉200.22', 20022 ),
			array( '₉2,000.00', 200000 ),
			array( '₉2,100.00', 210000 ),
			array( '₉2,150.00', 215000 ),
			array( '₉2,153.00', 215300 ),
			array( '₉2,153.22', 215322 ),
			array( '₉2,153.02', 215302 ),
			array( '₉2,230,153.02', 223015302 ),
			array( 2230153.02, 223015302 ),
			array( '2230153.02', 223015302 ),
			array( '2230153', 223015300 ),
			array( 2230153, 223015300 )
		);
	}

	/**
	 * @dataProvider _dp_convert_to_database_number_inr_before_comma_per
	 */
	public function test_convert_to_database_number_inr_before_comma_per( $price, $expected ) {

		add_filter( 'it_exchange_get_option-settings_general', function () {
			return array(
				'default-currency'             => 'inr',
				'currency-symbol-position'     => 'before',
				'currency-thousands-separator' => ',',
				'currency-decimals-separator'  => '.',
			);
		} );

		$this->assertEquals( $expected, it_exchange_convert_to_database_number( $price ) );
	}

	public function _dp_convert_to_database_number_inr_after_comma_per() {
		return array(
			array( '2.00₉', 200 ),
			array( '2.50₉', 250 ),
			array( '20.00₉', 2000 ),
			array( '20.40₉', 2040 ),
			array( '200.00₉', 20000 ),
			array( '200.22₉', 20022 ),
			array( '2,000.00₉', 200000 ),
			array( '2,100.00₉', 210000 ),
			array( '2,150.00₉', 215000 ),
			array( '2,153.00₉', 215300 ),
			array( '2,153.22₉', 215322 ),
			array( '2,153.02₉', 215302 ),
			array( '2,230,153.02₉', 223015302 ),
			array( 2230153.02, 223015302 ),
			array( '2230153.02', 223015302 ),
			array( '2230153', 223015300 ),
			array( 2230153, 223015300 )
		);
	}

	/**
	 * @dataProvider _dp_convert_to_database_number_inr_after_comma_per
	 */
	public function test_convert_to_database_number_inr_after_comma_per( $price, $expected ) {

		add_filter( 'it_exchange_get_option-settings_general', function () {
			return array(
				'default-currency'             => 'inr',
				'currency-symbol-position'     => 'after',
				'currency-thousands-separator' => ',',
				'currency-decimals-separator'  => '.',
			);
		} );

		$this->assertEquals( $expected, it_exchange_convert_to_database_number( $price ) );
	}

	public function _dp_convert_to_database_number_inr_before_per_comma() {
		return array(
			array( '₉2,00', 200 ),
			array( '₉2,50', 250 ),
			array( '₉20,00', 2000 ),
			array( '₉20,40', 2040 ),
			array( '₉200,00', 20000 ),
			array( '₉200,22', 20022 ),
			array( '₉2.000,00', 200000 ),
			array( '₉2.100,00', 210000 ),
			array( '₉2.150,00', 215000 ),
			array( '₉2.153,00', 215300 ),
			array( '₉2.153,22', 215322 ),
			array( '₉2.153,02', 215302 ),
			array( '₉2.230.153,02', 223015302 ),
			array( '2230153,02', 223015302 ),
			array( '2230153', 223015300 ),
			array( 2230153, 223015300 )
		);
	}

	/**
	 * @dataProvider _dp_convert_to_database_number_inr_before_per_comma
	 */
	public function test_convert_to_database_number_inr_before_per_comma( $price, $expected ) {

		add_filter( 'it_exchange_get_option-settings_general', function () {
			return array(
				'default-currency'             => 'inr',
				'currency-symbol-position'     => 'before',
				'currency-thousands-separator' => '.',
				'currency-decimals-separator'  => ',',
			);
		} );

		$this->assertEquals( $expected, it_exchange_convert_to_database_number( $price ) );
	}

	public function _dp_convert_to_database_number_inr_after_per_comma() {
		return array(
			array( '2,00₉', 200 ),
			array( '2,50₉', 250 ),
			array( '20,00₉', 2000 ),
			array( '20,40₉', 2040 ),
			array( '200,00₉', 20000 ),
			array( '200,22₉', 20022 ),
			array( '2.000,00₉', 200000 ),
			array( '2.100,00₉', 210000 ),
			array( '2.150,00₉', 215000 ),
			array( '2.153,00₉', 215300 ),
			array( '2.153,22₉', 215322 ),
			array( '2.153,02₉', 215302 ),
			array( '2.230.153,02₉', 223015302 ),
			array( '2230153,02', 223015302 ),
			array( '2230153', 223015300 ),
			array( 2230153, 223015300 )
		);
	}

	/**
	 * @dataProvider _dp_convert_to_database_number_inr_after_per_comma
	 */
	public function test_convert_to_database_number_inr_after_per_comma( $price, $expected ) {

		add_filter( 'it_exchange_get_option-settings_general', function () {
			return array(
				'default-currency'             => 'inr',
				'currency-symbol-position'     => 'after',
				'currency-thousands-separator' => '.',
				'currency-decimals-separator'  => ',',
			);
		} );

		$this->assertEquals( $expected, it_exchange_convert_to_database_number( $price ) );
	}

	public function _dp_convert_from_database_number() {
		return array(
			array( 1, 0.01 ),
			array( 002, 0.02 ),
			array( 10, 0.10 ),
			array( 100, 1.00 ),
			array( 102, 1.02 ),
			array( 123, 1.23 ),
			array( 1200, 12.00 ),
			array( 1234, 12.34 ),
			array( 12300, 123.00 ),
			array( 12345, 123.45 ),
			array( 12300, 123.00 ),
			array( 123400, 1234.00 ),
			array( 123456, 1234.56 ),
			array( 1234500, 12345.00 ),
			array( 1234567, 12345.67 )
		);
	}

	/**
	 * @dataProvider _dp_convert_from_database_number
	 */
	public function test_convert_from_database_number( $db_num, $expected ) {
		$this->assertEquals( $expected, it_exchange_convert_from_database_number( $db_num ) );
	}
}