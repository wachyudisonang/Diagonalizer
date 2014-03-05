<?php
/*
This is a fork of Devin Price's code for theme options framework.
With a good many additions. Thanks for the code bro!

Contributors: Devin Price
Tags: options, theme options
Donate link: http://bit.ly/options-donate-2
Requires at least: 3.3
Tested up to: 3.5
Stable tag: 1.6
License: GPLv2
*/

/**
 * Class acpt_sanitize
 *
 * Sanitize data before it is output using these functions.
 * You should also use the WP esc functions.
 */
class acpt_sanitize extends acpt {

  /**
   * Sanitize a textarea input field. Removes bad html like <script> and <html>.
   *
   * @param $input
   *
   * @return string
   */
  static function textarea($input) {
    global $allowedposttags;
    $output = wp_kses( $input, $allowedposttags);
    return $output;
  }

  /**
   * Sanitize editor data. Much like textarea remove <script> and <html>.
   * However, if the user can create unfiltered HTML allow it.
   *
   * @param $input
   *
   * @return string
   */
  static function editor($input) {
    if ( current_user_can( 'unfiltered_html' ) ) {
    $output = $input;
    }
    else {
      global $allowedtags;
      $output = wpautop(wp_kses( $input, $allowedtags));
    }
    return $output;
  }

  /**
   * Sanitize Hex Color Value
   *
   * If the hex does not validate return a default instead.
   *
   * @param $hex
   * @param string $default
   *
   * @return string
   */
  static function hex( $hex, $default = '#000000' ) {
    if ( acpt_validate::hex( $hex ) ) {
      return $hex;
    }
    return $default;
  }

}

/**
 * Class acpt_validate
 *
 * Validation for ACPT. Helpful when using acpt_save_filter to check data before saving it.
 */
class acpt_validate extends acpt {

  /**
   * Validate a numeric value
   *
   * Numeric, decimal passes.
   *
   * @param $num
   *
   * @return bool
   */
  static function numeric($num) {
    return is_numeric($num);
  }

  /**
   * Validate a Digit
   *
   * Digits only, no dots, passes.
   *
   * @param $digit
   *
   * @return bool
   */
  static function digits($digit) {
    return !preg_match ("/[^0-9]/", $digit);
  }

  /**
   * Validate ACPT Bracket Syntax
   *
   * For post input name groups. Used for getting values of ACPT forms.
   *
   * @param $group
   *
   * @return int
   */
  static function bracket($group) {
    return preg_match("/^\[.+\]/", $group);
  }

  /**
   * Is a given string a color formatted in hexadecimal notation?
   *
   * @param string $hex
   * @return bool
   *
   */
  static function validate_hex( $hex ) {
    $hex = trim( $hex );
    /* Strip recognized prefixes. */
    if ( 0 === strpos( $hex, '#' ) ) {
      $hex = substr( $hex, 1 );
    }
    elseif ( 0 === strpos( $hex, '%23' ) ) {
      $hex = substr( $hex, 3 );
    }
    /* Regex match. */
    if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
      return false;
    }
    else {
      return true;
    }
  }
}