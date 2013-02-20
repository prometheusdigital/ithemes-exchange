<?php
/**
 * This file prints the add-ons page in the Admin
 *
 * @since 0.3.6
 * @package IT_Cart_Buddy
*/
?>
<div class="wrap">
	<!-- temp icon --> 
	<?php screen_icon( 'page' ); ?>  
	<h2>Cart Buddy Add-Ons</h2>

	<h3>Enabled Add-ons</h3>
	<?php
	if ( $enabled = it_cart_buddy_get_options( 'cart_buddy_enabled_add_ons' ) ) { 
		foreach( (array) $enabled as $slug => $location ) { 
			if ( empty( $registered[$slug] ) ) 
				continue;
			$params = $registered[$slug];
			// TEMPORARY UI
			echo '<div style="height:200px;width:200px;border: 1px solid #444;float:left;margin-right:10px;"><div style="height:20px;background:#999;color:#fff;width:100%;text-align:center;padding:10px 0;">' . $params['name'] . '</div><p style="padding:5px">Category: ' . $add_on_cats[$params['options']['category']]['name'] . '</p><p style="padding:5px;">' . $params['description'] . '</p>';
			if ( ! empty( $params['options']['settings-callback'] ) && is_callable( $params['options']['settings-callback'] ) ) 
				echo '<p><a href="' . admin_url( 'admin.php?page=it-cart-buddy-addons&add_on_settings=' . $slug ) . '">Settings</a>';
			echo '<p style="margin-left:60px;text-align:center;width:75px;background:#999;border:1px solid #777;padding:2px;"><a href="' . get_site_url() . '/wp-admin/admin.php?page=it-cart-buddy-addons&it-cart-buddy-disable-addon=' . $slug . '" style="text-decoration:none;color:#fff;">Disable</a></p></div>';
		}   
	} else {
		echo '<p>' . __( 'No Add-ons currently enabled', 'LION' ) . '</p>';
	}   
	?>  
	<div style="height:1px;clear:both;-top:10px;"></div>
	<hr />

	<h3>Available Add-ons</h3>
	<?php
	$available_addons = false;
	if ( ( $registered ) ) { 
		foreach( $registered as $slug => $params ) { 
			if ( ! empty( $enabled[$slug] ) ) 
				continue;

			$available_addons = true;
			// TEMPORARY UI
			echo '<div style="height:200px;width:200px;border: 1px solid #444;float:left;margin-right:10px;"><div style="height:20px;background:#999;color:#fff;width:100%;text-align:center;padding:10px 0;">' . $params['name'] . '</div><p style="padding:5px">Category: ' . $add_on_cats[$params['options']['category']]['name'] . '</p><p style="padding:5px;">' . $params['description'] . '</p><p style="margin-left:60px;text-align:center;width:75px;background:#999;border:1px solid #777;padding:2px;"><a href="' . get_site_url() . '/wp-admin/admin.php?page=it-cart-buddy-addons&it-cart-buddy-enable-addon=' . $slug . '" style="text-decoration:none;color:#fff;">Enable</a></p></div>';
		}   
	}   
	if ( ! $available_addons )
		echo '<p>' . __( 'No Add-ons available', 'LION' ) . '</p>';
	?>  
</div>
<?php
