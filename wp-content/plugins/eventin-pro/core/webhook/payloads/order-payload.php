<?php
/**
 * Order Payload
 *
 * @package Eventin
 */
namespace Etn_Pro\Core\Webhook\Payloads;

/**
 * Order Payload Class
 */
class OrderPayload implements PayloadInterface {
    /**
     * Get payload
     *
     * @param   mixed  $args
     *
     * @return  array
     */
    public function get_data( $id ) {
        $post = get_post( $id );

        if ( 'shop_order' === $post->post_type ) {
            return $this->get_wc_order( $id );
        }

        return $this->get_stripe_order( $id );
    }

    /**
     * Get woocommerce order data
     *
     * @param   integer  $order_id 
     *
     * @return  array
     */
    private function get_wc_order( $order_id ) {
        $order = wc_get_order( $order_id );

        $data = $order->get_data();

        return $data;
    }

    /**
     * Get stripe order data
     *
     * @param   integer  $order_id 
     *
     * @return  array
     */
    private function get_stripe_order( $order_id ) {
        $order = [
            'id'            => $order_id,
            'tax'           =>  get_post_meta( $order_id, '_order_tax', true ),
            'order_total'   => get_post_meta( $order_id, 'etn_variation_total_price', true ),
            'currency'      => get_post_meta( $order_id, '_order_currency', true ),
            'status'        => get_post_meta( $order_id, 'etn_payment_status', true ),
            'billing_details'   => [
                'first_name'    => get_post_meta( $order_id, '_billing_first_name', true ),
                'last_name'     => get_post_meta( $order_id, '_billing_last_name', true ),
                'email'         => get_post_meta( $order_id, '_billing_email', true ),
                'country'       => get_post_meta( $order_id, '_billing_country', true ),
            ],
            'shipping'      =>  get_post_meta( $order_id, '_order_shipping', true ),
            'shipping_tax'  =>  get_post_meta( $order_id, '_order_shipping_tax', true ),
            
        ];

        return $order;
    }
}
