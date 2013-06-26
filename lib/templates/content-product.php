<?php
/**
 * The default template for displaying a single iThemes Exchange product
 *
 * @since 0.4.0
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
			<p><strong>Downloads</strong><br />
			<ul>
				<?php while( it_exchange( 'product', 'downloads' ) ): ?>
					<li>
						<em>Download</em>: <?php it_exchange( 'download', 'title' ); ?><br>
						<em>Limit</em>: <?php it_exchange( 'download', 'limit', array( 'unlimited-label' => 'Unlimited' ) ); ?><br>
						<em>Expiration: </em><?php it_exchange( 'download', 'expiration', array( 'never-expires-label' => 'Never Expires' ) ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
			</p>
		<?php endif; ?>

		<?php if ( it_exchange( 'product', 'has-inventory' ) ) : ?>
			<p><strong>Inventory</strong><br /><?php it_exchange( 'product', 'inventory' ); ?></p>
		<?php endif; ?>

		<?php if ( it_exchange( 'product', 'supports-purchase-quantity' ) ) : ?>
			<p><strong>Max Quantity Per Purchase</strong><br /><?php it_exchange( 'product', 'purchase-quantity', array( 'format' => 'max-quantity' ) ); ?></p>
		<?php endif; ?>

		<?php if ( it_exchange( 'product', 'has-availability', 'type=start' ) ) : ?>
			<p><strong>Product Start Availability</strong><br /><?php it_exchange( 'product', 'availability', array('type' => 'start' ) ); ?></p>
		<?php endif; ?>

		<?php if ( it_exchange( 'product', 'has-availability', 'type=end' ) ) : ?>
			<p><strong>Product End Availability</strong><br /><?php it_exchange( 'product', 'availability', array('type' => 'end' ) ); ?></p>
		<?php endif; ?>
        
		<?php it_exchange( 'product', 'purchase-options', array( 'class' => 'hide-if-super-widget' ) ); ?>
	</div>
</div>
