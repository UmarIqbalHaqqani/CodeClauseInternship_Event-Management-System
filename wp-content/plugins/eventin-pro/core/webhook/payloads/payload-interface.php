<?php
/**
 * Payload Interface
 * 
 * @package Eventin
 */
namespace Etn_Pro\Core\Webhook\Payloads;

/**
 * Payload Interface
 */
interface PayloadInterface {
    /**
     * Get payload
     *
     * @return  array
     */
    public function get_data( $args );
}
