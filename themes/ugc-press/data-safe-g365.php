<?php
/**
 * Template Name: Data Input - G365 Form
 */
if( strpos(site_url(), 'dev.') === false ) {
  wp_enqueue_script( 'js-g365-all', 'https://grassroots365.com/data-processor.js', array('jquery'), '69547', true );
} else {
  wp_enqueue_script( 'js-g365-all', 'https://dev.grassroots365.com/data-processor.js', array('jquery'), '69547', true );
}

defined( 'ABSPATH' ) || exit;

//load variables
get_header();
$shb_ad_info = shb_start_ads( $post->ID );

?>
  <section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_section_class']; ?>" role="main">
  	<div class="cell small-12">
<?php
		if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_before'] . $shb_ad_info['ad_content'] . $shb_ad_info['ad_after'];
// Check if the user is logged in before showing content
if ( !is_user_logged_in() ) { ?>

    <div class="cell small-12">
    
    <?php
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

    echo '<h1 class="xlarge-margin-bottom">Please Login to Continue to:<br><small class="loudest">' . get_the_title() . '</small></h1>';
    echo '<div class="cell">';
    echo do_shortcode( '[woocommerce_my_account]' );
    echo '</div>';
    ?>
  
      <h5 class="cell callout medium-padding">You must be logged in to modify data.</h5>
    </div>

  <?php
} else {
  //you are logged in, congrates
  //load variables
  global $wp_query;
  //available vars from url
  // $rg_tp = $wp_query->query_vars['rg_tp'];
  // $rg_ps = $wp_query->query_vars['rg_ps'];

  //get keys to test type
  $form_type_data = g365_return_keys( 'g365_url_form_key' );
  //url keys for form type
  $form_type_opt = $form_type_data[0];
  //key for form targets
  $form_type_target = $form_type_data[1];
  //if we have a form, process it or default page
  if( !empty($form_type_opt[$wp_query->query_vars['rg_tp']]) ) {
    $url_type_key = $wp_query->query_vars['rg_tp'];
    $rg_tp = $form_type_opt[$url_type_key];
    $rg_tp_full = $rg_tp;
    $rg_ps = '';
    $title = '';
    $event_details = '{}';
    $ids = filter_input( INPUT_GET, 'ro_ids', FILTER_SANITIZE_STRING );
    if( !empty($ids) ) $rg_tp_full .= ',' . $ids;
    //if the preset deafult is set, use it, otherwise 
    if( (!empty($wp_query->query_vars['rg_ps']) && empty($wp_query->query_vars[$url_type_key . '_' . $form_type_target[$url_type_key]])) || $wp_query->query_vars['rg_ps'] === '0'){
      //set var for main query var
      $rg_ps = $wp_query->query_vars['rg_ps'];
      //set defaults based on request type
      switch( $form_type_target[$url_type_key] ) {
        case 'event_id':
        case 'event_id_pm':
        case 'event_id_ct':
        case 'event_id_cps':
        case 'event_id_cp':
          //get event name

//               "ev":{
//                 "name": $ev_data->short_name,
//                 "vars":[
//                   {
//                    "name": $ev_data->name,
//                    "full_name": $ev_data->short_name,
//                    "event_divisions": $ev_data->divisions,
//                    "event_id":$rg_ps
//                   }
//                 ]
//               }
          //get event data if we have it
          $ev_data = null;
          if( $rg_ps  !== '0' ) $ev_data = g365_conn('g365_get_events', [ $rg_ps, null, 'id, name, short_name, divisions']);
          if( empty($ev_data) ) {
            $rg_ps = '0';
          } else {
            $rg_ps = $ev_data->id;
          }

          if( $rg_ps === '0' ) {
            $event_details = '{"ev":{"id": "ev", "name": "Default","vars": [{"name": "Default","full_name": "Default","event_divisions": "0","event_id": "0"}]}}';
            $ev_data = (object) array( 'name' => 'Roster for Club Team Page' );
          } else {
            $event_details = '{"ev":{"id": "ev", "name": "' . $ev_data->short_name . '","vars": [{"name": "' . $ev_data->name . '","full_name": "' . $ev_data->short_name . '","event_divisions": ' . (empty($ev_data->divisions) ? "0" : $ev_data->divisions) . ',"event_id": "' . $rg_ps . '"}]}}';
          }
          $title = ( empty($ev_data->name) ) ? 'Team Sign-Up' : $ev_data->name;
          break;
        case 'coach_id':
          $event_details = '{"co":{"id": "co", "name": "Add Coach","vars": [{"name": "Add Coach","full_name": "Add Coach"}]}}';
          $title = 'Coach Sign-Up';
        }
        $rg_ps = $form_type_target[$url_type_key] . ':' . $rg_ps;
        $rg_ps = ' data-g365_init_pre="' . $rg_tp . '_preset,' . $rg_ps . '" ';
      }
        
    ?>
    <div class="a cell small-12 medium-8 large-6"><script type="text/javascript">var g365_form_details = {"items" : {"Registration Forms":{"name":"Please fillout entire form.","title":"<?php echo $title; ?>","type":"<?php echo $rg_tp; ?>","items": <?php echo $event_details; ?>}}, "wrapper_target" : "g365_form_options_anchor", "admin_key" : "<?php echo g365_make_admin_key(); ?>"};</script>
      <p>&nbsp;</p>
      <div>
        <div id="g365_form_options_anchor" data-g365_type="<?php echo $rg_tp_full; ?>"<?php echo $rg_ps; ?>></div>
      </div>
    </div>
  <?php
  } else {
    if( !empty($_GET['ref_id']) && !empty(intval($_GET['ref_id'])) && !empty($_GET['ref_em']) ){
      //see if we can authorize the claim
      $g365_auth_result = g365_authorize_claim( $_GET['ref_id'], $_GET['ref_em'] );
      ?>
      <article id="post-<?php the_ID(); ?>">
        <header class="entry-header">
          <h1 class="entry-title">Grant Access</h1>
        </header><!-- .entry-header -->

        <div class="entry-content">
          <p>
            <?php  echo ((is_array($g365_auth_result)) ? $g365_auth_result[0] : $g365_auth_result) ?>
          </p>
        </div><!-- .entry-content -->

      </article><!-- #post-## -->
      <?php
    } else {
      // show some links and messaging if we aren't doing a form
      if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'page-parts/content', get_post_type() );

        endwhile;
      else :

          get_template_part( 'page-parts/content', 'none' );

      endif;

      $child_pg_args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $post->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order'
      );
      $parent_pg = new WP_Query( $child_pg_args );
      if ( $parent_pg->have_posts() ) : ?>

        <div class="cell small-12">

        <?php while ( $parent_pg->have_posts() ) : $parent_pg->the_post(); ?>

        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> | 

      <?php endwhile; ?>

        </div>

      <?php endif; wp_reset_postdata();
    }
  }
}?>
  </div>
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