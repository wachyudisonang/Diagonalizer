<?php
/**
  * Post Type
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
class acpt_post_type extends acpt {

	public $singular = null;
	public $plural = null;
	public $icon = null;
	public $icon_pos = array(
		'notebook' => array('a' => '2px -3px', 'i' => '2px -35px'),
		'refresh' => array('a' => '-30px -3px', 'i' => '-30px -35px'),
		'thumbs-up' => array('a' => '-60px -3px', 'i' => '-60px -35px'),
		'box' => array('a' => '-86px -3px', 'i' => '-86px -35px'),
		'bug' => array('a' => '-135px -3px', 'i' => '-135px -35px'),
		'cake' => array('a' => '-159px -3px', 'i' => '-159px -35px'),
		'calendar' => array('a' => '-182px -3px', 'i' => '-182px -35px'),
		'card-biz' => array('a' => '-230px -3px', 'i' => '-230px -35px'),
		'task' => array('a' => '-254px -3px', 'i' => '-254px -35px'),
		'clock' => array('a' => '-279px -3px', 'i' => '-279px -35px'),
		'color' => array('a' => '-303px -3px', 'i' => '-303px -35px'),
		'compass' => array('a' => '-326px -3px', 'i' => '-326px -35px'),
		'dine' => array('a' => '-350px -3px', 'i' => '-350px -35px'),
		'ipad' => array('a' => '-371px -3px', 'i' => '-371px -35px'),
		'ticket' => array('a' => '-392px -3px', 'i' => '-392px -35px'),
		'shirt' => array('a' => '-420px -3px', 'i' => '-420px -35px'),
		'pulse' => array('a' => '-442px -3px', 'i' => '-442px -35px'),
		'card-play' => array('a' => '-464px -3px', 'i' => '-464px -35px'),
		'dine-plate' => array('a' => '-485px -3px', 'i' => '-485px -35px'),
		'pill' => array('a' => '-510px -3px', 'i' => '-510px -35px'),
		'plane' => array('a' => '-531px -3px', 'i' => '-531px -35px'),
		'paint' => array('a' => '-557px -3px', 'i' => '-557px -35px'),
		'mic' => array('a' => '-580px -3px', 'i' => '-580px -35px'),
		'location' => array('a' => '-601px -3px', 'i' => '-601px -35px'),
		'leaf' => array('a' => '-622px -3px', 'i' => '-622px -35px'),
		'music' => array('a' => '-643px -3px', 'i' => '-643px -35px'),
		'wine' => array('a' => '-665px -3px', 'i' => '-665px -35px'),
		'dashboard' => array('a' => '-688px -3px', 'i' => '-688px -35px'),
		'person' => array('a' => '-711px -3px', 'i' => '-711px -35px'),
		'weather' => array('a' => '-735px -3px', 'i' => '-735px -35px')
	);

	function __construct( $singular = null, $plural = null, $cap = false, $settings = array(), $icon = null ) {
		return $this->make($singular, $plural, $cap, $settings);
	}

  /**
   * Add Icon to Post Type Menu Item
   *
   * @param $name
   *
   * @return $this
   */
  function icon($name) {
		if(!array_key_exists($name, $this->icon_pos)) exit('Adding Icon: You need to enter a valid icon name. You used ' . $name);

		$this->icon = $name;
		add_action( 'admin_head', array($this, 'set_icon_css') );

    return $this;
	}

  /**
   * Add CSS for Post Type Menu Icon
   */
  function set_icon_css() { ?>

		<style type="text/css">
			#adminmenu #menu-posts-<?php echo $this->singular; ?> .wp-menu-image {
			  background-image: url('<?php echo ACPT_LOCATION; ?>/<?php echo ACPT_FOLDER_NAME; ?>/core/img/menu.png');
			}

			#adminmenu #menu-posts-<?php echo $this->singular; ?> .wp-menu-image {
			  background-position: <?php echo $this->icon_pos[$this->icon]['i']; ?>;
			}

			#adminmenu #menu-posts-<?php echo $this->singular; ?>:hover div.wp-menu-image,
			#adminmenu #menu-posts-<?php echo $this->singular; ?>.wp-has-current-submenu div.wp-menu-image,
			#adminmenu #menu-posts-<?php echo $this->singular; ?>.current div.wp-menu-image {
			  background-position: <?php echo $this->icon_pos[$this->icon]['a']; ?>;
			}
		</style>

	<?php }

	/**
	 * Make Post Type. Do not use before init.
	 *
	 * @param string $singular singular name is required
	 * @param string $plural plural name is required
	 * @param boolean $cap turn on custom capabilities
	 * @param array $settings args override and extend
   * @return $this
	 */
	function make($singular = null, $plural = null, $cap = false, $settings = array() ) {
		if(!$singular) exit('Making Post Type: You need to enter a singular name.');
		if(!$plural) exit('Making Post Type: You need to enter a plural name.');

		// make lowercase
		$singular = strtolower($singular);
		$plural = strtolower($plural);

		// setup object for later use
		$this->plural = $plural;
		$this->singular = $singular;

		// make uppercase
		$upperSingular = ucwords($singular);
		$upperPlural = ucwords($plural);

		$labels = array(
			'name' => $upperPlural,
			'singular_name' => $upperSingular,
			'add_new' => 'Add New',
			'add_new_item' => 'Add New '.$upperSingular,
			'edit_item' => 'Edit '.$upperSingular,
			'new_item' => 'New '.$upperSingular,
			'view_item' => 'View '.$upperSingular,
			'search_items' => 'Search '.$upperPlural,
			'not_found' =>  'No '.$plural.' found',
			'not_found_in_trash' => 'No '.$plural.' found in Trash',
			'parent_item_colon' => '',
			'menu_name' => $upperPlural,
		);

		$capabilities = array(
			'publish_posts' => 'publish_'.$plural,
			'edit_post' => 'edit_'.$singular,
			'edit_posts' => 'edit_'.$plural,
			'edit_others_posts' => 'edit_others_'.$plural,
			'delete_post' => 'delete_'.$singular,
			'delete_posts' => 'delete_'.$plural,
			'delete_others_posts' => 'delete_others_'.$plural,
			'read_post' => 'read_'.$singular,
			'read_private_posts' => 'read_private_'.$plural,
		);

		if($cap === true) :
			$cap = array(
				'capability_type' => $singular,
				'capabilities' => $capabilities,
			);
		else :
			$cap = array();
		endif;

		$args = array(
			'labels' => $labels,
			'description' => $plural,
			'rewrite' => array( 'slug' => sanitize_title($plural)),
			'public' => true,
			'has_archive' => true,
		);

		$args = array_merge($args, $cap, $settings);

		// Register post type
		register_post_type($singular, $args);

    return $this;
	}
}