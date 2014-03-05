<?php
/**
 * Get Meta Access Function
 *
 * Get the post meta out of the DB table {prefix}_postmeta.
 * Though it is only a interface for acpt_get::meta it is the preferred method to
 * access data within theme template and plugin files.
 *
 * @param string $name
 * @param string $fallBack
 * @param bool $groups
 * @param null $id
 *
 * @return mixed|null|string
 */
function acpt_meta($name = '', $fallBack = '', $groups = true, $id = null) {
  return acpt_get::meta($name, $fallBack, $groups, $id);
}

/**
 * Echo Meta Data
 *
 * Echo a single string value gotten from acpt_meta().
 *
 * @param string $name
 * @param string $fallBack
 * @param bool $groups
 * @param null $id
 */
function e_acpt_meta($name = '', $fallBack = '', $groups = true, $id = null) {
  $data = acpt_meta($name, $fallBack, $groups, $id);
  ($fallBack !== '' ) ? true : $fallBack = 'No string data '.$name;
  is_string($data) ? true : $data = $fallBack;
  echo $data;
}

/**
 * Access function for acpt_form class
 *
 * @param $name
 * @param array $opts
 *
 * @return acpt_form
 */
function acpt_form($name, $opts=array()) {
  return new acpt_form($name, $opts);
}

/**
 * Access function for acpt_post_type class
 *
 * @param null $singular
 * @param null $plural
 * @param bool $cap
 * @param array $settings
 * @param null $icon
 *
 * @return acpt_post_type
 */
function acpt_post_type( $singular = null, $plural = null, $cap = false, $settings = array(), $icon = null ) {
  return new acpt_post_type($singular, $plural, $cap, $settings);
}

/**
 * Access function for acpt_meta_box class
 *
 * @param null $name
 * @param null $post_type
 * @param array $settings
 *
 * @return acpt_meta_box
 */
function acpt_meta_box($name=null, $post_type = null, $settings=array()) {
  return new acpt_meta_box($name, $post_type, $settings);
}

/**
 * Access function for acpt_tax class
 *
 * @param null $singular
 * @param null $plural
 * @param null $post_type
 * @param bool $hierarchical
 * @param bool $cap
 * @param array $settings
 *
 * @return acpt_tax
 */
function acpt_tax($singular = null, $plural = null, $post_type = null, $hierarchical = false, $cap = false, $settings = array() ) {
  return new acpt_tax($singular, $plural, $post_type, $hierarchical, $cap, $settings );
}

/**
 * Access function for creating a role
 *
 * @param null $role
 * @param array $settings
 * @param null $postType
 *
 * @return acpt_role
 */
function acpt_role($role = null, $settings = array(), $postType = null) {
  return new acpt_role($role, $settings, $postType);
}