<?php
/**
 * This is the default template part for the save
 * button detail in the content-profile template
 * part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-profile/actions/details/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_profile_fields_before_save-button' ); ?>
<div class="it-exchange-customer-save">
	<?php it_exchange( 'customer', 'save' ); ?>
</div>
<?php do_action( 'it_exchange_content_profile_fields_after_save-button' ); ?>