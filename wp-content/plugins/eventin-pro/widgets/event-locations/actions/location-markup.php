<div class='etn-location-item etn-location-item-<?php esc_attr_e($index+1, 'eventin-pro'); ?>'>
    <div class="etn-location-item-image">
            <a href="<?php echo esc_url( get_the_permalink( $event['event_id'] ) ); ?>"><img src="<?php echo esc_url( esc_url( get_the_post_thumbnail_url( $event['event_id'] ) ) ); ?>" alt="<?php echo esc_html( $location['location_name'] ); ?>"></a>
    </div>
    <div class="etn-location-item-content">
            <h3 class="etn-location-item-name">
                    <a href="<?php echo esc_url( get_the_permalink( $event['event_id'] ) ); ?>" target="_blank">
                            <?php echo esc_html( $event['event_name'] ); ?>
                    </a>
            </h3>
            <p class="etn-location-item-address">
                    <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.89994 10.1463C9.27879 10.1463 10.3966 9.02855 10.3966 7.6497C10.3966 6.27085 9.27879 5.15308 7.89994 5.15308C6.5211 5.15308 5.40332 6.27085 5.40332 7.6497C5.40332 9.02855 6.5211 10.1463 7.89994 10.1463Z" stroke="#5F6A78" stroke-width="1.5"/>
                    <path d="M1.19425 6.1933C2.77065 -0.736432 13.0372 -0.72843 14.6056 6.2013C15.5258 10.2663 12.9972 13.7072 10.7806 15.8357C9.17225 17.3881 6.62761 17.3881 5.01121 15.8357C2.80266 13.7072 0.274023 10.2583 1.19425 6.1933Z" stroke="#5F6A78" stroke-width="1.5"/>
                    </svg>
                    <?php echo esc_html( $address ); ?>
            </p>
            <p class="etn-location-item-email">
                    <svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.3529 15.5H5.11765C2.64706 15.5 1 14.2647 1 11.3824V5.61765C1 2.73529 2.64706 1.5 5.11765 1.5H13.3529C15.8235 1.5 17.4706 2.73529 17.4706 5.61765V11.3824C17.4706 14.2647 15.8235 15.5 13.3529 15.5Z" stroke="#5F6A78" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.3535 6.0293L10.7758 8.08812C9.92757 8.76341 8.53581 8.76341 7.68757 8.08812L5.11816 6.0293" stroke="#5F6A78" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php echo esc_html__( 'Email: ', 'eventin-pro' ) . esc_html( $email ); ?>
            </p>
            <p class="etn-location-item-direction">
                    <?php echo esc_html( $location_direction ); ?>
            </p>
    </div>
</div>