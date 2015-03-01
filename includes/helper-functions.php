<?php

/**
 * Contains any helper/template functions that will allow us to handle things in the front-end
 * or possibly the admin area of the WMD plugin
 */

/**
 * Fetches a listing based on user ID
 *
 * @param null $user_id
 *
 * @return array|bool|object
 */
function wmd_get_listing_by_user_id( $user_id = null ) {
	if ( ! isset( $user_id ) ) {
		return false;
	}

	$args = array(
		'post_type' => 'member-directory',
		'author' => (int) $user_id,
		'no_found_rows' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);
	$listing_query = new WP_Query( $args );

	if ( 1 !== $listing_query->post_count ) {
		return false;
	}

	$listing = wmd_get_listing( $listing_query->post );

	return $listing;
}

/**
 * Processes a post object or post ID into a member directory object.
 *
 * @param obj|int $post   The post object or post ID
 * @param string     $output Accepts OBJECT or ARRAY
 *
 * @return array|object|bool
 */
function wmd_get_listing( $_post = null, $output = OBJECT ) {
	if ( empty( $_post ) && isset( $GLOBALS['post'] ) ) {
		$post = $GLOBALS['post'];
	}

	if ( ! isset( $post ) ) {
		if ( is_a( $_post, 'WP_Post' ) ) {
			$post = $_post;
		} elseif ( is_int( $_post ) ) {
			$post = get_post( $_post );
		} else {
			// If all else fails, return empty.
			return false;
		}
	}

	// Fetch the company logo
	$prefix  = 'wmd_';
	$logo    = get_post_meta( $post->ID, $prefix . 'company_logo', true );
	$logo_id = get_post_meta( $post->ID, $prefix . 'company_logo_id', true );

	$data = array(
		'ID'                => (int) $post->ID,
		'member_id'         => (int) $post->post_author,
		'title'             => $post->post_title,
		'slug'              => $post->post_name,
		'content'           => wptexturize( wpautop( $post->post_content ) ),
		'logo_id'           => ( ! empty( $logo ) ? (int) $logo_id : 0 ),
		'portfolio'         => get_post_meta( $post->ID, $prefix . 'portfolio_items', true ),
		'url'               => get_post_meta( $post->ID, $prefix . 'url', true ),
		'low_price'         => get_the_terms( $post->ID, WMD_Taxonomies::PRICE_LOW ),
		'high_price'        => get_the_terms( $post->ID, WMD_Taxonomies::PRICE_HIGH ),
		'state'             => get_the_terms( $post->ID, WMD_Taxonomies::STATE ),
		'city'              => get_the_terms( $post->ID, WMD_Taxonomies::CITY ),
		'industries'        => wmd_get_terms( WMD_Taxonomies::INDUSTRY, $post->ID ),
		'technologies'      => wmd_get_terms( WMD_Taxonomies::TECHNOLOGY, $post->ID ),
		'types'             => wmd_get_terms( WMD_Taxonomies::TYPE, $post->ID ),
		'member_level'      => get_the_terms( $post->ID, WMD_Taxonomies::LEVEL ),
		'post_date'         => $post->post_date,
		'post_date_gmt'     => $post->post_date_gmt,
		'post_modified'     => $post->post_modified,
		'post_modified_gmt' => $post->post_modified_gmt,
		'guid'              => $post->guid,
	);

	// If the output requested is anything but OBJECT, we'll return an array
	if ( OBJECT !== $output ) {
		return $data;
	}

	return (object) $data;
}

/**
 * Fetches terms and handles returning only publicly available terms
 *
 * If no post id is passed, we'll fetch all terms for that taxonomy.
 * If a post id is passed, we'll fetch all terms set for that post.
 *
 * @param      $tax
 * @param null $post_id
 *
 * @return array|bool|WP_Error
 */
function wmd_get_terms( $tax, $post_id = null ) {
	if ( empty( $tax ) ) {
		return false;
	}

	if ( empty( $post_id ) ) {
		$terms = get_terms( $tax, array(
			'hide_empty' => false,
		) );
	} else {
		$terms = get_the_terms( $post_id, $tax );
	}

	if ( is_array( $terms ) ) {
		foreach ( $terms as $id => $t ) {
			$status = get_option( 'taxonomy_status_' . absint( $t->term_id ) );

			if ( 'review' === $status && is_single() || 'false' === $status ) {
				unset( $terms[ $id ] );
			} elseif ( 'review' === $status && 'listing_manager' === bp_current_component() ) {
				$terms[ $id ]->name = $terms[ $id ]->name . '*';
			}
		}
	}

	return $terms;
}

/**
 * Pairs up the low and high price values into a single string
 *
 * @param int $low  The low price
 * @param int $high The high price
 *
 * @return void
 */
function wmd_format_prices( $low, $high ) {
	if ( ! $low || ! $high || ! is_array( $low ) || ! is_array( $high ) ) {
		echo 'Prices not listed';

		return false;
	}

	$output  = '$' . number_format( intval( array_shift( $low )->name ) );
	$output .= '-$' . number_format( intval( array_shift( $high )->name ) );

	echo esc_html( $output );
}

/**
 * Formats an array of location terms into a read-able format
 *
 * @param array $locations The array of location term objects
 *
 * @return bool
 */
function wmd_format_location( $state, $city ) {
	if ( ! empty( $state ) || ! empty( $state ) ) {
		return false;
	}

	echo esc_html( $city . ', ' . wmd_format_state( $state, 'abbr' ) );
}

/**
 * Format State
 *
 * Note: Does not format addresses, only states. $input should be as exact as possible, problems
 * will probably arise in long strings, example 'I live in Kentukcy' will produce Indiana.
 *
 * @example echo myClass::format_state( 'Florida', 'abbr'); // FL
 * @example echo myClass::format_state( 'we\'re from georgia' ) // Georgia
 *
 * @param  string $input  Input to be formatted
 * @param  string $format Accepts 'abbr' to output abbreviated state, default full state name.
 * @return string         Formatted state on success,
 */
function wmd_format_state( $input, $format = '' ) {
	if ( ! $input || empty( $input ) ) {
		return '';
	}

	$states = array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District Of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	);

	foreach ( $states as $abbr => $name ) {
		if ( preg_match( "/\b($name)\b/", ucwords( strtolower( $input ) ), $match ) ) {
			if ( 'abbr' == $format ) {
				return $abbr;
			} else {
				return $name;
			}
		} elseif ( preg_match( "/\b($abbr)\b/", strtoupper( $input ), $match ) ) {
			if ( 'abbr' == $format ) {
				return $abbr;
			} else {
				return $name;
			}
		}
	}

	return '';
}

/**
 * Displays the portfolio slider
 *
 * @param array $items An array of portfolio items
 *
 * @return bool
 */
function wmd_display_portfolio( $items = array(), $type = 'slide' ) {
	if ( ! is_array( $items ) || empty( $items ) ) {
		return false;
	}

	if ( 'slide' === $type ) :
		?>
		<div class="flexslider">
			<ul class="slides">
				<?php foreach ( $items as $key => $value ) : ?>
					<li><img src="<?php echo esc_url( $value ); ?>" /></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	else :
		?>
		<ul id="portfolio-list">
			<?php foreach ( $items as $key => $value ) :
				$image = wp_prepare_attachment_for_js( $key ); ?>
				<li>
					<img src="<?php echo esc_url( $value ); ?>" alt="<?php echo esc_attr( $image['title'] ); ?>" />
					<?php
					if ( ! empty( $image['caption'] ) ) {
						echo '<p>' . wp_kses_post( $image['caption'] ) . '</p>';
					}
					?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	endif;
}

/**
 * Extracts the main member directory terms from the listing object
 *
 * @param object $listing The member listing object
 *
 * @return array $data An array of term objects for a specific listing object
 */
function wmd_list_terms( $listing ) {
	if ( ! is_object( $listing ) ) {
		return false;
	}

	if ( ! isset( $listing->industries, $listing->types, $listing->technologies ) ) {
		return false;
	}

	$allowed_fields = array(
		'industries',
		'types',
		'technologies',
	);
	$data = array();

	foreach ( $listing as $term => $values ) {
		if ( ! in_array( $term, $allowed_fields ) ) {
			continue;
		}

		$data[ $term ] = $values;
	}

	return $data;
}

/**
 * Applies the standard formatting of a listings meta data
 * Requires a listing object (@see wmd_get_listing()), if no type is passed, we'll output all.
 *
 * Pass taxonomy names through $type to return only the terms for that taxonomy.
 *
 * @param object $listing The listing object
 * @param string $type    The name of a listing taxonomy
 * @param bool   $return  Allows us to return or echo the results
 *
 * @return string|bool
 */
function wmd_format_terms( $listing, $type = null, $return = false ) {
	if ( empty( $listing ) || ! is_object( $listing ) ) {
		return '';
	}

	$terms        = wmd_list_terms( $listing );
	$html_default = array(
		'before-meta' => '<tr>',
		'after-meta'  => '</tr>',
		'before-list' => '<td>',
		'after-list'  => '</td>',
	);
	$html         = apply_filters( 'wmd_meta_html', $html_default );
	$output       = '';

	$output .= sprintf( '%s', wp_kses_post( $html['before-meta'] ) );

	foreach ( $terms as $name => $term ) {
		// Only apply the term type if it is in our array.
		// If we pass an empty array, we'll just output everything.
		if ( $name !== $type || ! $term ) {
			continue;
		}

		$output .= sprintf( '%s', wp_kses_post( $html['before-list'] ) );
		$output .= '<strong>' . esc_html( wmd_convert_tax_name( $name ) ) . ':</strong>';
		$output .= sprintf( '%s', wp_kses_post( $html['after-list'] ) );

		$output .= sprintf( '%s', wp_kses_post( $html['before-list'] ) );
		$end = end( $term );
		// Create a comma separated list of the terms attached to our taxonomy
		foreach ( $term as $term_id => $t ) {

			$output .= '<a href="' . esc_url( get_term_link( $t ) ) . '">'
			           . esc_html( $t->name ) . '</a>';

			// Only append a comma if we are not on the last item in the array
			if ( $end->term_id !== $t->term_id ) {
				$output .= ', ';
			}
		}
		$output .= sprintf( '%s', wp_kses_post( $html['after-list'] ) );
	}

	$output .= sprintf( '%s', wp_kses_post( $html['after-meta'] ) );

	// We may need to return our string instead of echoing it.
	if ( $return ) {
		return $output;
	}

	echo $output;
}

/**
 * Converts the default Taxonomy names into something more front-end readable.
 *
 * @param string $tax The name of the taxonomy we want to modify
 *
 * @return string $output
 */
function wmd_convert_tax_name( $tax ) {
	switch ( $tax ) {
		case 'industries':
			$output = 'Industry';
			break;
		case 'types':
			$output = 'Type';
			break;
		case 'technologies':
			$output = 'Technology';
			break;
		default:
			$output = '';
	}

	return $output;
}

/**
 * Produces a miniature member profile with a rounded avatar and name and website URL.
 *
 * @param null   $id       The member ID
 * @param string $site_url The member's website URL.
 */
function wmd_display_member( $id = null, $site_url = '' ) {
	if ( ! isset( $id ) ) {
		return;
	}
	?>
	<aside id="member-mini-profile">
		<div class="member-avatar">
			<?php echo bp_core_fetch_avatar( array(
				'item_id' => (int) $id,
				'type'    => 'full',
			) ); ?>
		</div>
		<div class="member-profile">
			<p>Contact: <a href="<?php echo esc_url( bp_core_get_user_domain( $id ) ); ?>">
				<?php echo esc_html( bp_core_get_user_displayname( $id ) ); ?>
			</a>

			<?php
				if ( ! empty( $site_url ) ) :
					echo '<br /><a href="' . esc_url( $site_url ) . '">' . esc_url( $site_url ) . '</a>';
				endif;
			?>
			</p>

		</div>
	</aside>
	<?php
}

/**
 * Allows us to recursively search a multidimensional array for a matching value.
 * If the item being looped/searched contains an object, we'll convert those values
 *
 * @param string $needle   The value to search for
 * @param array  $haystack The array we want to search against
 * @param bool   $strict   Force an exact match
 *
 * @return bool
 */
function wmd_in_array_r( $needle, $haystack, $strict = false ) {
	foreach ( $haystack as $item ) {
		if ( is_object( $item ) ) {
			$item = get_object_vars( $item );
		}

		if ( ( $strict ? $item === $needle : $item == $needle ) || ( is_array( $item ) && wmd_in_array_r( $needle, $item, $strict ) ) ) {
			return true;
		}
	}

	return false;
}

/**
 * A custom version of WordPress' selected() function to allow us to
 * check against an multidimensional array with objects.
 *
 * @param string $current The current item we are searching for
 * @param string $checked The array of items that are checked
 *
 * @return void
 */
function wmd_selected( $current, $checked ) {
	if ( is_array( $checked ) ) {
		echo ( wmd_in_array_r( $current, $checked ) ? ' selected="selected"' : '' );
	}
}

function wmd_filter_options() { ?>
	<section id="filter-results">
		<p>Filter By:</p>
		<div id="industry" class="filter">
			Industry
		</div>
		<div id="technology" class="filter">
			Technology
		</div>
		<div id="services" class="filter">
			Services
		</div>
	</section>

	<?php if ( WMD_Member_Directory::$invalid_filter ) : ?>
		<section class="filter-notification">
			<p>Oops! The filter options used are not valid! Please try a different filter option</p>
		</section>
	<?php endif;
}