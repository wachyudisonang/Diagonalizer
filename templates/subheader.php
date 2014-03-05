<?php
/**
 * The contents for displaying Subheader.
 *
 */
$home_static_img = diagonalizer( 'home_static_img' );
?>


<div id="subheaders" style="height: <?php echo diagonalizer( 'img_height' ) ?>px">
<?php 
	if ( is_front_page() ) {
		if ( diagonalizer( 'img_display_type' ) == 2 ) {
			if ( diagonalizer( 'img_display_pos' ) == 1 ) {
				if ( diagonalizer( 'home_slider' ) == 1 && !diagonalizer( 'home_layerslider' ) == 0 ) {
					$id_ls = '[layerslider id="' .diagonalizer( 'home_layerslider' ). '"]';
					echo do_shortcode($id_ls);
				}
				if ( diagonalizer( 'home_slider' ) == 2 && !diagonalizer( 'home_revslider' ) == 0 ) {
					putRevSlider( diagonalizer( 'home_revslider' ) );
				}
			}
		} else { if ( diagonalizer( 'img_display_pos' ) == 1 ) { ?>
			<div class="top_static_img" style="background: url(<?php echo $home_static_img['url'] ?>) repeat fixed center center;height: <?php echo diagonalizer( 'img_height' ) ?>px"></div>
		<?php }}
	}
?>
</div>
