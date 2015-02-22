<?php

/**
 * Echo the component's slug
 */
function bp_wimp_listing_slug() {
	echo bp_get_wimp_listing_slug();
}

/**
 * Return the component's slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps
 * avoid too-frequent direct calls to the $bp global.
 *
 * @uses apply_filters() Filter 'bp_get_wimp_listing_slug' to change the output
 *
 * @return str $slug The slug from $bp->wimp_listing->slug, if it exists.
 */
function bp_get_wimp_listing_slug() {
	global $bp;
	$slug = isset( $bp->wimp_listing->slug ) ? $bp->wimp_listing->slug : '';

	return apply_filters( 'bp_get_wimp_listing_slug', $slug );
}

/**
 * Output the URL to the Account Dashboard for the BP Component
 *
 * @param string $slug A sub page within if needed
 *
 * @return string
 */
function wmd_get_listing_manager_url( $slug = '' ) {
	global $bp;

	// To ensure our URL is correctly formatted we'll need to apply a forward slash before it
	if ( ! empty( $slug ) ) {
		$slug = trailingslashit( '/' . $slug );
	}

	return bp_loggedin_user_domain() . $bp->wimp_listing->slug . $slug;
}

/**
 * Display our listing management interface
 *
 * @param $data
 */
function wmd_get_listing_form( $data ) {
	$defaults = array(
		'ID'           => 0,
		'title'        => '',
		'slug'         => '',
		'content'      => '',
		'member_id'    => 0,
		'logo_id'      => 0,
		'portfolio'    => array(),
		'url'          => '',
		'low_price'    => 0,
		'high_price'   => 0,
		'state'        => '',
		'city'         => '',
		'industries'   => array(),
		'technologies' => array(),
		'types'        => array(),
	);
	$data = wp_parse_args( $data, $defaults );
	?>
	<form action="<?php echo esc_url( home_url() . $_SERVER['REQUEST_URI'] ); ?>" method="post" id="wmd-listings" class="standard-form base">
		<?php wp_nonce_field( 'create-edit-listing', 'wmd-listing-nonce', true ); ?>
		<input name="wmd[post-id]" value="<?php echo esc_attr( $data['ID'] ); ?>" id="id" type="hidden" save-data />
		<div>
			<label for="title">Title</label>
			<input type="text" name="wmd[title]" id="title" value="<?php echo esc_attr( $data['title'] ); ?>" data-save />
		</div>
		<div>
			<label for="content">Bio</label>
			<?php
			wp_editor( $data['content'], 'content', array(
				'wpautop'          => false,
				'media_buttons'    => false,
				'textarea_name'    => 'wmd[content]',
				'teeny'            => true,
				'drag_drop_upload' => true,

			) );
			?>
		</div>
		<div>
			<label for="logo">Logo</label>
			<div class="media-upload">
				<?php $logo = ( 0 !== $data['logo_id'] ) ? wp_get_attachment_image_src( $data['logo_id'], 'full' ) : array(); ?>
				<input type="text" name="wmd[logo]" id="logo" value="<?php echo ( isset( $logo[0] ) ) ? esc_attr( $logo[0] ) : ''; ?>" data-save />
				<input name="logo-id" type="hidden" id="logo-id" value="<?php echo absint( $data['logo_id'] ); ?>" data-save />
				<input type="button" value="Add Image" class="wmd-media-btn button" id="upload-image" />
			</div>
		</div>
		<div>
			<label>Portfolio</label>
			<div class="description">
				<p>Pro Tip: Any captions you add to images will display on your listing!</p>
			</div>
			<div class="media-upload">
				<?php
				if ( ! empty( $data['portfolio'] ) ) :
					foreach ( $data['portfolio'] as $id => $image ) : ?>
						<div class="portfolio-wrapper">
							<input type="text" name="wmd[portfolio][<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $image ); ?>" data-id="<?php echo esc_attr( $id ); ?>" data-type="portfolio" data-save />
							<input type="button" value="Add Image" class="wmd-media-btn button upload-portfolio" />
						</div>
					<?php endforeach;
				endif; ?>
				<div class="portfolio-wrapper">
					<input type="text" name="wmd[portfolio][0]" value="" data-id="0" data-type="portfolio" data-save />
					<input type="button" value="Add Image" class="wmd-media-btn button upload-portfolio" />
				</div>
			</div>
		</div>
		<div>
			<label for="url">Website URL</label>
			<input type="url" name="wmd[url]" id="url" value="<?php echo esc_url( $data['url'] ); ?>" placeholder="http://www.domain.com" data-save />
		</div>
		<div>
			<label>Price Range</label>
			<div class="two-column-form">
				<?php
				$price_low = ( isset( $data['low_price'][0]->slug ) ) ? $data['low_price'][0]->slug : '';
				$price_high = ( isset( $data['high_price'][0]->slug ) ) ? $data['high_price'][0]->slug : '';
				?>
				<input type="number" name="wmd[low_price]" id="price-low" class="price-low" value="<?php echo esc_attr( $price_low ); ?>" placeholder="0" data-save /> to
				<input type="number" name="wmd[high_price]" id="price-high" class="price-high" value="<?php echo esc_attr( $price_high ); ?>" placeholder="0" data-save />
			</div>
		</div>
		<div>
			<label>Location</label>
			<div class="two-column-form">
				<?php
				$states = get_terms( WMD_Taxonomies::STATE, array(
					'hide_empty' => false,
				) );
				$cities = get_terms( WMD_Taxonomies::CITY, array(
					'hide_empty' => false,
				) );
				?>
				<select name="wmd[state]" id="state" class="state-selection" data-save>
					<option value="0">Select A State</option>
					<?php foreach ( $states as $state ) :
						$current = ( isset( $data['state'][0] ) ) ? $data['state'][0]->name : ''; ?>
						<option value="<?php echo esc_attr( $state->term_id ); ?>"
							<?php selected( $current, $state->name ); ?>>
							<?php echo esc_html( $state->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>,
				<select name="wmd[city]" id="city" class="city-selection" data-save>
					<?php foreach ( $cities as $city ) :
						$current = ( isset( $data['city'][0] ) ) ? $data['city'][0]->name : ''; ?>
						<option value="<?php echo esc_attr( $city->term_id ); ?>"
							<?php selected( $current, $city->name ); ?>>
							<?php echo esc_html( $city->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<input type="text" name="wmd[city][new]" placeholder="Add New City" data-type="<?php echo esc_attr( WMD_Taxonomies::CITY ); ?>" class="add-new" />
				<button class="button add-new-tax" id="add-city">Add New</button>
			</div>
		</div>
		<div>
			<label for="industry">Industries</label>
			<?php
			$industries = get_terms( WMD_Taxonomies::INDUSTRY, array(
				'hide_empty' => false,
			) );
			?>
			<select multiple="multiple" class="industry-selection" name="wmd[industry][]" id="industry" data-save>
				<?php foreach ( $industries as $industry ) : ?>
					<option value="<?php echo esc_attr( $industry->term_id ); ?>"
						<?php wmd_selected( $industry->term_id, $data['industries'] ); ?>>
						<?php echo esc_html( $industry->name ); ?>
					</option>
				<?php endforeach; unset( $industries ); ?>
			</select>
			<?php if ( is_super_admin() ) : ?>
				<input type="text" name="wmd[industry][new]" placeholder="Add New Industry" data-type="<?php echo esc_attr( WMD_Taxonomies::INDUSTRY ); ?>" class="add-new" />
				<button class="button add-new-tax">Add New</button>
			<?php endif; ?>
		</div>
		<div>
			<label for="technologies">Technologies</label>
			<?php
			$technologies = get_terms( WMD_Taxonomies::TECHNOLOGY, array(
				'hide_empty' => false,
			) );
			?>
			<select multiple="multiple" class="tech-selection" name="wmd[tech][]" id="techonologies" data-save>
				<?php foreach ( $technologies as $tech ) : ?>
					<option value="<?php echo esc_attr( $tech->term_id ); ?>"
						<?php wmd_selected( $tech->term_id, $data['technologies'] ); ?>>
						<?php echo esc_html( $tech->name ); ?>
					</option>
				<?php endforeach; unset( $technologies ); ?>
			</select>
			<?php if ( is_super_admin() ) : ?>
				<input type="text" name="wmd[tech][new]" placeholder="Add New Technology" data-type="<?php echo esc_attr( WMD_Taxonomies::TECHNOLOGY ); ?>" class="add-new" />
				<button class="button add-new-tax">Add New</button>
			<?php endif; ?>
		</div>
		<div>
			<label for="services">Services</label>
			<?php
			$types = get_terms( WMD_Taxonomies::TYPE, array(
				'hide_empty' => false,
			) );
			?>
			<select multiple="multiple" class="services-selection" name="wmd[types][]" id="services" data-save>
				<?php foreach ( $types as $type ) : ?>
					<option value="<?php echo esc_attr( $type->term_id ); ?>"
						<?php wmd_selected( $type->term_id, $data['types'] ); ?>>
						<?php echo esc_html( $type->name ); ?>
					</option>
				<?php endforeach; unset( $type ); ?>
			</select>
			<?php if ( is_super_admin() ) : ?>
				<input type="text" name="wmd[type][new]" placeholder="Add New Service" data-type="<?php echo esc_attr( WMD_Taxonomies::TYPE ); ?>" class="add-new" />
				<button class="button add-new-tax">Add New</button>
			<?php endif; ?>
		</div>
		<div>
			<input type="submit" value="Save Listing" id="submit-listing" />
			<div id="saving-listing">
				<img src="<?php echo esc_url( WMD_URL . 'images/loading.gif' ); ?>" alt="Saving Listing..." width="20" height="20" /> Saving...
			</div>
			<div id="wmd-notifications"></div>
		</div>
	</form>
	<script type="text/template" id="portfolio-tmpl">
		<div class="portfolio-wrapper">
			<input type="text" name="wmd[portfolio][0]" value="" data-id="0" data-type="portfolio" data-save />
			<input type="button" value="Add Image" class="wmd-media-btn button upload-portfolio" />
		</div>
	</script>
	<?php
}