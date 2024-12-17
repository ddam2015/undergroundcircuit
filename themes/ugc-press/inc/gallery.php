<?php
add_filter( 'post_gallery', 'gallery_shorcode_extend', 10, 3 );
/**
 *     Attributes of the gallery shortcode.
 *
 *     @type string       $order      Order of the images in the gallery. Default 'ASC'. Accepts 'ASC', 'DESC'.
 *     @type string       $orderby    The field to use when ordering the images. Default 'menu_order ID'.
 *                                    Accepts any valid SQL ORDERBY statement.
 *     @type int          $id         Post ID.
 *     @type string       $itemtag    HTML tag to use for each image in the gallery.
 *                                    Default 'dl', or 'figure' when the theme registers HTML5 gallery support.
 *     @type string       $icontag    HTML tag to use for each image's icon.
 *                                    Default 'dt', or 'div' when the theme registers HTML5 gallery support.
 *     @type string       $captiontag HTML tag to use for each image's caption.
 *                                    Default 'dd', or 'figcaption' when the theme registers HTML5 gallery support.
 *     @type int          $columns    Number of columns of images to display. Default 3.
 *     @type string|array $size       Size of the images to display. Accepts any valid image size, or an array of width
 *                                    and height values in pixels (in that order). Default 'thumbnail'.
 *     @type string       $ids        A comma-separated list of IDs of attachments to display. Default empty.
 *     @type string       $include    A comma-separated list of IDs of attachments to include. Default empty.
 *     @type string       $exclude    A comma-separated list of IDs of attachments to exclude. Default empty.
 *     @type string       $link       What to link each image to. Default empty (links to the attachment page).
 *                                    Accepts 'file', 'none'.
 */

function gallery_shorcode_extend( $output = '', $attr, $instance ) {
  if( empty($attr['ids']) ) return $output;
	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'ids'        => NULL,
		'columns'    => 3,
		'size'       => 'medium',
		'size_class' => 'gallery-grid',
    'type'       => 'default',
    'featured'   => false
	), $attr, 'gallery' );
  $_attachments = get_posts( array( 'include' => $atts['ids'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
  $attachments = array();
  foreach ( $_attachments as $key => $val ) {
    $attachments[$val->ID] = $_attachments[$key];
  }
	if ( empty( $attachments ) ) return $output;
	$columns = intval( $atts['columns'] );
	$selector = "gallery-{$instance}";
	$size_class = sanitize_html_class( $atts['size_class'] );
	$galleryClass = '';
	$limit = -1;
	if( $atts['featured'] ){
		$galleryClass = ' gallery-featured';
		$limit = 6;
	}
  $type_class = ( $atts['type'] !== 'default' ) ? sanitize_html_class( $atts['type'] ) : '';
	$output = "<div id='$selector' class='grid-x grid-margin-x small-up-3 medium-up-$columns $galleryClass $type_class' role='gallery'>";
	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$title = trim( $attachment->post_title );
    $title = ( !empty($title) ) ? '<h5>' . $title . '</h5>' : '';
    $description = trim( $attachment->post_excerpt );
    if( !empty($description) ) $description = '<span>' . ( $atts['type'] === 'staff' && false !== strpos($description, ' | ') ) ? implode('<hr class="no-margin">', explode(' | ', $description)) : $description . '</span>';
		$image_output = wp_get_attachment_image( $id, $atts['size'], false, ( !empty($description) ) ? array( 'aria-describedby' => "$selector-$id" ) : '' );
		$output .= "
    <div class='cell'>
			<div class='card text-center'>
				$image_output
        <div id='$selector-$id' class='card-section'>
        $title
        $description
        </div>
			</div>
		</div>";
		$i++;
		if($limit && $limit == $i) break;
	}
	$output .= "\n</div>\n";
	return $output;
}
?>