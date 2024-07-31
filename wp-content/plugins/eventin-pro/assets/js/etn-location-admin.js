(function($) {
    "use strict";

    $(document).ready(function() {
        let map_parent    = ".etn-location-map";
        let map_container = "#etn-location-map-container";

        var map_settings = {
            map_parent        : map_parent,
            map_container     : map_container,
            default_lat       : $(map_parent).data('lat'),
            default_long      : $(map_parent).data('long'),
            default_zoom      : $(map_parent).data('zoom'),
            loc_lat_field     : $("#location_latitude"),
            loc_long_field    : $("#location_longitude"),
            loc_address_field : $("#address"),
        };

        // load map
        locationMapFunctionalities($, map_settings);

        // address position button click
        $('#etn-location-map-position').on('click', function(e){
            e.preventDefault();
            geocodeAddressCoordinates($, map_settings);
        });
    });

})(jQuery);

// all map initialization functionalities
function locationMapFunctionalities($, map_settings) {
    let container       = $(map_settings.map_container);
    let default_zoom    = parseInt(map_settings.default_zoom);
    let default_lat     = parseFloat(map_settings.default_lat);
    let default_long    = parseFloat(map_settings.default_long);

    if( !isNaN(default_lat) && !isNaN(default_long) ) {
        // map drawing
        let map_params = {
            zoom        : default_zoom,
            center      : new google.maps.LatLng(default_lat, default_long),
            scrollwheel : false,
        };
        let d_map = new google.maps.Map(container[0], map_params);
        map_settings.map    = d_map;

        // marker generate
        let marker_params = {
            map       : d_map,
            position  : new google.maps.LatLng(default_lat, default_long),
            draggable : true,
        };
        let d_marker = new google.maps.Marker(marker_params);
        d_marker.setMap(d_map);

        map_settings.marker = d_marker;

        // marker icon drag time update coordinate
        google.maps.event.addListener(d_marker, 'drag', function (e){
            updateCoordinate($, map_settings, e.latLng.lat(), e.latLng.lng());
        });
    }
}

// Update lat long of input field
function updateCoordinate($, map_settings, lat, lng) {
    map_settings.loc_lat_field.val(lat);
    map_settings.loc_long_field.val(lng);
};

// address to coordinate
function geocodeAddressCoordinates($, map_settings){
    let address = $(map_settings.loc_address_field).val();

    if(address) {
        let geocoder = new google.maps.Geocoder();

        geocoder.geocode({'address': address}, function (res, status) {
            if(status === google.maps.GeocoderStatus.OK) {
                let outLatLong = res[0].geometry.location;
                map_settings.map.setCenter(outLatLong);
                map_settings.marker.setPosition(outLatLong);

                updateCoordinate($, map_settings, outLatLong.lat(), outLatLong.lng());
            } else {
                alert('Google geocoder error');
            }
        } );
    } else {
        alert('Address is empty');
    }
};
