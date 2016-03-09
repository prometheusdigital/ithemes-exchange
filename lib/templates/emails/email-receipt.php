<?php
/**
 * This file contains the markup for the email template.
 *
 * @since   1.36
 * @link    http://ithemes.com/codex/page/Exchange_Template_Updates
 * @package IT_Exchange
 *
 * WARNING: Do not edit this file directly. To use
 * this template in a theme, simply copy over this
 * file's content to the exchange directory located
 * at your templates root.
 *
 * Example: theme/exchange/email.php
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title>iThemes Exchange</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

</head>
<body style="margin: 0 !important; padding: 0 10px !important; background: <?php it_exchange( 'email', 'background-color' ); ?>; font-family: <?php it_exchange( 'email', 'body-font' ); ?>; color: <?php it_exchange( 'email', 'body-text-color' ); ?>; font-size: <?php it_exchange( 'email', 'body-font-size' ); ?>px;">

<!-- HIDDEN PREHEADER TEXT -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
	Pre-header text. This should show up in the mail client preview lines. Could be used as a sort of sub-subject line to entice an open.
</div>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<!-- begin site name / logo header -->
	<tr>
		<td align="center">
			<!--[if mso]>
			<center>
				<table>
					<tr>
						<td width="640">
			<![endif]-->
			<table id="header" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 640px; background: <?php it_exchange( 'email', 'header-background' ); ?>; margin: 40px auto 0 auto; <?php echo it_exchange( 'email', 'has-header-image' ) ? 'min-height:225px;' : ''; ?>" class="wrapper">
				<tr>
					<td align="center" valign="top" style="padding: 54px 25px; background-image: url(<?php it_exchange( 'email', 'header-image' ); ?>); background-position: top center; background-repeat: no-repeat; background-size: cover; border-top: 5px solid <?php it_exchange( 'email', 'header-background' ); ?>; border-bottom: 0; border-radius: 5px 5px 0 0;">
						<?php if ( it_exchange( 'email', 'has-header-logo' ) ): ?>
							<img src="<?php it_exchange( 'email', 'header-logo' ); ?>" width="<?php it_exchange( 'email', 'header-logo-size' ); ?>" />
						<?php endif; ?>

						<?php if ( it_exchange( 'email', 'has-header-store-name' ) ): ?>
							<h1 style="color: <?php it_exchange( 'email', 'header-store-name-color' ); ?>; font-family: <?php it_exchange( 'email', 'header-store-name-font' ); ?>; font-size: <?php it_exchange( 'email', 'header-store-name-size' ); ?>px; margin: 20px 0 0 0;">
								<?php it_exchange( 'email', 'header-store-name' ); ?>
							</h1>
						<?php endif; ?>
					</td>
				</tr>
			</table>
			<!--[if mso]>
			</td></tr></table>
			</center>
			<![endif]-->
		</td>
	</tr>
	<!-- end site name / logo header -->

	<!-- begin content heading -->
	<tr>
		<td align="center">
			<!--[if mso]>
			<center>
				<table>
					<tr>
						<td width="640">
			<![endif]-->
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 640px; background: <?php it_exchange( 'email', 'body-background-color' ); ?>;  margin: 25px auto 0 auto; border-bottom: 1px solid <?php it_exchange( 'email', 'body-border-color' ); ?>;" class="wrapper body-bkg-color body-border-color">
				<tr>
					<td valign="top" style="padding: 20px 25px;">
						<table width="100%">
							<tr>
								<td style="font-weight: bold; ">
									<strong><?php it_exchange( 'transaction', 'date' ); ?></strong>
								</td>
								<td align="right" style="font-weight: bold; ">
									<strong><?php it_exchange( 'transaction', 'total' ); ?></strong>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!--[if mso]>
			</td></tr></table>
			</center>
			<![endif]-->
		</td>
	</tr>
	<!-- end content heading -->

	<!-- begin order meta -->
	<tr>
		<td align="center">
			<!--[if mso]>
			<center>
				<table>
					<tr>
						<td width="640">
			<![endif]-->
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 640px; background: <?php it_exchange( 'email', 'body-background-color' ); ?>; padding-bottom: 20px; margin: 0 auto;" class="wrapper body-bkg-color">
				<tr>
					<td valign="top" style="padding: 20px 25px; ">
						<table width="100%">
							<tr>
								<?php if ( it_exchange( 'transaction', 'has-billing-address' ) ): ?>
									<td style="line-height: 1.4; ">
										<strong>Billing Address</strong><br>
										<?php it_exchange( 'transaction', 'billing-address' ); ?>
									</td>
								<?php endif; ?>

								<?php if ( it_exchange( 'transaction', 'has-shipping-address' ) ): ?>
									<td style="line-height: 1.4; ">
										<strong>Shipping Address</strong><br>
										<?php it_exchange( 'transaction', 'shipping-address' ); ?>
									</td>
								<?php endif; ?>

								<td style="line-height: 1.4; ">
									<strong>Payment Method</strong><br>
									<?php it_exchange( 'transaction', 'method' ); ?><br><br>
									<strong>Order Number</strong><br>
									<?php it_exchange( 'transaction', 'order-number', array( 'label' => '%s' ) ); ?><br>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!--[if mso]>
			</td></tr></table>
			</center>
			<![endif]-->
		</td>
	</tr>
	<!-- end order meta -->

	<!-- begin cart details -->
	<tr>
		<td align="center">
			<!--[if mso]>
			<center>
				<table>
					<tr>
						<td width="640">
			<![endif]-->
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 640px; background: <?php it_exchange( 'email', 'body-background-color' ); ?>; margin: 0 auto;" class="wrapper body-bkg-color">
				<tr>
					<td valign="top" style="padding: 20px 25px; ">
						<table width="100%" style="line-height: 1.2;">
							<tr>
								<th align="left" style="border-bottom: 1px solid <?php it_exchange( 'email', 'body-border-color' ); ?>; padding: 0 0 10px 0;" class="body-border-color">Description</th>
								<th align="left" style="border-bottom: 1px solid <?php it_exchange( 'email', 'body-border-color' ); ?>; padding: 0 0 10px 0;" class="body-border-color">Qty</th>
								<th align="right" style="border-bottom: 1px solid <?php it_exchange( 'email', 'body-border-color' ); ?>; padding: 0 0 10px 0;" class="body-border-color">Price</th>
							</tr>
							<?php while ( it_exchange( 'transaction', 'products' ) ): ?>
								<tr>
									<td align="left" style="border-bottom: 1px solid <?php it_exchange( 'email', 'body-border-color' ); ?>; padding-top: 10px;" class="body-border-color">
										<table>
											<tr>
												<?php if ( it_exchange( 'transaction', 'has-featured-image' ) ): ?>
													<td>
														<img src="<?php it_exchange( 'transaction', 'featured-image', 'format=url&size=thumbnail' ); ?>" width="80" style="margin-right: 20px;" />
													</td>
												<?php endif; ?>
												<td style="vertical-align: top">
													<strong><?php it_exchange( 'transaction', 'product-attribute', 'attribute=product_name' ); ?></strong><br>
													<?php it_exchange( 'transaction', 'variants' ); ?>
													<?php if ( it_exchange( 'transaction', 'has-purchase-message' ) ): ?>
														<p style="border-left: 4px solid <?php it_exchange( 'email', 'body-border-color' ); ?>; padding-left: 10px; max-width: 300px; font-size: .9em" class="body-border-color">
															<?php it_exchange( 'transaction', 'purchase-message' ); ?>
														</p>
													<?php endif; ?>
												</td>
											</tr>
										</table>
									</td>
									<td align="left" style="border-bottom: 1px solid <?php it_exchange( 'email', 'body-border-color' ); ?>; padding-top: 10px;" class="body-border-color">
										<?php it_exchange( 'transaction', 'product-attribute', 'attribute=product_count' ); ?>
									</td>
									<td align="right" style="border-bottom: 1px solid <?php it_exchange( 'email', 'body-border-color' ); ?>; padding-top: 10px;" class="body-border-color">
										<?php it_exchange( 'transaction', 'product-attribute', 'attribute=product_subtotal' ); ?>
									</td>
								</tr>
							<?php endwhile; ?>
						</table>
					</td>
				</tr>
			</table>
			<!--[if mso]>
			</td></tr></table>
			</center>
			<![endif]-->
		</td>
	</tr>
	<!-- end cart details -->

	<!-- begin cart totals -->
	<tr>
		<td align="center">
			<!--[if mso]>
			<center>
				<table>
					<tr>
						<td width="640">
			<![endif]-->
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background: <?php it_exchange( 'email', 'body-background-color' ); ?>; max-width: 640px; padding-bottom: 50px;" class="wrapper body-bkg-color">
				<tr>
					<td valign="top" style="padding: 0 25px;">
						<table width="100%" style="line-height: 1.2;">
							<tr>
								<td></td>
								<td align="right" style="padding: 10px; ">
									<strong>Subtotal</strong>
								</td>
								<td align="right" style="padding: 10px 0 10px 10px; ">
									<?php it_exchange( 'transaction', 'subtotal' ); ?>
								</td>
							</tr>
							<tr>
								<td></td>
								<td align="right" style="padding: 10px; ">
									<strong>Shipping</strong>
								</td>
								<td align="right" style="padding: 10px 0 10px 10px; ">
									<?php it_exchange( 'transaction', 'shipping-total' ); ?>
								</td>
							</tr>
							<tr>
								<td></td>
								<td align="right" style="padding: 10px; ">
									<strong>Total</strong>
								</td>
								<td align="right" style="padding: 10px 0 10px 10px; ">
									<strong><?php it_exchange( 'transaction', 'total' ); ?></strong>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!--[if mso]>
			</td></tr></table>
			</center>
			<![endif]-->
		</td>
	</tr>
	<!-- end cart totals -->

	<!-- begin order meta -->
	<tr>
		<td align="center">
			<table id="footer" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 640px; padding-top: 20px;" class="wrapper">
				<tr>
					<td valign="top" align="center" style="padding: 10px 25px 100px 25px; ">
						<table width="100%">
							<tr style="text-align: center;">
								<td style="color: <?php it_exchange( 'email', 'footer-text-color' ); ?>;" class="footer-text-container">
									<?php it_exchange( 'email', 'footer-text' ); ?>
								</td>
							</tr>
							<tr class="footer-logo-container" style="text-align: center">
								<td>
									<?php if ( it_exchange( 'email', 'has-footer-logo' ) ): ?>
										<img src="<?php it_exchange( 'email', 'footer-logo' ); ?>" width="<?php it_exchange( 'email', 'footer-logo-size' ); ?>" style="margin-top: 40px;" />
									<?php endif; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- end order meta -->
</table>
<?php if ( is_customize_preview() ) {
	wp_footer();
} ?>
</body>
</html>