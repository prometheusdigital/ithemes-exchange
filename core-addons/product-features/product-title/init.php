<?php
/**
 * This add-on will enable the product title (post title ) box on the edit add / edit product page
 *
 * @since 0.3.8
 * @package IT_Cart_Buddy
*/

add_action( 'it_cart_buddy_enabled_addons_loaded', 'it_cart_buddy_init_product_title_addon' );
add_action( 'it_cart_buddy_update_product_feature-product_title', 'it_cart_buddy_product_title_addon_save_title', 9, 2 );
add_filter( 'it_cart_buddy_get_product_feature-product_title', 'it_cart_buddy_product_title_addon_get_title', 9, 2 );

/**
 * Register the product and add it to enabled product-type addons
 *
 * @since 0.3.8
*/
function it_cart_buddy_init_product_title_addon() {
	// Register the product feature
	$slug        = 'product_title';
	$description = 'The title of the product';
	it_cart_buddy_register_product_feature( $slug, $description );

	// Add it to all enabled product-type addons
	$products = it_cart_buddy_get_enabled_addons( array( 'category' => 'product-type' ) );
	foreach( $products as $key => $params ) {
		it_cart_buddy_add_feature_support_to_product_type( 'product_title', $params['slug'] );
	}
}

/**
 * Return the product's title
 *
 * @since 0.3.8
 * @param mixed $title the values passed in by the WP Filter API. Ignored here.
 * @param integer product_id the WordPress post ID
 * @return string post_title
*/
function it_cart_buddy_product_title_addon_get_title( $title, $product_id ) {
	return get_the_title( $product_id );
}
