<?php
/**
 * The main template file for the Title detail in the cart-items loop for content-cart template part
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_cart_item_details_before_title' ); ?>
<div class="it-exchange-cart-item-title it-exchange-table-column">
	<?php do_action( 'it_exchange_cart_item_details_begin_title' ); ?>
	<div class="it-exchange-table-column-inner">
		<a href="<?php it_exchange( 'cart-item', 'permalink' ) ?>"><?php it_exchange( 'cart-item', 'title' ) ?></a>
	</div>
	<?php do_action( 'it_exchange_cart_item_details_end_title' ); ?>
</div>
<?php do_action( 'it_exchange_cart_item_details_after_title' ); ?>
