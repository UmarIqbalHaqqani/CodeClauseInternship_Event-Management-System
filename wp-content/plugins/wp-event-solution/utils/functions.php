<?php

use Eventin\Settings;
use Eventin\Validation\Validator;

if ( ! function_exists( 'etn_array_csv_column' ) ) {
    /**
     * Convert array to CSV column
     *
     * @param array $data
     *
     * @return string
     */
    function etn_array_csv_column( $data = [] ) {
        $result_string = '';

        foreach ( $data as $data_key => $value ) {
            if ( ! is_array( $value ) ) {
                return etn_is_associative_array( $data ) ? etn_single_array_csv_column( $data ) : implode( ',', $data );
            }

            if ( etn_is_associative_array( $value ) ) {
                $valueString = etn_single_array_csv_column( $value );
                $result_string .= rtrim( $valueString, ', ' ) . '|';
            } else {
                $result_string .= implode( ',', $value ) . '|';
            }
        }

        // Remove the trailing '|'
        $result_string = rtrim( $result_string, '|' );

        return $result_string;
    }
}

if ( ! function_exists( 'etn_is_associative_array' ) ) {
    /**
     * Check an associative array or not
     *
     * @param array $array
     *
     * @return bool
     */
    function etn_is_associative_array( $array ) {
        return is_array( $array ) && count( array_filter( array_keys( $array ), 'is_string' ) ) > 0;
    }
}

if ( ! function_exists( 'etn_single_array_csv_column' ) ) {
    /**
     * Convert single array to csv column
     *
     * @param array $data
     *
     * @return string
     */
    function etn_single_array_csv_column( $data ) {
        if ( ! is_array( $data ) ) {
            return false;
        }

        $result_string = '';

        foreach ( $data as $key => $value ) {
            if ( is_array( $value ) ) {
                $result_string .= implode( ',', $value );
            } else {
                $result_string .= "$key:$value,";
            }
        }

        return rtrim( $result_string, ',' );
    }
}

if ( ! function_exists( 'etn_csv_column_array' ) ) {
    /**
     * Convert CSV column to array
     *
     * @param string $csvColumn
     *
     * @return array|bool
     */
    function etn_csv_column_array( $csv_column, $separator = '|' ) {
        // Explode the CSV column by '|' to get individual array elements
        if ( strpos( $csv_column, $separator ) !== false ) {
            return etn_csv_column_multi_dimension_array( $csv_column );
        }

        return etn_csv_column_single_array( $csv_column );
    }
}

if ( ! function_exists( 'etn_csv_column_multi_dimension_array' ) ) {
    /**
     * Convert CSV column to multi dimensional array
     *
     * @param   string  $csv_column
     * @param   string  $separator
     *
     * @return  array
     */
    function etn_csv_column_multi_dimension_array( $csv_column, $separator = '|' ) {
        $array_strings = explode( $separator, $csv_column );
        $result_array  = [];

        foreach ( $array_strings as $array_string ) {
            // Add the temporary array to the result array
            $result_array[] = etn_csv_column_single_array( $array_string );
        }

        return $result_array;
    }
}

if ( ! function_exists( 'etn_csv_column_single_array' ) ) {
    /**
     * Convert CSV column to multi dimensional array
     *
     * @param   string  $csv_column
     * @param   string  $separator
     *
     * @return  array
     */
    function etn_csv_column_single_array( $csv_column, $separator = ',' ) {
        $temp_array = [];

        if ( false !== strpos( $csv_column, ':' ) ) {
            $csv_column = explode( $separator, $csv_column );

            foreach ( $csv_column as $pair ) {
                // Explode key-value pairs by ':' and populate the temporary array
                list( $key, $value ) = explode( ':', $pair );
                $temp_array[$key]  = $value;
            }

            return $temp_array;
        }

        return explode( $separator, $csv_column );
    }
}

if ( ! function_exists( 'etn_is_request' ) ) {
    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    function etn_is_request( $type ) {
        switch ( $type ) {
        case 'admin':
            return is_admin();

        case 'ajax':
            return defined( 'DOING_AJAX' );

        case 'rest':
            return defined( 'REST_REQUEST' );

        case 'cron':
            return defined( 'DOING_CRON' );

        case 'frontend':
            return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }
}

if ( ! function_exists( 'etn_get_locale_data' ) ) {
    /**
     * Get locale data
     *
     * @return  array
     */
    function etn_get_locale_data() {
        $localize_vars   = include Wpeventin::plugin_dir() . 'utils/locale/vars.php';
        $localize_static = include Wpeventin::plugin_dir() . 'utils/locale/static.php';

        $data = array_merge( $localize_static, $localize_vars );

        return apply_filters( 'etn_locale_data', $data );
    }
}

if ( ! function_exists( 'etn_permision_error' ) ) {
    /**
     * Rest api error message
     *
     * @param   string  $message
     *
     * @return  \WP_REST_Response
     */
    function etn_permision_error( $message = '' ) {
        if ( ! $message ) {
            $message = __( 'Sorry, you are not allowed to do that.', 'eventin' );
        }

        $data = [
            'code'    => 'rest_forbidden',
            'message' => 'Sorry, you are not allowed to do that.',
            'data'    => [
                'status' => 403,
            ],
        ];

        return new WP_REST_Response( $data, 401 );
    }
}

if ( ! function_exists( 'etn_parse_block_content' ) ) {
    /**
     * Parses dynamic blocks out of `post_content` and re-renders them.
     *
     * @param   string  $content 
     *
     * @return  string
     */
    function etn_parse_block_content( $content ) {
        return do_blocks( $content );
    }
}

if ( ! function_exists( 'etn_validate' ) ) {
    /**
     * Validate user input
     *
     * @param   array  $request
     * @param   array  $rules
     *
     * @return  bool | WP_Error
     */
    function etn_validate( $request, $rules ) {
        $validator = new Validator( $request );

        $validator->set_rules( $rules );

        if ( ! $validator->validate() ) {
            return $validator->get_error();
        }

        return true;
    }
}

if ( ! function_exists( 'etn_get_option' ) ) {
    /**
     * Get option for eventin
     *
     * @since 1.0.0
     * @return  mixed
     */
    function etn_get_option( $key = '', $default = false ) {
        return Settings::get( $key );
    }
}

if ( ! function_exists( 'etn_update_option' ) ) {

    /**
     * Update option
     *
     * @param   string  $key
     *
     * @since 1.0.0
     *
     * @return  boolean
     */
    function etn_update_option( $key = '', $value = false ) {
        if ( ! $key ) {
            return false;
        }

        return Settings::update( [
            $key => $value,
        ] );
    }  
}

if ( ! function_exists( 'etn_is_ticket_sale_end' ) ) {
    /**
     * Check an event has attendees or not
     *
     * @param   string  $end_date_time  Event ticket sale end date and time
     * @param   string  $timezone       Event timezone
     *
     * @return  bool
     */
    function etn_is_ticket_sale_end( $end_date_time, $timezone = 'Asia/Dhaka' ) {
        // Create a DateTime object for the end date and time in the given timezone
        $event_end_dt = new DateTime( $end_date_time, new DateTimeZone( $timezone ) );
    
        // Create a DateTime object for the current date and time in the given timezone
        $current_dt = new DateTime( 'now', new DateTimeZone( $timezone ) );
    
        // Compare the dates
        if ( $current_dt > $event_end_dt ) {
            return true;
        }

        return false;
    }
}

if ( ! function_exists( 'etn_is_ticket_sale_start' ) ) {
    /**
     * Check an event has attendees or not
     *
     * @param   string  $start_date_time  Event ticket sale start date and time
     * @param   string  $timezone         Event timezone
     *
     * @return  bool 
     */
    function etn_is_ticket_sale_start( $start_date_time, $timezone = 'Asia/Dhaka' ) {
        // Create a DateTime object for the start date and time in the given timezone
        $event_date = new DateTime( $start_date_time, new DateTimeZone( $timezone ) );
    
        // Create a DateTime object for the current date and time in the given timezone
        $current_datte = new DateTime('now', new DateTimeZone( $timezone ) );
    
        // Compare the dates
        if ( $current_datte < $event_date ) {
            return false;
        } 

        return true;
    }
}


if ( ! function_exists( 'etn_create_date_timezone' ) ) {
    /**
     * Create datetimezone object
     *
     * @param   string  $timezoneString  Timezone
     *
     * @return  string
     */
    function etn_create_date_timezone( $timezoneString ) {
         // List of valid named timezones
        $validTimezones = DateTimeZone::listIdentifiers();

        // Check if the provided timezone is a valid named timezone
        if ( in_array( $timezoneString, $validTimezones ) ) {
            return $timezoneString;
        }

        // Check if the provided timezone is an offset timezone like UTC+6 or UTC-4.5
        if ( preg_match('/^UTC([+-]\d{1,2})(?:\.(\d))?$/i', $timezoneString, $matches ) ) {
            // Convert the matched offset to a format recognized by DateTimeZone
            $hours = intval( $matches[1] );
            $minutes = isset( $matches[2] ) ? intval($matches[2]) * 6 : 0; // 0.1 fractional part means 6 minutes

            // Ensure the format is like +06:30 or -04:30
            $formattedOffset = sprintf( '%+03d:%02d', $hours, $minutes );
            return $formattedOffset;
        }

        // If the timezone string doesn't match any known format, throw an exception
        throw new Exception('Unknown or bad timezone: ' . $timezoneString);
    }
}

if ( ! function_exists( 'etn_convert_to_date' ) ) {
    /**
     * Convert to date from date time string
     *
     * @param   string  $datetimeString  Datetime string
     *
     * @return  string  Date string
     */
    function etn_convert_to_date( $datetimeString ) {
        try {
            // Create a DateTime object using the provided datetime string
            $datetime = new DateTime( $datetimeString );
            
            // Return the formatted date in 'Y-m-d' format
            return $datetime->format( 'Y-m-d' );
        } catch ( Exception $e ) {
            return 'Error: ' . $e->getMessage();
        }
    }
}

if ( ! function_exists( 'etn_get_currency' ) ) {
    /**
     * Get currency list
     *
     * @return  array
     */
    function etn_get_currency () {
        $currencies = require Wpeventin::plugin_dir() . '/utils/currency.php';

        return $currencies;
    }
}

if ( ! function_exists( 'etn_event_url_editable' ) ) {
    /**
     * Check editable url
     *
     * @return  bool
     */
    function etn_event_url_editable () {
        $permalink_structure = get_option('permalink_structure');

        if ( strpos( $permalink_structure, '%postname%' ) !== false) {
            return true;
        }

        return false;
    }
}

if ( ! function_exists( 'etn_get_timezone' ) ) {
    /**
     * Get valid timezonelists
     *
     * @return  array Timezone lists
     */
    function etn_get_timezone() {
        $validTimezones = DateTimeZone::listIdentifiers();

        return $validTimezones;
    }
}

if ( ! function_exists( 'etn_prepare_address' ) ) {
    /**
     * Prepare event address from event location
     * 
     * This function is written for temporary solution. We have a nested location issue @since 4.0.0. To resolve this we impletent a temporary function. We have to remove this function when v4.0 is completely statble from location issue. Before remove this function make sure remove from all of the place where we used this. 
     *
     * @param   array  $location  
     *
     * @return  string
     */
    function etn_prepare_address( $location ) {
        static $depth = 0;
    
        if ( $depth >= 10 ) {
            return '';
        }
    
        $depth++;
    
        if ( ! is_array( $location ) ) {
            return $location;
        }
    
        $address = ! empty( $location['address'] ) ? $location['address'] : '';
    
        return etn_prepare_address( $address );
    }
}
