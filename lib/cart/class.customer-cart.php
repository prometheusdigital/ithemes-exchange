<?php
/**
 * Cart class.
 *
 * @since   1.36
 * @license GPLv2
 */

/**
 * Class ITE_Cart
 */
class ITE_Cart {

	/** @var \ITE_Line_Item_Repository */
	private $repository;

	/** @var ITE_Cart_Validator[] */
	private $cart_validators = array();

	/** @var ITE_Line_Item_Validator[] */
	private $item_validators = array();

	/** @var ITE_Location_Validator[] */
	private $location_validators = array();

	/** @var string */
	private $cart_id;

	/** @var IT_Exchange_Customer|null */
	private $customer;

	/** @var ITE_Cart_Feedback */
	private $feedback;

	/** @var bool */
	private $doing_merge = false;

	/**
	 * ITE_Cart constructor.
	 *
	 * @param ITE_Line_Item_Repository  $repository
	 * @param string                    $cart_id
	 * @param IT_Exchange_Customer|null $customer
	 */
	public function __construct( ITE_Line_Item_Repository $repository, $cart_id, IT_Exchange_Customer $customer = null ) {
		$this->repository = $repository;
		$this->cart_id    = $cart_id;

		if ( ! $customer && $this->is_current() ) {
			$customer = it_exchange_get_current_customer();

			if ( ! $customer instanceof IT_Exchange_Customer ) {
				$customer = null;
			}
		}

		$this->customer = $customer;
		$this->feedback = new ITE_Cart_Feedback();

		foreach ( self::validators() as $validator ) {
			if ( $validator instanceof ITE_Cart_Validator ) {
				$this->add_cart_validator( $validator );
			} elseif ( $validator instanceof ITE_Line_Item_Validator ) {
				$this->add_item_validator( $validator );
			} elseif ( $validator instanceof ITE_Location_Validator ) {
				$this->add_location_validator( $validator );
			}
		}

		/**
		 * Fires when a cart is constructed.
		 *
		 * @since 1.36.0
		 *
		 * @param \ITE_Cart $this
		 */
		do_action( 'it_exchange_construct_cart', $this );
	}

	/**
	 * Create a new cart.
	 *
	 * This should only be called once for each cart session. If this cart is backed by the current session, the cart ID
	 * will be set in the session.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Line_Item_Repository|null $repository Specify the repository to used. If null, the session
	 *                                                   repository will be used.
	 * @param \IT_Exchange_Customer|null     $customer   Specify the customer to use. If null, and this is the active
	 *                                                   cart, the current customer will be used.
	 *
	 * @return \ITE_Cart
	 */
	public static function create( ITE_Line_Item_Repository $repository = null, IT_Exchange_Customer $customer = null ) {

		$repository = $repository ?: new ITE_Line_Item_Session_Repository(
			it_exchange_get_session(), new ITE_Line_Item_Repository_Events()
		);

		if ( ! $customer && ( $c = it_exchange_get_current_customer() ) && $c instanceof IT_Exchange_Customer ) {
			$customer = $c;
		}

		$is_current = $repository instanceof ITE_Line_Item_Session_Repository && $repository->backed_by_active_session();
		$cart_id    = it_exchange_create_cart_id();

		if ( $is_current ) {
			it_exchange_update_cart_data( 'cart_id', $cart_id );
		}

		$cart = new self( $repository, $cart_id, $customer );

		if ( $cart->get_billing_address() ) {
			$compare = new ITE_In_Memory_Address( $cart->get_billing_address()->to_array() );
			$address = $cart->get_billing_address();

			if ( ! $cart->validate_location( $address ) ) {
				$cart->set_billing_address( null );
			} elseif ( ! $address->equals( $compare ) ) { // Handle changes by reference
				$cart->set_billing_address( $address );
			}
		}

		if ( $cart->get_shipping_address() ) {
			$compare = new ITE_In_Memory_Address( $cart->get_billing_address()->to_array() );
			$address = $cart->get_shipping_address();

			if ( ! $cart->validate_location( $cart->get_shipping_address() ) ) {
				$cart->set_shipping_address( null );
			} elseif ( ! $address->equals( $compare ) ) { // Handle changes by reference
				$cart->set_shipping_address( $address );
			}
		}

		/**
		 * Fires when a new cart is created.
		 *
		 * @since 1.36.0
		 *
		 * @param \ITE_Cart $cart
		 */
		do_action( 'it_exchange_create_cart', $cart );

		return $cart;
	}

	/**
	 * Get the cart ID.
	 *
	 * @since 1.36
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->cart_id;
	}

	/**
	 * Get the customer this cart belongs to.
	 *
	 * @since 1.36
	 *
	 * @return IT_Exchange_Customer|null
	 */
	public function get_customer() {
		return $this->customer;
	}

	/**
	 * Check if the cart is the current active cart.
	 *
	 * @since 1.36
	 *
	 * @return bool
	 */
	public function is_current() {
		return $this->get_id() === it_exchange_get_cart_id();
	}

	/**
	 * Returns true if the cart is undergoing a merge.
	 *
	 * @since 1.36
	 *
	 * @return boolean
	 */
	public function is_doing_merge() {
		return $this->doing_merge;
	}

	/**
	 * Get the customer's shipping address.
	 *
	 * @since 1.36.0
	 *
	 * @return ITE_Location|null
	 */
	public function get_shipping_address() {
		return $this->get_repository()->get_shipping_address();
	}

	/**
	 * Set the customer's shipping address.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Location|null $location
	 *
	 * @return bool
	 */
	public function set_shipping_address( ITE_Location $location = null ) {

		$previous = $this->get_shipping_address();

		if ( $location && ( ! $previous || ! $location->equals( $previous ) ) ) {
			$valid = $this->validate_location( $location );

			if ( ! $valid ) {
				return false;
			}
		}

		if ( $result = $this->get_repository()->set_shipping_address( $location ) ) {
			/**
			 * Fires when the cart's shipping address has been updated.
			 *
			 * @since 1.36.0
			 *
			 * @param \ITE_Cart          $cart
			 * @param \ITE_Location|null $previous
			 * @param \ITE_Location|null $location
			 */
			do_action( 'it_exchange_set_cart_shipping_address', $this, $previous, $location );
		}

		return $result;
	}

	/**
	 * Get the customer's billing address.
	 *
	 * @since 1.36.0
	 *
	 * @return ITE_Location|null
	 */
	public function get_billing_address() {
		return $this->get_repository()->get_billing_address();
	}

	/**
	 * Set the customer's billing address.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Location|null $location
	 *
	 * @return bool
	 */
	public function set_billing_address( ITE_Location $location = null ) {

		$previous = $this->get_billing_address();

		if ( $location && ( ! $previous || ! $location->equals( $previous ) ) ) {
			$valid = $this->validate_location( $location );

			if ( ! $valid ) {
				return false;
			}
		}

		if ( $result = $this->get_repository()->set_billing_address( $location ) ) {
			/**
			 * Fires when the cart's billing address has been updated.
			 *
			 * @since 1.36.0
			 *
			 * @param \ITE_Cart          $cart
			 * @param \ITE_Location|null $previous
			 * @param \ITE_Location|null $location
			 */
			do_action( 'it_exchange_set_cart_billing_address', $this, $previous, $location );
		}

		return $result;
	}

	/**
	 * Validate a location against all registered location validators.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Location $location
	 *
	 * @return bool
	 */
	protected final function validate_location( ITE_Location $location ) {

		foreach ( $this->location_validators as $validator ) {
			if ( ! $validator->can_validate() || $validator->can_validate()->contains( $location ) ) {
				if ( ! $validator->validate_for_cart( $location, $this ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Add a line item to the cart.
	 *
	 * @since 1.36
	 *
	 * @param \ITE_Line_Item $item
	 * @param bool           $coerce
	 *
	 * @return bool
	 *
	 * @throws \ITE_Line_Item_Coercion_Failed_Exception
	 * @throws \ITE_Cart_Coercion_Failed_Exception
	 */
	public function add_item( ITE_Line_Item $item, $coerce = true ) {

		if ( $item instanceof ITE_Line_Item_Repository_Aware ) {
			$item->set_line_item_repository( $this->get_repository() );
		}

		if ( $item instanceof ITE_Cart_Aware ) {
			$item->set_cart( $this );
		}

		$method = "add_{$item->get_type()}_item";

		if ( ! method_exists( $this, $method ) || $this->{$method}( $item ) !== false ) {
			$this->get_repository()->save( $item );

			$new_added = true;
		} else {
			$new_added = false;
		}

		if ( $coerce ) {
			$this->coerce( $item );
		}

		if ( ! $this->validate() ) {
			return false;
		}

		if ( ! $new_added ) {
			return true;
		}

		/**
		 * Fires when a line item is added to the cart.
		 *
		 * @since 1.36
		 *
		 * @param \ITE_Line_Item $item
		 * @param \ITE_Cart      $cart
		 */
		do_action( 'it_exchange_add_line_item_to_cart', $item, $this );

		$item = $this->get_item( $item->get_type(), $item->get_id() );

		/**
		 * Fires when a line item is added to the cart.
		 *
		 * The dynamic portion of this hook refers to the line item type.
		 *
		 * @since 1.36
		 *
		 * @param \ITE_Line_Item $item
		 * @param \ITE_Cart      $cart
		 */
		do_action( "it_exchange_add_{$item->get_type()}_to_cart", $item, $this );

		return true;
	}

	/**
	 * Get the line items contained in the cart.
	 *
	 * @since 1.36
	 *
	 * @param string $type    If empty, all line items will be returned.
	 * @param bool   $flatten Whether to flatten aggregate line items.
	 *
	 * @return ITE_Line_Item_Collection|ITE_Line_Item[]
	 *
	 * @throws InvalidArgumentException If $type is invalid.
	 */
	public function get_items( $type = '', $flatten = false ) {

		if ( $type ) {
			self::assert_type( $type );
		}

		if ( $flatten ) {
			$items = $this->get_items()->flatten();

			return $type ? $items->with_only( $type ) : $items;
		}

		return $this->get_repository()->all( $type )->set_cart( $this );
	}

	/**
	 * Retrieve a line item from the cart.
	 *
	 * @since 1.36
	 *
	 * @param string     $type
	 * @param string|int $id
	 *
	 * @return \ITE_Line_Item|null
	 *
	 * @throws InvalidArgumentException If $type is invalid.
	 */
	public function get_item( $type, $id ) {

		$items = $this->get_items( $type );

		foreach ( $items as $item ) {
			if ( $item->get_id() === $id ) {
				return $item;
			}
		}

		return null;
	}

	/**
	 * Remove an item from the cart.
	 *
	 * @since 1.36
	 *
	 * @param string     $type
	 * @param string|int $id
	 *
	 * @return bool False if item could not be found.
	 *
	 * @throws \InvalidArgumentException If invalid type given.
	 */
	public function remove_item( $type, $id ) {

		$item = $this->get_item( $type, $id );

		if ( ! $item ) {
			return false;
		}

		$deleted = $this->get_repository()->delete( $item );

		if ( $deleted ) {
			/**
			 * Fires when a line item is removed from the cart.
			 *
			 * @since 1.36
			 *
			 * @param \ITE_Line_Item $item
			 * @param \ITE_Cart      $cart
			 */
			do_action( 'it_exchange_remove_line_item_from_cart', $item, $this );

			/**
			 * Fires when a line item is removed from the cart.
			 *
			 * The dynamic portion of this hook refers to the line item type.
			 *
			 * @since 1.36
			 *
			 * @param \ITE_Line_Item $item
			 * @param \ITE_Cart      $cart
			 */
			do_action( "it_exchange_remove_{$item->get_type()}_from_cart", $item, $this );

			if ( ! $this->get_items()->count() ) {
				$this->destroy();
			}
		}

		return $deleted;
	}

	/**
	 * Remove all line items, or all line items of a given type from the cart.
	 *
	 * @since 1.36
	 *
	 * @param string $type    The item type. Optionally. If unspecified, all item types will be removed.
	 * @param bool   $flatten Whether to remove all items, including aggregates' children.
	 *
	 * @return bool
	 *
	 * @throws \InvalidArgumentException If invalid type given.
	 */
	public function remove_all( $type = '', $flatten = false ) {

		foreach ( $this->get_items( $type, $flatten ) as $item ) {
			$this->get_repository()->delete( $item );

			// This hook is documented in lib/cart/class.customer-cart.php
			do_action( 'it_exchange_remove_line_item_from_cart', $item, $this );

			// This hook is documented in lib/cart/class.customer-cart.php
			do_action( "it_exchange_remove_{$item->get_type()}_from_cart", $item, $this );
		}

		$this->destroy();

		return true;
	}

	/**
	 * Callback to perform custom processing when a cart product line item is added to the cart.
	 *
	 * @since 1.36
	 *
	 * @param \ITE_Cart_Product $product
	 *
	 * @return bool
	 */
	protected function add_product_item( ITE_Cart_Product $product ) {
		ITE_Cart_Product::generate_cart_product_id( $product );

		if ( $dupe = $this->get_item( 'product', $product->get_id() ) ) {

			if ( $this->is_doing_merge() ) {
				return false; // Don't combine quantities when doing a merge
			}

			$dupe->set_quantity( $product->get_quantity() + $dupe->get_quantity() );
			$this->get_repository()->save( $dupe );

			return false;
		}

		return true;
	}

	/**
	 * Callback to perform custom processing when a tax line item is added to the cart.
	 *
	 * @since 1.36
	 *
	 * @param ITE_Tax_Line_Item $tax
	 *
	 * @return bool
	 */
	protected function add_tax_item( ITE_Tax_Line_Item $tax ) {
		foreach ( $this->get_items() as $item ) {
			if ( $item instanceof ITE_Taxable_Line_Item && $tax->applies_to( $item ) ) {
				$item->add_tax( $tax->create_scoped_for_taxable( $item ) );
				$this->get_repository()->save( $item );
			}
		}

		return false;
	}

	/**
	 * Callback to perform custom processing when a coupon line item is added to the cart.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Coupon_Line_Item $coupon
	 *
	 * @return bool
	 */
	protected function add_coupon_item( ITE_Coupon_Line_Item $coupon ) {

		/** @var ITE_Cart_Product $product */
		foreach ( $this->get_items( 'product' ) as $product ) {
			if ( $coupon->get_coupon()->valid_for_product( $product ) ) {
				$product->add_item( $coupon->create_scoped_for_product( $product ) );
				$this->get_repository()->save( $product );
			}
		}

		return false;
	}

	/**
	 * Calculate the total of all line items or a given line item type.
	 *
	 * This calculation is not cached.
	 *
	 * @since 1.36
	 *
	 * @param string $type
	 * @param bool   $unravel
	 *
	 * @return float
	 */
	public function calculate_total( $type = '', $unravel = true ) {
		return $this->get_items( $type, $unravel )->total();
	}

	/**
	 * Validate the current state of the cart.
	 *
	 * @since 1.36
	 *
	 * @return bool
	 */
	public function validate() {

		$feedback = $this->feedback;

		foreach ( $this->cart_validators as $cart_validator ) {
			if ( ! $cart_validator->validate( $this, $feedback ) ) {
				return false;
			}
		}

		$items = $this->get_items();

		foreach ( $this->item_validators as $item_validator ) {
			foreach ( $items as $item ) {
				if ( $item_validator->accepts( $item->get_type() ) && ! $item_validator->validate( $item, $this, $feedback ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Coerce the cart to a valid state.
	 *
	 * @since 1.36
	 *
	 * @param \ITE_Line_Item $new_item
	 *
	 * @return bool
	 *
	 * @throws \ITE_Line_Item_Coercion_Failed_Exception
	 * @throws \ITE_Cart_Coercion_Failed_Exception
	 */
	public function coerce( ITE_Line_Item $new_item = null ) {

		$feedback = $this->feedback;
		$valid    = true;

		foreach ( $this->cart_validators as $cart_validator ) {
			if ( ! $cart_validator->coerce( $this, $new_item, $feedback ) ) {
				$valid = false;
			}
		}

		$items = $this->get_items();

		foreach ( $this->item_validators as $item_validator ) {
			foreach ( $items as $item ) {
				if ( $item_validator->accepts( $item->get_type() ) && ! $item_validator->coerce( $item, $this, $feedback ) ) {
					$valid = false;
				}
			}
		}

		return $valid;
	}

	/**
	 * Prepare the cart for purchase.
	 *
	 * @since 1.36.0
	 */
	public function prepare_for_purchase() {

		/**
		 * Fires when the cart totals should be finalized.
		 *
		 * @since 1.36.0
		 *
		 * @param \ITE_Cart $cart
		 */
		do_action( 'it_exchange_finalize_cart_totals', $this );
	}

	/**
	 * Empty the cart.
	 *
	 * @since 1.36
	 */
	public function empty_cart() {

		/**
		 * Fires when the cart is about to be emptied.
		 *
		 * @since 1.36
		 *
		 * @param \ITE_Cart $cart
		 */
		do_action( 'it_exchange_empty_cart', $this );

		$items = $this->get_items();
		$this->remove_all();

		/**
		 * Fires when the cart was just emptied.
		 *
		 * @since 1.36
		 *
		 * @param \ITE_Cart                 $cart
		 * @param \ITE_Line_Item_Collection $items Items removed from the cart.
		 */
		do_action( 'it_exchange_emptied_cart', $this, $items );
	}

	/**
	 * Merge another cart into this cart.
	 *
	 * @since 1.36
	 *
	 * @param \ITE_Cart $cart
	 * @param bool      $coerce
	 */
	public function merge( ITE_Cart $cart, $coerce = true ) {

		$this->doing_merge = true;
		$cart->doing_merge = true;

		/**
		 * Fires before a cart has been merged into another cart.
		 *
		 * @since 1.36
		 *
		 * @param \ITE_Cart $this The primary cart.
		 * @param \ITE_Cart $cart The cart being merged.
		 * @param bool      $coerce
		 */
		do_action( 'it_exchange_merge_cart', $this, $cart, $coerce );

		foreach ( $cart->get_items() as $item ) {
			$this->add_item( $item, false );
		}

		$cart->remove_all();

		$this->set_billing_address( $cart->get_billing_address() );
		$this->set_shipping_address( $cart->get_shipping_address() );

		if ( $coerce ) {
			$this->coerce();
		}

		/**
		 * Fires after a cart has been merged into another cart.
		 *
		 * @since 1.36
		 *
		 * @param \ITE_Cart $this The primary cart.
		 * @param \ITE_Cart $cart The cart being merged.
		 * @param bool      $coerce
		 */
		do_action( 'it_exchange_merged_cart', $this, $cart, $coerce );

		$this->doing_merge = false;
		$cart->doing_merge = false;
	}

	/**
	 * Destroy the cart.
	 *
	 * @since 1.36.0
	 */
	public function destroy() {

		if ( $this->is_current() ) {
			it_exchange_remove_cart_id();
		}
	}

	/**
	 * Clone this cart, saving its contents to a new repository.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Line_Item_Repository $repository
	 *
	 * @return \ITE_Cart
	 */
	public function with_new_repository( ITE_Line_Item_Repository $repository ) {

		$repository->save_many( $this->get_items()->flatten()->to_array() );
		$repository->set_billing_address( $this->get_billing_address() );
		$repository->set_shipping_address( $this->get_shipping_address() );

		$clone             = clone $this;
		$clone->repository = $repository;

		return $clone;
	}

	/**
	 * Get cart feedback.
	 *
	 * @since 1.36.0
	 *
	 * @return \ITE_Cart_Feedback
	 */
	public function get_feedback() {
		return $this->feedback;
	}

	/**
	 * Add a cart wide validator.
	 *
	 * @since 1.36
	 *
	 * @param \ITE_Cart_Validator $validator
	 *
	 * @return $this
	 */
	public function add_cart_validator( ITE_Cart_Validator $validator ) {
		$this->cart_validators[ $validator->get_name() ] = $validator;

		return $this;
	}

	/**
	 * Remove a cart wide validator.
	 *
	 * @since 1.36
	 *
	 * @param string $name
	 *
	 * @return $this
	 */
	public function remove_cart_validator( $name ) {
		unset( $this->cart_validators[ $name ] );

		return $this;
	}

	/**
	 * Add a line item validator.
	 *
	 * @since 1.36
	 *
	 * @param \ITE_Line_Item_Validator $validator
	 *
	 * @return $this
	 */
	public function add_item_validator( ITE_Line_Item_Validator $validator ) {
		$this->item_validators[ $validator->get_name() ] = $validator;

		return $this;
	}

	/**
	 * Remove a line item validator.
	 *
	 * @since 1.36
	 *
	 * @param string $name
	 *
	 * @return $this
	 */
	public function remove_item_validator( $name ) {
		unset( $this->item_validators[ $name ] );

		return $this;
	}

	/**
	 * Add a location validator.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Location_Validator $validator
	 *
	 * @return $this
	 */
	public function add_location_validator( ITE_Location_Validator $validator ) {
		$this->location_validators[ $validator->get_name() ] = $validator;

		return $this;
	}

	/**
	 * Remove a location validator.
	 *
	 * @since 1.36.0
	 *
	 * @param string $name
	 *
	 * @return $this
	 */
	public function remove_location_validator( $name ) {
		unset( $this->location_validators[ $name ] );

		return $this;
	}

	/**
	 * Get the repository being used for persistence.
	 *
	 * @since 1.36
	 *
	 * @return \ITE_Line_Item_Repository
	 */
	public function get_repository() {
		return $this->repository;
	}

	/**
	 * Assert that the given type is valid.
	 *
	 * @since 1.36
	 *
	 * @param string $type
	 *
	 * @throws InvalidArgumentException
	 */
	protected static function assert_type( $type ) {
		if ( ! is_string( $type ) || trim( $type ) === '' ) {
			throw new InvalidArgumentException( '$type must be non-zero length string.' );
		}
	}

	/**
	 * Get all available validators.
	 *
	 * @since  1.36.0
	 *
	 * @return (\ITE_Line_Item_Validator|\ITE_Cart_Validator|\ITE_Location_Validator)[]
	 */
	private static function validators() {
		$validators = array(
			new ITE_Multi_Item_Cart_Validator(),
			new ITE_Multi_Item_Product_Validator(),
			new ITE_Product_Inventory_Validator()
		);

		/**
		 * Filter the available validators.
		 *
		 * @since 1.36.0
		 *
		 * @param array $validators
		 */
		return apply_filters( 'it_exchange_cart_validators', $validators );
	}
}
