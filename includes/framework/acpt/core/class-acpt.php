<?php
/**
 * Class acpt
 *
 * Base class for other classes to extend
 */
class acpt {

  function __construct() {}

  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    } else {
      return null;
    }
  }

  public function __set($property, $value) {
    if (property_exists($this, $property)) {
      $this->$property = $value;
    }

    return $this;
  }

  /**
   * Replace white space with underscore and make all text lowercase
   *
   * @param $name
   *
   * @return mixed
   */
  protected function make_computer_name($name) {
    $pattern = '/(\s+)/';
    $replacement = '_';
    $computerName = preg_replace($pattern,$replacement,strtolower(trim($name)));
    return $computerName;
  }

  /**
   * Test for value if there is none die.
   *
   * @param $data
   * @param $error
   * @param string $type
   */
  protected function test_for($data, $error, $type = 'string') {
    switch($type) {
      case 'array' :
        if(isset($data) && !is_array($data) ) die('ACPT Error: '. $error);
        break;
      case 'bool' :
        if(isset($data) && !is_bool($data) ) die('ACPT Error: '. $error);
        break;
      default:
        if(empty($data) && !is_string($data)) die('ACPT Error: '. $error);
        break;
    }
  }

  /**
   * Set Empty Keys
   *
   * @param $opts
   * @param mixed $desired_keys
   *
   * @return mixed
   */
  protected function set_empty_keys($opts, $desired_keys = false) {
    $keys = array_keys($opts);

    if($desired_keys === false) {
      $desired_keys = array('readonly', 'group', 'sub', 'button', 'help', 'bLabel', 'aLabel', 'aField', 'label', 'labelTag', 'class');
    }

    foreach($desired_keys as $desired_key){
      if(in_array($desired_key, $keys)) continue;
      $opts[$desired_key] = null;
    }

    return $opts;
  }

  /**
   * Get Options By Test
   *
   * Setting the $return field will send those results if the test passes.
   * Default is sent on a failing test.
   *
   * @param $test
   * @param string $fail
   * @param bool $pass
   *
   * @return bool|string
   */
  protected function get_opt_by_test($test, $fail = '', $pass = true) {
    $pass = ($pass === true) ? $test : $pass;
    return (isset($test) && $test != '') ? $pass : $fail;
  }

}