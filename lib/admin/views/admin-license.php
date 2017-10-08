<?php
/**
 * This file contains the contents of the Licenses page
 * @since 0.4.14
 * @package IT_Exchange
*/
  ?>
	<div class="wrap">
		<?php
		ITUtility::screen_icon( 'it-exchange' );
		// Print Admin Settings Tabs
		$GLOBALS['IT_Exchange_Admin']->print_general_settings_tabs();
		$license = get_option( 'exchangewp_invoices_license_key' );
		$status  = get_option( 'exchangewp_invoices_status' );
		?>

		<h2>License Keys</h2>
		<p>If you have purchased a licnese key for ExchangeWP, you can enter that below.
			If you'd like to purchase an ExchangeWP license, you can do so
			by <a href="https://exchangewp.com/pricing">going here.</a></p>

<?php
      $settings = it_exchange_get_option( 'exchangewp_licenses', true );
      $form_values  = empty( $this->error_message ) ? $settings : ITForm::get_post_data();
      $form_options = array(
          'id'      => apply_filters( 'it_exchange_licenses', 'it-exchange-licenses-settings' ),
          'enctype' => apply_filters( 'it_exchange_licenses_settings_form_enctype', false ),
          'action'  => 'admin.php?page=it-exchange-settings&tab=license',
      );
      $form         = new ITForm( $form_values, array( 'prefix' => 'it-exchange-licenses' ) );

      if ( !empty ( $this->status_message ) )
          ITUtility::show_status_message( $this->status_message );
      if ( !empty( $this->error_message ) )
          ITUtility::show_error_message( $this->error_message );

      ?>
      <div class="wrap">
          <?php screen_icon( 'it-exchange' ); ?>
          <?php do_action( 'it_exchange_paypal-pro_settings_page_top' ); ?>
          <?php do_action( 'it_exchange_addon_settings_page_top' ); ?>

          <?php $form->start_form( $form_options, 'it-exchange-licenses-settings' ); ?>
              <?php do_action( 'it_exchange_licenses_settings_form_top' ); ?>
              <?php get_form_table( $form, $form_values ); ?>
              <?php do_action( 'it_exchange_licenses_settings_form_bottom' ); ?>
              <p class="submit">
                  <?php $form->add_submit( 'submit', array( 'value' => __( 'Save Changes', 'LION' ), 'class' => 'button button-primary button-large' ) ); ?>
              </p>
          <?php $form->end_form(); ?>
          <?php do_action( 'it_exchange_licenses_settings_page_bottom' ); ?>
          <?php do_action( 'it_exchange_addon_settings_page_bottom' ); ?>
      </div>
      <?php

/**
 * Builds Settings Form Table
 *
 * @since 1.0.0
 */
function get_form_table( $form, $settings = array() ) {

    if ( !empty( $settings ) ) {
        foreach ( $settings as $key => $var ) {
            $form->set_option( $key, $var );
			}
		}

    ?>
    <!-- This is where the form would start for all of the licenses. -->
    <table class="form-table">
      <tbody>
        <tr>
          <th>License key</th>
          <td>
          <?php $form->add_text_box( 'invoice_license' ); ?></td>
        </tr>
      </tbody>
    </table>
    <?php
}

//need to change the if line
if ( !empty( $_POST ) && $this->_is_admin && 'it-exchange-addons' == $this->_current_page && '2checkout' == $this->_current_add_on ) {
    add_action( 'it_exchange_save_licenses', 'save_settings' ) );
    do_action( 'it_exchange_save_licenses' );
}

/**
 * Save settings
 *
 * @since 1.0.0
 * @return void
*/
function save_settings() {
    $defaults = it_exchange_get_option( 'exchangewp_licenses' );
    $new_values = wp_parse_args( ITForm::get_post_data(), $defaults );

    // Check nonce
    if ( !wp_verify_nonce( $_POST['_wpnonce'], 'it-exchange-licenses-settings' ) ) {
        $this->error_message = __( 'Error. Please try again', 'LION' );
        return;
    }

    $errors = apply_filters( 'it_exchange_add_on_licenses_validate_settings', $this->get_form_errors( $new_values ), $new_values );
    if ( !$errors && it_exchange_save_option( 'exchangewp_licenses', $new_values ) ) {
        ITUtility::show_status_message( __( 'Settings saved.', 'LION' ) );
    } else if ( $errors ) {
        $errors = implode( '<br />', $errors );
        $this->error_message = $errors;
    } else {
        $this->status_message = __( 'Settings not saved.', 'LION' );
    }
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 *
 * @since 1.2.2
 */
function exchange_licenses_admin_notices() {
	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch( $_GET['sl_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;

		}
	}
}
