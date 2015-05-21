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

// Deny any direct accessing of this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Useful global constants
define( 'WMD_VERSION',   '0.1.0'                    );
define( 'WMD_URL',       plugin_dir_url( __FILE__ ) );
define( 'WMD_PATH',      dirname( __FILE__ ) . '/'  );
define( 'WMD_ASSETS',    WMD_URL . 'assets/'        );
define( 'WMD_TEMPLATES', WMD_PATH . 'templates/'    );
define( 'WMD_INCLUDES',  WMD_PATH . 'includes/'     );
define( 'WMD_LIB',       WMD_PATH . 'includes/lib/' );
define( 'WMD_ACTIVE',    true                       );

// Includes
include_once WMD_INCLUDES . 'class-wmd-utils.php';
include_once WMD_INCLUDES . 'shortcodes.php';
include_once WMD_INCLUDES . 'class-wmd-member-directory.php';
include_once WMD_INCLUDES . 'helper-functions.php';

include_once WMD_PATH . 'post-types/class-wmd-post-types.php';
include_once WMD_PATH . 'taxonomies/class-wmd-taxonomies.php';

register_activation_hook(   __FILE__, array( 'WMD_Utils', 'activate'   ) );
register_deactivation_hook( __FILE__, array( 'WMD_Utils', 'deactivate' ) );

// Wireup actions
add_action( 'init',                  array( 'WMD_Utils',      'init'                              ) );
add_action( 'init',                  array( 'WMD_Post_Types', 'member_directory_init'             ) );
add_filter( 'post_updated_messages', array( 'WMD_Post_Types', 'member_directory_updated_messages' ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'price_low_init'                    ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'price_high_init'                   ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'state_init'                        ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'city_init'                         ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'industry_init'                     ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'technology_init'                   ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'type_init'                         ) );
add_action( 'init',                  array( 'WMD_Taxonomies', 'level_init'                        ) );
add_action( 'after_setup_theme',     array( 'WMD_Utils',      'after_setup',                      ) );

/**
 * Load our BuddyPress Membership component if BP is installed
 */
function wmd_bp_membership_component_init() {
	// Because our loader file uses BP_Component, it requires BP 1.5 or greater.
	if ( version_compare( BP_VERSION, '1.3', '>' )  ) {
		require( WMD_INCLUDES . '/membership/bp-membership-loader.php' );
		require( WMD_INCLUDES . '/membership/bp-membership-shortcodes.php' );
		require( WMD_INCLUDES . '/listing-manager/bp-listing-manager-loader.php' );
	}
}
add_action( 'bp_include', 'wmd_bp_membership_component_init' );

// Wireup filters
add_filter( 'pmpro_account_preheader_no_user_redirect', array( 'WMD_Utils', 'pmpro_no_user_redirect' ) );

// Wireup shortcodes