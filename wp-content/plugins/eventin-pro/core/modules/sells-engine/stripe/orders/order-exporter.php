<?php
/**
 * Order Exporter Class
 *
 * @package Eventin
 */
namespace Etn_Pro\Core\Modules\Sells_Engine\Stripe\Orders;

use Etn\Base\Exporter\Exporter_Factory;
use Etn\Base\Exporter\Post_Exporter_Interface;
use Etn_Pro\Core\Attendee\Hooks;
use Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe;

/**
 * Class Order Exporter
 *
 * Export Order Data
 */
class Order_Exporter implements Post_Exporter_Interface {
    /**
     * Store file name
     *
     * @var string
     */
    private $file_name = 'order-data';

    /**
     * Store attendee data
     *
     * @var array
     */
    private $data;

    /**
     * Export attendee data
     *
     * @return void
     */
    public function export( $data, $format ) {
        $this->data = $data;

        $rows      = $this->prepare_data();
        $columns   = $this->get_columns();
        $file_name = $this->file_name;

        $exporter = Exporter_Factory::get_exporter( $format );

        $exporter->export( $rows, $columns, $file_name );
    }

    /**
     * Prepare data to export
     *
     * @return  array
     */
    private function prepare_data() {
        $ids           = $this->data;
        $exported_data = [];
        $stripe        = new Stripe();

        foreach ( $ids as $id ) {
            $order = $stripe->stripe_order_details( $id );
            if ( ! $order ) {
                continue;
            }

            $ticket     = Hooks::instance()->attendee_meta_by_order( $id );
            $order_data = [
                'id'                => $id,
                'first_name'        => get_post_meta( $id, '_billing_first_name', true ),
                'last_name'         => get_post_meta( $id, '_billing_last_name', true ),
                'email'             => get_post_meta( $id, '_billing_email', true ),
                'invoice'           => $order->invoice,
                'date'              => $order->date_time,
                'event_id'          => $order->post_id,
                'event'             => get_the_title( $order->post_id ),
                'ticket_details'    => $ticket,
                'ticket_variations' => get_post_meta( $id, 'etn_ticket_variations_picked', true ),
                'ticket_quantity'   => get_post_meta( $id, 'etn_variation_total_quantity', true ),
                'amount'            => $order->event_amount,
                'currency'          => get_post_meta( $id, '_order_currency', true ),
                'payment_gateway'   => $order->payment_type,
                'status'            => $order->status,
            ];

            array_push( $exported_data, $order_data );
        }

        return $exported_data;
    }

    /**
     * Get columns
     *
     * @return  array
     */
    private function get_columns() {
        return [
            'id'                => __( 'Order No', 'eventin-pro' ),
            'first_name'        => __( 'First Name', 'eventin-pro' ),
            'last_name'         => __( 'Last Name', 'eventin-pro' ),
            'email'             => __( 'Email', 'eventin-pro' ),
            'invoice'           => __( 'Invoice', 'eventin-pro' ),
            'date'              => __( 'Date', 'eventin-pro' ),
            'event_id'          => __( 'Event ID', 'eventin-pro' ),
            'event'             => __( 'Event', 'eventin-pro' ),
            'ticket_details'    => __( 'Ticket Details', 'eventin-pro' ),
            'ticket_variations' => __( 'Ticket Variations', 'eventin-pro' ),
            'ticket_quantity'   => __( 'Ticket Quantity', 'eventin-pro' ),
            'amount'            => __( 'Amount', 'eventin-pro' ),
            'currency'          => __( 'Currency', 'eventin-pro' ),
            'payment_gateway'   => __( 'Payment gateway', 'eventin-pro' ),
            'status'            => __( 'Status', 'eventin-pro' ),
        ];
    }
}
