<?php

/**
 * Class WMD_Post_Types
 *
 * Loads all the code needed for registering post types
 */
class WMD_Post_Types {
	public static function wmd_member_directory_init() {
		register_post_type( 'member-directory', array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'supports'          => array( 'title', 'editor' ),
			'has_archive'       => true,
			'query_var'         => true,
			'rewrite'           => true,
			'labels'            => array(
				'name'               => __( 'Member Directories', 'wimp' ),
				'singular_name'      => __( 'Member Directory', 'wimp' ),
				'all_items'          => __( 'Member Directories', 'wimp' ),
				'new_item'           => __( 'New Member Directory', 'wimp' ),
				'add_new'            => __( 'Add New', 'wimp' ),
				'add_new_item'       => __( 'Add New Member Directory', 'wimp' ),
				'edit_item'          => __( 'Edit Member Directory', 'wimp' ),
				'view_item'          => __( 'View Member Directory', 'wimp' ),
				'search_items'       => __( 'Search Member Directories', 'wimp' ),
				'not_found'          => __( 'No Member Directories found', 'wimp' ),
				'not_found_in_trash' => __( 'No Member Directories found in trash', 'wimp' ),
				'parent_item_colon'  => __( 'Parent Member Directory', 'wimp' ),
				'menu_name'          => __( 'Member &nbsp; Directory', 'wimp' ),
			),
		) );
	}

	/**
	 * Load our updated messages for Member Directory post type
	 *
	 * @param array $messages An array of
	 *
	 * @return mixed
	 */
	public static function wmd_member_directory_updated_messages( $messages ) {
		global $post;

		$permalink = get_permalink( $post );

		$messages['member-directory'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Member Directory updated. <a target="_blank" href="%s">View Member Directory</a>', 'wimp' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'wimp' ),
			3  => __( 'Custom field deleted.', 'wimp' ),
			4  => __( 'Member Directory updated.', 'wimp' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Member Directory restored to revision from %s', 'wimp' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Member Directory published. <a href="%s">View Member Directory</a>', 'wimp' ), esc_url( $permalink ) ),
			7  => __( 'Member Directory saved.', 'wimp' ),
			8  => sprintf( __( 'Member Directory submitted. <a target="_blank" href="%s">Preview Member Directory</a>', 'wimp' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9  => sprintf( __( 'Member Directory scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Member Directory</a>', 'wimp' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
			10 => sprintf( __( 'Member Directory draft updated. <a target="_blank" href="%s">Preview Member Directory</a>', 'wimp' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		);

		return $messages;
	}
}