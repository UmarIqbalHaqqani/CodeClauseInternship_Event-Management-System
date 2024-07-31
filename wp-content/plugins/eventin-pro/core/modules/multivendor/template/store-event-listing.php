<?php

use Etn_Pro\Utils\Helper;

$event_options  = get_option("etn_event_options");
$date_options   = Helper::get_date_formats();
$desc_limit     = 15;
$btn_text       = esc_html__('Purchase', 'eventin-pro');
?>
<div class="etn-multivendor-event-list-wrap">
    <div class="etn-vendor-event-list-title">
        <?php echo esc_html__('Events', 'eventin-pro'); ?>
    </div>
    
    <div class="etn-vendor-event-list">
        <div class='etn-row etn-event-wrapper'>
            <?php 
            foreach($vendor_events as $event){
                
                $social             = get_post_meta( $event->ID, 'etn_event_socials', true);
                $event_location     = \Etn\Core\Event\Helper::instance()->display_event_location($event->ID);
                $start_date         = get_post_meta( $event->ID, 'etn_start_date', true);
                $event_start_date   = isset( $event_options["date_format"] ) && $event_options["date_format"] !=='' ? date_i18n( $date_options[$event_options["date_format"]], strtotime( $start_date ) ) : date_i18n( get_option( 'date_format' ), strtotime( $start_date ) );
                ?>
                <div class="etn-col-md-6 etn-col-lg-6">
                    <div class="etn-event-item">
                        <?php 
                        if ( esc_url( get_the_post_thumbnail_url( $event->ID ) ) ) { 
                            ?>
                            <div class="etn-event-thumb">
                                <a href="<?php echo esc_url( get_the_permalink( $event->ID ) ); ?>" aria-label="<?php echo esc_html( get_the_title( $event->ID ) ); ?>">
                                    <img src="<?php echo esc_url( esc_url( get_the_post_thumbnail_url( $event->ID ) ) ); ?>" alt="<?php the_title_attribute($event->ID); ?>">
                                </a>
                            </div>
                            <?php 
                        } 
                        ?>
                        <!-- thumbnail start-->
                        <!-- content start-->
                        <div class="etn-event-content">
                            <div class="etn-event-date">
                                <i class="etn-icon etn-calendar"></i>
                                <?php echo esc_html( $event_start_date ); ?>
                            </div>

                            <h3 class="etn-title etn-event-title">
                                <a href="<?php echo esc_url( get_the_permalink( $event->ID ) ); ?>"> <?php echo esc_html( get_the_title( $event->ID ) ); ?></a> 
                            </h3>
                            
                            <div class="etn-event-location">
                                <i class="etn-icon etn-location"></i> 
                                <?php echo esc_html( $event_location ); ?>
                            </div>
                            
                            <p>
                                <?php echo esc_html( Helper::trim_words( get_the_excerpt($event->ID), $desc_limit) ); ?>
                            </p>
                            <div class="etn-event-footer">
                                <div class="etn-event-attendee-count">
                                    <i class="etn-icon etn-user-plus"></i>
                                    <?php echo esc_html( \Etn_Pro\Core\Action::instance()->total_attendee( $event->ID ) ); ?>
                                    <?php echo esc_html__( 'People Joined', 'eventin-pro' ); ?>
                                </div>
                                <div class="etn-atend-btn">
                                    <a href="<?php echo esc_url( get_the_permalink( $event->ID ) ); ?>" class="etn-btn etn-btn-border"><?php echo esc_html( $btn_text ); ?>
                                     <i class="etn-icon etn-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- content end-->
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
 