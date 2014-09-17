<?php
/**
 * Default template part for the checkout page.
 *
 * @since 0.4.0
 * @version CHANGEME
 * @link http://ithemes.com/codex/page/Exchange_Template_Updates* @updated 1.0.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange/ directory located
 * in your theme.
*/
?>

<?php do_action( 'it_exchange_content_checkout_before_wrap' ); ?>
<div id="it-exchange-cart" class="it-exchange-wrap it-exchange-checkout">
	<?php do_action( 'it_exchange_content_checkout_begin_wrap' ); ?>

	<?php it_exchange_get_template_part( 'messages' ); ?>

	<?php if ( it_exchange( 'cart', 'has-cart-items' ) ) :  ?>

		<?php
		it_exchange_get_template_part( 'content-checkout/loops/purchase-requirements' );
		$purchase_requirements_class = ( false !== ( $notification = it_exchange_get_next_purchase_requirement() ) ) ? ' it-exchange-requirements-active' : '';
		$purchase_requirements_class = apply_filters( 'it_exchange_purchase_requirements_class_for_order_details', $purchase_requirements_class );
		?>
		<div class="it-exchange-order-details<?php echo esc_attr_e( $purchase_requirements_class ); ?>">
			<?php
				// Loops we want to include, in the order we want them.
				$loops = array( 'items', 'coupons', 'totals', 'actions' );
				foreach ( it_exchange_get_template_part_loops( 'content-cart', 'has-cart-item', $loops ) as $loop ) :
					it_exchange_get_template_part( 'content-checkout/loops/' . $loop );
				endforeach;
			?>
		</div>

	<?php else : ?>
		<?php do_action( 'it_exchange_content_cart_start_empty_cart' ); ?>
			<p><?php _e( 'There are no items in your cart', 'LION' ); ?></p>
		<?php do_action( 'it_exchange_content_cart_end_empty_cart' ); ?>
	<?php endif; ?>
	<?php do_action( 'it_exchange_content_cart_end_wrap' ); ?>
</div>
<?php do_action( 'it_exchange_content_cart_after_wrap' ); ?>
