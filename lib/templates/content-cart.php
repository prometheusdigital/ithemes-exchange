<?php
/**
 * This file contains the default template part for the cart's content
 * @since 0.4.0
 * @package IT_Exchange
*/
?>
<?php it_exchange_get_template_part( 'messages' ); ?>

<?php if ( it_exchange( 'cart', 'has-cart-items' ) ) :  ?>
	<?php it_exchange( 'cart', 'form-open' ); ?>
		<div id="it-exchange-cart">
			<div class="cart-items-coupons">
				<div class="cart-items cart-table">
					<?php while ( it_exchange( 'cart', 'cart-items' ) ) : ?>
						<div class="cart-item cart-row">
							<!-- NOTE This will need the product image API -->
							<div class="cart-item-thumbnail cart-column">
								<div class="cart-column-inner">
									<img src="http://placehold.it/80x80" />
								</div>
							</div>
							<div class="cart-item-title cart-column">
								<div class="cart-column-inner">
									<a href="<?php it_exchange( 'cart-item', 'permalink' ) ?>"><?php it_exchange( 'cart-item', 'title' ) ?></a>
								</div>
							</div>
							<div class="cart-item-quantity cart-column">
								<div class="cart-column-inner">
									<?php it_exchange( 'cart-item', 'quantity' ) ?>
								</div>
							</div>
							<div class="cart-item-subtotal cart-column">
								<div class="cart-column-inner">
									<?php it_exchange( 'cart-item', 'subtotal' ); ?>
								</div>
							</div>
							<div class="cart-item-remove cart-column cart-remove">
								<div class="cart-column-inner">
									<?php it_exchange( 'cart-item', 'remove' ) ?>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				
				<?php if ( it_exchange( 'coupons', 'supported', 'type=cart' ) && it_exchange( 'coupons', 'has-applied', 'type=cart' ) ) : ?>
					<div class="cart-coupons cart-table">
						<?php while ( it_exchange( 'coupons', 'applied', 'type=cart' ) ) : ?>
							<div class='cart-coupon cart-row'>
								<div class="cart-coupon-code cart-column">
									<div class="cart-column-inner">
										<?php it_exchange( 'coupons', 'code' ); ?>
									</div>
								</div>
								<div class="cart-coupon-discount cart-column">
									<div class="cart-column-inner">
										<?php it_exchange( 'coupons', 'discount' ); ?>
									</div>
								</div>
								<div class="cart-coupon-remove cart-column cart-remove">
									<div class="cart-column-inner">
										<?php it_exchange( 'coupons', 'remove', 'type=cart' ); ?>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="cart-totals-wrapper">
				<div class="cart-totals">
					<div class="totals-column totals-titles cart-column">
						<p><?php _e( 'Subtotal', 'LION' ); ?></p>
						<p><?php _e( 'Savings', 'LION' ); ?></p>
						<p><?php _e( 'Total', 'LION' ); ?></p>
					</div>
					<div class="totals-column totals-amounts cart-column">
						<p class="cart-subtotal"><?php it_exchange( 'cart', 'subtotal' ); ?></p>
						<p class="cart-discount"><?php it_exchange( 'coupons', 'total-discount', 'type=cart' ); ?></p>
						<p class="cart-total"><?php it_exchange( 'cart', 'total' ); ?><br /></p>
					</div>
				</div>
			</div>
			
			<div class="cart-apply-coupons">
				<?php if ( it_exchange( 'coupons', 'supported', 'type=cart' ) && it_exchange( 'coupons', 'accepting', 'type=cart' ) ) : ?>
					<?php it_exchange( 'coupons', 'apply', 'type=cart' ); ?>
					<?php it_exchange( 'cart', 'update', 'label=' . __( 'Apply Coupon', 'LION' ) ); ?>
				<?php endif; ?>
			</div>
			
			<div class="cart-actions">
				<?php it_exchange( 'cart', 'update' ); ?>
				<?php it_exchange( 'cart', 'empty' ); ?>
				<?php it_exchange( 'cart', 'checkout' ); ?>
			</div>
		</div>
	<?php it_exchange( 'cart', 'form-close' ); ?>
<?php else : ?>
	<p><?php _e( 'There are no items in your cart', 'LION' ); ?></p>
<?php endif; ?>
