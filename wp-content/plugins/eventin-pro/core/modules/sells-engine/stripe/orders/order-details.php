<div class="etn-view etn-view-public">
    <section class="etn-order" id="">
        <div class="checkout-content">
            <?php 
            $order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
            $order = \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->stripe_order_details( $order_id );
			$manual_attendee    = get_post_meta($order_id,'_manual_attendee',true);

            if ( !empty( $order )  ) { 
				$ticket_details_data = "";
				if ( "" == $manual_attendee ) {
					$variation_details = maybe_unserialize( $order->ticket_variations );
					$ticket_details    = [];
					foreach( $variation_details as $single_variation ) {
						if ( absint( $single_variation['etn_ticket_qty'] ) > 0) {
							$ticket_details[] = esc_html( $single_variation['etn_ticket_name'] . '*' . $single_variation['etn_ticket_qty'] );
						}
					}
					$ticket_details_data = join( ', ', $ticket_details );
				} else {
					$ticket_details_data = \Etn_Pro\Core\Attendee\Hooks::instance()->attendee_meta_by_order( $order_id );
				}
				
            ?>
            <div class="etn-stripe-order-details">
                <table class="wp-list-table widefat fixed striped table-view-list">
                    <thead>
                        <tr>
                            <th colspan="2"><?php echo esc_html__('Order details', 'eventin-pro'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo esc_html__('Order No.', 'eventin-pro'); ?></td>
                        <td><?php echo esc_html( $order_id ); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Invoice', 'eventin-pro'); ?></td>
                        <td><?php echo esc_html( $order->invoice ); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Date', 'eventin-pro'); ?></td>
                        <td><?php echo esc_html( $order->date_time ); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Event', 'eventin-pro'); ?></td>
                        <td><?php echo esc_html( get_the_title( $order->post_id ) ); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Ticket Details', 'eventin-pro'); ?></td>
                        <td><?php echo esc_html( $ticket_details_data ); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Amount', 'eventin-pro'); ?></td>
                        <td><?php echo esc_html( $order->event_amount ); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Currency', 'eventin-pro'); ?></td>
                        <td>
                            <?php 
                            $order_currency = get_post_meta( $order_id, '_order_currency', true );
                            echo esc_html( $order_currency ); 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Payment gateway', 'eventin-pro'); ?></td>
                        <td><?php echo esc_html( ucfirst( $order->payment_type ) ); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Status', 'eventin-pro'); ?></td>
                        <td><?php echo esc_html( $order->status ); ?></td>
                    </tr>
                    </tbody>
                </table>

                <table class="wp-list-table widefat fixed striped table-view-list">
                    <thead>
                    <tr>
                        <th colspan="2"><?php echo esc_html__('Billing details', 'eventin-pro'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $first_name      = get_post_meta( $order_id, '_billing_first_name', true );
                        $last_name       = get_post_meta( $order_id, '_billing_last_name', true );
                        $email           = get_post_meta( $order_id, '_billing_email', true );
                        ?>
                        <tr>
                            <td><?php echo esc_html__('First Name', 'eventin-pro'); ?></td>
                            <td><?php echo esc_html( $first_name ); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo esc_html__('Last Name', 'eventin-pro'); ?></td>
                            <td><?php echo esc_html( $last_name ); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo esc_html__('Email', 'eventin-pro'); ?></td>
                            <td><?php echo esc_html( $email ); ?></td>
                        </tr>
                    <?php
                    ?>

                    </tbody>
                </table>
            </div>
			<?php } else {
				echo '<p class="etn-error-message">' . esc_html__('Invalid Order', 'eventin-pro') . '</p>';
			}
			?>
        </div>
    </section>
</div>