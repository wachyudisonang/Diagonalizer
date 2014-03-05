<?php

class acpt_form extends acpt {

  public $name = null;
  public $action = null;
  public $method = null;
  public $group = null;
  public $label = null;
  public $labelTag = null;
  public $bLabel = null;
  public $aLabel = null;
  public $aField = null;

  function __construct($name, $opts=array()) {
    return $this->make($name, $opts);
  }

  /**
   * Make Form.
   *
   * @param string $name singular name is required
   * @param array $opts args [action, method]
   *
   * @return $this
   */
  function make($name, $opts=array()) {
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $opts = $this->set_empty_keys($opts, array('group', 'label', 'labelTag', 'bLabel', 'aLabel', 'aField'));
    $this->group = $this->get_opt_by_test($opts['group'], '');

    if($opts['label'] === false ) {
      $this->label = false;
    } elseif($opts['label'] === true) {
      $this->label = true;
    }

    $this->labelTag = $this->get_opt_by_test($opts['labelTag']);
    $this->bLabel = is_string($opts['bLabel']) ? $opts['bLabel'] : null;
    $this->aLabel = is_string($opts['aLabel']) ? $opts['aLabel'] : null;
    $this->aField = is_string($opts['aField']) ? $opts['aField'] : null;

    if(isset($opts['method'])) :
      $this->method = $opts['method'];
      $field = '<form id="'.$name.'" ';
      $field .= isset($opts['method']) ? 'method="'.$opts['method'].'" ' : 'method="post" ';
      $field .= isset($opts['action']) ? 'action="'.$opts['action'].'" ' : 'action="'.$name.'" ';
      $field .= '>';
    endif;

    if(isset($opts['action'])) $this->method = $opts['action'];

    $this->name = $name;

    if(isset($field)) echo $field;
    wp_nonce_field('nonce_actp_nonce_action','nonce_acpt_nonce_field');
    echo '<input type="hidden" name="save_acpt" value="true" />';

    return $this;
  }

  /**
   * End Form.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   */
  function end($name=null, $opts=array()) {
    if($name) :
      $field = $opts['type'] == 'button' ? '<input type="button"' : '<input type="submit"';
      $field .= 'value="'.esc_attr($name).'" />';
      $field .= '</form>';
    endif;

    if(isset($field)) echo $field;
  }

  /**
   * Form Text.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function text($name, $opts=array(), $label = null) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $field = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "text",
      'field' => $field,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($field . '_filter', $this->get_text_form($args));

    return $this;
  }

  /**
   * Form Color
   *
   * this function works well for making a form element {@link get_color_form()}
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function color($name, $opts=array(), $label = null) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "color color-picker",
      'field' => $fieldName,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($fieldName . '_filter', $this->get_color_form($args));

    return $this;
  }

  /**
   * Form Checkbox
   *
   * this function works well for making a form element {@link get_color_form()}
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function checkbox($name, $opts=array(), $label = null) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $opts['labelTag'] = 'span';
    $fieldName = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "checkbox",
      'field' => $fieldName,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($fieldName . '_filter', $this->get_checkbox_form($args));

    return $this;
  }

  /**
   * Form Textarea.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function textarea($name, $opts=array(), $label = null) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $opts = $this->set_empty_keys($opts, array('group', 'sub'));
    $value = $this->get_field_value($fieldName, $opts['group'], $opts['sub']);

    // value
    if(empty($value)) $value = '';

    $s = $this->get_opts($name, $opts, $fieldName, $label);

    $attr = array(
      'readonly' => $s['read'],
      'class' => "textarea $fieldName {$s['class']}",
      'id' => $s['id'],
      'name' => $s['name'],
      'html' => acpt_sanitize::textarea($value)
    );
    $field = acpt_html::element('textarea', $attr);

    $dev_note = $this->dev_message($fieldName, $opts['group'], $opts['sub']);

    echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['aField']);

    return $this;
  }

  /**
   * Form Select.
   *
   * @param string $name singular name is required
   * @param array $options values for select options
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function select($name, $options=array('Key' => 'Value'), $opts=array(), $label = null) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $optionsList = '';
    $fieldName = $this->get_field_name($name);

    // get options HTML
    if(isset($options)) :

      $opts = $this->set_empty_keys($opts, array('group', 'sub'));
      $value = $this->get_field_value($fieldName, $opts['group'], $opts['sub']);

      foreach( $options as $key => $option) :
        if($option == $value)
          $selected = 'selected="selected"';
        else
          $selected = null;

        if(array_key_exists('select_key', $opts) && $opts['select_key'] == true)
          true;
        else
          $key = $option;

        $option = esc_attr($option);

        $optionsList .= "<option $selected value=\"$option\">$key</option>";
      endforeach;

    endif;

    $s = $this->get_opts($name, $opts, $fieldName, $label);

    $attr = array(
      'readonly' => $s['read'],
      'class' => "select $fieldName {$s['class']}",
      'id' => $s['id'],
      'name' => $s['name'],
      'html' => $optionsList
    );
    $field = acpt_html::element('select', $attr);
    $dev_note = $this->dev_message($fieldName, $opts['group'], $opts['sub']);

    echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['aField']);

    return $this;
  }

  /**
   * Form Radio.
   *
   * @param string $name singular name is required
   * @param array $options values for radio options
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function radio($name, $options=array('Key' => 'Value'), $opts=array(), $label = null) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $optionsList = '';
    $opts['labelTag'] = 'span';
    $fieldName = $this->get_field_name($name);

    // name
    $s = $this->get_opts($name, $opts, $fieldName, $label);

    // get options HTML
    if(!empty($options)) :

      $opts = $this->set_empty_keys($opts, array('group', 'sub'));
      $value = $this->get_field_value($fieldName, $opts['group'], $opts['sub']);

      foreach( $options as $key => $option) :
        if($option == $value)
          $checked = 'checked';
        else
          $checked = null;

        if(array_key_exists('select_key', $opts) && $opts['select_key'] == true)
          true;
        else
          $key = $option;

        $anOption = array(array(
          'label' => array(
            'html' => array(array(
              'input' => array(
                'type' => 'radio',
                'name' => $s['name'],
                'value' => esc_attr($option),
                'checked' => $checked
              )
            ), array(
              'span' => array(
                'html' => $key
              )
            ))
          )
        ));

        $optionsList .= acpt_html::make_html($anOption);

      endforeach;

    endif;

    $attr = array(
      'readonly' => $s['read'],
      'class' => "radio $fieldName {$s['class']}",
      'id' => $s['id'],
      'html' => $optionsList
    );
    $field = acpt_html::element('div', $attr);
    $dev_note = $this->dev_message($fieldName, $opts['group'], $opts['sub']);

    echo apply_filters($fieldName . '_filter', $s['bLabel'].$s['label'].$s['aLabel'].$field.$dev_note.$s['help'].$s['aField']);

    return $this;
  }

  /**
   * Form WP Editor.
   *
   * In the $editor_setteings set array('teeny' => true) to have a smaller editor
   *
   * @param string $name singular name is required
   * @param bool $label text for the label
   * @param array $opts args override and extend wp_editor
   * @param array $editor_settings
   * @return $this
   */
  function editor($name, $label=null, $opts=array(), $editor_settings = array()) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $opts = $this->set_empty_keys($opts, array('group', 'sub'));
    $group = $this->get_opt_by_test($opts['group'], '');
    $sub = $this->get_opt_by_test($opts['sub'], '');

    $v = $this->get_field_value($fieldName, $group, $sub);
    $s = $this->get_opts($label, array('labelTag' => 'span'), $fieldName, true);


    echo '<div class="control-group">';
    echo $s['label'];
    wp_editor(
        acpt_sanitize::editor($v),
        'wysisyg_'.$fieldName,
        array_merge($editor_settings, array('textarea_name' => $this->get_acpt_post_name($fieldName, $group, $sub)))
    );
    echo $this->dev_message($fieldName, $group, $sub);
    echo '</div>';

    return $this;
  }

  /**
   * Form Image
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function image($name, $opts=array(), $label = null) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "image upload-url",
      'field' => $fieldName,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($fieldName . '_filter', $this->get_image_form($args));

    return $this;
  }

  /**
   * Form File
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function file($name, $opts=array(), $label = null) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "file upload-url",
      'field' => $fieldName,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($fieldName . '_filter', $this->get_file_form($args));

    return $this;
  }

  /**
   * Google Maps.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function google_map($name, $opts=array(), $label = true) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "googleMap",
      'field' => $fieldName,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($fieldName . '_filter', $this->get_google_map_form($args));

    return $this;
  }

  /**
   * Date.
   *
   * @param string $name singular name is required
   * @param array $opts args override and extend
   * @param bool $label show label or not
   * @return $this
   */
  function date($name, $opts=array(), $label = true) {
    $this->test_for($this->name, 'Making Form: You need to make the form first.');
    $this->test_for($name, 'Making Form: You need to enter a singular name.');

    $fieldName = $this->get_field_name($name);

    $args = array(
      'name' => $name,
      'opts' => $opts,
      'classes' => "date date-picker",
      'field' => $fieldName,
      'label' => $label,
      'html' => ''
    );

    echo apply_filters($fieldName . '_filter', $this->get_date_form($args));

    return $this;
  }

  /**
   * Get Date Form
   *
   * @param $o
   *
   * @return string
   */
  function get_date_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);
    //$o['opts']['readonly'] = $this->get_opt_by_test($o['opts']['readonly'], true);

    return $this->get_text_form($o);
  }

  /**
   * Get Image Form
   *
   * @param $o
   *
   * @return string
   */
  function get_image_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);
    $o['opts']['readonly'] = $this->get_opt_by_test($o['opts']['readonly'], true);

    // setup for grouping
    $group = $this->get_opt_by_test($o['opts']['group'], '');
    $sub = $this->get_opt_by_test($o['opts']['sub'], '');
    $field = $o['field'];

    $value = $this->get_field_value($field, $group, $sub);
    $name = $this->get_acpt_post_name($field.'_id', $group, $sub);

    // button text
    $btnValue = $this->get_opt_by_test($o['opts']['button'], "Insert Image", $o['opts']['button']);

    // placeholder image and image id value
    if(!empty($value)) :
      $placeHolderImage = '<img class="upload-img" src="'.esc_url($value).'" />';
      $vID = $this->get_field_value($field.'_id', $group, $sub);
    else :
      $vID = $placeHolderImage = '';
    endif;

    $attachmentID = acpt_html::input(array(
      'type' => 'hidden',
      'class' => 'attachment-id-hidden',
      'name' => $name,
      'value' => esc_attr($vID)
    ));

    $btn = array('input' => array(
      'type' => 'button',
      'class' => 'button-primary upload-button',
      'value' => esc_attr($btnValue)
    ));

    $phRemove = array(
        'a' => array(
          'class' => 'remove-image',
          'html' => 'remove'
        )
    );

    $phImg = array(
      'none' => array(
        'html' => $placeHolderImage
      )
    );

    $ph = array(
      'div' => array(
        'class' => 'image-placeholder',
        'html' => array(
          $phRemove,
          $phImg
        )
      )
    );

    $html = array($attachmentID, $btn, $ph );

    $o['html'] = acpt_html::make_html($html);

    return $this->get_text_form($o);
  }

  /**
   * Checkbox Get Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_checkbox_form($o) {
    $opts = $this->set_empty_keys($o['opts']);
    $desc = isset($opts['desc']) ? $opts['desc'] : $opts['label'];
    $group = $opts['group'];
    $sub = $opts['sub'];
    $s = $this->get_opts($o['name'], $opts, $o['field'], $o['label']);
    $v = $this->get_field_value($o['field'], $group, $sub);

    if( $v == 1 ) $checked = array('checked' => 'checked');
    else $checked = array();

    $attr = array(
      'class' => "{$o['classes']}  acpt_{$o['field']} {$s['class']}",
      'type' => 'checkbox',
      'value' => 1,
      'name' => $s['name'],
      'id' => $s['id'],
      'readonly' => $s['read']
    );

    $attr = array_merge($attr, $checked);

    $input = acpt_html::input($attr);

    $l = acpt_html::element('none', array('html' => " {$desc}" ), null);

    $default = acpt_html::element('input', array(
        'type' => 'hidden',
        'name' => $s['name'],
        'value' => '0'
      ), null);

    $field = acpt_html::element('label', array(
        'html' => array($default, $input, $l)
      ));

    ;

    $dev_note = $this->dev_message($o['field'], $group, $sub);

    return $s['bLabel'].$s['label'].$s['aLabel'].$field.$o['html'].$dev_note.$s['help'].$s['aField'];
  }

  /**
   * Get Google Map Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_google_map_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);

    // setup for grouping
    $group = $this->get_opt_by_test($o['opts']['group'], '');
    $sub = $this->get_opt_by_test($o['opts']['sub'], '');
    $field = $o['field'].'_encoded';

    $value = $this->get_field_value($field, $group, $sub);
    $name = $this->get_acpt_post_name($field, $group, $sub);

    // set http
    if (is_ssl()) $http = 'https://';
    else $http = 'http://';

    // zoom
    if(empty($value)) $zoom = 1;
    else $zoom = 15;

    $attrName = acpt_html::make_html_attr('name', $name);

    $o['html'] = "<input type=\"hidden\" class=\"googleMap-encoded\" value=\"{$value}\" {$attrName} />";
    $o['html'] .= '<p class="map"><img src="'.$http.'maps.googleapis.com/maps/api/staticmap?center='.$value.'&zoom='.$zoom.'&size=1200x140&sensor=true&markers='.$value.'" class="map-image" alt="Map Image" /></p>';

    return $this->get_text_form($o);
  }


  /**
   * Get Color Form
   *
   * get color input and form data
   *
   * @param $args
   *
   * @return string
   */
  protected function get_color_form($args) {
    global $acptPalette, $acptDefaultColor;

    if(!isset($args['opts']['palette'])) {
      $args['opts']['palette'] = $acptPalette;
    }

    if(!isset($args['opts']['default'])) {
      $args['opts']['default'] = $acptDefaultColor;
    }

    wp_localize_script('acpt-fields', 'acpt_'.$args['field'].'_color_palette', $args['opts']['palette'] );
    wp_localize_script('acpt-fields', 'acpt_'.$args['field'].'_defaultColor', $args['opts']['default'] );

    return $this->get_text_form($args);
  }

  /**
   * Get File Form
   *
   * @param $o
   *
   * @return string
   */
  function get_file_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);

    // setup for grouping
    $group = $this->get_opt_by_test($o['opts']['group'], '');
    $sub = $this->get_opt_by_test($o['opts']['sub'], '');
    $field = $o['field'].'_id';

    $value = $this->get_field_value($field, $group, $sub);
    $name = $this->get_acpt_post_name($field, $group, $sub);

    if(empty($o['opts']['readonly'])) $o['opts']['readonly'] = true;

    // button
    if(isset($o['opts']['button'])) :
      $button = $o['opts']['button'];
    else :
      $button = "Insert File";
    endif;

    // placeholder image and image id value
    if(isset($value)) :
      $valueID = acpt_html::make_html_attr('value', esc_attr($value));
    else :
      $valueID = '';
    endif;

    $attrName = acpt_html::make_html_attr('name', $name); // $o['field'].'_id', $o['opts']['group'], $o['opts']['sub']);

    $o['html'] = "<input type=\"hidden\" class=\"attachment-id-hidden\" {$attrName} {$valueID}>";
    $o['html'] .= '<input type="button" class="button-primary upload-button" value="'.$button.'"> <span class="clear-attachment">clear file</span>';

    return $this->get_text_form($o);
  }

  /**
   * Get Text Form
   *
   * @param $o
   *
   * @return string
   */
  protected function get_text_form($o) {
    $o['opts'] = $this->set_empty_keys($o['opts']);
    $s = $this->get_opts($o['name'], $o['opts'], $o['field'], $o['label']);
    $v = $this->get_field_value($o['field'], $o['opts']['group'], $o['opts']['sub']);

    $field = acpt_html::input(array(
        'class' => "{$o['classes']}  acpt_{$o['field']} {$s['class']}",
        'type' => 'text',
        'value' => esc_attr($v),
        'name' => $s['name'],
        'id' => $s['id'],
        'readonly' => $s['read']
    ), true);

    $dev_note = $this->dev_message($o['field'], $o['opts']['group'], $o['opts']['sub']);

    return $s['bLabel'].$s['label'].$s['aLabel'].$field.$o['html'].$dev_note.$s['help'].$s['aField'];
  }

  /**
   * Get Input Options
   *
   * Testing each field needs to prevent errors.
   *
   * @param $name
   * @param $opts
   * @param $fieldName
   * @param $label
   *
   * @return mixed
   */
  protected function get_opts($name, $opts, $fieldName, $label) {
    $opts = $this->set_empty_keys($opts);

    // help text
    $help = acpt_html::element('p', array(
        'class' => 'help-text',
        'html' => $opts['help']
    ));
    $s['help'] = $this->get_opt_by_test($opts['help'], '', $help);

    // attributes
    $s['class'] = $this->get_opt_by_test($opts['class']);
    $s['read'] = $this->get_opt_by_test($opts['readonly']);
    $group = $this->get_opt_by_test($opts['group'], '');
    $sub = $this->get_opt_by_test($opts['sub'], '');
    $s['name'] = $this->get_acpt_post_name($fieldName, $group, $sub);
    $s['id'] = 'acpt_'.$fieldName;

    // label
    $labelSettings = $this->get_input_label($s, $opts, $name, $label);
    $s = array_merge($s, $labelSettings);

    return $s;
  }

  /**
   * Setup Label Data
   *
   * Grab label data from the form object and from each inputs settings.
   *
   * @param $s
   * @param $opts
   * @param $name
   * @param $label
   *
   * @return mixed
   */
  private function get_input_label($s, $opts, $name, $label) {

    // is there a label at all?
    if(is_null($label) && is_bool($this->label)) {
      $label = $this->label;
    } elseif( is_null($label) ) {
      $label = true;
    }

    if( is_string($this->bLabel) && is_null($opts['bLabel']) ) {
      $opts['bLabel'] = $this->bLabel;
    }

    if( is_string($this->aLabel) && is_null($opts['aLabel'])) {
      $opts['aLabel'] = $this->aLabel;
    }

    if( is_string($this->aField) && is_null($opts['aField'])) {
      $opts['aField'] = $this->aField;
    }

    $opts['labelTag'] = $this->get_opt_by_test($this->labelTag, $opts['labelTag']);

    $s['bLabel'] = is_null($opts['bLabel']) ? BEFORE_LABEL : $opts['bLabel'];
    $s['aLabel'] = is_null($opts['aLabel']) ? AFTER_LABEL : $opts['aLabel'];
    $s['aField'] = is_null($opts['aField']) ? AFTER_FIELD : $opts['aField'];
    $opts['labelTag'] = $this->get_opt_by_test($opts['labelTag'], 'label');

    // show label?
    if($label === true) :
      $s['label'] = acpt_html::element($opts['labelTag'], array(
          'class' => 'control-label',
          'for' => $s['id'],
          'html' => $this->get_opt_by_test($opts['label'], $name)
        ));
    else :
      $s['label'] = '';
    endif;

    return $s;
  }

  /**
   * Get Dev Note
   *
   * Add the dev field to the admin to see the a acpt_meta() function
   *
   * @param $fieldName
   * @param $group
   * @param $sub
   *
   * @return string
   */
  protected function dev_message($fieldName, $group, $sub) {
    $group = $this->get_opt_by_test($group, $this->group);

    if(DEV_MODE == true) :
        $v = "acpt_meta('{$group}[{$fieldName}]{$sub}');";
    else :
        $v = '';
    endif;

    return acpt_html::input(array(
        'class' => 'dev_note',
        'readonly' => true,
        'value' => esc_attr($v)
    ), true);
  }

  /**
   * Get Field Name
   *
   * @param $name
   *
   * @return string
   */
  protected function get_field_name($name) {
    return $this->name.'_'.$name;
  }
  /**
   * Get Field Value
   *
   * Get the value if it is a post type or another page form
   *
   * @param mixed|string $field
   * @param string $group
   * @param string $sub
   *
   * @return mixed|null|string
   */
  protected function get_field_value($field, $group, $sub) {
    global $post;
    $group = $this->get_opt_by_test($group, $this->group);

    if(isset($post->ID)) :
      $value = acpt_get::meta("{$group}[{$field}]{$sub}");
    else :
      $value = acpt_get::option("{$group}[{$field}]{$sub}");
    endif;

    return $value;
  }

  /**
   * Get $_POST Name
   *
   * This will set the name value for a field
   *
   * @param $field
   * @param string $group
   * @param string $sub
   *
   * @return string
   */
  private function get_acpt_post_name($field, $group, $sub ) {
    $post_name = $this->get_bracket_syntax($field, $group, $sub);

    return "acpt{$post_name}";
  }

  /**
   * Compile bracket syntax for usage
   *
   * @param $field
   * @param $group
   * @param $sub
   *
   * @return string
   */
  private function get_bracket_syntax($field, $group, $sub ) {
    $group = $this->get_opt_by_test($group, $this->group);

    if(!acpt_validate::bracket($group) && $group != '' ) {
      $this->test_for(false, 'ACPT ERROR: You need to to the form group to an array format ['.$group.']');
    }

    if(!acpt_validate::bracket($sub) && $sub != '' ) {
      $this->test_for(false, 'ACPT ERROR: You need to to the form sub group to an array format ['.$group.']');
    }

    return "{$group}[{$field}]{$sub}";
  }

}