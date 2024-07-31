<?php

defined( 'ABSPATH' ) || exit;

?>
<div class="etn-event-meta-info etn-widget">
    <ul>
        <?php 
        if (!isset($event_options["etn_hide_time_from_details"]) && ( !empty( $data['event_start_time'] ) || !empty( $data['event_end_time'] ) )) { ?>
            <li>
                <span><?php echo esc_html__('Time : ', 'eventin-pro'); ?></span>
                <?php echo esc_html($data['event_start_time'] . " - " . $data['event_end_time']); ?>
                <span class="etn-event-timezone">
                    <?php
                    if ( !empty( $data['event_timezone'] ) && !isset($event_options["etn_hide_timezone_from_details"]) ) {
                        ?>
                        (<?php echo esc_html( $data['event_timezone'] ); ?>)
                        <?php
                    }
                    ?>
                </span>
            </li>
            <?php 
        } 
        ?>

        <?php
        if(!empty($data['etn_deadline_value'])){ 
            ?>
           <li>
                <span><?php echo esc_html__('Registration Deadline : ', 'eventin-pro'); ?></span>
                <?php echo esc_html($data['etn_deadline_value']); ?>
            </li>
            <?php 
        } 
        ?>
        <?php 
        if (!isset($event_options["hide_social_from_details"]) && is_array($data['etn_event_socials']) && !empty( $data['etn_event_socials'] )) { ?>
            <li>
                <div class="etn-social">
                    <div class="share-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18">
							<path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
						</svg>
                    </div>
                    <?php foreach ($data['etn_event_socials'] as $social) : ?>
                        <?php $etn_social_class = 'etn-' . str_replace('fab fa-', '', $social['icon']); ?>
                        <a 
                            href="<?php echo esc_url($social['etn_social_url']); ?>" 
                            target="_blank" 
                            class="<?php echo esc_attr($etn_social_class); ?>"
                            aria-label="<?php echo esc_attr($social["etn_social_title"]); ?>"
                        > 
                            <i class="etn-icon <?php echo esc_attr($social['icon']); ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </li>
            <?php 
        } 
        ?>
    </ul>
</div>