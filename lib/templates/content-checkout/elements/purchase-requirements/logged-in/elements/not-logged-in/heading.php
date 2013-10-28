<?php
/**
 * This is the default template part for the
 * header element in the not-logged-in loop for the 
 * purchase-requriements in the content-checkout 
 * template part.
 *
 * @since 1.5.0
 * @version 1.0.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file to the 
 * /exchange/content-checkout/elements/purchase-requirements/logged-in/elements/not-logged-in
 * directory located in your theme.
*/
?>
<?php do_action( 'it_exchange_content_checkout_logged_in_purchase_requirement_not_logged_in_before_header_element' ); ?>
<div class="it-exchange-logged-in-purchase-requirement-not-logged-in-header">
	<h3><?php _e( 'Register or Log in', 'LION' ); ?></h3>
</div>
<?php do_action( 'it_exchange_content_checkout_logged_in_purchase_requirement_not_logged_in_after_header_element' ); ?>
