<?php
/**
 * This is the default template part for the coupon remove detail in the coupons loop of the content-cart template part
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_content_cart_coupon_details_before_remove' ); ?>
<div class="it-exchange-cart-coupon-remove it-exchange-table-column cart-remove">
	<?php do_action( 'it_exchange_content_cart_coupon_details_begin_remove' ); ?>
	<div class="it-exchange-table-column-inner">
		<?php it_exchange( 'coupons', 'remove', array( 'type' => 'cart' ) ); ?>
	</div>
	<?php do_action( 'it_exchange_content_cart_coupon_details_end_remove' ); ?>
</div>
<?php do_action( 'it_exchange_content_cart_coupon_details_after_remove' ); ?>
