<?php
/**
 * The default template part for the download's
 * expiration in the content-downloads template
 * part's download-data loop.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy this file's
 * content to the exchange/content-downloads/elements
 * directory located in your theme.
*/
?>

<?php if ( it_exchange( 'transaction', 'get-product-download-hash', array( 'attribute' => 'expires' ) ) ) : ?>
	<?php do_action( 'it_exchange_content_downloads_details_before_download-expiration-date' ); ?>
	<span class="it-exchange-download-expiration">
		<?php _e( 'Expires on', 'LION' ); ?> <?php it_exchange( 'transaction', 'product-download-hash', array( 'attribute' => 'expiration-date' ) ); ?>
	</span>
	<?php do_action( 'it_exchange_content_downloads_details_after_download-expiration-date' ); ?>
<?php else : ?>
	<?php do_action( 'it_exchange_content_downloads_details_before_download-expiration-date' ); ?>
	<span class="it-exchange-download-expiration">
		<?php _e( 'No expiration date', 'LION' ); ?>
	</span>
	<?php do_action( 'it_exchange_content_downloads_details_after_download-expiration-date' ); ?>
<?php endif; ?>