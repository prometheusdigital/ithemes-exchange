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
 * content to the exchange/content-downloads/details
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_download_info_before_download_expiration' ); ?>
<?php it_exchange_get_template_part( 'content-downloads/details/download-expiration-date' ); ?>
<?php it_exchange_get_template_part( 'content-downloads/details/download-limit' ); ?>
<?php it_exchange_get_template_part( 'content-downloads/details/download-url' ); ?>
<?php do_action( 'it_exchange_content_download_info_after_download_expiration' ); ?>
