<?php

// Deny any direct accessing of this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WMD_Taxonomies
 *
 * Contains all the taxonomies to be registered
 */
class WMD_Taxonomies {
	/**
	 * Constants for the taxonomy names in the DB
	 */
	const PRICE_LOW  = 'wmd-price-low';
	const PRICE_HIGH = 'wmd-price-high';
	const LOCATION   = 'wmd-location';
	const INDUSTRY   = 'wmd-industry';
	const TECHNOLOGY = 'wmd-technology';
	const TYPE       = 'wmd-type';
	const LEVEL      = 'wmd-level';
	
	/**
	 * Registers the price-low taxonomy
	 *
	 * This taxonomy will hold the low price for a single member directory
	 */
	public static function price_low_init() {
		register_taxonomy( self::PRICE_LOW, array( 'member-directory' ), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Low Prices', 'wimp' ),
				'singular_name'              => _x( 'Low Price ', 'taxonomy general name', 'wimp' ),
				'search_items'               => __( 'Search low prices', 'wimp' ),
				'popular_items'              => __( 'Popular low prices', 'wimp' ),
				'all_items'                  => __( 'All low prices', 'wimp' ),
				'parent_item'                => __( 'Parent low price', 'wimp' ),
				'parent_item_colon'          => __( 'Parent low price:', 'wimp' ),
				'edit_item'                  => __( 'Edit low price', 'wimp' ),
				'update_item'                => __( 'Update low price', 'wimp' ),
				'add_new_item'               => __( 'New low price', 'wimp' ),
				'new_item_name'              => __( 'New low price', 'wimp' ),
				'separate_items_with_commas' => __( 'Low prices separated by comma', 'wimp' ),
				'add_or_remove_items'        => __( 'Add or remove low prices', 'wimp' ),
				'choose_from_most_used'      => __( 'Choose from the most used low prices', 'wimp' ),
				'menu_name'                  => __( 'Low Prices', 'wimp' ),
			),
		) );
	}

	/**
	 * Registers the price-high taxonomy
	 *
	 * This taxonomy will hold the high price for a single member directory
	 */
	public static function price_high_init() {
		register_taxonomy( self::PRICE_HIGH, array( 'member-directory' ), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'High Prices', 'wimp' ),
				'singular_name'              => _x( 'High Price ', 'taxonomy general name', 'wimp' ),
				'search_items'               => __( 'Search high prices', 'wimp' ),
				'popular_items'              => __( 'Popular high prices', 'wimp' ),
				'all_items'                  => __( 'All high prices', 'wimp' ),
				'parent_item'                => __( 'Parent high price', 'wimp' ),
				'parent_item_colon'          => __( 'Parent high price:', 'wimp' ),
				'edit_item'                  => __( 'Edit high price', 'wimp' ),
				'update_item'                => __( 'Update hige price', 'wimp' ),
				'add_new_item'               => __( 'New high price', 'wimp' ),
				'new_item_name'              => __( 'New high price', 'wimp' ),
				'separate_items_with_commas' => __( 'High prices separated by comma', 'wimp' ),
				'add_or_remove_items'        => __( 'Add or remove high prices', 'wimp' ),
				'choose_from_most_used'      => __( 'Choose from the most used high prices', 'wimp' ),
				'menu_name'                  => __( 'High Prices', 'wimp' ),
			),
		) );
	}

	/**
	 * Registers the location taxonomy
	 *
	 * Contains the specific location (State and City) and will be stored hierarchically.
	 */
	public static function location_init() {
		register_taxonomy( self::LOCATION, array( 'member-directory' ), array(
			'hierarchical'      => true,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Locations', 'wimp-member-directory' ),
				'singular_name'              => _x( 'Locations', 'taxonomy general name', 'wimp-member-directory' ),
				'search_items'               => __( 'Search Locations', 'wimp-member-directory' ),
				'popular_items'              => __( 'Popular Locations', 'wimp-member-directory' ),
				'all_items'                  => __( 'All Locations', 'wimp-member-directory' ),
				'parent_item'                => __( 'Parent Locations', 'wimp-member-directory' ),
				'parent_item_colon'          => __( 'Parent Locations:', 'wimp-member-directory' ),
				'edit_item'                  => __( 'Edit Locations', 'wimp-member-directory' ),
				'update_item'                => __( 'Update Locations', 'wimp-member-directory' ),
				'add_new_item'               => __( 'New Locations', 'wimp-member-directory' ),
				'new_item_name'              => __( 'New Locations', 'wimp-member-directory' ),
				'separate_items_with_commas' => __( 'Locations separated by comma', 'wimp-member-directory' ),
				'add_or_remove_items'        => __( 'Add or remove Locations', 'wimp-member-directory' ),
				'choose_from_most_used'      => __( 'Choose from the most used Locations', 'wimp-member-directory' ),
				'menu_name'                  => __( 'Locations', 'wimp-member-directory' ),
			),
		) );
	}

	/**
	 * Registers the industry taxonomy
	 *
	 * Contains the type of industry the member directory entree is in
	 */
	public static function industry_init() {
		register_taxonomy( self::INDUSTRY, array( 'member-directory' ), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Industries', 'wimp-member-directory' ),
				'singular_name'              => _x( 'Industry', 'taxonomy general name', 'wimp-member-directory' ),
				'search_items'               => __( 'Search Industries', 'wimp-member-directory' ),
				'popular_items'              => __( 'Popular Industries', 'wimp-member-directory' ),
				'all_items'                  => __( 'All Industries', 'wimp-member-directory' ),
				'parent_item'                => __( 'Parent Industry', 'wimp-member-directory' ),
				'parent_item_colon'          => __( 'Parent Industry:', 'wimp-member-directory' ),
				'edit_item'                  => __( 'Edit Industry', 'wimp-member-directory' ),
				'update_item'                => __( 'Update Industry', 'wimp-member-directory' ),
				'add_new_item'               => __( 'New Industry', 'wimp-member-directory' ),
				'new_item_name'              => __( 'New Industry', 'wimp-member-directory' ),
				'separate_items_with_commas' => __( 'Industries separated by comma', 'wimp-member-directory' ),
				'add_or_remove_items'        => __( 'Add or remove Industries', 'wimp-member-directory' ),
				'choose_from_most_used'      => __( 'Choose from the most used Industries', 'wimp-member-directory' ),
				'menu_name'                  => __( 'Industries', 'wimp-member-directory' ),
			),
		) );
	}

	/**
	 * Registers the technology taxonomy
	 *
	 * Allows the member directory to create and assign different technologies
	 */
	public static function technology_init() {
		register_taxonomy( self::TECHNOLOGY, array( 'member-directory' ), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Technologies', 'wimp-member-directory' ),
				'singular_name'              => _x( 'Technology', 'taxonomy general name', 'wimp-member-directory' ),
				'search_items'               => __( 'Search Technologies', 'wimp-member-directory' ),
				'popular_items'              => __( 'Popular Technologies', 'wimp-member-directory' ),
				'all_items'                  => __( 'All Technologies', 'wimp-member-directory' ),
				'parent_item'                => __( 'Parent Technology', 'wimp-member-directory' ),
				'parent_item_colon'          => __( 'Parent Technology:', 'wimp-member-directory' ),
				'edit_item'                  => __( 'Edit Technology', 'wimp-member-directory' ),
				'update_item'                => __( 'Update Technology', 'wimp-member-directory' ),
				'add_new_item'               => __( 'New Technology', 'wimp-member-directory' ),
				'new_item_name'              => __( 'New Technology', 'wimp-member-directory' ),
				'separate_items_with_commas' => __( 'Technologies separated by comma', 'wimp-member-directory' ),
				'add_or_remove_items'        => __( 'Add or remove Technologies', 'wimp-member-directory' ),
				'choose_from_most_used'      => __( 'Choose from the most used Technologies', 'wimp-member-directory' ),
				'menu_name'                  => __( 'Technologies', 'wimp-member-directory' ),
			),
		) );
	}

	/**
	 * Registers the type taxonomy
	 *
	 * Allows member directories to assign what type of work they normally perform
	 */
	public static function type_init() {
		register_taxonomy( self::TYPE, array( 'member-directory' ), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Types', 'wimp-member-directory' ),
				'singular_name'              => _x( 'Type', 'taxonomy general name', 'wimp-member-directory' ),
				'search_items'               => __( 'Search Types', 'wimp-member-directory' ),
				'popular_items'              => __( 'Popular Types', 'wimp-member-directory' ),
				'all_items'                  => __( 'All Types', 'wimp-member-directory' ),
				'parent_item'                => __( 'Parent Type', 'wimp-member-directory' ),
				'parent_item_colon'          => __( 'Parent Type:', 'wimp-member-directory' ),
				'edit_item'                  => __( 'Edit Type', 'wimp-member-directory' ),
				'update_item'                => __( 'Update Type', 'wimp-member-directory' ),
				'add_new_item'               => __( 'New Type', 'wimp-member-directory' ),
				'new_item_name'              => __( 'New Type', 'wimp-member-directory' ),
				'separate_items_with_commas' => __( 'Types separated by comma', 'wimp-member-directory' ),
				'add_or_remove_items'        => __( 'Add or remove Types', 'wimp-member-directory' ),
				'choose_from_most_used'      => __( 'Choose from the most used Types', 'wimp-member-directory' ),
				'menu_name'                  => __( 'Types', 'wimp-member-directory' ),
			),
		) );
	}

	/**
	 * Registers the member level taxonomy
	 *
	 * Allows us to say what level of membership this member directory is paying for
	 */
	public static function level_init() {
		register_taxonomy( self::LEVEL, array( 'member-directory' ), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Member Levels', 'wimp-member-directory' ),
				'singular_name'              => _x( 'Member Level', 'taxonomy general name', 'wimp-member-directory' ),
				'search_items'               => __( 'Search Member Levels', 'wimp-member-directory' ),
				'popular_items'              => __( 'Popular Member Levels', 'wimp-member-directory' ),
				'all_items'                  => __( 'All Member Levels', 'wimp-member-directory' ),
				'parent_item'                => __( 'Parent Member Level', 'wimp-member-directory' ),
				'parent_item_colon'          => __( 'Parent Member Level:', 'wimp-member-directory' ),
				'edit_item'                  => __( 'Edit Member Level', 'wimp-member-directory' ),
				'update_item'                => __( 'Update Member Level', 'wimp-member-directory' ),
				'add_new_item'               => __( 'New Member Level', 'wimp-member-directory' ),
				'new_item_name'              => __( 'New Member Level', 'wimp-member-directory' ),
				'separate_items_with_commas' => __( 'Member Levels separated by comma', 'wimp-member-directory' ),
				'add_or_remove_items'        => __( 'Add or remove Member Levels', 'wimp-member-directory' ),
				'choose_from_most_used'      => __( 'Choose from the most used Member Levels', 'wimp-member-directory' ),
				'menu_name'                  => __( 'Member Levels', 'wimp-member-directory' ),
			),
		) );

	}
}
