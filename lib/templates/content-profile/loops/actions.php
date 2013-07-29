<?php
/**
 * This is the default template part for the
 * actions loop in the content-profile
 * template part.
 *
 * @since 1.1.0
 * @version 1.1.0
 * @package IT_Exchange
 * 
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, copy over this file
 * to the exchange/content-profile/loops/ directory
 * located in your theme.
*/
?>

<?php do_action( 'it_exchange_content_profile_before_actions_loop' ); ?>
	<div class="it-exchange-customer-actions">
	<?php do_action( 'it_exchange_content_profile_begin_actions_loop' ); ?>
		<?php foreach ( it_exchange_get_template_part_elements( 'content_profile', 'actions', array( 'save' ) ) as $action ) : ?>
			<?php
			/** 
			 * Theme and add-on devs should add code to this loop by 
			 * hooking into it_exchange_get_template_part_elements filter
			 * and adding the appropriate template file to their theme or add-on
			*/
			it_exchange_get_template_part( 'content-profile/elements/' . $action );
			?>
		<?php endforeach; ?>
	<?php do_action( 'it_exchange_content_profile_end_actions_loop' ); ?>
	</div>
<?php do_action( 'it_exchange_content_profile_after_actions_loop' ); ?>