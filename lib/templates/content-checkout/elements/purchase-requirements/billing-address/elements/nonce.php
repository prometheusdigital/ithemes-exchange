<?php
/**
 * This is the default template part for the
 * nonce element in the billing-address
 * purchase-requriements in the content-checkout template part.
 *
 * @since 1.3.0
 * @version 1.3.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file to the 
 * /exchange/content-checkout/elements/purchase-requirements/billing-address/elements/
 * directory located in your theme.
*/
?>
<?php wp_nonce_field( 'it-exchange-update-checkout-billing-address-' . it_exchange_get_session_id(), 'it-exchange-update-billing-address' ); ?>
