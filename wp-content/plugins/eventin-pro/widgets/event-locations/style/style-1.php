<?php
		use Etn_Pro\Utils\Helper;

		$location_latitude  = '37.4224428';
		$location_longitude = '-122.0842467';
		$default_address    = '';

		global $wp;
		$redirect_url = home_url( $wp->request );

		$locations_html = esc_html__( 'No event location is added yet.', 'eventin-pro' );
		$all_locations  = Helper::get_location_data( '', '', 'id' );

		if ( !empty( $all_locations )) {
			unset($all_locations[""]);
			$events_by_locations  = Etn_Pro\Widgets\Event_Locations\Actions\Ajax_Action::instance()->get_evenst_by_loication( $all_locations );
		}
		else{
			$events_by_locations  = array();
		}

		if ( !empty( $events_by_locations ) ) {
			ob_start();
				foreach ( $events_by_locations as $key => $location  ) {
						if ( !empty( $location ) ) {
								$term_id        = $location['location_id'];
								$address        = get_term_meta( $term_id, 'address', true );
								$email          = get_term_meta( $term_id, 'location_email', true );
								$location_url   = $redirect_url . '?location=' . $term_id;
								$location_direction = '';

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
										?>
											<div class='etn-location-item etn-location-item-<?php esc_attr_e($index+1, 'eventin-pro'); ?>'>
													<div class="etn-location-item-image">
															<a href="<?php echo esc_url( get_the_permalink( $event['event_id'] ) ); ?>"><img src="<?php echo esc_url( esc_url( get_the_post_thumbnail_url( $event['event_id'] ) ) ); ?>" alt="<?php echo esc_html( $location['location_name'] ); ?>"></a>
													</div>
													<div class="etn-location-item-content">
															<h3 class="etn-location-item-name">
																	<a href="<?php echo esc_url( get_the_permalink( $event['event_id'] ) ); ?>" target="_blank">
																			<?php echo esc_html( $event['event_name'] ); ?>
																	</a>
															</h3>
															<p class="etn-location-item-address">
																	<svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M7.89994 10.1463C9.27879 10.1463 10.3966 9.02855 10.3966 7.6497C10.3966 6.27085 9.27879 5.15308 7.89994 5.15308C6.5211 5.15308 5.40332 6.27085 5.40332 7.6497C5.40332 9.02855 6.5211 10.1463 7.89994 10.1463Z" stroke="#5F6A78" stroke-width="1.5"/>
																	<path d="M1.19425 6.1933C2.77065 -0.736432 13.0372 -0.72843 14.6056 6.2013C15.5258 10.2663 12.9972 13.7072 10.7806 15.8357C9.17225 17.3881 6.62761 17.3881 5.01121 15.8357C2.80266 13.7072 0.274023 10.2583 1.19425 6.1933Z" stroke="#5F6A78" stroke-width="1.5"/>
																	</svg>
																	<?php echo esc_html( $address ); ?>
															</p>
															<p class="etn-location-item-email">
																	<svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M13.3529 15.5H5.11765C2.64706 15.5 1 14.2647 1 11.3824V5.61765C1 2.73529 2.64706 1.5 5.11765 1.5H13.3529C15.8235 1.5 17.4706 2.73529 17.4706 5.61765V11.3824C17.4706 14.2647 15.8235 15.5 13.3529 15.5Z" stroke="#5F6A78" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
																	<path d="M13.3535 6.0293L10.7758 8.08812C9.92757 8.76341 8.53581 8.76341 7.68757 8.08812L5.11816 6.0293" stroke="#5F6A78" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
																	</svg>
																	<?php echo esc_html__( 'Email: ', 'eventin-pro' ) . esc_html( $email ); ?>
															</p>
															<p class="etn-location-item-direction">
																	<?php echo esc_html( $location_direction ); ?>
															</p>
													</div>
											</div>
										<?php
									endforeach;

								endif;
						}
				}

				$locations_html_data = ob_get_clean();

		} else {
				$msg = esc_html__( 'No events found in this location', 'eventin-pro');
		}
		if ( !empty( $locations_html_data ) ) {
				$locations_html = "<div class='etn-location-item-wrapper'><h4 class='location-area-title'>";
				$locations_html .= esc_html__('Available Event Nearby:', 'eventin-pro');
				$locations_html .=  "</h4>";
				$locations_html .= $locations_html_data;
				$locations_html .= "</div>";
		}
?>
<div class="etn_loc_address_wrap">
    <div class="etn_loc_form">
        <input id="etn_loc_address" class="etn_loc_address" type="text" name="etn_loc_address" value="<?php echo esc_attr( $default_address ); ?>" placeholder="<?php echo esc_html__('Enter address here', 'eventin-pro'); ?>">
        <!-- search result -->
        <div class="near_location"></div>
        <a href="#" id="etn_loc_my_position" class="etn_loc_my_position">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M10 16.75C13.7279 16.75 16.75 13.7279 16.75 10C16.75 6.27208 13.7279 3.25 10 3.25C6.27208 3.25 3.25 6.27208 3.25 10C3.25 13.7279 6.27208 16.75 10 16.75Z" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M9.9998 12.7C11.491 12.7 12.6998 11.4912 12.6998 10C12.6998 8.50888 11.491 7.30005 9.9998 7.30005C8.50864 7.30005 7.2998 8.50888 7.2998 10C7.2998 11.4912 8.50864 12.7 9.9998 12.7Z" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M10 2.8V1" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M2.8 10H1" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M10 17.2V19" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M17.2002 10H19.0002" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>

            <?php echo esc_html__('Find me', 'eventin-pro'); ?>
        </a>
        <div class="etn_button_wrapper">
            <button aria-label="<?php echo esc_html__('Search location button', 'eventin-pro'); ?>" class="button button-success etn_loc_address_search">
								<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8.6 16.2C12.7974 16.2 16.2 12.7974 16.2 8.6C16.2 4.40264 12.7974 1 8.6 1C4.40264 1 1 4.40264 1 8.6C1 12.7974 4.40264 16.2 8.6 16.2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M17.0004 16.9999L15.4004 15.3999" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
            </button>
        </div>
    </div>
</div>

<div class="etn_map_and_result_wrapper etn_map_loading">
		<div class="etn-location-result"><?php echo $locations_html; ?></div>
		<div class="etn-front-map" data-lat="<?php echo esc_attr( $location_latitude ); ?>" data-long="<?php echo esc_attr( $location_longitude ); ?>" data-zoom="14" data-radius="25"  data-redirect_url="<?php echo esc_url( $redirect_url ); ?>">
			<div id="etn-front-map-container"></div>
		</div>
</div>

<div class="etn_loader_wrapper">
	<div class="loder-dot dot-a"></div>
	<div class="loder-dot dot-b"></div>
	<div class="loder-dot dot-c"></div>
	<div class="loder-dot dot-d"></div>
	<div class="loder-dot dot-e"></div>
	<div class="loder-dot dot-f"></div>
	<div class="loder-dot dot-g"></div>
	<div class="loder-dot dot-h"></div>
</div>
