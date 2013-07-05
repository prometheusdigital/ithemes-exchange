<?php
/**
 * These are hooks that add-ons should use for form actions
 * @since 0.3.7
 * @package IT_Exchange
*/

/**
 * Pass a PHP date format string to this function to return its jQuery datepicker equivalent
 *
 * @since 0.4.16
 * @param string $date_format PHP Date Format
 * @return string jQuery datePicker Format
*/
function it_exchange_php_date_format_to_jquery_datepicker_format( $date_format ) {
	
	//http://us2.php.net/manual/en/function.date.php
	//http://api.jqueryui.com/datepicker/#utility-formatDate
	$php_format = array(
		//day
		'/d/', //Day of the month, 2 digits with leading zeros
		'/D/', //A textual representation of a day, three letters
		'/j/', //Day of the month without leading zeros
		'/l/', //A full textual representation of the day of the week
		//'/N/', //ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)
		//'/S/', //English ordinal suffix for the day of the month, 2 characters
		//'/w/', //Numeric representation of the day of the week
		'/z/', //The day of the year (starting from 0)
		
		//week
		//'/W/', //ISO-8601 week number of year, weeks starting on Monday (added in PHP 4.1.0)
		
		//month
		'/F/', //A full textual representation of a month, such as January or March
		'/m/', //Numeric representation of a month, with leading zeros
		'/M/', //A short textual representation of a month, three letters
		'/n/', //numeric month no leading zeros
		//'t/', //Number of days in the given month
		
		//year
		//'/L/', //Whether it's a leap year
		//'/o/', //ISO-8601 year number. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead. (added in PHP 5.1.0)
		'/Y/', //A full numeric representation of a year, 4 digits
		'/y/', //A two digit representation of a year
	);
	
	$datepicker_format = array(
		//day
		'dd', //day of month (two digit)
		'D',  //day name short
		'd',  //day of month (no leading zero)
		'DD', //day name long
		//'',   //N - Equivalent does not exist in datePicker
		//'',   //S - Equivalent does not exist in datePicker
		//'',   //w - Equivalent does not exist in datePicker
		'z' => 'o',  //The day of the year (starting from 0)
		
		//week
		//'',   //W - Equivalent does not exist in datePicker
		
		//month
		'MM', //month name long
		'mm', //month of year (two digit)
		'M',  //month name short
		'm',  //month of year (no leading zero)
		//'',   //t - Equivalent does not exist in datePicker
		
		//year
		//'',   //L - Equivalent does not exist in datePicker
		//'',   //o - Equivalent does not exist in datePicker
		'yy', //year (four digit)
		'y',  //month name long
	);
	
	return preg_replace( $php_format, $datepicker_format, preg_quote( $date_format ) );
}

/**
 * Returns an integer value of the price passed
 *
 * @since 0.4.16
 * @param string|int|float price to convert to database integer
 * @return string|int converted price
*/
function it_exchange_convert_to_database_number( $price ) {
	$settings = it_exchange_get_option( 'settings_general' );
	$sep = $settings['currency-decimals-separator'];
	
	$price = trim( $price );
			
	if ( strstr( $price, $sep ) )
		$price = preg_replace("/[^0-9]*/", '', $price );
	else //if we don't find a decimal separator, we want to multiply by 100 for future decimal operations
		$price = preg_replace("/[^0-9]*/", '', $price ) * 100;
		
	return $price;
}

/**
 * Returns a float value of the price passed from database
 *
 * @since 0.4.16
 * @param string|int price from database integer
 * @return float converted price
*/
function it_exchange_convert_from_database_number( $price ) {
	return $price /= 100;
}

/**
 * Returns a field name used in links and forms
 *
 * @since 0.4.0
 * @param string $var var being requested
 * @return string var used in links / forms for different actions
*/
function it_exchange_get_field_name( $var ) {
	$field_names = it_exchange_get_field_names();
	$field_name = empty( $field_names[$var] ) ? false : $field_names[$var];
	return apply_filters( 'it_exchange_get_field_name', $field_name, $var );
}

/**
 * Returns an array of all field names registered with iThemes Exchange
 *
 * @since 0.4.0
 * @return array
*/
function it_exchange_get_field_names() {
	// required field names
	$required = array(
		'add_product_to_cart'      => 'it-exchange-add-product-to-cart',
		'buy_now'                  => 'it-exchange-buy-now',
		'remove_product_from_cart' => 'it-exchange-remove-product-from-cart',
		'update_cart_action'       => 'it-exchange-update-cart-request',
		'empty_cart'               => 'it-exchange-empty-cart',
		'proceed_to_checkout'      => 'it-exchange-proceed-to-checkout',
		'view_cart'                => 'it-exchange-view-cart',
		'purchase_cart'            => 'it-exchange-purchase-cart',
		'alert_message'            => 'it-exchange-messages',
		'error_message'            => 'it-exchange-errors',
		'transaction_id'           => 'it-exchange-transaction-id',
		'transaction_method'       => 'it-exchange-transaction-method',
		'sw_cart_focus'            => 'ite-sw-cart-focus',
		'sw_ajax_call'             => 'it-exchange-sw-ajax',
		'sw_ajax_action'           => 'sw-action',
		'sw_ajax_product'          => 'sw-product',
		'sw_ajax_quantity'         => 'sw-quantity',
	);
	//We don't want users to modify the core vars, but we should let them add new ones.
	return apply_filters( 'it_exchange_get_field_names', array_merge( $required, apply_filters( 'it_exchange_default_field_names', array() ) ) );
}

/**
 * Grabs the current URL, removes all registerd exchange query_args from it
 *
 * Exempts args in first paramater
 * Cleans additional args in second paramater
 *
 * @since 0.4.0
 *
 * @param array $exempt optional array of query args not to clean
 * @param array $additional opitonal array of params to clean even if not found in register params
 * @return string
*/
function it_exchange_clean_query_args( $exempt=array(), $additional=array() ) {
	// Get registered
	$registered = array_values( (array) it_exchange_get_field_names() );
	$registered = array_merge( $registered, (array) array_values( $additional ) );

	// Additional args
	$registered[] = '_wpnonce';
	$registered[] = apply_filters( 'it_exchange_purchase_product_nonce_var' , '_wpnonce' );
	$registered[] = apply_filters( 'it_exchange_cart_action_nonce_var' , '_wpnonce' );
	$registered[] = apply_filters( 'it_exchange_remove_product_from_cart_nonce_var' , '_wpnonce' );
	$registered[] = apply_filters( 'it_exchange_checkout_action_nonce_var' , '_wpnonce' );
	$registered[] = 'it-exchange-basic-coupons-remove-coupon-cart';

	$registered = array_unique( $registered );

	$url = false;
	foreach( $registered as $key => $param ) {
		if ( ! in_array( $param, $exempt ) )
			$url = remove_query_arg( $param, $url );
	}

	return apply_filters( 'it_exchange_clean_query_args', $url );
}

/**
 * Replace Log in text with Log out text in nav menus
 *
 * @since 0.4.0
 *
 * @param string $page page setting
 * @return string url
*/
function it_exchange_wp_get_nav_menu_items_filter( $items, $menu, $args ) {
	if ( is_user_logged_in() ) {
		foreach ( $items as $item ) {
			if ( $item->url == it_exchange_get_page_url( 'login' ) ) {

				$item->url = it_exchange_get_page_url( 'logout' );
				$item->title = it_exchange_get_page_name( 'logout' );
			}
		}
	}
	return apply_filters( 'it_exchange_wp_get_nav_menu_items_filter', $items, $menu, $args );

}
add_filter( 'wp_get_nav_menu_items', 'it_exchange_wp_get_nav_menu_items_filter', 10, 3 );

if ( ! function_exists( 'wp_nav_menu_disabled_check' ) && version_compare( $GLOBALS['wp_version'], '3.5.3', '<=' ) ) {

	/**
	 * From WordPress 3.6.0 for back-compat
	 * Check whether to disable the Menu Locations meta box submit button
	 *
	 * @since 0.4.0
	 *
	 * @uses global $one_theme_location_no_menus to determine if no menus exist
	 * @uses disabled() to output the disabled attribute in $other_attributes param in submit_button()
	 *
	 * @param int|string $nav_menu_selected_id (id, name or slug) of the currently-selected menu
	 * @return string Disabled attribute if at least one menu exists, false if not
	*/
	function wp_nav_menu_disabled_check( $nav_menu_selected_id ) {
		global $one_theme_location_no_menus;

		if ( $one_theme_location_no_menus )
			return false;

		return disabled( $nav_menu_selected_id, 0 );
	}

}

/**
 * Returns currency data
 *
 * @since 0.3.4
*/
function it_exchange_get_currency_options() {
	// Country Code => array( symbol, name )
	$currencies = array(
		'AED' => array( 'symbol' => '&#1583;.&#1573;', 'name' => __( 'UAE dirham', 'LION' ) ),
		'AFN' => array( 'symbol' => 'Afs', 'name' => __( 'Afghan afghani', 'LION' ) ),
		'ALL' => array( 'symbol' => 'L', 'name' => __( 'Albanian lek', 'LION' ) ),
		'AMD' => array( 'symbol' => 'AMD', 'name' => __( 'Armenian dram', 'LION' ) ),
		'ANG' => array( 'symbol' => 'NA&#402;', 'name' => __( 'Netherlands Antillean gulden', 'LION' ) ),
		'AOA' => array( 'symbol' => 'Kz', 'name' => __( 'Angolan kwanza', 'LION' ) ),
		'ARS' => array( 'symbol' => '$', 'name' => __( 'Argentine peso', 'LION' ) ),
		'AUD' => array( 'symbol' => '$', 'name' => __( 'Australian dollar', 'LION' ) ),
		'AWG' => array( 'symbol' => '&#402;', 'name' => __( 'Aruban florin', 'LION' ) ),
		'AZN' => array( 'symbol' => 'AZN', 'name' => __( 'Azerbaijani manat', 'LION' ) ),
		'BAM' => array( 'symbol' => 'KM', 'name' => __( 'Bosnia and Herzegovina konvertibilna marka', 'LION' ) ),
		'BBD' => array( 'symbol' => 'Bds$', 'name' => __( 'Barbadian dollar', 'LION' ) ),
		'BDT' => array( 'symbol' => '&#2547;', 'name' => __( 'Bangladeshi taka', 'LION' ) ),
		'BGN' => array( 'symbol' => 'BGN', 'name' => __( 'Bulgarian lev', 'LION' ) ),
		'BHD' => array( 'symbol' => '.&#1583;.&#1576;', 'name' => __( 'Bahraini dinar', 'LION' ) ),
		'BIF' => array( 'symbol' => 'FBu', 'name' => __( 'Burundi franc', 'LION' ) ),
		'BMD' => array( 'symbol' => 'BD$', 'name' => __( 'Bermudian dollar', 'LION' ) ),
		'BND' => array( 'symbol' => 'B$', 'name' => __( 'Brunei dollar', 'LION' ) ),
		'BOB' => array( 'symbol' => 'Bs.', 'name' => __( 'Bolivian boliviano', 'LION' ) ),
		'BRL' => array( 'symbol' => 'R$', 'name' => __( 'Brazilian real', 'LION' ) ),
		'BSD' => array( 'symbol' => 'B$', 'name' => __( 'Bahamian dollar', 'LION' ) ),
		'BTN' => array( 'symbol' => 'Nu.', 'name' => __( 'Bhutanese ngultrum', 'LION' ) ),
		'BWP' => array( 'symbol' => 'P', 'name' => __( 'Botswana pula', 'LION' ) ),
		'BYR' => array( 'symbol' => 'Br', 'name' => __( 'Belarusian ruble', 'LION' ) ),
		'BZD' => array( 'symbol' => 'BZ$', 'name' => __( 'Belize dollar', 'LION' ) ),
		'CAD' => array( 'symbol' => '$', 'name' => __( 'Canadian dollar', 'LION' ) ),
		'CDF' => array( 'symbol' => 'F', 'name' => __( 'Congolese franc', 'LION' ) ),
		'CHF' => array( 'symbol' => 'Fr.', 'name' => __( 'Swiss franc', 'LION' ) ),
		'CLP' => array( 'symbol' => '$', 'name' => __( 'Chilean peso', 'LION' ) ),
		'CNY' => array( 'symbol' => '&#165;', 'name' => __( 'Chinese/Yuan renminbi', 'LION' ) ),
		'COP' => array( 'symbol' => 'Col$', 'name' => __( 'Colombian peso', 'LION' ) ),
		'CRC' => array( 'symbol' => '&#8353;', 'name' => __( 'Costa Rican colon', 'LION' ) ),
		'CUC' => array( 'symbol' => '$', 'name' => __( 'Cuban peso', 'LION' ) ),
		'CVE' => array( 'symbol' => 'Esc', 'name' => __( 'Cape Verdean escudo', 'LION' ) ),
		'CZK' => array( 'symbol' => 'K&#269;', 'name' => __( 'Czech koruna', 'LION' ) ),
		'DJF' => array( 'symbol' => 'Fdj', 'name' => __( 'Djiboutian franc', 'LION' ) ),
		'DKK' => array( 'symbol' => 'Kr', 'name' => __( 'Danish krone', 'LION' ) ),
		'DOP' => array( 'symbol' => 'RD$', 'name' => __( 'Dominican peso', 'LION' ) ),
		'DZD' => array( 'symbol' => '&#1583;.&#1580;', 'name' => __( 'Algerian dinar', 'LION' ) ),
		'EEK' => array( 'symbol' => 'KR', 'name' => __( 'Estonian kroon', 'LION' ) ),
		'EGP' => array( 'symbol' => '&#163;', 'name' => __( 'Egyptian pound', 'LION' ) ),
		'ERN' => array( 'symbol' => 'Nfa', 'name' => __( 'Eritrean nakfa', 'LION' ) ),
		'ETB' => array( 'symbol' => 'Br', 'name' => __( 'Ethiopian birr', 'LION' ) ),
		'EUR' => array( 'symbol' => '&#8364;', 'name' => __( 'European Euro', 'LION' ) ),
		'FJD' => array( 'symbol' => 'FJ$', 'name' => __( 'Fijian dollar', 'LION' ) ),
		'FKP' => array( 'symbol' => '&#163;', 'name' => __( 'Falkland Islands pound', 'LION' ) ),
		'GBP' => array( 'symbol' => '&#163;', 'name' => __( 'British pound', 'LION' ) ),
		'GEL' => array( 'symbol' => 'GEL', 'name' => __( 'Georgian lari', 'LION' ) ),
		'GHS' => array( 'symbol' => 'GH&#8373;', 'name' => __( 'Ghanaian cedi', 'LION' ) ),
		'GIP' => array( 'symbol' => '&#163;', 'name' => __( 'Gibraltar pound', 'LION' ) ),
		'GMD' => array( 'symbol' => 'D', 'name' => __( 'Gambian dalasi', 'LION' ) ),
		'GNF' => array( 'symbol' => 'FG', 'name' => __( 'Guinean franc', 'LION' ) ),
		'GQE' => array( 'symbol' => 'CFA', 'name' => __( 'Central African CFA franc', 'LION' ) ),
		'GTQ' => array( 'symbol' => 'Q', 'name' => __( 'Guatemalan quetzal', 'LION' ) ),
		'GYD' => array( 'symbol' => 'GY$', 'name' => __( 'Guyanese dollar', 'LION' ) ),
		'HKD' => array( 'symbol' => 'HK$', 'name' => __( 'Hong Kong dollar', 'LION' ) ),
		'HNL' => array( 'symbol' => 'L', 'name' => __( 'Honduran lempira', 'LION' ) ),
		'HRK' => array( 'symbol' => 'kn', 'name' => __( 'Croatian kuna', 'LION' ) ),
		'HTG' => array( 'symbol' => 'G', 'name' => __( 'Haitian gourde', 'LION' ) ),
		'HUF' => array( 'symbol' => 'Ft', 'name' => __( 'Hungarian forint', 'LION' ) ),
		'IDR' => array( 'symbol' => 'Rp', 'name' => __( 'Indonesian rupiah', 'LION' ) ),
		'ILS' => array( 'symbol' => '&#8362;', 'name' => __( 'Israeli new sheqel', 'LION' ) ),
		'INR' => array( 'symbol' => '&#8329;', 'name' => __( 'Indian rupee', 'LION' ) ),
		'IQD' => array( 'symbol' => '&#1583;.&#1593;', 'name' => __( 'Iraqi dinar', 'LION' ) ),
		'IRR' => array( 'symbol' => 'IRR', 'name' => __( 'Iranian rial', 'LION' ) ),
		'ISK' => array( 'symbol' => 'kr', 'name' => __( 'Icelandic kr\u00f3na', 'LION' ) ),
		'JMD' => array( 'symbol' => 'J$', 'name' => __( 'Jamaican dollar', 'LION' ) ),
		'JOD' => array( 'symbol' => 'JOD', 'name' => __( 'Jordanian dinar', 'LION' ) ),
		'JPY' => array( 'symbol' => '&#165;', 'name' => __( 'Japanese yen', 'LION' ) ),
		'KES' => array( 'symbol' => 'KSh', 'name' => __( 'Kenyan shilling', 'LION' ) ),
		'KGS' => array( 'symbol' => '&#1089;&#1086;&#1084;', 'name' => __( 'Kyrgyzstani som', 'LION' ) ),
		'KHR' => array( 'symbol' => '&#6107;', 'name' => __( 'Cambodian riel', 'LION' ) ),
		'KMF' => array( 'symbol' => 'KMF', 'name' => __( 'Comorian franc', 'LION' ) ),
		'KPW' => array( 'symbol' => 'W', 'name' => __( 'North Korean won', 'LION' ) ),
		'KRW' => array( 'symbol' => 'W', 'name' => __( 'South Korean won', 'LION' ) ),
		'KWD' => array( 'symbol' => 'KWD', 'name' => __( 'Kuwaiti dinar', 'LION' ) ),
		'KYD' => array( 'symbol' => 'KY$', 'name' => __( 'Cayman Islands dollar', 'LION' ) ),
		'KZT' => array( 'symbol' => 'T', 'name' => __( 'Kazakhstani tenge', 'LION' ) ),
		'LAK' => array( 'symbol' => 'KN', 'name' => __( 'Lao kip', 'LION' ) ),
		'LBP' => array( 'symbol' => '&#163;', 'name' => __( 'Lebanese lira', 'LION' ) ),
		'LKR' => array( 'symbol' => 'Rs', 'name' => __( 'Sri Lankan rupee', 'LION' ) ),
		'LRD' => array( 'symbol' => 'L$', 'name' => __( 'Liberian dollar', 'LION' ) ),
		'LSL' => array( 'symbol' => 'M', 'name' => __( 'Lesotho loti', 'LION' ) ),
		'LTL' => array( 'symbol' => 'Lt', 'name' => __( 'Lithuanian litas', 'LION' ) ),
		'LVL' => array( 'symbol' => 'Ls', 'name' => __( 'Latvian lats', 'LION' ) ),
		'LYD' => array( 'symbol' => 'LD', 'name' => __( 'Libyan dinar', 'LION' ) ),
		'MAD' => array( 'symbol' => 'MAD', 'name' => __( 'Moroccan dirham', 'LION' ) ),
		'MDL' => array( 'symbol' => 'MDL', 'name' => __( 'Moldovan leu', 'LION' ) ),
		'MGA' => array( 'symbol' => 'FMG', 'name' => __( 'Malagasy ariary', 'LION' ) ),
		'MKD' => array( 'symbol' => 'MKD', 'name' => __( 'Macedonian denar', 'LION' ) ),
		'MMK' => array( 'symbol' => 'K', 'name' => __( 'Myanma kyat', 'LION' ) ),
		'MNT' => array( 'symbol' => '&#8366;', 'name' => __( 'Mongolian tugrik', 'LION' ) ),
		'MOP' => array( 'symbol' => 'P', 'name' => __( 'Macanese pataca', 'LION' ) ),
		'MRO' => array( 'symbol' => 'UM', 'name' => __( 'Mauritanian ouguiya', 'LION' ) ),
		'MUR' => array( 'symbol' => 'Rs', 'name' => __( 'Mauritian rupee', 'LION' ) ),
		'MVR' => array( 'symbol' => 'Rf', 'name' => __( 'Maldivian rufiyaa', 'LION' ) ),
		'MWK' => array( 'symbol' => 'MK', 'name' => __( 'Malawian kwacha', 'LION' ) ),
		'MXN' => array( 'symbol' => '$', 'name' => __( 'Mexican peso', 'LION' ) ),
		'MYR' => array( 'symbol' => 'RM', 'name' => __( 'Malaysian ringgit', 'LION' ) ),
		'MZM' => array( 'symbol' => 'MTn', 'name' => __( 'Mozambican metical', 'LION' ) ),
		'NAD' => array( 'symbol' => 'N$', 'name' => __( 'Namibian dollar', 'LION' ) ),
		'NGN' => array( 'symbol' => '&#8358;', 'name' => __( 'Nigerian naira', 'LION' ) ),
		'NIO' => array( 'symbol' => 'C$', 'name' => __( 'Nicaraguan c\u00f3rdoba', 'LION' ) ),
		'NOK' => array( 'symbol' => 'kr', 'name' => __( 'Norwegian krone', 'LION' ) ),
		'NPR' => array( 'symbol' => 'NRs', 'name' => __( 'Nepalese rupee', 'LION' ) ),
		'NZD' => array( 'symbol' => 'NZ$', 'name' => __( 'New Zealand dollar', 'LION' ) ),
		'OMR' => array( 'symbol' => 'OMR', 'name' => __( 'Omani rial', 'LION' ) ),
		'PAB' => array( 'symbol' => 'B./', 'name' => __( 'Panamanian balboa', 'LION' ) ),
		'PEN' => array( 'symbol' => 'S/.', 'name' => __( 'Peruvian nuevo sol', 'LION' ) ),
		'PGK' => array( 'symbol' => 'K', 'name' => __( 'Papua New Guinean kina', 'LION' ) ),
		'PHP' => array( 'symbol' => '&#8369;', 'name' => __( 'Philippine peso', 'LION' ) ),
		'PKR' => array( 'symbol' => 'Rs.', 'name' => __( 'Pakistani rupee', 'LION' ) ),
		'PLN' => array( 'symbol' => 'z&#322;', 'name' => __( 'Polish zloty', 'LION' ) ),
		'PYG' => array( 'symbol' => '&#8370;', 'name' => __( 'Paraguayan guarani', 'LION' ) ),
		'QAR' => array( 'symbol' => 'QR', 'name' => __( 'Qatari riyal', 'LION' ) ),
		'RON' => array( 'symbol' => 'L', 'name' => __( 'Romanian leu', 'LION' ) ),
		'RSD' => array( 'symbol' => 'din.', 'name' => __( 'Serbian dinar', 'LION' ) ),
		'RUB' => array( 'symbol' => 'R', 'name' => __( 'Russian ruble', 'LION' ) ),
		'SAR' => array( 'symbol' => 'SR', 'name' => __( 'Saudi riyal', 'LION' ) ),
		'SBD' => array( 'symbol' => 'SI$', 'name' => __( 'Solomon Islands dollar', 'LION' ) ),
		'SCR' => array( 'symbol' => 'SR', 'name' => __( 'Seychellois rupee', 'LION' ) ),
		'SDG' => array( 'symbol' => 'SDG', 'name' => __( 'Sudanese pound', 'LION' ) ),
		'SEK' => array( 'symbol' => 'kr', 'name' => __( 'Swedish krona', 'LION' ) ),
		'SGD' => array( 'symbol' => 'S$', 'name' => __( 'Singapore dollar', 'LION' ) ),
		'SHP' => array( 'symbol' => '&#163;', 'name' => __( 'Saint Helena pound', 'LION' ) ),
		'SLL' => array( 'symbol' => 'Le', 'name' => __( 'Sierra Leonean leone', 'LION' ) ),
		'SOS' => array( 'symbol' => 'Sh.', 'name' => __( 'Somali shilling', 'LION' ) ),
		'SRD' => array( 'symbol' => '$', 'name' => __( 'Surinamese dollar', 'LION' ) ),
		'SYP' => array( 'symbol' => 'LS', 'name' => __( 'Syrian pound', 'LION' ) ),
		'SZL' => array( 'symbol' => 'E', 'name' => __( 'Swazi lilangeni', 'LION' ) ),
		'THB' => array( 'symbol' => '&#3647;', 'name' => __( 'Thai baht', 'LION' ) ),
		'TJS' => array( 'symbol' => 'TJS', 'name' => __( 'Tajikistani somoni', 'LION' ) ),
		'TMT' => array( 'symbol' => 'm', 'name' => __( 'Turkmen manat', 'LION' ) ),
		'TND' => array( 'symbol' => 'DT', 'name' => __( 'Tunisian dinar', 'LION' ) ),
		'TRY' => array( 'symbol' => 'TRY', 'name' => __( 'Turkish new lira', 'LION' ) ),
		'TTD' => array( 'symbol' => 'TT$', 'name' => __( 'Trinidad and Tobago dollar', 'LION' ) ),
		'TWD' => array( 'symbol' => 'NT$', 'name' => __( 'New Taiwan dollar', 'LION' ) ),
		'TZS' => array( 'symbol' => 'TZS', 'name' => __( 'Tanzanian shilling', 'LION' ) ),
		'UAH' => array( 'symbol' => 'UAH', 'name' => __( 'Ukrainian hryvnia', 'LION' ) ),
		'UGX' => array( 'symbol' => 'USh', 'name' => __( 'Ugandan shilling', 'LION' ) ),
		'USD' => array( 'symbol' => '$', 'name' => __( 'United States dollar', 'LION' ) ),
		'UYU' => array( 'symbol' => '$U', 'name' => __( 'Uruguayan peso', 'LION' ) ),
		'UZS' => array( 'symbol' => 'UZS', 'name' => __( 'Uzbekistani som', 'LION' ) ),
		'VEB' => array( 'symbol' => 'Bs', 'name' => __( 'Venezuelan bolivar', 'LION' ) ),
		'VND' => array( 'symbol' => '&#8363;', 'name' => __( 'Vietnamese dong', 'LION' ) ),
		'VUV' => array( 'symbol' => 'VT', 'name' => __( 'Vanuatu vatu', 'LION' ) ),
		'WST' => array( 'symbol' => 'WS$', 'name' => __( 'Samoan tala', 'LION' ) ),
		'XAF' => array( 'symbol' => 'CFA', 'name' => __( 'Central African CFA franc', 'LION' ) ),
		'XCD' => array( 'symbol' => 'EC$', 'name' => __( 'East Caribbean dollar', 'LION' ) ),
		'XDR' => array( 'symbol' => 'SDR', 'name' => __( 'Special Drawing Rights', 'LION' ) ),
		'XOF' => array( 'symbol' => 'CFA', 'name' => __( 'West African CFA franc', 'LION' ) ),
		'XPF' => array( 'symbol' => 'F', 'name' => __( 'CFP franc', 'LION' ) ),
		'YER' => array( 'symbol' => 'YER', 'name' => __( 'Yemeni rial', 'LION' ) ),
		'ZAR' => array( 'symbol' => 'R', 'name' => __( 'South African rand', 'LION' ) ),
		'ZMK' => array( 'symbol' => 'ZK', 'name' => __( 'Zambian kwacha', 'LION' ) ),
		'ZWR' => array( 'symbol' => 'Z$', 'name' => __( 'Zimbabwean dollar', 'LION' ) ),
	);
	
	return apply_filters( 'it_exchange_get_currency_options', $currencies );
}

/**
 * Returns the currency symbol based on the currency key
 *
 * @since 0.4.0
 *
 * @param string $country_code country code for the currency
 * @return string
*/
function it_exchange_get_currency_symbol( $country_code ) {
	$currencies = it_exchange_get_currency_options();
	$symbol = empty( $currencies[$country_code] ) ? '$' : $currencies[$country_code];
	$symbol = ( is_array( $symbol ) && ! empty( $symbol['symbol'] ) ) ? $symbol['symbol'] : '$';
	return apply_filters( 'it_exchange_get_currency_symbol', $symbol );
}
