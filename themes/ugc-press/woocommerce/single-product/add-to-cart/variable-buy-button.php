<?php
/**
 * Variable product add to cart standalone for grid
 *
 * This original base is yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$available_variations = ( empty($available_variations) ) ? $product->get_available_variations() : $available_variations;
$attributes = ( empty($attributes) ) ? $product->get_attributes() : $attributes;

// $attribute_keys = array_keys( $product->get_available_variations() );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart cell small-12" action="<?php echo esc_url( get_permalink() ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
			<div class="variations small-margin-bottom">
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
        <div class="input-group <?php echo ( count($attributes) == 1 || $attribute_name === array_keys($attributes)[count($attributes)-1] ) ? "no-margin" : "small-margin-bottom"; ?>">
          <label class="input-group-label" for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
          <?php
          wc_dropdown_variation_attribute_options(
            array(
              'options' => $options,
              'class' => 'input-group-button',
              'attribute' => $attribute_name,
              'product' => $product,
              'selected' => null
            )
          );
          ?>
        </div>
				<?php endforeach;?>
			</div>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

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

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
