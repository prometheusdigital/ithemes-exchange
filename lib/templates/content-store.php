<?php
/**
 * Default template for displaying the store.
 * 
 * @since 0.4.0
 * @package IT_Exchange
 * @version 1.0.2
 * @link http://ithemes.com/codex/page/Exchange_Template_Updates
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange directory located
 * in your theme.
*/
?>

<?php do_action( 'it_exchange_content_store_before_wrap' ); ?>
<div id="it-exchange-store" class="it-exchange-wrap it-exchange-account">
	<?php do_action( 'it_exchange_content_store_begin_wrap' ); ?>
	<?php it_exchange_get_template_part( 'messages' ); ?>
    <?php it_exchange_get_template_part( 'content-store/loops/products' ); ?>
	<?php do_action( 'it_exchange_content_store_after_wrap' ); ?>
</div>
<?php do_action( 'it_exchange_content_store_after_wrap' ); ?>
