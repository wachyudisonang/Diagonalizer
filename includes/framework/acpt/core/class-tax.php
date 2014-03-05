<?php
/**
  * Taxonomy
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
class acpt_tax extends acpt {

	public $singular = null;
	public $plural = null;
	public $args = array();

	function __construct($singular = null, $plural = null, $post_type = null, $hierarchical = false, $cap = false, $settings = array() ) {
		return $this->make($singular, $plural, $post_type, $hierarchical, $cap, $settings);
	}

	/**
	* Make Taxonomy. Do not use before init.
	*
	* @param string $singular singular name is required
	* @param string $plural plural name is required
	* @param boolean $hierarchical add hierarchy
	* @param boolean $cap turn on custom capabilities
	* @param string|array $post_type set the post types which to apply taxonomy (null is an option)
	* @param array $settings args override and extend
  * @return $this
	*/
	function make($singular = null, $plural = null, $post_type = null, $hierarchical = false, $cap = false, $settings = array() ) {
		if(!$singular) exit('Making Taxonomy: You need to enter a singular name.');
		if(!$plural) exit('Making Taxonomy: You need to enter a plural name.');

		$upperPlural = ucwords($plural);
		$upperSingular = ucwords($singular);

		// setup object for later use
		$this->plural = $plural;
		$this->singular = $singular;
		
		$labels = array(
		    'name' => _x( $upperPlural, 'taxonomy general name' ),
		    'singular_name' => _x( $upperSingular, 'taxonomy singular name' ),
		    'search_items' =>  'Search '.$upperPlural,
		    'all_items' => 'All '.$upperPlural,
		    'parent_item' => 'Parent '.$upperSingular,
		    'parent_item_colon' => 'Parent '.$upperSingular.':',
		    'edit_item' => 'Edit '.$upperSingular, 
		    'update_item' => 'Update '.$upperSingular,
		    'add_new_item' => 'Add New '.$upperSingular,
		    'new_item_name' => 'New '.$upperSingular.' Name',
		    'menu_name' => $upperSingular,
		);
		
		$capabilities = array(
			'manage_terms' => 'manage_'.$plural,
		    'edit_terms' => 'manage_'.$plural,
		    'delete_terms' => 'manage_'.$plural,
		    'assign_terms' => 'edit_posts',
		);
		
		// hierarchical
		if($hierarchical === true) :
			$hierarchical = array('hierarchical' => true,);
		else :
			$hierarchical = array();
			$specialLabels = array(
			    'popular_items' => 'Popular '.$upperPlural,
			    'separate_items_with_commas' => 'Separate '.$singular.' with commas',
			    'add_or_remove_items' => 'Add or remove '.$singular,
			    'choose_from_most_used' => 'Choose from the most used '.$singular,
			);
			$labels = array_merge($labels, $specialLabels);
		endif;
		// capabilities
		if($cap === true) :
			$cap = array('capabilities' => $capabilities,);
		else :
			$cap = array();
		endif;
		
		$args = array(
		    'labels' => $labels,
		    'show_ui' => true,
		    'rewrite' => array( 'slug' => sanitize_title($singular) ),
		);
		
		$this->args = array_merge($args, $hierarchical, $cap, $settings);

		// check post type
		if(is_array($post_type)) :

			$the_types = array();

			foreach($post_type as $key => $type ) :

				if(is_string($type)) :
					array_push($the_types, $type);
				elseif( $type instanceof acpt_post_type ) :
					array_push($the_types, $type->singular);
				endif;

			endforeach;

			$this->reg($the_types);

		elseif( $post_type instanceof acpt_post_type ) :
			$this->reg($post_type->singular);
		elseif(is_string($post_type)) :
			$this->reg($post_type);
		endif;

    return $this;
	}

	function reg($post_type) {
		register_taxonomy($this->singular, $post_type, $this->args);

    return $this;
	}
}