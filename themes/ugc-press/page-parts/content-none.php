<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<div class="grid-x">
  <div class="cell small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3 large-margin-top large-margin-bottom no-results not-found">
    <header class="page-header">
      <h1 class="page-title"><?php _e( 'Nothing Found', 'ugc-press' ); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
      <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

        <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'ugc-press' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

      <?php elseif ( is_search() ) : ?>

        <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'ugc-press' ); ?></p>
        <?php get_search_form(); ?>

      <?php else : ?>

        <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help. Feel free to return to the <a href="/">homepage</a>.', 'ugc-press' ); ?></p>
        <?php get_search_form(); ?>

      <?php endif; ?>
    </div><!-- .page-content -->
  </div>
</div><!-- .no-results -->