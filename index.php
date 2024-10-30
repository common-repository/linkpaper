<?php
/*
Plugin Name: LinkPaper
Plugin URI: http://blackarchdev.com/plugins/linkpaper
Description: An extremely lightweight plugin that can turn any link into a referral link using jQuery. Install, enter your ID, and go. Ability to whitelist or blacklist by domain with a nice Angular backend. Monetize your site instantly.
Version: 1.0.0
Author: Black Arch Development
Author URI: http://blackarchdev.com
License: GPL2
*/


define('LINKPAPER_PLUGIN_URL', dirname(__FILE__) );

include(LINKPAPER_PLUGIN_URL . '/functions.php');
include(LINKPAPER_PLUGIN_URL . '/admin.php');

$wctest = new wctest();

?>