<?php
// Include this file in functions.php or plugin
global $wp_version;
if($wp_version < '3.5' || $wp_version == null ): exit('You need the 3.5+ version of WordPress.');
else: $acpt_version = '3.1.1';
endif;

// load config
require_once('config.php');

// load classes
$lib = array(
  'acpt',
  'html',
  'save',
  'utility',
  'getter',
  'validate',
  'post_type',
  'tax',
  'role',
  'form',
  'meta_box'
);

foreach($lib as $value) :
  require_once('core/class-'.$value.'.php');
endforeach;

require_once('core/functions.php');

if($useDepreciated) { require_once('core/depreciated.php'); }

// setup
if(ACPT_MESSAGES) add_filter('post_updated_messages', 'acpt_utility::set_messages' );
add_action('save_post','acpt_save::save_post_fields');
if(ACPT_STYLES) add_action('admin_init', 'acpt_utility::apply_css');
if( is_admin() ) add_action('admin_enqueue_scripts', 'acpt_utility::upload_scripts');

// load plugins
if(ACPT_LOAD_PLUGINS == true) :
	foreach($acptPlugins as $plugin) :

    /*
     * Filter acpt_plugin_folder
     *
     * Set a custom plugin path. This should be a file system level path
     * not relative to http location.
     */
    $pluginsFolder = apply_filters('acpt_plugin_folder', ACPT_FILE_PATH.'/'.ACPT_FOLDER_NAME.'/plugins/');
		if (file_exists($pluginsFolder . $plugin . '/index.php')) :
			$pluginFile = $plugin . '/index.php';
		else :
			$pluginFile =  $plugin . '.php';
		endif;

		include_once($pluginsFolder.$pluginFile);

	endforeach;
endif;