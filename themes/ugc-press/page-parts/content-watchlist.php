<article id="post-<?php the_ID(); ?>">
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-title-sub-tight medium-margin-bottom">
			The list below is based exclusively on player performance at EBC camps only – see list <a href="<?php echo get_site_url(); ?>/calendar/#ebcevents">HERE</a><br>
      Updates: December 15, March 15, May 15, August 15
		</div>

	</header><!-- .entry-header -->

	<?php if( !empty(get_the_content()) ) : ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'ugc-press' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'ugc-press' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		?>
	</div><!-- .entry-content -->
	
	<?php endif; ?>
	
	<div class="grid-x cell">
	<?php
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'ugc-press' ),
				get_the_title()
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer><!-- .entry-footer -->'
		);
	?>
	</div>

</article><!-- #post-## -->
