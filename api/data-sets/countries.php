<?php
/**
 * Country data sets
 * @package IT_Exchange
 * @since 1.2.0
*/

/**
 * Returns an array of countries
 *
 * @since 1.2.0
 *
 * @return array
*/
function it_exchange_get_countries() {

	$countries = array(
		'AD' => __( 'Andorra', 'LION' ),
		'AE' => __( 'United Arab Emirates', 'LION' ),
		'AF' => __( 'Afghanistan', 'LION' ),
		'AG' => __( 'Antigua and Barbuda', 'LION' ),
		'AI' => __( 'Anguilla', 'LION' ),
		'AL' => __( 'Albania', 'LION' ),
		'AM' => __( 'Armenia', 'LION' ),
		'AN' => __( 'Netherlands Antilles', 'LION' ),
		'AO' => __( 'Angola', 'LION' ),
		'AP' => __( 'Asia/Pacific Region', 'LION' ),
		'AQ' => __( 'Antarctica', 'LION' ),
		'AR' => __( 'Argentina', 'LION' ),
		'AS' => __( 'American Samoa', 'LION' ),
		'AT' => __( 'Austria', 'LION' ),
		'AU' => __( 'Australia', 'LION' ),
		'AW' => __( 'Aruba', 'LION' ),
		'AX' => __( 'Aland Islands', 'LION' ),
		'AZ' => __( 'Azerbaijan', 'LION' ),
		'BA' => __( 'Bosnia and Herzegovina', 'LION' ),
		'BB' => __( 'Barbados', 'LION' ),
		'BD' => __( 'Bangladesh', 'LION' ),
		'BE' => __( 'Belgium', 'LION' ),
		'BF' => __( 'Burkina Faso', 'LION' ),
		'BG' => __( 'Bulgaria', 'LION' ),
		'BH' => __( 'Bahrain', 'LION' ),
		'BI' => __( 'Burundi', 'LION' ),
		'BJ' => __( 'Benin', 'LION' ),
		'BM' => __( 'Bermuda', 'LION' ),
		'BN' => __( 'Brunei Darussalam', 'LION' ),
		'BO' => __( 'Bolivia', 'LION' ),
		'BR' => __( 'Brazil', 'LION' ),
		'BS' => __( 'Bahamas', 'LION' ),
		'BT' => __( 'Bhutan', 'LION' ),
		'BV' => __( 'Bouvet Island', 'LION' ),
		'BW' => __( 'Botswana', 'LION' ),
		'BY' => __( 'Belarus', 'LION' ),
		'BZ' => __( 'Belize', 'LION' ),
		'CA' => __( 'Canada', 'LION' ),
		'CD' => __( 'Congo, The Democratic Republic of the', 'LION' ),
		'CF' => __( 'Central African Republic', 'LION' ),
		'CG' => __( 'Congo', 'LION' ),
		'CH' => __( 'Switzerland', 'LION' ),
		'CI' => __( 'Cote D\'Ivoire', 'LION' ),
		'CK' => __( 'Cook Islands', 'LION' ),
		'CL' => __( 'Chile', 'LION' ),
		'CM' => __( 'Cameroon', 'LION' ),
		'CN' => __( 'China', 'LION' ),
		'CO' => __( 'Colombia', 'LION' ),
		'CR' => __( 'Costa Rica', 'LION' ),
		'CU' => __( 'Cuba', 'LION' ),
		'CV' => __( 'Cape Verde', 'LION' ),
		'CY' => __( 'Cyprus', 'LION' ),
		'CZ' => __( 'Czech Republic', 'LION' ),
		'DE' => __( 'Germany', 'LION' ),
		'DJ' => __( 'Djibouti', 'LION' ),
		'DK' => __( 'Denmark', 'LION' ),
		'DM' => __( 'Dominica', 'LION' ),
		'DO' => __( 'Dominican Republic', 'LION' ),
		'DZ' => __( 'Algeria', 'LION' ),
		'EC' => __( 'Ecuador', 'LION' ),
		'EE' => __( 'Estonia', 'LION' ),
		'EG' => __( 'Egypt', 'LION' ),
		'ER' => __( 'Eritrea', 'LION' ),
		'ES' => __( 'Spain', 'LION' ),
		'ET' => __( 'Ethiopia', 'LION' ),
		'EU' => __( 'Europe', 'LION' ),
		'FI' => __( 'Finland', 'LION' ),
		'FJ' => __( 'Fiji', 'LION' ),
		'FK' => __( 'Falkland Islands (Malvinas)', 'LION' ),
		'FM' => __( 'Micronesia, Federated States of', 'LION' ),
		'FO' => __( 'Faroe Islands', 'LION' ),
		'FR' => __( 'France', 'LION' ),
		'GA' => __( 'Gabon', 'LION' ),
		'GB' => __( 'United Kingdom', 'LION' ),
		'GD' => __( 'Grenada', 'LION' ),
		'GE' => __( 'Georgia', 'LION' ),
		'GF' => __( 'French Guiana', 'LION' ),
		'GG' => __( 'Guernsey', 'LION' ),
		'GH' => __( 'Ghana', 'LION' ),
		'GI' => __( 'Gibraltar', 'LION' ),
		'GL' => __( 'Greenland', 'LION' ),
		'GM' => __( 'Gambia', 'LION' ),
		'GN' => __( 'Guinea', 'LION' ),
		'GP' => __( 'Guadeloupe', 'LION' ),
		'GQ' => __( 'Equatorial Guinea', 'LION' ),
		'GR' => __( 'Greece', 'LION' ),
		'GT' => __( 'Guatemala', 'LION' ),
		'GU' => __( 'Guam', 'LION' ),
		'GW' => __( 'Guinea-Bissau', 'LION' ),
		'GY' => __( 'Guyana', 'LION' ),
		'HK' => __( 'Hong Kong', 'LION' ),
		'HN' => __( 'Honduras', 'LION' ),
		'HR' => __( 'Croatia', 'LION' ),
		'HT' => __( 'Haiti', 'LION' ),
		'HU' => __( 'Hungary', 'LION' ),
		'ID' => __( 'Indonesia', 'LION' ),
		'IE' => __( 'Ireland', 'LION' ),
		'IL' => __( 'Israel', 'LION' ),
		'IM' => __( 'Isle of Man', 'LION' ),
		'IN' => __( 'India', 'LION' ),
		'IO' => __( 'British Indian Ocean Territory', 'LION' ),
		'IQ' => __( 'Iraq', 'LION' ),
		'IR' => __( 'Iran, Islamic Republic of', 'LION' ),
		'IS' => __( 'Iceland', 'LION' ),
		'IT' => __( 'Italy', 'LION' ),
		'JE' => __( 'Jersey', 'LION' ),
		'JM' => __( 'Jamaica', 'LION' ),
		'JO' => __( 'Jordan', 'LION' ),
		'JP' => __( 'Japan', 'LION' ),
		'KE' => __( 'Kenya', 'LION' ),
		'KG' => __( 'Kyrgyzstan', 'LION' ),
		'KH' => __( 'Cambodia', 'LION' ),
		'KI' => __( 'Kiribati', 'LION' ),
		'KM' => __( 'Comoros', 'LION' ),
		'KN' => __( 'Saint Kitts and Nevis', 'LION' ),
		'KP' => __( 'Korea, Democratic People\'s Republic of', 'LION' ),
		'KR' => __( 'Korea, Republic of', 'LION' ),
		'KW' => __( 'Kuwait', 'LION' ),
		'KY' => __( 'Cayman Islands', 'LION' ),
		'KZ' => __( 'Kazakstan', 'LION' ),
		'LA' => __( 'Lao People\'s Democratic Republic', 'LION' ),
		'LB' => __( 'Lebanon', 'LION' ),
		'LC' => __( 'Saint Lucia', 'LION' ),
		'LI' => __( 'Liechtenstein', 'LION' ),
		'LK' => __( 'Sri Lanka', 'LION' ),
		'LR' => __( 'Liberia', 'LION' ),
		'LS' => __( 'Lesotho', 'LION' ),
		'LT' => __( 'Lithuania', 'LION' ),
		'LU' => __( 'Luxembourg', 'LION' ),
		'LV' => __( 'Latvia', 'LION' ),
		'LY' => __( 'Libyan Arab Jamahiriya', 'LION' ),
		'MA' => __( 'Morocco', 'LION' ),
		'MC' => __( 'Monaco', 'LION' ),
		'MD' => __( 'Moldova, Republic of', 'LION' ),
		'ME' => __( 'Montenegro', 'LION' ),
		'MG' => __( 'Madagascar', 'LION' ),
		'MH' => __( 'Marshall Islands', 'LION' ),
		'MK' => __( 'Macedonia', 'LION' ),
		'ML' => __( 'Mali', 'LION' ),
		'MM' => __( 'Myanmar', 'LION' ),
		'MN' => __( 'Mongolia', 'LION' ),
		'MO' => __( 'Macau', 'LION' ),
		'MP' => __( 'Northern Mariana Islands', 'LION' ),
		'MQ' => __( 'Martinique', 'LION' ),
		'MR' => __( 'Mauritania', 'LION' ),
		'MS' => __( 'Montserrat', 'LION' ),
		'MT' => __( 'Malta', 'LION' ),
		'MU' => __( 'Mauritius', 'LION' ),
		'MV' => __( 'Maldives', 'LION' ),
		'MW' => __( 'Malawi', 'LION' ),
		'MX' => __( 'Mexico', 'LION' ),
		'MY' => __( 'Malaysia', 'LION' ),
		'MZ' => __( 'Mozambique', 'LION' ),
		'NA' => __( 'Namibia', 'LION' ),
		'NC' => __( 'New Caledonia', 'LION' ),
		'NE' => __( 'Niger', 'LION' ),
		'NF' => __( 'Norfolk Island', 'LION' ),
		'NG' => __( 'Nigeria', 'LION' ),
		'NI' => __( 'Nicaragua', 'LION' ),
		'NL' => __( 'Netherlands', 'LION' ),
		'NO' => __( 'Norway', 'LION' ),
		'NP' => __( 'Nepal', 'LION' ),
		'NR' => __( 'Nauru', 'LION' ),
		'NU' => __( 'Niue', 'LION' ),
		'NZ' => __( 'New Zealand', 'LION' ),
		'OM' => __( 'Oman', 'LION' ),
		'PA' => __( 'Panama', 'LION' ),
		'PE' => __( 'Peru', 'LION' ),
		'PF' => __( 'French Polynesia', 'LION' ),
		'PG' => __( 'Papua New Guinea', 'LION' ),
		'PH' => __( 'Philippines', 'LION' ),
		'PK' => __( 'Pakistan', 'LION' ),
		'PL' => __( 'Poland', 'LION' ),
		'PM' => __( 'Saint Pierre and Miquelon', 'LION' ),
		'PR' => __( 'Puerto Rico', 'LION' ),
		'PS' => __( 'Palestinian Territory, Occupied', 'LION' ),
		'PT' => __( 'Portugal', 'LION' ),
		'PW' => __( 'Palau', 'LION' ),
		'PY' => __( 'Paraguay', 'LION' ),
		'QA' => __( 'Qatar', 'LION' ),
		'RE' => __( 'Reunion', 'LION' ),
		'RO' => __( 'Romania', 'LION' ),
		'RS' => __( 'Serbia', 'LION' ),
		'RU' => __( 'Russian Federation', 'LION' ),
		'RW' => __( 'Rwanda', 'LION' ),
		'SA' => __( 'Saudi Arabia', 'LION' ),
		'SB' => __( 'Solomon Islands', 'LION' ),
		'SC' => __( 'Seychelles', 'LION' ),
		'SD' => __( 'Sudan', 'LION' ),
		'SE' => __( 'Sweden', 'LION' ),
		'SG' => __( 'Singapore', 'LION' ),
		'SI' => __( 'Slovenia', 'LION' ),
		'SK' => __( 'Slovakia', 'LION' ),
		'SL' => __( 'Sierra Leone', 'LION' ),
		'SM' => __( 'San Marino', 'LION' ),
		'SN' => __( 'Senegal', 'LION' ),
		'SO' => __( 'Somalia', 'LION' ),
		'SR' => __( 'Suriname', 'LION' ),
		'ST' => __( 'Sao Tome and Principe', 'LION' ),
		'SV' => __( 'El Salvador', 'LION' ),
		'SY' => __( 'Syrian Arab Republic', 'LION' ),
		'SZ' => __( 'Swaziland', 'LION' ),
		'TC' => __( 'Turks and Caicos Islands', 'LION' ),
		'TD' => __( 'Chad', 'LION' ),
		'TG' => __( 'Togo', 'LION' ),
		'TH' => __( 'Thailand', 'LION' ),
		'TJ' => __( 'Tajikistan', 'LION' ),
		'TK' => __( 'Tokelau', 'LION' ),
		'TM' => __( 'Turkmenistan', 'LION' ),
		'TN' => __( 'Tunisia', 'LION' ),
		'TO' => __( 'Tonga', 'LION' ),
		'TR' => __( 'Turkey', 'LION' ),
		'TT' => __( 'Trinidad and Tobago', 'LION' ),
		'TV' => __( 'Tuvalu', 'LION' ),
		'TW' => __( 'Taiwan', 'LION' ),
		'TZ' => __( 'Tanzania, United Republic of', 'LION' ),
		'UA' => __( 'Ukraine', 'LION' ),
		'UG' => __( 'Uganda', 'LION' ),
		'UM' => __( 'United States Minor Outlying Islands', 'LION' ),
		'US' => __( 'United States', 'LION' ),
		'UY' => __( 'Uruguay', 'LION' ),
		'UZ' => __( 'Uzbekistan', 'LION' ),
		'VA' => __( 'Holy See (Vatican City State)', 'LION' ),
		'VC' => __( 'Saint Vincent and the Grenadines', 'LION' ),
		'VE' => __( 'Venezuela', 'LION' ),
		'VG' => __( 'Virgin Islands, British', 'LION' ),
		'VI' => __( 'Virgin Islands, U.S.', 'LION' ),
		'VN' => __( 'Vietnam', 'LION' ),
		'VU' => __( 'Vanuatu', 'LION' ),
		'WF' => __( 'Wallis and Futuna', 'LION' ),
		'WS' => __( 'Samoa', 'LION' ),
		'YE' => __( 'Yemen', 'LION' ),
		'YT' => __( 'Mayotte', 'LION' ),
		'ZA' => __( 'South Africa', 'LION' ),
		'ZM' => __( 'Zambia', 'LION' ),
		'ZW' => __( 'Zimbabwe', 'LION' ),
	);
	return apply_filters( 'it_exchange_get_countries', $countries );
}
