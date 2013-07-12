<?php
/**
 * Default template part for the cart page.
 * 
 * @since 0.4.0
 * @version 1.0.0
 * @link http://ithemes.com/codex/page/Exchange_Template_Updates* @updated 1.0.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange directory located
 * in your theme.
*/
?>

<?php do_action( 'it_exchange_content_cart_before_wrap' ); ?>
<div id="it-exchange-cart" class="it-exchange-wrap">
	<?php do_action( 'it_exchange_content_cart_begin_wrap' ); ?>
	
	<?php it_exchange_get_template_part( 'messages' ); ?>
	
	<?php if ( it_exchange( 'cart', 'has-cart-items' ) ) :  ?>
		
		<?php do_action( 'it_exchange_content_cart_before_form' ); ?>
		<?php it_exchange( 'cart', 'form-open' ); ?>
			<?php do_action( 'it_exchange_content_cart_begin_form' ); ?>
            
			<?php it_exchange_get_template_part( 'content-cart/loops/items' ); ?>
			<?php it_exchange_get_template_part( 'content-cart/loops/coupons' ); ?>
			<?php it_exchange_get_template_part( 'content-cart/loops/totals' ); ?>
			<?php it_exchange_get_template_part( 'content-cart/loops/actions' ); ?>
			
			<?php do_action( 'it_exchange_content_cart_end_form' ); ?>
		<?php it_exchange( 'cart', 'form-close' ); ?>
		<?php do_action( 'it_exchange_content_cart_after_form' ); ?>
	<?php else : ?>
		<?php do_action( 'it_exchange_content_cart_start_empty_cart' ); ?>
			<p><?php _e( 'There are no items in your cart', 'LION' ); ?></p>
		<?php do_action( 'it_exchange_content_cart_end_empty_cart' ); ?>
	<?php endif; ?>
	<?php do_action( 'it_exchange_content_cart_end_wrap' ); ?>
</div>
<?php do_action( 'it_exchange_content_cart_after_wrap' ); ?>
