<?php

// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

class US_Shortcodes {

	public function __construct()
	{
		add_filter('the_content', array($this, 'paragraph_fix'));
		add_filter('the_content', array($this, 'sections_fix'), 12);
		add_filter('the_excerpt', array($this, 'sections_fix'), 12);
	}

	public function paragraph_fix($content)
	{
		$array = array (
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']',
			']<br>' => ']',
		);

		$content = strtr($content, $array);
		return $content;
	}

	public function sections_fix($content)
	{
		$link_pages_args = array(
			'before'           => '<div class="w-blog-pagination"><div class="g-pagination">',
			'after'            => '</div></div>',
			'next_or_number'   => 'next_and_number',
			'nextpagelink'     => __('Next', 'us'),
			'previouspagelink' => __('Previous', 'us'),
			'echo'             => 0
		);

		global $disable_section_shortcode;

		if ($disable_section_shortcode)
		{
			add_shortcode('section', array($this, 'section_dummy'));
			$content = $content.us_wp_link_pages($link_pages_args);
			return do_shortcode($content);
		}

		add_shortcode('section', array($this, 'section'));

		if (strpos($content, '[section') !== FALSE)
		{
			$content = strtr($content, array('[section' => '[/section automatic_end_section="1"][section'));

			$content = strtr($content, array('[/section]' => '[/section][section]'));

			$content = strtr($content, array('[/section automatic_end_section="1"]' => '[/section]'));

			$content = '[section]'.$content.us_wp_link_pages($link_pages_args).'[/section]';
		}
		else
		{
			$content = '[section]'.$content.us_wp_link_pages($link_pages_args).'[/section]';
		}

		$content = preg_replace('%\[section\](\\s)*\[/section\]%i', '', $content);//echo '<textarea>'.str_replace('[/section]', "[/section]\n\n", $content).'</textarea>';

		return do_shortcode($content);
	}

	public function section ($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'background' => FALSE,
				'img' => FALSE,
				'parallax' => FALSE,
				'parallax_speed' => FALSE,
				'full_width' => FALSE,

			), $attributes);

		$output_type = ($attributes['background'] != '')?' color_'.$attributes['background']:'';
		$full_width_type = ($attributes['full_width'] != '')?' type_fullwidth':'';
		$background_style = '';
		if ($attributes['img'] != '')
		{
			//$output_type = ' type_background';
			if (is_numeric($attributes['img']))
			{
				$img_id = preg_replace('/[^\d]/', '', $attributes['img']);
				$img = wpb_getImageBySize(array( 'attach_id' => $img_id, 'thumb_size' => 'full' ));

				if ( $img != NULL )
				{
					$img = wp_get_attachment_image_src( $img_id, 'full');
					$img = $img[0];
				}

				$background_style = ' style="background-image: url('.$img.')"';
			}
			else
			{
				$background_style = ' style="background-image: url('.$attributes['img'].')"';
			}

		}

		$section_id_string = '';
		$parallax_class = '';
		$js_output = '';
		if ($attributes['parallax']) {
			$section_id = 'section_'.rand(99999, 999999);
			$section_id_string = ' id="'.$section_id.'"';
			$parallax_class = ' with_parallax';

			$js_output = "<script>jQuery(window).load(function(){ jQuery('#".$section_id."').parallax('50%', '".$attributes['parallax_speed']."'); });</script>";
		}

		$no_sidebar = ( !roots_display_sidebar() )?'no-sidebar':'';

		$output =	'<div class="wrapper l-submain '.$no_sidebar.$full_width_type.$output_type.$parallax_class.'"'.$background_style.$section_id_string.'>'.
						'<div class="sub-wrapper l-submain-h g-html ">'.
							do_shortcode($content).
						'</div>'.
					'</div>'.$js_output;

		return $output;
	}
}

global $us_shortcodes;
$us_shortcodes = new US_Shortcodes;

