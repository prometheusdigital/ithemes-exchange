<?php
/**
 * This is the default template part for the
 * cancel action in the super-widget-registration template
 * part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/super-widget-registration/fields/details/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_super_widget_registration_actions_before_cancel' ); ?>
<div class="cancel_url">
    <?php it_exchange( 'registration', 'cancel', array( 'label' => __( 'Log in', 'LION' ) ) ); ?>
</div>
<?php do_action( 'it_exchange_super_widget_registration_actions_after_cancel' ); ?>
