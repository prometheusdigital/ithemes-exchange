<?php
/**
 * This is the default template part for the
 * recover password element in the content-login
 * template part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-login/elements
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_login_actions_before_recover' ); ?>
<div class="it-exchange-recover-url">
	<?php it_exchange( 'login', 'recover' ); ?>
</div>
<?php do_action( 'it_exchange_content_login_actions_after_recover' ); ?>
