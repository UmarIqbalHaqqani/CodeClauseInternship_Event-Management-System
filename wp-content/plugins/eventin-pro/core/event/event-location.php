<?php

namespace Etn_Pro\Core\Event;

use Etn\Utils\Helper;
use Etn_Pro\Utils\Helper as UtilsHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomy Class.
 * Taxonomy class for taxonomy of Event location.
 * @extend Inherit class \Etn\Base\taxonomy Abstract Class
 *
 * @since 1.0.0
 */
class Event_Location extends \Etn\Base\Taxonomy {

	use \Etn\Traits\Singleton;

	/**
	* Location field.
	*/
	public function init() {
		// showing in add/ edit form.
		add_action('etn_location_add_form_fields', [$this, 'location_taxonomy_add_new_field'], 10, 1);
		add_action('etn_location_edit_form_fields', [$this, 'location_taxonomy_edit_field'], 10, 1);

		// save data in database.
		add_action('edited_etn_location', [$this, 'taxonomy_save_meta_field'], 10, 1);
		add_action('create_etn_location', [$this, 'taxonomy_save_meta_field'], 10, 1);

		// Show in table.
		add_filter('manage_edit-etn_location_columns', [$this, 'custom_fields_list_title']);
		add_action('manage_etn_location_custom_column', [$this, 'custom_fields_list_display'], 10, 3);
	}

  /**
  * set custom post type name
  */
  public function get_name() {
		return 'etn_location';
	}

	public function get_cpt() {
		return 'etn';
	}

	// Operation custom post type
	public function flush_rewrites() {
	}

	/**
	 * Create page
	 *
	 * @param string $title_of_the_page
	 * @param string $content
	 * @param [type] $parent_id
	 * @return void
	 */
	public function create_page() {
		$page_id = Helper::create_page( 'etn-location', '', null , '_' );

		return $page_id;
	}

	/**
	* Taxonomy array
	*/
	public function taxonomy() {

		$labels = array(
			'name'              => esc_html__( 'Location', 'eventin-pro' ),
			'singular_name'     => esc_html__( 'Location', 'eventin-pro' ),
			'search_items'      => esc_html__( 'Search Location', 'eventin-pro' ),
			'all_items'         => esc_html__( 'All Location', 'eventin-pro' ),
			'parent_item'       => esc_html__( 'Parent Location', 'eventin-pro' ),
			'parent_item_colon' => esc_html__( 'Parent Location:', 'eventin-pro' ),
			'edit_item'         => esc_html__( 'Edit Location', 'eventin-pro' ),
			'update_item'       => esc_html__( 'Update Location', 'eventin-pro' ),
			'add_new_item'      => esc_html__( 'Add New Location', 'eventin-pro' ),
			'new_item_name'     => esc_html__( 'New Location Name', 'eventin-pro' ),
			'menu_name'         => esc_html__( 'Location', 'eventin-pro' ),
			'not_found' 		=> esc_html__( 'No Location Found', 'eventin-pro' ),
			'no_terms' 			=> esc_html__( 'No Location Found', 'eventin-pro' ),
			'back_to_items' 	=> esc_html__( '← Back to Location', 'eventin-pro' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'public'            => true,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'show_in_menu'      => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'etn_location' ),
		);

		return $args;
	}

		/**
	 * Add menu
	 */
	public function menu() {

		$parent = 'etn-events-manager';
		$name   = $this->get_name();
		$cpt    = $this->get_cpt(); 
	}
 
	/**
	 * Add new field
	 */
	public function location_taxonomy_add_new_field() {
		?>
		<!-- Address -->
		<div class="form-field term-group">
			<label for="address"><?php esc_html_e('Address', 'eventin-pro'); ?></label>
			<textarea type="number" name="address" id="address" rows="5" cols="5"></textarea>
			<p class="description"><?php esc_html_e('Event location address. Note: From this address, latitude and longitude fields will be populated. After updating address, click the "Address Position" button to auto populate the latitude and longitude fields.', 'eventin-pro'); ?></p>
		</div>
		<!-- Email -->
		<div class="form-field term-group">
			<label for="location_email"><?php esc_html_e('Email', 'eventin-pro'); ?></label>
			<input type="email" id="location_email" name="location_email" value="">
			<p><?php esc_html_e('Email of the location', 'eventin-pro'); ?></p>
		</div>
		<!-- Latitude -->
		<div class="form-field term-group">
			<label for="location_latitude"><?php esc_html_e('Latitude', 'eventin-pro'); ?></label>
			<input type="text" id="location_latitude" name="location_latitude" value="">
			<p><?php esc_html_e('Latitude of the location', 'eventin-pro'); ?></p>
		</div>
		<!-- Longitude -->
		<div class="form-field term-group">
			<label for="location_longitude"><?php esc_html_e('Longitude', 'eventin-pro'); ?></label>
			<input type="text" id="location_longitude" name="location_longitude" value="">
			<p><?php esc_html_e('Longitude of the location', 'eventin-pro'); ?></p>
		</div>
		<!-- Location map -->
		<div class="form-field term-group">
			<?php
					$settings = get_option( "etn_event_options" );
					$api_key  = isset( $settings['google_api_key'] ) ? $settings['google_api_key']  : '';
					if ( empty( $api_key ) ) {
							$settings_page_url = Helper::kses( '<a href="' . esc_url( admin_url() . 'admin.php?page=etn-event-settings&etn_tab=tab5&key=google-meet-options' ).'" target="_blank" >'. esc_html__('Settings', 'eventin-pro').'</a>', 'eventin-pro' );
							?>
							<p class="location-map-api-msg"><?php echo esc_html__('Google Api Key is empty. Please fill the api key field from ', 'eventin-pro') . $settings_page_url; ?> </p>
							<?php
					} else {
							?>
							<a href="#" id="etn-location-map-position" class="button button-primary"><?php esc_html_e('Address Map Position', 'eventin-pro'); ?></a>
							<p><?php esc_html_e('From address field value, Position will show in map.', 'eventin-pro'); ?></p>
							<div class="etn-location-map" data-lat="37.4224428" data-long="-122.0842467" data-zoom="14">
									<div id="etn-location-map-container"></div>
							</div>
							<?php
					}
			?>
		</div>
		<?php
	}

	/**
	 * Update field
	 */
		public function location_taxonomy_edit_field( $term ) {
		?>
			<!-- Address -->
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="address"><?php esc_html_e('Address', 'eventin-pro' ); ?></label>
				</th>
				<td>
					<textarea type="address" id="address" name="address"
					rows="5" cols="5"><?php esc_attr_e( get_term_meta($term->term_id, 'eventin-pro', true) ); ?></textarea>
					<p class="description"><?php esc_html_e('Event location address. Note: From this address, latitude and longitude fields will be populated. After updating address, click the "Address Position" button to auto populate the latitude and longitude fields.', 'eventin-pro'); ?></p>
				</td>
			</tr>
			<!-- Email -->
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="location_email"><?php esc_html_e('Email', 'eventin-pro'); ?></label>
				</th>
				<td>
					<?php $location_email = get_term_meta($term->term_id, 'location_email', true); ?>
					<input type="email" id="location_email" name="location_email" value="<?php echo esc_attr( $location_email ); ?>">
					<p><?php esc_html_e('Email of the location', 'eventin-pro'); ?></p>
				</td>
			</tr>
			<!-- Latitude -->
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="location_latitude"><?php esc_html_e('Latitude', 'eventin-pro'); ?></label>
				</th>
				<td>
					<?php
						$location_latitude = get_term_meta($term->term_id, 'location_latitude', true);
						if ( empty( $location_latitude ) ) {
							$location_latitude = '37.4224428';
						}
					?>
					<input type="text" id="location_latitude" name="location_latitude" value="<?php echo esc_attr( $location_latitude ); ?>">
					<p><?php esc_html_e('Latitude of the location', 'eventin-pro'); ?></p>
				</td>
			</tr>
			<!-- Longitude -->
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="location_longitude"><?php esc_html_e('Longitude', 'eventin-pro'); ?></label>
				</th>
				<td>
					<?php
						$location_longitude = get_term_meta($term->term_id, 'location_longitude', true);
						if ( empty( $location_longitude ) ) {
							$location_longitude = '-122.0842467';
						}
					?>
					<input type="text" id="location_longitude" name="location_longitude" value="<?php echo esc_attr( $location_longitude ); ?>">
					<p><?php esc_html_e('Longitude of the location', 'eventin-pro'); ?></p>
				</td>
			</tr>
			<!-- Location map -->
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="location_map"><?php esc_html_e('Location Map', 'eventin-pro'); ?></label>
				</th>
				<td>
					<?php
						$settings = get_option( "etn_event_options" );
						$api_key  = isset( $settings['google_api_key'] ) ?  $settings['google_api_key'] : '';

						if ( empty( $api_key ) ) {
							$settings_page_url = Helper::kses( '<a href="' . esc_url( admin_url() . 'admin.php?page=etn-event-settings&etn_tab=tab5' ).'" target="_blank" >'. esc_html__('Settings', 'eventin-pro').'</a>', 'eventin-pro' );
							?>
							<p class="location-map-api-msg"><?php echo esc_html__('Google Api Key is empty. Please fill the api key field from ', 'eventin-pro') . $settings_page_url; ?> </p>
							<?php
						} else {
							?>
								<a href="#" id="etn-location-map-position" class="button button-primary"><?php esc_html_e('Address Position', 'eventin-pro'); ?></a>
								<p><?php esc_html_e('From address field value, Position will show in map.', 'eventin-pro'); ?></p>
								<div class="etn-location-map" data-lat="<?php echo esc_attr( $location_latitude ); ?>" data-long="<?php echo esc_attr( $location_longitude ); ?>" data-zoom="12">
									<div id="etn-location-map-container"></div>
								</div>
							<?php
						}
					?>
				</td>
			</tr>
		<?php
	}

	/**
	 * Save field
	 */
	public function taxonomy_save_meta_field( $term_id ) {
		$location_input  = array(
			'location_email'     => FILTER_VALIDATE_EMAIL ,
			'address'            => array(),
			'location_latitude'  => array(),
			'location_longitude' => array(),
		);

		$location_input = filter_input_array( INPUT_POST, $location_input );

		if ( is_array( $location_input ) && count( $location_input )>0 ) {
			foreach ( $location_input as $key => $value ) {
				update_term_meta( $term_id, $key , $value , false );
			}
		}
	}

	/**
	 * Column added to location taxonomy admin screen.
	 */
	public function custom_fields_list_title($columns) {
		$columns['location_email']      = esc_html__('Email', 'eventin-pro');
		$columns['location_latitude']   = esc_html__('Lat', 'eventin-pro');
		$columns['location_longitude']  = esc_html__('Long', 'eventin-pro');

		return $columns;
	}

	/**
	 * Location column value added to product category admin screen.
	 */
	public function custom_fields_list_display($columns, $column, $id){
		// Get the image ID for the category
		switch ( $column ) {
			case 'location_email' :
				$location_email = get_term_meta($id, 'location_email', true);
				echo esc_html( $location_email );
				break;
			case 'location_latitude' :
				$location_latitude = get_term_meta($id, 'location_latitude', true);
				echo esc_html( $location_latitude );
				break;
			case 'location_longitude' :
				$location_longitude = get_term_meta($id, 'location_longitude', true);
				echo esc_html( $location_longitude );
			break;
		}
	}

}


