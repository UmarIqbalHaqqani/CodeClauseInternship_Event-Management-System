<?php

$settings                         = \Etn\Core\Settings\Settings::instance()->get_settings_option();
$data = [
    'ajax_url'                    => admin_url( 'admin-ajax.php' ),
    'site_url'                    => site_url(),
    'admin_url'                   => admin_url(),
    'assets_url'                  => \Wpeventin::plugin_url("assets"),
    'evnetin_pro_active'          => ( class_exists( 'Wpeventin_Pro' ) ) ? true : false,
    'locale_name'                 => strtolower( str_replace( '_', '-', get_locale() ) ),
    'start_of_week'               => get_option( 'start_of_week' ),
    'author_id'                   => get_current_user_id(),
    'ticket_scanner_link'         => admin_url( '/edit.php?post_type=etn-attendee' ),
    'post_id'                     => get_the_ID(),
    'zoom_connection_check_nonce' => wp_create_nonce( 'zoom_connection_check_nonce' ),
    'ticket_status_nonce'         => wp_create_nonce( 'ticket_status_nonce_value' ),
    'zoom_module'                 => empty( $settings['etn_zoom_api'] ) ? 'no' : 'yes',
    'attendee_module'             => empty( $settings['attendee_registration'] ) ? 'no' : 'yes',
    'currency_list'               => etn_get_currency(),
    'timezone_list'               => etn_get_timezone(),
    'version'                     => \Wpeventin::version(),
    'payment_option_woo'          => !empty($settings['sell_tickets']) ? $settings['sell_tickets'] :'',
    'payment_option_stripe'       => !empty($settings['etn_sells_engine_stripe']) ? $settings['etn_sells_engine_stripe'] :'',
    'currency_symbol'             => \Etn\Core\Event\Helper::instance()->get_currency(),
];

return apply_filters( 'etn_locale_vars', $data );
