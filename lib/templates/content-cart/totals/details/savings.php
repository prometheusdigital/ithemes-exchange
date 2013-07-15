<?php
/**
 * This is the default template for the Savings detail in the totals loop of the content-cart.php template part
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_content_cart_totals_details_before_savings' ); ?>
<?php if ( it_exchange( 'coupons', 'supported', 'type=cart' ) && it_exchange( 'coupons', 'has-applied', 'type=cart' ) ) : ?>
	<div class="it-exchange-cart-totals-title it-exchange-cart-savings it-exchange-table-column">
		<div class="it-exchange-table-column-inner">
			<?php _e( 'Savings', 'LION' ); ?>
		</div>
	</div>
	<div class="it-exchange-cart-totals-title it-exchange-cart-savings it-exchange-table-column">
		<div class="it-exchange-table-column-inner">
			<?php it_exchange( 'coupons', 'total-discount', array( 'type' => 'cart' ) ); ?>
		</div>
	</div>
<?php endif ?>
<?php do_action( 'it_exchange_content_cart_totals_details_after_savings' ); ?>
