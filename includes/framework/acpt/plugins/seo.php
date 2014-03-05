<?php
if( !defined('WPSEO_URL') && !defined('AIOSEOP_VERSION') ) {
	add_action( 'add_meta_boxes', 'acpt_seo_meta' );
	add_filter( 'wp_title', 'acpt_seo_title', 100 );
	add_action( 'wp_head' , 'acpt_seo_description');
}

function acpt_seo_meta() {
	$publicTypes = get_post_types( array( 'public' => true ) );
	acpt_meta_box('acpt_seo', $publicTypes, array('label' => 'Search Engine Optimization'));
}

function meta_acpt_seo() {
	acpt_form('acpt_seo', array('group' => '[seo][meta]'))
	->text('title', array('label' => 'Title'))
	->textarea('description', array('label' => 'Description'));
}

function acpt_seo_title( $title, $sep = '', $other = '' ) {
    global $paged, $page;

    $newTitle = acpt_meta('[seo][meta][acpt_seo_title]');

    if ( $newTitle != '') {
      if(is_feed() || is_single() || is_page() || is_singular() ) {
        return $newTitle;
      } else {
        return $title;
      }
    } else {
      return $title;
    }

}

function acpt_seo_description() {
	global $post;
	$seo = esc_attr(acpt_meta('[seo][meta][acpt_seo_description]'));
	if( !empty( $seo ) ) { echo "\t<meta name=\"Description\" content=\"{$seo}\" />\n"; }
}