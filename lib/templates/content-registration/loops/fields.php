<?php
/**
 * This is the default template part for the
 * fields loop in the content-registration
 * template part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-registration/loops/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_registration_before_fields_loop' ); ?>
	<div class="it-exchange-customer-fields">
	<?php do_action( 'it_exchange_content_registration_begin_fields_loop' ); ?>
		<?php foreach ( it_exchange_get_template_part_elements( 'content_registration', 'fields', array( 'username', 'first-name', 'last-name', 'email', 'password1', 'password2' ) ) as $field ) : ?>
			<?php
			/** 
			 * Theme and add-on devs should add code to this loop by 
			 * hooking into it_exchange_get_template_part_elements filter
			 * and adding the appropriate template file to their theme or add-on
			*/
			it_exchange_get_template_part( 'content-registration/elements/' . $field ); ?>
		<?php endforeach; ?>
   	<?php do_action( 'it_exchange_content_registration_end_fields_loop' ); ?>
	</div>
<?php do_action( 'it_exchange_content_registration_after_fields_loop' ); ?>
