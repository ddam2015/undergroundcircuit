$(document).foundation();

//make images work on player profiles
var g365_profile_event_imgs = $('#event-imgs');
if (g365_profile_event_imgs.length > 0) {
  g365_profile_event_imgs.on('change.zf.tabs', function(ev, tar) {
    $('#event-stats .tabs-panel').attr('aria-hidden', true).removeClass('is-active');
    $(tar.children('a').attr('href') + '-data').attr('aria-hidden', false).addClass('is-active');
  });
}



//for the splash closer
var shb_reveal_closer_today_button = $('#reveal_close_today');
if (shb_reveal_closer_today_button.length > 0) {
  shb_reveal_closer_today_button.on('click', function() {
    localStorage.setItem("shb_close_today", 'true');
    localStorage.setItem("shb_close_today_date", new Date() );
  });
}

//home page news article rotator
var g365_news_rotator = $('#news_rotator');
if( g365_news_rotator.length > 0 ) {
	g365_news_rotator.slick({
		autoplay: true,
		autoplaySpeed: 2000,
		arrows: false,
		dots: false
	});
	var g365_news_nav = $('#news_nav');
	var g365_news_nav_div = $('div', g365_news_nav);
	var g365_news_nav_a = $('a', g365_news_nav);
	function g365_select_nav(select_button) {
		g365_news_nav_a.attr("aria-selected","false").blur().parent().removeClass('is-active');
		select_button.attr("aria-selected","true").parent().addClass('is-active');
	}
	g365_news_rotator.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
		g365_select_nav($('a', g365_news_nav_div[nextSlide]));
	});
	$('#slider-wrapper').on('mouseenter', function(){g365_news_rotator.slick('slickPause')});
	$('#slider-wrapper').on('mouseleave', function(){g365_news_rotator.slick('slickPlay')});
	g365_news_nav_a.on('click', function(e){
		e.preventDefault();
		var select_this = $(this);
		$('#news_rotator').slick('slickGoTo', select_this.parent().index());
		g365_select_nav(select_this);
	});
	g365_select_nav($(g365_news_nav_a[0]));
}

//support calendar table navigation if needed
var g365_table_hover_link = $('table.hover');
if( g365_table_hover_link.length > 0 ) {
  g365_table_hover_link.on('click','tr.event-line', function(){
    if( typeof $(this).attr('data-event_link') === 'undefined' ) return;
    var win = window.open($(this).attr('data-event_link'), '_blank');
  });
}

//make display rotator
var g365_display_rotator = $("#event_display_rotator");
if (g365_display_rotator.length > 0) {
  g365_display_rotator.slick({
    autoplay: true,
    autoplaySpeed: 4000,
    arrows: false,
    dots: false
  });
}

//event menu switcher
var event_menu_buttons = $('.event-menu-button', '#main-nav');
if( event_menu_buttons.length > 0 ) {
  $('a', event_menu_buttons).on('click', function(e){
    e.stopPropagation();
    var this_parent = $(this).parent();
    event_menu_buttons.removeClass('selected-tab');
    this_parent.addClass('selected-tab');
    event_menu_buttons.each(function(){ $('.' + $(this).attr('data-ev-target') ).addClass('hide'); });
    $('.' + this_parent.attr('data-ev-target') ).removeClass('hide');
  });
  $('.event-menu-button.event-menu-start a', '#main-nav').click();
}

//event region selector
var revealers = $('.revealer-column', '#event-menu-region');
if( revealers.length > 0 ) {
  $('.revealer-main #g365-all-regions-button', '#event-menu-region').click(function(e){
    e.stopPropagation();
    $(this).slideUp();
    $('.helper-title', '#event-menu-region').slideDown();
    revealers.removeClass('revealed-column hidden-column');
  });
  $('.revealer-column .nav-title', '#event-menu-region').click(function(e){
    e.stopPropagation();
    $('.revealer-main #g365-all-regions-button', '#event-menu-region').slideDown({
      start: function() {
        jQuery(this).css('display','inline-block');
      }
    });
    $('.helper-title', '#event-menu-region').slideUp();
    revealers.removeClass('revealed-column').addClass('hidden-column');
    $(this).closest('.revealer-column').addClass('revealed-column').removeClass('hidden-column');
  });
  $('.revealer-column.revealer-start .nav-title').click();
}


//event season accordion
var resizers = $('.resizer-column', '#event-menu-season');
if( resizers.length > 0 ) {
  $('.resizer-main #g365-all-events-button', '#event-menu-season').click(function(e){
    e.stopPropagation();
    $(this).slideUp();
    resizers.removeClass('expanded-column collapse-column');
    resizers.prev().removeClass('expanded-label-column collapse-label-column');
  });
  $('.resizer-column .nav-title', '#event-menu-season').click(function(e){
    e.stopPropagation();
    $('.resizer-main #g365-all-events-button', '#event-menu-season').slideDown();
    resizers.removeClass('expanded-column').addClass('collapse-column');
    resizers.prev().removeClass('expanded-label-column').addClass('collapse-label-column');
    $(this).closest('.resizer-column').addClass('expanded-column').removeClass('collapse-column').prev().addClass('expanded-label-column').removeClass('collapse-label-column');
  });
  $('.resizer-column.resizer-start .nav-title').click();
}

//toggles the next element
$('.toggle-next').click(function(){ $(this).next().toggleClass('hide');});
// $( '.accordion' ).on( 'click', 'h5', function(){
//   var button_clicked = $(this);
//   var self_close = button_clicked.next().is( ":visible" );
//   button_clicked.siblings( ':not(h5)' ).slideUp( 100, 'swing', function(){ $(this).prev().removeClass( 'is-active' ); });
//   if( self_close ) return false;
//   button_clicked.addClass( 'is-active' ).next().slideDown();
// });


// staff bio controller
$('.staff .blocks-gallery-item').on('click', function(){
  var clicked = $(this);
  clicked.siblings().removeClass('expanded');
  clicked.addClass('expanded');
});
$('.staff .blocks-gallery-item .staff-remove-button').on('click', function(e){
  e.stopPropagation();
  $(this).parent().parent().removeClass('expanded');
});


// var isMobile;

// function mobileCheck() {
//     if(window.outerWidth < 1100) {
//         isMobile = true;
//     } else  {
//         isMobile = false;
//     }
// }   

// $(function() {

//     //Staff section hover speed increase on desktop, click modal on mobile
//     //NOTE: doesnt work on resize, only initial load
//     if($('body').is('.home')) {
//         $('.registration-link').css('display', 'flex');

//         var staffMember = $('#staffWrapper .blocks-gallery-item');

//             mobileCheck();

//             if(isMobile == false) {
//                 staffMember.hover(
//                 function(e){
//                     var staffMemberBack = $(e.currentTarget.children[0].children[0].children[1]);
//                     staffMemberBack.css('display' , 'block');
//                 },function(e){
//                     var staffMemberBack = $(e.currentTarget.children[0].children[0].children[1]);
//                     staffMemberBack.css('display' , 'none');
//                 });
//             } else {
//                 staffMember.on('click touch', function(e) {
//                     var staffImage = $(e.currentTarget.children[0].children[0].children[0].children[0].children[0]);
//                     var staffMemberBack = $(e.currentTarget.children[0].children[0].children[1]);

//                     //add modal info with corresponding back of card data
//                     $(staffMemberBack.html()).insertAfter('#modalImg');

//                     //add modal img
//                     staffImage.clone().appendTo('#modalImg');

//                     //modal open
//                     $('.modal__outer').css('display', 'block');

//                 });

//                 $('.modal__close, .modal__outer').on('click', function(){
//                     $('.modal__outer').css('display', 'none');

//                     //clear info after the modal img
//                     $('#modalImg').nextAll().remove();
                    
//                     //clear modal img
//                     $('#modalImg').empty();
//                 })
//             }
//          } 
// });


//mega menu support (full page)
$('[data-curtain-menu-button]').click(function(){
  $('body').toggleClass('curtain-menu-open');
})
//slide menu support 
$('[data-side-slide-menu-button]').click(function(){
  $('body').toggleClass('side-slide-menu-open');
})
//slide menu closer
$('.main-navigation.side-slide-menu-wrapper').click(function(e){ if (e.target !== this) return; $(this).prev().click(); });

//switch between series pages
$("#series_selector").change(function(){
  window.location.href = $( "option:selected", this ).val();
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

//start any functions that depend on foundations to be loaded.
if (typeof g365_func_wrapper !== 'undefined' && g365_func_wrapper.found.length > 0) g365_func_wrapper.found.forEach( function(func){ func.name.apply(null, func.args); });
