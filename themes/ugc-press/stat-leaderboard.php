<?php 
 /**
 * Template Name: Stat Leaderboard
 * Author: Daradona Dam
 * Version: 2.0
 */
get_header();
global $wp_query; if(!empty($wp_query->query_vars['pg_type'])){ $pg_type = $wp_query->query_vars['pg_type']; }else{ $pg_type = ''; }
// $headerbg = 'https://thestagecircuit.com/wp-content/uploads/2022/03/stage-stat-img-placeholder.png'; 
$current_month = wp_date('m');
$current_year = wp_date('Y');

if ($current_month > 8) {
    $season_year = $current_year . '-' . ($current_year + 1);
} else {
    $season_year = ($current_year - 1) . '-' . $current_year;
}
?>
<div class="grid-container">
  <div class="full_width_container small-12 medium-12 large-12 medium-padding-top">
    <div class="stat-header__wrap large-margin-bottom">
<!--       <img src="<?php //echo $headerbg ?>" class="stat-header__img" alt="stat-header image -->
      <div class="stat-header__info">
        <h1 class="stat-header__heading">Stat Leaderboard <?php echo $season_year;?></h1>
      </div>
    </div>
  </div>
  <?php 
    switch( $pg_type ){
      case '':
      case 'all-stage-events':
      case 'event': ugc_dir_render('stat-leaderboard','by-event', '', $arg = null); break;
    }
  ?>
  <ul class="accordion xlarge-margin-top" data-accordion data-allow-all-closed="true">
    <li class="accordion-item" data-accordion-item>
      <a href="#" class="accordion-title disclaimer--stat">Stat Disclaimer</a>
      <div class="accordion-content" data-tab-content>
          <p>We make every effort to provide the most accurate stats possible. However, a number of factors including but not limited to duplicate jerseys and the subjective nature of certain basketball stats, prevent us from 100% accuracy. The stats we provide are intended to be a metric to track progress 
          and reward achievement over the course of a youth basketball career while providing a more robust experience for players and teams. It is not possible to provide this experience without some margin of error. We will do our best to provide the most accurate statistical data 
          possible, but we reserve the right to refuse any request to update statistics.
          </p>
      </div>
    </li>
  </ul>
</div>
<?php get_footer(); ?>