<?php
/**
 * This is the default template part for the
 * coupon discount detail in the coupons loop of
 * the content-checkout template part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-checkout/coupons/details/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_checkout_coupon_details_before_discount' ); ?>
<div class="it-exchange-cart-coupon-discount it-exchange-table-column">
	<?php do_action( 'it_exchange_content_checkout_coupon_details_begin_discount' ); ?>
	<div class="it-exchange-table-column-inner">
		<?php it_exchange( 'coupons', 'discount' ); ?>
	</div>
	<?php do_action( 'it_exchange_content_checkout_coupon_details_end_discount' ); ?>
</div>
<?php do_action( 'it_exchange_content_checkout_coupon_details_after_discount' ); ?>