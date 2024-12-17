//connect the cart to the g365 form
$( document.body ).on( 'updated_cart_totals', function(){
  //get all the qty inputs
  var form_qty_elements = $('div.woocommerce .qty');
  //loop through the entire global json tree
  $.each(g365_form_details.items, function(cat_id, cat_vals){
    $.each(cat_vals.items, function(prod_id, prod_vals){
      $.each(prod_vals.vars, function(var_id, var_vals){
        //defaults to eliminate, unless the global is claimed by a qty
        var eliminate = true;
        //loop through all the qty inputs and cross check with the global json
        form_qty_elements.each(function(){
          //pull all the data we need to perform the check
          var data_element = $(this);
          var product_cat = data_element.attr('data-prod-cat');
          var product_id = data_element.attr('data-prod-id');
          var product_var_id = data_element.attr('data-prod-var-id');
          //exit if any of our variables are weird or don't match
          if(
            isNaN(parseInt(product_cat)) || product_cat === '' ||
            isNaN(parseInt(product_id)) || product_id === '' ||
            isNaN(parseInt(product_var_id)) || product_var_id === '' ||
            product_cat != cat_id || product_id != prod_id || (product_var_id != var_id && product_var_id !== 0)
          ) return;
          //if we have a match then prevent elimination
          eliminate = false;
          //update the quantity of the global json
          g365_form_details.items[cat_id].items[prod_id].vars[var_id].qty = data_element.val();
        }); //end qty elements
        if( eliminate ) delete g365_form_details.items[cat_id].items[prod_id].vars[var_id];
      }); //end vars
      if( $.isEmptyObject(g365_form_details.items[cat_id].items[prod_id].vars) ) delete g365_form_details.items[cat_id].items[prod_id];
    }); //end prod
    //if we don't have any more items in the category, erase the global holder, otherwise run the g365 form manager
    if( $.isEmptyObject(g365_form_details.items[cat_id].items) ) {
      delete g365_form_details.items[cat_id];
    } else {
      g365_manage_cart_form( $('#' + g365_form_details.items[cat_id].target), g365_form_details.items[cat_id].type, g365_form_details.items[cat_id].items );
    }
  }); //end cat
});

//add cart updater for the quantity change
$('div.woocommerce').on( 'change', '.qty', function(){ $( '#update_cart' ).trigger('click'); });

var woo_comm_form = $('#woocommerce-checkout-form');
//activate the input to support data  if we have the g365_form_details object
if( typeof g365_form_details !== 'undefined' && Object.keys(g365_form_details.items).length > 0 ) {
  woo_comm_form.prepend('<input type="hidden" id="order_data" name="order_data" value="null" />');
}


// //make sure that we check for g365 data before we try to submit the first woo checkout server validation
// woo_comm_form.on( 'checkout_place_order', function() {
//   // return true to continue the submission or false to prevent it
//   var error_present = false;
//   $('.form-init', '#g365_form_wrap').each(function(){
//     $('.form_loader', target_container).on('click', function(e){ e.preventDefault(); g365_build_form_from_data( $(this) ); });

//     if( g365_check_validation($(this)) === true ) error_present = true;
//   });
//   return error_present;
// });


//pause the form submit while we try and get the g365 data squared away
$(document.body).on( 'checkout_error', function() {
  $( 'html, body' ).stop();
  console.log('arrete');
  // return true to continue the submission or false to prevent it
  
  
  // get error messages
  var error_item = $('.woocommerce-error').find('li').first();
  console.log(error_item);
  if ( error_item.text().trim() == 'shb Data needs to be processed or completed.' ) {
    //form passed and we now want to process the g365 data
    //also hide the notice and stop any scrolling
    $.scroll_to_notices( $( '#place_order' ) );
//     $( 'html, body' ).stop();
    $('.woocommerce-error').remove();
    
    //try to submit the custom g356 form
    var g365_forms = $('#g365_form_wrap .primary-form');
    //set the send status to false;
    woo_comm_form.data('form_fired', false);
    
    g365_forms.each(function(){
      //reference to the form element
      var current_set = $(this);
      //set the listener for the submission completion
      $('#' + current_set.attr('id') + '_message', current_set).on( 'result_complete', function(e, result_info) {
        //if there are no errors, add the result data and finish the form
        if( result_info.error_status === false ){
          //add it to the order_data object and try to submit the whole form again
          woo_comm_form.data(current_set.attr('data-g365_type') + '_g365', result_info.result_ids.join(','));
          //if we have the right amount of fieldsets results, add the data to the order_data field and submit the form, but screen out the keys that aren't ours
          var woo_form_data = woo_comm_form.data();
          var woo_form_data_keys = Object.keys(woo_comm_form.data()).filter(function(val){ return val.substr(val.length - 5) === '_g365'; });
          if( woo_form_data_keys.length === g365_forms.length && woo_form_data.form_fired === false ) {
            woo_comm_form.data('form_fired', true);
            var woo_order_data = [];
            $.each( woo_form_data_keys, function(dex, val){
              var val_name = val.substr(0, val.length-5);
              var exit_status = true;
              $.each( g365_form_details.items, function(cat_dex, cat_vals) {
                if( cat_vals.type == val_name ) {
                  inc_status = false;
                  return false;
                }
              });
              if( inc_status ) return;
              woo_order_data[woo_order_data.length] = val_name + ',' + woo_form_data[val];
            });
            $('#order_data').val(woo_order_data.join('|'));
            woo_comm_form.submit();
            return false;
          }
        }
        
      });
      current_set.submit();
    });
    return false;
  }
  console.log('no extra forms found, submitting...');
//   return false;
  return true;
});
