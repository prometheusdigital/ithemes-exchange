<?php
/**
 * This is the default template part for the
 * fields loop in the shipping-address purchase-requriements
 * in the content-checkout template part.
 *
 * @since CHANGEME
 * @version CHANGEME
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file to the 
 * /exchange/content-checkout/elements/purchase-requirements/shipping-address/loops/
 * directory located in your theme.
*/
?>
<?php do_action( 'it_exchange_content_checkout_shipping_address_purchase_requirement_before_fields_loop' ); ?>
<?php $fields = array( 'first_name', 'last_name', 'clearfix', 'address_1', 'clearfix', 'address_2', 'clearfix', 'city', 'state', 'clearfix', 'zip', 'clearfix', 'country', 'clearfix', 'nonce' ); ?>
<?php foreach( it_exchange_get_template_part_elements( 'content_checkout/elements/purchase-requirements/shipping-address/elements/', 'fields', $fields ) as $field ) : ?>
	<?php
	/**
	 * Theme and add-on devs should add code to this loop by 
	 * hooking into it_exchange_get_template_part_elements filter
	 * and adding the appropriate template file to their theme or add-on
	 */
	it_exchange_get_template_part( 'content', 'checkout/elements/purchase-requirements/shipping-address/elements/' . $field );
	?>
<?php endforeach; ?>
<?php do_action( 'it_exchange_content_checkout_shipping_address_purchase_requirement_after_fields_loop' ); ?>
