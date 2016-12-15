<?php
/**
 * The transaction meta loop.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange/content-confirmation/loops/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_confirmation_before_transaction_meta_loop' ); ?>
<div class="it-exchange-transaction-meta">
	<?php do_action( 'it_exchange_content_confirmation_begin_transaction_meta_loop' ); ?>
	<?php foreach( it_exchange_get_template_part_elements( 'content_confirmation', 'transaction_meta', array( 'order-details-label', 'order-number', 'date', 'total', 'status', 'instructions' ) ) as $meta ) : ?>
		<?php it_exchange_get_template_part( 'content-confirmation/elements/' . $meta ); ?>
	<?php endforeach; ?>

	<div class="it-exchange-columns-wrapper it-exchange-clearfix">
		<?php foreach( it_exchange_get_template_part_elements( 'content_confirmation', 'address_meta', array( 'billing-address', 'shipping-address' ) ) as $meta ) : ?>
			<?php it_exchange_get_template_part( 'content-confirmation/elements/' . $meta ); ?>
		<?php endforeach; ?>
	</div>
	<?php do_action( 'it_exchange_content_confirmation_end_transaction_meta_loop' ); ?>
</div>
<?php do_action( 'it_exchange_content_confirmation_after_transaction_meta_loop' ); ?>