<?php

defined( 'ABSPATH' ) || exit;
?>
<?php if(get_post_meta( $single_event_id, "event_external_link", true )): ?>
    <div class="etn-event-meta-external-link etn-widget">
        <a target="_blank" rel="noopener" href="<?php echo esc_url( get_post_meta( $single_event_id, "event_external_link", true ) ); ?>" class="etn-btn">
                <?php 
                $event_external_link_btn_text = apply_filters( 'etn_event_attendee_list_buttn_text', esc_html__("More Info", "eventin-pro") );
                echo esc_html( $event_external_link_btn_text );?>
            </a>
    </div>
<?php endif; ?>