<?php
/**
 * This is the default template part for the
 * rememberme field in the super-widget-login template
 * part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/super-widget-login/fields/details/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_super_widget_login_fields_before_rememberme' ); ?>
<div class="it-exchange-rememberme">
	<?php it_exchange( 'login', 'rememberme' ); ?>
</div>
<?php do_action( 'it_exchange_super_widget_login_fields_after_rememberme' ); ?>
