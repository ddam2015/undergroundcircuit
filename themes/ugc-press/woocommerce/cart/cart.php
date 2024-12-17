<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form id="wc_cart_form" class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-remove">&nbsp;</th>
				<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-remove">
							<?php
                $added_info = '';
                $product_cats = get_the_terms( $product_id, 'product_cat' );
                if( !empty( $product_cats ) ) {
                  $product_cat_ids = array_map(function($cat_data){ return $cat_data->term_id; }, $product_cats);
                  $added_info .= ' data-prod-cat="' . implode(',', $product_cat_ids) . '"';
                }
                $added_info .= ' data-prod-id="' . $product_id . '"';
                if( $cart_item['variation_id'] === 0 || !empty( $cart_item['variation_id'] ) ) $added_info .= ' data-prod-var-id="' . $cart_item['variation_id'] . '"';
								// @codingStandardsIgnoreLine
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"%s>&times;</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() ),
                  $added_info
								), $cart_item_key );
							?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
						<span class="product-thumbnail"><?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail;
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
						}
						?></span>
            <?php if ( ! $product_permalink ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
						} else {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
						}
          
            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data.
						echo wc_get_formatted_cart_item_data( $cart_item );

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
						}
						?></td>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
//             echo($_product->regular_price);
//             echo("<br>");
//             echo($_product);
//             echo("<br>");
//             echo("<br>");
// 								if(is_user_logged_in()){echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );}else{echo'????';}
          echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>"><?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '<input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input( array(
								'input_name'    => "cart[{$cart_item_key}][qty]",
								'input_value'   => $cart_item['quantity'],
								'max_value'     => $_product->get_max_purchase_quantity(),
								'min_value'     => '0',
								'product_name'  => $_product->get_name(),
                'added_info'    => $added_info
							), $_product, false );
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?></td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
							<?php
// 								if(is_user_logged_in()){echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );}else{echo'????';}
          echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="6" class="actions">
					<div class="grid-x">

						<?php /* g365_change */ if ( wc_coupons_enabled() ) { ?>
							<div class="cell small-12 medium-4">
								<div class="coupon input-group no-margin">
									<label for="coupon_code"><?php esc_html_e( 'Promo Code:', 'woocommerce' ); ?></label>
									<input type="text" name="coupon_code" class="input-text input-group-field" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Code', 'woocommerce' ); ?>" />
									<div class="input-group-button">
										<input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Promo Code', 'woocommerce' ); ?>" />
									</div>
									<?php do_action( 'woocommerce_cart_coupon' ); ?>
								</div>
							</div>
						<?php } ?>

							<div class="cell text-right small-12 medium-8">

              <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <div class="input-group no-margin-bottom cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                  <div class="input-group-field"></div>
                  <div class="input-group-label"><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
                  <div class="input-group-button tiny-padding min-width text-left"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
                </div>
              <?php endforeach; ?>

              </div>
							<div>
								<button id="update_cart" type="submit" class="button no-margin hide" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
							</div>

						<?php /* g365_change end */ ?>

						<?php do_action( 'woocommerce_cart_actions' ); ?>

  					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

					</div>
				</td>
			</tr>
      <tr>
<!--   			<td colspan="6" class="text-right"><?php _e( 'Total', 'woocommerce' ); ?>: <span class="min-width text-left in-block tiny-padding-left"><?php if(is_user_logged_in()){wc_cart_totals_order_total_html();}else{echo'????';} ?></span></td> -->
        <td colspan="6" class="text-right"><?php _e( 'Total', 'woocommerce' ); ?>: <span class="min-width text-left in-block tiny-padding-left"><?php wc_cart_totals_order_total_html();?></span></td>
      </tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
