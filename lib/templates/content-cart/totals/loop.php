<?php
/**
 * This is the default template part for the apply_coupon action in the content-cart template part
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php do_action( 'it_exchange_content_cart_totals_before_loop' ); ?>
	<div class="it-exchange-table-row">
	<?php do_action( 'it_exchange_content_cart_totals_begin_loop' ); ?>
		<?php foreach ( it_exchange_get_content_cart_totals_details() as $detail ) : ?>
			<?php
            /** 
             * Theme and add-on devs should add code to this loop by 
             * hooking into it_exchange_get_content_cart_totals_details filter
             * and adding the appropriate template file to their theme or add-on
             */
			it_exchange_get_template_part( 'content-cart/totals/details/' . $detail ); ?>
		<?php endforeach; ?>
	<?php do_action( 'it_exchange_content_cart_totals_end_loop' ); ?>
	</div>
<?php do_action( 'it_exchange_content_cart_totals_before_loop' ); ?>
