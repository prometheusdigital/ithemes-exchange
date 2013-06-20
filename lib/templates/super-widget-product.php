<?php if ( it_exchange( 'product', 'found' ) ) : ?>
	<div class="it-exchange-sw-selecting">
		<?php if ( it_exchange( 'cart', 'get-item-count' ) && it_exchange_is_multi_item_cart_allowed() ) : ?>
			<div class="item-count">
				<?php printf( __( 'You have %s item(s) in your <a href="%s">%s</a>', 'LION' ), it_exchange( 'cart', 'get-item-count' ), it_exchange_get_page_url( 'cart' ), strtolower( it_exchange_get_page_name( 'cart' ) ) ); ?>
			</div>
		<?php endif; ?>
	
		<?php if ( it_exchange_is_page( 'product' ) ) : ?>
			<div class="purchase-options">
				<?php it_exchange( 'product', 'purchase-options', array( 'add-to-cart-edit-quantity' => false, 'buy-now-edit-quantity' => false ) ); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
