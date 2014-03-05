<?php
/**
 * Class acpt_utility
 *
 * Functions that have no home for specific class usage.
 */
class acpt_utility {

  /**
   * Set custom post type messages to make more since.
   *
   * @param $messages
   *
   * @return mixed
   */
  static function set_messages($messages) {
    global $post;

    $pt = get_post_type( $post->ID );

    if($pt != 'attachment' && $pt != 'page' && $pt != 'post') :

      $obj = get_post_type_object($pt);
      $singular = $obj->labels->singular_name;

      if($obj->public == true) :
        $view = sprintf( __('<a href="%s">View %s</a>'), esc_url( get_permalink($post->ID)), $singular);
        $preview = sprintf( __('<a target="_blank" href="%s">Preview %s</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ), $singular);
      else :
        $view = $preview = '';
      endif;

      $messages[$pt] = array(
        1 => sprintf( __('%s updated. %s'), $singular , $view),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => sprintf( __('%s updated.'), $singular),
        5 => isset($_GET['revision']) ? sprintf( __('%s restored to revision from %s'), $singular, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('%s published. %s'), $singular, $view ),
        7 => sprintf( __('%s saved.'), $singular),
        8 => sprintf( __('%s submitted. %s'), $singular, $preview ),
        9 => sprintf( __('%s scheduled for: <strong>%1$s</strong>. %s'), $singular, date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ), $preview ),
        10 => sprintf( __('%s draft updated. '), $singular),
      );

    endif;

    return $messages;
  }

  /**
   * Apply ACPT CSS to WP
   */
  static function apply_css() {
    wp_enqueue_style( 'acpt-styles', ACPT_LOCATION . '/'.ACPT_FOLDER_NAME.'/core/css/style.css' );
    wp_enqueue_style( 'acpt-date-picker', ACPT_LOCATION . '/'.ACPT_FOLDER_NAME.'/core/css/date-picker.css' );
  }

  /**
   * Apply ACPT JS to WP
   */
  static function upload_scripts() {

    wp_enqueue_script('acpt-fields', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/fields.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );

    if(function_exists( 'wp_enqueue_media' )){
      wp_enqueue_media();
      wp_enqueue_script('upload', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/upload-3.5.js', array('jquery'));
      wp_enqueue_script('plupload');
      wp_enqueue_script('media-upload');
      wp_enqueue_style('thickbox');
      wp_enqueue_script('thickbox');
    }
    else {
      wp_enqueue_script('plupload');
      wp_enqueue_script('media-upload');
      wp_enqueue_style('thickbox');
      wp_enqueue_script('thickbox');
      wp_enqueue_script('upload', ACPT_LOCATION .'/'.ACPT_FOLDER_NAME.'/core/js/upload.js', array('jquery','media-upload','thickbox'));
    }
  }

  /**
   * ACPT Groups Syntax to Array
   *
   * Convert ACPT bracket syntax into an array and return it.
   *
   * @param $name
   *
   * @return mixed
   */
  static function groups_to_array($name) {
    $regex = '/\[([^]]+)\]/i';
    preg_match_all($regex, $name, $groups, PREG_PATTERN_ORDER);

    return $groups[1];
  }

}