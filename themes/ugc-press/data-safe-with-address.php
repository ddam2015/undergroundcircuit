<?php
/**
 * Template Name: Data Input w/ Address
 */

defined( 'ABSPATH' ) || exit;

//load variables
get_header();
$shb_ad_info = shb_start_ads( $post->ID );
?>
  <section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_section_class']; ?>" role="main">
<?php
		if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_before'] . $shb_ad_info['ad_content'] . $shb_ad_info['ad_after'];
// Check if the user is logged in before showing content
if ( !is_user_logged_in() ) {

  wp_localize_script( 'wc-password-strength-meter', 'pwsL10n', array(
    'unknown' => __('Password strength unknown'),
    'empty' => __( 'Strength indicator' ),
    'short' => __( 'Very weak' ),
    'bad' => __( 'Weak' ),
    'good' => __( 'Medium', 'password strength' ),
    'strong' => __( 'Strong' ),
    'mismatch' => __( 'Mismatch' )
  ) );
  wp_enqueue_script( 'password-strength-meter' );
  wp_enqueue_script( 'wc-password-strength-meter', site_url() . '/wp-content/plugins/woocommerce/assets/js/frontend/password-strength-meter.min.js', array('jquery', 'password-strength-meter'), '4', true );
  wp_localize_script( 'wc-password-strength-meter', 'wc_password_strength_meter_params', array(
    'min_password_strength' => '3',
    'stop_checkout' => '',
    'i18n_password_error' => __('Please enter a stronger password.'),
    'i18n_password_hint' => __('Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! \" ? $ % ^ & ).')
  ) );
  
  echo '<h1 class="cell xlarge-margin-bottom">Please Login to Continue to:<br><small class="loudest">' . get_the_title() . '</small></h1>';
  echo '<div class="cell">';
  echo do_shortcode( '[woocommerce_my_account]' );
  echo '</div>';
  echo '<h5 class="cell callout medium-padding">You must be logged in to modify data.</h5>';

} else { 
  
  wc_print_notices();

  if ( have_posts() ) : while ( have_posts() ) : the_post();

    get_template_part( 'page-parts/content', 'address' );
    $user_key = g365_make_admin_key();
    if( !empty($user_key) ) echo '<script type="text/javascript">g365_form_details.admin_key = "' . $user_key . '";</script>';

  endwhile;
  // If no content, include the "No posts found" template.
  else :

    get_template_part( 'page-parts/content', 'none' );

  endif;

} ?>
</section>

<?php get_footer();

if ( !is_user_logged_in() ) { ?>
  <script type="text/javascript">
    (function($) {
      $('#reg_password2').on('focusout', function(){
        var pass2 = $(this);
        if( pass2.prev().attr('id') === 'reg_password2_warning' ) pass2.prev().remove();
        if( pass2.val() !== $('#reg_password').val() ) $( '<div id="reg_password2_warning" class="woocommerce-password-strength bad">Passwords don\'t match.</div>' ).insertBefore(pass2)
      });
    })(jQuery);
  </script>
<?php } ?>