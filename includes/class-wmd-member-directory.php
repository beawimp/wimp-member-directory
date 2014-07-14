<?php

/**
 * Class WMD_Member_Directory
 *
 *
 */
class WMD_Member_Directory {

	/**
	 * Run our actions
	 */
	public function __construct() {
		add_filter( 'cmb_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
	}

	/**
	 * Registers our Meta Boxes
	 *
	 * @param array $meta_boxes The array of meta boxes that will be loaded through CMB
	 *
	 * @return array
	 */
	public static function add_meta_boxes( $meta_boxes ) {
		$prefix = '_wmd_'; // Prefix for all fields

		$meta_boxes['member-directory-data'] = array(
			'id'         => 'member-directory-data',
			'title'      => 'Details',
			'pages'      => array( 'member-directory' ),
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields'     => array(
				array(
					'name'  => 'Company Logo',
					'id'    => $prefix . 'company_logo',
					'type'  => 'file',
					'allow' => array( 'url', 'attachment' ),
				),
				array(
					'name'  => 'Portfolio',
					'id'    => $prefix . 'portfolio_items',
					'type'  => 'file_list',
				),
				array(
					'name'  => 'Price Range',
					'id'    => $prefix . 'price_rance',
					'type'  => 'text',
				)
			),
		);

		return $meta_boxes;
	}

}
$wmd_member_directory = new WMD_Member_Directory();