<?php

function diagonalizer_topheader() {

	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? 'mobile' : 'notmobile');
	$arr 	= '';
	$items 	= '';

	/**
	 * pull data #social
	 *
	 */
	$arr['social'] = '';
	$social_items = diagonalizer( 'social_items' );
	$social_enabled = $social_items['enabled'];

	if ( is_array( $social_enabled ) ) {
		foreach ( $social_enabled as $meta => $value ) {
			if ($meta == 'facebook') 	$value = diagonalizer( 'facebook' ); 
			if ($meta == 'twitter') 	$value = diagonalizer( 'twitter' ); 
			if ($meta == 'google-plus') $value = diagonalizer( 'googleplus' );  
			if ($meta == 'youtube') 	$value = diagonalizer( 'youtube' ); 
			if ($meta == 'linkedin') 	$value = diagonalizer( 'linkedin' );
			if ($meta == 'pinterest') 	$value = diagonalizer( 'pinterest' );  
			if ($meta == 'flickr') 		$value = diagonalizer( 'flickr' ); 
			if ($meta == 'skype') 		$value = diagonalizer( 'skype' );

			if ($meta != 'placebo'){
				$arr['social'] .= '<a href="' .$value. '" target="_blank" class="icon-' .$meta. '"></a>';
			} 
		}	
	}


	/**
	 * pull data #search-form
	 *
	 */
	$arr['search'] = '';
	$get_search_query = is_search() ? get_search_query() :'';

	$arr['search'] = ($deviceType == 'notmobile') ?
		'<form action="' .esc_url( home_url( '/' ) ). '" class="search-form form-inline" method="get" role="search">
			<div class="input-group">
				<input type="search" placeholder="' .__('Search', 'roots'). ' ' .get_bloginfo('name'). '" class="search-field form-control" name="s" value="' .$get_search_query. '">
				<label class="hide">' .__('Search for:', 'roots'). '</label>
			</div>
		</form>' :
		'<a title="" href="/search" data-original-title="Search" class="has_bottom_tooltip">
			<i class="icon-search2"></i>
		</a>';


	/**
	 * pull data #phone
	 *
	 */
	$arr['phone'] = '';
	$phone = diagonalizer( 'phone' );

	$arr['phone'] = '<a href="tel:' .$phone. '"><i class="icon-phone2"></i><span>' .$phone. '</span></a>';


	/**
	 * pull data #email
	 *
	 */
	$arr['email'] = '';
	$email = diagonalizer( 'email' );

	$arr['email'] = '<a href="mailto:' .$email. '"><i class="icon-mail2"></i><span>' .$email. '</span></a>';



	$topheader = diagonalizer( 'topheader' );
	unset( $topheader['disabled'] );
	$base_class = 'col-md-';

	$i = 0;
	foreach( array_keys( $topheader ) as $key ) {
  		unset( $topheader[$key]['placebo'] );
  		if ( !empty( $topheader[$key] ) ) $i++;
	}

	if( $i > 0 ) {
		$col_size = 12 / $i;
	} else {
		$col_size = 12;
	}

	$thl = $topheader['left-side'];
	$thr = $topheader['right-side'];

	$side['left'] 	= '';
	$side['right'] 	= '';
	if( $i > 1 ) {
		$side['left'] 	= 'left';
		$side['right'] 	= 'right';
	}

	$items .= get_items( $thl, $arr, $base_class, $col_size, $side['left'] );
	$items .= get_items( $thr, $arr, $base_class, $col_size, $side['right'] );

	return $items;
}

function get_items( $side, $arr, $base_class, $col_size, $col_class ) {

	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? 'mobile' : 'notmobile');
	$column = '';

	if( !empty( $side ) ) {
		$each_column = wrap_items( $side, $arr );

		// $column .= '<div class="' . $base_class . $col_size . '" ><ul class="' .$col_class. '">';
		$column .= ($deviceType == 'notmobile') ? '<div class="navbar-' .$col_class. '" ><ul class="' .$col_class. '">' : '';
	    	foreach ($each_column as $value) {
				$column .= $value;
	    	}
		$column .= ($deviceType == 'notmobile') ? '</ul></div>' : '';
	}

	return $column;

}

function wrap_items( $side, $arr ) {

	$column_items = '';
	$i = 0;

	foreach ( $side as $meta => $value ) {
		if ( $meta == 'account'	) $value 	= 'logyay '; 
		if ( $meta == 'search'	) $value 	= '<li class="th-items ' .$meta. '">' .$arr[$meta]. '</li>'; 
		if ( $meta == 'email'	) $value 	= '<li class="th-items ' .$meta. '">' .$arr[$meta]. '</li>';  
		if ( $meta == 'phone'	) $value 	= '<li class="th-items ' .$meta. '">' .$arr[$meta]. '</li>'; 
		if ( $meta == 'social'	) $value 	= '<li class="th-items ' .$meta. '">' .$arr[$meta]. '</li>'; 

		if ( $meta != 'placebo' ){
			$column_items[$i] = $value;
			$i++;
		}
	}

	return $column_items;
}



