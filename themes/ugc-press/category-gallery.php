<?php
/**
 * The post type gallery template
 * @package OGP EBC Press
 * @since EBC 1.0.0
 */
get_header(); ?>

<section id="content" class="grid-x site-main featured-image small-padding-top xlarge-padding-bottom" role="main">
	<div class="cell large-padding small-12">
		<header>
			<h1 class="entry-title screen-reader-text">Galleries</h1>
			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
		</header>
		<hr class="xlarge-margin-bottom green-border">
    <div class="category-gallery grid-x grid-padding-x small-up-1 medium-up-2">
		<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'archive-parts/content', 'gallery' );

    endwhile;
    ?>
    </div>
		
		<hr />
		
		<?php
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
		?>
	</div>
</section>

<?php get_footer(); ?>