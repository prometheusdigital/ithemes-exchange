<?php
/**
 * The default download-info loop for the content-downloads.php template part
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php if ( it_exchange( 'transaction', 'has-product-downloads' ) ) : ?>
	<?php do_action( 'it_exchange_content_downloads_before_downloads_wrapper' ); ?>
	<div class="downloads-wrapper">
		<?php do_action( 'it_exchange_content_downloads_before_downloads_loop' ); ?>
		<?php while ( it_exchange( 'transaction', 'product-downloads' ) ) : ?>
			<?php do_action( 'it_exchange_content_downloads_begin_downloads_loop' ); ?>
			<div class="download">
				<?php it_exchange_get_template_part( 'content-downloads/details/confirmation-url' ); ?>
				<div class="download-info">
                    <?php it_exchange_get_template_part( 'content-downloads/details/download-title' ); ?>
					<?php if ( it_exchange( 'transaction', 'has-product-download-hashes' ) ) : ?>
						<?php it_exchange_get_template_part( 'content-downloads/loops/download-hashes' ); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php do_action( 'it_exchange_content_downloads_end_downloads_loop' ); ?>
		<?php endwhile; ?>
		<?php do_action( 'it_exchange_content_downloads_after_downloads_loop' ); ?>
	</div>
	<?php do_action( 'it_exchange_content_downloads_after_downloads_wrapper' ); ?>
<?php endif; ?>
