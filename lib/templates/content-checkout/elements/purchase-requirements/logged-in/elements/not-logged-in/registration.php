<?php
/**
 * This is the default template part for the
 * registration element in the not-logged-in loop for the 
 * purchase-requriements in the content-checkout 
 * template part.
 *
 * @since 1.5.0
 * @version 1.2.1
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file to the 
 * /exchange/content-checkout/elements/purchase-requirements/logged-in/elements/not-logged-in
 * directory located in your theme.
*/
?>
<?php do_action( 'it_exchange_content_checkout_logged_in_purchase_requirement_not_logged_in_before_registration_element' ); ?>
<div class="<?php echo it_exchange_is_checkout_mode( 'registration' ) ? '' : 'it-exchange-hidden'; ?> checkout-purchase-requirement-registration">
	<?php do_action( 'it_exchange_content_checkout_logged_in_purchase_requirement_not_logged_in_begin_registration_element' ); ?>
	<?php
	$reg_loops = array( 'fields', 'actions' );

	if ( it_exchange( 'registration', 'is-enabled' ) ) :
		do_action( 'it_exchange_content_checkout_logged_in_checkout_requirement_registration_before_form' );
		it_exchange( 'registration', 'form-open' );
		// Include template parts for each of the above loops
		foreach( (array) it_exchange_get_template_part_loops( 'content-checkout/elements/purchase-requirements/logged-in/loops/', 'registration', $reg_loops ) as $loop ) :
			it_exchange_get_template_part( 'content', 'checkout/elements/purchase-requirements/logged-in/loops/registration/' . $loop );
		endforeach;
		it_exchange( 'registration', 'form-close' );
		do_action( 'it_exchange_content_checkout_logged_in_purchase_requirement_registration_after_form' );
	endif;
	?>
	<?php do_action( 'it_exchange_content_checkout_logged_in_purchase_requirement_not_logged_in_end_registration_element' ); ?>
	<?php it_exchange_get_template_part( 'content', 'checkout/elements/purchase-requirements/logged-in/loops/not-logged-in/links' ); ?>
</div>
<?php do_action( 'it_exchange_content_checkout_logged_in_purchase_requirement_not_logged_in_after_registration_element' ); ?>
