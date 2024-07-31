<?php
    wp_head();
    
    $ticket_file_name   = sanitize_title_with_dashes( $attendee_name );
    $payment_status     = get_post_meta( $attendee_id, 'etn_status', true );

    $all_payment_status = [
        'success' => esc_html__('Success', 'eventin-pro'),
        'failed'  => esc_html__('Failed', 'eventin-pro')
    ];

    // Load ticket layout style
    wp_enqueue_style('etn-ticket-markup');
    wp_enqueue_script('etn-pdf-gen');
    wp_enqueue_script('etn-html-2-canvas');
    wp_enqueue_script('etn-dom-purify-pdf');
    wp_enqueue_script('html-to-image');
    
    // Include QR Code related scripts when pro plugin is activated
    if(class_exists('Wpeventin_Pro')) {
        wp_enqueue_script('etn-qr-code');
        wp_enqueue_script('etn-qr-code-scanner');
        wp_enqueue_script('etn-qr-code-custom');
    }

?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<div class="etn-ticket-download-wrapper">
    <div class="etn-ticket-wrap" id="etn_attendee_details_to_print" >
      <div class="etn-ticket-wrapper ticket-style-2">
            <div class="etn-ticket-main-wrapper">
                <div class="ticket-left-side">
                <?php  if(has_custom_logo()){ ?>
                          <div class="etn-ticket-logo-wrapper">
                             <?php 
                                $custom_logo_id = get_theme_mod( 'custom_logo' );
                                $image = wp_get_attachment_image_src( $custom_logo_id, 'Full' );
                            ?>
                             <img style="max-width: 150px; max-height: 70px; object-fit: cover"  src="<?php echo esc_url($image[0]); ?>" />

                            <div class="logo-shape">
                                <span class="logo-bar bar-one" ></span>
                                <span class="logo-bar bar-two" ></span>
                                <span class="logo-bar bar-three" ></span>
                            </div>
                        </div>
                    <?php    
                        }elseif( class_exists( 'ET_Builder_Element' ) ){ 
                    ?>
                        <div class="etn-ticket-logo-wrapper">
                             <?php 
                                $image = et_get_option( 'divi_logo');
                            ?>
                             <img style="max-height: 70px; object-fit: cover"  src="<?php echo esc_url($image); ?>" />

                            <div class="logo-shape">
                                <span class="logo-bar bar-one" ></span>
                                <span class="logo-bar bar-two" ></span>
                                <span class="logo-bar bar-three" ></span>
                            </div>
                        </div>
                    <?php             
                        } 
                    ?>

                    <div class="etn-ticket-qr-code">
                        <?php
                            if( $payment_status ==='success'){
                                do_action('etn_pro_ticket_qr', $attendee_id, $event_id);
                            }
                        ?>
                    </div>
                </div>
                <div class="etn-ticket-content"> 
                    <div class="etn-ticket-head">
                        <h3 class="etn-ticket-head-title"><?php echo esc_html( $event_name ) ?></h3>
                        <ul class="etn-ticket-body-top-ul">
                            <?php if ( $date !== "") : ?>
                                <li class="etn-ticket-body-top-li">
                                    <p><?php echo esc_html__( "Date :", "eventin-pro" ); ?> </p>
                                    <?php echo esc_html( $date ) ?>  
                                </li>
                            <?php endif; ?>

                            <?php if ( $time !== "") : ?>
                                <li class="etn-ticket-body-top-li">
                                    <p><?php echo esc_html__( "Time :", "eventin-pro" ); ?> </p>
                                    <?php echo esc_html( $time ) ?>  
                                </li>
                            <?php endif; ?>
                            <?php $location = \Etn\Core\Event\Helper::instance()->display_event_location($event_id); ?>
                            <?php if ( !empty($location)) { ?>
                                <li class="etn-ticket-body-top-li">
                                    <p><?php echo esc_html__( "VENUE :", "eventin-pro" ); ?></p> 
                                    <p><?php echo esc_html( $location ) ?></p>
                                </li>
                            <?php }?>
                           
                        </ul>
                    </div>
                    <div class="etn-ticket-body">
                        <div class="etn-ticket-body-top">
                            <div class="etn-ticket-body-top-ul-wrapper">
                                <ul class="etn-ticket-body-top-ul">
                                    <?php do_action('etn_pro_ticket_id', $attendee_id, $event_id); ?>
                                    <?php if ( $ticket_price !== "") { ?>
                                        <li class="etn-ticket-body-top-li">
                                        <p><?php echo esc_html__( "PRICE :", "eventin-pro" ); ?> </p>
                                            
                                                <?php 
                                                    if ( class_exists('WooCommerce') ) {
                                                        $ticket_price = is_float( $ticket_price ) ? wc_format_decimal( $ticket_price, wc_get_price_decimals() ) : $ticket_price;

                                                        $currency_symbol = get_woocommerce_currency_symbol();
                                                        $currency_pos 	 = get_option( 'woocommerce_currency_pos' );
                                                        if ( $currency_pos == 'left_space' ) {
                                                            $currency_symbol = $currency_symbol . ' ';
                                                        } elseif ( $currency_pos == 'right_space' ) {
                                                            $currency_symbol = ' ' . $currency_symbol;
                                                        }

                                                        $print_left = ( strpos( $currency_pos, 'left' ) !== false ) ? true : false;
                                                        echo ( $print_left ) ? $currency_symbol . esc_html( $ticket_price ):  esc_html( $ticket_price ) . $currency_symbol;
                                                    } else {
                                                        $ticket_price = is_float( $ticket_price ) ? number_format( $ticket_price, 2 ) : $ticket_price;
                                                    }
                                                ?>
                                            
                                        </li>
                                    <?php }?>
                                    <?php if ( $ticket_name !== "") { ?>
                                        <li class="etn-ticket-body-top-li flex-100">
                                            <p><?php echo esc_html__( "TYPE :", "eventin-pro" ); ?> </p>
                                            <?php echo esc_html( $ticket_name ) ?>
                                        </li>
                                    <?php }?>
                                    <?php if ( $attendee_seat !== "") { ?>
                                        <li class="etn-ticket-body-top-li flex-100">
											<?php echo esc_html__( "Seat :", "eventin-pro" ); ?> 
                                            <p><?php echo esc_html( $attendee_seat ) ?></p>
                                        </li>
                                    <?php }?>

                                 
                                    <?php if ( $attendee_name !== "") { ?>
                                        <li class="etn-ticket-body-top-li">
                                            <p><?php echo esc_html__( "ATTENDEE :", "eventin-pro" ); ?> </p>
                                            <?php echo esc_html( $attendee_name ) ?>
                                        </li>
                                    <?php }?>
                                    
                                    <?php if ( $include_phone  && $attendee_phone !== "") { ?>
                                        <li class="etn-ticket-body-top-li">
                                            <p><?php echo esc_html__( "PHONE :", "eventin-pro" ); ?> </p>
                                            <?php echo esc_html( $attendee_phone ) ?>
                                        </li>
                                    <?php }?>
                                    <?php if ( $include_email && $attendee_email !== "" ) { ?>
                                        <li class="etn-ticket-body-top-li">
                                            <p><?php echo esc_html__( "EMAIL :", "eventin-pro" ); ?> </p>
                                            <?php echo esc_html( $attendee_email ) ?>
                                        </li>
                                    <?php }?>

                                    <?php if($payment_status){ ?>
                                        <li class="etn-ticket-body-top-li">
                                            <p><?php echo esc_html__( "Payment Status :", "eventin-pro" ); ?> </p>
                                            <?php echo esc_html( $all_payment_status[$payment_status] ); ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    </div>
</div>
<div class="etn-download-ticket">
    <button class="etn-btn button etn-print-ticket-btn" id="etn_ticket_print_btn" onclick="etn_ticket_content_area('etn-ticket-download-wrapper');" data-ticketname="<?php echo esc_html( $ticket_file_name )?>" ><?php echo esc_html__( "Print", "eventin-pro" ); ?></button>
    <button class="etn-btn button etn-download-ticket-btn" id="etn_ticket_download_btn" data-ticketname="<?php echo esc_html( $ticket_file_name )?>" ><?php echo esc_html__( "Download", "eventin-pro" ); ?></button>
</div>

<?php wp_footer(); ?>

