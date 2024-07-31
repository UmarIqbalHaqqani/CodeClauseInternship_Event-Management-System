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
function etn_get_license() {
    $data = get_option( '_etn_license' );

    return $data;
}

/**
 * Check the license is valid or invalid
 *
 * @return  bool
 */
function etn_is_valid_license() {
    $data = etn_get_license();

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
function etn_update_user( $args ) {
    $defaults = [
        'name'        => '',
        'email'       => '',
        'license_key' => '',
    ];
    $args = wp_parse_args( $args, $defaults );

    update_option( '_etn_license_user', $args );
}

/**
 * Get user details
 *
 * @return  array
 */
function etn_get_user_details() {
    return get_option( '_etn_license_user' );
}

/**
 * Get license user name
 *
 * @return  string
 */
function etn_get_name() {
    $data = etn_get_user_details();

    $name = ! empty( $data['name'] ) ? $data['name'] : '';

    return $name;
}

/**
 * Get license user email
 *
 * @return  email
 */
function etn_get_email() {
    $data = etn_get_user_details();

    $email = ! empty( $data['email'] ) ? $data['email'] : '';

    return $email;
}

/**
 * Get license key
 *
 * @return  string
 */
function etn_get_license_key() {
    $data = etn_get_user_details();

    $license_key = ! empty( $data['license_key'] ) ? $data['license_key'] : '';

    return $license_key;
}

/**
 * Delete etn user details
 *
 * @return  bool
 */
function etn_delete_user_details() {
    return delete_option( '_etn_license_user' );
}

/**
 * Delete etn license
 *
 * @return bool
 */
function etn_delete_license_details() {
    return delete_option( '_etn_license' );
}
