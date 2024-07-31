<?php

namespace Etn_Pro\Widgets\Event_Locations\Actions;

use Etn_Pro\Traits\Singleton;

defined( 'ABSPATH' ) || exit;

/**
* Handle ajax request
*/
class Ajax_Action {

	use Singleton;

	/**
		* Fire ajax hook.
	*/
	public function init() {
		$callback = array( 'get_events_by_location' );
		if ( ! empty( $callback ) ) {
			foreach ( $callback as $key => $value ) {
					add_action( 'wp_ajax_'.$value ,array( $this , $value ) );
					add_action( 'wp_ajax_nopriv_'.$value ,array( $this , $value ) );
			}
		}
	}

	/**
	 * Get all event by location.
	 * Find all location filtered to lat, lng, radius
	 *
	 * @return array
	 */
  public function get_events_by_location(){
			if ( ! wp_verify_nonce( $_POST['security'], 'location_map_nonce' ) ) {
					$msg = esc_html__( 'Nonce is not valid! Please try again.', 'eventin-pro' );

					$response = array(
							'status_code'   => 403,
							'message'       => array( $msg ),
							'data'          => array(),
						);

					wp_send_json_error( $response );

			} else {
					$lat            = floatval( $_POST['lat'] );
					$lng            = floatval( $_POST['lng'] );
					$radius         = absint( $_POST['radius'] );
					$redirect_url   = esc_url_raw( $_POST['redirect_url'] );
					$all_locations  = $this->query_all_locations( $lat, $lng, $radius );

					$locations_html      = '';
					$locations_html_data = array();
					$all_events_lat_lng  = [];
					$msg = esc_html__( 'ok', 'eventin-pro' );

					if ( !empty( $all_locations ) ) {
							$loc_index = 0;
							foreach ( $all_locations as $key => $location ) {
									$term_id        = $location['location_id'];
									$address        = get_term_meta( $term_id, 'address', true );
									$email          = get_term_meta( $term_id, 'location_email', true );
									$location_lat   = get_term_meta( $term_id, 'location_latitude', true );
									$location_lng   = get_term_meta( $term_id, 'location_longitude', true );
				
									$location_url       = $redirect_url . '?location=' . $term_id;
									$location_direction = '';
									if ( !empty( $location->lat ) ) {
											$location_direction = '<a href="http://maps.google.com/maps?saddr=' . $lat. ',' . $lng . '&daddr=' . $location['lat'] . ',' . $location->lng . '" target="_blank">' . esc_html__( 'Get Directions', 'eventin-pro' ) . '</a>';
									}

									$image_id  = get_term_meta( $term_id, 'location_image', true );
									$loc_image = \Wpeventin_Pro::assets_url() . 'images/placeholder.png';
									if ( ! empty( $image_id ) ) {
											$loc_image = wp_get_attachment_image_src( $image_id, 'thumbnail' );
											if ( is_array( $loc_image ) ) {
													$loc_image = $loc_image[0];
											}
									}
									if( !empty( $location['events'] ) ) :
										foreach( $location['events'] as $index => $event) :
											if ( !empty( $event ) && file_exists( ETN_PRO_DIR . "/widgets/event-locations/actions/location-markup.php" ) ) {
												ob_start();
												include ETN_PRO_DIR . "/widgets/event-locations/actions/location-markup.php";
												$locations_html_data[$loc_index] = ob_get_clean();

												$all_events_lat_lng[$loc_index] = [
													'lat' => $location_lat,
													'lng' => $location_lng,
												];
												$loc_index++;
											}
										endforeach;
									endif;
							}


					} else {
							$msg = "<p class='location-not-found'>";
							$msg .=  esc_html__( 'No event found in this location. Please try another location', 'eventin-pro');
							$msg .= "</p>";
					}

					if ( !empty( $locations_html_data ) ) {
							$locations_html = "<div class='etn-location-item-wrapper'><h4 class='location-area-title'>";
							$locations_html .= esc_html__( 'Nearby Events:', 'eventin-pro' );
							$locations_html .=  "</h4>";
							$locations_html .= join( '', $locations_html_data );;
							$locations_html .= "</div>";
					}

					$response = [
							'status_code'   => 200,
							'message'       => [ $msg ],
							'data'          => [
									'locations'             => $all_locations,
									'locations_html'        => $locations_html,
									'locations_html_data'   => $locations_html_data,
									'all_events_lat_lng'    => $all_events_lat_lng,
							]
					];
					wp_send_json_success( $response );
			}

			exit;
	}

	/**
	 * helper function to db query to get locations
	 *
	 * @param [float] $lat
	 * @param [float] $lng
	 * @param [int] $radius
	 * @return array
	 */
	public function query_all_locations ( $lat, $lng, $radius ) {
		global $wpdb;

		$sorting = '';
		if( empty( $sorting ) ) {
				$sorting = 'distance';
		}

		$max_row        = 100;
		$distance_unit  = 6371; // 6371 : 3959

		$sql = "SELECT terms.term_id, terms.name, terms.slug,
								term_lat.meta_value AS lat, term_lng.meta_value AS lng,
								( %d
										* acos(
												cos( radians(%s) )
												* cos( radians(term_lat.meta_value) ) * cos( radians(term_lng.meta_value) - radians(%s) )
												+ sin( radians(%s) )
												* sin( radians(term_lat.meta_value) )
										)
								) AS distance
								FROM $wpdb->terms AS terms
								INNER JOIN $wpdb->termmeta AS term_lat ON term_lat.term_id = terms.term_id AND term_lat.meta_key = 'location_latitude'
								INNER JOIN $wpdb->termmeta AS term_lng ON term_lng.term_id = terms.term_id AND term_lng.meta_key = 'location_longitude'
								GROUP BY lat HAVING distance < %d ORDER BY " . $sorting . " LIMIT 0, %d";

		$params = array(
				$distance_unit,
				$lat, $lng, $lat, $radius,
				$max_row,
			);

		$locations = $wpdb->get_results( $wpdb->prepare( $sql, $params ) );

		return $this->get_evenst_by_loication( $locations );
	}

	/**
	* Get all events by location
	*/
	public function get_evenst_by_loication( $locations ) {
		$result_data = array();
		if ( ! empty( $locations ) ) {
			foreach ( $locations as $index => $value ) {
				$term_id = is_object( $value ) ? $value->term_id : $index;
				if ( is_object( $value ) ) {
					$term_id    = $value->term_id;
					$term_name  = $value->name;
				}
				else{
					$term_id    = $index;
					$term_name  = $value;
				}


				$args = array(
					'post_type' => 'etn',
					'tax_query' => array(
						array(
									'taxonomy'  => 'etn_location',
									'field'     => 'term_id',
									'terms'     => $term_id,
							),
						),
					);

				$get_events = get_posts( $args );

				if ( count( $get_events )>0 ) {
					$events_by_location = array();
					foreach ( $get_events as $key => $event ){
						$events_by_location[ $key ][ 'event_id' ]     = $event->ID;
						$events_by_location[ $key ][ 'event_name' ]   = $event->post_title;
					}

					$result_data[ $index ][ 'events' ] = $events_by_location;
				}
				else{
					$result_data[ $index ][ 'events' ] = array();
				}
				$result_data[ $index ][ 'location_id' ]   = $term_id;
				$result_data[ $index ][ 'location_name' ] = $term_name;
			}

		}

		return $result_data;
	}

}
