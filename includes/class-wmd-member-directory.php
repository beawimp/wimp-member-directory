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
	 * Allows us to say if there is an invalid filter set or not.
	 * This is built so we can serve back some notification to users in the front-end
	 */
	public static $invalid_filter = false;

	/**
	 * Run our actions
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_resources' ) );
		add_action( 'wp_ajax_wmd_save_listing_tax', array( __CLASS__, 'save_taxonomy_ajax' ) );
		add_action( 'wp_ajax_wmd_save_listing_post', array( __CLASS__, 'save_listing' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'post_query' ) );

		add_filter( 'template_include',  array( __CLASS__, 'member_directory_templates' ) );
		add_filter( 'cmb_meta_boxes',     array( __CLASS__, 'add_meta_boxes' ) );
	}

	/**
	 * Load any JavaScript or CSS we need to run the plugin
	 */
	public static function enqueue_resources() {
		$min = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		// We don't need Duru Sans enqueued because the main WIMP theme already does.
		// We'll leave this code here just in case..
		// wp_enqueue_style( 'wmd-fonts',
		// 	 'http://fonts.googleapis.com/css?family=Duru+Sans',
		//	 null,
		//	 WMD_VERSION
		// );
		wp_enqueue_style( 'wmd-styles',
			WMD_ASSETS . "css/wimp-member-directory{$min}.css",
			null,
			WMD_VERSION
		);

		if ( is_post_type_archive( 'member-directory' ) ) {
			wp_enqueue_script( 'wmd-flexslider-js',
				WMD_ASSETS . 'js/vendor/jquery.flexslider-min.js',
				array( 'jquery' ),
				'2.2.2',
				true
			);
		}

		// Load Select2 for just the listing manager.
		if ( 'listing_manager' === bp_current_component() ) {
			wp_enqueue_media();
			wp_enqueue_style( 'wmd-select2-style',
				'//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-beta.3/css/select2.min.css',
				null,
				'4.0.0b3'
			);
			wp_enqueue_script( 'wmd-select2-js',
				'//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-beta.3/js/select2.min.js',
				array( 'jquery' ),
				'4.0.0b3',
				true
			);
		}

		wp_enqueue_script( 'wmd-js',
			WMD_ASSETS . "js/wimp-member-directory{$min}.js",
			array(),
			WMD_VERSION,
			true
		);
	}

	/**
	 * Create a new term for the Member Listings via Ajax through their profile area.
	 *
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_success()
	 */
	public static function save_taxonomy_ajax() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'create-edit-listing' ) ) {
			wp_send_json_error( 'Cannot validate request.' );
		}

		if ( ! wmd_is_wimp_plus_member() ) {
			wp_send_json_error( 'You do not have sufficient permissions to complete this request.' );
		}

		if ( ! isset( $_POST['data']['term'] ) || empty( $_POST['data']['term'] ) ) {
			wp_send_json_error( 'Name cannot be empty! Please try again.' );
		}

		$term = sanitize_text_field( $_POST['data']['term'] );
		$tax  = sanitize_text_field( $_POST['data']['tax'] );
		$already_exists = term_exists( $term, $tax );

		if ( 0 !== $already_exists && null !== $already_exists ) {
			$status = get_option( 'taxonomy_status_' . absint( $already_exists ) );

			if ( 'allowed' === $status ) {
				wp_send_json_error( str_replace( 'wmd-', '', esc_html( $tax ) ) . ' option already exists! Please enter a new option.' );
			} elseif ( 'disallowed' === $status ) {
				wp_send_json_error( str_replace( 'wmd-', '', esc_html( $tax ) ) . ' is not a valid option! Please enter a new option.' );
			} elseif ( 'review' === $status ) {
				wp_send_json_error( str_replace( 'wmd-', '', esc_html( $tax ) ) . ' is currently under review! Please enter a new option.' );
			} else {
				$message  = 'The Term ' . sanitize_text_field( $tax ) . ' - ' . absint( $already_exists ) . ' exists, but status doesn\'t seem to be set!';
				wp_mail( 'cole@beawimp.org', 'Listing Manager Term Creation Error', $message );

				wp_send_json_error( 'An error occured and our site administrators have been notified. Please try another option.' );
			}
		}

		$term_obj = wp_insert_term( $term, $tax );

		if ( is_wp_error( $term_obj ) ) {
			$message  = 'The Term ' . sanitize_text_field( $tax ) . ' could not be set!';
			wp_mail( 'cole@beawimp.org', 'Listing Manager Term Creation Error', $message );

			wp_send_json_error( 'Cannot create new option. ' . esc_html( $term_obj->get_error_message() . '.' ) );
		}

		// Set the status as 'In Review'
		update_option( 'taxonomy_status_' . absint( $term_obj['term_id'] ), 'review' );

		// Get the term object and return the term_id and term name
		$term_obj = get_term( $term_obj['term_id'], $tax );
		$term_obj->taxonomy = str_replace( 'wmd-', '', $tax );

		wp_send_json_success( $term_obj );
	}

	public static function save_listing() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'create-edit-listing' ) ) {
			wp_send_json_error( 'Cannot validate request.' );
		}

		if ( ! wmd_is_wimp_plus_member() ) {
			wp_send_json_error( 'You do not have sufficient permissions to complete this request.' );
		}

		if ( ! isset( $_POST['data'] ) || empty( $_POST['data'] ) ) {
			wp_send_json_error( 'No data found!' );
		}

		// Check the post doesn't exist already
		if ( ! empty( $_POST['data']['id'] ) ) {
			$post_id = (int) $_POST['data']['id'];
		} else {
			$post_id = null;
		}

		$status = self::update_listing( $post_id, $_POST['data'] );

		if ( $status ) {
			wp_send_json_success( 'Member Listing Saved!' );
		} else {
			wp_send_json_error( 'An error occurred! Please try again.' );
		}
	}

	public static function post_query( $query ) {
		// If we are viewing the member listing archive page in the front-end
		if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'member-directory' ) ) {
			// If we are filtering a listing
			if ( isset( $_GET['filter'], $_GET['tax'], $_GET['terms'] ) && 'true' === $_GET['filter'] ) {
				$valid = self::validate_taxonomy( $_GET['tax'], $_GET['terms'] );

				// Allow us to present some notification the filter isn't valid to the front-end
				if ( ! $valid ) {
					self::$invalid_filter = true;
				}

				// All is well, set the tax query.
				$tax_query = array(
					array(
						'taxonomy' => $_GET['tax'],
						'field'    => 'slug',
						'terms'    => explode( ',', $_GET['terms'] ),
					),
				);
				$query->set( 'tax_query', $tax_query );
			}
		}
	}

	/**
	 * Allows us to validate the content being requested for filtering listings.
	 *
	 * @param string $tax
	 * @param string $terms comma separated list if more than one term
	 *
	 * @return bool
	 */
	protected static function validate_taxonomy( $tax, $terms ) {
		$allowed_tax = array(
			'wmd-industry',
			'wmd-technology',
			'wmd-type',
		);

		// Return early if we don't have a valid taxonomy
		if ( ! taxonomy_exists( $tax ) || ! in_array( $tax, $allowed_tax ) ) {
			return false;
		}

		// Return early if one of the terms doesn't exist
		$terms = explode( ',', $terms );
		foreach( (array) $terms as $term ) {
			// break out the loop if term doesn't exist.
			if ( ! term_exists( $term ) ) {
				$in_valid = false;
				break;
			}
		}

		return ! isset( $in_valid );
	}

	protected static function update_listing( $post_id = null, $data ) {
		if ( empty( $data ) ) {
			return false;
		}

		if ( ! wmd_is_wimp_plus_member() ) {
			return false;
		}

		$prefix = 'wmd_';
		$post_data = array(
			'post_author'    => get_current_user_id(),
			'post_name'      => sanitize_title_with_dashes( $data['title'] ),
			'post_title'     => sanitize_text_field( $data['title'] ),
			'post_content'   => wp_kses_post( $data['content'] ),
			'post_type'      => 'member-directory',
			'post_status'    => 'publish',
			'comment_status' => false,
			'ping_status'    => false,
		);

		if ( isset( $post_id ) ) {
			$post_data['ID'] = (int) $post_id;
			$post_id = wp_update_post( $post_data );
		} else {
			$post_id = wp_insert_post( $post_data );
		}

		// Make sure we have a post actually saved.
		if ( 0 === $post_id ) {
			return false;
		}

		// Save the logo
		update_post_meta( $post_id, $prefix . 'company_logo', esc_url( $data['logo'] ) );
		update_post_meta( $post_id, $prefix . 'company_logo_id', absint( $data['logo-id'] ) );

		// Save the portfolio items
		unset( $data['portfolio'][0] ); // Remove the empty portfolio item from the array.
		update_post_meta( $post_id, $prefix . 'portfolio_items', self::sanitize_array( $data['portfolio'], 'url' ) );

		// Save the url
		update_post_meta( $post_id, $prefix . 'url', esc_url( $data['url'] ) );

		// Save taxonomies
		$taxonomies = array(
			'price-low'  => WMD_Taxonomies::PRICE_LOW,
			'price-high' => WMD_Taxonomies::PRICE_HIGH,
			'state'      => WMD_Taxonomies::STATE,
			'city'       => WMD_Taxonomies::CITY,
			'industry'   => WMD_Taxonomies::INDUSTRY,
			'tech'       => WMD_Taxonomies::TECHNOLOGY,
			'type'       => WMD_Taxonomies::TYPE,
			'level'      => WMD_Taxonomies::LEVEL,
		);

		foreach ( $taxonomies as $key => $tax ) {
			if ( 'location' === $key ) {
				// Figure out if we need to create or update an existing state term
				if ( ! term_exists( $data['state'], $tax ) ) {
					$state_id = wp_insert_term( sanitize_text_field( $data['state'] ), $tax );
				} else {
					$state_id = get_term_by( 'slug', $data['state'], $tax )->term_id;
				}

				// Figure out if we need to create or update an existing city term
				if ( ! term_exists( $data['city'], $tax, $state_id ) ) {
					$city_id = wp_insert_term( sanitize_text_field( $data['city'] ), $tax, array(
						'parent' => (int) $state_id,
					) );
				} else {
					$city_id = get_term_by( 'slug', $data['city'], $tax )->term_id;
				}

				$location = array(
					$state_id,
					$city_id,
				);
				wp_set_object_terms( $post_id, $location, $tax );
			} elseif ( 'level' === $key ) {
				// For phase 1 we will not have different levels.
				wp_set_object_terms( $post_id, 'large', $tax );
			} elseif ( 'price-low' === $key || 'price-high' === $key ) {
				wp_set_object_terms( $post_id, self::sanitize_array( $data[ $key ] ), $tax );
			} elseif ( 'state' === $key || 'city' === $key ) {
				wp_set_object_terms( $post_id, absint( $data[ $key ] ), $tax );
			} else {
				wp_set_object_terms( $post_id, self::sanitize_array( $data[ $key ], 'int' ), $tax );
			}
		}

		return true;
	}

	/**
	 * Loops through an array and sanitizes the key and value
	 *
	 * @param $array
	 * @param $type
	 *
	 * @return array
	 */
	protected static function sanitize_array( $array, $type = '' ) {
		$clean = array();
		foreach ( (array) $array as $key => $val ) {
			switch ( $type ) {
				case 'url':
					$clean[ sanitize_key( $key ) ] = esc_url( $val );
					break;
				case 'int':
					$clean[ sanitize_key( $key ) ] = absint( $val );
					break;
				default:
					$clean[ sanitize_key( $key ) ] = sanitize_text_field( $val );
			}
		}

		return $clean;
	}

	/**
	 * Allows us to locate for the right template file to serve for the Member Directory post type
	 */
	public static function member_directory_templates( $template ) {
		if ( get_query_var( 'member-directory' ) && is_single() ) {
			$template = self::locate_template( 'single-member-directory.php', true );
		} elseif ( is_post_type_archive( 'member-directory' ) ) {
			$template = self::locate_template( 'archive-member-directory.php', true );
		}
		return $template;
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
			$stylesheet_template_path = get_stylesheet_directory() . $template_path;
			$theme_template_path  = get_template_directory() . $template_path;
			$plugin_template_path = WMD_TEMPLATES . sanitize_file_name( untrailingslashit( $template_name ) );

			// Check if child theme has template
			if ( file_exists( $stylesheet_template_path ) ) {
				$path = $stylesheet_template_path;
				break;

			// Check if parent theme has template
			} elseif ( file_exists( $theme_template_path ) ) {
				$path = $theme_template_path;
				break;

			// Check if plugin has it
			} elseif ( file_exists( $plugin_template_path ) ) {
				$path = $plugin_template_path;
				break;

			}
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
		$prefix = 'wmd_'; // Prefix for all fields

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