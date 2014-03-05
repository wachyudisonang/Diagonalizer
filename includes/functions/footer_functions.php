<?php

function diagonalizer_footer() {
	// Finding the number of active widget sidebars
	$count_foosb = 0;
	$class = 'col-md-';

	for ( $i=0; $i<5 ; $i++ ) {
		$foosb = 'sidebar-footer-'.$i.'';
		if ( is_active_sidebar( $foosb ) )
			$count_foosb++;
	}

	// Showing the active sidebars
	for ( $i=0; $i<5 ; $i++ ) {
		$foosb = 'sidebar-footer-' . $i;

		if ( is_active_sidebar( $foosb ) ) {
			// Setting each column width accordingly
			$col = 12 / $count_foosb;
		
			echo '<div class="' . $class . $col . '">';
			dynamic_sidebar( $foosb );
			echo '</div>';
		}
	}
}

