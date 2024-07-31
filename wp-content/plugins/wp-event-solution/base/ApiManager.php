<?php
namespace Eventin;

use Eventin\Attendee\Api\AttendeeController;
use Eventin\Event\Api\EventController;
use Eventin\EventCategory\Api\EventCategoryController;
use Eventin\Event\Api\EventTagController;
use Eventin\Event\Api\TransactionController;
use Eventin\Location\Api\LocationController;
use Eventin\Schedule\Api\ScheduleController;
use Eventin\Settings\Api\SettingsController;
use Eventin\Speaker\Api\SpeakerCategoryController;
use Eventin\Speaker\Api\SpeakerController;
use Eventin\ZoomMeeting\Api\ZoomMeetingController;
use WP_REST_Controller;

/**
 * Api Manager class
 */
class ApiManager {
    /**
     * Store Controllers
     *
     * @var array
     */
    protected static $controllers = [
        EventController::class,
        SpeakerController::class,
        ScheduleController::class,
        LocationController::class,
        EventCategoryController::class,
        SettingsController::class,
        AttendeeController::class,
        SpeakerCategoryController::class,
        EventTagController::class,
        TransactionController::class,
    ];

    /**
     * Registers services with the container.
     *
     * @return void
     * @throws \ReflectionException
     */
    public static function register(): void {
        $controllers = apply_filters( 'eventin_api_controllers', self::$controllers );
        
        foreach ( $controllers as $controller ) {
            if ( ! class_exists( $controller ) ) {
                continue;
            }

            if ( is_subclass_of( $controller, WP_REST_Controller::class ) ) {
                $api_controller = Eventin::$container->get( $controller );
                $api_controller->register_routes();
            }
        }
    }

}
