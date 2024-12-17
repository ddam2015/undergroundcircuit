<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>
<li id="post-<?php the_ID(); ?>" <?php post_class('accordion-item'); ?> data-accordion-item>

  <!-- Accordion tab title -->
	<a class="accordion-title entry-header">
		<?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
      the_title( '<h3 class="entry-title subtle no-margin">', shb_excerpt( 'title_insert' ) . '</h3>' );
		}
		?>
	</a><!-- .entry-header -->

  <!-- Accordion tab content -->
  <div class="accordion-content entry-content" data-tab-content>
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</li>
