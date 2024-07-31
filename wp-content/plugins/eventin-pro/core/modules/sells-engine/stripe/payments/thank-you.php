<?php
if( wp_is_block_theme() ){
    block_header_area();
    wp_head();
}else{
    get_header();
}

$order_id       = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
$invoice        = isset( $_GET['invoice'] ) ? sanitize_text_field( $_GET['invoice'] ) : '';
$event_id       = isset( $_GET['event_id'] ) ? intval( $_GET['event_id'] ) : 0;
$payment_intent = isset( $_GET['payment_intent'] ) ? sanitize_text_field( $_GET['payment_intent'] ) : 0;

$payment_intent_client_secret = isset( $_GET['payment_intent_client_secret'] ) ? sanitize_text_field( $_GET['payment_intent_client_secret'] ) : '';

$payment_status = isset( $_GET['redirect_status'] ) ? sanitize_text_field( $_GET['redirect_status'] ) : 0;

$payment_data = [
    'payment_intent' => $payment_intent,
    'payment_status' => $payment_status,
    'payment_intent_client_secret' => $payment_intent_client_secret
];

do_action( 'etn_stripe_thankyou', $event_id, $order_id, $invoice, $payment_data );

?>
<div class="etn-view etn-view-public">
    <section class="etn-order" id="">
        <div class="checkout-content">
            <?php 
            global $wpdb;
            $table_name = ETN_EVENT_PURCHASE_HISTORY_TABLE;
            $query_string   = "SELECT * FROM $table_name WHERE form_id = %d AND invoice = %s";
            $order          = $wpdb->get_row( $wpdb->prepare("$query_string", $order_id, $invoice) );

            if ( $order_id > 0 && !empty( $order ) ) { 
                $invoice  = $order->invoice;
                $date     = $order->date_time;
                $email    = $order->email;
                $total    = $order->event_amount;
                $currency = get_post_meta( $order_id, '_order_currency', true );

                $variation_details = maybe_unserialize( $order->ticket_variations );
                $ticket_details    = esc_html__( 'Ticket Details', 'eventin-pro' ) . '<br>';
                foreach( $variation_details as $single_variation ) {
                    if ( absint( $single_variation['etn_ticket_qty'] ) > 0) {
                        $ticket_details .= esc_html( $single_variation['etn_ticket_name'] . '*' . $single_variation['etn_ticket_qty'] ) . '<br>';
                    }
                }
                ?>
                <div class="title-section">
                    <h1 class="order-heading"> <?php echo esc_html__('Order received', 'eventin-pro'); ?></h1>
                </div>

                <div class="etn-order-summery-section">
                    <p class="order-success"><?php echo esc_html__('Thank you. Your order has been received.', 'eventin-pro'); ?></p>
                
                    <ul class="order_details">
                        <li class="order-number"><?php echo esc_html__('Order number:', 'eventin-pro'); ?>
                            <strong><?php echo esc_html( $order_id ); ?></strong>
                        </li>
                        <li class="order-number"><?php echo esc_html__('Invoice:', 'eventin-pro'); ?>
                            <strong><?php echo esc_html( $invoice ); ?></strong>
                        </li>
                        <li class="order-date"><?php echo esc_html__('Date:', 'eventin-pro'); ?>
                            <strong><?php echo date("F d, Y", strtotime( $date ) ); ?></strong>
                        </li>
                        <li class="order-email"><?php echo esc_html__('Email:', 'eventin-pro'); ?>
                            <strong><?php echo esc_html( $email ); ?></strong>
                        </li>
                        <li class="order-total"><?php echo esc_html__('Total: ', 'eventin-pro') . esc_html( $currency ); ?>
                            <strong><?php echo esc_html( $total ); ?></strong>
                        </li>
                        <li class="order-method"> <?php echo esc_html__('Payment method:', 'eventin-pro'); ?>
                            <strong><?php echo esc_html__('Stripe', 'eventin-pro'); ?></strong>
                        </li>
                    </ul>
                </div>

                <div class="etn-order-details-section">
                    <h2 class="order-heading"> <?php echo esc_html__('Order details', 'eventin-pro'); ?></h2>

                    <div class="etn-table-container">
                        <table class="form-table etn-table-design etn-order-details">
                            <thead>
                                <tr>
                                    <th class="product-name">  <?php echo esc_html__('Product', 'eventin-pro'); ?></th>
                                    <th class="payment-total"> <?php echo esc_html__('Total', 'eventin-pro'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $product_name   = get_the_title( $order->post_id );
                                $product_url    = get_post_permalink( $order->post_id );
                                $product_amount = $total;
                                $sub_total      = $total;
                                $total          = $total;
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo esc_url( $product_url ); ?>" target="_blank"><?php echo esc_html( $product_name ); ?></a>
                                        <strong> × 1</strong><br>
                                        <?php echo $ticket_details; ?>
                                    </td>
                                    <td><?php echo esc_html( $product_amount ) . ' ' . esc_html( $currency ); ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html__('Subtotal:', 'eventin-pro'); ?></strong>
                                    </td>
                                    <td><?php echo esc_html( $sub_total ) . ' ' . esc_html( $currency ); ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html__('Payment method:', 'eventin-pro'); ?></strong>
                                    </td>
                                    <td>
                                        <strong><?php echo esc_html__('Stripe', 'eventin-pro'); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html__('Total:', 'eventin-pro'); ?></strong>
                                    </td>
                                    <td><?php echo esc_html( $total ) . ' ' . esc_html( $currency ); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="etn-billing-section">
                    <h2 class="order-heading"> <?php echo esc_html__('Billing address', 'eventin-pro'); ?></h2>
                    <?php
                    $stripe_order_id = $order->form_id;

                    $addTioalData = [
                        'first_name'    => get_post_meta( $stripe_order_id, '_billing_first_name', true ),
                        'last_name'     => get_post_meta( $stripe_order_id, '_billing_last_name', true ),
                        'email'         => get_post_meta( $stripe_order_id, '_billing_email', true ),
                    ];
                    if(is_array($addTioalData)) {
                        $dataAttributes = array_map(function($value, $key) {
                            $key = ucwords(str_replace(['_'], ' ', $key));
                            if(strlen(trim($value)) > 0):
                                return '<li><strong>' . $key . ':</strong> ' . $value . ' </li>';
                            endif;
                        }, array_values($addTioalData), array_keys($addTioalData));

                        $dataAttributes = implode(' ', $dataAttributes);
                    } else {
                        $dataAttributes = $addTioalData;
                    }
                    ?>
                    <ul class="shipping_details">
                        <?php echo $dataAttributes; ?>
                    </ul>
                </div>
            <?php 
            } else {
                echo '<p class="etn-error-message">' . esc_html__('Invalid Order', 'eventin-pro') . '</p>';
            }
            ?>
        </div>
    </section>
</div>

<?php 
if( wp_is_block_theme() ){
    block_footer_area();
    wp_footer();
}else{
    get_footer();
}
?>