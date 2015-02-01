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
		'locations'    => array(),
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
				<input type="text" name="wmd[logo]" id="logo" value="<?php echo esc_attr( $logo[0] ); ?>" data-save />
				<input name="logo-id" type="hidden" id="logo-id" value="<?php echo absint( $data['logo_id'] ); ?>" data-save />
				<input type="button" value="Add Image" class="wmd-media-btn button" id="upload-image" />
			</div>
		</div>
		<div>
			<label>Portfolio</label>
			<div class="media-upload">
				<?php foreach ( $data['portfolio'] as $id => $image ) : ?>
					<input type="text" name="wmd[portfolio][<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $image ); ?>" data-id="<?php echo esc_attr( $id ); ?>" data-save />
					<input type="button" value="Add Image" class="wmd-media-btn button upload-portfolio" />
				<?php endforeach; ?>
			</div>
		</div>
		<div>
			<label for="url">Website URL</label>
			<input type="url" name="wmd[url]" id="url" value="<?php echo esc_url( $data['url'] ); ?>" data-save />
		</div>
		<div>
			<label>Price Range</label>
			<div class="two-column-form">
				<input type="number" name="wmd[low_price]" id="price-low" class="price-low" value="<?php echo esc_attr( $data['low_price'][0]->slug ); ?>" data-save /> to
				<input type="number" name="wmd[high_price]" id="price-high" class="price-high" value="<?php echo esc_attr( $data['high_price'][0]->slug ); ?>" data-save />
			</div>
		</div>
		<div>
			<label>Location</label>
			<div class="two-column-form">
				<?php if ( empty( $data['locations'] ) ) : ?>
					<input type="text" name="wmd[state]" id="state" class="state" placeholder="State" data-save />,
					<input type="text" name="wmd[city]" id="city" class="city" placeholder="City" data-save />
				<?php else : ?>
					<?php echo wmd_process_location_fields( $data['locations'] ); ?>
				<?php endif; ?>
			</div>
		</div>
		<div>
			<label for="industry">Industries</label>
			<?php
			$industries = get_terms( WMD_Taxonomies::INDUSTRY, array(
				'hide_empty' => false,
			) );
			?>
			<?php foreach ( $industries as $industry ) : ?>
				<label for="<?php echo esc_attr( $industry->term_id ); ?>">
					<input type="checkbox"
					       name="wmd[industry][<?php echo esc_attr( $industry->term_id ); ?>]"
					       value="<?php echo esc_attr( $industry->term_id ); ?>"
					       id="<?php echo esc_attr( $industry->term_id ); ?>"
					       data-save
						   <?php wmd_checked( $industry->term_id, $data['industries'] ); ?>>
					<?php echo esc_html( $industry->name ); ?>
				</label>
			<?php endforeach; unset( $industries ); ?>
			<input type="text" name="wmd[industry][new]" placeholder="Add New Industry" data-type="<?php echo esc_attr( WMD_Taxonomies::INDUSTRY ); ?>" class="add-new" />
			<button class="button add-new-tax">Add New</button>
		</div>
		<div>
			<label for="technologies">Technologies</label>
			<?php
			$technologies = get_terms( WMD_Taxonomies::TECHNOLOGY, array(
				'hide_empty' => false,
			) );
			?>
			<?php foreach ( $technologies as $tech ) : ?>
				<label for="<?php echo esc_attr( $tech->term_id ); ?>">
					<input type="checkbox"
					       name="wmd[tech][<?php echo esc_attr( $tech->term_id ); ?>]"
					       value="<?php echo esc_attr( $tech->term_id ); ?>"
					       id="<?php echo esc_attr( $tech->term_id ); ?>"
					       data-save
						   <?php wmd_checked( $tech->term_id, $data['technologies'] ); ?>>
					<?php echo esc_html( $tech->name ); ?>
				</label>
			<?php endforeach; unset( $technologies ); ?>
			<input type="text" name="wmd[tech][new]" placeholder="Add New Technology" data-type="<?php echo esc_attr( WMD_Taxonomies::TECHNOLOGY ); ?>" class="add-new" />
			<button class="button add-new-tax">Add New</button>
		</div>
		<div>
			<label for="services">Services</label>
			<?php
			$types = get_terms( WMD_Taxonomies::TYPE, array(
				'hide_empty' => false,
			) );
			?>
			<?php foreach ( $types as $type ) : ?>
				<label for="<?php echo esc_attr( $type->term_id ); ?>">
					<input type="checkbox"
					       name="wmd[type][<?php echo esc_attr( $type->term_id ); ?>]"
					       value="<?php echo esc_attr( $type->term_id ); ?>"
					       id="<?php echo esc_attr( $type->term_id ); ?>"
					       data-save
						   <?php wmd_checked( $type->term_id, $data['types'] ); ?>>
					<?php echo esc_html( $type->name ); ?>
				</label>
			<?php endforeach; unset( $types ); ?>
			<input type="text" name="wmd[type][new]" placeholder="Add New Service" data-type="<?php echo esc_attr( WMD_Taxonomies::TYPE ); ?>" class="add-new" />
			<button class="button add-new-tax">Add New</button>
		</div>
		<div>
			<input type="submit" value="Save Listing" id="submit-listing" />
			<div id="saving-listing">
				<img src="<?php echo esc_url( WMD_URL . 'images/loading.gif' ); ?>" alt="Saving Listing..." width="20" height="20" /> Saving...
			</div>
		</div>
	</form>
	<?php
}