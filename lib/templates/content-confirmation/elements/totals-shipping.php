<?php
/**
 * This is the default template for the Shipping
 * element in the totals loop of the content-cart
 * template part. It was added by Simple Shipping core add-on.
 *
 * @since 1.4.0
 * @version 1.0.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-confirmation/elements/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_confirmation_before_totals_shipping_simple_element' ); ?>
<div class="it-exchange-confirmation-totals-title it-exchange-table-column">
	<?php do_action( 'it_exchange_content_confirmation_begin_totals_shipping_simple_element_label' ); ?>
	<div class="it-exchange-table-column-inner">
		<?php _e( 'Shipping', 'LION' ); ?>
	</div>
	<?php do_action( 'it_exchange_content_confirmation_end_totals_shipping_simple_element_label' ); ?>
</div>
<div class="it-exchange-confirmation-totals-amount it-exchange-table-column">
	<?php do_action( 'it_exchange_content_confirmation_begin_totals_shipping_simple_element_value' ); ?>
	<div class="it-exchange-table-column-inner">
		<?php esc_attr_e( it_exchange_get_confirmation_shipping_cost() ); ?>
	</div>
	<?php do_action( 'it_exchange_content_confirmation_end_totals_shipping_simple_element_value' ); ?>
</div>
<?php do_action( 'it_exchange_content_confirmation_after_totals_shipping_simple_element' ); ?>
