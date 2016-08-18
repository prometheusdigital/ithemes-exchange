<?php
/**
 * This is the default template part for the line
 * item child element.
 *
 * @since   1.36.0
 * @version 1.36.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-checkout/loops/ directory
 * located in your theme.
 */
?>
<div class="it-exchange-table-row it-exchange-line-item it-exchange-<?php it_exchange( 'line-item', 'type', 'label=0' ); ?>-item">
	<div class="it-exchange-table-column it-exchange-column-offset" style="width: 10%;"></div>
	<?php it_exchange_get_template_part( 'content-checkout/elements/line-item-name' ); ?>
	<?php it_exchange_get_template_part( 'content-checkout/elements/line-item-quantity' ); ?>
	<?php it_exchange_get_template_part( 'content-checkout/elements/line-item-total' ); ?>
</div>
