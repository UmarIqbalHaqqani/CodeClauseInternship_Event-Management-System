<?php

use Etn\Core\Event\Event_Model;
defined( 'ABSPATH' ) || exit;

wp_enqueue_style('jquery-countdown');
wp_enqueue_script('jquery-countdown');

$event_id = get_the_ID();

$event = new Event_Model( $event_id );
$timezone = $event->event_timezone;

$timezone = $timezone ? etn_create_date_timezone( $timezone ) : 'America/New_York';
$timezone = new DateTimeZone( $timezone );

$formatted_start_date = $event->etn_start_date ? (new DateTime($event->etn_start_date, $timezone))->format('Y-m-d') : '';
$formatted_end_date = $event->etn_end_date ? (new DateTime($event->etn_end_date, $timezone))->format('Y-m-d') : '';

$start_date_time = strtotime( $formatted_start_date . ' ' . $event->etn_start_time );
$end_date_time   = strtotime( $formatted_end_date . ' ' . $event->etn_end_time );

?>

<div class="count_down_block">
    <?php if ( time() > $start_date_time && time() < $end_date_time ) :?>
        <p class="etn-countdown-expired"><?php esc_html_e( 'This event is going on', 'eventin-pro' ); ?></p>
    <?php elseif ( time() > $end_date_time ): ?>
        <p class="etn-countdown-expired"><?php esc_html_e( 'This event has been expaired', 'eventin-pro' ); ?></p>
    <?php else: ?>
        <div class="etn-event-countdown-wrap etn-countdown1 etn-countdown-parent" 
            data-start-date="<?php echo esc_attr( $counter_start_time ); ?>"
            data-date-texts='<?php echo json_encode( $date_texts );?>'>
            <div class="etn-count-item etn-days">
                <span class="day-count days"></span>
                <span class="text days_text">  <?php echo esc_html($countdown_day); ?></span>
            </div>
            <?php if ( $show_seperate_dot ){ ?>
            <span class="date-seperate"> : </span>
            <?php } ?>
            <div class="etn-count-item etn-hours">
                <span class="hr-count hours"></span>
                <span class="text hours_text"><?php echo esc_html($countdown_hr); ?></span>
            </div>
            <?php if ( $show_seperate_dot ){ ?>
            <span class="date-seperate"> : </span>
            <?php } ?>
            <div class="etn-count-item etn-minutes">
                <span class="min-count minutes"></span>
                <span class="text minutes_text"> <?php echo esc_html($countdown_min); ?></span>
            </div>
            <?php if ( $show_seperate_dot ){ ?>
            <span class="date-seperate"> : </span>
            <?php } ?>
            <div class="etn-count-item etn-seconds">
                <span class="sec-count seconds"></span>
                <span class="text seconds_text"> <?php echo esc_html($countdown_sec); ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>