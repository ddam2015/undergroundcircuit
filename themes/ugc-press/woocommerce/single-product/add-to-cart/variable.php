<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.1
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
				<?php foreach ( $attributes as $attribute_name => $options ) : 
        if (stripos($product->get_sku(), 'tix') !== false) { //grid layout for tickets
          $prod_vars = $product->get_available_variations();
          if( !empty( $prod_vars ) ) {
            echo '<div class="grid-x grid-margin-x">';
            $prod_vars_count = count( $prod_vars );
            $divider_html = '';
            if( $prod_vars_count > 3 ) {
              $divide_count = intval(ceil($prod_vars_count/2));
              echo '<div class="cell small-12 medium-6">';
            }
            foreach( $prod_vars as $dex => $var_data ) {
              if( $dex === $divide_count ) echo '</div><div class="cell small-12 medium-6">';
              echo '<p class="no-margin-bottom">' . $var_data['attributes']['attribute_options'] . '</p>';
              echo  '<div class="grid-x input-group input-number-group"><a class="small-2 large-1 input-group-button button no-margin-bottom minus-button">-</a>';
              echo  '<input class="small-2 large-4 input-number no-margin-bottom variable-input input-quantity" type="number" data-var_id="' . $var_data['variation_id'] . '" data-prod_id="' . $product->get_id() . '" value="0" min="0">';
              echo  '<a class="small-2 large-1 input-group-button button no-margin-bottom add-button">+</a>';
              echo  '<div class="small-2 large-1 input-group-label">x</div><div class="small-4 large-2 input-group-label target-number" data-target_number="' . $var_data['display_price'] . '">$' . $var_data['display_price'] . '</div>';
              echo  '<div class="small-6 large-1 input-group-label">=</div><div class="small-6 large-2 input-group-label target-total">$<span class="calc-sub-total">0</span></div>';
              echo  '</div>';
            }
            if( $prod_vars_count > 3 ) echo '</div>';
            echo '<hr class="cell small-12" /><div class="cell small-12 text-center emphasis">Total: $<span id="calc-total">0</span></div>';
            echo '</div>';
          }
        } else {
        ?>
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
				<?php } endforeach;?>
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
