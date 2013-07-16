<?php
/**
 * Default template part for the purchase
 * confirmation page.
 * 
 * @since 0.4.0
 * @version 1.0.0
 * @link http://ithemes.com/codex/page/Exchange_Template_Updates
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange directory located
 * at your templates root.
 * 
 * Example: theme/exchange/content-confirmation.php
*/
?>
<?php do_action( 'it_exchange_content_confirmation_before_wrap' ); ?>
<div id="it-exchange-wrap it-exchange-confirmation">
<?php it_exchange_get_template_part( 'content-confirmation/loops/transactions' ); ?>
</div>
<?php do_action( 'it_exchange_content_confirmation_after_wrap' ); ?>
