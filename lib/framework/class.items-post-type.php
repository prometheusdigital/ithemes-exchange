<?php
/**
 * Creates the post type for Items
 *
 * @package IT_Cart_Buddy
 * @since 0.3.0
*/

/**
 * Registers the it_cart_buddy_items post type
 *
 * @since 0.3.0
*/
class IT_Cart_Buddy_Item_Post_Type {
	
	/**
	 * Class Constructor
	 *
	 * @todo Filter some of these options. Not all.
	 * @since 0.3.0
	 * @return void
	*/
	function IT_Cart_Buddy_Item_Post_Type() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	function init() {
		$this->post_type = 'it_cart_buddy_item';
		$labels    = array(
			'name'          => __( 'Items', 'LION' ),
			'singular_name' => __( 'Item', 'LION' ),
			'add_new_item'  => $this->get_add_new_item_label(),
		);
		$this->options = array(
			'labels' => $labels,
			'description' => __( 'A Cart Buddy Post Type for storing all Items in the system', 'LION' ),
			'public'      => true,
			'show_ui'     => true,
			'show_in_nav_menus' => true,
			'show_in_menu'      => false, // We will be adding it manually with various labels based on available item-type add-ons
			'show_in_admin_bar' => false,
			'hierarchical'      => false,
			'supports'          => array( // Support everything but page-attributes for add-on flexibility
				'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields',
				'comments', 'revisions', 'post-formats',
			),
			'register_meta_box_cb' => array( $this, 'meta_box_callback' ),
		);

		add_action( 'init', array( $this, 'register_the_post_type' ) );
	}

	/**
	 * Actually registers the post type
	 *
	 * @since 0.3.0
	 * @return void
	*/
	function register_the_post_type() {
		register_post_type( $this->post_type, $this->options );
	}

	/**
	 * Call Back hook
	 *
	 * @since 0.3.0
	 * @uses it_cart_buddy_get_enabled_add_ons()
	 * @return void
	*/
	function meta_box_callback( $post ) {
		$this->setup_post_type_properties( $post );

		if ( $item_types = it_cart_buddy_get_enabled_add_ons( array( 'category' => array( 'items' ) ) ) ) {
			foreach( $item_types as $addon_slug => $params ) {
				do_action( 'it_cart_buddy_item_metabox_callback_' . $addon_slug, $post );
			}
		}
	}

	/**
	 * Generates the Add New Item Label for a new item
	 *
	 * @since 0.3.0
	 * @return string $label Label for add new item page.
	*/
	function get_add_new_item_label() {
		global $pagenow;
		if ( $pagenow != 'post-new.php' || empty( $_GET['post_type'] ) || 'it_cart_buddy_item' != $_GET['post_type'] )
			return apply_filters( 'it_cart_buddy_add_new_item_label', __( 'Add New Item', 'LION' ) );

		$item_add_ons = it_cart_buddy_get_enabled_add_ons( array( 'category' => array( 'items' ) ) );
		$item = array();
		if ( 1 == count( $item_add_ons ) ) {
			$item = reset( $item_add_ons );
		} else {
			if ( ! empty( $_GET['product_type'] ) && ! empty( $item_add_ons[$_GET['product_type']] ) )
				$item = $item_add_ons[$_GET['product_type']];
			else
				$item['options']['labels']['singular_name'] = 'Item';
		}
		$singular = empty( $item['options']['labels']['singular_name'] ) ? $item['name'] : $item['options']['labels']['singular_name'];
		return apply_filters( 'it_cart_buddy_add_new_item_label-' . $item['slug'], __( 'Add New ', 'LION' ) . $singular );
	}

	/**
	 * Add's Item Type vars to this post
	 *
	 * @since 0.3.1
	 * @return void
	*/
	function setup_post_type_properties() {
		global $post, $pagenow;

		// Set the product type from Param or from post_meta
		$product_type = empty( $_GET['product_type'] ) ? get_post_meta( $post->ID, '_it_cart_buddy_product_type', true ) : $_GET['product_type'];

		// If we're not on the add-new or edit product page, exit. Also, if we're not on the correct post type exit
		if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow && 'it_cart_buddy_item' != get_post_type( $post ) )
			return;

		// If this is a new product, tag the product_type from the URL param
		if ( 'post-new.php' == $pagenow )
			update_post_meta( $post->ID, '_it_cart_buddy_product_type', $product_type );

		// Add to product type to $post object
		$post->it_cart_buddy_product_type = $product_type;
	}
}
$IT_Cart_Buddy_Item_Post_Type = new IT_Cart_Buddy_Item_Post_Type();
