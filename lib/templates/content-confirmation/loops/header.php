<?php
/**
 * The transaction header loop.
 *
 * @since 1.4.0
 * @version 1.0.0
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange/content-confirmation/loops/
 * directory located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_confirmation_before_header_loop' ); ?>
	<?php do_action( 'it_exchange_content_confirmation_begin_header_loop' ); ?>
	<?php foreach( it_exchange_get_template_part_elements( 'content_confirmation', 'header_loop', array( 'message', 'menu' ) ) as $detail ) : ?>
		<?php it_exchange_get_template_part( 'content-confirmation/elements/' . $detail ); ?>
    <?php endforeach; ?>
	<?php do_action( 'it_exchange_content_confirmation_end_header_loop' ); ?>
<?php do_action( 'it_exchange_content_confirmation_after_header_loop' ); ?>