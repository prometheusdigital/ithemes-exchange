<?php
/**
 * This is the default template part for the
 * register element in the content-login template
 * part.
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

<?php do_action( 'it_exchange_content_login_before_register_element' ); ?>
<div class="it-exchange-register-url">
	<a class="it-exchange-login-requirement-cancel" href="<?php echo it_exchange_get_page_url( 'checkout' ); ?>"><?php _e( 'Cancel', 'LION' ); ?></a>
</div>
<?php do_action( 'it_exchange_content_login_after_register_element' ); ?>