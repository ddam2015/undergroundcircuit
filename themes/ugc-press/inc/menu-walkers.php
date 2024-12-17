<?php
/**
 * Customize the output of menus for Foundation top bar
 * classes to active searches: col-small-small-margin-top col-small-12 col-medium-6 col-divider livesearch player-profiles
**/
if ( ! class_exists( 'shb_Top_Bar_Walker' ) ) :
class shb_Top_Bar_Walker extends Walker_Nav_Menu {
	private $curItem;
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent<ul class=\"menu vertical\">\n";
				$output .= "\n$indent<li>";
				$output .= "\n$indent<div class=\"grid-x grid-margin-x\">";
				$output .= "\n$indent<div class=\"cell\">";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
		}
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</li>\n";
				$output .= "\n$indent</ul>\n";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
		}
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		//this annonyamous function builds the html class string
		$shb_make_class = function( $classes_array, $item, $args ) {
			if( empty($classes_array) ) return;
			return ( ' class="' . esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes_array ), $item, $args ) ) ) . '"' );
		};
		//this annonyamous function builds the actual menu links
		$shb_make_link = function( $atts, $args, $itemtitle, $itemID, $extra_data, $before_link = '' ) {
//         print_r($atts);
			$attributes = '';
			//make all the attirbutes for a link element
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					//if we are processing the url use a different sanitization function
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			//add any before info from the wp_nav function
			$item_output = $args->before;
			//build opening anchor tag
			$item_output .= '<a'. $attributes .'>';
			// This filter is documented in wp-includes/post-template.php
			$item_output .= $args->link_before . $before_link . apply_filters( 'the_title', $itemtitle, $itemID ) . $args->link_after;
      
// 			if( $item->description != '' ) {
// 				$item_output .= ' - ' . $item->description;
// 			}
      if( $extra_data !== null ) $item_output .= '<small class="block subheader">' . $extra_data . '</small>';
			//add closing anchor tag
			$item_output .= '</a>';
			//add any after info from the wp_nav function
			$item_output .= $args->after;
			return $item_output;
		};
		$shb_sort_classes = function( $all_classes ) {
			$classes = (object) array(
				'col' => array(),
				'li' => array(),
				'anc' => array(),
				'divider' => false
			);
			foreach( $all_classes as $dex => $class ) {
				if( strpos($class, 'col-') !== false ) {
					if( strpos($class, 'divider') !== false ) {
						$classes->divider = true;
						continue;
					}
					$classes->col[] = str_replace( 'col-', '', $class );
				} elseif( strpos($class, 'anc-') !== false ) {
					$classes->anc[] = str_replace( 'anc-', '', $class );
				} else {
          $classes->li[] = $class;
        }
			}
			$classes->col[] = 'cell';
			return $classes;
		};
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//collect and format all the attibutes for each link element
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		//collect this item's attrubute data
		$atts = array(
			'title'  => !empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => !empty( $item->target )     ? $item->target     : '',
			'rel'    => !empty( $item->xfn )        ? $item->xfn        : '',
			'href'   => !empty( $item->url )        ? $item->url        : ''
		);
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		//figure out what content we need to add for each element
		switch($depth) {
			case 0:
				$all_classes = $shb_sort_classes( $item->classes );
        if( !empty($all_classes->anc) ){
          if( empty($atts['class']) ) $atts["class"] = array();
          $atts['class'] = implode(' ', array_merge($atts['class'],$all_classes->anc));
        }
				//start with a li tag
				$output .= $indent . '<li' . $id . $shb_make_class( $all_classes->li, $item, $args ) . ">\n";
				//add the link
				$item_output = $shb_make_link( $atts, $args, $item->title, $item->ID, null );
				break;
			case 1:
				//is this a column
				if( !empty(array_filter($item->classes, function($check_class) {return (strpos($check_class, 'col-') !== false ? true : false);})) ) {
					$all_classes = $shb_sort_classes( $item->classes );
					$item->classes = $all_classes->li;
					if( $all_classes->divider ) $output .=  "$indent\n</div>$indent\n<div" . $shb_make_class( $all_classes->col, $item, $args ) . ">\n";
				}
        $switch_to_img_header = false;
        if( in_array('sig-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="" />'; $switch_to_img_header = true; }
        if( in_array('nat-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="" />'; $switch_to_img_header = true; }
				if( $args->walker->has_children ) {
					$item->classes[] = 'nav-title' . ( ($switch_to_img_header) ? ' hide' : '');
					if( !empty($item->title) ) $output .= $indent . '<h4' . $shb_make_class( $item->classes, $item, $args ) . '>' . apply_filters( 'the_title', $item->title, $item->ID ) . "</h4>\n";
				} elseif( in_array('livesearch', $item->classes) ) {
					$output .= "$indent<div" . $id . $shb_make_class( $item->classes, $item, $args ) . ">";
					if( in_array('club-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Club Team Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="club_profiles" placeholder="Enter Team Name" autocomplete="off">';
					} elseif( in_array('player-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Player Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="player_profiles" placeholder="Enter Player Name" autocomplete="off">';
					} else {
						$output .= "\n$indent<span>&nbsp;</span>";
					}
					$output .= "\n$indent</div>\n";
				} else {
					$output .= "$indent<div" . $id . $shb_make_class( $item->classes, $item, $args ) . ">";
          $extra_data = null;
          $before_link = '';
          if( $item->object === 'product' ) {
            $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
            if( $product_event_link !== 0 ) {
              $product_event = g365_conn( 'g365_get_event_data', [$product_event_link, true] );
              if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $before_link .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
              $product_data = array();
              if( !empty($product_event->dates) ) $product_data[] = shb_build_dates($product_event->dates, 1, true);
              $this_location = (in_array('loc-abbr', $item->classes) && !empty($product_event->short_locations)) ? $product_event->short_locations : $product_event->locations;
              if( !empty($this_location) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $this_location)));
              if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
            }
          }
					$output .= $shb_make_link( $atts, $args, $item->title, $item->ID, $extra_data, $before_link );
					$output .= "\n$indent</div>\n";
				}
				$item_output = '';
				break;
			default:
				//start with a li tag
				$output .= $indent . '<div' . $id . $shb_make_class( $item->classes, $item, $args ) . ">";
        $extra_data = null;
        if( $item->object === 'product' ) {
          $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
          if( $product_event_link !== 0 ) {
            $product_event = g365_conn( 'g365_get_event_data', [$product_event_link, true] );
            if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $output .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
            $product_data = array();
            if( !empty($product_event->dates) ) $product_data[] = shb_build_dates($product_event->dates, 1, true);
            $this_location = (in_array('loc-abbr', $item->classes) && !empty($product_event->short_locations)) ? $product_event->short_locations : $product_event->locations;
            if( !empty($this_location) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $this_location)));
            if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
          }
        }
				//add the link
				$item_output = $shb_make_link( $atts, $args, $item->title, $item->ID, $extra_data );
				break;
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		switch($depth) {
			case 0:
				$output .= "</li>\n";
				break;
			case 1:
				break;
			default:
				$output .= "\n$indent</div>\n";
				break;
		}
	}
}
endif;

if ( ! class_exists( 'shb_Mobile_Walker' ) ) :
class shb_Mobile_Walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"nested\">\n";
	}
}
endif;

if ( ! class_exists( 'shb_Mega_Walker' ) ) :
class shb_Mega_Walker extends Walker_Nav_Menu {
	private $curItem;
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent<ul class=\"menu vertical\">\n";
				$output .= "\n$indent<li>";
				$output .= "\n$indent<div class=\"grid-x grid-margin-x\">";
				$output .= "\n$indent<div class=\"cell\">";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
		}
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</li>\n";
				$output .= "\n$indent</ul>\n";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
		}
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
//     if($item->object === 'product') {}
		//this annonyamous function builds the html class string
		$shb_make_class = function( $classes_array, $item, $args ) {
			if( empty($classes_array) ) return;
			return ( ' class="' . esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes_array ), $item, $args ) ) ) . '"' );
		};
		//this annonyamous function builds the actual menu links
		$shb_make_link = function( $atts, $args, $itemtitle, $itemID, $extra_data, $before_link = '' ) {
//         print_r($atts);
			$attributes = '';
			//make all the attirbutes for a link element
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					//if we are processing the url use a different sanitization function
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			//add any before info from the wp_nav function
			$item_output = $args->before;
			//build opening anchor tag
			$item_output .= '<a'. $attributes .'>';
			// This filter is documented in wp-includes/post-template.php
			$item_output .= $args->link_before . $before_link . apply_filters( 'the_title', $itemtitle, $itemID ) . $args->link_after;
      
// 			if( $item->description != '' ) {
// 				$item_output .= ' - ' . $item->description;
// 			}
      if( $extra_data !== null ) $item_output .= '<small class="block subheader">' . $extra_data . '</small>';
			//add closing anchor tag
			$item_output .= '</a>';
			//add any after info from the wp_nav function
			$item_output .= $args->after;
			return $item_output;
		};
		$shb_sort_classes = function( $all_classes ) {
			$classes = (object) array(
				'col' => array(),
				'li' => array(),
				'anc' => array(),
				'divider' => false
			);
			foreach( $all_classes as $dex => $class ) {
				if( strpos($class, 'col-') !== false ) {
					if( strpos($class, 'divider') !== false ) {
						$classes->divider = true;
						continue;
					}
					$classes->col[] = str_replace( 'col-', '', $class );
				} elseif( strpos($class, 'anc-') !== false ) {
					$classes->anc[] = str_replace( 'anc-', '', $class );
				} else {
          $classes->li[] = $class;
				}
			}
			return $classes;
		};
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//collect and format all the attibutes for each link element
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		//collect this item's attrubute data
		$atts = array(
			'title'  => !empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => !empty( $item->target )     ? $item->target     : '',
			'rel'    => !empty( $item->xfn )        ? $item->xfn        : '',
			'href'   => !empty( $item->url )        ? $item->url        : ''
		);
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		//figure out what content we need to add for each element
		switch($depth) {
			case 0:
        $all_classes = $shb_sort_classes( $item->classes );
        if( empty($all_classes->col) ) {
          $li_classes = $all_classes->li;
          $atts['class'] = [];
        } else {
          $li_classes = $all_classes->col;
          $atts['class'] = $all_classes->li;
        }
        if( !empty($all_classes->anc) ){
          if( empty($atts['class']) ) $atts["class"] = array();
          $atts['class'] = implode(' ', array_merge($atts['class'],$all_classes->anc));
        }
				//start with a li tag
				$output .= $indent . '<li' . $id . $shb_make_class( $li_classes, $item, $args ) . ">\n";
				//add the link
        if( empty($atts['href']) ) {
          $item_output = $indent . '<h4' . $shb_make_class( $atts['class'], $item, $args ) . '>' . apply_filters( 'the_title', $item->title, $item->ID ) . "</h4>\n";
        } else {
          $atts['class'] = esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $atts['class'] ), $item, $args ) ) );
          $item_output = $shb_make_link( $atts, $args, $item->title, $item->ID, null );
        }
				break;
			case 1:
				//is this a column
				if( !empty(array_filter($item->classes, function($check_class) {return (strpos($check_class, 'col-') !== false ? true : false);})) ) {
					$all_classes = $shb_sort_classes( $item->classes );
					$item->classes = $all_classes->li;
          $all_classes->col[] = 'cell';
					if( $all_classes->divider ) $output .=  "$indent\n</div>$indent\n<div" . $shb_make_class( $all_classes->col, $item, $args ) . ">\n";
				}
        $switch_to_img_header = false;
        if( in_array('sig-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="" />'; $switch_to_img_header = true; }
        if( in_array('nat-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="" />'; $switch_to_img_header = true; }
				if( $args->walker->has_children ) {
					$item->classes[] = 'nav-title' . ( ($switch_to_img_header) ? ' hide' : '');
					if( !empty($item->title) ) $output .= $indent . '<h5' . $shb_make_class( $item->classes, $item, $args ) . '>' . apply_filters( 'the_title', $item->title, $item->ID ) . "</h5>\n";
				} elseif( in_array('livesearch', $item->classes) ) {
					$output .= "$indent<div" . $id . $shb_make_class( $item->classes, $item, $args ) . ">";
					if( in_array('club-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Club Team Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="club_profiles" placeholder="Enter Team Name" autocomplete="off">';
					} elseif( in_array('player-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Player Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="player_profiles" placeholder="Enter Player Name" autocomplete="off">';
					} else {
						$output .= "\n$indent<span>&nbsp;</span>";
					}
					$output .= "\n$indent</div>\n";
				} else {
					$output .= "$indent<div" . $id . $shb_make_class( $item->classes, $item, $args ) . ">";
          $extra_data = null;
          $before_link = '';
          if( $item->object === 'product' ) {
            $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
            if( $product_event_link !== 0 ) {
              $product_event = g365_conn( 'g365_get_event_data', [$product_event_link, true] );
              if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $before_link .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
              $product_data = array();
              if( !empty($product_event->dates) ) $product_data[] = shb_build_dates($product_event->dates, 1, true);
              if( !empty($product_event->locations) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $product_event->locations)));
              if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
            }
          }
					$output .= $shb_make_link( $atts, $args, $item->title, $item->ID, $extra_data, $before_link );
					$output .= "\n$indent</div>\n";
				}
				$item_output = '';
				break;
			default:
				//start with a li tag
				$output .= $indent . '<div' . $id . $shb_make_class( $item->classes, $item, $args ) . ">";
        $extra_data = null;
        if( $item->object === 'product' ) {
          $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
          if( $product_event_link !== 0 ) {
            $product_event = g365_conn( 'g365_get_event_data', [$product_event_link, true] );
            if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $output .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
            $product_data = array();
            if( !empty($product_event->dates) ) $product_data[] = shb_build_dates($product_event->dates, 1, true);
            if( !empty($product_event->locations) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $product_event->locations)));
            if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
          }
        }
				//add the link
				$item_output = $shb_make_link( $atts, $args, $item->title, $item->ID, $extra_data );
				break;
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		switch($depth) {
			case 0:
				$output .= "</li>\n";
				break;
			case 1:
				break;
			default:
				$output .= "\n$indent</div>\n";
				break;
		}
	}
}
endif;

// Get menu description as global variable
function add_menu_description( $item_output, $item, $depth, $args ) {
    global $parent_title;
    $parent_title = ( $depth === 0 ) ? $item->title : '';
    return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'add_menu_description', 10, 4);


if ( ! class_exists( 'shb_Side_Slide_Walker' ) ) :
class shb_Side_Slide_Walker extends Walker_Nav_Menu {
	private $curItem;
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
        global $parent_title;
				$output .= "\n$indent<ul class=\"menu vertical\"" . (( !empty($parent_title) ) ? " data-parent_title=\"" . $parent_title . "\"" :  "" ) . ">\n";
				$output .= "\n$indent<li>";
				$output .= "\n$indent<div class=\"grid-x grid-margin-x\">";
				$output .= "\n$indent<div class=\"cell\">";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
		}
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</li>\n";
				$output .= "\n$indent</ul>\n";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
		}
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
//     if($item->object === 'product') {}
		//this annonyamous function builds the html class string
		$shb_make_class = function( $classes_array, $item, $args ) {
			if( empty($classes_array) ) return;
			return ( ' class="' . esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes_array ), $item, $args ) ) ) . '"' );
		};
		//this annonyamous function builds the actual menu links
		$shb_make_link = function( $atts, $args, $itemtitle, $itemID, $extra_data, $before_link = '' ) {
//         print_r($atts);
			$attributes = '';
			//make all the attirbutes for a link element
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					//if we are processing the url use a different sanitization function
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			//add any before info from the wp_nav function
			$item_output = $args->before;
			//build opening anchor tag
			$item_output .= '<a'. $attributes .'>';
			// This filter is documented in wp-includes/post-template.php
			$item_output .= $args->link_before . $before_link . apply_filters( 'the_title', $itemtitle, $itemID ) . $args->link_after;
      
// 			if( $item->description != '' ) {
// 				$item_output .= ' - ' . $item->description;
// 			}
      if( $extra_data !== null ) $item_output .= '<small class="block subheader">' . $extra_data . '</small>';
			//add closing anchor tag
			$item_output .= '</a>';
			//add any after info from the wp_nav function
			$item_output .= $args->after;
			return $item_output;
		};
		$shb_sort_classes = function( $all_classes ) {
			$classes = (object) array(
				'col' => array(),
				'li' => array(),
				'anc' => array(),
				'divider' => false
			);
			foreach( $all_classes as $dex => $class ) {
				if( strpos($class, 'col-') !== false ) {
					if( strpos($class, 'divider') !== false ) {
						$classes->divider = true;
						continue;
					}
					$classes->col[] = str_replace( 'col-', '', $class );
				} elseif( strpos($class, 'anc-') !== false ) {
					$classes->anc[] = str_replace( 'anc-', '', $class );
				} else {
          $classes->li[] = $class;
				}
			}
			return $classes;
		};
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//collect and format all the attibutes for each link element
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		//collect this item's attrubute data
		$atts = array(
			'title'  => !empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => !empty( $item->target )     ? $item->target     : '',
			'rel'    => !empty( $item->xfn )        ? $item->xfn        : '',
			'href'   => !empty( $item->url )        ? $item->url        : ''
		);
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
    //item title
    $item_title = $item->title;
    $before_link = '';
		//figure out what content we need to add for each element
		switch($depth) {
			case 0:
        $all_classes = $shb_sort_classes( $item->classes );
        if( empty($all_classes->col) ) {
          $li_classes = $all_classes->li;
          $atts['class'] = [];
        } else {
          $li_classes = $all_classes->col;
          $atts['class'] = $all_classes->li;
        }
        if( !empty($all_classes->anc) ){
          if( empty($atts['class']) ) $atts["class"] = array();
          $atts['class'] = array_merge($atts['class'],$all_classes->anc);
        }
				//start with a li tag
				$output .= $indent . '<li' . $id . $shb_make_class( $li_classes, $item, $args ) . ">\n";
				//add the link
        
        if( empty($atts['href']) ) {
          $item_output = $indent . '<a' . $shb_make_class( $atts['class'], $item, $args ) . '>' . apply_filters( 'the_title', $item_title, $item->ID ) . "</a>\n";
        } else {
          $atts['class'] = esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $atts['class'] ), $item, $args ) ) );
          $item_output = $shb_make_link( $atts, $args, $item_title, $item->ID, null );
        }
				break;
			case 1:
				//is this a column
				if( !empty(array_filter($item->classes, function($check_class) {return (strpos($check_class, 'col-') !== false ? true : false);})) ) {
					$all_classes = $shb_sort_classes( $item->classes );
					$item->classes = $all_classes->li;
          $all_classes->col[] = 'cell';
					if( $all_classes->divider ) $output .=  "$indent\n</div>$indent\n<div" . $shb_make_class( $all_classes->col, $item, $args ) . ">\n";
				}
        $switch_to_img_header = false;
        if( in_array('nat-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="" />'; $switch_to_img_header = true; }
				if( $args->walker->has_children ) {
					$item->classes[] = 'nav-title' . ( ($switch_to_img_header) ? ' hide' : '');
					if( !empty($item_title) ) $output .= $indent . '<h5' . $shb_make_class( $item->classes, $item, $args ) . '>' . apply_filters( 'the_title', $item_title, $item->ID ) . "</h5>\n";
				} elseif( in_array('livesearch', $item->classes) ) {
					$output .= "$indent<div" . $id . $shb_make_class( $item->classes, $item, $args ) . ">";
					if( in_array('club-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Club Team Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="club_profiles" placeholder="Enter Team Name" autocomplete="off">';
					} elseif( in_array('player-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Player Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="player_profiles" placeholder="Enter Player Name" autocomplete="off">';
					} else {
						$output .= "\n$indent<span>&nbsp;</span>";
					}
					$output .= "\n$indent</div>\n";
				} else {
          if( in_array('event-menu-button', $item->classes) ) {
            $ev_target = array_filter($item->classes, function($value) { return strpos($value, 'ev-target-') !== false; });
            if( count($ev_target) === 1 ) $data_tag_info = ' data-ev-target="' . substr(array_values($ev_target)[0], 10) . '"';
          } 
					$output .= "$indent<div" . $id . $shb_make_class( $item->classes, $item, $args ) . $data_tag_info . ">";
          //see if we are loading the events menu
          if( in_array('event-menu-season', $item->classes) ) {
            $event_menu = get_transient( 'menu-cache-event-menu-season' );
            if ( false === $event_menu ) {
              $event_menu = g365_conn( 'g365_event_menu_season_nav', [true] ); //returns as an array
              if( is_array($event_menu) ) $event_menu = $event_menu[0];
              set_transient( 'menu-cache-event-menu-season', $event_menu, 15552000 );
            }
            $output .= ( empty($event_menu) ) ? ('<p class="error">Seasonal Event Menu Retrieval Error. ' . $event_menu . '.</p>') : $event_menu;
          } elseif( in_array('event-menu-region', $item->classes) ) {
            $event_menu = get_transient( 'menu-cache-event-menu-region' );
            if ( false === $event_menu ) {
              $event_menu = g365_conn( 'g365_event_menu_region_nav', [true] ); //returns as an array
              if( is_array($event_menu) ) $event_menu = $event_menu[0];
              set_transient( 'menu-cache-event-menu-region', $event_menu, 15552000 );
            }
            $output .= ( empty($event_menu) ) ? ('<p class="error">Regional Event Menu Retrieval Error. ' . $event_menu . '.</p>') : $event_menu;
          } else {
            $extra_data = '';
            if( $item->object === 'product' ) {
              //OLD AJAX first :(
              //$product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
              //g365_conn( 'g365_get_event_data', [$product_event_link, true] );
              $product_event = get_post_meta( $item->object_id, '_event_link_data', true );
              if( !empty($product_event) ) {
                if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $output .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
                $product_data = array( 'dates' => array(), 'locations' => array() );
                if( !empty($product_event->dates) ) $product_data['dates'][] = shb_build_dates($product_event->dates, ( in_array('title-date', $item->classes) ) ? 2 : 1, ( in_array('title-date', $item->classes) ) ? false : true);
                $this_location = (in_array('loc-abbr', $item->classes) && !empty($product_event->short_locations)) ? $product_event->short_locations : $product_event->locations;
                if( !empty($this_location) ) $product_data['locations'][] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $this_location)));
                //see if we need to add series date info
  //               $series_ids = get_post_meta( $item->object_id, '_series_ids', true );
  //               if( !empty( $series_ids ) && is_array($series_ids) ) {
  //                 $series_products = array();
  //                 $series_events = array();
  //                 $series_order = array();
  //                 $series_print_string = array();
  //   //                 $series_ids[] = $post->ID; // if we need to add the current product to the list
  //                 foreach ( $series_ids as $product_id ) {
  //                   $series_products[ $product_id ] = wc_get_product( $product_id );
  //                   $series_events[ $product_id ] = $series_products[ $product_id ]->get_meta( '_event_link' );
  //                   if( !empty($series_events[ $product_id ]) ) {
  //                     $series_events[ $product_id ] = array( 'data_pull' => g365_conn( 'g365_get_event_data', [$series_events[ $product_id ], true] ), 'label' => array(), 'dates' => array(), 'locations' => array() );
  //                     if( !empty($series_events[ $product_id ][ 'data_pull' ]) ) {
  //                       $series_order[ $product_id ] = $series_events[ $product_id ][ 'data_pull' ]->eventtime;
  //   //                       $series_events[ $product_id ][ 'label' ][] = $series_events[ $product_id ][ 'data_pull' ]->short_name; //if we need the product name
  //                       if( !empty($series_events[ $product_id ][ 'data_pull' ]->dates) ) $series_events[ $product_id ][ 'dates' ][] = shb_build_dates($series_events[ $product_id ][ 'data_pull' ]->dates, 1, true);
  //                       if( !empty($series_events[ $product_id ][ 'data_pull' ]->locations) ) $series_events[ $product_id ][ 'locations' ][] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $series_events[ $product_id ][ 'data_pull' ]->locations)));
  //                     }
  //                   }
  //                 }
  //                 asort( $series_order );
  //                 foreach ( $series_order as $product_id => $event_time ) {
  //                   $product_data['dates'] = array_merge($product_data['dates'], $series_events[ $product_id ][ 'dates' ]);
  //                   $product_data['locations'] = array_merge($product_data['locations'], $series_events[ $product_id ][ 'locations' ]);
  //                 }
  //               }
                if( in_array('title-date', $item->classes) && !empty($product_data['dates']) ) {
                  $item_title = implode(' | ', $product_data['dates']);
                  $product_data['dates'] = array();
                }
                if( !empty($product_data['dates']) ) $extra_data .= implode(' | ', $product_data['dates']);
                if( !empty($product_data['dates']) && !empty($product_data['locations']) ) $extra_data .= '<br>';
                if( !empty($product_data['locations']) ) $extra_data .= implode(' | ', array_unique($product_data['locations']));
              }
            }
            $output .= $shb_make_link( $atts, $args, $item_title, $item->ID, $extra_data, $before_link );
          }
          //close the row 
					$output .= "\n$indent</div>\n";
				}
				$item_output = '';
				break;
			default:
        
        if( $args->walker->has_children && empty($atts['href']) ) {
          $switch_to_img_header = false;
          $prod_id_logo = array_values(array_filter($item->classes, function($val){ return strpos($val, 'prod-logo-id-') !== false; }));
          if( !empty($prod_id_logo) ) {
            $product_id = intval(substr($prod_id_logo[0], 12));
            //OLD AJAX first :(
            //$product_event_link_logo = intval(get_post_meta( $prod_id, '_event_link', true ));
            //g365_conn( 'g365_get_event_data', [$product_event_link_logo, true] );
            $product_event = get_post_meta( $prod_id, '_event_link_data', true );
            if( !empty($product_event) ) {
              if( !empty($product_event->logo_img) ) {
                $output .= '<img class="menu-column-img" src="' . $product_event->logo_img . '" />';
                $switch_to_img_header = true;
              }
            }
          }
					$item->classes[] = 'nav-title';
          if($switch_to_img_header) $item->classes[] = 'hide';
					if( !empty($item_title) ) $output .= $indent . '<h5' . $shb_make_class( $item->classes, $item, $args ) . '>' . apply_filters( 'the_title', $item_title, $item->ID ) . "</h5>\n";
  				$item_output = '';
				} else {
          //start with a li tag
          $output .= $indent . '<div' . $id . $shb_make_class( $item->classes, $item, $args ) . ">";
          $extra_data = '';
          if( $item->object === 'product' ) {
            //OLD AJAX first :(
            //$product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
            //g365_conn( 'g365_get_event_data', [$product_event_link, true] );
            $product_event = get_post_meta( $item->object_id, '_event_link_data', true );
            if( !empty($product_event) ) {
              if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $before_link .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
//               if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $before_link .= '<img class="' . ( (in_array('enhance', $item->classes)) ? 'menu-column-img' : 'menu-line-img' ) . '" src="' . $product_event->logo_img . '" />';
              $product_data = array( 'dates' => array(), 'locations' => array() );
// with date abbreviations              if( !empty($product_event->dates) ) $product_data['dates'][] = shb_build_dates($product_event->dates, ( in_array('title-date', $item->classes) ) ? 2 : 1, ( in_array('title-date', $item->classes) ) ? false : true);
              if( !empty($product_event->dates) ) $product_data['dates'][] = shb_build_dates($product_event->dates, 2);
              $this_location = (in_array('loc-abbr', $item->classes) && !empty($product_event->short_locations)) ? $product_event->short_locations : $product_event->locations;
              if( !empty($this_location) ) $product_data['locations'][] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $this_location)));
              //see if we need to add series date info
  //             $series_ids = get_post_meta( $item->object_id, '_series_ids', true );
  //             if( !empty( $series_ids ) && is_array($series_ids) ) {
  //               $series_products = array();
  //               $series_events = array();
  //               $series_order = array();
  //               $series_print_string = array();
  // //                 $series_ids[] = $post->ID; // if we need to add the current product to the list
  //               foreach ( $series_ids as $product_id ) {
  //                 $series_products[ $product_id ] = wc_get_product( $product_id );
  //                 $series_events[ $product_id ] = $series_products[ $product_id ]->get_meta( '_event_link' );
  //                 if( !empty($series_events[ $product_id ]) ) {
  //                   $series_events[ $product_id ] = array( 'data_pull' => g365_conn( 'g365_get_event_data', [$series_events[ $product_id ], true] ), 'label' => array(), 'dates' => array(), 'locations' => array() );
  //                   if( !empty($series_events[ $product_id ][ 'data_pull' ]) ) {
  //                     $series_order[ $product_id ] = $series_events[ $product_id ][ 'data_pull' ]->eventtime;
  // //                       $series_events[ $product_id ][ 'label' ][] = $series_events[ $product_id ][ 'data_pull' ]->short_name; //if we need the product name
  //                     if( !empty($series_events[ $product_id ][ 'data_pull' ]->dates) ) $series_events[ $product_id ][ 'dates' ][] = shb_build_dates($series_events[ $product_id ][ 'data_pull' ]->dates, 1, true);
  //                     if( !empty($series_events[ $product_id ][ 'data_pull' ]->locations) ) $series_events[ $product_id ][ 'locations' ][] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $series_events[ $product_id ][ 'data_pull' ]->locations)));
  //                   }
  //                 }
  //               }
  //               asort( $series_order );
  //               foreach ( $series_order as $product_id => $event_time ) {
  //                 $product_data['dates'] = array_merge($product_data['dates'], $series_events[ $product_id ][ 'dates' ]);
  //                 $product_data['locations'] = array_merge($product_data['locations'], $series_events[ $product_id ][ 'locations' ]);
  //               }
  //             }
              //
              if( in_array('title-date', $item->classes) && !empty($product_data['dates']) ) {
                $item_title = implode(' | ', $product_data['dates']);
                $product_data['dates'] = array();
              }
              if( !empty($product_data['dates']) ) $extra_data .= implode(' | ', $product_data['dates']);
              if( !empty($product_data['dates']) && !empty($product_data['locations']) ) $extra_data .= '<br>';
              if( !empty($product_data['locations']) ) $extra_data .= implode(' | ', array_unique($product_data['locations']));
            }
          }
          //add the link
          $output .= $shb_make_link( $atts, $args, $item_title, $item->ID, $extra_data, $before_link );
          $output .= "\n$indent</div>\n";
        }
				$item_output = '';
				break;
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		switch($depth) {
			case 0:
				$output .= "</li>\n";
				break;
			case 1:
			default:
				break;
		}
	}
}
endif;