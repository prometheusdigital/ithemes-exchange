<?php
/**
 * Default template for displaying the an exchange
 * customer's purchase(s).
 * 
 * @since 0.4.0
 * @version 1.0.1
 * @link http://ithemes.com/codex/page/Exchange_Template_Updates
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange directory located
 * at your templates root.
 * 
 * Example: theme/exchange/content-purchases.php
*/
?>

<div class="it-exchange-purchases-wrapper">
	<?php it_exchange( 'customer', 'menu' ); ?>
	<?php if ( it_exchange( 'transactions', 'found' ) ) : ?>
		<?php while( it_exchange( 'transactions', 'exist' ) ) : ?>
			<div class="it-exchange-purchase">
				<div class="it-exchange-purchase-top">
					<span class="it-exchange-purchase-date"><strong><?php it_exchange( 'transaction', 'date' ); ?></strong></span> 
					<span class="it-exchange-purchase-status">- <?php it_exchange( 'transaction', 'status' ); ?></span> 
					<span class="it-exchange-purchase-total"><strong><?php it_exchange( 'transaction', 'total' ); ?></strong></span>
				</div>
				<?php if ( it_exchange( 'transaction', 'has-products' ) ) : ?>
					<?php while( it_exchange( 'transaction', 'products' ) ) : ?>
					<div class="it-exchange-purchase-items">
						<div class="item-info">
							<div class="item-thumbnail">
								<?php it_exchange( 'transaction', 'product-featured-image' ); ?>
							</div>
							<div class="item-data">
								<h4>
									<?php it_exchange( 'transaction', 'product-attribute', array( 'attribute' => 'title' ) ); ?> 
									<span class="item-price">- <?php it_exchange( 'transaction', 'product-attribute', array( 'attribute' => 'product_subtotal' ) ); ?></span>
									<span class="item-quantity">- <?php it_exchange( 'transaction', 'product-attribute', array( 'attribute' => 'count' ) ); ?></span>
								</h4>
								<p><?php it_exchange( 'transaction', 'product-attribute', array( 'attribute' => 'description' ) ); ?></p>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	<?php endif; ?>
</div>