<?php

defined( 'ABSPATH' ) || exit;

use Etn\Utils\Helper;

$location_latitude  = '37.4224428';
$location_longitude = '-122.0842467';
$default_address    = '';

global $wp;
$redirect_url = home_url( $wp->request );

$locations_html = esc_html__( 'No event location is added yet.', 'eventin-pro' );
$all_locations  = wp_get_post_terms($single_event_id, 'etn_location');
$latitudes = [];
$longitudes = [];
?>
<div class="etn-event-meta-info etn-widget">
<?php

if ( !empty( $all_locations ) ) {
    ?>
    
    <h4 class="etn-widget-title etn-title">
        <?php 
        $location_title = apply_filters( 'etn_event_location_title', esc_html__('Venue Info', 'eventin-pro') ); 
        echo esc_html( $location_title );
        ?>
    </h4>
    <?php
    
    foreach ( $all_locations as $key => $location ) {
        $location_name = $location->name;
        $term_id = $location->term_id;
        $address = get_term_meta( $term_id, 'address', true );
        $email = get_term_meta( $term_id, 'location_email', true );
        $location_latitude = get_term_meta( $term_id, 'location_latitude', true );
        $location_longitude = get_term_meta( $term_id, 'location_longitude', true );
        $latitudes[] = $location_latitude;
        $longitudes[] = $location_longitude;
        $location_url = $redirect_url . '?location=' . $term_id;
        $location_direction = '';

        $location_latitude  = get_term_meta( $term_id, 'location_latitude', true );
        $location_longitude = get_term_meta( $term_id, 'location_longitude', true );

            $image_id  = get_term_meta( $term_id, 'location_image', true );
            $loc_image = \Wpeventin_Pro::assets_url() . 'images/placeholder.png';
            if ( ! empty( $image_id ) ) {
                $loc_image = wp_get_attachment_image_src( $image_id, 'thumbnail' );
                if ( is_array( $loc_image ) ) {
                        $loc_image = $loc_image[0];
                }
            }
            ob_start();
        ?>
        <div class='etn-single-location-item etn-location-item-<?php echo $term_id+1; ?>'>
                <div class="etn-single-location-item-content">
                        <p class="etn-location-item-address">
                                <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.89994 10.1463C9.27879 10.1463 10.3966 9.02855 10.3966 7.6497C10.3966 6.27085 9.27879 5.15308 7.89994 5.15308C6.5211 5.15308 5.40332 6.27085 5.40332 7.6497C5.40332 9.02855 6.5211 10.1463 7.89994 10.1463Z" stroke="#5F6A78" stroke-width="1.5"/>
                                <path d="M1.19425 6.1933C2.77065 -0.736432 13.0372 -0.72843 14.6056 6.2013C15.5258 10.2663 12.9972 13.7072 10.7806 15.8357C9.17225 17.3881 6.62761 17.3881 5.01121 15.8357C2.80266 13.7072 0.274023 10.2583 1.19425 6.1933Z" stroke="#5F6A78" stroke-width="1.5"/>
                                </svg>
                                <span class="etn-location-text"><?php echo esc_html( $address ); ?></span>
                        </p>
                        <a 
                                href="http://maps.google.com/maps?q=<?php echo esc_attr($location_latitude); ?>,<?php echo esc_attr($location_longitude); ?>"
                                class="etn-btn etn-primary view-map-button" 
                                target="_blank"
                        >
                                <svg width="14" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"  fill="#fff"><path d="M497.1 222.1l-208.1-208.1c-9.364-9.364-21.62-14.04-33.89-14.03C243.7 .0092 231.5 4.686 222.1 14.03L14.03 222.1C4.676 231.5 .0002 243.7 .0004 255.1c.0002 12.26 4.676 24.52 14.03 33.87l208.1 208.1C231.5 507.3 243.7 511.1 256 511.1c12.26 0 24.52-4.677 33.87-14.03l208.1-208.1c9.352-9.353 14.03-21.61 14.03-33.87C511.1 243.7 507.3 231.5 497.1 222.1zM410.5 252l-96 84c-10.79 9.545-26.53 .9824-26.53-12.03V272H223.1l-.0001 48C223.1 337.6 209.6 352 191.1 352S159.1 337.6 159.1 320V240c0-17.6 14.4-32 32-32h95.1V156c0-13.85 16.39-20.99 26.53-12.03l96 84C414 231 415.1 235.4 415.1 240S414 249 410.5 252z"/></svg>        
                        </a>
                        
                </div>
        </div>
        <?php
        $locations_html_data[ $term_id ] = ob_get_clean();
    } 
} else {
        $msg = esc_html__( 'No store found in this location', 'eventin-pro');
}

if ( !empty( $locations_html_data ) ) {
        $locations_html = "<div class='etn-location-item-wrapper'>";
        $locations_html .= join( '', $locations_html_data );
        $locations_html .= "</div>";
}

        $latitudes = implode(',', $latitudes);
        $longitudes = implode(',', $longitudes);

        if ( !empty( $all_locations ) ) {
        ?>

                <div class="etn_single_event_map_and_result_wrapper etn_map_at_sidebar">
                        <div class="etn-location-result"><?php echo Helper::render($locations_html); ?></div>
                        <div 
                                class="etn-front-map" 
                                data-lat="<?php echo esc_attr( $latitudes ); ?>" 
                                data-long="<?php echo esc_attr( $longitudes ); ?>" 
                                data-zoom="16" data-radius="25" 
                                data-redirect_url="<?php echo esc_url( $redirect_url ); ?>"
                        >
                                <div id="etn-front-map-container"></div>
                        </div>
                </div>
        <?php } ?>
</div>
