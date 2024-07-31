<?php
    /**
     * External ticket purchase list.
     *
     * @package EventinPro
     */
    global $post;
    $tickets       = get_post_meta( $post->ID, "etn_ticket_variations", true );
    $price_decimal = class_exists( 'WooCommerce' ) ? esc_attr( wc_get_price_decimals() ) : '2';
    $currencey     = \Etn\Core\Event\Helper::instance()->get_currency();
    $avaiilable_tickets = get_post_meta( $post->ID, 'etn_total_avaiilable_tickets', true );
?>
<?php if ( $tickets ): ?>
<?php foreach ( $tickets as $key => $ticket ): ?>
    <div class="variation_<?php echo $key; ?>">
        <div class="etn-single-ticket-item">
            <h5 class="ticket-header">
                <?php echo $ticket['etn_ticket_name']; ?>
                <span class="seat-remaining-text">(<?php echo esc_html_e( 'Avaiable Tickets: ', 'eventin-pro' ) . $avaiilable_tickets ?>)</span>
            </h5>
            <div class="etn-ticket-divider"></div>
            <div class="etn-ticket-price-body ">
                <div class="ticket-price-item etn-ticket-price">
                    <label><?php echo esc_html_e( 'Ticket Price :', 'eventin-pro' ); ?></label>
                    <strong>
                        <?php
                            echo esc_html( $currencey );
                            echo number_format( (float) $ticket['etn_ticket_price'], $price_decimal, '.', '' );
                        ?>
                    </strong>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>
    <a 
        href="<?php echo get_permalink( $post->ID ); ?>"
        class="etn-btn etn-primary external-event"
        target="_blank"
    ><?php esc_html_e( 'Get your ticket', 'eventin-pro' ); ?>
    </a>
<?php endif;?>


