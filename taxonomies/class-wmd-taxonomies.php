<?php

class WMD_Taxonomies {
	public static function price_range_init() {
		register_taxonomy( 'price-range', array( 'member-directory' ), array(
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
				'name'                       => __( 'Price ranges', 'wimp' ),
				'singular_name'              => _x( 'Price range', 'taxonomy general name', 'wimp' ),
				'search_items'               => __( 'Search price ranges', 'wimp' ),
				'popular_items'              => __( 'Popular price ranges', 'wimp' ),
				'all_items'                  => __( 'All price ranges', 'wimp' ),
				'parent_item'                => __( 'Parent price range', 'wimp' ),
				'parent_item_colon'          => __( 'Parent price range:', 'wimp' ),
				'edit_item'                  => __( 'Edit price range', 'wimp' ),
				'update_item'                => __( 'Update price range', 'wimp' ),
				'add_new_item'               => __( 'New price range', 'wimp' ),
				'new_item_name'              => __( 'New price range', 'wimp' ),
				'separate_items_with_commas' => __( 'Price ranges separated by comma', 'wimp' ),
				'add_or_remove_items'        => __( 'Add or remove price ranges', 'wimp' ),
				'choose_from_most_used'      => __( 'Choose from the most used price ranges', 'wimp' ),
				'menu_name'                  => __( 'Price ranges', 'wimp' ),
			),
		) );
	}
}
