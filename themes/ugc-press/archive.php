<?php
/**
 * The main post template
 * @package shb EBC Press
 * @since EBC 1.0.0
 */
get_header(); ?>

<section id="content" class="grid-x site-main large-padding-top xlarge-padding-bottom" role="main">
	<div class="cell large-padding small-12">
		<header>
			<h1 class="entry-title screen-reader-text"><?php echo single_term_title(); ?></h1>
			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
		</header>
		<hr class="xlarge-margin-bottom green-border">
		<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();

      get_template_part( 'archive-parts/content', get_post_format() );
    
    endwhile;
    ?>
		
		<hr />
		<div class="navigation pagination pagination-inline">
			<h2 class="show-for-sr">Posts Navigation</h2>
			<div class="nav-links">
				<?php
				$big = 999999999; // need an unlikely integer

				echo paginate_links( array(
					'base'							=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'  					=> '?paged=%#%',
					'current' 					=> max( 1, get_query_var('paged') ),
					'total'  						=> $wp_query->max_num_pages,
					'mid_size'					=> 2,
					'prev_text'					=> '<i class="fi-arrow-left"></i> <span class="screen-reader-text">' . __( 'Previous page', 'g365-press' ) . '</span>',
					'next_text'					=> '<span class="screen-reader-text">' . __( 'Next page', 'g365-press' ) . '</span> <i class="fi-arrow-right"></i>',
					'before_page_number'=> '<span class="meta-nav show-for-sr">' . __( 'Page', 'g365-press' ) . ' </span>',
				) );
				?>
			</div>
		</div>
		
		<?php
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
		?>
	</div>
</section>

<?php get_footer(); ?>