<?php
/**
 * This is the default template part for the empty cart action in the content-cart template part
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_content_cart_actions_before_empty_cart' ); ?>
<?php it_exchange( 'cart', 'empty' ); ?>
<?php do_action( 'it_exchange_content_cart_actions_after_empty_cart' ); ?>
