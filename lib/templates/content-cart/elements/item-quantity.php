<?php
/**
 * This is the default template for the Quantity
 * cart item element in the content-cart.php
 * template part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-cart/items/elements/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_cart_item_details_before_quantity' ); ?>
<div class="it-exchange-cart-item-quantity it-exchange-table-column">
	<?php do_action( 'it_exchange_content_cart_item_details_begin_quantity' ); ?>
	<div class="it-exchange-table-column-inner">
		<?php it_exchange( 'cart-item', 'quantity' ); ?>
	</div>
	<?php do_action( 'it_exchange_content_cart_item_details_end_quantity' ); ?>
</div>
<?php do_action( 'it_exchange_content_cart_item_details_after_quantity' ); ?>