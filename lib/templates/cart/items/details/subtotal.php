<?php
/**
 * The main template file for the Subtotal detail in the cart-items loop for content-cart template part
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_cart_item_details_before_subtotal' ); ?>
<div class="it-exchange-cart-item-subtotal it-exchange-table-column">
	<?php do_action( 'it_exchange_cart_item_details_begin_subtotal' ); ?>
	<div class="it-exchange-table-column-inner">
		<?php it_exchange( 'cart-item', 'subtotal' ); ?>
	</div>
	<?php do_action( 'it_exchange_cart_item_details_end_subtotal' ); ?>
</div>
<?php do_action( 'it_exchange_cart_item_details_after_subtotal' ); ?>
