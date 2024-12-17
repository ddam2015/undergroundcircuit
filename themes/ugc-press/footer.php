<?php
  $shb_layout_type = get_option( 'shb_layout' );
  if( !is_front_page() || $shb_layout_type['front_layout']['type'] !== 'tiles' ) echo '</div>';
  echo ( !empty( $shb_potm ) || !empty( $shb_ctotm ) || !empty( $featured_events_arr ) ) ? '<footer id="site-footer" class="xlarge-padding small-small-padding no-margin-bottom relative">' : '<footer id="site-footer" class="xlarge-padding small-small-padding no-margin-bottom medium-margin-top relative">';
?>
  <div class="grid-container">
    <div class="grid-x grid-margin-x align-middle">
      <div class="cell small-12">
        <?php shb_footer_nav(); ?>
      </div>
			<?php $blog_info = get_bloginfo( 'name' ); ?>
			<?php if ( ! empty( $blog_info ) ) : ?>
        <div class="small-12 medium-12 large-12 text-center cell">
          <p>
           All Rights Reserved | &#169; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?> | <a href="<?php echo get_site_url() ?>/privacy-policy/" aria-current="page">Privacy Policy</a>
          </p>
        </div>
			<?php endif; ?>
    </div>
  </div>
</footer>
<?php if(!strpos(get_site_url(), 'dev.')): ?>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-S6B4HN33NW"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-S6B4HN33NW');
  </script>
<?php endif; ?>
<?php wp_footer(); ?>
</body>

</html>