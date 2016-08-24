<?php
/**
 * Tax Manager.
 *
 * @since   1.36.0
 * @license GPLv2
 */

/**
 * Class ITE_Tax_Manager
 */
class ITE_Tax_Manager implements ITE_Cart_Aware {

	/** @var ITE_Tax_Provider[] */
	private $providers = array();

	/** @var bool */
	private $use_shipping = false;

	/** @var ITE_Cart */
	private $cart;

	/** @var ITE_Location */
	private $current_location;

	/**
	 * ITE_Tax_Manager constructor.
	 *
	 * @param \ITE_Cart $cart
	 */
	public function __construct( ITE_Cart $cart ) {
		$this->cart         = $cart;
		$this->use_shipping = (bool) $cart->get_shipping_address();

		if ( $this->use_shipping ) {
			$this->current_location = new ITE_In_Memory_Address( $this->cart->get_shipping_address()->to_array() );
		} else {
			$this->current_location = new ITE_In_Memory_Address( $this->cart->get_billing_address()->to_array() );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function set_cart( ITE_Cart $cart ) {
		$this->cart = $cart;
	}

	/**
	 * Register a tax provider.
	 * @since 1.36.0
	 *
	 * @param \ITE_Tax_Provider $provider
	 *
	 * @return $this
	 */
	public function register_provider( ITE_Tax_Provider $provider ) {
		$this->providers[] = $provider;

		return $this;
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.36.0
	 */
	public function hooks() {
		add_action( 'it_exchange_add_line_item_to_cart', array( $this, 'on_add_item' ), 10, 2 );
		add_action( 'it_exchange_finalize_cart_totals', array( $this, 'finalize_totals' ) );
		add_action( 'it_exchange_set_cart_shipping_address', array( $this, 'shipping_updated' ) );
		add_action( 'it_exchange_set_cart_billing_address', array( $this, 'billing_updated' ) );
		add_action( 'it_exchange_merged_cart', array( $this, 'cart_merged' ), 10, 3 );
	}

	/**
	 * Add taxes when an item is added to the cart.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Line_Item $item
	 * @param \ITE_Cart      $cart
	 */
	public function on_add_item( ITE_Line_Item $item, ITE_Cart $cart ) {

		if ( ! $item instanceof ITE_Taxable_Line_Item ) {
			return;
		}

		if ( $cart->get_id() !== $this->cart->get_id() ) {
			return;
		}

		foreach ( $this->providers as $provider ) {

			$zone = $provider->is_restricted_to_location();

			if ( ! $this->current_location ) {
				if ( ! $zone ) {
					$this->add_taxes_to_item( $item, $provider );
				}

				continue;
			} elseif ( $zone && $zone->contains( $zone->mask( $this->current_location ) ) ) {
				$this->add_taxes_to_item( $item, $provider );
			} elseif ( ! $zone ) {
				$this->add_taxes_to_item( $item, $provider );
			}
		}
	}

	/**
	 * Finalize tax totals on the cart.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Cart $cart
	 */
	public function finalize_totals( ITE_Cart $cart ) {

		foreach ( $this->providers as $provider ) {
			$provider->finalize_taxes( $cart );
		}
	}

	/**
	 * When the shipping address is updated, possibly recalculate taxes.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Cart $cart
	 */
	public function shipping_updated( ITE_Cart $cart ) {

		if ( $cart->get_id() !== $this->cart->get_id() ) {
			return;
		}

		if ( ! $this->use_shipping ) {
			return;
		}

		$this->handle_address_update( $this->cart->get_shipping_address() );
	}

	/**
	 * When the billing address is updated, possibly recalculate taxes.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Cart $cart
	 */
	public function billing_updated( ITE_Cart $cart ) {

		if ( $cart->get_id() !== $this->cart->get_id() ) {
			return;
		}

		if ( $this->use_shipping ) {
			return;
		}

		$this->handle_address_update( $this->cart->get_billing_address() );
	}

	/**
	 * Handle carts merging.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Cart $cart
	 * @param \ITE_Cart $other
	 * @param bool      $coerce
	 */
	public function cart_merged( ITE_Cart $cart, ITE_Cart $other, $coerce ) {

		if ( $cart->get_id() !== $this->cart->get_id() && $other->get_id() !== $this->cart->get_id() ) {
			return;
		}

		if ( $this->use_shipping ) {
			$new = $cart->get_shipping_address();
		} else {
			$new = $cart->get_billing_address();
		}

		if ( $new && $new->equals( $this->current_location ) ) {
			return;
		}

		$this->handle_address_update( $new );

		if ( $coerce ) {
			$cart->coerce();
		}
	}

	/**
	 * Handle an address being updated.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Location|null $new_address
	 */
	protected function handle_address_update( ITE_Location $new_address = null ) {

		foreach ( $this->providers as $provider ) {

			if ( ( $zone = $provider->is_restricted_to_location() ) === null ) {
				continue;
			}

			if ( $new_address === null ) {
				$this->cart->get_items( 'tax', true )->with_only_instances_of( $provider->get_item_class() )->delete();

				continue;
			}

			$masked = $zone->mask( $new_address );

			if ( $this->current_location->equals( $masked ) ) {
				continue;
			} elseif ( $zone->contains( $masked ) ) {
				$this->cart->get_items( 'tax', true )->with_only_instances_of( $provider->get_item_class() )->delete();
				foreach ( $this->cart->get_items()->taxable() as $item ) {
					$this->add_taxes_to_item( $item, $provider );
				}
			} else {
				$this->cart->get_items( 'tax', true )->with_only_instances_of( $provider->get_item_class() )->delete();
			}
		}
	}

	/**
	 * Add taxes to a taxable item for a provider.
	 *
	 * @since 1.36.0
	 *
	 * @param \ITE_Taxable_Line_Item $item
	 * @param \ITE_Tax_Provider      $provider
	 */
	protected function add_taxes_to_item( ITE_Taxable_Line_Item $item, ITE_Tax_Provider $provider ) {
		$provider->add_taxes_to( $item, $this->cart );
	}
}