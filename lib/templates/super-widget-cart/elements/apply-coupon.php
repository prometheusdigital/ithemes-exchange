<?php
/**
 * This is the default template for the 
 * super-widget-checkout apply-coupon element.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/super-widget-checkout/elements directory
 * located in your theme.
*/
?>

<?php do_action( 'it_exchange_super_widget_cart_actions_before_apply_coupon' ); ?>
<div class="coupon">
	<?php do_action( 'it_exchange_super_widget_cart_actions_begin_apply_coupon' ); ?>
	<?php it_exchange( 'coupons', 'apply', array( 'type' => 'cart' ) ); ?>
	<?php it_exchange( 'cart', 'update', array( 'class' => 'it-exchange-apply-coupon-button', 'label' => __( 'Apply', 'LION' ) ) ); ?>
	<?php do_action( 'it_exchange_super_widget_cart_actions_end_apply_coupon' ); ?>
</div>
<?php do_action( 'it_exchange_super_widget_cart_actions_after_apply_coupon' ); ?>