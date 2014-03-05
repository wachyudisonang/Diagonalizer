<?php

/*------------------------------------------------------------------
// Constant
-------------------------------------------------------------------*/
function diagonalizer( $name, $key = false ) {
	global $diagonalizer;
	$options = $diagonalizer;

	// Set this to your preferred default value
	$var = '';

	if ( empty( $name ) && !empty( $options ) ) {
		$var = $options;
	} else {
		if ( !empty( $options[$name] ) ) {
			$var = ( !empty( $key ) && !empty( $options[$name][$key] ) && $key !== true ) ? $options[$name][$key] : $var = $options[$name];;
		}
	}
	return $var;
}

/*------------------------------------------------------------------
// Put Logo Client in Login Page
-------------------------------------------------------------------*/
function my_login_logo() { 
	$admin_logo_image = diagonalizer('admin_logo_image');
	if ( !empty($admin_logo_image['url']) && $admin_logo_image['url'] != '' ) {
?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo $admin_logo_image['url']?>);
        }
    </style>
<?php } }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function put_my_url(){
    return home_url();
}
add_filter('login_headerurl', 'put_my_url');

function put_my_title(){
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'put_my_title');

/*------------------------------------------------------------------
// Favicon
-------------------------------------------------------------------*/
function diagonalizer_favicon() {

	$detect = new Mobile_Detect;
	$favicon_item = diagonalizer('favicon');
	$apple_icon_item = diagonalizer('apple_icon');
	

	// Add the favicon
	if ( !empty($favicon_item['url']) && $favicon_item['url'] != '' ) {
		$favicon = matthewruddy_image_resize( $favicon_item['url'], 32, 32, true, false );
		echo '<link rel="shortcut icon" href="'.$favicon['url'].'" type="image/x-icon" />';
	}

	// Add the apple icons
	if ( !empty($apple_icon_item['url']) ) {
		$iphone_icon        = matthewruddy_image_resize( $apple_icon_item['url'], 57, 57, true, false );
		$iphone_icon_retina = matthewruddy_image_resize( $apple_icon_item['url'], 57, 57, true, true );
		$ipad_icon          = matthewruddy_image_resize( $apple_icon_item['url'], 72, 72, true, false );
		$ipad_icon_retina   = matthewruddy_image_resize( $apple_icon_item['url'], 72, 72, true, true );

		if ( $detect->isiPhone() ) {
		?>
		<!-- For iPhone --><link rel="apple-touch-icon-precomposed" href="<?php echo $iphone_icon['url'] ?>">
		<!-- For iPhone 4 Retina display --><link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $iphone_icon_retina['url'] ?>">
		<?php } 	
		if ( $detect->isiPad() ) {
		?>
		<!-- For iPad --><link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $ipad_icon['url'] ?>">
		<!-- For iPad Retina display --><link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $ipad_icon_retina['url'] ?>">
		<?php }
	}
}
add_action( 'wp_head', 'diagonalizer_favicon' );

function blocks_layout() {
	global $get_blocks_layout;

	if ( !isset( $get_blocks_layout ) ) {
		$get_blocks_layout = intval( diagonalizer('site_layout') );

		// Looking for a per-page template ?
		if ( is_page() && is_page_template() ) {
			if ( is_page_template( 'template-0.php' ) )
				$get_blocks_layout = 0;
			elseif ( is_page_template( 'template-1.php' ) )
				$get_blocks_layout = 1;
			elseif ( is_page_template( 'template-2.php' ) )
				$get_blocks_layout = 2;
			elseif ( is_page_template( 'template-3.php' ) )
				$get_blocks_layout = 3;
			elseif ( is_page_template( 'template-4.php' ) )
				$get_blocks_layout = 4;
			elseif ( is_page_template( 'template-5.php' ) )
				$get_blocks_layout = 5;
		}

		// if ( diagonalizer( 'cpt_layout_toggle' ) == 1 ) {
		// 	if ( !is_page_template() ) {
		// 		$post_types = get_post_types( array( 'public' => true ), 'names' );
		// 		foreach ( $post_types as $post_type ) {
		// 			$get_blocks_layout = ( is_singular( $post_type ) ) ? intval( diagonalizer( $post_type . '_layout' ) ) : $get_blocks_layout;
		// 		}
		// 	}
		// }

		if ( !is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $get_blocks_layout == 5 )
			$get_blocks_layout = 3;
	}
	return $get_blocks_layout;
}

function blocks_class( $target, $echo = false ) {
	global $diagonalizer;
	
	$layout = blocks_layout();
	$first  = intval( diagonalizer( 'primary_sidebar_width' ) );
	$second = intval( diagonalizer( 'secondary_sidebar_width' ) );
	
	// disable responsiveness if layout is set to non-responsive
	$base = ( diagonalizer( 'site_style' ) == 'static' ) ? 'col-xs-' : 'col-sm-';
	
	// Set some defaults so that we can change them depending on the selected template
	$main       = $base . 12;
	$primary    = NULL;
	$secondary  = NULL;
	$wrapper    = NULL;

	if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) ) {

		if ( $layout == 5 ) {
			$main       = $base . ( 12 - floor( ( 12 * $first ) / ( 12 - $second ) ) );
			$primary    = $base . floor( ( 12 * $first ) / ( 12 - $second ) );
			$secondary  = $base . $second;
			$wrapper    = $base . ( 12 - $second );
		} elseif ( $layout >= 3 ) {
			$main       = $base . ( 12 - $first - $second );
			$primary    = $base . $first;
			$secondary  = $base . $second;
		} elseif ( $layout >= 1 ) {
			$main       = $base . ( 12 - $first );
			$primary    = $base . $first;
			$secondary  = $base . $second;
		}

	} elseif ( !is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) ) {

		if ( $layout >= 1 ) {
			$main       = $base . ( 12 - $first );
			$primary    = $base . $first;
		}

	} elseif ( is_active_sidebar( 'sidebar-secondary' ) && !is_active_sidebar( 'sidebar-primary' ) ) {

		if ( $layout >= 3 ) {
			$main       = $base . ( 12 - $second );
			$secondary  = $base . $second;
		}
	}

	// Overrides the main region class when on the frontpage and sidebars are set to not being displayed there.
	if ( is_front_page() && diagonalizer( 'front_sidebar' ) != 1 || is_404() ) {
		$main      = $base . 12;
		$wrapper   = NULL;
	}

	

	if ( $target == 'primary' )
		$class = $primary;
	elseif ( $target == 'secondary' )
		$class = $secondary;
	elseif ( $target == 'wrapper' )
		$class = $wrapper;
	else
		$class = $main;

	if ( $target != 'wrap'  ) {
		// echo or return the result.
		if ( $echo )
			echo $class;
		else
			return $class;

	} else {
		if ( $layout == 5 )
			return true;
		else
			return false;
	}
}

function diagonalizer_body_class( $classes ) {
	$layout     = blocks_layout();

	$classes[] = ( $layout == 2 || $layout == 3 || $layout == 5 ) ? 'main-float-right' : '';

	return $classes;
}
add_filter('body_class', 'diagonalizer_body_class');

/*------------------------------------------------------------------
    Override Functions
-------------------------------------------------------------------*/
function diagonalizer_sidebar() {
	// echo diagonalizer( 'site_layout' ).'<br>';
	// echo blocks_layout();
	$front_sidebar = diagonalizer('front_sidebar');

	if ( ( blocks_layout() != 0 && ( roots_display_sidebar() ) ) || ( is_front_page() && $front_sidebar == 1 && blocks_layout() != 0 ) ) {
		if ( !is_front_page() || ( is_front_page() && $front_sidebar == 1 ) ) {
			echo '<aside class="sidebar ' .blocks_class( 'primary' ). '" role="complementary">';
				if ( !has_action( 'diagonalizer_sidebar_override' ) ) {
					include roots_sidebar_path_primary();
				} else {
					do_action( 'diagonalizer_sidebar_override' );
				}
			echo '</aside>';
		}
	}

	if ( blocks_class( 'wrap' ) ) {
        echo '</div></div>';
    }

	if ( blocks_layout() >= 3 && is_active_sidebar( 'sidebar-secondary' ) ) {
		if ( !is_front_page() || ( is_front_page() && $front_sidebar == 1 ) ) {
			echo '<aside class="sidebar secondary ' .blocks_class( 'secondary' ). '" role="complementary">';
				include roots_sidebar_path_secondary();
			echo '</aside>';
		}
	}
}

function getcomments() {
	global $post;

	$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

	if ( comments_open() ) {
		if ( $num_comments == 0 ) {
			$comments = __('No Comments');
		} elseif ( $num_comments > 1 ) {
			$comments = $num_comments . __(' Comments');
		} else {
			$comments = __('1 Comment');
		}
		$write_comments = '<a title="Comment on ' .get_the_title($post->ID). '" href="' . get_comments_link() .'">'. $comments.'</a>';
	} else {
		$write_comments =  __('Comments are off for this post.');
	}
	return $write_comments;
}

function diagonalizer_excerpt_more( $more ) {
	$excerpt_text = diagonalizer( 'post_excerpt_more' );
	return ' &hellip; <a href="' . get_permalink() . '">' . $excerpt_text . '</a>';
}
add_filter('excerpt_more', 'diagonalizer_excerpt_more');

function diagonalizer_excerpt_length($length) {
	$excerpt_length = diagonalizer( 'post_excerpt_length' );
	return $excerpt_length;
}
add_filter('excerpt_length', 'diagonalizer_excerpt_length');


