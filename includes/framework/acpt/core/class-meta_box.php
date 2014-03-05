<?php
/**
  * Meta Box
  *
  * This is the long description for a DocBlock. This text may contain
  * multiple lines and even some _markdown_.
  *
  * * Markdown style lists function too
  * * Just try this out once
  *
  * The section after the long description contains the tags; which provide
  * structured meta-data concerning the given element.
  *
  * @author  Kevin Dees
  *
  * @since 0.6
  * @version 0.6
  *
  * @global string $acpt_version
  */
class acpt_meta_box extends acpt {

	public $name = null;
	public $post_type = null;
	public $settings = null;
	public $label = null;

	function __construct($name=null, $post_type = null, $settings=array()) {
		return $this->make($name, $post_type, $settings);
	}

  /**
   * Make Meta Box
   *
   * @param null $name
   * @param null $post_type
   * @param array $settings
   */
  function make($name=null, $post_type = null, $settings=array('context' => 'normal', 'priority' => 'high', 'label' => null, 'callback' => null )) {
		if(!$name) exit('Making Meta Box: You need to enter a name.');

		$computerName = $this->make_computer_name($name);

		$this->name = $computerName;
		if(isset($settings['label'])) $this->label = $settings['label'];

		$default_settings = array(
			'context' => 'normal',
			'priority' => 'high',
			'label' => null,
			'callback' => null );

		$settings = array_merge($default_settings, $settings);

		if(!$settings['label']) $settings['label'] = $name;
		if(!$settings['callback']) $settings['callback'] = 'meta_' . $computerName;

		$this->post_type = $post_type;
		$this->settings = $settings;

    // If a post type has in its support array the meta box add it.
		global $post;
		$type = get_post_type( $post->ID );
		if ( post_type_supports( $type, $computerName ) ) {
			$this->reg($type);
		}

		if(isset($post_type)) $this->add_post_type_support($post_type);
	}


	function add_post_type_support($post_type) {
		// check post type
		if(is_array($post_type)) :

			$the_types = array();

			foreach($post_type as $key => $type ) :

				if(is_string($type)) :
					array_push($the_types, $type);
				elseif( $type instanceof post_type ) :
					array_push($the_types, $type->singular);
				endif;

			endforeach;

			if(is_array($the_types)) :
				foreach($the_types as $key => $type ) :
					$this->reg($type);
				endforeach;
			endif;


		elseif( $post_type instanceof post_type ) :
			$this->reg($post_type->singular);
		elseif(is_string($post_type)) :
			$this->reg($post_type);
		endif;

    return $this;
	}

	function reg($type) {
		add_meta_box(
			$this->name, // id
			$this->settings['label'],
			$this->settings['callback'],
			$type,
			$this->settings['context'],
			$this->settings['priority']
		);

    return $this;
	}
}