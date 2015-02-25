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
	const STATE      = 'wmd-state';
	const CITY       = 'wmd-city';
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
				'name'                       => __( 'Low Prices', 'wmd' ),
				'singular_name'              => _x( 'Low Price ', 'taxonomy general name', 'wmd' ),
				'search_items'               => __( 'Search low prices', 'wmd' ),
				'popular_items'              => __( 'Popular low prices', 'wmd' ),
				'all_items'                  => __( 'All low prices', 'wmd' ),
				'parent_item'                => __( 'Parent low price', 'wmd' ),
				'parent_item_colon'          => __( 'Parent low price:', 'wmd' ),
				'edit_item'                  => __( 'Edit low price', 'wmd' ),
				'update_item'                => __( 'Update low price', 'wmd' ),
				'add_new_item'               => __( 'New low price', 'wmd' ),
				'new_item_name'              => __( 'New low price', 'wmd' ),
				'separate_items_with_commas' => __( 'Low prices separated by comma', 'wmd' ),
				'add_or_remove_items'        => __( 'Add or remove low prices', 'wmd' ),
				'choose_from_most_used'      => __( 'Choose from the most used low prices', 'wmd' ),
				'menu_name'                  => __( 'Low Prices', 'wmd' ),
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
				'name'                       => __( 'High Prices', 'wmd' ),
				'singular_name'              => _x( 'High Price ', 'taxonomy general name', 'wmd' ),
				'search_items'               => __( 'Search high prices', 'wmd' ),
				'popular_items'              => __( 'Popular high prices', 'wmd' ),
				'all_items'                  => __( 'All high prices', 'wmd' ),
				'parent_item'                => __( 'Parent high price', 'wmd' ),
				'parent_item_colon'          => __( 'Parent high price:', 'wmd' ),
				'edit_item'                  => __( 'Edit high price', 'wmd' ),
				'update_item'                => __( 'Update hige price', 'wmd' ),
				'add_new_item'               => __( 'New high price', 'wmd' ),
				'new_item_name'              => __( 'New high price', 'wmd' ),
				'separate_items_with_commas' => __( 'High prices separated by comma', 'wmd' ),
				'add_or_remove_items'        => __( 'Add or remove high prices', 'wmd' ),
				'choose_from_most_used'      => __( 'Choose from the most used high prices', 'wmd' ),
				'menu_name'                  => __( 'High Prices', 'wmd' ),
			),
		) );
	}

	/**
	 * Registers the state taxonomy
	 *
	 * Contains the state the listing will exist in
	 */
	public static function state_init() {
		register_taxonomy( self::STATE, array( 'member-directory' ), array(
			'hierarchical'      => true,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => 'state',
			),
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'State', 'wmd' ),
				'singular_name'              => _x( 'State', 'taxonomy general name', 'wmd' ),
				'search_items'               => __( 'Search States', 'wmd' ),
				'popular_items'              => __( 'Popular States', 'wmd' ),
				'all_items'                  => __( 'All States', 'wmd' ),
				'parent_item'                => __( 'Parent State', 'wmd' ),
				'parent_item_colon'          => __( 'Parent State:', 'wmd' ),
				'edit_item'                  => __( 'Edit State', 'wmd' ),
				'update_item'                => __( 'Update State', 'wmd' ),
				'add_new_item'               => __( 'New State', 'wmd' ),
				'new_item_name'              => __( 'New State', 'wmd' ),
				'separate_items_with_commas' => __( 'State separated by comma', 'wmd' ),
				'add_or_remove_items'        => __( 'Add or remove States', 'wmd' ),
				'choose_from_most_used'      => __( 'Choose from the most used States', 'wmd' ),
				'menu_name'                  => __( 'State', 'wmd' ),
			),
		) );
	}

	/**
	 * Registers the city taxonomy
	 *
	 * Contains the city the listing will exist in
	 */
	public static function city_init() {
		register_taxonomy( self::CITY, array( 'member-directory' ), array(
			'hierarchical'      => true,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => 'state',
			),
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Cities', 'wmd' ),
				'singular_name'              => _x( 'City', 'taxonomy general name', 'wmd' ),
				'search_items'               => __( 'Search Cities', 'wmd' ),
				'popular_items'              => __( 'Popular Cities', 'wmd' ),
				'all_items'                  => __( 'All Cities', 'wmd' ),
				'parent_item'                => __( 'Parent Cities', 'wmd' ),
				'parent_item_colon'          => __( 'Parent Cities:', 'wmd' ),
				'edit_item'                  => __( 'Edit City', 'wmd' ),
				'update_item'                => __( 'Update City', 'wmd' ),
				'add_new_item'               => __( 'New City', 'wmd' ),
				'new_item_name'              => __( 'New City', 'wmd' ),
				'separate_items_with_commas' => __( 'Cities separated by comma', 'wmd' ),
				'add_or_remove_items'        => __( 'Add or remove Cities', 'wmd' ),
				'choose_from_most_used'      => __( 'Choose from the most used Cities', 'wmd' ),
				'menu_name'                  => __( 'City', 'wmd' ),
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
			'rewrite'           => array(
				'slug' => 'industry',
			),
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Industries', 'wmd' ),
				'singular_name'              => _x( 'Industry', 'taxonomy general name', 'wmd' ),
				'search_items'               => __( 'Search Industries', 'wmd' ),
				'popular_items'              => __( 'Popular Industries', 'wmd' ),
				'all_items'                  => __( 'All Industries', 'wmd' ),
				'parent_item'                => __( 'Parent Industry', 'wmd' ),
				'parent_item_colon'          => __( 'Parent Industry:', 'wmd' ),
				'edit_item'                  => __( 'Edit Industry', 'wmd' ),
				'update_item'                => __( 'Update Industry', 'wmd' ),
				'add_new_item'               => __( 'New Industry', 'wmd' ),
				'new_item_name'              => __( 'New Industry', 'wmd' ),
				'separate_items_with_commas' => __( 'Industries separated by comma', 'wmd' ),
				'add_or_remove_items'        => __( 'Add or remove Industries', 'wmd' ),
				'choose_from_most_used'      => __( 'Choose from the most used Industries', 'wmd' ),
				'menu_name'                  => __( 'Industries', 'wmd' ),
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
			'rewrite'           => array(
				'slug' => 'technology',
			),
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Technologies', 'wmd' ),
				'singular_name'              => _x( 'Technology', 'taxonomy general name', 'wmd' ),
				'search_items'               => __( 'Search Technologies', 'wmd' ),
				'popular_items'              => __( 'Popular Technologies', 'wmd' ),
				'all_items'                  => __( 'All Technologies', 'wmd' ),
				'parent_item'                => __( 'Parent Technology', 'wmd' ),
				'parent_item_colon'          => __( 'Parent Technology:', 'wmd' ),
				'edit_item'                  => __( 'Edit Technology', 'wmd' ),
				'update_item'                => __( 'Update Technology', 'wmd' ),
				'add_new_item'               => __( 'New Technology', 'wmd' ),
				'new_item_name'              => __( 'New Technology', 'wmd' ),
				'separate_items_with_commas' => __( 'Technologies separated by comma', 'wmd' ),
				'add_or_remove_items'        => __( 'Add or remove Technologies', 'wmd' ),
				'choose_from_most_used'      => __( 'Choose from the most used Technologies', 'wmd' ),
				'menu_name'                  => __( 'Technologies', 'wmd' ),
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
			'rewrite'           => array(
				'slug' => 'type',
			),
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts'
			),
			'labels'            => array(
				'name'                       => __( 'Types', 'wmd' ),
				'singular_name'              => _x( 'Type', 'taxonomy general name', 'wmd' ),
				'search_items'               => __( 'Search Types', 'wmd' ),
				'popular_items'              => __( 'Popular Types', 'wmd' ),
				'all_items'                  => __( 'All Types', 'wmd' ),
				'parent_item'                => __( 'Parent Type', 'wmd' ),
				'parent_item_colon'          => __( 'Parent Type:', 'wmd' ),
				'edit_item'                  => __( 'Edit Type', 'wmd' ),
				'update_item'                => __( 'Update Type', 'wmd' ),
				'add_new_item'               => __( 'New Type', 'wmd' ),
				'new_item_name'              => __( 'New Type', 'wmd' ),
				'separate_items_with_commas' => __( 'Types separated by comma', 'wmd' ),
				'add_or_remove_items'        => __( 'Add or remove Types', 'wmd' ),
				'choose_from_most_used'      => __( 'Choose from the most used Types', 'wmd' ),
				'menu_name'                  => __( 'Types', 'wmd' ),
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
				'name'                       => __( 'Member Levels', 'wmd' ),
				'singular_name'              => _x( 'Member Level', 'taxonomy general name', 'wmd' ),
				'search_items'               => __( 'Search Member Levels', 'wmd' ),
				'popular_items'              => __( 'Popular Member Levels', 'wmd' ),
				'all_items'                  => __( 'All Member Levels', 'wmd' ),
				'parent_item'                => __( 'Parent Member Level', 'wmd' ),
				'parent_item_colon'          => __( 'Parent Member Level:', 'wmd' ),
				'edit_item'                  => __( 'Edit Member Level', 'wmd' ),
				'update_item'                => __( 'Update Member Level', 'wmd' ),
				'add_new_item'               => __( 'New Member Level', 'wmd' ),
				'new_item_name'              => __( 'New Member Level', 'wmd' ),
				'separate_items_with_commas' => __( 'Member Levels separated by comma', 'wmd' ),
				'add_or_remove_items'        => __( 'Add or remove Member Levels', 'wmd' ),
				'choose_from_most_used'      => __( 'Choose from the most used Member Levels', 'wmd' ),
				'menu_name'                  => __( 'Member Levels', 'wmd' ),
			),
		) );

	}
}
