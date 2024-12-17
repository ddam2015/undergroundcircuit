<?php
/**
 * Variable subscription product add to cart
 *
 * @author  Prospress
 * @package WooCommerce-Subscriptions/Templates
 * @version 2.2.20
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$available_variations = ( empty($available_variations) ) ? $product->get_available_variations() : $available_variations;
$attributes = ( empty($attributes) ) ? get_attributes() : $attributes;

$user_id = get_current_user_id();

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart cell small-12" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wcs_json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'woocommerce-subscriptions' ); ?></p>
	<?php else : ?>
		<?php if ( ! $product->is_purchasable() && 0 != $user_id && 'no' != wcs_get_product_limitation( $product ) && wcs_is_product_limited_for_user( $product, $user_id ) ) : ?>
			<?php $resubscribe_link = wcs_get_users_resubscribe_link_for_product( $product->get_id() ); ?>
			<?php if ( ! empty( $resubscribe_link ) && 'any' == wcs_get_product_limitation( $product ) && wcs_user_has_subscription( $user_id, $product->get_id(), wcs_get_product_limitation( $product ) ) && ! wcs_user_has_subscription( $user_id, $product->get_id(), 'active' ) && ! wcs_user_has_subscription( $user_id, $product->get_id(), 'on-hold' ) ) : // customer has an inactive subscription, maybe offer the renewal button ?>
				<a href="<?php echo esc_url( $resubscribe_link ); ?>" class="button product-resubscribe-link"><?php esc_html_e( 'Resubscribe', 'woocommerce-subscriptions' ); ?></a>
			<?php else : ?>
				<p class="limited-subscription-notice notice"><?php esc_html_e( 'You have an active subscription to this product already.', 'woocommerce-subscriptions' ); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<?php if ( wp_list_filter( $available_variations, array( 'is_purchasable' => false ) ) ) : ?>
				<p class="limited-subscription-notice notice"><?php esc_html_e( 'You have added a variation of this product to the cart already.', 'woocommerce-subscriptions' ); ?></p>
			<?php endif;
        //prep item for stock listings
        $product_variations = $product->get_available_variations();
        echo '<ul class="attr_avail_custom hide">';
        foreach ($product_variations as $variation)  {
          $var_data = $variation['attributes'];
          if(!$variation['is_in_stock']) {
            echo '<li>';
            foreach ($var_data as $vk => $vv) echo "<span data-key=$vk data-value=$vv >$vv</span>";
            echo '</li>';
          }
        }
        echo '</ul>';
      ?>
			<div class="variations small-margin-bottom">
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<div class="input-group max-button <?php if( count($attributes) == 1 || $attribute_name === array_keys($attributes)[count($attributes)-1] ) : echo "no-margin"; else: echo "small-margin-bottom"; endif; ?>">
						<label class="input-group-label" for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
            <?php
            $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
            wc_dropdown_variation_attribute_options(
              array(
                'options' => $options,
                'class' => 'input-group-button',
                'attribute' => $attribute_name,
                'product' => $product,
                'selected' => $selected
              )
            );
            ?>
  				</div>
				<?php endforeach; ?>
			</div>
			<div class="single_variation_wrap">
				<?php
				/**
				 * woocommerce_before_single_variation Hook.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * woocommerce_after_single_variation Hook.
				 */
				do_action( 'woocommerce_after_single_variation' );
				?>
			</div>

		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
