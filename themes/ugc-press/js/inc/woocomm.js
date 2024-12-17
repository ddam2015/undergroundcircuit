// $( 'form.variations_form' ).on( 'show_variation', 
// 	function( event, variation ){
// 		console.log( event, variation );
// 	} 
// );

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


//buy button listener
// var cartButton = document.querySelector('.variations_button');

// var observer = new MutationObserver(
//   function(mutations) {
//     priceChangeCallback(mutations);
//   }
// );

// var config = {
//   attributes: true
// };

// observer.observe(cartButton, config);

// function priceChangeCallback(mutations) {
//   if (!document.querySelector('.wc-variation-selection-needed')) {
//     document.querySelector('.price-range').classList.remove('hidden');
//   } else {
//     document.querySelector('.price-range').classList.add('hidden');
//   }
// }

$(function() {
    console.log('doc ready');
    if(document.querySelector('.hbspt-form')) {
        console.log('hubspot form found');
        var orderReviewHeading =  $('#order_review_heading');
        var orderReview = $('#order_review');
 
        window.addEventListener('message', function(event) {
            if(event.data.type === 'hsFormCallback' && event.data.eventName === 'onFormSubmitted') {
                orderReviewHeading.slideDown(300);
                orderReview.slideDown(300);
            }
         });
    }
});
