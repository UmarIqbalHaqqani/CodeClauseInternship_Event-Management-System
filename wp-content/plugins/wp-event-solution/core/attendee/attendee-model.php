<?php
/**
 * Attendee Model Class
 *
 * @package Eventin
 */
namespace Etn\Core\Attendee;

use Etn\Base\Post_Model;

/**
 * Attendee Model
 */
class Attendee_Model extends Post_Model {
    protected $post_type = 'etn-attendee';

    /**
     * Store attendee data
     *
     * @var array
     */
    protected $data = [
        'etn_name'                    => '',
        'etn_email'                   => '',
        'etn_phone'                   => '',
        'etn_event_id'                => '',
        'etn_unique_ticket_id'        => '',
        'ticket_name'                 => '',
        'etn_attendeee_ticket_status' => '',
        'etn_ticket_price'            => '',
        'etn_status'                  => '',
        'etn_info_edit_token'         => '',
    ];
}
