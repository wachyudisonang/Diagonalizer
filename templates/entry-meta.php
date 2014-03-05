<?php
	
	$post_meta_switch = diagonalizer('post_meta_switch');
	$post_meta = diagonalizer( 'post_meta' );

if ( $post_meta_switch == 1 ) {
	
	$hasmeta = $post_meta['enabled'];

	$categories_list = get_the_category_list( __( ', ', 'diagonal-framework' ) );
	$tag_list        = get_the_tag_list( '', __( ', ', 'diagonal-framework' ) );

	// $i = 0;
	// if ( is_array( $hasmeta ) ) {
	// 	foreach ( $hasmeta as $meta => $value ) {
	// 		if ( $meta == 'comment'  && !empty( $value ) ) $i++;
	// 		if ( $meta == 'date'     && !empty( $value ) ) $i++;
	// 		if ( $meta == 'category' && !empty( $value ) ) $i++;
	// 		if ( $meta == 'tags'     && !empty( $value ) ) $i++;
	// 		if ( $meta == 'author'   && !empty( $value ) ) $i++;
	// 	}
	// }

	$content = '';
	if ( is_array( $hasmeta ) ) {
		foreach ( $hasmeta as $meta => $value ) {
			// output sticky element
			if ( $meta == 'comment' && !empty( $value ) ) {
				$content .= '<div class="icons meta-comment">
								<i class="icon-chat4"></i>
								<span class="iconist">' .getcomments(). '</span>
							</div>';
			}
			// output date element
			if ( $meta == 'date' && !empty( $value ) ) {
				$content .= '<div class="icons meta-date">
								<i class="icon-calendar22"></i>
								<span class="iconist"><time class="published" datetime="' .get_the_time('c'). '">' .get_the_date(). '</time></span>
							</div>';
			}
			// output category element
			if ( $meta == 'category' && !empty( $value ) ) {
				if ( $categories_list )
					$content .= '<div class="icons meta-category">
									<i class="icon-file-text"></i>
									<span class="iconist">' .$categories_list. '</span>
								</div>';
			}
			// output tag element
			if ( $meta == 'tags' && !empty( $value ) ) {
				if ( $tag_list )
					$content .= '<div class="icons meta-tag">
									<i class="icon-tag3"></i>
									<span class="iconist">' .$tag_list. '</span>
								</div>';
			}
			// output author element
			if ( $meta == 'author' && !empty( $value ) ) {
				$content .= sprintf( '<div class="icons meta-author"><i class="icon-user3"></i><span class="iconist byline author vcard"> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></div>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'diagonal-framework' ), get_the_author() ) ),
					get_the_author()
				);
			}
		}
	}

	if ( !empty( $content ) ) {
		echo '<div class="post-meta">' . $content . '</div>';
	}
}

?>
