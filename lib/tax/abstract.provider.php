<?php
/**
 * Tax Provider Interface.
 *
 * @since   2.0.0
 * @license GPLv2
 */

/**
 * class ITE_Tax_Provider
 */
abstract class ITE_Tax_Provider {

	/**
	 * Get the item class.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public abstract function get_item_class();

	/**
	 * Get the tax rate for a given product.
	 *
	 * @since 2.0.0
	 *
	 * @param \IT_Exchange_Product $product
	 *
	 * @return string|int
	 */
	public function get_tax_code_for_product( IT_Exchange_Product $product ) { return ''; }

	/**
	 * Get the tax code for an item.
	 *
	 * @since 2.0.0
	 *
	 * @param ITE_Line_Item $item
	 *
	 * @return string|int
	 */
	public function get_tax_code_for_item( ITE_Line_Item $item ) { return ''; }

	/**
	 * Check if a product is tax exempt.
	 *
	 * @since 2.0.0
	 *
	 * @param \IT_Exchange_Product $product
	 *
	 * @return bool
	 */
	public abstract function is_product_tax_exempt( IT_Exchange_Product $product );

	/**
	 * Add taxes to the given item.
	 *
	 * @since 2.0.0
	 *
	 * @param \ITE_Taxable_Line_Item $item
	 * @param \ITE_Cart              $cart
	 */
	public abstract function add_taxes_to( ITE_Taxable_Line_Item $item, ITE_Cart $cart );

	/**
	 * Finalize the taxes for a given cart.
	 *
	 * @since 2.0.0
	 *
	 * @param \ITE_Cart $cart
	 */
	public function finalize_taxes( ITE_Cart $cart ) { }

	/**
	 * Return the zone this tax type is restricted to, if any.
	 *
	 * @since 2.0.0
	 *
	 * @return \ITE_Zone
	 */
	public function is_restricted_to_location() { return null; }
}
