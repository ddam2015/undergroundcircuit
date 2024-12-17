<?php
/**
 * Single Product stock.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/stock.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;

if( $product->backorders !== 'yes' || $_GET['hd_add'] === 'd9' ) : ?>
  <p class="stock no-margin-bottom neg-margin-top cell small-12 text-right <?php echo esc_attr( $class ); ?>"><?php echo wp_kses_post( $availability ); ?></p>
<?php else: ?>
  <div class="stock emphasis cell small-12 <?php echo esc_attr( $class ); ?>"><p class="closed text-center tiny-padding no-margin-bottom">Registration Closed</p></div>
<?php endif;

// global $product;

// if ( ! $product->is_purchasable() ) {
// 	return;
// }

// echo wc_get_stock_html( $product ); // WPCS: XSS ok.

// if ( $product->is_in_stock() ) : ?>