<?php

// Deny any direct accessing of this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BP_Membership_Component
 *
 * Defines the Membership BuddyPress Component by extending the BP_Component object
 *
 * BP_Component is the base class that all BuddyPress components use to setup their basic
 * structure, including global data, navigation elements, and admin bar information.
 */
class BP_Membership_Component extends BP_Component {

	/**
	 * Constructor method
	 *
	 * It is recommended we call the parent::start() method here. This will trigger the
	 * BP_Component to begin its setup routine.
	 *
	 * @global obj $bp
	 */
	function __construct() {
		global $bp;

		parent::start(
			'wimp_plus',
			'WIMP+',
			WMD_PATH
		);

		// BuddyPress-dependent plugins are loaded too late to depend on BP_Component's
		// hooks, so we must call the method directly.
		$this->includes();

		// Places the component into the active components array so functions like `bp_is_active( 'wmd_membership' )`
		// returns true when appropriate. We have to do this manually, because non-core
		// components are not saved as active components in the database.
		$bp->active_components[ $this->id ] = '1';
	}

	/**
	 * Include the component's files
	 *
	 * BP_Components has a method called includes(), which will automatically load the plugin's
	 * files. BP_Component::includes() loops through the $includes array for each file in the array.
	 *
	 * @param array $includes
	 */
	function includes( $includes = array() ) {
		$path     = 'includes/membership/';
		$includes = array(
			$path . 'bp-actions.php',
			$path . 'bp-screens.php',
			$path . 'bp-filters.php',
			$path . 'bp-classes.php',
			$path . 'bp-activity.php',
			$path . 'bp-template.php',
			$path . 'bp-functions.php',
			$path . 'bp-notifications.php',
			$path . 'bp-widgets.php',
			$path . 'bp-cssjs.php',
			$path . 'bp-ajax.php',
		);

		parent::includes( $includes );
	}

	/**
	 * Setup the components globals
	 *
	 * Using the parent::setup_globals() method to set up the key global data for the component
	 *
	 * @global obj   $bp
	 *
	 * @param  array $args
	 */
	function setup_globals( $args = array() ) {
		global $bp;

		// Defining the slug in this way makes it possible for admins to override it
		if ( ! defined( 'BP_WIMP_MEMBERSHIP_SLUG' ) ) {
			define( 'BP_WIMP_MEMBERSHIP_SLUG', $this->id );
		}

		// Set up the $globals array to be passed along to parent::setup_globals()
		$globals = array(
			'slug'          => BP_WIMP_MEMBERSHIP_SLUG,
			'root_slug'     => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : BP_WIMP_MEMBERSHIP_SLUG,
			'has_directory' => false,
		);

		// Let BP_Component::setup_globals do the work
		parent::setup_globals( $globals );
	}

	/**
	 * Set up the component's navigation
	 *
	 * The navigation elements create here are responsible for the main site navigation (eg
	 * Profile > Activity > Mentions), as well as the navigation in the BuddyBar. WP Admin Bar
	 * navigation is broken out into a separate method; see BP_Example_Component::setup_admin_bar().
	 *
	 * @param array $main_nav
	 * @param array $sub_nav
	 */
	function setup_nav( $main_nav = array(), $sub_nav = array() ) {
		// Add the Membership parent item
		$main_nav = array(
			'name'                    => 'WIMP+',
			'slug'                    => bp_get_wimp_membership_slug(),
			'position'                => 80,
			'screen_function'         => 'bp_wimp_membership_account_screen',
			'default_subnav_slug'     => 'account',
			'show_for_displayed_user' => false,
		);

		$sub_nav[] = array(
			'name'            => 'Account',
			'slug'            => 'account',
			'parent_url'      => trailingslashit( bp_loggedin_user_domain() . bp_get_wimp_membership_slug() ),
			'parent_slug'     => bp_get_wimp_membership_slug(),
			'screen_function' => 'bp_wimp_membership_account_screen',
			'position'        => 10,
		);

		if ( wmd_is_wimp_plus_member() ) {
			$sub_nav[] = array(
				'name'            => 'Billing',
				'slug'            => 'billing',
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . bp_get_wimp_membership_slug() ),
				'parent_slug'     => bp_get_wimp_membership_slug(),
				'screen_function' => 'bp_wimp_membership_billing_screen',
				'position'        => 20,
			);

			$sub_nav[] = array(
				'name'            => 'Invoices',
				'slug'            => 'invoice',
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . bp_get_wimp_membership_slug() ),
				'parent_slug'     => bp_get_wimp_membership_slug(),
				'screen_function' => 'bp_wimp_membership_invoice_screen',
				'position'        => 30,
			);
		}

		parent::setup_nav( $main_nav, $sub_nav );
	}
}

/**
 * Loads the component into the $bp global
 *
 * By hooking to bp_loaded, we ensure that BP_Example_Component is loaded after Buddypress's core
 * components. This is a good thing because it gives us access to those components' functions and
 * data, should our component interactive with them.
 *
 * @global obj $bp
 */
function bp_wimp_membership_load_core_component() {
	global $bp;

	$bp->wimp_membership = new BP_Membership_Component;
}

add_action( 'bp_loaded', 'bp_wimp_membership_load_core_component' );