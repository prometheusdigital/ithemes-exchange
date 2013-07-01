<?php
/**
 * Creates the post type for Transactions
 *
 * @package IT_Exchange
 * @since 0.3.3
*/

/**
 * Registers the it_exchange_tran post type
 *
 * @since 0.3.3
*/
class IT_Exchange_Transaction_Post_Type {
	
	/**
	 * Class Constructor
	 *
	 * @since 0.3.3
	 * @return void
	*/
	function IT_Exchange_Transaction_Post_Type() {
		$this->init();
		
		add_action( 'save_post', array( $this, 'save_transaction' ) );

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'modify_post_type_features' ) );
			add_filter( 'manage_edit-it_exchange_tran_columns', array( $this, 'modify_all_transactions_table_columns' ) );
			add_filter( 'manage_edit-it_exchange_tran_sortable_columns', array( $this, 'make_transaction_custom_columns_sortable' ) );
			add_filter( 'manage_it_exchange_tran_posts_custom_column', array( $this, 'add_transaction_method_info_to_view_all_table_rows' ) );
			add_filter( 'it_exchange_transaction_metabox_callback', array( $this, 'register_transaction_details_admin_metabox' ) );
			add_filter( 'post_row_actions', array( $this, 'rename_edit_to_details' ), 10, 2 );
			add_filter( 'screen_layout_columns', array( $this, 'modify_details_page_layout' ) ); 
			add_filter( 'get_user_option_screen_layout_it_exchange_tran', array( $this, 'update_user_column_options' ) );
			add_action( 'wp_ajax_it-exchange-update-transaction-status', array( $this, 'ajax_update_status' ) );
		}
	}

	function init() {
		$this->post_type = 'it_exchange_tran';
		$labels    = array(
			'name'          => __( 'Payments', 'LION' ),
			'singular_name' => __( 'Payment', 'LION' ),
			'edit_item'     => __( 'Payment Details', 'LION' ),
		);
		$this->options = array(
			'labels'               => $labels,
			'description'          => __( 'An iThemes Exchange Post Type for storing all Payments in the system', 'LION' ),
			'public'               => false,
			'show_ui'              => true,
			'show_in_nav_menus'    => false,
			'show_in_menu'         => false, // We will be adding it manually with various labels based on available product-type add-ons
			'show_in_admin_bar'    => false,
			'hierarchical'         => false,
			'register_meta_box_cb' => array( $this, 'meta_box_callback' ),
			'supports'             => array( // Support everything but page-attributes for add-on flexibility
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'trackbacks',
				'custom-fields',
				'comments',
				'revisions',
				'post-formats',
			),
			'capabilities'         => array(
				'edit_posts'        => 'edit_posts',
				'create_posts'      => false,
				'edit_others_posts' => 'edit_others_posts',
				'publish_posts'     => 'publish_posts',
			),
			'map_meta_cap'         => true,
			'capability_type'      => 'post',
		);

		add_action( 'init', array( $this, 'register_the_post_type' ) );
	}
	
	/**
	 * Change 'Edit Transaction' to 'View Details' in All Payments Table
	 *
	 * @since 0.4.0
	 *
	 * @param array $actions actions array
	 * @param object $post wp_post object
	 * @return array
	*/
	function rename_edit_to_details( $actions, $post ) {
		
		if ( 'it_exchange_tran' === $post->post_type ) 
		$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'View the transaction details', 'LION' ) ) . '">' . __( 'Details', 'LION' ) . '</a>';
		
		return $actions;
		
	}

    /**
     * Set the max columns option for the add / edit product page.
     *
     * @since 0.4.0
     *
     * @param $columns Existing array of how many colunns to show for a post type
     * @return array Filtered array
    */
    function modify_details_page_layout( $columns ) {
        $columns['it_exchange_tran'] = 1;
        return $columns;
    }

    /**
     * Updates the user options for number of columns to use on transaction details page
     *
     * @since 0.4.0
     *
     * @return 2
    */
    function update_user_column_options( $existing ) {
        return 1;
    }

	/**
	 * Actually registers the post type
	 *
	 * @since 0.3.3
	 * @return void
	*/
	function register_the_post_type() {
		register_post_type( $this->post_type, $this->options );
	}

	/**
	 * Callback hook for transaction post type admin views
	 *
	 * @since 0.3.3
	 * @uses it_exchange_get_enabled_add_ons()
	 * @return void
	*/
	function meta_box_callback( $post ) {
		$transaction = it_exchange_get_transaction( $post );

		// Add action for current product type
		if ( $transaction_methods = it_exchange_get_enabled_addons( array( 'category' => array( 'transaction-method' ) ) ) ) {
			foreach( $transaction_methods as $addon_slug => $params ) {
				if ( $addon_slug == $transaction->transaction_method )
					do_action( 'it_exchange_transaction_metabox_callback_' . $addon_slug, $transaction );
			}
		}

		// Do action for any product type
		do_action( 'it_exchange_transaction_metabox_callback', $transaction );
	}

	/**
	 * Provides specific hooks for when iThemes Exchange transactions are saved.
	 *
	 * This method is hooked to save_post. It provides hooks for add-on developers
	 * that will only be called when the post being saved is an iThemes Exchange transaction. 
	 * It provides the following 4 hooks:
	 * - it_exchange_save_transaction_unvalidated                    // Runs every time an iThemes Exchange transaction is saved.
	 * - it_exchange_save_transaction_unavalidate-[transaction-method] // Runs every time a specific iThemes Exchange transaction type is saved.
	 * - it_exchange_save_transaction                                // Runs every time an iThemes Exchange transaction is saved if not an autosave and if user has permission to save post
	 * - it_exchange_save_transaction-[transaction-method]             // Runs every time a specific iThemes Exchange transaction-method is saved if not an autosave and if user has permission to save post
	 *
	 * @since 0.3.3
	 * @return void
	*/
	function save_transaction( $post ) { 

		// Exit if not it_exchange_prod post_type
		if ( ! 'it_exchange_tran' == get_post_type( $post ) ) 
			return;

		// Grab enabled transaction-method add-ons
		$transaction_method_addons = it_exchange_get_enabled_addons( array( 'category' => 'transaction-method' ) );
		
		// Grab current post's transaction-method
		$transaction_method = it_exchange_get_transaction_method();

		// These hooks fire off any time a it_exchange_tran post is saved w/o validations
		do_action( 'it_exchange_save_transaction_unvalidated', $post );
		foreach( (array) $transaction_method_addons as $slug => $params ) { 
			if ( $slug == $transaction_method ) { 
				do_action( 'it_exchange_save_transaction_unvalidated_' . $slug, $post );
			}   
		}   

		// Fire off actions with validations that most instances need to use.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( ! current_user_can( 'edit_post', $post ) ) 
			return;

		// This is called any time save_post hook
		do_action( 'it_exchange_save_transaction', $post );
		foreach( (array) $transaction_method_addons as $slug => $params ) { 
			if ( $slug == $transaction_method ) { 
				do_action( 'it_exchange_save_transaction_' . $slug, $post );
			}   
		}   
	}

	/**
	 * Sets the post transaction_method on post creation
	 *
	 * @since 0.3.3
	 * @return void
	*/
	function set_initial_post_transaction_method( $post ) {
		global $pagenow;
		if ( $transaction = it_exchange_get_transaction( $post ) ) {
			if ( ! empty( $transaction->transaction_method ) && ! get_post_meta( $transaction->ID, '_it_exchange_transaction_method', true ) )
				update_post_meta( $transaction->ID, '_it_exchange_transaction_method', $transaction->transaction_method );
		}
	}

	/**
	 * Adds the transaction method column to the View All transactions table
	 *
	 * @since 0.3.3
	 * @param array $existing  exisiting columns array
	 * @return array  modified columns array
	*/
	function modify_all_transactions_table_columns( $existing ) {

		// Add a filter to replace the title text with the Date
		add_filter( 'the_title', array( $this, 'replace_transaction_title_with_order_number' ) );

		// Remove Checkbox - adding it back below
		if ( isset( $existing['cb'] ) ) {
			$check = $existing['cb'];
			unset( $existing['cb'] );
		}

		// Remove Title - adding it back below
		if ( isset( $existing['title'] ) )
			unset( $existing['title'] );

		// Remove Format
		if ( isset( $existing['format'] ) )
			unset( $existing['format'] );

		// Remove Author 
		if ( isset( $existing['author'] ) )
			unset( $existing['author'] );

		// Remove Comments 
		if ( isset( $existing['comments'] ) )
			unset( $existing['comments'] );

		// Remove Date
		if ( isset( $existing['date'] ) )
			unset( $existing['date'] );

		// All Core should be removed at this point. Build ours back (including date from core)
		$exchange_columns = array(
			'cb'                                      => $check,
			'title'                                   => __( 'Order Number', 'LION' ),
			'it_exchange_transaction_total_column'    => __( 'Total', 'LION' ),
			'it_exchange_transaction_status_column'   => __( 'Status', 'LION' ),
			'it_exchange_transaction_customer_column' => __( 'Customer', 'LION' ),
			'it_exchange_transaction_method_column'   => __( 'Method', 'LION' ),
			'date'                                    => __( 'Date', 'LION' ),
		);

		// Merge ours back with existing to preserve any 3rd party columns
		$columns = array_merge( $exchange_columns, $existing );
		return $columns;
	}

	/**
	 * Replace the title with the order_number
	 *
	 * @since 0.4.0
	 *
	 * @param string $title the real title
	 * @return string
	*/
	function replace_transaction_title_with_order_number( $title ) {
		global $post;
		$transaction = it_exchange_get_transaction($post);
		return it_exchange_get_transaction_order_number( $post );
	}

	/**
	 * Makes some of the custom transaction columns added above sortable
	 *
	 * @since 0.3.3
	 * @param array $sortables  existing sortable columns
	 * @return array  modified sortable columnns
	*/
	function make_transaction_custom_columns_sortable( $sortables ) {
		$sortables['it_exchange_transaction_method_column']   = 'it_exchange_transaction_method_column';
		$sortables['it_exchange_transaction_status_column']   = 'it_exchange_transaction_status_column';
		$sortables['it_exchange_transaction_customer_column'] = 'it_exchange_transaction_customer_column';
		$sortables['it_exchange_transaction_total_column']    = 'it_exchange_transaction_total_column';
		return $sortables;
	}

	/**
	 * Adds the values to each row of the custom columns added above
	 *
	 * @since 0.3.3
	 * @param string $column  column title
	 * @param integer $post  post ID
	 * @return void
	*/
	function add_transaction_method_info_to_view_all_table_rows( $column ) {
		global $post, $wp_post_statuses;
		$transaction = it_exchange_get_transaction( $post );
		switch( $column ) {
			case 'it_exchange_transaction_method_column' :
				esc_attr_e( it_exchange_get_transaction_method_name( $transaction ) );
				break;
			case 'it_exchange_transaction_status_column' :
				esc_attr_e( it_exchange_get_transaction_status_label( $post ) );
				break;
			case 'it_exchange_transaction_customer_column' :
				if ( $customer = it_exchange_get_transaction_customer( $transaction ) )
					esc_attr_e( empty( $customer->wp_user->display_name ) ? $customer->wp_user->user_login : $customer->wp_user->display_name );
				else
					esc_attr_e( __( 'Unknown', 'LION' ) );
				break;
			case 'it_exchange_transaction_total_column' :
				esc_attr_e( it_exchange_get_transaction_total( $transaction ) );		
				break;
		}
	}

	/**
	 * This triggers the method to modify what is included in $_wp_post_type_features for the it_exchange_tran post type
	 *
	 * @since 0.3.3
	 * @return void
	*/
	function modify_post_type_features() {
		global $pagenow;
		$post = empty( $_GET['post'] ) ? false : get_post( $_GET['post'] );
		if ( ! $post )
			return false;

		it_exchange_get_transaction( $post );
	}

	/**
	 * Registers the transaction details meta box
	 *
	 * @since 0.4.0
	 *
	 * @param object $post post object
	 * @return void
	*/
	function register_transaction_details_admin_metabox( $post ) {
		// Remove Publish metabox
		remove_meta_box( 'submitdiv', 'it_exchange_tran', 'side' );

		// Remove Slug metabox
		remove_meta_box( 'slugdiv', 'it_exchange_tran', 'normal' );

		// Remove screen options tab
		add_filter('screen_options_show_screen', '__return_false');

		// Transaction Details
		$title     = __( 'Transaction Details', 'LION' );
		$callback  = array( $this, 'print_transaction_details_metabox' );
		$post_type = 'it_exchange_tran';
		add_meta_box( 'it-exchange-transaction-details', $title, $callback, $post_type, 'normal', 'high' );

	}

	/**
	 * Prints the transaction details metabox
	 *
	 * @since 0.4.0
	 * @param object $post post object
	 * @return void
	*/
	function print_transaction_details_metabox( $post ) {
		$confirmation_url = it_exchange_get_transaction_confirmation_url( $post->ID );
		?>
		<div class="transaction-stamp hidden <?php esc_attr_e( strtolower( it_exchange_get_transaction_status_label( $post ) ) ); ?>">
			<?php esc_attr_e( it_exchange_get_transaction_status_label( $post ) ); ?>
		</div>
		
		<div class="customer-data spacing-wrapper">
			<div class="customer-avatar left">
				<?php echo get_avatar( it_exchange_get_transaction_customer_id( $post->ID ), 80 ); ?>
			</div>
			<div class="transaction-data right">
				<div class="transaction-order-number">
					<?php esc_attr_e( it_exchange_get_transaction_order_number( $post ) ); ?>
				</div>
				<div class="transaction-date">
					<?php esc_attr_e( it_exchange_get_transaction_date( $post ) ); ?>
				</div>
				<div class="transaction-status <?php esc_attr_e( strtolower( it_exchange_get_transaction_status_label( $post ) ) ); ?>">
					<?php esc_attr_e( it_exchange_get_transaction_status_label( $post ) ); ?>
				</div>
			</div>
			<div class="customer-info">
				<h2 class="customer-display-name">
					<?php esc_attr_e( it_exchange_get_transaction_customer_display_name( $post ) ); ?>
				</h2>
				<div class="customer-email">
					<?php esc_attr_e( it_exchange_get_transaction_customer_email( $post ) ); ?>
				</div>
				<div class="customer-profile">
					<a href="<?php esc_attr_e( it_exchange_get_transaction_customer_admin_profile_url( $post ) ); ?>">
						<?php _e( 'View Customer Data', 'LION' ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="products">
			<div class="products-header spacing-wrapper">
				<span><?php _e( 'Products', 'LION' ); ?></span>
				<span class="right"><?php _e( 'Amount', 'LION' ); ?></span>
			</div>
			<?php
				// Grab products attached to transaction
				$transaction_products = it_exchange_get_transaction_products( $post );
			
				// Grab all hashes attached to transaction
				$hashes   = it_exchange_get_transaction_download_hash_index( $post );
			?>
			
			<?php foreach ( $transaction_products as $transaction_product ) : ?>
				<?php
					$product_id = $transaction_product['product_id'];
					
					$db_product = it_exchange_get_product( $transaction_product );
				?>
				<div class="product spacing-wrapper">
					<div class="product-header clearfix">
						<div class="product-title left">
							<?php esc_attr_e( it_exchange_get_transaction_product_feature( $transaction_product, 'title' ) ); ?>
						</div>
						<div class="product-subtotal right">
							<?php esc_attr_e( it_exchange_format_price( it_exchange_get_transaction_product_feature( $transaction_product, 'product_subtotal' ) ) ); ?>
						</div>
					</div>
					<div class="product-details">
						<?php if ( $product_downloads = it_exchange_get_product_feature( $transaction_product['product_id'], 'downloads' ) ) : ?>
							<?php foreach( $product_downloads as $download_id => $download_data ) : ?>
								<div class="product-download product-download-<?php esc_attr_e( $download_id ); ?>">
									<h4 class="product-download-title">
										<?php esc_attr_e( get_the_title( $download_id ) ); ?>
									</h4>
									<!--
									NOTE Removing this as it was not in the mock up. However, I'd hate to delete the work I've don in case we want to change this later. - Koop
									<ul class="download-hashes hidden">
										<?php $hashes_for_product_transaction = it_exchange_get_download_hashes_for_transaction_product( $post->ID, $transaction_product, $download_id ); ?>
										<?php foreach( (array) $hashes_for_product_transaction as $hash ) : ?>
											<?php
												$hash_data = it_exchange_get_download_data_from_hash( $hash );
												$download_limit = ( 'unlimited' == $hash_data['download_limit'] ) ? __( 'Unlimited', 'LION' ) : $hash_data['download_limit'];
												$downloads      = empty( $hash_data['downloads'] ) ? (int) 0 : absint( $hash_data['downloads'] );
											?>
											<li class="product-download-hash">
												<span class="hash">
													<?php esc_attr_e( $hash ); ?>
												</span>
												<span class="expires">
													<?php
														if ( $expires = it_exchange_get_download_expiration_date( $hash_data ) )
															printf( __( 'Expires on %s', 'LION' ), $expires );
														else
															_e( "Doesn't expire", 'LION' );
													?>
												</span>
												<span class="limit">
													<?php
														if ( $download_limit )
															printf( __( 'Limited to %d total download(s)', 'LION' ), $download_limit );
														else
															_e( 'Unlimited downloads', 'LION' );
													?>
												</span>
												
												<?php if ( $download_limit ) : ?>
													<p class="remaining">
														<?php echo ( $download_limit - $downloads ) . ' '. __( 'Downloads Remaining', 'LION' ); ?>
													</p>
												<?php endif; ?>
												<p class="count">
													<?php printf( __( 'Downloaded %d Time(s)', 'LION' ), $downloads ); ?>
												</p>
											</li>
										<?php endforeach; ?>
									</ul>
									ENDNOTE
									-->
								</div>
							<?php endforeach; ?>
						<?php else : ?>
							<div class="no-product-downloads">
								<?php _e( 'This product does not contain any downloads', 'LION' ); ?>
							</div> 
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="transaction-costs clearfix spacing-wrapper">
			<div class="transaction-costs-subtotal right clearfix">
				<div class="transaction-costs-subtotal-label left"><?php _e( 'Subtotal', 'LION' ); ?></div>
				<div class="transaction-costs-subtotal-price"><?php esc_attr_e( it_exchange_get_transaction_subtotal( $post ) ); ?></div>
			</div>

			<?php if ( $coupons = it_exchange_get_transaction_coupons( $post ) ) : ?>
				<div class="transaction-costs-coupons right">
					<div class="transaction-costs-coupon-total-label left"><?php _e( 'Total Discount', 'LION' ); ?></div>
					<div class="transaction-costs-coupon-total-amount"><?php esc_attr_e( it_exchange_get_transaction_coupons_total_discount( $post ) ); ?></div>
				</div>
				<label><strong><?php _e( 'Coupons', 'LION' ); ?></strong></label>
				<?php foreach ( $coupons as $type => $coupon ) : ?>
					<?php foreach ( $coupon as $data ) : ?>
						<div class="transaction-cost-coupon">
							<span class="code"><?php echo $data['code'] ?></span>
							<!--
								NOTE Plan to edit this later when more variables are added to the output of the coupon. - Koop
							-->
							<!-- <span class="code amount"><?php echo $data['amount_number'] ?><?php echo $data['amount_type'] ?></span> -->
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			
			<?php if ( $refunds = it_exchange_get_transaction_refunds( $post ) ) : ?>
				<div class="transaction-costs-refunds right">
					<div class="transaction-costs-refund-total">
						<div class="transaction-costs-refund-total-label left"><?php _e( 'Total Refund', 'LION' ); ?></div>
						<div class="transaction-costs-refund-total-amount"><?php esc_attr_e( it_exchange_get_transaction_refunds_total( $post ) ); ?></div>
					</div>
				</div>
				<div class="transaction-refunds-list">
					<label><strong><?php _e( 'Refunds', 'LION' ); ?></strong></label>
					<?php foreach ( $refunds as $refund ) : ?>
						<div class="transaction-costs-refund">
							<span class="code"><?php echo esc_attr( it_exchange_format_price( $refund['amount'] ) ) . ' ' . __( 'on', 'LION' ) . ' ' . esc_attr( $refund['date'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="transaction-summary clearfix spacing-wrapper">
			<div class="payment-method left">
				<div class="payment-method-label"><?php _e( 'Payment Method', 'LION' ); ?></div>
				<div class="payment-method-name"><?php esc_attr_e( it_exchange_get_transaction_method_name( $post ) ); ?></div>
			</div>
			<div class="payment-total right clearfix">
				<div class="payment-total-label left"><?php _e( 'Total', 'LION' ); ?></div>
				<div class="payment-total-amount"><?php _e( it_exchange_get_transaction_total( $post ) ); ?></div>

				<?php if ( $refunds = it_exchange_get_transaction_refunds( $post ) ) : ?>
					<div class="payment-original-total-label left"><?php _e( 'Total before refunds', 'LION' ); ?></div>
					<div class="payment-original-total-amount"><?php _e( it_exchange_get_transaction_total( $post, true, false ) ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		if ( it_exchange_transaction_status_can_be_manually_changed( $post ) ) : 
			?>
			<div class="transaction-status-update clearfix spacing-wrapper">
				<div class="update-status-label left">
					<?php _e( 'Change Status', 'LION' ); ?>
					<span class="tip" title="<?php _e( 'The customer will receive an email When this is changed from a status that is not cleared for delivery to a status that is cleared for delivery', 'LION' ); ?>">i</span>
				</div>
				<div class="update-status-setting right">
					<select id='it-exchange-update-transaction-status'>
						<?php
						if ( $options = it_exchange_get_status_options_for_transaction( $post ) ) {
							$current_status = it_exchange_get_transaction_status( $post );
							foreach( $options as $key => $label ) {
								$status_label = it_exchange_get_transaction_status_label( $post, array( 'status' => $key ) );
								?>
								<option value="<?php esc_attr_e( $key ); ?>" <?php selected( $key, $current_status ); ?>>
									<?php esc_attr_e( $status_label ); ?>
								</option>
								<?php
							}
						}
						?>
					</select>
					<?php wp_nonce_field( 'update-transaction-status' . $post->ID, 'it-exchange-update-transaction-nonce' ); ?>
					<input type="hidden" id="it-exchange-update-transaction-current-status" value="<?php esc_attr_e( $current_status ); ?>" />
					<input type="hidden" id="it-exchange-update-transaction-id" value="<?php esc_attr_e( $post->ID ); ?>" />
					<div class="hidden" id="it-exchange-update-transaction-status-failed"><?php _e( 'Not Saved.', 'LION' ); ?></div>
					<div class="hidden" id="it-exchange-update-transaction-status-success"><?php _e( 'Saved!', 'LION' ); ?></div>
				</div>
			</div>
			<?php
		endif;
	}

	/**
	 * Update transaction status on AJAX calls
	 *
	 * @since 0.4.11
	 *
	 * @return void
	*/
	function ajax_update_status() {
		$transaction_id = empty( $_POST['it-exchange-transaction-id'] ) ? false: absint( $_POST['it-exchange-transaction-id'] );
		$nonce          = empty( $_POST['it-exchange-nonce'] ) ? false: $_POST['it-exchange-nonce'];
		$current_status = empty( $_POST['it-exchange-current-status'] ) ? false : $_POST['it-exchange-current-status'];
		$new_status     = empty( $_POST['it-exchange-new-status'] ) ? false : $_POST['it-exchange-new-status'];

		// Fail if we don't have all the data
		if ( ! $transaction_id || ! $nonce || ! $current_status || ! $new_status )
			die( 'failed' );

		// Fail if we don't have a valid nonce
		if ( ! wp_verify_nonce( $nonce, 'update-transaction-status' . $transaction_id ) )
			die( 'failed' );

		// Fail if status is the same as old status
		if ( $current_status == $new_status )
			die( 'failed' );

		// Fail if transaction isn't found
		if ( ! $transaction = it_exchange_get_transaction( $transaction_id ) )
			die( 'failed' );

		// Attempt to change status
		if ( $current_status != it_exchange_update_transaction_status( $transaction, $new_status ) )
			die( 'success' );
		else
			die ('failed' );
	}
}
$IT_Exchange_Transaction_Post_Type = new IT_Exchange_Transaction_Post_Type();
