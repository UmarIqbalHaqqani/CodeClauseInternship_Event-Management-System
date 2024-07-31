<?php
/**
 * Order Importer Class
 *
 * @package Eventin
 */
namespace Etn_Pro\Core\Modules\Sells_Engine\Stripe\Orders;

use Etn\Base\Importer\Reader_Factory;

/**
 * Class Order Importer
 */
class Order_Importer {
    /**
     * Store File
     *
     * @var string
     */
    private $file;

    /**
     * Store data
     *
     * @var array
     */
    private $data;

    /**
     * Order import
     *
     * @return  void
     */
    public function import( $file ) {
        $this->file  = $file;
        $file_reader = Reader_Factory::get_reader( $this->file );

        $this->data = $file_reader->read_file();

        $this->create_Order();
    }

    /**
     * Create Order
     *
     * @return  void
     */
    private function create_Order() {
        $order = new Order_Model();

        $data = $this->data;
        $file_type = ! empty( $this->file['type'] ) ? $this->file['type'] : '';

        foreach ( $data as $row ) {
            $first_name         = ! empty( $row['first_name'] ) ? $row['first_name'] : '';
            $last_name          = ! empty( $row['last_name'] ) ? $row['last_name'] : '';
            $email              = ! empty( $row['email'] ) ? $row['email'] : '';
            $invoice            = ! empty( $row['invoice'] ) ? $row['invoice'] : '';
            $date               = ! empty( $row['date'] ) ? $row['date'] : '';
            $event_id           = ! empty( $row['event_id'] ) ? $row['event_id'] : '';
            $event              = ! empty( $row['event'] ) ? $row['event'] : '';
            $ticket_details     = ! empty( $row['ticket_details'] ) ? $row['ticket_details'] : '';
            $amount             = ! empty( $row['amount'] ) ? $row['amount'] : '';
            $_order_currency    = ! empty( $row['currency'] ) ? $row['currency'] : '';
            $payment_gateway    = ! empty( $row['payment_gateway'] ) ? $row['payment_gateway'] : '';
            $etn_payment_status = ! empty( $row['status'] ) ? $row['status'] : '';
            $ticket_variations  = ! empty( $row['ticket_variations'] ) ? $row['ticket_variations'] : '';
            $ticket_quantity    = ! empty( $row['ticket_quantity'] ) ? $row['ticket_quantity'] : '';

            if ( 'text/csv' == $file_type ) { 
                $ticket_variations = etn_csv_column_multi_dimension_array( $ticket_variations );
            }
            
            $args = [
                '_billing_first_name'          => $first_name,
                '_billing_last_name'           => $last_name,
                '_billing_email'               => $email,
                'invoice'                      => $invoice,
                'date'                         => $date,
                'event_id'                     => $event_id,
                'event'                        => $event,
                'ticket_details'               => $ticket_details,
                '_order_currency'              => $_order_currency,
                'payment_gateway'              => $payment_gateway,
                'etn_payment_status'           => $etn_payment_status,
                'etn_ticket_variations_picked' => $ticket_variations,
                'etn_variation_total_quantity' => $ticket_quantity,
            ];

            if ( $order->create( $args ) ) {
                $transaction_data = [
                    'post_id'           => $event_id,
                    'form_id'           => $order->id,
                    'invoice'           => $invoice,
                    'event_amount'      => $amount,
                    'ticket_qty'        => $ticket_quantity,
                    'ticket_variations' => serialize( $ticket_variations ),
                    'user_id'           => get_current_user_id(),
                    'email'             => $email,
                    'event_type'        => 'ticket',
                    'payment_type'      => $payment_gateway,
                    'pledge_id'         => '',
                    'payment_gateway'   => $payment_gateway,
                    'date_time'         => $date,
                    'status'            => $etn_payment_status,
                ];

                $this->create_transaction( $transaction_data );
            }
        }
    }

    /**
     * Create transaction
     *
     * @param   array  $args
     *
     * @return  void
     */
    private function create_transaction( $args ) {
        global $wpdb;

        $defaults = [
            'post_id'           => 0,
            'form_id'           => 0,
            'invoice'           => '',
            'event_amount'      => 0,
            'ticket_qty'        => 0,
            'ticket_variations' => '',
            'user_id'           => 0,
            'email'             => '',
            'event_type'        => '',
            'payment_type'      => '',
            'pledge_id'         => '',
            'payment_gateway'   => '',
            'date_time'         => NULL,
            'status'            => '',
        ];

        $args = wp_parse_args( $args, $defaults );

        $wpdb->insert( ETN_EVENT_PURCHASE_HISTORY_TABLE, $args );
    }
}
