// Admission product calculation
//for adding 1 to input box in input-groups
  $('.button.add-button', '.input-group').click(function() { //alert('clicked');
    var target = $(this).prev( '.input-number' );
    var new_val = parseInt(target.val()) + 1;
    new_val = ( new_val > parseInt(target.attr('max')) ) ? target.attr('max') : new_val; 
    target.val( new_val ).change();
  });
  //for subtracting 1 to input box in input-groups
  $('.button.minus-button', '.input-group').click(function() {
    var target = $(this).next( '.input-number' );
    var new_val = parseInt(target.val()) - 1;
    new_val = ( new_val < parseInt(target.attr('min')) ) ? target.attr('min') : new_val; 
    target.val( new_val ).change();
  });
  //for creating sub totals in input boxes with input-groups
  $('.input-quantity', '.input-group').change(function() {
    var this_quantity = $(this);
    this_quantity.siblings( '.target-total' ).children('span').html( this_quantity.siblings( '.target-number' ).attr('data-target_number') * this_quantity.val() ).change();
  });
  //add all sub totals together
  var all_sub_totals = $('.calc-sub-total', '.input-group');
  all_sub_totals.change(function() {
    var total_price = 0;
    all_sub_totals.each(function(){ total_price += parseInt($(this).html()); });
    $( '#calc-total' ).html( total_price );
  });
  //change the variation prices based on the dropdown. 
$( '.variations select' ).change(function(){
  var selector = $(this);
  var selector_parent = selector.closest('.product');
  var target_price = $( '.main-price', selector_parent );
  if( typeof target_price.data( 'var_prices_html' ) === 'undefined' ) {
    var var_prices_html = [];
    if( $( ':first-child', selector ).val() === '' ) var_prices_html[0] = target_price.html();
    var var_attr_full = JSON.parse($('.variations_form', selector_parent).attr('data-product_variations'));
    $.each(var_attr_full, function(attr_dex, attr_vals){
      var_prices_html[attr_dex+1] = attr_vals.price_html.replace('.00', '');
    });
    target_price.data( 'var_prices_html', var_prices_html );
  }
  var target_price_options = target_price.data( 'var_prices_html');
  target_price.html( target_price_options[$( 'option:selected', selector ).index()] );
}).change();

//add multiple variations to cart with quantities
$('.summary .variations_collector', '#content').click(function() {
  var var_parent = $('.variations', '.summary');
  var url_string = [];
  $('input.variable-input', var_parent).each(function(){
    var input_target = $(this);
    if( input_target.val() > 0 ) url_string[url_string.length] = input_target.attr('data-var_id') + ',' + input_target.val();
  });
  if( url_string.length > 0 ) window.location = '/cart/?add-more-to-cart=' + url_string.join('|');
});
// End admission product calculation