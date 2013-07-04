<?php
/**
 * This file contains the contents of the Settings page
 * @since 0.3.6
 * @package IT_Exchange
*/
?>
<div class="wrap">
	<?php
	screen_icon( 'it-exchange' );
	$this->print_general_settings_tabs();
	do_action( 'it_exchange_general_settings_page_top' );

	$form->start_form( $form_options, 'exchange-general-settings' );
	?>
		<?php do_action( 'it_exchange_general_settings_form_top', $form ); ?>
		<table class="form-table">
			<?php do_action( 'it_exchange_general_settings_table_top', $form ); ?>
			<tr valign="top">
				<th scope="row"><strong><?php _e( 'Company Details', 'LION' ); ?></strong></th>
				<td></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="company-name"><?php _e( 'Company Name', 'LION' ) ?></label></th>
				<td>
					<?php $form->add_text_box( 'company-name', array( 'class' => 'normal-text' ) ); ?>
					<br /><span class="description"><?php _e( 'The name used in customer receipts.', 'LION' ); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="company-tax-id"><?php _e( 'Company Tax ID', 'LION' ) ?> <span class="tip" title="<?php _e( 'In the U.S., this is your Federal Tax ID Number', 'LION' ); ?>">i</span></label></th>
				<td>
					<?php $form->add_text_box( 'company-tax-id', array( 'class' => 'normal-text' ) ); ?>
                    <p class="description"><a href="http://www.irs.gov/Businesses/Small-Businesses-&amp;-Self-Employed/Employer-ID-Numbers-(EINs)-" target="_blank"><?php _e( 'Click here for more info about obtaining a Tax ID in the US', 'LION' ); ?></a></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="company-email"><?php _e( 'Company Email', 'LION' ) ?> <span class="tip" title="<?php _e( 'Where do you want customer inquiries to go?', 'LION' ); ?>">i</span></label></th>
				<td>
					<?php $form->add_text_box( 'company-email', array( 'class' => 'normal-text' ) ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="company-phone"><?php _e( 'Company Phone', 'LION' ) ?> <span class="tip" title="<?php _e( 'This is your main customer service line.', 'LION' ); ?>">i</span></label></th>
				<td>
					<?php $form->add_text_box( 'company-phone', array( 'class' => 'normal-text' ) ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="company-address"><?php _e( 'Company Address', 'LION' ) ?></label></th>
				<td>
					<?php $form->add_text_area( 'company-address', array( 'rows' => 5, 'cols' => 30 ) ); ?>
				</td>
			</tr>
			<?php do_action( 'it_exchange_general_settings_before_settings_currency', $form ); ?>
			<tr valign="top">
				<th scope="row"><strong><?php _e( 'Currency Settings', 'LION' ); ?></strong></th>
				<td></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="default-currency"><?php _e( 'Default Currency', 'LION' ) ?></label></th>
				<td>
					<?php $form->add_drop_down( 'default-currency', $this->get_default_currency_options() ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="currency-symbol-position"><?php _e( 'Symbol Position', 'LION' ) ?></label></th>
				<td>
					<?php 
					$symbol_positions = array( 'before' => __( 'Before: $10.00', 'LION' ), 'after' => __( 'After: 10.00$', 'LION' ) );
					$form->add_drop_down( 'currency-symbol-position', $symbol_positions ); ?>
					<br /><span class="description"><?php _e( 'Where should the currency symbol be placed in relation to the price?', 'LION' ); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="currency-thousands-separator"><?php _e( 'Thousands Separator', 'LION' ) ?></label></th>
				<td>
					<?php $form->add_text_box( 'currency-thousands-separator', array( 'class' => 'small-text', 'maxlength' => '1' ) ); ?>
					<br /><span class="description"><?php _e( 'What character would you like to use to separate thousands when displaying prices?', 'LION' ); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="currency-decimals-separator"><?php _e( 'Decimals Separator', 'LION' ) ?></label></th>
				<td>
					<?php $form->add_text_box( 'currency-decimals-separator', array( 'class' => 'small-text', 'maxlength' => '1' ) ); ?>
					<br /><span class="description"><?php _e( 'What character would you like to use to separate decimals when displaying prices?', 'LION' ); ?></span>
				</td>
			</tr>
            <?php do_action( 'it_exchange_general_settings_before_settings_registration', $form ); ?>
			<tr valign="top">
				<th scope="row"><strong><?php _e( 'Customer Registration Settings', 'LION' ); ?></strong></th>
				<td></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="site-registration"><?php _e( 'Customer Registration', 'LION' ) ?></label></th>
				<td>
					<?php $form->add_radio( 'site-registration', array( 'value' => 'it' ) ); ?>
                	<label for="site-registration-it"><?php _e( 'Use Exchange Registration Only', 'LION' ) ?></label>
                    <br />
					<?php $form->add_radio( 'site-registration', array( 'value' => 'wp' ) ); ?>
                	<label for="site-registration-wp"><?php _e( 'Use WordPress Registration Setting', 'LION' ) ?></label><span class="tip" title="<?php esc_attr_e( __( 'In order to use this setting, you will first need to check the "Anyone can register" checkbox from the WordPress General Settings page to allow site membership.', 'LION' ) ); ?>">i</span>
				</td>
			</tr>
            <?php do_action( 'it_exchange_general_settings_before_settings_styles', $form ); ?>
			<tr valign="top">
				<th scope="row"><strong><?php _e( 'Stylesheet Settings', 'LION' ); ?></strong></th>
				<td></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="custom-styles"><?php _e( 'Custom Styles', 'LION' ) ?></label></th>
				<td>
					<?php _e( 'If they exist, the following files will be loaded in order after core Exchange stylesheets:', 'LION' ); ?><br />
					<span class="description">
						<?php
						$parent = get_template_directory() . '/exchange/style.css';
						$child  = get_stylesheet_directory() . '/exchange/style.css';
						$custom_style_locations[$parent] = '&#151;&nbsp;&nbsp;' . $parent;
						$custom_style_locations[$child]  = '&#151;&nbsp;&nbsp;' . $child;
						echo implode( $custom_style_locations, '<br />' );
						?>
					</span>
				</td>
			<?php do_action( 'it_exchange_general_settings_table_bottom', $form ); ?>
		</table>
		<p class="submit"><input type="submit" value="<?php _e( 'Save Changes', 'LION' ); ?>" class="button button-primary" /></p>
		<?php do_action( 'it_exchange_general_settings_form_bottom', $form ); ?>
	<?php $form->end_form(); ?>
	<?php do_action( 'it_exchange_general_settings_page_bottom' ); ?>
</div>
