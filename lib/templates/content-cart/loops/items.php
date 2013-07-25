<?php
/**
 * This is the default template part for the cart
 * items loop.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-cart/loops/ directory
 * located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_cart_before_items' ); ?>
<div id="it-exchange-cart-items" class="it-exchange-table">
<?php do_action( 'it_exchange_content_cart_before_items_loop' ); ?>
<?php while ( it_exchange( 'cart', 'cart-items' ) ) : ?>
	<?php do_action( 'it_exchange_content_cart_begin_items_loop' ); ?>
	
	<?php do_action( 'it_exchange_content_cart_before_items_loop_table_row' ); ?>
	<div class="it-exchange-table-row">
		<?php do_action( 'it_exchange_content_cart_begin_items_loop_table_row' ); ?>
		
		<?php foreach ( it_exchange_get_template_part_elements( 'content_cart', 'items', array( 'item-featured-image', 'item-title', 'item-quantity', 'item-subtotal', 'item-remove' ) ) as $item ) : ?>
			<?php
			/**
			 * Theme and add-on devs should add code to this loop by 
			 * hooking into it_exchange_get_template_part_elements filter
			 * and adding the appropriate template file to their theme or add-on
			 */
			it_exchange_get_template_part( 'content-cart/elements/' . $item );
			?>
		<?php endforeach; ?>
		<?php do_action( 'it_exchange_content_cart_end_items_loop_table_row' ); ?>
	</div>
	<?php do_action( 'it_exchange_content_cart_after_items_loop_table_row' ); ?>
	
	<?php do_action( 'it_exchange_content_cart_end_items_loop' ); ?>
<?php endwhile; ?>
<?php do_action( 'it_exchange_content_content_cart_after_items_loop' ); ?>
</div>
<?php do_action( 'it_exchange_content_cart_after_items' ); ?>
