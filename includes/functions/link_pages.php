<?php

add_filter('wp_link_pages_args', 'wp_link_pages_args_prevnext_add');

function wp_link_pages_args_prevnext_add($args) {
	global $page, $numpages, $more, $pagenow;

	if (!$args['next_or_number'] == 'next_and_number')
		return $args; # exit early

	$args['next_or_number'] = 'number'; # keep numbering for the main part
	if (!$more)
		return $args; # exit early

	if($page-1) # there is a previous page
		$args['before'] .= '<a class="'.@$args['link_classes'].' '.@$args['prev_link_classes'].'" href="'._us_wp_link_page($page-1).'">'
			. $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>'
		;

	if ($page<$numpages) # there is a next page
		$args['after'] = '<a class="'.@$args['link_classes'].' '.@$args['next_link_classes'].'" href="'._us_wp_link_page($page+1).'">'
			. $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
			. $args['after']
		;

	return $args;
}

function us_wp_link_pages($args = '') {

	$defaults = array(
		'before' => '<p>' . __('Pages:', 'us'), 'after' => '</p>',
		'link_classes' => 'g-pagination-item', 'next_link_classes' => 'to_next', 'prev_link_classes' => 'to_prev',
		'link_before' => '', 'link_after' => '',
		'next_or_number' => 'number', 'nextpagelink' => __('Next page', 'us'),
		'previouspagelink' => __('Previous page', 'us'), 'pagelink' => '%',
		'echo' => 1
	);

	$r = wp_parse_args( $args, $defaults );
	$r = apply_filters( 'wp_link_pages_args', $r );
	extract( $r, EXTR_SKIP );

	global $page, $numpages, $multipage, $more, $pagenow;

	$output = '';
	if ( $multipage ) {
		if ( 'number' == $next_or_number ) {
			$output .= $before;
			for ( $i = 1; $i < ($numpages+1); $i = $i + 1 ) {
				$j = str_replace('%',$i,$pagelink);
				$output .= ' ';
				if ( ($i != $page) || ((!$more) && ($page==1)) ) {
					$output .= '<a class="'.$link_classes.'" href="'._us_wp_link_page($i).'">';
				} elseif ($i == $page) {
					$output .= '<span class="'.$link_classes.' active">';
				}
				$output .= $link_before . $j . $link_after;
				if ( ($i != $page) || ((!$more) && ($page==1)) )
				{
					$output .= '</a>';
				} elseif ($i == $page) {
					$output .= '</span>';
				}
			}
			$output .= $after;
		} else {
			if ( $more ) {
				$output .= $before;
				$i = $page - 1;
				if ( $i && $more ) {
					$output .= '<a class="'.$link_classes.' '.$prev_link_classes.'" href="'._us_wp_link_page($i).'">';
					$output .= $link_before. $previouspagelink . $link_after . '</a>';
				}
				$i = $page + 1;
				if ( $i <= $numpages && $more ) {
					$output .= '<a class="'.$link_classes.' '.$next_link_classes.'" href="'._us_wp_link_page($i).'">';
					$output .= $link_before. $nextpagelink . $link_after . '</a>';
				}
				$output .= $after;
			}
		}
	}

	if ( $echo )
		echo $output;

	return $output;
}

function _us_wp_link_page( $i ) {
	global $wp_rewrite;
	$post = get_post();

	if ( 1 == $i ) {
		$url = get_permalink();
	} else {
		if ( '' == get_option('permalink_structure') || in_array($post->post_status, array('draft', 'pending')) )
			$url = add_query_arg( 'page', $i, get_permalink() );
		elseif ( 'page' == get_option('show_on_front') && get_option('page_on_front') == $post->ID )
			$url = trailingslashit(get_permalink()) . user_trailingslashit("$wp_rewrite->pagination_base/" . $i, 'single_paged');
		else
			$url = trailingslashit(get_permalink()) . user_trailingslashit($i, 'single_paged');
	}

	return esc_url( $url );
}

if(!function_exists('us_pagination')) {
	function us_pagination($pages = 0, $range = 2)
	{
		$showitems = ($range * 2)+1;

		global $paged;
		if(empty($paged)) $paged = 1;

		if($pages == 0)
		{
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if(!$pages)
			{
				$pages = 1;
			}
		}

		$result = '';

		if(1 != $pages)
		{
			if($paged > 1) $result .= '<a href="'.get_pagenum_link($paged - 1).'" class="g-pagination-item to_prev">'.__('Prev', 'us').'</a>';

			for ($i=1; $i <= $pages; $i++)
			{
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				{
					$result .= ($paged == $i)? '<span class="g-pagination-item active">'.$i.'</span>':'<a href="'.get_pagenum_link($i).'" class="g-pagination-item">'.$i.'</a>';
				}
			}

			if ($paged < $pages) $result .= '<a href="'.get_pagenum_link($paged + 1).'" class="g-pagination-item to_next">'.__('Next', 'us').'</a>';

		}

		return $result;
	}
}