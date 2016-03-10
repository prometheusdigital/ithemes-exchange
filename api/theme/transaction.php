<?php

/**
 * Transaction class for THEME API
 *
 * @since 0.4.0
 */
class IT_Theme_API_Transaction implements IT_Theme_API {

	/**
	 * API context
	 *
	 * @var string $_context
	 * @since 0.4.0
	 */
	private $_context = 'transaction';

	/**
	 * The current transaction
	 *
	 * @var array
	 * @since 0.4.0
	 */
	public $_transaction = false;

	/**
	 * @var bool
	 */
	private $demo;

	/**
	 * Maps api tags to methods
	 *
	 * @var array $_tag_map
	 * @since 0.4.0
	 */
	public $_tag_map = array(
		'ordernumber'           => 'order_number',
		'status'                => 'status',
		'date'                  => 'date',
		'method'                => 'method',
		'note'                  => 'note',
		'total'                 => 'total',
		'subtotal'              => 'subtotal',
		'savingstotal'          => 'savings_total',
		'shippingtotal'         => 'shipping_total',
		'instructions'          => 'instructions',
		'shippingaddress'       => 'shipping_address',
		'shippingmethod'        => 'shipping_method',
		'billingaddress'        => 'billing_address',
		'products'              => 'products',
		'productattribute'      => 'product_attribute',
		'purchasemessage'       => 'purchase_message',
		'variants'              => 'variants',
		'description'           => 'description',
		'productdownloads'      => 'product_downloads',
		'productdownload'       => 'product_download',
		'productdownloadhashes' => 'product_download_hashes',
		'productdownloadhash'   => 'product_download_hash',
		'productfeaturedimage'  => 'product_featured_image',
		'clearedfordelivery'    => 'cleared_for_delivery',
		'featuredimage'         => 'featured_image',
		'cartobject'            => 'cart_object',
	);

	/**
	 * The current transaction product
	 *
	 * @var array $_transaction_product
	 * @since 0.4.0
	 */
	public $_transaction_product = false;

	/**
	 * The current transaction cart object
	 *
	 * @var array $_transaction_cart_object
	 * @since 1.4.0
	 */
	public $_transaction_cart_object = false;

	/**
	 * Constructor
	 *
	 * @since 0.4.0
	 */
	function __construct() {

		$this->demo = empty( $GLOBALS['it_exchange']['demo-receipt'] ) ? false : true;

		$this->_transaction                       = empty( $GLOBALS['it_exchange']['transaction'] ) ? false : $GLOBALS['it_exchange']['transaction'];
		$this->_transaction_product               = empty( $GLOBALS['it_exchange']['transaction_product'] ) ? false : $GLOBALS['it_exchange']['transaction_product'];
		$this->_transaction_product_download      = empty( $GLOBALS['it_exchange']['transaction_product_download'] ) ? false : $GLOBALS['it_exchange']['transaction_product_download'];
		$this->_transaction_product_download_hash = empty( $GLOBALS['it_exchange']['transaction_product_download_hash'] ) ? false : $GLOBALS['it_exchange']['transaction_product_download_hash'];
	}

	/**
	 * Deprecated PHP 4 style constructor.
	 *
	 * @deprecated
	 */
	function IT_Theme_API_Transaction() {

		self::__construct();

		_deprecated_constructor( __CLASS__, '1.24.0' );
	}

	/**
	 * Returns the context. Also helps to confirm we are an iThemes Exchange theme API class
	 *
	 * @since 0.4.0
	 *
	 * @return string
	 */
	function get_api_context() {
		return $this->_context;
	}

	/**
	 * Returns the transaction order number
	 *
	 * @since 1.4.0
	 *
	 */
	function order_number( $options = array() ) {
		// Set options
		$defaults = array(
			'before' => '',
			'after'  => '',
			'label'  => __( 'Order Number: %s', 'it-l10n-ithemes-exchange' ),
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		$number = $this->demo ? '#058713' : it_exchange_get_transaction_order_number( $this->_transaction );

		return $options['before'] . sprintf( $options['label'], $number ) . $options['after'];
	}

	/**
	 * Returns the transaction status
	 *
	 * @since 0.4.0
	 *
	 */
	function status( $options = array() ) {
		// Set options
		$defaults = array(
			'before' => '',
			'after'  => '',
			'label'  => __( 'Status: <span class="%s">%s</span>', 'it-l10n-ithemes-exchange' ),
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		return $options['before'] . sprintf( $options['label'], it_exchange_get_transaction_status( $this->_transaction ), it_exchange_get_transaction_status_label( $this->_transaction ) ) . $options['after'];
	}

	/**
	 * Returns the transaction instructions
	 *
	 * @since 0.4.0
	 *
	 */
	function instructions( $options = array() ) {
		// Set options
		$defaults = array(
			'before' => '',
			'after'  => '',
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		return $options['before'] . it_exchange_get_transaction_instructions( $this->_transaction ) . $options['after'];
	}

	/**
	 * Returns the transaction date
	 *
	 * @since 0.4.0
	 *
	 * @param array $options output options
	 *
	 * @return string
	 */
	function date( $options = array() ) {

		// Set options
		$defaults = array(
			'before' => '',
			'after'  => '',
			'format' => get_option( 'date_format' ),
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		if ( $this->demo ) {
			return date( $options['format'], strtotime( 'February 23, 2016' ) );
		}

		return $options['before'] . it_exchange_get_transaction_date( $this->_transaction, $options['format'] ) . $options['after'];
	}

	/**
	 * Returns the transaction method label.
	 *
	 * @since 1.36
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	function method( $options = array() ) {

		$defaults = array(
			'before' => '',
			'after'  => '',
		);

		$options = ITUtility::merge_defaults( $options, $defaults );

		$method = $this->demo ? 'Stripe' : it_exchange_get_transaction_method_name( $this->_transaction );

		return $options['before'] . $method . $options['after'];
	}

	/**
	 * Retrieve the order note.
	 *
	 * @since 1.36
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	public function note( $options = array() ) {

		if ( ! empty ( $this->_transaction->cart_details->customer_order_notes ) ) {
			$note = $this->_transaction->cart_details->customer_order_notes;
		} else {
			$note = '';
		}

		if ( $this->demo ) {
			$note = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sed sem eu mauris lobortis congue. Vivamus nec elit id ex luctus aliquam ut et elit.';
		}

		if ( ! empty( $options['has'] ) ) {
			return (bool) $note;
		}

		return $note;
	}

	/**
	 * Returns the transaction total
	 *
	 * @since 0.4.0
	 *
	 * @param array $options output options
	 *
	 * @return string
	 */
	function total( $options = array() ) {
		// Set options
		$defaults = array(
			'before'          => '',
			'after'           => '',
			'format_currency' => true,
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		$total = $this->demo ? '263.85' : it_exchange_get_transaction_total( $this->_transaction, false );
		$total = $options['format_currency'] ? it_exchange_format_price( $total ) : $total;

		return $options['before'] . $total . $options['after'];
	}

	/**
	 * Returns the transaction total
	 *
	 * @since 1.4.0
	 *
	 * @param array $options output options
	 *
	 * @return string
	 */
	function subtotal( $options = array() ) {
		// Set options
		$defaults = array(
			'before'          => '',
			'after'           => '',
			'format_currency' => true,
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		$total = $this->demo ? '255.00' : it_exchange_get_transaction_subtotal( $this->_transaction, false );
		$total = $options['format_currency'] ? it_exchange_format_price( $total ) : $total;

		return $options['before'] . $total . $options['after'];
	}

	/**
	 * Returns the transaction savings
	 *
	 * @since 1.4.0
	 *
	 * @param array $options output options
	 *
	 * @return string
	 */
	function savings_total( $options = array() ) {
		if ( ! empty( $options['has'] ) ) {
			return (bool) it_exchange_get_transaction_coupons( $this->_transaction );
		}

		// Set options
		$defaults = array(
			'before'          => '',
			'after'           => '',
			'format_currency' => true,
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		return $options['before'] . it_exchange_get_transaction_coupons_total_discount( $this->_transaction, $options['format_currency'] ) . $options['after'];
	}

	/**
	 * Returns the transaction savings
	 *
	 * @since 1.4.0
	 *
	 * @param array $options output options
	 *
	 * @return string
	 */
	function shipping_total( $options = array() ) {
		if ( ! empty( $options['has'] ) ) {
			return (bool) it_exchange_get_transaction_shipping_total( $this->_transaction );
		}

		// Set options
		$defaults = array(
			'before'          => '',
			'after'           => '',
			'format_currency' => true,
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		$total = $this->demo ? '8.85' : it_exchange_get_transaction_shipping_total( $this->_transaction, false );
		$total = $options['format_currency'] ? it_exchange_format_price( $total ) : $total;

		return $options['before'] . $total . $options['after'];
	}

	/**
	 * Get the shipping method for the transaction.
	 *
	 * @since 1.36
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	public function shipping_method( $options = array() ) {

		$defaults = array(
			'open-line'  => '',
			'close-line' => '<br>'
		);

		$options = ITUtility::merge_defaults( $options, $defaults );

		if ( ! empty( $options['has'] ) ) {
			return it_exchange_transaction_includes_shipping( $this->_transaction );
		}

		$method = it_exchange_get_transaction_shipping_method( $this->_transaction );

		if ( $method->slug === 'multiple-methods' ) {

			$out = '';

			foreach ( it_exchange_get_transaction_products( $this->_transaction ) as $product ) {
				$name   = get_the_title( $product['product_id'] );
				$method = it_exchange_get_transaction_shipping_method_for_product( $this->_transaction, $product['product_cart_id'] );

				$out .= $options['open-line'] . $name . ': ' . $method . $options['close-line'];
			}
		} else {
			$out = it_exchange_get_transaction_shipping_method( $this->_transaction )->label;
		}

		return $out;
	}

	/**
	 * Returns the transaction shipping address
	 *
	 * @since 1.4.0
	 *
	 * @param array $options output options
	 *
	 * @return string
	 */
	function shipping_address( $options = array() ) {

		$address = empty( $this->_transaction->cart_details->shipping_address ) ? array() : $this->_transaction->cart_details->shipping_address;

		if ( $this->demo ) {
			$address = array(
				'company-name' => 'iThemes',
				'address1'     => '1720 S. Kelly Ave.',
				'address2'     => '',
				'city'         => 'Edmond',
				'state'        => 'OK',
				'zip'          => '73013',
				'country'      => 'US',
				'email'        => '',
				'phone'        => '',
			);
		}

		if ( ! empty( $options['has'] ) ) {
			return ! empty( $address ) && ! empty( $address['address1'] );
		}

		// Set options
		$defaults = array(
			'before' => '',
			'after'  => '',
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		return $options['before'] . it_exchange_get_formatted_shipping_address( $address ) . $options['after'];
	}

	/**
	 * Returns the transaction billing address
	 *
	 * @since 1.4.0
	 *
	 * @param array $options output options
	 *
	 * @return string
	 */
	function billing_address( $options = array() ) {

		$address = empty( $this->_transaction->cart_details->billing_address ) ? array() : $this->_transaction->cart_details->billing_address;

		if ( $this->demo ) {
			$address = array(
				'company-name' => 'iThemes',
				'address1'     => '1720 S. Kelly Ave.',
				'address2'     => '',
				'city'         => 'Edmond',
				'state'        => 'OK',
				'zip'          => '73013',
				'country'      => 'US',
				'email'        => '',
				'phone'        => '',
			);
		}

		if ( ! empty( $options['has'] ) ) {
			return ! empty( $address ) && ! empty( $address['address1'] );
		}

		// Set options
		$defaults = array(
			'before' => '',
			'after'  => '',
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		return $options['before'] . it_exchange_get_formatted_billing_address( $address ) . $options['after'];
	}

	/**
	 * This loops through the transaction_products GLOBAL and updates the transaction_product global.
	 *
	 * It return false when it reaches the last product
	 * If the has flag has been passed, it just returns a boolean
	 *
	 * @since 0.4.0
	 * @return string
	 */
	function products( $options = array() ) {
		// Return boolean if has flag was set
		if ( $options['has'] ) {

			if ( $this->demo ) {
				return true;
			}

			return count( it_exchange_get_transaction_products( $this->_transaction ) ) > 0;
		}

		// If we made it here, we're doing a loop of transaction_products for the current query.
		// This will init/reset the transaction_products global and loop through them.
		if ( empty( $GLOBALS['it_exchange']['transaction_products'] ) ) {
			$GLOBALS['it_exchange']['transaction_products'] = $this->demo ? $this->get_demo_products() : it_exchange_get_transaction_products( $this->_transaction );
			$GLOBALS['it_exchange']['transaction_product']  = reset( $GLOBALS['it_exchange']['transaction_products'] );

			return true;
		} else {
			if ( next( $GLOBALS['it_exchange']['transaction_products'] ) ) {
				$GLOBALS['it_exchange']['transaction_product'] = current( $GLOBALS['it_exchange']['transaction_products'] );

				return true;
			} else {
				$GLOBALS['it_exchange']['transaction_products'] = array();
				end( $GLOBALS['it_exchange']['transaction_products'] );
				$GLOBALS['it_exchange']['transaction_product'] = false;

				return false;
			}
		}
	}

	/**
	 * Get the demo products.
	 *
	 * @since 1.36
	 *
	 * @return array
	 */
	protected function get_demo_products() {
		return array(
			array(
				'product_id'         => 0,
				'count'              => 2,
				'product_name'       => 'Lewis Trouser Strap',
				'product_subtotal'   => '170.00',
				'product_base_price' => '85.00',
			),
			array(
				'product_id'         => 0,
				'count'              => 1,
				'product_name'       => 'Lewis Trouser Strap',
				'product_subtotal'   => '85.00',
				'product_base_price' => '85.00',
			),
		);
	}

	/**
	 * Returns boolean is the transaction cleared for delivery or not
	 *
	 * @since 0.4.10
	 *
	 * @param array $options
	 *
	 * @return boolean
	 */
	function cleared_for_delivery( $options = array() ) {
		return it_exchange_transaction_is_cleared_for_delivery( $this->_transaction->ID );
	}

	/**
	 * Use this to get a transaction product attribute like title, description, price, etc.
	 *
	 * See lib/templates/content-downloads/ files for examples
	 *
	 * @since 0.4.0
	 * @return string
	 */
	function product_attribute( $options = array() ) {

		// Set defaults
		$defaults = array(
			'wrap'         => false,
			'format'       => 'html',
			'attribute'    => false,
			'format_price' => true,
			'class'        => false
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		// Return empty if attribute was not provided
		if ( empty( $options['attribute'] ) ) {
			return '';
		}

		// Return empty string if empty
		if ( 'description' == $options['attribute'] ) {
			$attribute = it_exchange_get_product_feature( $this->_transaction_product['product_id'], 'description' );
			if ( empty( $attribute ) ) {
				return '';
			}
		} else if ( 'confirmation-url' == $options['attribute'] ) {
			$attribute = it_exchange_get_transaction_confirmation_url( $this->_transaction->ID );
		} else if ( 'product_subtotal' == $options['attribute'] ) {
			$attribute = $this->_transaction_product['product_subtotal'];
		} else if ( 'product_base_price' == $options['attribute'] ) {
			$attribute = $this->_transaction_product['product_base_price'];
		} else if ( 'product_count' == $options['attribute'] ) {
			$attribute = $this->_transaction_product['count'];
		} else if ( 'product_name' == $options['attribute'] && $this->variants() ) {
			$attribute = get_the_title( $this->_transaction_product['product_id'] );
		} else if ( ! $attribute = it_exchange_get_transaction_product_feature( $this->_transaction_product, $options['attribute'] ) ) {
			return '';
		}

		// Format price
		if ( (boolean) $options['format_price'] && in_array( $options['attribute'], array(
				'product_subtotal',
				'product_base_price'
			) )
		) {
			$attribute = it_exchange_format_price( $attribute );
		}

		$open_wrap  = empty( $options['wrap'] ) ? '' : '<' . esc_attr( $options['wrap'] ) . ' class="' . $options['class'] . '">';
		$close_wrap = empty( $options['wrap'] ) ? '' : '</' . esc_attr( $options['wrap'] ) . '>';
		$result     = '';

		if ( 'html' == $options['format'] ) {
			$result .= $open_wrap;
		}

		$result .= apply_filters( 'it_exchange_api_theme_transaction_product_attribute', $attribute, $options, $this->_transaction, $this->_transaction_product );

		if ( 'html' == $options['format'] ) {
			$result .= $close_wrap;
		}

		return $result;
	}

	/**
	 * Get the purchase message for a product.
	 *
	 * @since 1.36
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	function purchase_message( $options = array() ) {

		if ( $this->demo ) {

			static $showed = 0;

			if ( $showed < 2 ) {
				$message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sed sem eu mauris lobortis congue. Vivamus nec elit id ex luctus aliquam ut et elit.';
				$showed ++;
			} else {
				$message = '';
			}
		} elseif ( ! empty( $this->_transaction_product['product_id'] ) ) {
			$message = it_exchange_get_product_feature( $this->_transaction_product['product_id'], 'purchase-message' );
		} else {
			return false;
		}

		if ( ! empty( $options['has'] ) ) {
			return (bool) $message;
		}

		return apply_filters( 'it_exchange_email_notification_order_table_purchase_message', $message, $this->_transaction_product );
	}

	/**
	 * Get the transaction description.
	 *
	 * @since 1.36
	 *
	 * @param array $options
	 *
	 * @return bool|string
	 */
	function description( $options = array() ) {

		$description = it_exchange_get_transaction_description( $this->_transaction );

		if ( ! empty( $options['has'] ) ) {
			return (bool) trim( $description );
		}

		return $description;
	}

	/**
	 * Print the varaints.
	 *
	 * @since 1.36
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	function variants( $options = array() ) {

		$product = $this->_transaction_product;

		if ( empty( $product['itemized_data'] ) ) {
			return '';
		}

		$itemized_data = maybe_unserialize( $product['itemized_data'] );

		if ( empty( $itemized_data['it_variant_combo_hash'] ) || ! function_exists( 'it_exchange_get_variant_combo_attributes_from_hash' ) ) {

			return '';
		}

		$atts = it_exchange_get_variant_combo_attributes_from_hash( $product['product_id'], $itemized_data['it_variant_combo_hash'] );

		$out = '';

		foreach ( $atts['combo'] as $variant_group => $variant ) {
			$out .= get_the_title( $variant_group ) . ': ' . get_the_title( $variant ) . '<br>';
		}

		return $out;
	}

	/**
	 * The product's featured image
	 *
	 * @since 1.4.0
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	function featured_image( $options = array() ) {

		// Get the real product item or return empty
		if ( ( ! $product_id = empty( $this->_transaction_product['product_id'] ) ? false : $this->_transaction_product['product_id'] ) && ! $this->demo ) {
			return false;
		}

		// Return boolean if has flag was set
		if ( $options['supports'] ) {
			return it_exchange_product_supports_feature( $product_id, 'product-images' ) || $this->demo;
		}

		// Return boolean if has flag was set
		if ( $options['has'] ) {
			return it_exchange_product_has_feature( $product_id, 'product-images' ) || $this->demo;
		}

		$defaults = array(
			'format' => 'html'
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		if ( ( it_exchange_product_supports_feature( $product_id, 'product-images' ) && it_exchange_product_has_feature( $product_id, 'product-images' ) ) || $this->demo ) {

			$defaults = array(
				'size' => 'thumbnail'
			);

			$options = ITUtility::merge_defaults( $options, $defaults );

			$product_images = it_exchange_get_product_feature( $product_id, 'product-images' );

			$feature_image = array(
				'id'    => $product_images[0],
				'thumb' => wp_get_attachment_thumb_url( $product_images[0] ),
				'large' => wp_get_attachment_url( $product_images[0] ),
			);

			if ( is_array( $options['size'] ) ) {
				$img_src = wp_get_attachment_image_url( $product_images[0], $options['size'] );
			} elseif ( 'thumbnail' === $options['size'] ) {
				$img_src = $feature_image['thumb'];
			} else {
				$img_src = $feature_image['large'];
			}

			$img_src = apply_filters( 'it_exchange_theme_api_transaction_product_featured_image_src', $img_src, $this->_transaction_product, $this->_transaction );

			if ( $this->demo ) {
				$img_src = $GLOBALS['IT_Exchange']->_plugin_url . '/lib/email-notifications/assets/product-image.png';
			}

			if ( $options['format'] === 'url' ) {
				return $img_src;
			}

			ob_start();
			?>
			<div class="it-exchange-feature-image-<?php echo get_the_id(); ?> it-exchange-featured-image">
				<div class="featured-image-wrapper">
					<img alt="" src="<?php echo $img_src ?>" data-src-large="<?php echo $feature_image['large'] ?>"
					     data-src-thumb="<?php echo $feature_image['thumb'] ?>" />
				</div>
			</div>
			<?php
			$output = ob_get_clean();

			return $output;
		}

		return false;
	}

	/**
	 * Grabs a list of all downloads for a specific transaction product.
	 *
	 * Intended to be used in a while statement.
	 * If used with the has- prefix, it returns a boolean of true/false
	 * If it returns true, you may then continue your while loop with the product-download api method
	 *
	 * eg: while( it_exchange( 'transaction', 'product-downloads' ) ) { it_exchange( 'transaction', 'product_download',
	 * array( 'title' ) ); } See lib/templates/content-downloads/ files for examples
	 *
	 * @param array $options
	 *
	 * @return boolean
	 */
	function product_downloads( $options = array() ) {
		// Return false if we don't have a product id
		if ( empty( $this->_transaction_product['product_id'] ) ) {
			return false;
		}

		// Return boolean if we'er just checking
		if ( ! empty( $options['has'] ) ) {
			return it_exchange_product_has_feature( $this->_transaction_product['product_id'], 'downloads' );
		}

		// Set product id
		$product_id = $this->_transaction_product['product_id'];

		// If we made it here, we're doing a loop of transaction_product_downloads for the current query.
		// This will init/reset the transaction_product_downloads global and loop through them.
		if ( empty( $GLOBALS['it_exchange']['transaction_product_downloads'][ $product_id ] ) ) {
			$GLOBALS['it_exchange']['transaction_product_downloads'][ $product_id ] = it_exchange_get_product_feature( $product_id, 'downloads' );
			$GLOBALS['it_exchange']['transaction_product_download']                 = reset( $GLOBALS['it_exchange']['transaction_product_downloads'][ $product_id ] );

			return true;
		} else {
			if ( next( $GLOBALS['it_exchange']['transaction_product_downloads'][ $product_id ] ) ) {
				$GLOBALS['it_exchange']['transaction_product_download'] = current( $GLOBALS['it_exchange']['transaction_product_downloads'][ $product_id ] );

				return true;
			} else {
				$GLOBALS['it_exchange']['transaction_product_downloads'][ $product_id ] = array();
				end( $GLOBALS['it_exchange']['transaction_product_downloads'][ $product_id ] );
				$GLOBALS['it_exchange']['transaction_product_download'] = false;

				return false;
			}
		}
	}

	/**
	 * Returns attributes for a download that is a part of a specific transaction
	 *
	 * Intended to be used inside a while loop with it_exchange( 'transaction', 'product-downloads' );
	 * Use the attribute option to indicated what type of download attribute you want. ie: array( 'attribute' =>
	 * 'title' ); See lib/templates/content-downloads/ files for examples
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	function product_download( $options = array() ) {
		if ( ! empty( $options['has'] ) ) {
			return (boolean) $this->_transaction_product_download;
		}

		if ( empty( $options['attribute'] ) ) {
			return false;
		}

		if ( 'title' == $options['attribute'] || 'name' == $options['attribute'] ) {
			$value = get_the_title( $this->_transaction_product_download['id'] );
		}

		return $value;
	}

	/**
	 * Sets up a loop of all the hashes generated for a specific download for a specific transaction.
	 *
	 * Intended to be used in a loop. You may loop through it with the product_download_hash method once setup.
	 * The number of hashes per download will equal the quantity paid for at time of purchase for the transaction
	 * See lib/templates/content-downloads/ files for examples
	 *
	 * @return bool
	 */
	function product_download_hashes( $options = array() ) {
		// Return false if we don't have a product id
		if ( empty( $this->_transaction_product['product_id'] ) || empty( $this->_transaction_product_download ) ) {
			return false;
		}

		// Return boolean if we're just checking
		if ( ! empty( $options['has'] ) ) {
			return (boolean) it_exchange_get_download_hashes_for_transaction_product( $this->_transaction, $this->_transaction_product, $this->_transaction_product_download['id'] );
		}

		// Download ID
		$download_id = $this->_transaction_product_download['id'];

		// If we made it here, we're doing a loop of transaction_product_download_hashes for the current query.
		// This will init/reset the transaction_product_download_hashes global and loop through them.
		if ( empty( $GLOBALS['it_exchange']['transaction_product_download_hashes'][ $download_id ] ) ) {
			$GLOBALS['it_exchange']['transaction_product_download_hashes'][ $download_id ] = it_exchange_get_download_hashes_for_transaction_product( $this->_transaction, $this->_transaction_product, $download_id );
			$GLOBALS['it_exchange']['transaction_product_download_hash']                   = reset( $GLOBALS['it_exchange']['transaction_product_download_hashes'][ $download_id ] );

			return true;
		} else {
			if ( next( $GLOBALS['it_exchange']['transaction_product_download_hashes'][ $download_id ] ) ) {
				$GLOBALS['it_exchange']['transaction_product_download_hash'] = current( $GLOBALS['it_exchange']['transaction_product_download_hashes'][ $download_id ] );

				return true;
			} else {
				$GLOBALS['it_exchange']['transaction_product_download_hashes'][ $download_id ] = array();
				end( $GLOBALS['it_exchange']['transaction_product_download_hashes'][ $download_id ] );
				$GLOBALS['it_exchange']['transaction_product_download_hash'] = false;

				return false;
			}
		}
	}

	/**
	 * Prints details about a specific download has (remaining downloads, etc)
	 * See lib/templates/content-downloads/ files for examples
	 *
	 * @return string
	 */
	function product_download_hash( $options = array() ) {
		if ( ! empty( $options['has'] ) ) {
			return (boolean) $this->_transaction_product_download_hash;
		}

		if ( ! isset( $options['attribute'] ) ) {
			return false;
		}

		$defaults = array(
			'date-format' => false,
		);
		$options  = ITUtility::merge_defaults( $options, $defaults );

		$hash_data = it_exchange_get_download_data_from_hash( $this->_transaction_product_download_hash );
		if ( 'title' == $options['attribute'] || 'name' == $options['attribute'] ) {
			$options['attribute'] = 'hash';
		} else if ( 'download-limit' == $options['attribute'] ) {
			$options['attribute'] = 'download_limit';
		} else if ( 'download-count' == $options['attribute'] ) {
			$options['attribute'] = 'downloads';
		}

		if ( 'expiration-date' == $options['attribute'] ) {

			$date_format = empty( $options['date-format'] ) ? false : $options['date-format'];
			$date        = it_exchange_get_download_expiration_date( $hash_data, $date_format );
			$value       = empty( $date ) ? false : $date;
		} else if ( 'downloads-remaining' == $options['attribute'] ) {
			$limit     = empty( $hash_data['download_limit'] ) ? __( 'Unlimited Downloads', 'it-l10n-ithemes-exchange' ) : absint( $hash_data['download_limit'] );
			$count     = empty( $hash_data['downloads'] ) ? 0 : absint( $hash_data['downloads'] );
			$remaining = ( $limit - $count );
			$value     = ( $remaining < 0 ) ? 0 : $remaining;
		} else if ( 'download-url' == $options['attribute'] ) {
			$value = add_query_arg( array( 'it-exchange-download' => $hash_data['hash'] ), get_home_url() );
		} else {
			$value = isset( $hash_data[ $options['attribute'] ] ) ? $hash_data[ $options['attribute'] ] : false;
		}

		return $value;
	}

	/**
	 * The product's featured image
	 *
	 * @since 0.4.12
	 *
	 * @return string
	 */
	function product_featured_image( $options = array() ) {

		// Get the real product item or return empty
		if ( ! $product_id = empty( $this->_transaction_product['product_id'] ) ? false : $this->_transaction_product['product_id'] ) {
			return false;
		}

		// Return boolean if has flag was set
		if ( $options['supports'] ) {
			return it_exchange_product_supports_feature( $product_id, 'product-images' );
		}

		// Return boolean if has flag was set
		if ( $options['has'] ) {
			return it_exchange_product_has_feature( $product_id, 'product-images' );
		}

		if ( it_exchange_product_supports_feature( $product_id, 'product-images' )
		     && it_exchange_product_has_feature( $product_id, 'product-images' )
		) {

			$defaults = array(
				'size' => 'thumb'
			);

			$options = ITUtility::merge_defaults( $options, $defaults );
			$output  = array();

			$product_images = it_exchange_get_product_feature( $product_id, 'product-images' );

			$feature_image = array(
				'id'    => $product_images[0],
				'thumb' => wp_get_attachment_thumb_url( $product_images[0] ),
				'large' => wp_get_attachment_url( $product_images[0] ),
			);

			if ( 'thumbnail' === $options['size'] ) {
				$img_src = $feature_image['thumb'];
			} else {
				$img_src = $feature_image['large'];
			}

			ob_start();
			?>
			<div class="it-exchange-feature-image-<?php echo get_the_id(); ?> it-exchange-featured-image">
				<div class="featured-image-wrapper">
					<img alt="" src="<?php echo $img_src ?>" data-src-large="<?php echo $feature_image['large'] ?>"
					     data-src-thumb="<?php echo $feature_image['thumb'] ?>" />
				</div>
			</div>
			<?php
			$output = ob_get_clean();

			return $output;
		}

		return false;
	}

	function cart_object() {
		ITDebug::print_r( $this->_transaction );
	}
}
