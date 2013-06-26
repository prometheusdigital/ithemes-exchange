<?php
/**
 * Registers IT Exchange Product Tags
 *
 * @package iThemes Exchange
 * @since 0.4.0
 */

if ( !function_exists( 'create_it_exchange_tags' ) ) {

	/**
	 * Registers iThemes Exchange Product Tag Taxonomy
	 *
	 * @since 1.0.0
	 * @uses register_taxonomy()
	 */
	function create_it_exchange_tags() {

		$labels = array(
			'name'              => __( 'Product Tags', 'LION' ),
			'singular_name'     => __( 'Product Tag', 'LION' ),
			'search_items'      => __( 'Search Product Tags', 'LION' ),
			'all_items'         => __( 'All Product Tags', 'LION' ),
			'parent_item'       => __( 'Parent Product Tags', 'LION' ),
			'parent_item_colon' => __( 'Parent Product Tags:', 'LION' ),
			'edit_item'         => __( 'Edit Product Tags', 'LION' ),
			'update_item'       => __( 'Update Product Tags', 'LION' ),
			'add_new_item'      => __( 'Add New Product Tags', 'LION' ),
			'new_item_name'     => __( 'New Product Tag', 'LION' ),
		);

		register_taxonomy(
			'it_exchange_tag',
			array( 'it_exchange_prod' ),
			array(
				'hierarchical' => false,
				'labels'       => $labels,
				'show_ui'      => true,
				'query_var'    => true,
				'rewrite'      => array( 'slug' => 'product-tag' ),
			)
		);

	}
	add_action( 'init', 'create_it_exchange_tags', 0 );

}

if ( !function_exists( 'it_exchange_tags_add_menu_item' ) ) {

	/**
	 * This adds a menu item to the Exchange menu pointing to the WP All [post_type] table
	 *
	 * @since 0.4.0
	 *
	 * @return void
	*/
	function it_exchange_tags_add_menu_item() {
		$url = "edit-tags.php?taxonomy=it_exchange_tag&amp;post_type=it_exchange_prod";
		add_submenu_page( 'it-exchange', __( 'Product Tags', 'LION' ), __( 'Product Tags', 'LION' ), 'update_plugins', $url );
	}
	add_action( 'admin_menu', 'it_exchange_tags_add_menu_item' );

}

if ( !function_exists( 'it_exchange_tags_fix_menu_parent_file' ) ) {

	/**
	 * This fixed the $parent_file variable so that the Exchange top-level menu expands when on the Product Tags page
	 *
	 * @since 0.4.11
	 *
	 * @return void
	*/
	function it_exchange_tags_fix_menu_parent_file() {
		if ( 'it_exchange_tag' == $_GET['taxonomy'] )
			$GLOBALS['parent_file'] = 'it-exchange';
	}
	add_action( 'admin_head-edit-tags.php', 'it_exchange_tags_fix_menu_parent_file' );

}
