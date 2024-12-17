<?php
/**
 * Template Name: CPS Profiles
 */
$position_order = array('Point Guard', 'Combo Guard', 'Wing', 'Forward', 'Post', 'Unclassified');
//load variables
global $wp_query;
//available vars from url
// $pl_id = $wp_query->query_vars['pl_id'];
// $pl_tp = $wp_query->query_vars['pl_tp'];
//add class for profile styles
add_filter( 'body_class', function($classes){ $classes[] = 'profiles-page'; return $classes; } );

get_header();
$g365_ad_info = shb_start_ads( $post->ID );
$print_search = true;

$event_id = 231;

$data_haul = g365_conn( 'g365_get_stats', ['null', $event_id, 1, "player.grad_year, pos.id, player.name"], 'g365_get_event_data', [$event_id, true] );
$stat_data = $data_haul[0];
if( gettype($data_haul) === 'string' ) $stat_data = $data_haul;
$event_data = array( $event_id => $data_haul[1] );

//if we have data, process it, otherwise show error
if( !is_string($stat_data) ) :
  //img defaults
  $default_profile_img = get_site_url() . '/wp-content/uploads/shb_profile_placeholder.gif';

  $stat_data_process = (object) array();
  foreach($stat_data as $val) {
    if( !isset($stat_data_process->{$val->grad_year}) ) $stat_data_process->{$val->grad_year} = (object) array();
    if( !isset($stat_data_process->{$val->grad_year}->{$val->position_name}) ) $stat_data_process->{$val->grad_year}->{$val->position_name} = array();
    $stat_data_process->{$val->grad_year}->{$val->position_name}[] = $val;
  }
?>
<section id="content" class="grid-x grid-margin-x site-main small-padding-top medium-padding-bottom profile-wrap<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
    //standard ad and title format for single use pages
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif; ?>
	</div>
  <div class="cell small-12">
    <ul class="accordion" data-responsive-accordion-tabs="accordion medium-tabs large-accordion" data-allow-all-closed="true">
    <?php
//     <ul class="accordion" data-responsive-accordion-tabs="accordion medium-tabs large-accordion" data-allow-all-closed="true" data-accordion data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge-delay="500">
    //output the classes
    foreach( $stat_data_process as $class => $positions ) { //start classes ?>
      <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">Class of <?php echo $class; ?></a>
        <div class="accordion-content" data-tab-content>
          <div class="grid-x grid-margin-x">
            <div class="cell small-12">
              <div class="button-group expanded stacked-for-small">
              <?php foreach( $position_order as $dex => $position ) {
                if( $position === 'Unclassified' && empty($positions->{''})  ) continue; ?>
                <a class="button<?php echo (empty($positions->{$position})) ? ' disabled' : '' ; ?>" href="#<?php echo strtolower(preg_replace("([^a-zA-Z])", "-", preg_replace("([^a-zA-Z -])", "", $position))); ?>_heading<?php echo '_' . $class; ?>"><?php echo $position; ?></a>
              <?php } ?>
              </div>
            </div>
            <?php
            //output the profiles
            foreach( $position_order as $dex => $position ) { //start position
              if( empty($positions->{$position}) ) continue;
              $player_stats = $positions->{$position};
              ?>
              <div class="cell small-12 gset large-margin-top large-margin-bottom">
                <h2 class="small-padding medium-padding-sides no-margin" id="<?php echo strtolower(preg_replace("([^a-zA-Z])", "-", preg_replace("([^a-zA-Z -])", "", $position))); ?>_heading<?php echo '_' . $class; ?>"><?php echo $position; ?></h2>
              </div>
              <div class="cell small-12 grid-x grid-margin-x small-up-1 medium-up-2 small-margin-bottom">
                <?php
                foreach( $player_stats as $dex => &$player_stat ) { //start player

                  //convert some data for output
                  if( !empty($player_stat->height_ft) ) $player_stat->height = $player_stat->height_ft . "'";
                  if( !empty($player_stat->height_ft) && !empty($player_stat->height_in) ) $player_stat->height .= ' ' . $player_stat->height_in . '"';
                  if( !empty($player_stat->state) ) $player_stat->hometown = $player_stat->state;
                  if( !empty($player_stat->state) && !empty($player_stat->city) ) $player_stat->hometown = $player_stat->city . ', ' . $player_stat->hometown;

                  if( !empty($player_stat->trends) && !empty($event_data[$player_stat->event]) && !empty($event_data[$player_stat->event]->trends) ) :
                    $player_stat->trends = json_decode($player_stat->trends);
                    if(!is_object($event_data[$player_stat->event]->trends)) {
                      $event_trends = json_decode($event_data[$player_stat->event]->trends);
                      $trend_by_handle = (object) array();
                      foreach( $event_trends as $dex => $trend ) $trend_by_handle->{$trend->handle} = $trend;
                      $event_data[$player_stat->event]->trends = $trend_by_handle;
                    }
                  endif;
                  ?>
                  <div class="cell tiny-padding" id="<?php echo $player_stat->id; ?>_player">
                    <div id="profile-wrapper" class="grid-x grid-margin-x flex-align-content-start small-padding-sides medium-padding-top medium-padding-bottom maximum-height gset profile">
                      <div class="cell small-12">
                        <h1 class="profile-name small-profile cell auto">
                          <?php echo $player_stat->name; ?>
                        </h1>
                      </div>
                      <div id="profile-image" class="cell small-6 small-offset-3 medium-offset-0">
                        <div class="profile-image-bg green-border">
                          <img src="<?php
                          //if we have don't have a general profile img then get one
                          if( !empty($player_stat->event_profile_img) ) {
                            echo $player_stat->event_profile_img;
                          } else {
                            echo $default_profile_img;
                          }
                          ?>" />
                        </div>
                        <?php
                        if( is_object($player_stat->trends) && !empty($player_stat->trends->video_link) ) echo '<a href="' . $player_stat->trends->video_link . '" class="button emphasis expanded small-margin-top no-margin-bottom" target="_blank">Highlight Reel</a>';
                        ?>
                      </div>
                      <div id="profile-name" class="cell small-12 medium-6">
                        <div id="profile-info" class="cell small-12">
                          <table class="unstriped stack profile-data small-margin-bottom">
                            <tbody>
                              <tr><th><strong>Height:</strong></th><td><?php echo ( empty($player_stat->height) ) ? '' : $player_stat->height; ?></td></tr>
          <!--                     <tr><th><strong>Weight:</strong></th><td><?php echo ( empty($player_stat->weight) ) ? '' : $player_stat->weight; ?></td></tr> -->
                              <tr><th><strong>Class:</strong></th><td><?php echo ( empty($player_stat->grad_year) ) ? '' : $player_stat->grad_year; ?></td></tr>
                              <?php if( !empty( $player_stat->position_name )  ) echo '<tr><th><strong>Position:</strong></th><td>' . $player_stat->position_name . '</td></tr>'; ?>
                              <?php if( !empty( $player_stat->gpa )  ) echo '<tr><th><strong>GPA:</strong></th><td>' . $player_stat->gpa . '</td></tr>'; ?>
                              <?php if( !empty( $player_stat->sat )  ) echo '<tr><th><strong>SAT:</strong></th><td>' . $player_stat->sat . '</td></tr>'; ?>
                              <?php if( !empty( $player_stat->act )  ) echo '<tr><th><strong>ACT:</strong></th><td>' . $player_stat->act . '</td></tr>'; ?>
          <!--                     <tr><th><strong>Club Team:</strong></th><td><?php //echo ( empty($player_stat->club_name) ) ? '' : '<a href="' . get_site_url() . '/club/' . $player_stat->club_url . '">' . (( empty($player_stat->club_abb) ) ? $player_stat->club_name : $player_stat->club_abb) . '</a>'; ?></td></tr> -->
                              <?php if( !empty( $player_stat->school_name )  ) echo '<tr><th><strong>School:</strong></th><td>' . $player_stat->school_name . '</td></tr>'; ?>
                              <tr><th><strong>State:</strong></th><td><?php echo ( empty($player_stat->state) ) ? '' : $player_stat->state; ?></td></tr>
                              <?php
                              if( is_object($player_stat->trends) ) {
                                foreach($player_stat->trends as $trend_handle => $trend_value) :
                                  if( strpos( $trend_handle, '_link' ) !== false ) {} else { ?>
                                  <tr><th><strong><?php echo $event_data[$player_stat->event]->trends->{$trend_handle}->name; ?></strong></th><td><?php echo ( empty($trend_value) ) ? '' : $trend_value; ?></td></tr>
                                  <?php
                                  }
                                endforeach;
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <?php if( !empty($player_stat->stats) && !empty($event_data[$player_stat->event]) && !empty($event_data[$player_stat->event]->stats) ) :
                        $player_stat->stats = json_decode($player_stat->stats);
                        if(!is_object($event_data[$player_stat->event]->stats)) {
                          $event_stats = json_decode($event_data[$player_stat->event]->stats);
                          $stat_by_handle = (object) array();
                          foreach( $event_stats as $dex => $stat ) $stat_by_handle->{$stat->handle} = $stat;
                          $event_data[$player_stat->event]->stats = $stat_by_handle;
                        }
                      ?>
                      <div id="profile-stats" class="cell small-12 xlarge-margin-top">
                        <h2>Stats</h2>
                        <div class="table-scroll">
                          <table class="text-center ghost-white-bg no-margin-bottom">
                            <tbody>
                              <tr>
                                <?php foreach($player_stat->stats as $stat_handle => $stat_value) : ?>
                                <th><?php echo $event_data[$player_stat->event]->stats->{$stat_handle}->name; ?></th>
                                <?php endforeach; ?> 
                              </tr>
                              <tr>
                                <?php foreach($player_stat->stats as $stat_handle => $stat_value) : ?>
                                <td><?php echo $stat_value; ?></td>
                                <?php endforeach; ?> 
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php 
                    } //end player
                  ?>
              </div>
            <?  } //end position ?>
          </div>
        </div>
      </li>
    <?php } //end classes?>
    </ul>
  </div>
	</section>
	<script>
	<?php //add the js to power the stat jumping and video thumbs
	if( !empty($wp_query->query_vars['pl_tp']) ) : $targ = preg_replace('/\s+|\.|-/', '', $wp_query->query_vars['pl_tp']); ?>
		function g365_nav_click( targ ) {  $('#click' + targ).click(); }
		if( typeof g365_func_wrapper !== 'object' ) var g365_func_wrapper={sess:[],found:[],end:[]};
		g365_func_wrapper.found[g365_func_wrapper.found.length] = {name : g365_nav_click, args : ['<?php echo $targ; ?>']};
	<?php endif; ?>
	</script>
<?php	else : //end of cps data check ?>
<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		?>
		<h1 class="entry-title">CPS</h1>
    <h3><?php echo $stat_data; ?></h3>
  </div>
</section>
<?php endif;
get_footer();


//         <?php if( !empty($player_stat->trends) && !empty($event_data[$player_stat->event]) && !empty($event_data[$player_stat->event]->trends) ) :
//           $player_stat->trends = json_decode($player_stat->trends);
//           if(!is_object($event_data[$player_stat->event]->trends)) {
//             $event_trends = json_decode($event_data[$player_stat->event]->trends);
//             $trend_by_handle = (object) array();
//             foreach( $event_trends as $dex => $trend ) $trend_by_handle->{$trend->handle} = $trend;
//             $event_data[$player_stat->event]->trends = $trend_by_handle;
//           }
//         ? >
//         <div id="profile-trends" class="cell small-12 xlarge-margin-top">
//           <h2>More Info</h2>
//           <div id="profile-info-more" class="cell small-12">
//             <table class="unstriped stack profile-data small-margin-bottom">
//               <tbody>
//                 <?php foreach($player_stat->trends as $trend_handle => $trend_value) : ? >
//                 <tr><th><strong><?php echo $event_data[$player_stat->event]->trends->{$trend_handle}->name; ? ></strong></th><td><?php echo ( empty($trend_value) ) ? '' : $trend_value; ? ></td></tr>
//                 <?php endforeach; ? > 
//               </tbody>
//             </table>
//           </div>
//         </div>
//         <?php endif; ?>