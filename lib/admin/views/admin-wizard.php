<?php
/**
 * This file prints the wizard page in the Admin
 *
 * @since 0.4.0
 * @package IT_Exchange
*/
$flat_rate_cost = it_exchange_get_option( 'simple-shipping', true );
$flat_rate_cost = empty( $options['flat-rate-shipping-amount'] ) ? it_exchange_format_price( 5 ) : $options['flat-rate-shipping-amount'];
$form->set_option( 'simple-shipping-flat-rate-cost', $flat_rate_cost );
?>
<div class="wrap">
	<?php screen_icon( 'it-exchange' );  ?>
	
	<h2>iThemes Exchange <?php _e( 'Setup', 'LION' ); ?></h2>
	
	<?php $form->start_form( $form_options, 'exchange-general-settings' ); ?>
		<div class="it-exchange-wizard">
			<div class="fields">
				<div class="field product-types">
					<p><?php _e( 'Click to select the types of products you plan to sell in your store.', 'LION' ); ?><span class="tip" title="<?php _e( "You can always add or remove these later on the Add-ons page.", 'LION' ); ?>">i</span></p>
					<ul>
						<?php
							$addons = it_exchange_get_addons( array( 'category' => 'product-type', 'show_required' => false ) );
							if ( isset( $addons['simple-product-type'] ) )
								unset( $addons['simple-product-type'] );

							$show_shipping = 'hide-if-js';
							it_exchange_temporarily_load_addons( $addons );
							foreach( (array) $addons as $addon ) {
								if ( ! empty( $addon['options']['wizard-icon'] ) ) {
									$name  = '<img src="' . $addon['options']['wizard-icon'] . '" alt="' . $addon['name'] . '" />';
									$name .= '<span class="product-name">' . $addon['name'] . '</span>';
								} else {
									$name = $addon['name'];
								}
									
								if ( it_exchange_is_addon_enabled( $addon['slug'] ) )
									$selected_class = 'selected';
								else
									$selected_class = '';
								
								$toggle_ships = 'physical-product-type' == $addon['slug'] ? ' data-ships="shipping-types"' : '';
								if ( 'physical-product-type' == $addon['slug'] ) {
									$show_shipping = empty( $selected_class ) ? 'hide-if-js' : '';	
								}

								echo '<li class="productoption ' . $addon['slug'] . '-productoption ' . $selected_class . '" product-type="' . $addon['slug']. '" data-toggle="' . $addon['slug'] . '-wizard"' . $toggle_ships . '>';
								echo '<div class="option-spacer">';
								echo $name;
								echo '</div>';
								if ( ! empty( $selected_class ) )
									echo '<input class="enable-' . esc_attr( $addon['slug'] ) . '" type="hidden" name="it-exchange-product-types[]" value="' . esc_attr( $addon['slug'] ) . '" />';
								echo '</li>';
							}
						?>
						<?php if ( ! it_exchange_is_addon_registered( 'membership-product-type' ) ) : ?>
							<li class="membership-productoption inactive" data-toggle="membership-wizard">
								<div class="option-spacer">
									<img src="<?php echo ITUtility::get_url_from_file( dirname( dirname( __FILE__ ) ) . '/images/wizard-membership.png' ); ?>" alt="<?php _e( 'Membership', 'LION' ); ?>" />
									<span class="product-name"><?php _e( 'Membership', 'LION' ); ?></span>
									<span>$</span>
								</div>
							</li>
						<?php endif; ?>
					</ul>
				</div>
				
				<?php if ( ! it_exchange_is_addon_registered( 'membership-product-type' ) ) : ?>
					<div class="field membership-wizard inactive hide-if-js">
						<h3><?php _e( 'Membership', 'LION' ); ?></h3>
						<p><?php _e( 'To use Membership, you need to install the Membership add-on.', 'LION' ); ?></p>
						<div class="membership-action activate-membership">
							<img src="<?php echo ITUtility::get_url_from_file( dirname( dirname( __FILE__ ) ) . '/images/plugin32.png' ); ?>" />
							<p><?php _e( 'I have the Membership add-on and just need to install and/or activate it.', 'LION' ); ?></p>
							<p><a href="<?php echo admin_url( 'plugins.php' ); ?>" target="_self"><?php _e( 'Go to the plugins page', 'LION' ); ?></a></p>
						</div>
						<div class="membership-action buy-membership">
							<img src="<?php echo ITUtility::get_url_from_file( dirname( dirname( __FILE__ ) ) . '/images/icon32.png' ); ?>" />
							<p><?php _e( "I don't have the Membership add-on yet, but I want to use Membership.", 'LION' ); ?></p>
							<p><a href="http://ithemes.com/purchase/membership-add-on/" target="_blank"><?php _e( 'Get the Membership Add-on', 'LION' ); ?></a></p>
						</div>
					</div>
				<?php endif; ?>
				
				<?php 
				foreach( (array) $addons as $addon ) {
					do_action( 'it_exchange_print_' . $addon['slug'] . '_wizard_settings', $form ); 
				}
				?>

				<div class="field shipping-types <?php esc_attr_e( $show_shipping ); ?>">
					<p><?php _e( 'How will you ship your products?', 'LION' ); ?><span class="tip" title="<?php _e( "You can always add or remove these later on the Shipping Settings page.", 'LION' ); ?>">i</span></p>
					<ul>
						<?php
							$addons = it_exchange_get_addons( array( 'category' => 'shipping', 'show_required' => false ) );
							it_exchange_temporarily_load_addons( $addons );

							// Add Simple Shipping's free and flat rate methods as providers since Brad thinks they're so special 
							$flat_rate_selected = 'hide-if-js';
							$addons['simple-shipping-flat-rate'] = array(
								'name' => __( 'Flat Rate', 'LION' ),
								'slug' => 'simple-shipping-flat-rate',
								'options' => array( 'wizard-icon' => false ),
							);
							$addons['simple-shipping-free'] = array(
								'name' => __( 'Free Shipping', 'LION' ),
								'slug' => 'simple-shipping-free',
								'options' => array( 'wizard-icon' => false ),
							);

							// Loop through them and print their settings
							foreach( (array) $addons as $addon ) {
								// Skip simple shipping
								if ( 'simple-shipping' == $addon['slug'] )
									continue;

								if ( ! empty( $addon['options']['wizard-icon'] ) )
									$name = '<img src="' . $addon['options']['wizard-icon'] . '" alt="' . $addon['name'] . '" />';
								else
									$name = $addon['name'];
									
								if ( it_exchange_is_addon_enabled( $addon['slug'] ) )
									$selected_class = 'selected';
								else
									$selected_class = '';

								// Set selected for free and flat rate
								if ( 'simple-shipping-free' == $addon['slug'] || 'simple-shipping-flat-rate' == $addon['slug'] ) {
									$option_key = ( 'simple-shipping-free' == $addon['slug'] ) ? 'enable-free-shipping' : 'enable-flat-rate-shipping';
									$simple_shipping_options = it_exchange_get_option( 'simple-shipping' );
									$selected_class = it_exchange_is_addon_enabled( 'simple-shipping' ) && ! empty( $simple_shipping_options[$option_key] ) ? 'selected' : '';
								}
								
								if ( 'simple-shipping-flat-rate' == $addon['slug'] && ! empty( $selected_class ) && empty( $show_shipping ) )
									$flat_rate_selected = '';

								echo '<li class="shippingoption ' . $addon['slug'] . '-shippingoption ' . $selected_class . '" shipping-method="' . $addon['slug']. '" data-toggle="' . $addon['slug'] . '-wizard">';
								echo '<div class="option-spacer">';
								echo $name;
								echo '</div>';
								if ( $selected_class )
									echo '<input class="enable-' . esc_attr( $addon['slug'] ) . '" type="hidden" name="it-exchange-shipping-methods[]" value="' . esc_attr( $addon['slug'] ) . '" />';
								echo '</li>';
							}
						?>
					</ul>
				</div>

				<div class="field simple-shipping-flat-rate-wizard <?php esc_attr_e( $flat_rate_selected ); ?>">
					<h3><?php _e( 'Flat Rate Shipping', 'LION' ); ?></h3>
					<table class="form-table">
						<tr valign="top">
							<td scope="row">
								<label for="simple-shipping-flat-rate-cost"><?php _e( 'Flat Rate Default Amount', 'LION' ); ?></label>
								<span class="tip" title="<?php _e( 'Default shipping costs for flat rate. Multiplied by quantity purchased. Customizable per product by Store Admin.', 'LION' ); ?>" >i</span>
							</td>
							<td>
								<?php $form->add_text_box( 'simple-shipping-flat-rate-cost', array( 'class' => 'normal-text' ) ); ?>
							</td>
						</tr>
					</table>
				</div>

				<?php 
				foreach( (array) $addons as $addon ) {
					do_action( 'it_exchange_print_' . $addon['slug'] . '_wizard_settings', $form ); 
				}
				?>

				<div class="field payments">
					<p><?php _e( 'How will you be accepting payments? Choose one.', 'LION' ); ?><span class="tip" title="<?php _e( "Choose your preferred payment gateway for processing transactions. You can select more than one option but it's not recommended.", 'LION' ); ?>">i</span></p>
					<ul>
						<?php
							$addons = it_exchange_get_addons( array( 'category' => 'transaction-methods', 'show_required' => false ) );
							it_exchange_temporarily_load_addons( $addons );
							foreach( (array) $addons as $addon ) {
								if ( ! empty( $addon['options']['wizard-icon'] ) )
									$name = '<img src="' . $addon['options']['wizard-icon'] . '" alt="' . $addon['name'] . '" />';
								else
									$name = $addon['name'];
									
								if ( it_exchange_is_addon_enabled( $addon['slug'] ) )
									$selected_class = 'selected';
								else
									$selected_class = '';
								
								echo '<li class="payoption ' . $addon['slug'] . '-payoption ' . $selected_class . '" transaction-method="' . $addon['slug']. '" data-toggle="' . $addon['slug'] . '-wizard">';
								echo '<div class="option-spacer">';
								echo $name;
								echo '<input type="hidden" class="remove-if-js" name="it-exchange-transaction-methods[]" value="' . $addon['slug'] . '" />';
								echo '</div>';
								echo '</li>';
							}
						?>
						
						<?php if ( ! it_exchange_is_addon_registered( 'stripe' ) ) : ?>
							<li class="stripe-payoption inactive" data-toggle="stripe-wizard">
								<div class="option-spacer">
									<img src="<?php echo ITUtility::get_url_from_file( dirname( dirname( __FILE__ ) ) . '/images/stripe32.png' ); ?>" alt="<?php _e( 'Stripe', 'LION' ); ?>" />
								</div>
							</li>
						<?php endif; ?>
					</ul>
				</div>
				
				<?php if ( ! it_exchange_is_addon_registered( 'stripe' ) ) : ?>
					<div class="field stripe-wizard inactive hide-if-js">
						<h3><?php _e( 'Stripe', 'LION' ); ?></h3>
						<p><?php _e( 'To use Stripe, you need to install the Stripe add-on.', 'LION' ); ?></p>
						<div class="stripe-action activate-stripe">
							<img src="<?php echo ITUtility::get_url_from_file( dirname( dirname( __FILE__ ) ) . '/images/plugin32.png' ); ?>" />
							<p><?php _e( 'I have the Stripe add-on and just need to install and/or activate it.', 'LION' ); ?></p>
							<p><a href="<?php echo admin_url( 'plugins.php' ); ?>" target="_self"><?php _e( 'Go to the plugins page', 'LION' ); ?></a></p>
						</div>
						<div class="stripe-action buy-stripe">
							<img src="<?php echo ITUtility::get_url_from_file( dirname( dirname( __FILE__ ) ) . '/images/icon32.png' ); ?>" />
							<p><?php _e( "I don't have the Stripe add-on yet, but I want to use Stripe.", 'LION' ); ?></p>
							<p><a href="http://ithemes.com/exchange/stripe/" target="_blank"><?php _e( 'Get the free Stripe Add-on', 'LION' ); ?></a></p>
						</div>
					</div>
				<?php endif; ?>
				
				<?php 
				foreach( (array) $addons as $addon ) {
					do_action( 'it_exchange_print_' . $addon['slug'] . '_wizard_settings', $form ); 
				}
				?>
				
				<div class="field general-settings-wizard">
					<h3><?php _e( 'General', 'LION' ); ?></h3>
					<label for="company-email"><?php _e( 'E-mail Notifications', 'LION' ); ?> <span class="tip" title="<?php _e( 'At what email address would you like to receive store notifications?', 'LION' ); ?>">i</span></label>
					<?php $form->add_text_box( 'company-email', array( 'value' => get_bloginfo( 'admin_email' ), 'class' => 'clearfix' ) ); ?>
					<p>
						<?php $form->add_check_box( 'exchange-notifications', array( 'checked' => true ) ); ?>
						<label for="exchange-notifications"><?php _e( 'Get e-mail updates from us about iThemes Exchange', 'LION' ); ?> <span class="tip" title="<?php _e( 'Subscribe to get iThemes Exchange news, updates, discounts and swag &hellip; oh, and our endless love.', 'LION' ); ?>">i</span></label>
					</p>
					<div class="default-currency">
						<label for="default-currency"><?php _e( 'Currency', 'LION' ); ?><span class="tip" title="<?php _e( 'Select the currency you plan to use in your store.', 'LION' ); ?>">i</span></label>
						<?php $form->add_drop_down( 'default-currency', $this->get_default_currency_options() ); ?>
					</div>
				</div>
				
				<!-- 
				NOTE: We are removing this for now, but will probably add this later.
				<div class="field add-on-banner">
					<img src="<?php echo ITUtility::get_url_from_file( dirname( dirname( __FILE__ ) ) . '/images/icon32.png' ); ?>" />
					<p><?php _e( 'You\'re almost ready to start selling digital products using PayPal and iThemes Exchange.', 'LION' ); ?></p>
					<p><strong><?php _e( 'Remember, if you want to do more with Exchange, check out our Add-ons Library.', 'LION' ); ?></strong></p>
					<a class="get-add-ons " href="javascript:void(0);" target="_blank"><span><?php _e( "Get Add-ons", 'LION' ); ?></span></a>
				</div>
				-->
				
				<div class="field submit-wrapper">
					<?php $form->add_submit( 'submit', array( 'class' => 'button button-primary button-large', 'value' => __( 'Save Settings', 'LION' ) ) ); ?>
					<?php $form->add_hidden( 'dismiss-wizard-nag', true ); ?>
					<?php $form->add_hidden( 'wizard-submitted', true ); ?>
				</div>
			</div>
		</div>
	<?php $form->end_form(); ?> 
</div>
