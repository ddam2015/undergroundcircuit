<?php
/*
 *	Plugin Name: The Underground Circuit Data Management Framework
 *	Description: The Underground Circuit Data Management contains functionality to support The Underground Circuit website.
 *	Version: 1.0
 *	Author: Daradona Dam
 */
function ugc_dir_render($sub_dir = null, $file_name, $player_id = null, $arg = null){
  $dir = 'inc/'.$sub_dir.'/'.$file_name.'.php';
  include($dir);
  $output_dir = ob_get_contents();
  return $output_dir;
}
function g365_trans_data($arg = null, $type = null){
  $g365_event_data = g365_conn( 'g365_get_event_data', [$product_event_link, true] );
  return ['g365_event_data' => $g365_event_data];
}
function fav_insert($arg = null, $type = null){
  switch($type){
    case 'fav_insert':
      return '"'.wp_date('Y-m-d H:i:s').'","'.wp_date('Y-m-d H:i:s').'",DEFAULT,1,NULL,'.get_current_user_id().','.$arg['pl_id'].',"{\"notes\": \"'.$arg['pl_note'].'\"}","{\"img_link\": \"'.$arg['pl_img'].'\", \"pl_name\": \"'.$arg['pl_name'].'\", \"pl_nickname\": \"'.$arg['pl_nickname'].'\", \"grad_year\": \"'.$arg['pl_grad_year'].'\", \"position\": \"'.$arg['pl_position'].'\", \"height\": \"'.$arg['pl_height'].'\", \"gpa\": \"'.$arg['pl_gpa'].'\", \"sat\": \"'.$arg['pl_sat'].'\", \"contact_info\": \"'.$arg['pl_contact_info'].'\"}"';
      break;
  }
}
function ugc_g365_data_xfer($arg = null, $type = null){
  global $wpdb;
  $conn = mysqli_connect("localhost", "OkQTcxehx7sk", "ueUN8bXkkvWt6vqQ", "g365-dev-wp-Q1ZlLivS");
  $db_prefix = "wp_54ab678738_g365_";
  $g365_db_tb = $arg['db_tb'];
  $query_type = $arg['qn_type'];
  $cond = ""; $limit = ""; $order = "";
  switch($type){
    case 'INSERT':
      $field_val = $arg['insert_field_val'];
      $sql = "$type INTO $db_prefix$g365_db_tb VALUES ($field_val) ON DUPLICATE KEY UPDATE notes=VALUES(notes), enabled = 1, pl_data=VALUES(pl_data), updatetime = CURRENT_TIMESTAMP";
      mysqli_query($conn, $sql);
      return $sql;
      break;
    case 'SELECT':
      if(!empty($arg['limit'])){
        $limit = " LIMIT ".$arg['limit'];
      }else{$limit = " LIMIT 10 ";}
      switch($query_type){
        case '1': //With conditions: fav notes and fav list
          $order = " ORDER BY updatetime DESC, createdate DESC ";
          if(!empty($arg['player_id'])){
            $cond = "WHERE enabled = 1 AND user_id = ".$arg['user_id']." AND player_id = ".$arg['player_id'];
          }else{$cond = "WHERE enabled = 1 AND user_id = ".$arg['user_id'];}
          break;
      }
      $sql = " $type * FROM $db_prefix$g365_db_tb $cond $order $limit ";
      $result = mysqli_query($conn, $sql);
      $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
      return $result;  
      break;
//     case 'DELETE': // Delete record
//       $cond = " WHERE id = ".$arg['rec_id'];
//       $sql = " $type FROM $db_prefix$g365_db_tb $cond ";
//       mysqli_query($conn, $sql);
//       return $sql;
//       break;
     case 'DELETE': // Disable instead of delete the record
      $cond = " SET enabled = 0 WHERE id = ".$arg['rec_id'];
      $sql = " UPDATE $db_prefix$g365_db_tb $cond ";
      mysqli_query($conn, $sql);
      return $sql;
      break;
  }
      //Close connection
      mysqli_close($conn);
}
function ajax_data_xfer($arg = null, $type = null){
  $script = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
  $script .= '<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">';
  $script .= '<link rel="stylesheet" href="/resources/demos/style.css">';
  $script .= '<script src="https://code.jquery.com/jquery-3.6.0.js"></script>';
  $script .= '<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>';
  $class_name = $arg['class_name'];
  $dir_url = "../ajax-caller";
  switch($type){
    case 'add_fav':
      $vars = 'var pl_id = this.dataset.plId; var pl_name = this.dataset.plName; var pl_nickname = this.dataset.plNickname; var pl_grad_year = this.dataset.plGradYear; var pl_position = this.dataset.plPosition; var pl_height = this.dataset.plHeight; var pl_gpa = this.dataset.plGpa; var pl_sat = this.dataset.plSat; var pl_contact_info = this.dataset.plContactInfo; var pl_img = this.dataset.plImg; var dir_url = "'.$dir_url.'"; var pl_note = $("#note_"+pl_id).val();';
      $data_fields = 'post_type: "add_fav", pl_id: pl_id, pl_note: pl_note, pl_name: pl_name, pl_img: pl_img, pl_nickname: pl_nickname, pl_grad_year: pl_grad_year, pl_position: pl_position, pl_height: pl_height, pl_gpa: pl_gpa, pl_sat: pl_sat, pl_contact_info: pl_contact_info';
      break;
    case 'remove_fav':
      $vars = ' var rec_id = this.dataset.rmId; var dir_url = "'.$dir_url.'"; ';
      $data_fields = 'post_type: "remove_fav", rec_id: rec_id ';
      break;
  }
  $script .= 
    '<script>
      $(document).on("click", ".'.$class_name.'", function(){
        '.$vars.'
        $.ajax({ 
          url: dir_url,
          data: {'.$data_fields.'},
          type: "POST",
//           success: function() {alert(pl_grad_year);}
        });
      });
      function rm_fav(pointer){
        var rm_id = pointer.dataset.rmId;
        $("#"+rm_id).hide("slow");
      }
    </script>';
    return $script;
}
function fav_reveal($arg=null, $type=null){
  $js_fav = '
    <script>
      function fav_icon_animation(el){
        var id = el.dataset.plId;
        $("#"+id+" a").addClass("fav_animation");
        $("#"+id+" a").removeClass("btn-flip");
      }
    </script>
  ';
  switch($type){
    case 'add_fav':
      $fav_notes = array();
      foreach($arg["fav_data"] as $fav_info){$fav_note = json_decode($fav_info["notes"], true); $fav_notes[] = $fav_note['notes'];}
      $reveal_form = '
        <div class="reveal small fav_box" id="'.$arg["data_toggle"].'" data-reveal data-close-on-click="false" data-animation-in="slide-out-up" data-animation-out="spin-out">
          <h1>'.$arg['full_name'].'</h1>
          <p class="lead">Player image, information and note.</p>
          <textarea class="secondary button text-left" style="color:#000" id="'.$arg["data_note"].'" name="'.$arg["data_note"].'" rows="2" cols="50" placeholder="Leave a note for '.$arg["full_name"].'">'.$fav_notes[0].'</textarea>
          <button onClick="fav_icon_animation(this)" class="fav_pl success button no-margin-bottom" data-pl-id="'.$arg["pl_id"].'" data-pl-name="'.$arg["full_name"].'" data-pl-nickname="'.$arg["pl_nickname"].'" data-pl-img="'.$arg["pl_img"].'" data-pl-grad-year="'.$arg["pl_grad_year"].'" data-pl-position="'.$arg["pl_position"].'" data-pl-height="'.$arg["pl_height"].'" data-pl-gpa="'.$arg["pl_gpa"].'" data-pl-sat="'.$arg["pl_sat"].'" data-pl-contact-info="'.$arg["pl_contact_info"].'" data-close aria-label="Close reveal">Save</button>
          <button class="secondary button" data-close aria-label="Close reveal">Cancel</button>
        </div>
      ';
      break;
    case 'remove_fav':
      $reveal_form = '
        <div class="reveal small fav_box" id="'.$arg["data_toggle"].'" data-reveal data-close-on-click="false" data-animation-in="slide-out-up" data-animation-out="spin-out">
          <p class="lead medium-padding-bottom">Do you want to remove <span style="font-size:22px; font-weight:bolder; text-decoration:underline;">'.$arg['full_name'].'</span> from your favorite list?</p>
          <button class="rm_pl success button no-margin-bottom" data-rm-id="'.$arg["rec_id"].'" onClick="rm_fav(this)" data-close aria-label="Close reveal">Yes</button>
          <button class="secondary button" data-close aria-label="Close reveal">Cancel</button>
        </div>
      ';
      break;  
  }
  return $reveal_form;
}
add_filter( 'allowed_http_origins', 'add_allowed_origins' );
function add_allowed_origins( $origins ) {
  $origins[] = 'https://opengympremier.com';
  $origins[] = 'https://elitebasketballcircuit.com';
  $origins[] = 'https://grassroots365.com/';
  $origins[] = 'https://dev.opengympremier.com';
  $origins[] = 'https://dev.elitebasketballcircuit.com';
  $origins[] = 'https://dev.grassroots365.com/';
  $origins[] = 'https://dev.theundergroundcircuit.com';
  $origins[] = 'https://theundergroundcircuit.com/';
  return $origins;
}
function g365_fn($arg=null, $type=null){
  $fn_data = g365_conn( $arg['fn_name'], $arg['arguments'] );
  if(!empty($arg['decode'])){ $is_decoded = $arg['decode']; }else{ $is_decoded = ''; }
  if($is_decoded == true){
    $fn_data = json_decode(json_encode($fn_data), true);  
  }else{
    $fn_data = $fn_data;
  }
  return $fn_data;
}
function cdp_fav_pl_info($arg=null, $type=null){
  if(!empty($arg['pl_name'])){
    $pl_name = '<p>'.$arg['pl_name'].'</p>';
  }else{$pl_name="";}
  if(!empty($arg['grad_year'])){
    $grad_year = '<p>Class of '.$arg['grad_year'].'</p>';
  }else{$grad_year="";}
  if(!empty($arg['position'])){
    $position = '<p>Position: '.$arg['position'].'</p>';
  }else{$position="";}
  if(!empty($arg['height'])){
    $height = '<p>Height: '.$arg['height'].'</p>';
  }else{$height="";}
  if(!empty($arg['gpa'])){
    $gpa = '<p>GPA: '.$arg['gpa'].'</p>';
  }else{$gpa="";}
  if(!empty($arg['sat'])){
    $sat = '<p>SAT: '.$arg['sat'].'</p>';
  }else{$sat="";}
  if(!empty($arg['contact_info'])){
    $contact_info = '<p>Contact: '.$arg['contact_info'].'</p>';
  }else{$contact_info="";}
  switch($type){
    case 'pl_fav':
      return ''.$pl_name.''.$grad_year.''.$position.''.$height.''.$gpa.''.$sat.''.$contact_info.'';
      break;
    case 'hm_pl_fav':
      break;
  }
}
//format start and end date based on a 'pipe' separated string
function tsc_build_dates($dates, $type = 1, $abbv = false, $add_reg = false) {
	//date is undetermined, don't process
	if( strpos($dates, 'TBD') !== false ) return $dates;
  //set default timezone
  date_default_timezone_set('America/Los_Angeles');
	//if the event is only one day, cut most of the processing
  $start_date = $dates;
  //if the date does have the "|" jump to bottom
	if( strpos($dates, '|') !== false ) {
		$dates = explode('|', $dates);
    //if we want just the dates, only first and last
    if( $type === 5 ) {
      return array( date("m-d-y", strtotime($dates[0])), date("m-d-y", strtotime(end($dates))) );
    }
		$start_date = $dates[0];
    if( $type === 4 ) return $start_date;
		$end_date = end($dates);
		$start_month = explode(' ', $start_date);
		$end_month = explode(' ', $end_date);
		if( $start_month[0] != $end_month[0] ) {
			if( end($start_month) != end($end_month) ) {
        $dates = $start_date . ' - ' . $end_date . $type;
			} else {
        if( $type === 3 ){
          $dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . substr($end_month[1], 0, -1);
        } else {
          $dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . $end_month[0] . ' ' . substr($end_month[1], 0, -1);
        }
			}
		} else {
			$start_day = substr($start_month[1], 0, -1);
			$end_day = substr($end_month[1], 0, -1);
			if( $start_day == $end_day ) {
				$dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1);
			} else {
				$dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . substr($end_month[1], 0, -1);
			}
		}
		switch( $type ){
			case 1:
				break;
			case 2:
				$dates .= ', ' . end($end_month);
        $dates = preg_replace('/ \- /', '-', $dates);
				break;
			case 3:
				break;
		}
	} else {
		switch( $type ){
			case 1:
				$dates = explode(' ', $dates);
				if( strpos($dates[1], ',') !== false ) $dates[1] = substr($dates[1], 0, -1);
				$dates = $dates[0] . ' ' . $dates[1];
				break;
			case 2:
				break;
			case 3:
				$dates = explode(' ', $dates);
				if( strpos($dates[1], ',') !== false ) $dates[1] = substr($dates[1], 0, -1);
				$dates = $dates[0] . ' ' . $dates[1];
				break;
      case 4:
        return $dates;
        break;
		}
	}
  if( $abbv ) return preg_replace('/([A-Za-z]{3})( |.+? )/', '\1 ', $dates);
  if( $add_reg !== false ) {
    $registration_date = 'No registration deadline.';
    if( $add_reg !== 0 ) {
      $registration_date = date('F d, Y', strtotime('-' . intval($add_reg) . ' days', strtotime($start_date)));
    }
    return array($dates, $registration_date);
  }
	return $dates;
}