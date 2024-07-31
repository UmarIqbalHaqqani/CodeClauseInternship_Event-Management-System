<?php
/**
 * Essential license functions.
 *
 * @package License
 */

/**
 * Get license data.
 *
 * @return  array
 */
function theme_get_license() {
    $data = get_option( '_theme_license' );

    return $data;
}

/**
 * Check the license is valid or invalid
 *
 * @return  bool
 */
function theme_is_valid_license() {
    $data = theme_get_license();

    if ( ! empty( $data['license'] ) && 'valid' == $data['license'] ) {
        return true;
    }

    return false;
}

/**
 * Update license key
 *
 * @param   string  $license_key  Activation license key
 *
 * @return  void
 */
function theme_update_user( $args ) {
    $defaults = [
        'name'        => '',
        'email'       => '',
        'license_key' => '',
    ];
    $args = wp_parse_args( $args, $defaults );

    update_option( '_theme_license_user', $args );
}

/**
 * Get user details
 *
 * @return  array
 */
function theme_get_user_details() {
    return get_option( '_theme_license_user' );
}

/**
 * Get license user name
 *
 * @return  string
 */
function theme_get_name() {
    $data = theme_get_user_details();

    $name = ! empty( $data['name'] ) ? $data['name'] : '';

    return $name;
}

/**
 * Get license user email
 *
 * @return  email
 */
function theme_get_email() {
    $data = theme_get_user_details();

    $email = ! empty( $data['email'] ) ? $data['email'] : '';

    return $email;
}

/**
 * Get license key
 *
 * @return  string
 */
function theme_get_license_key() {
    $data = theme_get_user_details();

    $license_key = ! empty( $data['license_key'] ) ? $data['license_key'] : '';

    return $license_key;
}

/**
 * Delete theme user details
 *
 * @return  bool
 */
function theme_delete_user_details() {
    return delete_option( '_theme_license_user' );
}

/**
 * Delete theme license
 *
 * @return bool
 */
function theme_delete_license_details() {
    return delete_option( '_theme_license' );
}
