<?php
/**
 * This is the default template for the Total
 * element in the totals loop of the content-cart
 * template part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-cart/elements/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_cart_before_totals_total_element' ); ?>
<div class="it-exchange-cart-totals-title it-exchange-table-column">
	<?php do_action( 'it_exchange_content_cart_begin_totals_total_element_label' ); ?>
	<div class="it-exchange-table-column-inner">
		<?php _e( 'Total', 'LION' ); ?>
	</div>
	<?php do_action( 'it_exchange_content_cart_end_totals_total_element_label' ); ?>
</div>
<div class="it-exchange-cart-totals-amount it-exchange-table-column">
	<?php do_action( 'it_exchange_content_cart_begin_totals_total_element_value' ); ?>
	<div class="it-exchange-table-column-inner">
		<?php it_exchange( 'cart', 'total' ); ?>
	</div>
	<?php do_action( 'it_exchange_content_cart_end_totals_total_element_value' ); ?>
</div>
<?php do_action( 'it_exchange_content_cart_after_totals_total_element' ); ?>