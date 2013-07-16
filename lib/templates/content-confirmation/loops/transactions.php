<?php
/**
 * The transactions loop
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
*/
?>
<?php if ( it_exchange( 'transactions', 'found' ) ) : ?>
    <?php while( it_exchange( 'transactions', 'exist' ) ) : ?>
        
		<?php it_exchange_get_template_part( 'content-confirmation/loops/transaction-meta' ); ?>
		<?php it_exchange_get_template_part( 'content-confirmation/loops/products' ); ?>
        
    <?php endwhile; ?>
<?php else : ?>
    <?php it_exchange_get_template_part( 'content-confirmation/details/fields/no-transaction-found' ); ?>
<?php endif; ?>