<?php

function custom_rewrite_rule() {
	add_rewrite_rule('^ownership-registration/?','wp-admin/admin-ajax.php?action=g365_data_receiver','top');
	add_rewrite_rule('^send-claim/?','wp-admin/admin-ajax.php?action=g365_send_claim_notice','top');
	add_rewrite_rule('^register/([^/]*)/?([^/]*)?/?','index.php?page_id=22736&rg_tp=$matches[1]&rg_ps=$matches[2]','top');
}
add_action('init', 'custom_rewrite_rule', 10, 0);

//custom page url rewrites
function custom_rewrite_tag() {}
// add_action('init', 'custom_rewrite_tag', 10, 0);


// /**
//  * Change min password strength.
//  */
// function iconic_min_password_strength( $strength ) {
//     return 2;
// }
 
// add_filter( 'woocommerce_min_password_strength', 'iconic_min_password_strength', 10, 1 );


/*
 * load required files
 */
get_template_part( 'inc/cleanup' );
get_template_part( 'inc/menu-cache' );
get_template_part( 'inc/menu-walkers' );
get_template_part( 'inc/gallery' );
get_template_part( 'inc/g365_conn' );
get_template_part( 'inc/general' );
get_template_part( 'inc/woocomm' );
get_template_part( 'inc/woocomm-gatekeep' );

add_role( 'gate_controller', 'Gatekeeper', array( 'read' => true ) );
?>