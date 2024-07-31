<?php
namespace EventinPro\Integrations\Google\Services;

use EventinPro\Integrations\GoogleClient;

/**
 * Service abstract class
 * 
 * @package EventinPro\Google
 */
abstract class Service {
    /**
     * Store client object
     *
     * @var GoogleClient
     */
    protected $token;

    /**
     * Initialize the class
     *
     * @param   GoogleClient  $client  Google Client Object
     *
     * @return  Void
     */
    public function __construct( $token ) {
        $this->token = $token;
    }
}
