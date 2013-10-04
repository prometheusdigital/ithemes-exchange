<?php
/**
 * This is the default template part for the
 * billing element in the billing-address
 * purchase-requriements in the content-checkout template part.
 *
 * @since CHANGEME
 * @version CHANGEME
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file to the 
 * /exchange/content-checkout/elements/purchase-requirements/billing-address/elements/
 * directory located in your theme.
*/
?>
<?php do_action( 'it_exchange_content_checkout_billing_address_purchase_requirement_before_shipping_element' ); ?>
<div class="it-exchange-billing it-exchange-clear-left">
	<?php it_exchange( 'billing', 'shipping' ); ?>
</div>
<?php do_action( 'it_exchange_content_checkout_billing_address_purchase_requirement_after_shipping_element' ); ?>
