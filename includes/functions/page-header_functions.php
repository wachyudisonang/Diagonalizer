<?php

function diagonalizer_homeImage() {

	$home_static_img = diagonalizer( 'home_static_img' );

	if ( is_front_page() ) {
		if ( diagonalizer( 'img_display_type' ) == 2 ) {
			if ( diagonalizer( 'img_display_pos' ) == 2 ) {
				if ( diagonalizer( 'home_slider' ) == 1 && !diagonalizer( 'home_layerslider' ) == 0 ) {
					$id_ls = '[layerslider id="' .diagonalizer( 'home_layerslider' ). '"]';
					echo do_shortcode($id_ls);
				}
				if ( diagonalizer( 'home_slider' ) == 2 && !diagonalizer( 'home_revslider' ) == 0 ) {
					putRevSlider( diagonalizer( 'home_revslider' ) );
				}
			}
		} else { if ( diagonalizer( 'img_display_pos' ) == 2 ) { ?>
			<div class="btm_static_img" style="background-image: url('<?php echo $home_static_img['url'] ?>');height: <?php echo diagonalizer( 'img_height' ) ?>px"></div>
		<?php }}
	}

}
