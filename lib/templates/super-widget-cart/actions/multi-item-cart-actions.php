<?php do_action( 'it_exchange_super_widget_cart_actions_before_multi_item_actions_wrapper' ); ?>
<div class="cart-actions-wrapper two-actions">
	<?php do_action( 'it_exchange_super_widget_cart_actions_begin_multi_item_actions_wrapper' ); ?>
	<div class="cart-action view-cart">
		<?php it_exchange( 'cart', 'view-cart', array( 'class' => 'sw-cart-focus-cart', 'focus' => 'cart' ) ); ?>
	</div>     
	<div class="cart-action checkout">
		<?php it_exchange( 'cart', 'checkout', array( 'class' => 'sw-cart-focus-checkout', 'focus' => 'checkout' ) ); ?>
	</div>
	<?php do_action( 'it_exchange_super_widget_cart_actions_end_multi_item_actions_wrapper' ); ?>
</div>
<?php do_action( 'it_exchange_super_widget_cart_actions_after_multi_item_actions_wrapper' ); ?>
