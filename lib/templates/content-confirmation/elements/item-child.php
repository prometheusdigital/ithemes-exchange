<?php
/**
 * This is the default template part for the cart
 * item child element.
 *
 * @since   1.36.0
 * @version 1.36.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-confirmation/elements/ directory
 * located in your theme.
 */
?>
<div class="it-exchange-table-row it-exchange-child-item it-exchange-line-item it-exchange-<?php it_exchange( 'line-item', 'type', 'label=0' ); ?>-item">
	<?php it_exchange_get_template_part( 'content-confirmation/elements/line-item-name' ); ?>
	<?php it_exchange_get_template_part( 'content-confirmation/elements/line-item-quantity-total' ); ?>
</div>
