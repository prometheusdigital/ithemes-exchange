<?php
/**
 * The default template part for the download's download url in
 * the content-downloads template part's download-info loop
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_content_download_info_before_download_url' ); ?>
<?php if ( !it_exchange( 'transaction', 'get-product-download-hash', array( 'attribute' => 'download-limit' ) ) || it_exchange( 'transaction', 'get-product-download-hash', array( 'attribute' => 'downloads-remaining' ) ) ) : ?>
    <?php if ( it_exchange( 'transaction', 'get-cleared-for-delivery' ) ) : ?>
        <span>
            <a href="<?php it_exchange( 'transaction', 'product-download-hash', array( 'attribute' => 'download-url' ) ); ?>"><?php _e( 'Download Now', 'LION' ); ?></a>
        </span>
    <?php endif; ?>
<?php endif; ?>
<?php do_action( 'it_exchange_content_download_info_after_download_url' ); ?>