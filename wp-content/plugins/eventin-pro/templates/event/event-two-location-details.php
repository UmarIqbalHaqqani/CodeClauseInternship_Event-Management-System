<?php

defined('ABSPATH') || exit;

use Etn\Core\Event\Event_Model;
use Etn_Pro\Utils\Helper;

$event_location_type = get_post_meta($single_event_id, 'etn_event_location_type', true);
$event_terms   = !empty(get_the_terms($single_event_id, 'etn_location')) ? get_the_terms($single_event_id, 'etn_location') : [];

$event 			  = new Event_Model( $single_event_id );
$is_show_location = ! isset( $event_options["etn_hide_location_from_details"] );
$location         = \Etn\Core\Event\Helper::instance()->display_event_location( $single_event_id );

?>
<ul class="list-unstyled etn-event-date-meta">
	<?php if(!isset($event_options["etn_hide_time_from_details"])) { ?>
        <li>
            <?php if ( $event_start_date !==  $event_end_date ): ?>
            <i class="etn-icon etn-calendar"></i> <?php echo esc_html($event_start_date . Helper::showing_space( $event_end_date ) . $event_end_date); ?>
            <?php else: ?>
            <i class="etn-icon etn-calendar"></i> <?php echo esc_html( $event_start_date ); ?>
            <?php endif; ?>
        </li>
	<?php } ?>
    
    <?php if ( $is_show_location && !empty($location) ): ?>
        <li>
            <i class="etn-icon etn-location"></i>
			<?php echo esc_html( $location ); ?>
        </li>
	<?php endif; ?>
</ul>