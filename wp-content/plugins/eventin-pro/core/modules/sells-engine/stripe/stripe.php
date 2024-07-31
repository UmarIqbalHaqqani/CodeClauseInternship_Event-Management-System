<?php

namespace Etn_Pro\Core\Modules\Sells_Engine\Stripe;

use Etn_Pro\Core\Modules\Sells_Engine\Stripe\Orders\Order_Exporter;
use Etn_Pro\Core\Modules\Sells_Engine\Stripe\Orders\Order_Importer;
use Etn\Utils\Helper;

defined( 'ABSPATH' ) || die();

/***
* Stripe activities.
*/
class Stripe {
    use \Etn\Traits\Singleton;
    /**
     * Fire stripe hooks.
     */
    public function init() {

        // enqueue scripts.
        $this->enqueue_scripts();
        // load stripe script.
        $this->etn_stripe()->_load_script();
        add_filter( 'etn_pro/stripe/ticket_template', array( $this,'ticket_template' ) ,10 , 1 );
        add_action( 'wp_footer', [ $this, 'add_payment_form' ] );
        add_action( 'wp_ajax_etn_payment_intent', [ $this, 'create_payment_intent' ] );
        add_action( 'wp_ajax_nopriv_etn_payment_intent', [ $this, 'create_payment_intent' ] );
        add_action( 'admin_init', [ $this, 'stripe_refund' ] );
        add_filter( 'etn_post_exporters', [ $this, 'add_exporter' ] );
        add_filter( 'etn_post_importers', [ $this, 'add_importer' ] );
        
    }

    /**
     * Stripe payment form
     *
     * @return  void
     */
    public function add_payment_form() {
        global $post;

        if ( !empty($post) && 'etn' !== $post->post_type ) {
            return;
        }

        ?>
        <div id="etn-stripe-wrap" style="display: none">
            <div id="etn-stripe">
                <form id="payment-form">
                    <span class="close-button" id="etn-stripe-close">&times;</span>
                    <div id="link-authentication-element">
                        <!--Stripe.js injects the Link Authentication Element-->
                    </div>
                    <div id="payment-element">
                        <!--Stripe.js injects the Payment Element-->
                    </div>
                    <button id="submit">
                        <div class="spinner hidden" id="spinner"></div>
                        <span id="button-text">Pay now</span>
                    </button>
                    <div id="payment-message" class="hidden"></div>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Create payment intent
     *
     * @return  JSON
     */
    public function create_payment_intent() {
        $amount 	= ! empty( $_POST['amount'] ) ? $this->float_value( $_POST['amount'] ) : 0.0;
        $currency 	= ! empty( $_POST['currency'] ) ? sanitize_text_field($_POST['currency']) : 'USD';
        $stripe 	= new StripePayment();

        $data	= $stripe->create_payment([
            'amount' => $amount * 100,
            'currency' => $currency,
        ]);

        wp_send_json_success($data);
    }

    /**
     * Enqueue scripts.
     */
    public function enqueue_scripts() {
        add_action( 'wp_enqueue_scripts', array( $this , 'js_css_public' ) );
    }

    /**
     * Load Strip script.
     */
    public function etn_stripe() {
        return \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Payments\Setup::instance();
    }

    /**
     * Frontend scripts.
     */
    public function js_css_public() {
        wp_enqueue_script( 'etn-stripe-payment', ETN_PRO_CORE . 'modules/sells-engine/stripe/assets/js/stripe-payment.js', ['jquery'], time(), true );
        wp_enqueue_script( 'etn-stripe-public', ETN_PRO_CORE . 'modules/sells-engine/stripe/assets/js/etn-stripe.js', ['jquery', 'etn-stripe-payment'], time(), true );
        
        $settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $stripe_publishable_key = (isset($settings['stripe_live_publishable_key']) ? $settings['stripe_live_publishable_key'] : '');
        $thank_you_data 							= get_page_by_path( 'etn_stripe_thank_you' );
        $localized_data                         	= [];
        $localized_data['ajax_url']             	= admin_url( 'admin-ajax.php' );
        $localized_data['stripe_payment_nonce'] 	= wp_create_nonce( 'stripe_payment_nonce_value' );
        $localized_data['redirect_url'] 			= get_page_link( $thank_you_data->ID );
        $localized_data['stripe_publishable_key'] 	= $stripe_publishable_key;
        $localized_data['common_err_msg'] 			= esc_html__( 'Something went wrong, Please try again.', 'eventin-pro' );

        wp_localize_script( 'etn-stripe-public', 'localized_stripe_data', $localized_data );

        wp_enqueue_style('etn-stripe-css', ETN_PRO_CORE . 'modules/sells-engine/stripe/assets/css/stripe.css');
    }

    /**
     * Get an moderate entropy unique id, specially for generating uuid for pledge
     * @param int $prefix_len
     * @return string
     */
    public function get_uuid( $prefix_len = 6 ) {

        $text_shuffle = '@ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $uuid         = substr( str_shuffle( $text_shuffle ) , 0 , $prefix_len ) . '-' . time();

        return $uuid;
    }

    /**
     * Post attendee data
     */
    public function insert_attendee_stripe() {
        $response = array( 'success' => 0, 'message' => esc_html__( 'Something is wrong', 'eventin-pro' ), 'data'=> array() , 'error'=> array()  );
        $result = '';
        $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );

        $settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();

        $attendee_registration     = ( !empty( $settings["attendee_registration"] ) ? true : false );
        $etn_sells_engine_stripe   = ( ! empty( $settings['etn_sells_engine_stripe'] ) ? 'checked': '' );
        $etn_stripe_test_mode      = !empty( $settings['etn_stripe_test_mode'] ) ? $settings['etn_stripe_test_mode'] : '';
        $get_data                  = \Etn_Pro\Utils\Helper::instance()->retrieve_country_currency( true );
        $currency_code             = !empty( $get_data ) ? $get_data['currency'] : 'USD';
        $num_decimals              = !empty( $get_data ) ? $get_data['num_decimals'] : '2';
        $keys = ! empty( $settings['stripe_live_publishable_key'] ) ? $settings['stripe_live_publishable_key'] : '';

        if ( ! empty( $post_arr['attendee_data'] ) ) {
            $attendee_data        = array();
            // re-format data for post attendee.
            foreach ($post_arr['attendee_data'] as $key => $value) {
                $attendee_data[$value['name']]  = $value['value'];
            }

            $order_data         = $this->order_data( $post_arr );
            
            $total_price        = ! empty( $order_data['etn_total_price'] ) ? $order_data['etn_total_price'] : 0;
            $ticket_variations  = ! empty( $order_data['ticket_variations'] ) ? $order_data['ticket_variations'] : [];

            if ( ! empty( $attendee_data['ticket_name'] ) && ! empty( $attendee_data['ticket_quantity'] ) ) {
                $etn_ticket_variations = array();

                foreach ( $attendee_data['ticket_quantity'] as $key => $value ) {
                        $etn_ticket_variations[$key]['etn_ticket_slug'] = $attendee_data['ticket_slug'][$key];
                        $etn_ticket_variations[$key]['etn_ticket_name'] = $attendee_data['ticket_name'][$key];
                        $etn_ticket_variations[$key]['etn_ticket_qty']  = $value;
                }
                $attendee_data['picked_ticket_variations'] = $etn_ticket_variations;
            }

            if($attendee_registration){
                $result = \Etn\Core\Attendee\Hooks::instance()->add_attendee_data( 'stripe' , $attendee_data );
            }

            if ( 'success' == $result ) {

                $data['currency_code']  = $currency_code;
                $data['num_decimals']  = $num_decimals;
                $data['keys']           = $keys;

                if ( $attendee_registration ) {
                    $data['fname']           = !empty( $attendee_data['client_fname'] ) ? $attendee_data['client_fname'] : '';
                    $data['lname']           = !empty( $attendee_data['client_lname'] ) ? $attendee_data['client_lname'] : '';
                    $data['email']          = !empty( $attendee_data['client_email'] ) ? $attendee_data['client_email'] : '';
                    $data['event_id']       = !empty( $attendee_data['add-to-cart'] ) ? $attendee_data['add-to-cart'] : '';
                    $data['event_name']     = ! empty( $attendee_data['event_name'] ) ? $attendee_data['event_name'] : '';
                    $data['etn_status_update_token'] = !empty( $attendee_data['attendee_info_update_key'] ) ? $attendee_data['attendee_info_update_key'] : 0;
                } else {
                    $data['fname']           = !empty( $post_arr['client_fname'] ) ? $post_arr['client_fname'] : '';
                    $data['lname']           = !empty( $post_arr['client_lname'] ) ? $post_arr['client_lname'] : '';
                    $data['email']          = !empty( $post_arr['client_email'] ) ? $post_arr['client_email'] : '';
                    $data['event_id']       = !empty( $post_arr['add-to-cart'] ) ? $post_arr['add-to-cart'] : '';
                    $data['event_name']     = ! empty( $post_arr['event_name'] ) ? $post_arr['event_name'] : '';
                    $data['etn_status_update_token'] = !empty( $post_arr['attendee_info_update_key'] ) ? $post_arr['attendee_info_update_key'] : 0;
                }
                
                $data['etn_total_qty']  = !empty( $order_data['etn_total_qty'] ) ? $order_data['etn_total_qty'] : 0;
                $data['etn_total_price']= $total_price;
                $data['picked_ticket_variations'] = $ticket_variations;
                $data['check_id']       = \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->get_uuid();

                $stripe_order_id  = $this->stripe_order_creation( $data );
                $data['order_id'] = $stripe_order_id;
                $response = array( 'success' => 1, 'message' => esc_html__( 'Attendee insert successfully', 'eventin-pro' ) , 'data'=> $data , 'error'=> array() );

            }elseif(!($attendee_registration) && ('checked' == $etn_sells_engine_stripe) ) {
                $data['currency_code']  = $currency_code;
                $data['num_decimals']  = $num_decimals;
                $data['keys']           = $keys;

                if ( $attendee_registration ) {
                    $data['fname']           = !empty( $attendee_data['client_fname'] ) ? $attendee_data['client_fname'] : '';
                    $data['lname']           = !empty( $attendee_data['client_lname'] ) ? $attendee_data['client_lname'] : '';
                    $data['email']          = !empty( $attendee_data['client_email'] ) ? $attendee_data['client_email'] : '';
                    $data['event_id']       = !empty( $attendee_data['add-to-cart'] ) ? $attendee_data['add-to-cart'] : '';
                    $data['event_name']     = ! empty( $attendee_data['event_name'] ) ? $attendee_data['event_name'] : '';
                    $data['etn_status_update_token'] = !empty( $attendee_data['attendee_info_update_key'] ) ? $attendee_data['attendee_info_update_key'] : 0;
                } else {
                    $data['fname']           = !empty( $post_arr['client_fname'] ) ? $post_arr['client_fname'] : '';
                    $data['lname']           = !empty( $post_arr['client_lname'] ) ? $post_arr['client_lname'] : '';
                    $data['email']          = !empty( $post_arr['client_email'] ) ? $post_arr['client_email'] : '';
                    $data['event_id']       = !empty( $post_arr['add-to-cart'] ) ? $post_arr['add-to-cart'] : '';
                    $data['event_name']     = ! empty( $post_arr['event_name'] ) ? $post_arr['event_name'] : '';
                    $data['etn_status_update_token'] = !empty( $post_arr['attendee_info_update_key'] ) ? $post_arr['attendee_info_update_key'] : 0;
                }

                
                $data['etn_total_qty']  = !empty( $order_data['etn_total_qty'] ) ? $order_data['etn_total_qty'] : 0;
                $data['etn_total_price']= $total_price;
                $data['picked_ticket_variations'] = $ticket_variations ;
                $data['check_id']       = \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->get_uuid();

                $stripe_order_id  = $this->stripe_order_creation( $data );
                $data['order_id'] = $stripe_order_id;
    
                $response = array( 'success' => 1, 'message' => esc_html__( 'Added popup successfully', 'eventin-pro' ) , 'data'=> $data , 'error'=> array() );

            }else{
                $response = array( 'success' => 0, 'message' => esc_html__( 'Attendee is not insert successfully', 'eventin-pro' ), 'data'=> array() , 'error'=> array()  );
            }
        } 

        return wp_send_json_success( $response );
    }

    /**
    * Render ticket template.
    */
    public function ticket_template($single_event_id) {
        // for single events
        ?>
            <div class="etn-single-event-ticket-wrap">
                <?php \Etn\Utils\Helper::eventin_ticket_widget( $single_event_id );  ?>
            </div>
        <?php
    }

    
    /**
     * stripe order creation
     * 
     * @return int
     */
    public function stripe_order_creation( $data = [] , $manual = false ) {
        $stripe_order_id = wp_insert_post( [
            'post_title'  => esc_html__( 'Stripe order', 'eventin-pro' ),
            'post_type'   => 'etn-stripe-order',
            'post_status' => 'publish',
        ] );
        
        wp_update_post( ['ID' => $stripe_order_id] );

        $etn_ticket_variations_picked   = $data['picked_ticket_variations'];
        $etn_variation_total_price      = $data['etn_total_price'];
        $etn_variation_total_quantity   = $data['etn_total_qty'];
        $etn_status_update_key          = $data['etn_status_update_token'];
        $invoice_key                    = $data['check_id'];
        $currency                       = $data['currency_code'];
        $num_decimals                   = $data['num_decimals'];

        $billing_first_name           	= $data['fname'];
        $billing_last_name            	= $data['lname'];
        $billing_email                	= $data['email'];
        $billing_country				= '';

        $order_metas = [
            '_order_key'                    => $invoice_key,
            '_billing_first_name'           => $billing_first_name,
            '_billing_last_name'            => $billing_last_name,
            '_billing_email'                => $billing_email,
            '_order_shipping'               => '',
            '_order_shipping_tax'           => '',
            '_order_tax'                    => '',
            '_billing_country'              => $billing_country,
            '_order_currency'               => $currency,
            'etn_ticket_variations_picked'  => $etn_ticket_variations_picked,
            'etn_variation_total_quantity'  => $etn_variation_total_quantity,
            'etn_variation_total_price'     => $etn_variation_total_price,
            'etn_status_update_key'         => $etn_status_update_key,
            'etn_payment_status'         	  => 'Pending',
        ];

        if ( $manual ) {
            $order_metas['_manual_attendee'] = 1;
        }
        
        foreach ( $order_metas as $key => $value ) {
            update_post_meta( $stripe_order_id, $key, $value );
        }

        return $stripe_order_id;
    }

    /**
     * get order details through order id
     *
     * @param [int] $order_id
     * @return Object
     */
    public static function stripe_order_details( $order_id = null ) {
        global $wpdb;
        $table_name = ETN_EVENT_PURCHASE_HISTORY_TABLE;

        $query_string = "SELECT * FROM $table_name WHERE form_id = %d";
        $order        = $wpdb->get_row( $wpdb->prepare("$query_string", $order_id) );

        return $order;
    }

    /**
     * Create stripe refund
     *
     * @return  void
     */
    public function stripe_refund() {
        $action 	= isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
        $order_id 	= isset( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0;
        
        global $wpdb;

        if ( 'etn-stripe-refund' !== $action ) {
            return;
        }

        $payment_intent = get_post_meta( $order_id, 'payment_intent', true );

        $payment	= new StripePayment();
        $refunded 	= $payment->create_refund( $payment_intent );
        
        if ( $refunded ) {
            $update_where = [
                'form_id' => $order_id
            ];
            
            $table_name = ETN_EVENT_PURCHASE_HISTORY_TABLE;
            $wpdb->update( $table_name, [ 'status' => 'Refunded' ], $update_where );

            $transaction = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE form_id = {$order_id}" );

            $event_id                   = $transaction->post_id;
            $ticket_quantity            = $transaction->ticket_qty;
            $total_sold_tickets         = get_post_meta( $event_id, 'etn_total_sold_tickets', true );
            $updated_total_sold_tickets = $total_sold_tickets - $ticket_quantity;

            $ticket_variations          = $transaction->ticket_variations;


            // Update attendee payment status.
            $order_attendees = Helper::get_attendee_by_woo_order( $order_id );
            if ( is_array( $order_attendees ) && !empty( $order_attendees ) ) {
                foreach( $order_attendees as $attendee_id ){
                    update_post_meta( $attendee_id, 'etn_status', 'failed' );
                }
            }

            update_post_meta( $order_id, 'etn_payment_status', 'Refunded' );
            update_post_meta( $event_id, "etn_total_sold_tickets", $updated_total_sold_tickets );
        }

        wp_redirect( admin_url( 'admin.php?page=etn_stripe_orders_report' ) );

        exit;
    }

    /**
     * Format float value
     *
     * @param   string  $val
     *
     * @return  float
     */
    public function float_value( $val ) {
        $val = str_replace( ',', '.', $val );
        $val = preg_replace( '/\.(?=.*\.)/', '', $val );

        return floatval( $val );
    }

    /**
     * Add order exporters
     *
     * @param   array  $exporters
     *
     * @return  array
     */
    public function add_exporter( $exporters ) {
        $exporters['etn-stripe-order'] = Order_Exporter::class;

        return $exporters;
    }

    /**
     * Add order importers
     *
     * @param   array  $importers
     *
     * @return  array
     */
    public function add_importer( $importers ) {
        $importers['etn-stripe-order'] = Order_Importer::class;

        return $importers;
    }

    /**
     * Prepare order data
     *
     * @param   array  $input_data
     *
     * @return  array
     */
    private function order_data( $input_data ) {
        if ( session_status() === PHP_SESSION_NONE ) {
            session_start();
        }

        $order_data = [];
        $attendee_registration     = etn_get_option('attendee_registration') ?: false;
        

        if ( ! $attendee_registration ) {
            $event_id 			= ! empty( $input_data['event_id'] ) ? $input_data['event_id'] : [];
            $ticket_quantity 	= ! empty( $input_data['ticket_quantity'] ) ? $input_data['ticket_quantity'] : [];
            $ticket_variations = get_post_meta( $event_id, 'etn_ticket_variations', true );
            $total_price = 0;
            $total_ticket = 0;

            if ( ! $ticket_quantity ) {
                return;
            }

            $variations = [];

            foreach ( $ticket_variations as $ticket ) {

                $quantity 	= ! empty( $ticket_quantity[$ticket['etn_ticket_name']] ) ? $ticket_quantity[$ticket['etn_ticket_name']]: 0;
                $price 		= $ticket['etn_ticket_price']; 

                if ( $quantity ) {
                    $total_price += $quantity * $price;
                    $total_ticket += $quantity;
                    $session_data['ticket_quantity'][] = $quantity;
                    $session_data['ticket_price'][] = $price;
                    $session_data['ticket_name'][] = $ticket['etn_ticket_name'];
                    $session_data['ticket_slug'][] = $ticket['etn_ticket_slug'];

                    $variation = [
                        'etn_ticket_slug' => $ticket['etn_ticket_slug'],
                        'etn_ticket_name' => $ticket['etn_ticket_name'],
                        'ticket_price' 	  => $price,
                        'etn_ticket_qty'  => $quantity,
                    ];

                    $variations[] = $variation;
                }
                
            }
        } else {
            $session_data           = $_SESSION['etn_cart_session'] ?: [];
            $total_price            = ! empty( $session_data['etn_total_price'] ) ? $session_data['etn_total_price'] : 0;
            $variations             = ! empty( $session_data['ticket_variations'] ) ? $session_data['ticket_variations'] : [];
            $total_ticket           = ! empty( $session_data['etn_total_qty'] ) ? $session_data['etn_total_qty'] : 0;
        }

        $order_data = [
            'etn_total_price'   => $total_price,
            'ticket_variations' => $variations,
            'etn_total_qty'     => $total_ticket,
        ];

        return $order_data;
    }
}
