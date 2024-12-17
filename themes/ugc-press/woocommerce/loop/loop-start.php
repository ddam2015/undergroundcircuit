<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

$column_classes = array(
  [""],
  ["align-center"],
  ["small-up-2"],
  ["small-up-2", "medium-up-3"],
  ["small-up-2", "medium-up-3", "large-up-4"],
  ["small-up-2", "medium-up-3", "large-up-5"],
  ["small-up-3", "medium-up-4", "large-up-6"],
  ["small-up-3", "medium-up-4", "large-up-7", "xlarge-up-8"],
  ["small-up-3", "medium-up-4", "large-up-8", "xlarge-up-10"]
);

$col_count = intval( esc_attr( wc_get_loop_prop( 'columns' ) ) );
if( !array_key_exists( $col_count , $column_classes ) ) $col_count = ( $col_count > 8 ) ? 8 : $col_count;
?>
<div class="products products-grid grid-x grid-margin-x <?php echo implode(' ', $column_classes[$col_count]); ?>">
