<?php

//show woocommerce notice
if ( class_exists( 'WooCommerce' ) ) {

    if ( function_exists( 'wc_print_notices' ) ) {
        wc_print_notices();
    }

}

etn_after_single_event_meta_recurring_event_ticket_form( $single_event_ids );