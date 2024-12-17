<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * @package shb Press
 * @since shb 1.0.0
 */
get_header();
$shb_ad_info = shb_start_ads( $post->ID );

?>

<section id="content" class="grid-x site-main <?php echo ( (has_post_thumbnail( $post->ID ) || get_post_meta($post->ID, 'hero-video', true)) && !is_product() ) ? 'featured-image small-padding-top ' : ''; ?>xlarge-padding-bottom<?php if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell large-padding small-12">
		<?php
		if ( $shb_ad_info['go'] ) echo $shb_ad_info['ad_before'] . $shb_ad_info['ad_content'] . $shb_ad_info['ad_after'];
		if ( have_posts() ) : 
		if ( is_home() && ! is_front_page() ) : ?>
		<div class="tiny-margin-bottom tiny-padding gset no-border">
			<h1 class="entry-title screen-reader-text"><?php single_post_title(); ?></h1>
		</div>
		<?php endif;

    while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
		?>
	</div>
</section>

<?php get_footer(); ?>