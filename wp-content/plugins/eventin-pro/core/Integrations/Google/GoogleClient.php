<?php
/**
 * Google client class
 *
 * @package EventinPro\Integrations
 */
namespace EventinPro\Integrations;

use EventinPro\Integrations\Google\Services\Calender;
use Exception;

/**
 * Google client class
 */
class GoogleClient {
    /**
     * Store access token
     *
     * @var string
     */
    protected $token;

    /**
     * Store services
     *
     * @var array
     */
    private $services = [];

    /**
     * Initialize the class
     *
     * @return  void
     */
    public function __construct( $token = '' ) {
        if ( ! $token ) {
            throw new Exception( __( 'You must provide google access token', 'eventin-pro' ) );
        }

        $this->token = $token;
    }

    /**
     * Get service object
     *
     * @param   string  $service
     *
     * @return  Object
     */
    public function __get( $service ) {
        if ( ! isset( $this->services[$service] ) ) {
            $this->services[$service] = $this->create_service( $service );
        }

        return $this->services[$service];
    }

    /**
     * Create services
     *
     * @return  Object
     */
    protected function create_service( $service_name ) {
        $services = $this->get_services();

        if ( ! isset( $services[$service_name] ) ) {
            throw new Exception( __( 'Unknow service', 'eventin-pro' ) );
        }

        return new $services[$service_name]( $this->token );
    }

    /**
     * Get services
     *
     * @return  array  define all services
     */
    protected function get_services() {
        return [
            'calender' => Calender::class,
        ];
    }
}
