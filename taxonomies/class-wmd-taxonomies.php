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

/**
 * Adds a status dropdown to the term creation window in the admin area
 */
function wmd_term_meta() {
	?>
	<div class="form-field">
		<label for="status"><?php esc_html_e( 'Status', 'wmd' ); ?></label>
		<select name="term_status" id="status">
			<option value="true">Allowed</option>
			<option value="false">Disallowed</option>
			<option value="review">In Review</option>
		</select>
		<p class="description"><?php esc_html_e( 'The status of the term. It is either `In Review`, `Allowed` or `Disallowed`','wmd' ); ?></p>
	</div>
	<?php
}
add_action( 'wmd-industry_add_form_fields', 'wmd_term_meta', 10, 2 );
add_action( 'wmd-technology_add_form_fields', 'wmd_term_meta', 10, 2 );
add_action( 'wmd-type_add_form_fields', 'wmd_term_meta', 10, 2 );

/**
 * Adds a field to the edit screen of an already created term
 *
 * @param $term
 */
function wmd_edit_meta_field( $term ) {
	$term_meta = get_option( 'taxonomy_status_' . absint( $term->term_id ) ); ?>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="status"><?php esc_html_e( 'status', 'wmd' ); ?></label>
		</th>
		<td>
			<select name="term_status" id="status">
				<option value="true"<?php selected( $term_meta, 'true' ); ?>>Allowed</option>
				<option value="false"<?php selected( $term_meta, 'false' ); ?>>Disallowed</option>
				<option value="review"<?php selected( $term_meta, 'review' ); ?>>In Review</option>
			</select>
			<p class="description"><?php esc_html_e( 'The status of the term. It is either `In Review`, `Allowed` or `Disallowed`','wmd' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'wmd-industry_edit_form_fields', 'wmd_edit_meta_field', 10, 2 );
add_action( 'wmd-technology_edit_form_fields', 'wmd_edit_meta_field', 10, 2 );
add_action( 'wmd-type_edit_form_fields', 'wmd_edit_meta_field', 10, 2 );

/**
 * Saves term meta
 *
 * @param $term_id
 */
function save_taxonomy_custom_meta( $term_id ) {
	if ( ! isset( $_POST['term_status'] ) ) {
		return;
	}

	$allowed = array(
		'true',
		'false',
		'review',
	);

	// Make sure we are passing a value we actually...... allow.
	// If it isn't, we'll default to 'review'
	if ( ! in_array( $_POST['term_status'], $allowed ) ) {
		$value = 'review';
	} else {
		$value = $_POST['term_status'];
	}

	update_option( 'taxonomy_status_' . absint( $term_id ), sanitize_text_field( $value ) );
}
add_action( 'edited_wmd-industry', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_wmd-industry', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'edited_wmd-technology', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_wmd-technology', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'edited_wmd-type', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_wmd-type', 'save_taxonomy_custom_meta', 10, 2 );

/**
 * @param $theme_columns
 *
 * @return array
 */
function wmd_theme_columns( $theme_columns ) {
	$new_columns = array(
		'cb'          => '<input type="checkbox" />',
		'name'        => esc_html__( 'Name', 'wmd' ),
		'status'      => esc_html__( 'Status', 'wmd' ),
		'slug'        => esc_html__( 'Slug', 'wmd' ),
		'posts'       => esc_html__( 'Posts', 'wmd' )
	);

	return $new_columns;
}
add_filter( 'manage_edit-wmd-industry_columns', 'wmd_theme_columns' );
add_filter( 'manage_edit-wmd-technology_columns', 'wmd_theme_columns' );
add_filter( 'manage_edit-wmd-type_columns', 'wmd_theme_columns' );

/**
 * Add custom column to taxonomy list table
 *
 * @param $content
 * @param $column_name
 * @param $term_id
 *
 * @return string
 */
function wmd_column_content( $content, $column_name, $term_id ) {
	switch ( $column_name ) {
		case 'status':
			$status = get_option( 'taxonomy_status_' . absint( $term_id ) );

			if ( false === $status ) {
				$status = 'Not Set';
			} elseif ( 'true' === $status ) {
				$status = 'Allowed';
			} elseif ( 'false' === $status ) {
				$status = 'Disallowed';
			} else {
				$status = 'In Review';
			}

			$content .= sanitize_text_field( $status );
			break;

		default:
			break;
	}

	return $content;
}
add_filter( 'manage_wmd-industry_custom_column', 'wmd_column_content', 10, 3 );
add_filter( 'manage_wmd-technology_custom_column', 'wmd_column_content', 10, 3 );
add_filter( 'manage_wmd-type_custom_column', 'wmd_column_content', 10, 3 );