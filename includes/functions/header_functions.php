<?php

function diagonalizer_logo() {

	$logo_text  = diagonalizer('logo_text');
	$logo_image = diagonalizer('logo_image');

	if ( ( diagonalizer( 'logo_as_text' ) ==1 ) && ( !empty( $logo_text) ) ) {
        echo 	'<a class="navbar-brand text" href="' .home_url(). '/">' .$logo_text. '</a>';
	} elseif ( !empty( $logo_image['url'] ) ) {
		echo 	'<a class="navbar-brand" href="' .home_url(). '/">
					<img alt="Logo ' .$_SERVER['HTTP_HOST']. '" src="' .$logo_image['url']. '" style="max-height:100%;max-width:250px;height:auto;padding:10px 0;">
				</a>';
	} else {
		echo 	'<a class="navbar-brand text" href="' .home_url(). '/">' .get_bloginfo('name'). '</a>';
	}

}

