<?php
/**
 * Roles
 *
 * Using the roles methods affects the db. This means any changes will
 * keep. Be sure that you do not run role methods multiple times for this
 * reason. Use on theme or plugin activation. At the end og make and update
 * there is an arg shortcut for custom post type capabilities.
 **/
class acpt_role extends acpt {

  function __construct($role = null, $settings = array(), $postType = null) {
    return $this->make($role, $settings, $postType);
  }

	/**
	 * Make Role. Do not use before init. Use before making a post type.
	 * 
	 * @param string $role formatted role is suggested. role is required
	 * @param array $settings args override and extend
	 * @param array $postType args singular then plueral post type names
   * @return $this
	 */
	function make($role = null, $settings = array(), $postType = null) {
		if(!$role) exit('Making Role: You need to enter a role name.');

		// make name for db
		$computerName = $this->make_computer_name($role);
		add_role($computerName, $role, $settings);

		// custom post type
		if(is_array($postType) && count($postType) === 2) :
			$singular = strtolower($postType[0]);
			$plural = strtolower($postType[1]);
			$role = get_role($computerName);

			$role->add_cap( 'publish_'.$plural );
			$role->add_cap( 'edit_'.$singular );
			$role->add_cap( 'edit_'.$plural );
			$role->add_cap( 'edit_others_'.$plural );
			$role->add_cap( 'delete_'.$singular );
			$role->add_cap( 'delete_'.$plural );
			$role->add_cap( 'delete_others_'.$plural );
			$role->add_cap( 'read_'.$singular );
			$role->add_cap( 'read_private_'.$plural );
		elseif ($postType != null) :
			exit('Post types must be an array with two values. Singular name and plural.');
		endif;

    return $this;
	}
	/**
	 * Update Role. Do not use before init. Use before making a post type.
	 * 
	 * @param string $role name is required
	 * @param array $addCap args override and extend
	 * @param array $removeCap args override and remove
	 * @param array $postType args singular then plueral post type names
   * @return $this
	 */
	function update($role = null, $addCap = null, $removeCap = null, $postType = null) {
		if(!is_string($role)) exit('Updating Role: You need to enter a role name.');

		// role object
		$computerName = $this->make_computer_name($role);
		$role = get_role($computerName);

		// add new caps
		if(is_array($addCap)) :
			for($i = 0, $count = count($addCap); $i < $count; $i++) {
				if(is_string($addCap[$i])) $role->add_cap( $addCap[$i] );
			}
		endif;

		// remove old caps
		if(is_array($removeCap)) :
			for($i = 0, $count = count($removeCap); $i < $count; $i++) {
				if(is_string($removeCap[$i])) $role->remove_cap( $removeCap[$i] );
			}
		endif;

		// custom post type
		if(is_array($postType) && count($postType) === 2) :
			$singular = strtolower($postType[0]);
			$plural = strtolower($postType[1]);

			$role->add_cap( 'publish_'.$plural );
			$role->add_cap( 'edit_'.$singular );
			$role->add_cap( 'edit_'.$plural );
			$role->add_cap( 'edit_others_'.$plural );
			$role->add_cap( 'delete_'.$singular );
			$role->add_cap( 'delete_'.$plural );
			$role->add_cap( 'delete_others_'.$plural );
			$role->add_cap( 'read_'.$singular );
			$role->add_cap( 'read_private_'.$plural );
		elseif ($postType != null) :
			exit('Post types must be an array with two values. Singular name and plural.');
		endif;

    return $this;
	}
	/**
	 * Remove Role. Do not use before init. Use before making a post type.
	 * 
	 * @param string $role name is required
   * @return $this
	 */
	function remove($role = null) {
		if(!is_string($role)) exit('Removing Role: You need to enter a role name.');

		$computerName = $this->make_computer_name($role);
		remove_role( $computerName );

    return $this;
	}

}