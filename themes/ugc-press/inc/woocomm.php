<?php

// print script handles
// function head_scripts_handle() {
//     global $wp_scripts;
//     foreach( $wp_scripts->queue as $handle ) :
//         echo $handle,' ';
//     endforeach;
// }
// add_action( 'wp_print_scripts', 'head_scripts_handle' );
// wc-add-to-cart woocommerce wc-cart-fragments wc-chase-paymentech jquery foundation js-all
// wc-add-to-cart wc-cart selectWoo wc-password-strength-meter wc-checkout woocommerce wc-cart-fragments wc-chase-paymentech jquery foundation js-all

get_template_part( 'inc/woocomm-multiproduct' );

//all woocommerce modifications

//declare theme support for woocommerce
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'woocommerce_support' );

//see if woocommerce is installed
if ( function_exists( 'is_woocommerce' ) ) {
  
  //add multiple products to cart with url for Admission
  class add_more_to_cart {
    private $prevent_redirect = false; //used to prevent WC from redirecting if we have more to process

    function __construct() {
      if ( ! isset( $_REQUEST[ 'add-more-to-cart' ] ) ) return; //don't load if we don't have to
      $this->prevent_redirect = 'no'; //prevent WC from redirecting so we can process additional items
      add_action( 'wp_loaded', [ $this, 'add_more_to_cart' ], 21 ); //fire after WC does, so we just process extra ones
      add_action( 'pre_option_woocommerce_cart_redirect_after_add', [ $this, 'intercept_option' ], 9000 ); //intercept the WC option to force no redirect
    }

    function intercept_option() {
      return $this->prevent_redirect;
    }

    function add_more_to_cart() {
      $product_ids = explode( '|', $_REQUEST['add-more-to-cart'] );
      $count = count( $product_ids );
      $number = 0;

      foreach ( $product_ids as $product_id ) {
        $quantity = 1;
        if ( ++$number === $count ) $this->prevent_redirect = false; //this is the last one, so let WC redirect if it wants to.
        if (stripos($product_id, ',') !== false) { //get var_ids and quantities if we have them
          $product_data = explode(',', $product_id);
          if( count($product_data) > 2 ) continue;
          $product_id = $product_data[0];
          $quantity = $product_data[1];
        }
        $_REQUEST['add-to-cart'] = $product_id; //set the next product id
        $_REQUEST['quantity'] = $quantity; //set the next product quantity
        WC_Form_Handler::add_to_cart_action(); //let WC run its own code
      }
    }
  }
  new add_more_to_cart;
// 	End add multiple products
	
  //remove extra images
  add_action('init', 'remove_plugin_image_sizes');
  function remove_plugin_image_sizes() {
    remove_image_size('woocommerce_gallery_thumbnail');
    remove_image_size('woocommerce_thumbnail');
    remove_image_size('shop_thumbnail');
    remove_image_size('woocommerce_single');
    remove_image_size('shop_catalog');
    remove_image_size('shop_single');
  }

  //woocommerce wrappers
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
	function shb_wrapper_start() {
		echo '<section id="content" class="grid-x site-main woocomm-wrap" role="main">';
		echo '<div class="cell small-12 large-padding">';
	}
	function shb_wrapper_end() {
		echo '</div>';
		echo '</section>';
	}
	add_action('woocommerce_before_main_content', 'shb_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'shb_wrapper_end', 10);

	//unhook woocommerce css
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	//unhook woocommerce js if not on a woocommerce page
	function woocommerce_remove_frontend_scripts() {
		if ( !is_product() && !is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() ) {
			remove_action('wp_enqueue_scripts', [WC_Frontend_Scripts::class, 'load_scripts']);
			remove_action('wp_print_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
			remove_action('wp_print_footer_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
		}
	}
	add_action( 'wp', 'woocommerce_remove_frontend_scripts', 99 );

	//if we need to grab certain files for any reason
	// wp_dequeue_style( 'woocommerce_fancybox_styles' );
	// wp_dequeue_style( 'woocommerce_chosen_styles' );
	// wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
	// wp_dequeue_script( 'wc_price_slider' );
	// wp_dequeue_script( 'wc-single-product' );
	// wp_dequeue_script( 'wc-add-to-cart' );
	// wp_dequeue_script( 'wc-cart-fragments' );
	// wp_dequeue_script( 'wc-checkout' );
	// wp_dequeue_script( 'wc-add-to-cart-variation' );
	// wp_dequeue_script( 'wc-single-product' );
	// wp_dequeue_script( 'wc-cart' );
	// wp_dequeue_script( 'wc-chosen' );
	// wp_dequeue_script( 'woocommerce' );
	// wp_dequeue_script( 'prettyPhoto' );
	// wp_dequeue_script( 'prettyPhoto-init' );
	// wp_dequeue_script( 'jquery-blockui' );
	// wp_dequeue_script( 'jquery-placeholder' );
	// wp_dequeue_script( 'fancybox' );
	// wp_dequeue_script( 'jqueryui' );
	
	//change default image sizes
	function shb_woocommerce_image_dimensions() {
		global $pagenow;

		if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
			return;
		}
		$catalog = array(
			'width' 	=> '400',	// px
			'height'	=> '300',	// px
			'crop'		=> 1 		// true
		);
		$single = array(
			'width' 	=> '600',	// px
			'height'	=> '450',	// px
			'crop'		=> 1 		// true
		);
		$thumbnail = array(
			'width' 	=> '200',	// px
			'height'	=> '150',	// px
			'crop'		=> 0 		// false
		);
		// Image sizes
		update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
		update_option( 'shop_single_image_size', $single ); 		// Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	}
	add_action( 'after_switch_theme', 'shb_woocommerce_image_dimensions', 1 );

	//remove the breadcrumb (on product pages)
	function shb_remove_wc_breadcrumbs() {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	}
	add_action( 'init', 'shb_remove_wc_breadcrumbs' );

	//remove coupon from checkout page/section
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
  //remove login prompt before checkout and move it to before cart since they are on the same page.
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
  add_action( 'woocommerce_before_cart', 'woocommerce_checkout_login_form' );

	//remove the 'added to cart' message on the cart page
	add_filter( 'wc_add_to_cart_message_html', '__return_null' );

	//change checkout button text
	function shb_place_order_button_text() { return __( 'Complete Registration', 'woocommerce' ); }
	add_filter( 'woocommerce_order_button_text', 'shb_place_order_button_text' );

  //change empty cart return link
  function wc_empty_cart_redirect_url() {
    return '/';
  }
  add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

  //address update redirect
  function my_save_address_redirect(){
    $update_switch = filter_input( INPUT_GET, 'update_address', FILTER_SANITIZE_STRING );
    if( $update_switch ) {
      wp_safe_redirect( esc_url( $update_switch ) );
      exit;
    }
  }
  add_action( 'woocommerce_customer_save_address', 'my_save_address_redirect', 10, 2 );
  
  //change add to cart button text
	function shb_woocommerce_product_add_to_cart_text(){
		return 'Register';
	}
	add_filter( 'woocommerce_product_add_to_cart_text' , 'shb_woocommerce_product_add_to_cart_text' );
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'shb_woocommerce_product_add_to_cart_text' );
	add_filter( 'woocommerce_booking_single_add_to_cart_text', 'shb_woocommerce_product_add_to_cart_text' );
	
  //add support for gutenberg blocks on products
  function wplook_activate_gutenberg_products($can_edit, $post_type){
    if($post_type == 'product') $can_edit = true;
	  return $can_edit;
  }
  add_filter('use_block_editor_for_post_type', 'wplook_activate_gutenberg_products', 10, 2);

  //my account add profile edit section
  add_filter ( 'woocommerce_account_menu_items', 'g635_account_customization' );
  function g635_account_customization( $menu_links ){
    //if there is a data connection, display the tab
    $current_user = wp_get_current_user();
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    if( !empty($user_g365) && isset($user_g365['pl_ed']) && !empty($user_g365['pl_ed']) ) {
      //add the profiles after the dashboard and any other links we need
      $profile_links[ 'player_editor' ] = 'Player  Editor';
      $menu_links = array_slice( $menu_links, 0, 1, true ) + $profile_links + array_slice( $menu_links, 1, NULL, true );
    }
    // Add custom redeem ticket for gatekeeper
    if( current_user_can('gate_controller') ){
      $profile_links[ 'gatekeep' ] = 'Redeem Ticket'; 
      $menu_links = array_slice( $menu_links, 0, 1, true ) + $profile_links + array_slice( $menu_links, 1, NULL, true );
    }
    return $menu_links;
  }
  //my account g365 data edit
  add_action( 'init', 'g365_data_edit_endpoint' );
  function g365_data_edit_endpoint() {
    //so we can add a page
    add_rewrite_endpoint( 'player_editor', EP_PAGES );
  }
  //Add account data edit
  add_action('init', 'ebc_redeem_ticket_endpoint');
  function ebc_redeem_ticket_endpoint(){
    add_rewrite_endpoint( 'gatekeep', EP_PAGES );
  }
  //my account g365 edit data page
  add_action( 'woocommerce_account_player_editor_endpoint', 'player_editor_endpoint_content' );
  function player_editor_endpoint_content() { ?>
    <div class="cell small-12 medium-8 large-6">
<!--       <h1>Player Manager</h1> -->
    <?php
    $current_user = wp_get_current_user();
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    if( !empty($user_g365) && isset($user_g365['pl_ed']) && !empty($user_g365['pl_ed']) ) {
      if( strpos(site_url(), 'dev.') === false ) {
        wp_enqueue_script( 'js-g365-all', 'https://grassroots365.com/data-processor.js', array('jquery'), '69547', true );
      } else {
        wp_enqueue_script( 'js-g365-all', 'https://dev.grassroots365.com/data-processor.js', array('jquery'), '69547', true );
      }
      ?>
      <div>
        <script type="text/javascript">
          var g365_form_details = {
            "items" : {
              "Players":{
                "name":"",
                "title":"Player Editor",
                "type":"pl_ed",
                "items":{}
              }
            },
            "wrapper_target" : "g365_form_options_anchor",
            "admin_key": "<?php echo g365_make_admin_key(); ?>"
          };
        </script>
        <div>
        <div id="g365_form_options_anchor" data-g365_type="pl_ed,<?php echo implode(',', $user_g365['pl_ed']); ?>"></div>
        </div>
      </div>
    <?php
    } else {
      echo '<p class="xlarge-margin-top xlarge-margin-bottom">Please add some info to the site and you can view/edit it here!</p>';
    }
  }


  
  // ----- validate password match on the registration page
  function g365_confirm_password($reg_errors, $sanitized_user_login, $user_email) {
    global $woocommerce;
    extract( $_POST );
    if ( strcmp( $password, $password2 ) !== 0 ) {
      return new WP_Error( 'registration-error', __( 'Passwords do not match.', 'woocommerce' ) );
    }
    return $reg_errors;
  }
  add_filter('woocommerce_registration_errors', 'g365_confirm_password', 10,3);

}

//remove related products section from single product pages
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


//remove stripe styles
function shb_wc_scripts_styles() {
	if( is_admin() ) return;
  wp_dequeue_style( 'stripe_styles' );
  // js helpers for the cart page
  if( is_cart() || is_checkout() ) {
    wp_enqueue_script( 'js-g365-all', (( strpos(site_url(), 'dev.') === false ) ? 'https://grassroots365.com/data-processor.js' : 'https://dev.grassroots365.com/data-processor.js'), array('jquery'), '69550', true );
  }
}
add_action( 'wp_enqueue_scripts', 'shb_wc_scripts_styles', 100);

//add order details to Stripe payment metadata
function filter_wc_stripe_payment_metadata( $metadata, $order, $source ) {
  $order_data = $order->get_data();
  if(!empty($order_data['total_tax'])) $metadata['Total Tax Charged'] = $order_data['total_tax'];
  if(!empty($order_data['shipping_total']) && floatval($order_data['shipping_total']) > 0) $metadata['Total Shipping Charged'] = $order_data['shipping_total'];
  $count = 1;
  foreach( $order->get_items() as $item_id => $line_item ){
    $item_data = $line_item->get_data();
    $product = $line_item->get_product();
    $product_name = $product->get_name();
    $item_quantity = $line_item->get_quantity();
    $item_total = $line_item->get_total();
    $metadata['Line Item '.$count] = 'Product name: '.$product_name.' | Quantity: '.$item_quantity.' | Item total: '. number_format( $item_total, 2 );
    $count += 1;
  }
  return $metadata;
}
add_filter( 'wc_stripe_payment_metadata', 'filter_wc_stripe_payment_metadata', 10, 3 );

//add the products purchased to the description for quickbooks cataloging
function filter_wc_stripe_payment_description_mod( $post_data, $order, $source ) {
  $prod_data = [];
	foreach( $order->get_items() as $item_id => $line_item ){
		$item_data = $line_item->get_data();
		$product = $line_item->get_product();
		$product_sku = $product->get_sku();
    $product_name = $product->get_name();
    $item_quantity = $line_item->get_quantity();
    $item_total = $line_item->get_total();
    $prod_data[] = 'Name: '.$product_name.' | Quantity: '.$item_quantity.' | Item total: '. number_format( $item_total, 2 );
	}
	$post_data['description'] = sprintf( __( '%1$s | %2$s' ), $post_data['description'], implode(' :: ', $prod_data) );
	return $post_data;
}
add_filter( 'wc_stripe_generate_payment_request', 'filter_wc_stripe_payment_description_mod', 3, 10 );


//add meta data to products
// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'g365_add_event_id_reference' );
// Save Fields
add_action( 'woocommerce_process_product_meta', 'g365_add_event_id_reference_save' );

function g365_add_event_id_reference() {
	wp_enqueue_style( 'foundation-admin', site_url( '/wp-content/themes/ugc-press/css/', 'https' ) . "style-admin.css?ver=2.4" );
	wp_enqueue_script( 'admin-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), '2.2.4', true );
  wp_enqueue_script( 'js-g365-all', (( strpos(site_url(), 'dev.') === false ) ? 'https://grassroots365.com/data-processor.js' : 'https://dev.grassroots365.com/data-processor.js'), array('admin-jquery'), '69550', true );
  
  //
  // update functionality
  //
  
  global $post;
  ?>
  <div class="options_group g365_manage_wrapper">
    <p class="form-field g365_event_link no-input-margin">
      <label for="event_link_selector">G365 Event Link</label>
      <?php
      $event_id = get_post_meta( $post->ID, '_event_link', true );
      $event_data = get_post_meta( $post->ID, '_event_link_data', true );
      woocommerce_wp_hidden_input(array(
        'id'    => 'event_link',
        'value' => empty($event_id) ? '' : $event_id
      ));
      ?>
      <input type="text" class="g365_livesearch_input" id="event_link_selector" value="<?php echo ( empty($event_data->name) ) ? '' : $event_data->name; ?>" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="event_link" data-g365_type="event_names" placeholder="Enter Event Name" autocomplete="off">
    </p>
  </div>
  <?php
  
  //
  // end update functionality
  //

}

function g365_add_event_id_reference_save( $post_id ){
	$event_link = intval($_POST['event_link']);
  if( $event_link != get_post_meta( $post_id, '_event_link', true ) ) {
    if( $event_link === '' ) {
      delete_post_meta( $post_id, '_event_link' );
      delete_post_meta( $post_id, '_event_data_link' );
    } else {
      update_post_meta( $post_id, '_event_link', $event_link );
      //then front load a little data for use later
      $event_data_pull = g365_conn( 'g365_get_event_data', [$event_link, true] );
      $event_data = (object) array();
      if( !empty($event_data_pull->name) ) $event_data->name  = $event_data_pull->name;
      if( !empty($event_data_pull->logo_img) ) $event_data->logo_img  = $event_data_pull->logo_img;
      if( !empty($event_data_pull->dates) ) $event_data->dates  = $event_data_pull->dates;
      if( !empty($event_data_pull->locations) ) $event_data->locations  = $event_data_pull->locations;
      if( !empty($event_data_pull->short_locations) ) $event_data->short_locations  = $event_data_pull->short_locations;
      if( !empty($event_data_pull->divisions) ) $event_data->divisions  = $event_data_pull->divisions;
      if( !empty($event_data) )	update_post_meta( $post_id, '_event_link_data', $event_data );
    }
  }
}

// change the link classes for related product titles.
// remove_action( 'woocommerce_before_shop_loop_item','woocommerce_template_loop_product_link_open', 10 );
// add_action ( 'woocommerce_before_shop_loop_item', 'g365_woocommerce_template_loop_product_link_open', 10 );
// function g365_woocommerce_template_loop_product_link_open() {
// 	echo '<a target="_blank" href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link">';
// }

function shb_cart_process(){
  //for getting form parts
  $g365_cart_types = array();
  //for setting form restrictions/helpers
  $g365_cart_items = array();
  //if we have to add special fields for products like gear or fees
  $extra_fields = array();
  //if we have cart data
  if( !empty(WC()->cart->get_cart()) ) {
    //process each cart item
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

      //parse product info for us to build with
      $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
      //root product id
      $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
      //form type is tied to product categories, we only grab the first cat
      $product_cats = get_the_terms( $product_id, 'product_cat' );
      $product_cat_id = $product_cats[0]->term_id;
      $product_cat_name = $product_cats[0]->name;
      $product_cat_slug = $product_cats[0]->slug;
      //root product title
      $product_name = $_product->get_title();
      //variation id, if there isn't one it should default to 0
      $product_var_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['variation_id'], $cart_item, $cart_item_key );
      //product variation name, if there isn't one it should be empty
      if(!empty ($cart_item['variation']['attribute_divisions'])) {
        $product_var_name = apply_filters( 'woocommerce_cart_item_product_name', $cart_item['variation']['attribute_divisions'], $cart_item, $cart_item_key );
      } else $product_var_name = '';
      //get the event id to pull in any further restictions and key the new data
      $product_event_link = intval(get_post_meta( $product_id, '_event_link', true ));
      //OLD AJAX :(
      //$product_event_divisions_pull = g365_conn( 'g365_get_event_data', [$product_event_link, true] );
      if( !empty($product_event_link) ) {
        $product_event = get_post_meta( $product_id, '_event_link_data', true );
        //extract the division information
        $product_event_divisions = json_decode($product_event->divisions);
        if( !empty( $product_event_divisions ) ){
          //the correc order for divisions
          $product_event_divisions_base = ["Open", "Gold", "Silver", "Bronze", "Copper"];
          //loop through divisions available and reorder them
          foreach($product_event_divisions as $dex => $level) {
            $product_div_reorder = (object) [];
            foreach($product_event_divisions_base as $val) {
              $present_divs = $level->$val;
              if(!is_null($present_divs)) $product_div_reorder->$val = $present_divs;
            }
            $product_event_divisions->$dex = $product_div_reorder;
          }
        } else {
          $product_event_divisions = null;
        }
      }
      $form_type_data = g365_return_keys( 'g365_cat_form_key' );
      //key for form type
      $form_type_opt = $form_type_data[0];
      //key for form targets
      $form_type_target = $form_type_data[1];
      //key for extra field types outside managment
      $extra_types = array( 'tournaments' );
      //product_cat and product_id is needed to organize the cart item data,or if the cat type is invalid
//       return( $product_cat_slug . " :: " . $product_cat_id . " :: " . $product_id . " :: " . $form_type_opt[$product_cat_slug] );
//       if( empty($product_cat_slug) || empty($product_cat_id) || empty($product_id) || empty($form_type_opt[$product_cat_slug]) ) {
        if( in_array($product_cat_slug, $extra_types) ) {
          switch( $product_cat_slug ) {
            case 'tournaments':
              for ($i = 1; $i <= $cart_item['quantity']; ++$i) {
                $extra_fields[] = array(
                  'type'  => $product_cat_slug,
                  'title' => $product_name,
                  'type'  => 'text',
                  'placeholder'=> 'Team Name',
                  'label' => 'Team Name ' ,
                  'name'  => 'team_name_' ,
                  'required'=> true,
                  'class' => array($product_cat_slug)
                );
                $extra_fields[] = array(
                  'type'  => $product_cat_slug,
                  'title' => $product_name,
                  'type'  => 'select',
                  'placeholder'=> 'Select Division',
                  'label' => 'Division' ,
                  'name'  => 'division_' ,
                  'required'=> true,
                  'class' => array($product_cat_slug, 'medium-margin-bottom'),
                  'options' => array(
                    '' => 'Select Division',
                    '10' => '10U/4th Grade',
                    '11' => '11U/5th Grade',
                    '12' => '12U/6th Grade',
                    '13' => '13U/7th Grade',
                    '14' => '14U/8th Grade'
                  )
                );
              }
              
          }
        }
        continue;
//       }
      //add type to types array;
      $g365_cart_types[] = $form_type_opt[$product_cat_slug];
      //start the category if there isn't one.
      if( empty($g365_cart_items[ $product_cat_id ]) ) $g365_cart_items[ $product_cat_id ] = array( 'name' => $product_cat_name, 'title' => ($product_cats[0]->description), 'type' => ($form_type_opt[$product_cat_slug]), 'target' => ($form_type_target[$product_cat_slug]), 'items' => array() );
      //start the product if there isn't one.
      if( empty($g365_cart_items[ $product_cat_id ]['items'][ $product_id ]) ) $g365_cart_items[ $product_cat_id ]['items'][ $product_id ] = array( 'name' => $product_name, 'vars' => array() );
      //write some data to build the form with js
      $g365_cart_items[ $product_cat_id ][ 'items' ][ $product_id ][ 'vars' ][ $product_var_id ] = array(
        'id' => $product_id,
        'name' => ((empty($product_event_divisions_pull->name)) ? $product_name : $product_event_divisions_pull->name),
        'full_name' => ((empty($product_event_divisions_pull->short_name)) ? stripslashes($_product->get_name()) : $product_event_divisions_pull->short_name),
        'var_id' => $product_var_id,
        'var_name' => $product_var_name,
        'sku' => $_product->get_sku(),
        'qty' => $cart_item['quantity'],
//         'event_divisions' => ($product_event_divisions === null) ? 0 : $product_event_divisions
      );
      $g365_cart_items[ $product_cat_id ][ 'items' ][ $product_id ][ 'vars' ][ $product_var_id ][$form_type_target[$product_cat_slug]] = $product_event_link;
    }
//     $extra_fields[] = array(
//       'label'   => 'Where did you find us?',
//       'name'    => 'findus',
//       'type'    => 'select',
// //       'required'=> false,
//       'class'   => array('input', 'medium-margin-bottom'),
//       'options' => array(
//         'default'   => 'Please select',
//         'friend'    => 'A friend',
//         'google'    => 'Google',
//         'o_website' => 'Our Website',
//         'grpon'     => 'Groupon',
//         'lnkdin'    => 'LinkedIn',
//         'instag'    => 'Instagram',
//         'faceb'     => 'Facebook',
//         'twitt'     => 'Twitter',
//         'indeed'    => 'Indeed',
//         'walk_in'   => 'Walk-In'
//       )
//     );
    $g365_cart_types = array_unique( $g365_cart_types );
    return array($g365_cart_items, $g365_cart_types, $extra_fields);
  }
}

//global to use processed cart data
$cart_parse = array();

//start the process at the top of the cart load
add_filter( 'woocommerce_before_cart' , 'shb_cart_setup');
function shb_cart_setup() {
  //load in global proc var
  global $cart_parse;
  //process cart items
  $cart_parse = shb_cart_process();    
  //if we don't have items that need g365 data managment, and we need data collected, add it
  if(!empty($cart_parse[2]) ){
    //pull the array into a var for the function closure
    $order_data_field = $cart_parse[2];
    //add the field with the parameters from the cart process
    add_filter( 'woocommerce_after_order_notes' , function( $fields ) use ( $order_data_field ) { return shb_player_checkout_field( $field, $order_data_field ); });
  }
}

//make the admin credential string
function g365_make_admin_key() {
  $grassroots_keys = get_option( 'shb_g365_connector' );
  $current_user = wp_get_current_user();
  $email = ( empty($current_user->user_email) ) ? '' : ':::' . $current_user->user_email ;
  $admin_key = '';
  if( !empty($grassroots_keys['connector_data']['trans_key'])  && !empty($grassroots_keys['connector_data']['trans_id']) ) $admin_key = 'Basic ' . base64_encode( $grassroots_keys['connector_data']['trans_key'] .  $grassroots_keys['connector_data']['trans_id'] . ',' . site_url() . ':::' . $current_user->ID . $email);
  return $admin_key;
}

//add support for custom order data
add_filter('woocommerce_form_field_order_data', 'g365_order_data_form_field', 999, 4);
function g365_order_data_form_field($no_parameter, $key, $args, $value) {
  $current_user = wp_get_current_user();
  $admin_key = g365_make_admin_key();
  $presets = [];
  foreach( $args['g365_cart_types'] as $dex => $type ){
    switch( $type ){
      case 'camps':
      case 'league':
      case 'club_team':
        $current_user = wp_get_current_user();
        $presets[] = $type . '_preset,user_ac:' . ((strpos(site_url(), 'dev') === false) ? 'shb' : 'OGD') . '-' . $current_user->ID;
        break;
    }
  }
  if($presets) $presets = 'data-g365_init_pre="' . implode('|', $presets) . '"';
  //return string
  $field = '<div class="grid-x grid-margin-x small-margin-bottom" id="event_details"><div class="cell small-12 medium-8 large-6"><header class="entry-header"><h2 class="entry-title">' . __('Registration') . '</h2></header><div id="g365_registration_fields">';
//   $field .= '<script type="text/javascript">var g365_form_details = {"items" : ' . json_encode($args['g365_cart_items']) . ', "wrapper_target" : "g365_form_options_anchor", "user_org": "' . get_user_meta($current_user->ID, '_default_org', true) . '", "admin_key": "' . $admin_key . '"};</script><div><div id="g365_form_options_anchor" data-g365_type="' . implode('|', $args['g365_cart_types']) . '"' . $presets . '></div></div>';
  $field .= '</div></div></div>';
  return $field;
  //
  // end update functionality
  //
}

//to collect data for each cart based on items purchased
add_action( 'g365_collect_data_fields', 'g365_cart_order_fields' );
function g365_cart_order_fields($checkout) {
  //load in global proc var
  global $cart_parse;
  //if we have cart items that we are manageing with g365 add the fields for data collection
  if( !empty($cart_parse[0]) ) {
    //add field with woocommerce handler
    woocommerce_form_field( 'order_data', array(
      'type'         => 'order_data',
      'class'        => array(),
      'required'     => true,
      'g365_cart_items' => $cart_parse[0],
      'g365_cart_types' => $cart_parse[1]
    ), '' );
  }
}

//field function for custom data collection unmanaged by g365
function shb_player_checkout_field( $checkout, $extra_fields = null ) {
  if( $extra_fields === null ) return;
  //create a custom field that can be referenced to get the names of all the custom fields
  $added_field_names = array();
  //make a field for each product that needs it
  foreach( $extra_fields as $dex => $field_arr ) {
    echo '<div class="item">';
    //create a name unique to the order for each field
    $field_name = $field_arr['type'] . '_' . $field_arr['name'] . '_' . $dex;
    $added_field_names[] = $field_name;
    //use woocommerce to add the fields
    //if statements print out a title for the fields based on the name of the slug. Just insert into the array when making the fields.
    
//     echo $dex;
     if($dex === 0){
        echo "<h2>Team Info</h2>";
     }
    if($dex === 9){
        echo "<h2>Parent Info</h2>";
     }
    
    $field_arr_process = array(
      'type'  => $field_arr['type'],
      'class' => $field_arr['class'],
      'label' => $field_arr['label'],
      'required' => $field_arr['required'],
      'placeholder' => $field_arr['placeholder'],
    );
    if( ($field_arr['type'] === 'select' || $field_arr['type'] === 'radio' || $field_arr['type'] === 'multiselect') && !empty($field_arr['options']) ) $field_arr_process['options'] = $field_arr['options'];
    woocommerce_form_field( $field_name, $field_arr_process, '');
    echo '</div>';
  }
  //don't forget to add the field names reference otherwise we can't process this data at all.
  echo '<input type="hidden" name="shb_extra_data" value="' . implode(',',$added_field_names) . '" />';
}

//make the extra data required based on the field names variable
add_action('woocommerce_checkout_process', 'shb_player_checkout_field_process');
function shb_player_checkout_field_process() {
  // Check if we have extra data
  if ( $_POST['shb_extra_data'] ) {
    //parse and loop through the set to make sure we have all the fields filled out
    $fields = explode(',', $_POST['shb_extra_data']);
    foreach( $fields as $dex => $key ) {
      if( ! $_POST[ $key ] ) wc_add_notice( __( 'This field  is required.' ), 'error' );
    }
  }
}

//if we have extra data, save it
add_action( 'woocommerce_checkout_update_order_meta', 'shb_player_checkout_field_update_order_meta' );
function shb_player_checkout_field_update_order_meta( $order_id ) {
  // Check if we have extra data
  if ( $_POST['shb_extra_data'] ) {
    //save the field reference so we know what to pull later
    update_post_meta( $order_id, 'shb_extra_data', sanitize_text_field( $_POST['shb_extra_data'] ) );
    //parse extra field names, then loop and save
    $fields = explode(',', $_POST['shb_extra_data']);
    foreach( $fields as $dex => $key ) {
      if( !empty($_POST[ $key ]) ) update_post_meta( $order_id, $key, sanitize_text_field( $_POST[ $key ] ) );
    }
  }
}

//display extra information customize
//display any$titlecount
add_action( 'woocommerce_admin_order_data_after_billing_address', 'shb_player_checkout_field_display_admin_order_meta', 10, 1 );
function shb_player_checkout_field_display_admin_order_meta($order){
  //parse the field names, if we have them, then echo them
  $fields = get_post_meta( $order->id, 'shb_extra_data', true );
  
  
  $fieldscounter = 0;
  $counterTitle = 0;
  $counterVariable = 0;
  $titleVar = array();
  $playerVar = array();
  
  if( !empty($fields) ) {
    $fields = explode(',', $fields);
    
    //get counter of how many fields inside the fields variable
    foreach( $fields as $dex => $key ) $fieldscounter++;
//     echo $fieldscounter;
    
     for( $titlecount = 0, $valuecount = 0; $titlecount < $fieldscounter; $valuecount < $fieldscounter ) {
      echo '<table class="widefat"><thead><tr>';
//       foreach( $fields as $dex => $key ) {
       for($i = 0; $titlecount < $fieldscounter && $i < 6; $titlecount++, $i++){
        $key_parts = explode('_', $fields[$titlecount]);
        unset($key_parts[0]);
        array_pop($key_parts);
//         $counterTitle++;
        echo '<th>' . ucwords( implode(' ', $key_parts) ) .'</th>';
        array_push($titleVar, ucwords( implode(' ', $key_parts) ));
      } 
       
        echo '</tr></thead><tbody><br>';
    
        echo '<tr>';
//         foreach( $fields as $dex => $key ) {
       for($j = 0; $valuecount < $fieldscounter && $j < 6; $valuecount++, $j++){
          //clean up the cariable name a little
          $key_parts = explode('_', $key);
          unset($key_parts[0]);
          array_pop($key_parts);
//           echo '<p class="test"><strong>' . ucwords( implode(' ', $key_parts) ) . ':</strong> ' . get_post_meta( $order->id, $key, true ) . '</p>';
          echo '<th>' . get_post_meta( $order->id, $fields[$valuecount], true ) . '</th>';
          array_push($playerVar, get_post_meta( $order->id, $fields[$valuecount], true ));
        }
        echo '</tr>';
        echo '</tbody></table>';
    }
    
  }
  
      echo '<br>';
      echo '<div style="display: none;">';
      echo '<form action="" method="post">';
        echo '<input type="submit" name="csvdownload" value="Download CSV" class="button black small-margin-bottom expanded">';
      echo '</form>';
      echo '</div>';
  
  
}


add_action( 'woocommerce_admin_order_data_after_billing_address_export_csv', 'shb_player_checkout_field_display_admin_order_meta_export_csv', 10, 1 );
function shb_player_checkout_field_display_admin_order_meta_export_csv($order){
  //parse the field names, if we have them, then echo them
  $fields = get_post_meta( $order->id, 'shb_extra_data', true );
  
  
  $fieldscounter = 0;
  $counterTitle = 0;
  $counterVariable = 0;
  $titleVar = array();
  $playerVar = array();
  
  if( !empty($fields) ) {
    $fields = explode(',', $fields);
    
    //get counter of how many fields inside the fields variable
    foreach( $fields as $dex => $key ) $fieldscounter++;
    
     for( $titlecount = 0, $valuecount = 0; $titlecount < $fieldscounter; $valuecount < $fieldscounter ) {
       for($i = 0; $titlecount < $fieldscounter && $i < 6; $titlecount++, $i++){
        $key_parts = explode('_', $fields[$titlecount]);
        unset($key_parts[0]);
        array_pop($key_parts);
        array_push($titleVar, ucwords( implode(' ', $key_parts) ));
      } 
       for($j = 0; $valuecount < $fieldscounter && $j < 6; $valuecount++, $j++){
          //clean up the cariable name a little
          $key_parts = explode('_', $key);
          unset($key_parts[0]);
          array_pop($key_parts);
          array_push($playerVar, get_post_meta( $order->id, $fields[$valuecount], true ));
        }
    }
  }
      echo '<br>';
      echo '<div>';
      echo '<form action="" method="post">';
        echo '<input type="submit" name="csvdownload" value="Download CSV" class="button black small-margin-bottom expanded">';
      echo '</form>';
      echo '</div>';
  
  
       if(array_key_exists('csvdownload', $_POST)) {
            downloadCSV($titleVar, $playerVar, $order->id);
        }
}

function downloadCSV($titleVar, $playerVar, $orderID){
//   echo 'console.log("yoyo");';
  
  
  //if you dont echo anything out then you get the proper output. I would say create a new function with the same things just no echos. Should give you the proper answer.
  $filename = 'product_' . $orderID . '.csv';
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename="'.$filename.'";');
  
  
  // Start the output buffer.
  $seperator = ",";
  $exporting = array();
  array_push($exporting, $titleVar);
  array_push($exporting, $playerVar);
  
  ob_end_clean();
  
  $fp = fopen( 'php://output', 'w');
//   print_r($exporting);
  
  foreach($exporting as $lists){
    fputcsv($fp, $lists, $seperator);
  }
  
  fclose($fp);
  exit;
  
  
}

//prevent users from canceling subscriptions, extend for per product control
function shb_edit_memberships_actions( $actions ) {
  // Get the current active user
  $user_id = wp_get_current_user();

  if(!$user_id) return $actions; // No valid user, abort

  // Only query active subscriptions
  $memberships_info = wc_memberships_get_user_active_memberships($user_id, array( 'status' => array( 'active' ) ));

  // Loop through each active subscription
  foreach ($memberships_info as $membership) {
    $subscription_start_date = date("Y/m/d", strtotime($membership->get_start_date()));
    //$subscription_end_date = date("Y/m/d", strtotime($membership->get_end_date()));
    //$subscription_name = $membership->get_plan()->get_name();
    //$subscription_id = $membership->get_plan()->get_id();

    if($subscription_id == 'YOUR_ID') { // Active subscription
      // Compare the starting date of the subscription with the current date
      $datetime1 = date_create($subscription_start_date);
      $datetime2 = date_create(date(time()));

      $interval = date_diff($datetime1, $datetime2);

      if($interval->format('%m') <= 11) {
        // remove the "Cancel" action for members
        unset( $actions['cancel'] );
      }
    }
  }
 return $actions;
}

add_filter( 'wc_memberships_members_area_my-memberships_actions', 'shb_edit_memberships_actions' );
add_filter( 'wc_memberships_members_area_my-membership-details_actions', 'shb_edit_memberships_actions' );


/**
 * Add series products functionality
 */
add_action( 'woocommerce_product_options_related', 'product_series_support', 10, 2 );
function product_series_support() { global $post; ?>
  <div class="options_group">
		<p class="form-field">
			<label for="series_ids"><?php esc_html_e( 'Series', 'woocommerce' ); ?></label>
			<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="series_ids" name="series_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
				<?php
        $product_ids = get_post_meta( $post->ID, '_series_ids', true );
        foreach ( $product_ids as $product_id ) {
          $product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
          }
				}
				?>
			</select> <?php echo wc_help_tip( __( 'A product series will group products together in a way that supports an event with multiple separate dates.', 'woocommerce' ) ); // WPCS: XSS ok. ?>
		</p>
	</div>
<?php }

add_action( 'woocommerce_process_product_meta', 'save_product_series' );
function save_product_series( $post_id ) {
  // grab the series array from $_POST
  $series_ids = isset( $_POST[ 'series_ids' ] ) ? array_map( 'intval', (array) wp_unslash( $_POST['series_ids'] ) ) : array();
	update_post_meta( $post_id, '_series_ids', ( !empty( $series_ids ) ) ? $series_ids : [] );
}

add_action( 'woocommerce_single_product_summary', 'add_series_options', 11 );
function add_series_options() {
  // get the series data if the is any
  global $post;
  $product_ids = get_post_meta( $post->ID, '_series_ids', true );
  if( !empty( $product_ids ) && is_array($product_ids) ) {
    $series_products = array();
    $series_event_ids = array();
    $series_events = array();
    $series_prices = array();
    $series_order = array();
    $series_name = array();
    $product_ids[] = $post->ID;
    $series_title = 'Related Events';
    $count = 0;
    //get all the variation data sowe can format based on the whole option set
    foreach ( $product_ids as $product_id ) {
      //get the product
      $series_products[ $product_id ] = wc_get_product( $product_id );
      //get event_link
      $series_event_ids[ $product_id ] = $series_products[ $product_id ]->get_meta( '_event_link' );
      //if we have an id, try to get event data
      if( !empty($series_event_ids[ $product_id ]) ) {
        $series_events[ $product_id ] = array( 'data_pull' => get_post_meta( $product_id, '_event_link_data', true ), 'label' => array() );
      }
    }
    //same prices
    $price_options = ((count(array_unique($series_prices)) !== 1 ) ? true : false);
    //see what data we have so we can format the dropdown options
    $payment_options = ((count(array_unique($series_event_ids)) === 1 && count($series_event_ids) > 1) ? true : false);
    //go through all the related products
    foreach ( $product_ids as $product_id ) {
      //see if we have any data_pull var to build with
      if( !empty($series_events[ $product_id ][ 'data_pull' ]) ) {
        //if we don't have a date, order by post publish date
        if( empty($series_events[ $product_id ][ 'data_pull' ]->dates) ) {
          //add to the series order so we can be organized
          $series_order[ $product_id ] = get_the_date( 'Y-m-d', $product_id );
          //if it's a payment option
          if( $payment_options ) {
            //change title
            $series_title = 'Payment Options';
            $series_events[ $product_id ][ 'label' ][] = (class_exists( 'WC_Subscriptions_Product' ) && WC_Subscriptions_Product::is_subscription( $series_products[ $product_id ] )) ? 'Reoccurring' : 'Once';
          } else {
            //remove title
            $series_title = '';
            $series_events[ $product_id ][ 'label' ][] = ($series_events[ $product_id ][ 'data_pull' ]->short_name) ? $series_events[ $product_id ][ 'data_pull' ]->short_name : $series_events[ $product_id ][ 'data_pull' ]->name;
          }
          //add the name and price
          $series_events[ $product_id ][ 'label' ][] = $series_prices[ $product_id ];
        } else {
          //add to the series order so we can be organized
          $series_order[ $product_id ] = date( 'Y-m-d', strtotime(shb_build_dates($series_events[ $product_id ][ 'data_pull' ]->dates, 4)));
          //if we are payment option or we a date set
          if( $payment_options ) {
            //change title
            $series_title = 'Payment Options';
            $series_events[ $product_id ][ 'label' ][] = $series_prices[ $product_id ];
          } else {
            $series_title = 'Available Dates';
            $series_events[ $product_id ][ 'label' ][] = shb_build_dates($series_events[ $product_id ][ 'data_pull' ]->dates, 1, true);
            if( $price_options ) $series_events[ $product_id ][ 'label' ][] = $series_prices[ $product_id ];
          }
          if( !empty($series_events[ $product_id ][ 'data_pull' ]->locations) ) {
            $series_title = ( $series_title === 'Available Dates' ) ? $series_title . '/Locations' : $series_title;
            $series_events[ $product_id ][ 'label' ][] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $series_events[ $product_id ][ 'data_pull' ]->locations)));
          }
        }
        $series_events[ $product_id ][ 'label' ] = implode(' | ', $series_events[ $product_id ][ 'label' ]);
        asort( $series_order );
      } else {
        //change title
        $series_title = 'Payment Options';
        $series_events[ $product_id ][ 'label' ][] = $series_products[ $product_id ]->get_name();
//         $series_events[ $product_id ][ 'label' ][] = str_replace( '.00', '', $series_products[ $product_id ]->get_price_html() );
//         $series_events[ $product_id ][ 'label' ][] = (class_exists( 'WC_Subscriptions_Product' ) && WC_Subscriptions_Product::is_subscription( $series_products[ $product_id ] )) ? 'Reoccurring' : 'Once';
        //add the name and price
        $series_events[ $product_id ][ 'label' ] = implode(' | ', $series_events[ $product_id ][ 'label' ]);
        $series_order[ $product_id ] = $product_id;
      }
    }
    echo '<div class="cell small-12">';
    if( !empty($series_title) ) {
      if( $series_title === 'Payment Options' ) {
        echo '<div class="input-group max-button small-margin-bottom"><label class="input-group-label">' . $series_title . '</label>';
      } else {
        echo '<h5>' . $series_title . '</h5>';
      }
    }
    echo '<select id="series_selector"' . (( $series_title === 'Payment Options' ) ? ' class="input-group-button"' : '') . '>';
    foreach ( $series_order as $product_id ) {
      echo '<option value="' . esc_attr( $series_products[ $product_id ]->get_permalink() ) . '"' . selected( $product_id, $post->ID, false ) . '>' . wp_kses_post( $series_events[ $product_id ][ 'label' ] ) . '</option>';
    }
    echo '</select>';
    if( $series_title === 'Payment Options' ) '</div>';
    echo '</div>';
  }
}

/**
 * Add Addiive Upsell products functionality
 */
add_action( 'woocommerce_product_options_related', 'product_add_upsell_support', 10, 2 );
function product_add_upsell_support() { global $post; ?>
  <div class="options_group">
		<p class="form-field">
			<label for="series_ids"><?php esc_html_e( 'Additive Upsells', 'woocommerce' ); ?></label>
			<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="add_upsell_ids" name="add_upsell_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
				<?php
        $product_ids = get_post_meta( $post->ID, '_add_upsell_ids', true );
        foreach ( $product_ids as $product_id ) {
          $product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
          }
				}
				?>
			</select> <?php echo wc_help_tip( __( 'A product additive upsell will appear about the "buy button" as an additive checkbox. The checked product will be added when the current product is added to the cart.', 'woocommerce' ) ); // WPCS: XSS ok. ?>
		</p>
	</div>
<?php }

add_action( 'woocommerce_process_product_meta', 'save_product_add_upsell' );
function save_product_add_upsell( $post_id ) {
  // grab the upsell array from $_POST
  $add_upsell_ids = isset( $_POST[ 'add_upsell_ids' ] ) ? array_map( 'intval', (array) wp_unslash( $_POST['add_upsell_ids'] ) ) : array();
	update_post_meta( $post_id, '_add_upsell_ids', ( !empty( $add_upsell_ids ) ) ? $add_upsell_ids : [] );
}

add_action( 'woocommerce_before_add_to_cart_button', 'add_add_upsell_options', 12 );
function add_add_upsell_options() {
  // get the upsell data if the is any
  global $post;
  $product_ids = get_post_meta( $post->ID, '_add_upsell_ids', true );
  if( !empty( $product_ids ) && is_array($product_ids) ) {
    foreach ( $product_ids as $product_id ) {
      $add_upsell_product = wc_get_product( $product_id );
      $add_upsell_description = $add_upsell_product->get_description();
      if( empty($add_upsell_description) ) $add_upsell_description = $add_upsell_product->get_description();
      echo '<div class="cell small-12"><div class="tiny-padding add_upsell_row">';
      if( !empty($add_upsell_description) ) echo '<a class="float-right" data-toggle="panel_'. $add_upsell_product->get_id() . '">details</a>';
      echo '<label class="add_upsell_product" for="check_'. $add_upsell_product->get_id() . '"><input type="checkbox" name="additional-add-to-cart[]" id="check_'. $add_upsell_product->get_id() . '" value="'. $add_upsell_product->get_id() . '"> ' . $add_upsell_product->get_title() . ': ' . $add_upsell_product->get_price() . '</label>';
      if( !empty($add_upsell_description) ) echo '<div class="callout" id="panel_'. $add_upsell_product->get_id() . '" data-toggler data-animate="hinge-in-from-top hinge-out-from-top" style="display:none;">' . apply_filters('the_content', $add_upsell_description) . '</div>';
      echo '</div></div>';
    }
    echo '<hr class="cell small-12">';
  }
}

//remove additional notes section of checkout
add_filter( 'woocommerce_checkout_fields' , 'shb_modify_checkout_fields' );
// Our hooked in function - $fields is passed via the filter!
function shb_modify_checkout_fields( $fields ) {
   unset($fields['order']['order_comments']);
   return $fields;
}

//validate the checkout form before finishing the process of writting data to shb about the purchase
add_action('woocommerce_after_checkout_validation', 'process_shb_data');
function process_shb_data($form_data) {
  //if we have a order_data field and it's not filled, send it back to get that processed out.
  if( isset($_POST['order_data']) && $_POST['order_data'] === 'null' && wc_notice_count( 'error' ) == 0 )  wc_add_notice( '<strong>' . __( 'G365 Data' ) . '</strong> needs to be processed or completed.', 'error');
}

//update cart data and user info
add_action( 'woocommerce_checkout_update_order_meta', 'shb_update_meta_form_fields' );
function shb_update_meta_form_fields( $order_id ) {
  if ( ! empty( $_POST['order_data'] ) ) update_post_meta( $order_id, '_order_data', $_POST['order_data'] );
}

//display and custom order data for admin
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'ebc_checkout_field_display_admin_order_meta', 10, 1 );
function ebc_checkout_field_display_admin_order_meta($order){
  $order_data_meta = get_post_meta( $order->get_id(), '_order_data', true );
  $message = '<p>No additional order data to retrieve.</p>';
  if( !empty($order_data_meta) ) {
    $message = '';
    $order_data_meta = explode( '|', $order_data_meta );
    $order_data_meta_proc = array();
    foreach( $order_data_meta as $dex => $data ) {
      $data = explode( ',', $data );
      $type = array_shift($data);
      $message .= '<h3>' . ucfirst($type) . '</h3>';
      if( count($data) > 0 ) {
        $order_data_meta_proc[$type] = array('ids' => $data);
        $order_data_meta_proc[$type]['data'] = g365_conn( 'g365_get_stats', ['null', 'null', '0-1', 'stats.updatetime DESC', $data] );
        $column_order = array(
          'name' => 'Player',
          'age' => 'Age',
          'birthday' => 'Birthdate',
          'grade' => 'Grade',
          'grad_year' => 'Grad Class',
          'jersey_size' => 'Jersey Size'
        );
        $message .= '<table class="widefat"><thead><tr>';
        foreach( $column_order as $val ) $message .= '<th>' . $val . '</th>';
        $message .= '</tr></thead><tbody>';
        foreach( $order_data_meta_proc[$type]['data'] as $stat_id => $stat_data ) {
          $message .= '<tr>';
          foreach( $column_order as $key => $title ) {
            switch($key) {
              case 'age':
                $val = ((!empty($stat_data->birthday)) ? date_diff(date_create($stat_data->birthday), date_create(date("Y-m-d")))->format('%y') : '--');
                break;
              case 'grade':
                $val = ((!empty($stat_data->grad_year)) ? g365_class_to_grade($stat_data->grad_year) : '--');
                break;
              default:
                $val = ((!empty($stat_data->{$key})) ? $stat_data->{$key} : '--');
            }
            $message .= '<th>' . $val . '</th>';
          }
          $message .= '</tr>';
        }
        $message .= '</tbody></table>';
      } else {
        $message .= '<p>No additional ' . $type . ' order ids.</p>';
        continue;
      }
    }
  }
  echo '</div><div class="clear"></div><div>';
  echo '<div>';
  echo '<h3>Additional Order Information</h3>';
  echo $message;
  echo '</div>';
}

//product grid
function g365_template_loop_product_title() {
  echo '<h3 class="loop-title">' . get_the_title() . '</h3>';
}
function g365_template_loop_add_to_cart() {
  global $product;

  // Enqueue variation scripts.
  wp_enqueue_script( 'wc-add-to-cart-variation' );

  // Get Available variations?
  $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

  // Load the template.
  wc_get_template(
      'single-product/add-to-cart/variable-buy-button.php',
      array(
          'available_variations' => $get_variations ? $product->get_available_variations() : false,
          'attributes'           => $product->get_variation_attributes(),
          'selected_attributes'  => $product->get_default_attributes(),
      )
  );
}
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

// add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_shop_loop_item_title', 'g365_template_loop_product_title', 30 );
add_action( 'woocommerce_after_shop_loop_item', 'g365_template_loop_add_to_cart', 30 );

	


//add the stock to the variation dropdown menu, by overiding the builtin
//possible multi-dimensional variations with stock https://www.wpgarage.com/woocommerce/unlock-variation-combinations-woocommerce-dropdowns/
// Function that will check the stock status and display the corresponding additional text
function get_stock_status_text( $product, $name, $term_slug ){
  foreach ( $product->get_available_variations() as $variation ){
    if($variation['attributes'][$name] == $term_slug ) {
      $in_stock = ( $variation['is_in_stock'] == 1 ) ? '' : ' - SOLD OUT';
      $in_stock = ( $in_stock === '' ) ? ( ( is_integer($variation['max_qty']) ) ? ' - ' . $variation['max_qty'] . ' spots left' : '' ) : $in_stock;
      break;
    }
  }
  return $in_stock;
}

// The hooked function that will add the stock status to the dropdown options elements.
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'show_stock_status_in_dropdown', 10, 2);
function show_stock_status_in_dropdown( $html, $args ) {
    // Only if there is a unique variation attribute (one dropdown)
    if( sizeof($args['product']->get_variation_attributes()) == 1 ) :

    $options               = $args['options'];
    $product               = $args['product'];
    $attribute             = $args['attribute']; // The product attribute taxonomy
    $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
    $id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
    $class                 = $args['class'];
    $show_option_none      = $args['show_option_none'] ? true : false;
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' );

    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
        $attributes = $product->get_variation_attributes();
        $options    = $attributes[ $attribute ];
    }

    $html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
    $html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

    if ( ! empty( $options ) ) {
        if ( $product && taxonomy_exists( $attribute ) ) {
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

            foreach ( $terms as $term ) {
                if ( in_array( $term->slug, $options ) ) {
                    // HERE Added the function to get the text status
                    $stock_status = get_stock_status_text( $product, $name, $term->slug );
                    $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) . $stock_status ) . '</option>';
                }
            }
        } else {
            foreach ( $options as $option ) {
                $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                // HERE Added the function to get the text status
                $stock_status = get_stock_status_text( $product, $name, $option );
                $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) . $stock_status ) . '</option>';
            }
        }
    }
    $html .= '</select>';

    endif;

    return $html;
}

//A PICK UP FROM: https://www.cssigniter.com/how-to-add-a-custom-user-field-in-wordpress/
add_action( 'show_user_profile', 'shb_user_fields_display' );
add_action( 'edit_user_profile', 'shb_user_fields_display' );

function shb_user_fields_display( $user ) {
	wp_enqueue_style( 'foundation-admin', site_url( '/wp-content/themes/ugc-press/css/', 'https' ) . "style-admin.css?ver=2.345" );
	wp_enqueue_script( 'admin-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), '2.2.4', true );
  wp_enqueue_script( 'js-g365-all', (( strpos(site_url(), 'dev.') === false ) ? 'https://grassroots365.com/data-processor.js' : 'https://dev.grassroots365.com/data-processor.js'), array('admin-jquery'), '69550', true );
  ?>
	<h3><?php esc_html_e( 'Additional User Data', 'shb' ); ?></h3>
  <div class="options_group g365_manage_wrapper">
    <table class="form-table">
      <tbody>
        <tr>
          <th>
            <label for="g365_default_org">Default Club or Organization</label>
          </th>
          <td>
            <p>
              <?php
              $org_id = get_the_author_meta( '_default_org', $user->ID );
              if( !empty($org_id) ) $org_name_object = g365_conn( 'g365_get_org_names', [$org_id] ); 
              ?>
              <input type="hidden" id="g365_user_org" name="g365_user_org" value="<?php echo ( empty($org_id) ) ? '' : $org_id; ?>" />
              <input type="text" class="g365_livesearch_input short no-margin-bottom" id="g365_default_org" value="<?php echo ( empty($org_name_object) ) ? '' : $org_name_object[0]->name; ?>" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="g365_user_org" data-g365_type="orgs" placeholder="Organization Name" autocomplete="off">
            </p>
          </td>
        </tr>
        <tr>
          <th>
            <label for="g365_ownership">Owned Data</label>
          </th>
          <td>
            <?php
            $owned_string = json_encode(get_user_meta( $user->ID, '_user_owns_g365', true ));
            ?>
            <input type="text" id="g365_ownership" name="user_owns_g365" value="<?php if( !empty($owned_string) ) echo htmlspecialchars($owned_string); ?>">
            <p class="description"><?php echo $owned_string; ?></p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
	<?php
}

//FORM VALIDATION AND REQUIREMENT
add_action( 'user_profile_update_errors', 'shb_user_field_error', 10, 3 );
function shb_user_field_error( $errors, $update, $user ) {
	if ( ! $update ) {
		return;
	}
}

add_action( 'personal_options_update', 'shb_user_fields_update' );
add_action( 'edit_user_profile_update', 'shb_user_fields_update' );

function shb_user_fields_update( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
  if( isset($_POST['g365_user_org']) && (intval($_POST['g365_user_org']) || $_POST['g365_user_org'] === '') ) update_user_meta( $user_id, '_default_org', intval($_POST['g365_user_org']) );
  if( isset($_POST['user_owns_g365']) ) update_user_meta( $user_id, '_user_owns_g365', (( empty($_POST['user_owns_g365']) ) ? array() :  (array) json_decode(stripslashes($_POST['user_owns_g365'])) ) );
}


//add admins to get certain product notifications
function per_product_new_order_email_recipient( $recipient, $order ) {
	// Exit on WC settings pages since the order object isn't yet set yet or if the $order var is not right
	$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
	if ( 'wc-settings' === $page || !$order instanceof WC_Order ) return $recipient; 

  $items = $order->get_items();
	
  $notice_recipients = array(
    'CPS-001' => 'jamie.stopnitzky@opengympremier.com',
    'gear' => 'maddi.lawson@opengympremier.com'
  );
  $notice_recipients_keys = array_keys($notice_recipients);
  
  $add_recipients = [];
	// check if any product needs to get sent to specific people
	foreach( $items as $key => $item ) {
		$product = $order->get_product_from_item( $item );
    if( $product ) {
      //reference the sku
      $sku = $product->get_sku();
      //extricate the categories
      $product_cats = get_the_terms( $key, 'product_cat' );
      $product_cats_by_slug = array();
      if( !empty($product_cats) ) foreach( $product_cats as $term ) $product_cats_by_slug[] = $term->slug;
      // add recipient from object for the product
      if( in_array($sku, $notice_recipients_keys) ) $add_recipients[] = $notice_recipients[$sku];
      // add recipient from object for the categories
      foreach( $product_cats_by_slug as $slug ) if( in_array($slug, $notice_recipients_keys) ) $add_recipients[] = $notice_recipients[$slug];
      //add functionality to make the list unique
    }
	}
  if( !empty($add_recipients) ) $recipient .= ', ' . implode( ', ', $add_recipients);
	return $recipient;
}

add_filter('woocommerce_email_recipient_new_order', 'per_product_new_order_email_recipient', 1, 2);


//remove subscription cancel
function eg_remove_my_subscriptions_button( $actions, $subscription ) {

	foreach ( $actions as $action_key => $action ) {
		switch ( $action_key ) {
// 			case 'change_payment_method':	// Hide "Change Payment Method" button?
//			case 'change_address':		// Hide "Change Address" button?
//			case 'switch':			// Hide "Switch Subscription" button?
//			case 'resubscribe':		// Hide "Resubscribe" button from an expired or cancelled subscription?
//			case 'pay':			// Hide "Pay" button on subscriptions that are "on-hold" as they require payment?
//			case 'reactivate':		// Hide "Reactive" button on subscriptions that are "on-hold"?
			case 'cancel':			// Hide "Cancel" button on subscriptions that are "active" or "on-hold"?
				unset( $actions[ $action_key ] );
        echo 'hel';
				break;
			default: 
				error_log( '-- $action = ' . print_r( $action, true ) );
				break;
		}
	}

	return $actions;
}
add_filter( 'wcs_view_subscription_actions', 'eg_remove_my_subscriptions_button', 100, 2 );

// cart checkout expiration 2hrs
add_filter( 'wc_session_expiration', 'woocommerce_cart_session_expires'); 
function woocommerce_cart_session_expires() {
  return 60 * 60 * 2; 
}

?>