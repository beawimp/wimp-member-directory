<?php
/**
 * Plugin Name: WIMP Member Directory
 * Plugin URI:  http://beawimp.org/member-directory
 * Description: Creates a Member Directory for WIMP!
 * Version:     0.1.0
 * Author:      Cole Geissinger
 * Author URI:  http://www.colegeissinger.com
 * License:     GPLv2+
 * Text Domain: wmd
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2014 Cole Geissinger (email : cole@beawimp.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

// Useful global constants
define( 'WMD_VERSION',  '0.1.0' );
define( 'WMD_URL',      plugin_dir_url( __FILE__ ) );
define( 'WMD_PATH',     dirname( __FILE__ ) . '/' );
define( 'WMD_ASSETS',   WMD_URL . 'assets/' );
define( 'WMD_INCLUDES', WMD_PATH . 'includes/' );
define( 'WMD_LIB',      WMD_PATH . 'includes/lib/' );

// Includes
include_once WMD_INCLUDES . 'class-wmd-utils.php';
include_once WMD_INCLUDES . 'shortcodes.php';
include_once WMD_INCLUDES . 'class-wmd-member-directory.php';

include_once WMD_PATH . 'post-types/class-wmd-post-types.php';
include_once WMD_PATH . 'taxonomies/class-wmd-taxonomies.php';

register_activation_hook(   __FILE__, array( 'WMD_Utils', 'wmd_activate'   ) );
register_deactivation_hook( __FILE__, array( 'WMD_Utils', 'wmd_deactivate' ) );

// Wireup actions
add_action( 'init',                  array( 'WMD_Utils',      'wmd_init' ) );
add_action( 'init',                  array( 'WMD_Post_Types', 'wmd_member_directory_init' ) );
add_filter( 'post_updated_messages', array( 'WMD_Post_Types', 'wmd_member_directory_updated_messages' ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'price_range_init' ) );

// Wireup filters

// Wireup shortcodes
