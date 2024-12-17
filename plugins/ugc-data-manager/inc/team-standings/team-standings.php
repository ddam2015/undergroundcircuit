<?php
// <!-- cts: Club Team Standing -->
$select_year = $_POST['g365_year']; $select_lv_play = $_POST['lv_of_play']; $select_group = $_POST['group_lv']; $select_ev = $arg['ev_id'];
  if(!isset($select_year) && !isset($select_lv_play) && !isset($select_group)){ $select_year = wp_date('Y'); $select_group = '17U'; $select_lv_play = ''; }
  $g365_team_standings = g365_fn(['fn_name'=>'g365_team_standing', 'arguments'=>[['post_year'=>$select_year, 'post_ros_dvs'=>$select_lv_play, 'post_gp_lv'=>$select_group, 'is_year'=>$is_year, 'is_gp_lv'=>$is_gp_lv, 'is_dcp_ev'=>$select_ev, 'is_dcp_only'=>true], 'null']]);
$g365_team_standings = json_decode( json_encode($g365_team_standings), true); if(!empty($g365_team_standings[1])):/*main*/ echo $g365_team_standings[0]; foreach($g365_team_standings[1] as $level_index => $club_team_data): /*foreach-main*/?>
  <div id="dialong_div"></div>
  <div class="max-width-1200">
    <h5><?php echo ($g365_team_standings[3][$level_index].' '.$select_lv_play); ?></h5>
    <table class="cell cts_tb">
      <?php echo $g365_team_standings[2]; foreach($club_team_data as $index => $club_team_data_list): /*foreach-b*/ $box_score = $club_team_data_list['standing']; ?>
        <tr>
          <td>
            <div class="flex items-center">
              <span class="vr_btn small-margin-right" id="<?php echo $club_team_data_list['team_id'] ?>" onClick="view_result(this.id)">View Box Score</span>
              <span class="small-margin-right">
                <img style="height:25px;width:35px;" alt="<?php echo $club_team_data_list['full_team_name']; ?>" title="<?php echo $club_team_data_list['full_team_name']; ?>" src="<?php echo (!empty($club_team_data_list['org_logo']) ? $club_team_data_list['org_logo'] != "NULL" ? $g365_team_standings[5]['org_logo'].$club_team_data_list['org_logo'] : $g365_team_standings[5]['placeholder_img'] : $g365_team_standings[5]['placeholder_img']); ?>">
              </span>
              <span><?php echo $club_team_data_list['full_team_name']; ?></span>
            </div>
          </td>
          <td><?php echo !empty($club_team_data_list['win']) ? round($club_team_data_list['win'], 2) : '0'; ?></td>
          <td><?php echo !empty($club_team_data_list['loss']) ? round($club_team_data_list['loss'], 2) : '0'; ?></td>
          <td><?php echo !empty($club_team_data_list['pct']) ? round((float)(number_format($club_team_data_list['pct'], 3)) * 100 ) . '%' : '0%'; ?></td>
          <td><?php echo !empty($club_team_data_list['ppg']) ? number_format(round($club_team_data_list['ppg'], 1), 1) : '0'; ?></td>
          <td><?php echo !empty($club_team_data_list['opp_ppg']) ? number_format(round($club_team_data_list['opp_ppg'], 1), 1) : '0'; ?></td>
        </tr>
        <tr id="<?php echo $club_team_data_list['team_id'] ?>-result_box" class="result_box">
          <td colspan="6">
            <span class="close_vr_btn small-margin-right" id="<?php echo $club_team_data_list['team_id'] ?>" onClick="view_result(this.id)">Close</span>
            <div class="grid-x cts_box_score small-12 medium-12 large-12">
              <?php
                $box_score = json_decode('['.$box_score.']', true);
                $group_by_events = array();
                foreach($box_score as $data_list){$group_by_events[$data_list['event_name']][] = $data_list;}
                foreach($group_by_events as $index=> $group_by_event): //a
                  echo '<h5 class="small-12 medium-12 large-12 text-center" style="text-decoration:underline">'.$index.'</h5>';
                  foreach($group_by_event as $boxscore_list): //echo "<pre>";print_r($boxscore_list); echo "</pre>"; //b
                    if(!empty($boxscore_list)): //c
                      if($boxscore_list['gm_r_label'] == "W"){
                        $gm_result_color = 'style="color:blue; font-weight:bold"';
                      }else{
                        $gm_result_color = 'style="color:red; font-weight:bold"';
                      }?>
                      <div class="stats_customize cts_res flex items-center small-margin-bottom small-12 medium-12 large-12">
                        <div class="team_logo_box hide-for-small-only">
                          <a href="<?php echo $g365_team_standings[5]['g365_url'].'/club/'.$boxscore_list['org_nickname'].'/teams'; ?>" target="_blank"><img style="height:100px;width:125px;" alt="<?php echo $club_team_data_list['full_team_name']; ?>" title="<?php echo $club_team_data_list['full_team_name']; ?>" src="<?php echo (!empty($club_team_data_list['org_logo']) ? $club_team_data_list['org_logo'] != "NULL" ? $g365_team_standings[5]['org_logo'].$club_team_data_list['org_logo'] : $g365_team_standings[5]['placeholder_img'] : $g365_team_standings[5]['placeholder_img']); ?>"></a>
                        </div>
                        <div class="grid-x cts_res_box align-center">
                          <div class="small-4 medium-4 large-2 large-offset-2">
                            <span class="wrap-text--200 small-4 medium-4 large-4"><?php echo $club_team_data_list['full_team_name']; ?></span>
                          </div>
                          <div class="grid-x small-4 medium-4 large-4 align-center">
                            <span class="small-padding-right small-12 medium-12 large-12" <?php echo $gm_result_color; ?>><?php echo $boxscore_list['game_result']; ?></span>
<!--                             <button class="buttonization small-12 medium-12 large-12" style="max-width:100px; font-size:12px;padding:10px;max-height:35px" onClick="pl_box_score(this)" data-select-year="<?php echo $select_year ?>" data-event-name="<?php echo $boxscore_list['event_name'] ?>" data-game-id="<?php echo $boxscore_list['game_id'] ?>" data-team-id="<?php echo $club_team_data_list['team_id'] ?>" data-url="<?php echo $g365_team_standings[5]['g365_url']; ?>"> Box Score</button> -->
                            <a href="https://grassroots365.com/club-team-standing/team-box-score/_/_/<?php echo $club_team_data_list['team_id'] ?>/<?php echo $boxscore_list['game_id'] ?>/<?php echo $select_year ?>/" target="_blank"><button class="buttonization small-12 medium-12 large-12 small-margin" style="max-width:100px; font-size:12px;padding:10px;max-height:35px" data-select-year="<?php echo $select_year ?>" data-event-name="<?php echo $boxscore_list['event_name'] ?>" data-game-id="<?php echo $boxscore_list['game_id'] ?>" data-team-id="<?php echo $club_team_data_list['team_id'] ?>" data-url="<?php echo $g365_team_standings[5]['g365_url']; ?>"> Box Score</button></a>
                          </div>
                          <div class="grid-x small-4 medium-4 large-4">
                            <span class="large-8 end"><?php echo $boxscore_list['opp_name']; ?></span>
                          </div>
                        </div>
                        <div class="opp_logo_box hide-for-small-only">
                          <a href="<?php echo $g365_team_standings[5]['g365_url'].'/club/'.$boxscore_list['opp_nickname'].'/teams'; ?>" target="_blank"><img style="height:100px;width:125px;" alt="<?php echo (empty($boxscore_list['full_team_name']) ? "" : $boxscore_list['full_team_name']); ?>" title="<?php echo (empty($boxscore_list['full_team_name']) ? "" : $boxscore_list['full_team_name']); ?>" src="<?php echo (!empty($boxscore_list['opp_logo']) ? $boxscore_list['opp_logo'] != "NULL" ? $g365_team_standings[5]['org_logo'].$boxscore_list['opp_logo'] : $g365_team_standings[5]['placeholder_img'] : $g365_team_standings[5]['placeholder_img']); ?>"></a>
                        </div>
                      </div>
                    <?php else: echo ("<p>".$g365_team_standings[6]."</p>"); endif; //c
                  endforeach;// b
                endforeach;// a
              ?>
            </div>
          </td>
        </tr>
      <?php endforeach;/*endforeach-b*/ ?>
    </table>
  </div>
<?php endforeach;/*endforeach-main*/ else: echo ('<h4 class="text-center">'.$g365_team_standings[7].'</h4>'); endif;/*endif-main*/ echo $g365_team_standings[4]; ?>