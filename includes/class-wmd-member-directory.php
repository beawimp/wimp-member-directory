<?php

// Deny any direct accessing of this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WMD_Member_Directory
 */
class WMD_Member_Directory {

	/**
	 * Add the name of the expected directory name that will store the template files outside of the plugin
	 *
	 * @var string
	 */
	public static $template_dir = 'member-directory';

	/**
	 * Run our actions
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_resources' ) );
		add_action( 'template_include',   array( __CLASS__, 'template_redirect' ) );

		add_filter( 'cmb_meta_boxes',     array( __CLASS__, 'add_meta_boxes' ) );
	}

	/**
	 * Load any JavaScript or CSS we need to run the plugin
	 */
	public static function enqueue_resources() {
		$min = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_style( 'wmd-styles',
			WMD_ASSETS . "css/wimp-member-directory{$min}.css",
			null,
			WMD_VERSION
		);

		wp_enqueue_script( 'wmd-flexslider-js',
			WMD_ASSETS . 'js/vendor/jquery.flexslider-min.js',
			array( 'jquery' ),
			'2.2.2',
			true
		);
		wp_enqueue_script( 'wmd-js',
			WMD_ASSETS . "js/wimp-member-directory{$min}.js",
			array( 'wmd-flexslider-js' ),
			WMD_VERSION,
			true
		);
	}

	/**
	 * Allows us to locate for the right template file to serve for the Member Directory post type
	 */
	public static function template_redirect() {
		if ( is_post_type_archive( 'member-directory' ) && ! is_single() ) {
			self::locate_template( 'archive-member-directory.php', true );

		} elseif ( is_post_type_archive( 'member-directory' ) && is_single() ) {
			self::locate_template( 'single-member-directory.php', true );
		}
	}

	/**
	 * Locates the requested template
	 *
	 * Searches through child themes, parent themes and the plugin for the requested template in that order.
	 * If one exists, that will be used, allowing maximum customizations without needing to mess with the plugin.
	 *
	 * @param string $template_names The name of the template
	 * @param bool   $load           Allows us to just return the template path or actually load the template
	 * @param bool   $require_once   Allows us to require one or require
	 *
	 * @return bool|string
	 */
	public static function locate_template( $template_names, $load = false, $require_once = true ) {
		$path = false;

		// Loop through each template name and find them.
		foreach ( (array) $template_names as $template_name ) {

			if ( empty( $template_name ) ) {
				continue;
			}

			// Remove any trailing slashes if they exist
			$template_path  = '/' . trailingslashit( self::$template_dir ) . sanitize_file_name( untrailingslashit( $template_name ) );
			$store  = get_template_directory() . $template_path;
			$store2 = get_stylesheet_directory() . $template_path;
			$store3 = WMD_TEMPLATES . sanitize_file_name( untrailingslashit( $template_name ) );

			// Check if child theme has template
			if ( file_exists( get_stylesheet_directory() . $template_path ) ) {
				$path = get_stylesheet_directory() . $template_path;
				break;

			// Check if parent theme has template
			} elseif ( file_exists( get_template_directory() . $template_path ) ) {
				$path = get_template_directory() . $template_path;
				break;

			// Check if plugin has it
			} elseif ( file_exists( WMD_TEMPLATES . sanitize_file_name( untrailingslashit( $template_name ) ) ) ) {
				$path = WMD_TEMPLATES . sanitize_file_name( untrailingslashit( $template_name ) );
				break;

			}
		}

		// See if we have anything to load
		if ( ! empty( $path ) && true == $load ) {
			load_template( $path, $require_once );
		}

		return $path;
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
					'name'  => 'Website URL',
					'id'    => $prefix . 'url',
					'type'  => 'text_url',
				)
			),
		);

		return $meta_boxes;
	}
}
$wmd_member_directory = new WMD_Member_Directory();