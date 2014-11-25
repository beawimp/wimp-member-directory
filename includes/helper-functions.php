<?php

/**
 * Contains any helper/template functions that will allow us to handle things in the front-end
 * or possibly the admin area of the WMD plugin
 */

/**
 * Processes a post object or post ID into a member directory object.
 *
 * @param object|int $post   The post object or post ID
 * @param string     $output Accepts OBJECT or ARRAY
 *
 * @return array|object|bool
 */
function wmd_get_listing( $post = null, $output = OBJECT ) {
	// Check if we passed a post ID
	if ( is_int( $post ) ) {
		$post = get_post( $post );
	} elseif ( ! isset( $post ) || ! is_object( $post ) || empty( $post ) ) {
		global $post;

		// It's possible the global $post object isn't rendered...
		if ( empty( $post ) || ! isset( $post->ID ) ) {
			return false;
		}
	} else {
		// If all else fails, return empty.
		return false;
	}

	// Fetch the company logo
	$logo_id      = get_post_meta( $post->ID, '_wmd_company_logo_id', true );
	$portfolio    = get_post_meta( $post->ID, '_wmd_portfolio_items', true );
	$prices       = get_the_terms( $post->ID, 'price' );
	$locations    = get_the_terms( $post->ID, 'location' );
	$industries   = get_the_terms( $post->ID, 'industry' );
	$technologies = get_the_terms( $post->ID, 'technology' );
	$types        = get_the_terms( $post->ID, 'type' );
	$member_level = get_the_terms( $post->ID, 'level' );

	$data = array(
		'ID'                => (int) $post->ID,
		'member_id'         => (int) $post->post_author,
		'title'             => $post->post_title,
		'slug'              => $post->post_name,
		'logo_id'           => ( ! empty( $logo_id ) ? (int) $logo_id : '' ),
		'portfolio'         => $portfolio,
		'prices'            => $prices,
		'locations'         => $locations,
		'industries'        => $industries,
		'types'             => $types,
		'member_level'      => $member_level,
		'technologies'      => $technologies,
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
 * Formats an array of Price objects to be lowest to highest
 *
 * @param array $prices An array of term objects
 */
function wmd_format_prices( $prices ) {
	if ( ! is_array( $prices ) ) {
		return '';
	}

	// Sort the array lowest to height
	usort( $prices, function( $a, $b ) {
		return strcmp( $b->slug, $a->slug );
	} );

	$value = '';
	$end   = end( $prices );

	foreach ( $prices as $price ) {
		$value .= '$' . (int) $price->slug;

		if ( $price !== $end ) {
			$value .= ' - ';
		}
	}

	echo esc_html( $value );
}

function wmd_format_location( $locations ) {
	if ( ! is_array( $locations ) ) {
		return '';
	}

	$city  = '';
	$state = '';

	foreach ( $locations as $location ) {
		if ( 0 === $location->parent ) {
			$state = $location->name;
		} elseif ( 0 < $location->parent ) {
			$city = $location->name;
		}
	}

	if ( empty( $state ) || empty( $city ) ) {
		return '';
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