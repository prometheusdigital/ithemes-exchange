<?php
/**
 * The default template part for the transaction status in
 * the content-purchases template part's product-info loop
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_content_product_info_before_status' ); ?>
<span class="it-exchange-purchase-status">- <?php it_exchange( 'transaction', 'status' ); ?></span> 
<?php do_action( 'it_exchange_content_product_info_after_status' ); ?>
