<?php
/**
 * The default product-images loop for the content-product.php template part
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_content_product_before_product_images_loop' ); ?>
<?php foreach( it_exchange_get_content_product_feature_details( array( 'product-images' ) ) as $detail ): ?>
	<?php it_exchange_get_template_part( 'content-product/details/' . $detail ); ?>
<?php endforeach; ?>
<?php do_action( 'it_exchange_content_product_after_product_images_loop' ); ?>
