<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('cell'); ?>>
	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && !is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'ugc-press' ); ?></span>
		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title">', esc_url( get_permalink() ) ), '</h2>' ); ?>
	</header><!-- .entry-header -->

	<?php shb_excerpt(); ?>

	<div class="entry-content">
		<?php

    $customer_id = get_current_user_id();
    $edit_switch = filter_input( INPUT_GET, 'edit_form', FILTER_SANITIZE_STRING );
    //wc_add_notice( __("You get 50% of discount on the 2nd item"), 'notice');
    
    $fname = get_user_meta( $customer_id, 'first_name', true );
    $lname = get_user_meta( $customer_id, 'last_name', true );
    $address = get_user_meta( $customer_id, 'billing_address_1', true ); 
    $city = get_user_meta( $customer_id, 'billing_city', true );
    $postcode = get_user_meta( $customer_id, 'billing_postcode', true );
    $state = get_user_meta( $customer_id, 'billing_state', true );
    $country = get_user_meta( $customer_id, 'billing_country', true );
    $phone = get_user_meta( $customer_id, 'billing_phone', true );
    
    if( $edit_switch || !$fname || !$lname || !$address || !$city || !$postcode || !$state || !$country || !$phone ) {
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-6">
          <form method="post" action="?update_address=<?php echo get_permalink(); ?>">
            <h3><small>Add/Edit Information:</small><br>Parent/Guardian/User</h3>
            <span class="block callout tiny-padding warning">All Fields Required</span>
            <div class="woocommerce-address-fields">
              <div class="woocommerce-address-fields__field-wrapper">
                <?php
                //get user data
                $user_meta = get_user_meta($customer_id);
                // get the form fields
                $countries = new WC_Countries();
                $billing_fields = $countries->get_address_fields( '', 'billing_' );
                $skip = array('billing_company');
                $hide = array('billing_first_name', 'billing_last_name');
                foreach ( $billing_fields as $key => $field ) {
                  if( in_array( $key, $skip ) ) continue;
                  //if( in_array( $key, $hide ) && !empty($user_meta[$key][0]) ) $field['class'][] = 'hide';
                  woocommerce_form_field( $key, $field, $user_meta[$key][0] );
                }
                ?>
              </div>
              <p>
                <button type="submit" class="button" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>">Next</button>
                <?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
                <input type="hidden" name="action" value="edit_address" />
              </p>
            </div>

          </form>
        </div>        
      </div>
      <?php
    } else { ?>

      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 small-margin-bottom woocommerce-Address callout primary">
          <h5 class="no-margin-bottom">Parent/Guardian/User Information</h5>
          <address class="small-margin-left">
            <?php echo wp_kses_post( $fname . ' ' . $lname . '<br><strong>' . $address . '<br>' . $city . ', ' . $state . ' ' . $postcode . '</strong><br>' . $phone ); ?>
          </address>
          <a href="?edit_form=<?php echo $name; ?>" class="edit"><?php echo $address ? esc_html__( 'Edit', 'woocommerce' ) : esc_html__( 'Add', 'woocommerce' ); ?></a>
        </div>
        <div class="cell small-12 medium-8 small-margin-bottom">

    <?php
    remove_filter('the_content', 'foundation_content');
      /* START DEFAULT TEMPLATE */
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'ugc-press' ),
				get_the_title()
			) );

    add_filter('the_content', 'foundation_content'); ?>

        </div>
      </div>
    <?php

      wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'ugc-press' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'ugc-press' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
    }
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer grid-x cell">
		<?php shb_entry_meta(); ?>
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'ugc-press' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
