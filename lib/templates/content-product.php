<?php
/**
 * Default template for displaying the a single
 * exchange product.
 * 
 * @since 0.4.0
 * @version 1.0.0
 * @link http://ithemes.com/codex/page/Exchange_Template_Updates
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
	
	<?php if ( it_exchange( 'product', 'has-extended-description' ) ) : ?>
		<div class="extended-description advanced-item">
			<?php it_exchange( 'product', 'extended-description' ); ?>
		</div>
	<?php endif; ?>
</div>
