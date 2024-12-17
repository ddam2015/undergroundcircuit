<?php
 /**
 * Template Name: Team Standings
 * Author: Daradona Dam
 * Version: 1.0
 */
get_header();
$headerbg = 'http://thestagecircuit.com/wp-content/uploads/2022/03/stage-stat-img-placeholder.png'; ?>
<div class="grid-container">
  <div class="full_width_container small-12 medium-12 large-12 medium-padding-top">
    <div class="stat-header__wrap large-margin-bottom">
      <img src="<?php echo $headerbg ?>" class="stat-header__img" alt="stat-header image">
      <div class="stat-header__info">
        <h1 class="stat-header__heading">The Stage Team Standings 2024-2025</h1>
      </div>
    </div>
  </div>
<?php ugc_dir_render('team-standings','team-standings', '', $arg = null); get_footer(); ?>