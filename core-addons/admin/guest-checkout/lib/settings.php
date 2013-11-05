<?php
/**
 * Callback function for guest checkout add-on settings
 *
 * We are using this differently than most add-ons. We want the gear
 * to appear on the add-ons screen so we are registering the callback.
 * It will be intercepted though if the user clicks on it and redirected to 
 * The Exchange settings --> shipping tab.
 *
 * @since CHANGEME
 *
 * @return void
*/
function it_exchange_guest_checkout_settings_callback() {
	?>
	<div class="wrap">
		<?php screen_icon( 'it-exchange' ); ?>
		<h2><?php _e( 'Guest Checkout', 'LION' ); ?></h2>

		<?php
		// Registration dependant settings
		$registration_settings = array(
			array(
				'type'    => 'yes_no_drop_down',
				'label'   => __( 'Make Guest Checkout the default method?', 'LION' ),
				'slug'    => 'default-form',
				'tooltip' => __( 'This will overwrite the value for Default Form in General Settings.', 'LION' ),
				'default' => 1,
			),
			array( 
				'type'    => 'yes_no_drop_down',
				'label'   => __( 'Show log in link?', 'LION' ),
				'slug'    => 'show-log-in-link',
				'tooltip' => __( 'Selecting \'No\' will remove the Log in link from Registration and Guest Checkout forms.', 'LION' ),
				'default' => 1,
			),
			array( 
				'type'    => 'yes_no_drop_down',
				'label'   => __( 'Show registration link?', 'LION' ),
				'slug'    => 'show-registration-link',
				'tooltip' => __( 'Selecting \'No\' will remove the Registration link from Log in and Guest Checkout forms.', 'LION' ),
				'default' => 1,
			),
		);
		// Other Settings
		$core_settings = array(
			array( 
				'type'    => 'text_box',
				'label'   => __( 'Cart Expiration', 'LION' ),
				'slug'    => 'cart-expiration',
				'tooltip' => __( 'Cart will expire and be cleared after a guest customer is inactive for set number of minutes.', 'LION' ),
				'after'   => '&nbsp;' . __( 'minutes', 'LION' ),
				'default' => 15,
			),
		);

		// Init form fields
		$form_fields = array();

		// Merge in needed settings based on registration
		$general_settings = it_exchange_get_option( 'settings_general' );
		if ( 'wp' == $general_settings['site-registration'] && ! get_option( 'users_can_register' ) ) 
			$form_fields = array_merge( $form_fields, $core_settings );
		else
			$form_fields = array_merge( $form_fields, $registration_settings, $core_settings );

		// Set admin setting form class options
		$options = array(
			'prefix'       => 'addon-guest-checkout',
			'form-options' => array(
				'action'            => '',
			),  
			'form-fields'  => $form_fields,
		);  
		it_exchange_print_admin_settings_form( $options );
		?> 
	</div>
	<?php
}
