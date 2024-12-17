<?php
/**
 * The front page template
 * @package shb  Press
 * @since shb 1.0.0
 */

// News query for the slider
$news_feat = new WP_Query( array( 'category_name' => 'Featured', 'posts_per_page' => 6 ) );

//https://dev.grassroots365.com/wp-content/uploads/display-assets/event-promo-shb.jpg
//https://dev.grassroots365.com/wp-content/uploads/2017/11/shb-posts-banner.jpg
get_header();

//see if we need a splash display
$shb_ad_info = shb_start_ads( $post->ID );

$default_event_img = get_site_url() . '/wp-content/themes/ugc-press/shb_default_placeholder.gif';
$default_img = get_site_url() . '/wp-content/themes/ugc-press/shb_default_placeholder.gif';
$default_vid = get_site_url() . '/wp-content/themes/ugc-press/assets/videos/Beach-Email-Header.mp4';
$bigBG = get_site_url() . '/wp-content/uploads/2024/06/IMG_0762.jpg';
$smallBG = get_site_url() . '/wp-content/uploads/2024/06/IMG_0762.jpg';

$shb_layout_type = get_option( 'shb_layout' );
?>
<section class="hero herofadein">
  <a href="#" class="neon-button">
  <img class="hide" src="http://dev.theundergroundcircuit.com/wp-content/uploads/2024/08/underground-circuit-white.png">
  <img  src="<?php echo get_site_url();?>/wp-content/themes/ugc-press/assets/UndergroundLogo-400x300.png" alt="Underground Circuit Logo">
  </a>
</section>

<section id="content" class="site-main small-padding-top xlarge-padding-bottom grid-container">
  

		<div class="grid-x grid-margin-x">
      <div class="cell small-12">
        <div class="official-programs fadein">
        <h2 class="text-center">Official Programs</h2>
        <div class="orbit large-margin-top large-margin-bottom" role="region" aria-label="Favorite  Pictures" data-orbit="" data-resize="gr0eda-orbit" id="gr0eda-orbit" data-n="cgqrpa-n" data-events="resize">
          <div class="orbit-wrapper">
            <div class="orbit-controls hide">
              <button class="orbit-previous" tabindex="0"><span class="show-for-sr">Previous Slide</span>ᐸ</button>
              <button class="orbit-next" tabindex="0"><span class="show-for-sr">Next Slide</span>ᐳ</button>
            </div>
            <ul class="orbit-container college-container" tabindex="0" style="height: 144px;">
              <li class="orbit-slide  is-active" data-slide="0">
                <figure class="orbit-figure">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/CaliforniaStorm.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/CMELITE.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/DubsElite.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/ElPasoPrimetime.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/Future.png" alt="">
                </figure>
              </li>
              <li class="orbit-slide" data-slide="1">
                <figure class="orbit-figure">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/Gamepoint.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/GsixElit.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/HawkHoops.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/LASElect.png" alt>
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/YBAFTERMATH.png" alt="">
                </figure>
              </li>
              <li class="orbit-slide" data-slide="2" aria-live="polite">
                <figure class="orbit-figure">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/LasVegasUnited.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/MiamiKnights.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/OGPAnaheim.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/Lakeshow.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/TeamHoliday.png" alt="">
                </figure>
              </li>
              <li class="orbit-slide" data-slide="3">
                <figure class="orbit-figure">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/OGPArizona.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/PortCity.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/PortlandSupreme.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/ProsVision.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/SEB.png" alt="">
                </figure>
              </li>

              <li class="orbit-slide" data-slide="4">
                <figure class="orbit-figure">

                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/SESupreme.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/TeamArsenal.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/OGPLadera.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/VegasShowtime.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/OGPCorona.png" alt="">
                </figure>
              </li>

              <li class="orbit-slide" data-slide="5">
                <figure class="orbit-figure">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/confirmed-programs/YOungDons.png" alt="">
                </figure>
              </li>




            </ul>
          </div>
          <nav class="orbit-bullets">
            <button class="is-active" data-slide="0">
              <span class="show-for-sr">First slide details.</span>
            </button>
            <button data-slide="1" class=""><span class="show-for-sr">Second slide details.</span></button>
            <button data-slide="2" class=""><span class="show-for-sr">Second slide details.</span><span class="show-for-sr" data-slide-active-label="">Current Slide</span></button>
            <button data-slide="3"><span class="show-for-sr">Second slide details.</span></button>
            <button data-slide="4"><span class="show-for-sr">Second slide details.</span></button>
            <button data-slide="5"><span class="show-for-sr">Second slide details.</span></button>


          </nav>
        </div>
      </div>

        <section class="features">
          <div class="feature fadeinleft">
            <img src="https://grassroots365.com/wp-content/uploads/2024/09/G365-Fall-Kick-Off-2024-Day-2-37.jpg" style="    height: 300px; object-fit: cover; object-position: top;">
            <div>
              <h2 class="feature-heading">Features</h2>
              <ul>
                <li>Exclusive invite only</li>
                <li>Elite competition from 4th to 8th grade</li>
                <li>Player edition gear</li>
                <li>Advanced scheduling</li>
                <li>All stop guarantee</li>
                <li>Media coverage</li>
                <li>Shoe brand diversity</li>
              </ul>
            </div>
          </div>
          <div class="feature fadeinleft">
            <div>
              <h2 class="feature-heading">The Passport</h2>
              <p>The Passport is designed to transform the youth sports experience by archiving the player journey including verifying age and grade, tracking event participation, delivering real time stats, box scores and standings, showcasing achievements and storing pictures and videos.</p>
            </div>
            <img src="https://sportspassports.com/wp-content/themes/g365-press/assets/tiny-logos/Passport-2023.png">
          </div>
        </section>
  </div>

</section>



  <?php
if( $shb_layout_type['front_layout']['type'] === 'tiles' && count($news_feat->posts) === 6 ){
  //trigger for tile video support
  $tile_vid = false;
  $tile_video_settings = [];

  //get tile banner
	$shb_tile_banner = get_option( 'shb_display' );
	//reassign to focus on tile banner
	$shb_tile_banner = $shb_tile_banner['site_4'];
  $shb_tile_banner_build = '';
  //build tile banner from global settings if we have data
  if ( !empty($shb_tile_banner['title']) ) {
    if ( !empty($shb_tile_banner['link']) ) {
      $shb_tile_banner_build .= '<h2 class="no-margin"><a href="' . $shb_tile_banner['link'] . '">' . $shb_tile_banner['title'] . '</a></h2>';
    } else {
      $shb_tile_banner_build .= '<h2 class="no-margin">' . $shb_tile_banner['title'] . '</h2>';
    }
  }
  if ( !empty($shb_tile_banner['sub_title']) ) $shb_tile_banner_build .= '<p class="no-margin">' . $shb_tile_banner['sub_title'] . '</p>';
  
  function shb_tile_template( $target_num, $news_feat, $classes ) {
    $tile_type = get_post_meta($news_feat->posts[$target_num]->ID, 'video_head', true);
    $classes .= (!empty(array_filter(get_the_category( $news_feat->posts[$target_num]->ID ), function ($post) { return $post->slug === 'girls'; }))) ? ' girls_shb_hot_pink' : '';
    if( empty($tile_type) ) {
      $tile_type = '<img src="' . (( has_post_thumbnail($news_feat->posts[$target_num]->ID) ) ? get_the_post_thumbnail_url( $news_feat->posts[$target_num]->ID, "featured-tile" ) : get_site_url() . "/wp-content/themes/ugc-press/assets/shb_blank-placeholder_640x640.jpg") . '" alt="' . $news_feat->posts[$target_num]->post_title . '" />';
    } else {
      $video_settings = explode(":", $tile_type);
      if( $video_settings[0] === 'youtube' ) {
        global $tile_vid;
        global $tile_video_settings;
        $tile_type = '<div id="tile_player_' . $news_feat->posts[$target_num]->ID . '"></div>';
        $tile_vid = true;
        $tile_video_settings[] = (object) [
          'id' => 'tile_player_' . $news_feat->posts[$target_num]->ID,
          'data'=> (object)[
            'height' => '640.125',
            'width' => '1138',
            'videoId' => $video_settings[1],
            'playerVars' => (object)[
              'controls' => 0,
              'fs'  => 0,
              'modestbranding'  => 1,
              'enablejsapi' => 1,
              'loop' => 1,
              'playlist' => $video_settings[1]
            ]
          ]
        ];
        $classes .= ' responsive-embed';
//         $tile_type = '<iframe type="text/html" width="1138" height="640.125"
// src="https://www.youtube.com/embed/' . $video_settings[1] . '?autoplay=1&controls=0&enablejsapi=1&loop=1&modestbranding=1&fs=0" frameborder="0"></iframe>';
//         $classes .= ' responsive-embed';
      }
    }
    return '        <div id="news-' . $news_feat->posts[$target_num]->ID . '" class="white-border thick-border tile relative maximum-height">
          <a href="' . get_permalink($news_feat->posts[$target_num]->ID) . '" class="' . $classes . '">' . $tile_type . '</a>
          
          
          <h1 class="article-info">
            <a href="' . get_permalink($news_feat->posts[$target_num]->ID) . '">' . $news_feat->posts[$target_num]->post_title . '</a>' . 
            (( !empty($news_feat->posts[$target_num]->post_excerpt) ) ? "<p class=\"no-margin cute orange text-lowercase\">" . $news_feat->posts[$target_num]->post_excerpt . "</p>" : "") . 
          '</h1>
        </div>';
  } ?>

<div class="hide-for-small-only text-container" >
<!--   <video style="height:100%; width:100%;"  controls autoplay="true" loop="true" muted>
  <source src='/wp-content/themes/ugc-press/assets/videos/shb-DRONE-FACILITY.mov' type='video/mp4'>
  Your browser does not support the video tag.
  </video>
  
  <div class="welcoming-shb">
    <h1 style="float:left" class="welcome-text">WELCOME TO</h1><img src="/wp-content/themes/ugc-press/assets/tiny-logos/The-Stage-Act-3-883.jpg" style="float:right" class="welcome-img"></img>
  </div> -->
  
  
  <figure class=" size-full is-resized">
        <img srcset="<?php echo $smallBG ?> 1920w, <?php echo $bigBG ?> 2400w" src="<?php $smallBG ?>"  alt="" class="wp-image-51" />
  </figure>
  
  <div class="parent-2 ">
    
  <div class="grid-x align-center">
         <img class="img-2" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/tiny-logos/Strictly-Hoops-Logo.png">
  </div>
  
  </div>

</div>

<div class="show-for-small-only text-container">
<!--   <video style="height:100%; width:100%;"  autoplay="true" loop="true" muted  playsinline autoplay>
  <source src='/wp-content/themes/ugc-press/assets/videos/shb-DRONE-FACILITY.mov' type='video/mp4'>
  Your browser does not support the video tag.
  </video> -->
  
<!--   <iframe width="560" height="315" src="https://www.youtube.com/embed/R17HN5m1rqE?&autoplay=1&loop=1&rel=0&showinfo=0&color=white&mute=1&playlist=R17HN5m1rqE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; loop" allowfullscreen></iframe>
  
  <div class="welcoming-shb">
    <h1 style="float:left" class="welcome-text">WELCOME TO</h1><img src="/wp-content/themes/ugc-press/assets/tiny-logos/shb-new-white.png" style="float:right" class="welcome-img"></img>
  </div> -->
  
  
  <figure class=" size-full is-resized">
        <img srcset="<?php echo $smallBG ?> 1920w, <?php echo $bigBG ?> 2400w" src="<?php $smallBG ?>"  alt="" class="wp-image-51" />
  </figure>
  
  <div class="parent-2 ">
    
  <div class="grid-x align-center">
         <img class="img-2" src="<?php echo get_site_url(); ?>/wp-content/themes/ugc-press/assets/tiny-logos/Strictly-Hoops-Logo.png">
  </div>
  
  </div>
  
  
</div>


<section class="site-main width-hd hero-tiles<?php if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_section_class']; ?>">
  <?php if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_before'] . $shb_ad_info['ad_content'] . $shb_ad_info['ad_after']; ?>
  <div class="grid-x white-border thick-border" style="overflow-x:scroll; flex-wrap: nowrap;">
  
<!--             <div class="cell small-4 maximum-height">
              <?php echo shb_tile_template( 0, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo shb_tile_template( 1, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo shb_tile_template( 2, $news_feat, 'tile-image' ); ?>
            </div> -->
            <div class="cell small-4 maximum-height">
              <?php echo shb_tile_template( 3, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo shb_tile_template( 4, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo shb_tile_template( 5, $news_feat, 'tile-image' ); ?>
            </div>
        <?php if( $shb_tile_banner_build !== '' ) : ?>
        <div class="cell shrink">
          <div class="grid-x maximum-height">
            <div class="cell small-12 text-center small-small-padding large-padding callout secondary no-margin white-border thick-border">
              <?php echo $shb_tile_banner_build; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
  </div>
  
<!--old   <div class="grid-x white-border thick-border">
    <div class="cell medium-8">
      <div class="grid-y grid-frame small-block">
        <div class="cell auto">
          <div class="grid-x maximum-height">
            <div class="cell small-6 maximum-height">
              <?php echo shb_tile_template( 0, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-6 maximum-height">
              <?php echo shb_tile_template( 1, $news_feat, 'tile-image' ); ?>
            </div>
          </div>
        </div>
        <?php if( $shb_tile_banner_build !== '' ) : ?>
        <div class="cell shrink">
          <div class="grid-x maximum-height">
            <div class="cell small-12 text-center small-small-padding large-padding callout secondary no-margin white-border thick-border">
              <?php echo $shb_tile_banner_build; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <div class="cell auto">
          <div class="grid-x maximum-height">
            <div class="cell small-6 maximum-height">
              <?php echo shb_tile_template( 2, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-6 maximum-height">
              <?php echo shb_tile_template( 3, $news_feat, 'tile-image' ); ?>
            </div>
          </div>
        </div>
      </div>
    </div>  
    <div class="cell medium-4">
      <div class="grid-x">
        <div class="cell small-6 medium-12">
          <?php echo shb_tile_template( 4, $news_feat, 'tile-image' ); ?>
        </div>
        <div class="cell small-6 medium-12">
          <?php echo shb_tile_template( 5, $news_feat, 'tile-image' ); ?>
        </div>
      </div>
    </div>
  </div> -->
  
<div id="sth-info" style="text-align: right; margin-right: 2rem;">

<button class="button slider-btn" id="newsLeft" disabled><</button>
<button class="button slider-btn" id="newsRight">></button>
</div>
  
</section>

<!-- <img style="border: #fefefe solid 10px; height:780px; width: 100%" src="/wp-content/themes/ugc-press/assets/tiny-logos/Club-Accomplishments.png"> -->

<?php
  //$featured_events_arr = g365_conn( 'g365_display_events', [65, 6] );
  $shb_potm = get_post_meta($post->ID, 'shb_potm', true);
  $shb_ctotm = get_post_meta($post->ID, 'shb_ctotm', true);
  if( !empty( $shb_potm ) || !empty( $shb_ctotm ) || !empty( $featured_events_arr ) ) :
?>
<section class="site-main small-padding-top xlarge-padding-bottom grid-container">
  <div class="grid-x grid-margin-x">
    <div id="main" class="small-12 cell">
      <?php if( !empty($featured_events_arr) ) : ?>
      <div class="tiny-padding gset no-border">
        <h2 class="entry-title text-center screen-reader-text"><a href="/calendar">Featured Events</a></h2>
      </div>
      <div class="widget-wrapper medium-margin-bottom">
        <div class="grid-x small-up-2 medium-up-3 large-up-6 text-center profile-feature profile-widget">
          <?php foreach( $featured_events_arr as $dex => $obj ) : ?>
          <div class="cell">
            <div class="small-margin-bottom">
              <a href="<?php echo $obj->link; ?>" target="_blank">
                <img src="<?php echo (!empty($obj->logo_img)) ? $obj->logo_img : $default_event_img ?>" alt="<?php echo $obj->name; ?> official logo" />
                <p>
                  <?php echo ( empty($obj->short_name) ) ? $obj->name : $obj->short_name; ?><br>	
                  <small class="tiny-margin-top block"><?php echo shb_build_dates($obj->dates); ?></small>
                </p>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <a class="button expanded no-margin-bottom" href="/calendar">Full Calendar</a>
      </div>
      <?php endif;
      if( !empty($shb_potm) ) : ?>
      <div class="widget-wrapper medium-margin-bottom">
        <div class="grid-x">
          <div class="cell">
            <img src="<?php echo $shb_potm; ?>" alt="Players of the month by region. <?php the_modified_date(); ?>" />
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php if( !empty($shb_ctotm) ) : ?>
      <div class="widget-wrapper medium-margin-bottom">
        <div class="grid-x">
          <div class="cell">
            <img src="<?php echo $shb_ctotm; ?>" alt="Club Team of the month. <?php the_modified_date(); ?>" />
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; //end ptom section ?>

<?php } else { //end tile layout hero section, begin standard featured post rotator ?>

<section class="hide site-main small-padding-top xlarge-padding-bottom grid-container<?php if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_section_class']; ?>">
  <?php if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_before'] . $shb_ad_info['ad_content'] . $shb_ad_info['ad_after']; ?>
  <div class="grid-x grid-margin-x">
    <div id="main" class="small-12 medium-8 cell">
      <div class="tiny-padding gset no-border">
        <h2 class="entry-title"><a href="/category/news/">News</a></h2>
      </div>
      <div id="slider-wrapper" class="tiny-padding gset no-border medium-margin-bottom">
        <div class="grid-x collapse">
          <div class="small-12 medium-12 large-9 cell">
            <div id="news_rotator">

              <!-- News Slides	 -->
              <?php if ( $news_feat -> have_posts() ) : while ( $news_feat -> have_posts() ) : $news_feat -> the_post(); ?>

              <div id="news-<?php echo $post->ID; ?>" class="green-border tab-slider relative">
                <a href="<?php echo get_permalink(); ?>">
                  <img src="<?php echo ( has_post_thumbnail() ) ? the_post_thumbnail_url( 'featured-home' ) : 'http://image.mlive.com/home/mlive-media/width960/img/kalamazoogazette/photo/2016/12/22/-c8733c1e608c238b.JPG'; ?>" alt="<?php echo get_the_title(); ?>" />
                </a>
                <h4 class="article-info">
                  <a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>
                </h4>
              </div>

              <?php endwhile; wp_reset_postdata(); endif; ?>

            </div>
          </div>
          <div class="small-12 medium-12 large-3 cell">
            <div class="tabs tabs-vertical vertical flex-container flex-dir-column green-border slide-thumbs maximum-height" id="news_nav">

            <?php if ( $news_feat -> have_posts() ) : while ( $news_feat -> have_posts() ) : $news_feat -> the_post(); ?>

              <div class="tabs-title flex-child-auto flex-container flex-dir-column">
                <a class="flex-child-auto" href="#news<?php echo $post->ID; ?>"><?php echo get_the_title(); ?></a>
              </div>

            <?php endwhile; wp_reset_postdata(); endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="side" class="small-12 medium-4 cell">
      <div class="tiny-padding gset no-border">
        <h2 class="entry-title text-center screen-reader-text"><a href="/calendar">Featured Events</a></h2>
      </div>
      <div class="widget-wrapper medium-margin-bottom">
        <div class="grid-x small-up-2 text-center profile-feature profile-widget">
          <?php //$featured_events_arr = g365_conn( 'g365_display_events', [65, 6] );
          if( !empty($featured_events_arr) ) foreach( $featured_events_arr as $dex => $obj ) : 
          ?>
          <div class="cell">
            <div class="small-margin-bottom">
              <a href="<?php echo $obj->link; ?>" target="_blank">
                <img src="<?php echo (!empty($obj->logo_img)) ? $obj->logo_img : $default_event_img ?>" alt="<?php echo $obj->name; ?> official logo" />
                <p>
                  <?php echo ( empty($obj->short_name) ) ? $obj->name : $obj->short_name; ?><br>	
                  <small class="tiny-margin-top block"><?php echo shb_build_dates($obj->dates); ?></small>
                </p>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <a class="button expanded no-margin-bottom" href="/calendar">Full Calendar</a>
      </div>
      <?php $shb_potm = get_post_meta($post->ID, 'shb_potm', true);
      if( !empty($shb_potm) ) : ?>
      <div class="widget-wrapper medium-margin-bottom">
        <div class="grid-x">
          <div class="cell">
            <img src="<?php echo $shb_potm; ?>" alt="Players of the month by region. <?php the_modified_date(); ?>" />
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php $shb_ctotm = get_post_meta($post->ID, 'shb_ctotm', true);
      if( !empty($shb_ctotm) ) : ?>
      <div class="widget-wrapper medium-margin-bottom">
        <div class="grid-x">
          <div class="cell">
            <img src="<?php echo $shb_ctotm; ?>" alt="Club Team of the month. <?php the_modified_date(); ?>" />
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php } //end default hero featured image section ?>

<section id="content" class="site-main small-padding-top xlarge-padding-bottom grid-container">
  
<?php //if we have page content
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php the_content(); ?>

<?php endwhile; endif; ?>

</section>

<?php
//if we have a splash graphic, add  the elements to support, part 1
if( !empty($shb_ad_info['splash']) ) echo $shb_ad_info['splash'];

get_footer();

//if we have a splash graphic, initialize it now that foundation() has started, part 2
if( !empty($shb_ad_info['splash']) ) echo '<script type="text/javascript">
    var shb_closed = localStorage.getItem("shb_close_today");
    var shb_closed_date = localStorage.getItem("shb_close_today_date");
    var shb_now_date = new Date();
    if( shb_closed_date !== null && new Date(shb_closed_date).getDate() !== shb_now_date.getDate() ) {
      localStorage.removeItem("shb_close_today");
      localStorage.removeItem("shb_close_today_date");
      shb_closed = null;
    }
    if( shb_closed === null ) {
      (function($){$("#shb_home_reveal").foundation("open");})(jQuery);
    }
  </script>';

// if( $tile_vid ) {
//   print_r(
//     '<script>
//       var tag = document.createElement("script");
//       tag.src = "https://youtube.com/iframe_api";
//       var firstScriptTag = document.getElementsByTagName("script")[0];
//       firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
//       var tile_players = ' . json_encode( $tile_video_settings) . ';
//       function onYouTubeIframeAPIReady() {
//         tile_players.forEach( function( vid_settings, dex ) {
//           vid_settings.data.events = {
//             "onReady": onPlayerReady,
//             "onStateChange": onPlayerStateChange
//           };
//           tile_players[dex]["video_ref"] = new YT.Player( vid_settings.id, vid_settings.data);
//         });
//       }
//        function onPlayerReady(event) {
//          event.target.playVideo();
//          event.target.mute();
//        }
//        function onPlayerStateChange(event) {
//         if( event.data === 0 ){
//          event.target.playVideo();
//         }
//        }
//     </script>'
//   );
// }

?>
  
  <script type=text/javascript>
    

$(document).ready(function() {
	        $('.herofadein').each( function(i){
	                $(this).animate({'opacity':'1'},1000);
          });
                            
	    $(window).scroll( function(){
          var scrollAmount;
          var logo =  $('.main-logo')
            scrollAmount = window.scrollY;
//             console.log(scrollAmount)
            if(scrollAmount > 600) {
              logo.animate({'opacity':'1'},1000);
            } 

        
	        $('.fadein').each( function(i){
	            var bottom_of_element = $(this).offset().top + $(this).outerHeight();
	            var bottom_of_window = $(window).scrollTop() + $(window).height();
	            if( bottom_of_window > bottom_of_element ){
	                $(this).animate({'opacity':'1'},1000);
	            }
	        });
	        $('.fadeinleft').each( function(i){
	            var bottom_of_element = $(this).offset().top + $(this).outerHeight();
	            var bottom_of_window = $(window).scrollTop() + $(window).height();
	            if( bottom_of_window > bottom_of_element ){
	                $(this).animate({'opacity':'1','margin-left':'0px'},1000);
	            }
	        });
	        $('.fadeinright').each( function(i){
	            var bottom_of_element = $(this).offset().top + $(this).outerHeight();
	            var bottom_of_window = $(window).scrollTop() + $(window).height();
	            if( bottom_of_window > bottom_of_element ){
	                $(this).animate({'opacity':'1','right':'0px'},1000);
	            }
	        });
	    });
	});
  </script>