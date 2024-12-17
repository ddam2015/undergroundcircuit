<?php

// https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=Hello%20world&choe=UTF-8
// https://www.businessbloomer.com/woocommerce-easily-get-order-info-total-items-etc-from-order-object/


if ( function_exists( 'is_woocommerce' ) ) {

  //gatekeeper functionality
  //my account add profile edit section
  add_filter ( 'woocommerce_account_menu_items', 'gatekeep_account_customization' );
  function gatekeep_account_customization( $menu_links ) {
    $profile_links[ 'gatekeep' ] = 'Redeem Ticket'; 
    if( current_user_can('gate_controller') ) return array_slice( $menu_links, -1, NULL, true ) + $profile_links;
    return $menu_links;
  }

  //add the gatekeeper page
  add_action( 'init', 'gatekeep_edit_endpoint' );
  function gatekeep_edit_endpoint() { add_rewrite_endpoint( 'gatekeep', EP_PAGES ); }

  //my account g365 edit data page
  add_action( 'woocommerce_account_gatekeep_endpoint', 'gatekeep_endpoint_content' );
  function gatekeep_endpoint_content() { ?>
    <div class="cell small-12 medium-8 large-6">
    <?php
    if( current_user_can('gate_controller') ) {
      global $wp;
      $url_parts = explode( '/', wp_parse_url( $wp->request )['path'] );
      //get url var order_id if we have one, else display an input box for the order number\
      if( !empty($url_parts[2]) && is_numeric( $url_parts[2] ) ) {
        $order_id = intval($url_parts[2]);
        //get order data
        $order = wc_get_order( $order_id );
        if( $order === false ) { ?>
          <h1>Bad Order Number - Try Again</h1>
          <div class="small-12 cell">
            <div class="input-group input-number-group">
              <input class="input-number no-margin-bottom" type="number" placeholder="Numbers Only: 4356">
              <button class="input-group-button button no-margin-bottom minus-button" onclick="location.href = '/account/gatekeep/' + $(this).prev().val();">Load Order</button>
            </div>
          </div>
          <?php
        } else {
          //get existing data if there is any
          $existing = $order->get_meta( '_gate_control' );
          if( empty($existing) ) {
            $make_quantities = array();
            foreach ( $order->get_items() as $item_id => $item ) {
              $make_quantities[ (($item->get_variation_id() === 0) ? $item->get_product_id() : $item->get_variation_id() ) ] = array(
                'name'      => $item->get_name(),
                'quantity'  => $item->get_quantity(),
                'quantity_redeemed'  => 0
              );
            }
            $existing = $make_quantities;
          } //end of build first order redeemption
          
          //local time
          $dt = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
          //if we need to do some redeeming
          if( !empty($_POST['redeemption']) ) {
            foreach ( $_POST['redeemption'] as $item_id => $item_redeemed ) $existing[$item_id]['quantity_redeemed'] += $item_redeemed;
            $existing['ut'] = $dt->format('m/d/Y H:i:s');
            $order->update_meta_data( '_gate_control', $existing );
            $order->save();
            ?>
            <h1>Order# <?php echo $order->get_order_number(); ?> Redeemed</h1>
            <div class="small-12 cell">
              <?php
              foreach ( $existing as $item_id => $item_data ) {
                if( $item_id === 'ut' ) continue;
                $available_quantity = ($item_data['quantity'] - $item_data['quantity_redeemed']);
                echo '<p class="no-margin-bottom">' . $item_data['name'] . ' : ' . $available_quantity . ' left.</p>';
              }
              ?>
              <hr />
              <a class="button expanded" href="/account/gatekeep/">Load another order</a>
            </div>
            <?php
          } else {
            ?>
            <h1>Redeem Order# <?php echo $order->get_order_number(); ?></h1>
            <form method="post">
              <?php //<?php echo ; ? >
              //very usful intersection function, searches string for any single words in array, put into global at some point
              function contains($needles, $haystack) {
                return count(array_intersect($needles, explode(" ", preg_replace("/[^A-Za-z0-9' -]/", "", $haystack))));
              }
              //loop through all existing options and print them accordingly
              foreach ( $existing as $item_id => $item_data ) {
                if( $item_id === 'ut' ) continue;
                //check the available quantity
                $available_quantity = ($item_data['quantity'] - $item_data['quantity_redeemed']);
                //if the product variation title has a day of the week, and it's not that day today, print a message about the availability, but don't display redemption tools
                if( contains( array('Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'), $item_data['name'] ) && strpos( $item_data['name'], $dt->format("l") ) === false ) { ?>
                    <div class="small-12 cell">
                      <p class="tiny-margin-bottom tiny-padding green-border"><?php echo $item_data['name']; ?> | Available: <?php echo $available_quantity . '/' . $item_data['quantity']; ?></p>
                    </div>
                    <?php
                } else {
                  //if it's the correct day, but we don't have any redeemable tickets 
                  if( $available_quantity === 0 ) { ?>
                    <div class="small-12 cell">
                      <p class="tiny-margin-bottom tiny-padding green-border"><?php echo $item_data['name']; ?>: All Redeemed</p>
                    </div>
                    <?php 
                  } else {
                    //the buttons and tools to redeem tickets
                    ?>
                    <div class="small-12 cell">
                      <p class="no-margin-bottom"><?php echo $item_data['name']; ?></p>
                      <div class="input-group input-number-group">
                        <a class="input-group-button button no-margin-bottom minus-button">-</a>
                        <input class="input-number no-margin-bottom" type="number" name="redeemption[<?php echo $item_id; ?>]" value="<?php echo $available_quantity; ?>" min="0" max="<?php echo $available_quantity; ?>">
                        <a class="input-group-button button no-margin-bottom add-button">+</a>
                        <div class="input-group-label">
                          Available: <?php echo $available_quantity . '/' . $item_data['quantity']; ?>
                        </div>
                      </div>
                    </div>
                  <?php 
                  }
                }
              } ?>
              <button class="button expanded" type="submit">
                Redeem these Tickets
              </button>
            </form>
            <?php
          } //end save or redeem
        }
      } else {
        //default dashboard for gatekeepers
        ?>
        <h1>Select Order to Redeem</h1>
        <div class="small-12 cell">
          <div class="input-group input-number-group">
            <input class="input-number no-margin-bottom" type="number" placeholder="Numbers Only: 4356">
            <button class="input-group-button button no-margin-bottom minus-button" onclick="location.href = '/account/gatekeep/' + $(this).prev().val();">Load Order</button>
          </div>
        </div>
        <?php
      }
    } else {
      //if you aren't a gatekeeper and you come to this page
      ?>
      <h1>Please see event gatekeeper.</h1>
      <div class="small-12 cell">
        <p>A gatekeeper will be able to assist you further.</p>
      </div>
    <?php } ?>
    </div>
  <? }
  
  //add qr code to new order email
  add_action( 'woocommerce_email_before_order_table', 'qr_code_email_header', 10, 2 );
  function qr_code_email_header( $order ) {
    foreach ( $order->get_items() as $item_id => $item ) {
      $product = $item->get_product();
      if (stripos($product->get_sku(), 'tix') !== false) { ?>
        <div>
          <p>
            Present this email at the door for admission. You can use your smartphone, or print for your convenience.
          </p>
          <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo site_url() . '/account/gatekeep/' . $order->get_order_number(); ?>&choe=UTF-8" target="_blank" />
        </div>
        <?php
        break;
      }
    }
  }
} //end is_woocommerce

?>