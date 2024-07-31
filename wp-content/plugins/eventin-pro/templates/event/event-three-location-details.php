<?php

defined('ABSPATH') || exit;

use Etn\Core\Event\Event_Model;
use Etn_Pro\Utils\Helper;

$event_options       = get_option("etn_event_options");
$data                = Helper::single_template_options($single_event_id);
$event_start_date    = isset($data['event_start_date']) ? $data['event_start_date'] : '';
$event_end_date      = isset($data['event_end_date']) ? $data['event_end_date'] : '';
$event_start_time    = isset($data['event_start_time']) ? $data['event_start_time'] : '';
$event_end_time      = isset($data['event_end_time']) ? $data['event_end_time'] : '';
$etn_deadline_value  = isset($data['etn_deadline_value']) ? $data['etn_deadline_value'] : '';
$etn_event_location  = isset($data['etn_event_location']) ? $data['etn_event_location'] : '';
$event_terms         = !empty(get_the_terms($single_event_id, 'etn_location')) ? get_the_terms($single_event_id, 'etn_location') : [];
$event_location_type = get_post_meta($single_event_id, 'etn_event_location_type', true);

$event 			  = new Event_Model( $single_event_id );
 
$is_show_location = ! isset( $event_options["etn_hide_location_from_details"] );
$location         = \Etn\Core\Event\Helper::instance()->display_event_location( $single_event_id );

?>
<ul class="list-unstyled etn-event-date-meta">
	<?php if(!isset($event_options["etn_hide_time_from_details"])) : ?>
        <li>
            <i class="etn-icon etn-calendar"></i>
			<?php
				if ( $event_start_date !==  $event_end_date ) {
					echo esc_html($event_start_date . Helper::showing_space( $event_end_date ) . $event_end_date);
				} else {
					echo esc_html( $event_start_date );
				}
			?>
        </li>
	<?php endif; ?>
	
	<?php if ( $is_show_location && !empty($location) ): ?>
		<li>
            <i class="etn-icon etn-location"></i>
			<?php echo esc_html( $location ); ?>
        </li>
	<?php endif; ?>
</ul>