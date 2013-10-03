<?php
/**
 * This is the default template part for the
 * actions loop in the billing address 
 * purchase-requriements in the content-checkout 
 * template part.
 *
 * @since 1.3.0
 * @version 1.3.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file to the 
 * /exchange/content-checkout/elements/purchase-requirements/billing-address/loops/
 * directory located in your theme.
*/
?>
<?php do_action( 'it_exchange_content_checkout_purchase_requirement_billing_before_actions_loop' ); ?>
<?php foreach ( it_exchange_get_template_part_elements( 'content_checkout/elements/purchase-requirements/billing-address/elements/', 'actions', array( 'submit', 'clearfix', 'cancel' ) ) as $action ) : ?>
	<?php 
	/** 
	 * Theme and add-on devs should add code to this loop by 
	 * hooking into it_exchange_get_template_part_elements filter
	 * and adding the appropriate template file to their theme or add-on
	 */
	it_exchange_get_template_part( 'content', 'checkout/elements/purchase-requirements/billing-address/elements/' . $action );
	?>
<?php endforeach; ?>
<?php do_action( 'it_exchange_content_checkout_purchase_requirement_billing_after_actions_loop' ); ?>
