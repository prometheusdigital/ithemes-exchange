<?php
include( dirname( __FILE__ ) . '/functions.php' );

/**
 * Prints the Settings page for Simple Taxes
 *
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_taxes_simple_settings_callback() {
	$settings = it_exchange_get_option( 'addon_taxes_simple', true );
	$form_values  = ! it_exchange_has_messages( 'error' ) ? $settings : ITForm::get_post_data();
	$form_options = array(
		'id'      => 'it-exchange-add-on-taxes-simple-settings',
		'enctype' => false,
		'action'  => 'admin.php?page=it-exchange-addons&add-on-settings=taxes-simple',
	);  
	$form = new ITForm( $form_values, array( 'prefix' => 'it-exchange-add-on-taxes_simple' ) );

	if ( it_exchange_has_messages( 'notice' ) ) {
		foreach( (array) it_exchange_get_messages( 'notice' ) as $message ) {
			ITUtility::show_status_message( $message );
		}
	}
	if ( it_exchange_has_messages( 'error' ) ) {
		foreach( (array) it_exchange_get_messages( 'error' ) as $message ) {
			ITUtility::show_error_message( $message );
		}
	}
	?>  
	<div class="wrap">
		<?php screen_icon( 'it-exchange' ); ?>
		<h2><?php _e( 'Simple Taxes', 'LION' ); ?></h2>

		<?php $form->start_form( $form_options, 'it-exchange-taxes-simple-settings' ); ?>
			<?php $form->add_text_box( 'default-tax-rate' ); ?> %</br />
			<label for="calculate-after-discounts">
				<?php $form->add_check_box( 'calculate-after-discounts' ); ?> <?php _e( 'Calculate taxes after discounts are applied?', 'LION' ); ?><br />
			</label>
			<p class="submit">
				<?php $form->add_submit( 'submit', array( 'value' => __( 'Save Changes', 'LION' ), 'class' => 'button button-primary button-large' ) ); ?>
			</p>
		<?php $form->end_form(); ?>
	</div>
	<?php
}

/**
 * Save settings
 *
 * @since 1.0.0
 * @return void
*/
function it_exchange_addon_save_taxes_simple_settings() {
	$defaults = it_exchange_get_option( 'addon_taxes_simple' );
	$new_values = wp_parse_args( ITForm::get_post_data(), $defaults );

	// Return if not on our page or POST isn't set.
	if ( empty( $_POST ) || empty( $_GET['add-on-settings'] ) || 'taxes-simple' != $_GET['add-on-settings'] )
		return;

	// Check nonce
	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'it-exchange-taxes-simple-settings' ) ) {
		it_exchange_add_message( 'error', __( 'Error. Please try again', 'LION' ) );
		return;
	}

	// Validate data
	if ( ! is_numeric( $new_values['default-tax-rate'] ) ) {
		it_exchange_add_message( 'error', __( 'Default tax rate must be numeric', 'LION' ) );
	} else {
		it_exchange_save_option( 'addon_taxes_simple', $new_values );
		it_exchange_add_message( 'notice', __( 'Settings Saved', 'LION' ) );
	}
}
add_action( 'admin_init', 'it_exchange_addon_save_taxes_simple_settings' );

/**
 * Add Simple Taxes to the content-cart totals and content-checkout loop
 *
 * @since 1.0.0
 *
 * @return array
*/
function it_exchange_addon_add_taxes_simple_to_template_totals_loops( $elements ) {
	array_splice( $elements, -1, 0, 'totals-taxes-simple' );
	return $elements;
}
add_filter( 'it_exchange_get_content_cart_totals_elements', 'it_exchange_addon_add_taxes_simple_to_template_totals_loops' );
add_filter( 'it_exchange_get_content_checkout_totals_elements', 'it_exchange_addon_add_taxes_simple_to_template_totals_loops' );

/**
 * Adds our templates directory to the list of directories
 * searched by Exchange
 *
 * @since 1.0.0
 *
 * @return array
*/
function it_exchange_addon_taxes_simple_register_templates( $template_paths, $template_names ) {
	// Bail if not looking for one of our templates
	$add_path = false;
	$templates = array(
		'content-cart/elements/totals-taxes-simple.php',
		'content-checkout/elements/totals-taxes-simple.php',
		'super-widget-checkout/elements/taxes-simple.php',
	);
	foreach( $templates as $template ) {
		if ( in_array( $template, (array) $template_names ) )
			$add_path = true;
	}
	if ( ! $add_path )
		return $template_paths;

	$template_paths[] = dirname( __FILE__ ) . '/templates';
	return $template_paths;
}
add_filter( 'it_exchange_possible_template_paths', 'it_exchange_addon_taxes_simple_register_templates', 10, 2 );

/**
 * Adjusts the cart total
 *
 * @since 1.0.0
 *
 * @param $total the total passed to us by Exchange.
 * @return
*/
function it_exchange_addon_taxes_simple_modify_total( $total ) {
	$taxes = it_exchange_addon_get_simple_taxes_for_cart( false );
	return $total + $taxes;
}
add_filter( 'it_exchange_get_cart_total', 'it_exchange_addon_taxes_simple_modify_total' );

/**
 * Include taxes template part in super widget after the items loop
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_addon_taxes_add_to_superwidget() {
	it_exchange_get_template_part( 'super-widget-checkout/elements/taxes-simple' );
}
add_action( 'it_exchange_super_widget_checkout_after_items_loop', 'it_exchange_addon_taxes_add_to_superwidget' );