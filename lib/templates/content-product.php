<?php
/**
 * Default template for displaying the a single
 * exchange product.
 * 
 * @since 0.4.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange directory located
 * at your templates root.
 * 
 * Example: theme/exchange/content-product.php
*/
?>

<?php it_exchange_get_template_part( 'messages' ); ?>
<div id="it-exchange-product">
	<div class="product-standard-content product-columns <?php echo ( it_exchange( 'product', 'has-images' ) ) ? ' has-images' : ''; ?>">
		<div class="product-column product-info">
			<div class="product-column-inner">
				<?php if ( it_exchange( 'product', 'has-base-price' ) ) : ?>
					<div class="product-price">
						<p><?php it_exchange( 'product', 'base-price' ); ?></p>
					</div>
				<?php endif; ?>
			
				<?php if ( it_exchange( 'product', 'has-description' ) ) : ?>
					<div class="product-description">
						<?php it_exchange( 'product', 'description' ); ?>
					</div>
				<?php endif; ?>
				
				<?php it_exchange( 'product', 'super-widget' ); ?>
			</div>
		</div>
		<?php if ( it_exchange( 'product', 'has-images' ) ) : ?>
			<div class="product-column product-images">
				<div class="product-column-inner">
					<?php it_exchange( 'product', 'gallery' ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	
	<div class="product-advanced-content">
		<?php if ( it_exchange( 'product', 'has-extended-description' ) ) : ?>
			<div class="extended-description">
				<?php it_exchange( 'product', 'extended-description' ); ?>
			</div>
		<?php endif; ?>
		
		<?php if ( it_exchange( 'product', 'has-downloads' ) ) : ?>
			<p><strong><?php _e( 'Downloads', 'LION' ); ?></strong><br />
			<ul>
				<?php while( it_exchange( 'product', 'downloads' ) ): ?>
					<li>
						<em><?php _e( 'Download', 'LION' ); ?></em>: <?php it_exchange( 'download', 'title' ); ?><br>
						<em><?php _e( 'Limit', 'LION' ); ?></em>: <?php it_exchange( 'download', 'limit', array( 'unlimited-label' => 'Unlimited' ) ); ?><br>
						<em><?php _e( 'Expiration', 'LION' ); ?>: </em><?php it_exchange( 'download', 'expiration', array( 'never-expires-label' => 'Never Expires' ) ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
			</p>
		<?php endif; ?>
		
		<?php if ( it_exchange( 'product', 'has-inventory' ) ) : ?>
			<p><strong><?php _e( 'Inventory', 'LION' ); ?></strong><br /><?php it_exchange( 'product', 'inventory' ); ?></p>
		<?php endif; ?>
		
		<?php if ( it_exchange( 'product', 'supports-purchase-quantity' ) ) : ?>
			<p><strong><?php _e( 'Max Quantity Per Purchase', 'LION' ); ?></strong><br /><?php it_exchange( 'product', 'purchase-quantity', array( 'format' => 'max-quantity' ) ); ?></p>
		<?php endif; ?>
		
		<?php if ( it_exchange( 'product', 'has-availability', 'type=start' ) ) : ?>
			<p><strong><?php _e( 'Product Start Availability', 'LION' ); ?></strong><br /><?php it_exchange( 'product', 'availability', array('type' => 'start' ) ); ?></p>
		<?php endif; ?>
		
		<?php if ( it_exchange( 'product', 'has-availability', 'type=end' ) ) : ?>
			<p><strong><?php _e( 'Product End Availability', 'LION' ); ?></strong><br /><?php it_exchange( 'product', 'availability', array('type' => 'end' ) ); ?></p>
		<?php endif; ?>
	</div>
</div>
