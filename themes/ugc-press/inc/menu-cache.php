<?php
/**
 * WordPress menu cache.
 *
 * @package BJ\Menu
 * @author bjornjohansen
 * @version 0.1.0
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GNU General Public License version 2 (GPLv2)
 */
/**
 * Short-circuit the wp_nav_menu() output if we have cached output ready.
 *
 * Returning a non-null value to the filter will short-circuit
 * wp_nav_menu(), echoing that value if $args->echo is true,
 * returning that value otherwise.
 *
 * @see wp_nav_menu()
 *
 * @param string|null $output Nav menu output to short-circuit with. Default null.
 * @param stdClass    $args   An object containing wp_nav_menu() arguments.
 * @return string|null Nav menu output to short-circuit with. Passthrough (default null) if we don’t have a cached version.
 */
add_filter( 'pre_wp_nav_menu', function( $output, $args ) {
	/* This section is from wp_nav_menu(). It is here to find a menu when none is provided. */
	// @codingStandardsIgnoreStart
	
	// Get the nav menu based on the requested menu
	$menu = wp_get_nav_menu_object( $args->menu );
	// Get the nav menu based on the theme_location
	if ( ! $menu && $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) )
		$menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );
	// get the first menu that has items if we still can't find a menu
	if ( ! $menu && !$args->theme_location ) {
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu_maybe ) {
			if ( $menu_items = wp_get_nav_menu_items( $menu_maybe->term_id, array( 'update_post_term_cache' => false ) ) ) {
				$menu = $menu_maybe;
				break;
			}
		}
	}
	if ( empty( $args->menu ) ) {
		$args->menu = $menu;
	}
	// @codingStandardsIgnoreEnd
	/* End of the section from wp_nav_menu(). It was a pleasure, ladies and gents. */
	global $wp_query;
	$menu_signature = md5( wp_json_encode( $args ) . $wp_query->query_vars_hash );
	// We don’t actually need the references to all the cached versions of this menu,
	// but we need to make sure the cache is not out of sync - transients are unreliable.
	$cached_versions = get_transient( 'menu-cache-menuid-' . $args->menu->term_id );
	if ( false !== $cached_versions ) {
		$cached_output = get_transient( 'menu-cache-menuid-' . $args->menu->term_id . '-' . $menu_signature );
		if ( false !== $cached_output ) {
			$output = $cached_output;
		}
	}
	return $output;
}, 10, 2 );
/**
 * Cache the HTML content output for navigation menus.
 *
 * @see wp_nav_menu()
 *
 * @param string   $nav_menu The HTML content for the navigation menu.
 * @param stdClass $args     An object containing wp_nav_menu() arguments.
 * @return string The HTML content for the navigation menu.
 */
add_filter( 'wp_nav_menu', function( $nav_menu, $args ) {
	global $wp_query;
  if ( isset( $args->walker ) && gettype($args->walker) === 'object' ) {
    $args->walker->has_children = NULL;
  }
	$menu_signature = md5( wp_json_encode( $args ) . $wp_query->query_vars_hash );
	if ( isset( $args->menu->term_id ) ) {
		set_transient( 'menu-cache-menuid-' . $args->menu->term_id . '-' . $menu_signature, $nav_menu, 15552000 );
		// Store a reference to this version of the menu, so we can purge it when needed.
		$cached_versions = get_transient( 'menu-cache-menuid-' . $args->menu->term_id );
		if ( false === $cached_versions ) {
			$cached_versions = [];
		} else {
			$cached_versions = json_decode( $cached_versions );
		}
		if ( ! in_array( $menu_signature, $cached_versions, true ) ) {
			$cached_versions[] = $menu_signature;
		}
		set_transient( 'menu-cache-menuid-' . $args->menu->term_id, wp_json_encode( $cached_versions ), 15552000 );
	}
	return $nav_menu;
}, 10, 2 );
/**
 * Clears the menu cache.
 *
 * Fires after a navigation menu has been successfully updated.
 *
 * @param int   $menu_id   ID of the updated menu.
 * @param array $menu_data An array of menu data.
 */
add_action( 'wp_update_nav_menu', function( $menu_id, $menu_data = null ) {
	global $wpdb;
	if ( is_array( $menu_data ) && isset( $menu_data['menu-name'] ) ) {
		$menu = wp_get_nav_menu_object( $menu_data['menu-name'] );
		if ( isset( $menu->term_id ) ) {
			// Delete all cached versions of this menu.
      $wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE '%menu-cache-menuid-" . $menu->term_id . "%';" );
      //if this is the menu with regions delete those references
      if( $menu->term_id === 32 ) {
        delete_transient( 'menu-cache-event-menu-season' );
        delete_transient( 'menu-cache-event-menu-region' );
      }
		}
	}
}, 10, 2 );












// /**
//  * Simple logging for WordPress.
//  *
//  * @package BJ\Log
//  * @author bjornjohansen
//  * @version 0.1.0
//  * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GNU General Public License version 2 (GPLv2)
//  */
// if ( ! function_exists( 'write_log' ) ) {
// 	/**
// 	 * Utility function for logging arbitrary variables to the error log.
// 	 *
// 	 * Set the constant WP_DEBUG to true and the constant WP_DEBUG_LOG to true to log to wp-content/debug.log.
// 	 * You can view the log in realtime in your terminal by executing `tail -f debug.log` and Ctrl+C to stop.
// 	 *
// 	 * @param mixed $log Whatever to log.
// 	 */
// 	function write_log( $log ) {
// 		if ( true === WP_DEBUG ) {
// 			if ( is_scalar( $log ) ) {
// 				error_log( $log );
// 			} else {
// 				error_log( print_r( $log, true ) );
// 			}
// 		}
// 	}
// }

// /**
//  * WordPress menu cache timing.
//  *
//  * @package BJ\Menu
//  * @author bjornjohansen
//  * @version 0.1.0
//  * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GNU General Public License version 2 (GPLv2)
//  */
// /**
//  * Add the logging timer.
//  *
//  * @param string|null $output Nav menu output to short-circuit with. Default null.
//  * @param stdClass    $args   An object containing wp_nav_menu() arguments.
//  * @return string|null Output passthrough (default null).
//  */
// add_filter( 'pre_wp_nav_menu', function( $output, $args ) {
// 	global $wp_menu_generation_start;
// 	$wp_menu_generation_start = microtime( true );
// 	return $output;
// }, 9, 2 );
// /**
//  * Log the menu generation time if we have a cache hit.
//  *
//  * @param string|null $output Nav menu output to short-circuit with. Default null.
//  * @param stdClass    $args   An object containing wp_nav_menu() arguments.
//  * @return string|null Output passthrough (default null).
//  */
// add_filter( 'pre_wp_nav_menu', function( $output, $args ) {
// 	if ( ! is_null( $output ) ) {
// 		global $wp_menu_generation_start;
// 		$wp_menu_generation_time = microtime( true ) - $wp_menu_generation_start;
// 		write_log( sprintf( 'shb Cache hit: Menu output was fetched in %d ms', round( $wp_menu_generation_time * 1000 ) ) );
// 	}
// 	return $output;
// }, 11, 2 );
// /**
//  * Log the menu generation time.
//  *
//  * @param string   $nav_menu The HTML content for the navigation menu.
//  * @param stdClass $args     An object containing wp_nav_menu() arguments.
//  */
// add_filter( 'wp_nav_menu', function( $nav_menu, $args ) {
// 	global $wp_menu_generation_start;
// 	$wp_menu_generation_time = microtime( true ) - $wp_menu_generation_start;
// 	write_log( sprintf( 'shb Cache miss: Menu output was generated in %d ms', round( $wp_menu_generation_time * 1000 ) ) );
// 	return $nav_menu;
// }, 11, 2 );




// /**
//  * WordPress menu cache.
//  *
//  * @package BJ\Menu
//  * @author bjornjohansen
//  * @version 0.1.0
//  * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html  GNU General Public License version 2 (GPLv2)
//  */
// /**
//  * Short-circuit the wp_nav_menu() output if we have cached output ready.
//  *
//  * Returning a non-null value to the filter will short-circuit
//  * wp_nav_menu(), echoing that value if $args->echo is true,
//  * returning that value otherwise.
//  *
//  * @see wp_nav_menu()
//  *
//  * @param string|null $output Nav menu output to short-circuit with. Default null.
//  * @param stdClass    $args   An object containing wp_nav_menu() arguments.
//  * @return string|null Nav menu output to short-circuit with. Passthrough (default null) if we don’t have a cached version.
//  */
// add_filter( 'pre_wp_nav_menu', function( $output, $args ) {
// 	/* This section is from wp_nav_menu(). It is here to find a menu when none is provided. */
// 	// @codingStandardsIgnoreStart
	
// 	// Get the nav menu based on the requested menu
// 	$menu = wp_get_nav_menu_object( $args->menu );
// 	// Get the nav menu based on the theme_location
// 	if ( ! $menu && $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) )
// 		$menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );
// 	// get the first menu that has items if we still can't find a menu
// 	if ( ! $menu && !$args->theme_location ) {
// 		$menus = wp_get_nav_menus();
// 		foreach ( $menus as $menu_maybe ) {
// 			if ( $menu_items = wp_get_nav_menu_items( $menu_maybe->term_id, array( 'update_post_term_cache' => false ) ) ) {
// 				$menu = $menu_maybe;
// 				break;
// 			}
// 		}
// 	}
// 	if ( empty( $args->menu ) ) {
// 		$args->menu = $menu;
// 	}
// 	// @codingStandardsIgnoreEnd
// 	/* End of the section from wp_nav_menu(). It was a pleasure, ladies and gents. */
// 	global $wp_query;
// 	$menu_signature = md5( wp_json_encode( $args ) . $wp_query->query_vars_hash );
// 	// We don’t actually need the references to all the cached versions of this menu,
// 	// but we need to make sure the cache is not out of sync - transients are unreliable.
// 	$cached_versions = get_transient( 'menu-cache-menuid-' . $args->menu->term_id );
//   write_log( sprintf( 'shb Cache?' . $cached_versions ) );
// 	if ( false !== $cached_versions ) {
// 		$cached_output = get_transient( 'menu-cache-' . $menu_signature );
// 		if ( false !== $cached_output ) {
// 			$output = $cached_output;
// 		}
// 	}
// 	return $output;
// }, 10, 2 );
// /**
//  * Cache the HTML content output for navigation menus.
//  *
//  * @see wp_nav_menu()
//  *
//  * @param string   $nav_menu The HTML content for the navigation menu.
//  * @param stdClass $args     An object containing wp_nav_menu() arguments.
//  * @return string The HTML content for the navigation menu.
//  */
// add_filter( 'wp_nav_menu', function( $nav_menu, $args ) {
// 	global $wp_query;
// 	$menu_signature = md5( wp_json_encode( $args ) . $wp_query->query_vars_hash );
// 	if ( isset( $args->menu->term_id ) ) {
// 		set_transient( 'menu-cache-' . $menu_signature, $nav_menu );
// 		// Store a reference to this version of the menu, so we can purge it when needed.
// 		$cached_versions = get_transient( 'menu-cache-menuid-' . $args->menu->term_id );
// 		if ( false === $cached_versions ) {
// 			$cached_versions = [];
// 		} else {
// 			$cached_versions = json_decode( $cached_versions );
// 		}
// 		if ( ! in_array( $menu_signature, $cached_versions, true ) ) {
// 			$cached_versions[] = $menu_signature;
// 		}
// 		set_transient( 'menu-cache-menuid-' . $args->menu->term_id, wp_json_encode( $cached_versions ), 15552000 );
// 	}
// 	return $nav_menu;
// }, 10, 2 );
// /**
//  * Clears the menu cache.
//  *
//  * Fires after a navigation menu has been successfully updated.
//  *
//  * @param int   $menu_id   ID of the updated menu.
//  * @param array $menu_data An array of menu data.
//  */
// add_action( 'wp_update_nav_menu', function( $menu_id, $menu_data = null ) {
// 	if ( is_array( $menu_data ) && isset( $menu_data['menu-name'] ) ) {
// 		$menu = wp_get_nav_menu_object( $menu_data['menu-name'] );
// 		if ( isset( $menu->term_id ) ) {
// 			// Get all cached versions of this menu and delete them.
// 			$cached_versions = get_transient( 'menu-cache-menuid-' . $menu->term_id );
// 			if ( false !== $cached_versions ) {
// 				$cached_versions = json_decode( $cached_versions );
// 				foreach ( $cached_versions as $menu_signature ) {
// 					delete_transient( 'menu-cache-' . $menu_signature );
// 				}
// 				set_transient( 'menu-cache-menuid-' . $menu->term_id, wp_json_encode( [] ), 15552000 );
// 			}
// 		}
// 	}
// }, 10, 2 );