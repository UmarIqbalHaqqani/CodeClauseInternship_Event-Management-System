<?php
namespace Etn_Pro\Core;
use Etn_Pro\Utils\Helper;
defined( 'ABSPATH' ) || exit;

class Action {

    use \Etn\Traits\Singleton;

    public $etn_pro_attende_report;
    public $search_count;

    public function init(){
        add_action( 'init', [$this, 'resend_purchase_emails'] );
    }

    /**
     * Query to get attendee list
     */
    public function attendee_list( $id, $args = [], $action_download = false ) {
        global $wpdb;
        $defaults = [
            'orderby' => 'event_id',
            'order'   => 'DESC',
        ];

        $args       = extract( wp_parse_args( $args, $defaults ) );
        $table_name = ETN_EVENT_PURCHASE_HISTORY_TABLE;

        $query_string = "SELECT * FROM $table_name WHERE post_id = %d ORDER BY $orderby $order";
        
        if( !empty($limit) ){
            $query_string .= " LIMIT $offset, $limit";
        }

        $purchases  = $wpdb->get_results( $wpdb->prepare("$query_string", $id) );

        if ( !empty( $purchases ) ) {
            $order_data_not_found_msg   = esc_html__( 'Order data not found.', 'eventin-pro' );
            $full_name                  = $order_data_not_found_msg;
            $resend_email_markup        = $order_data_not_found_msg;

            foreach ( $purchases as $value ) {
                if ( class_exists( 'Woocommerce' ) && $value->payment_type == 'woocommerce' ) {
                    $order_id                   = $value->form_id;
                    $order                      = wc_get_order( $order_id );
                    $value->invoice             = !empty( $order ) ? $order->get_order_key() : '';
                    $value->invoice             = $action_download ? strtoupper($value->invoice) : "<a target='_blank' href='" . admin_url( 'post.php?post=' . absint( $order_id  ) . '&action=edit' ) . "'>" . esc_html(strtoupper($value->invoice)) . "</a>";
        
                    if( !empty( $order_id ) ){
                        $order   = wc_get_order( $order_id );
        				if ( empty($order) ) {
							break;
						}
                        foreach( $order->get_items() as $item_id => $item ){

                            $product_name     = $item->get_name();
                            $event_id         = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
                            /**
							 * Retrieve data
							 */
							$product_total      = $item->get_total();
							$total_price        = !empty( $item->get_meta( '_etn_variation_total_price', true ) ) ? $item->get_meta( '_etn_variation_total_price', true ) : $product_total;

							$update_where   = [
								'form_id' => $order_id,
								'post_id' => intval($event_id),
							];

							$wpdb->update( ETN_EVENT_PURCHASE_HISTORY_TABLE , [ 'event_amount' => $total_price ], $update_where );
							/**
							 * End
							 */

                            if( !empty( $event_id ) ){
                                $event_object = get_post( $event_id );
                            } else{
								$array_of_objects = get_posts(
									[
										'title' => $product_name,
										'post_type' => 'etn'
									]
								);

								$event_object = get_post( $array_of_objects[0]->ID );

                            }

                            if ( !empty( $event_object->post_type ) && ('etn' == $event_object->post_type) && ( $event_object->ID == $id ) ) {
                                $resend_email_markup = $this->resend_form_markup($id, $order_id);
                            }
                        }
                        $full_name = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
                    }
                    $value->no_of_tickets = $value->ticket_qty;
                } else {
                    $order_id            = $value->form_id;
                    $full_name           = get_post_meta( $order_id, '_billing_first_name', true ) . " " . get_post_meta( $order_id, '_billing_last_name', true );
                    $resend_email_markup = $this->resend_form_markup( $id, $order_id, 'stripe' );
                }

                $value->amount          = $value->event_amount;
                $value->payment_gateway = ucwords(str_replace('_', ' ', $value->payment_gateway));
                $value->full_name       = $full_name;
                $value->send_ticket     = $resend_email_markup;
            }

        }


        /*
         * Total tickets according to ticket variation
         * Like Event 1 : Attendee purchase 3tickets ( ticket 1 = 1 , ticket 2 = 2 )
        */

        if( count( $purchases ) > 0 ) {
            foreach($purchases as $key => $purchase) {
                $ticket_details     = '';
                $ticket_details_arr = [];
				$manual_attendee    = get_post_meta($purchase->form_id,'_manual_attendee',true);
				ob_start();
				if( '1' == $manual_attendee ) {
					?>
					<!-- Name -->
					<div><?php  esc_html_e( \Etn_Pro\Core\Attendee\Hooks::instance()->attendee_meta_by_order( $purchase->form_id ), 'eventin-pro' ) ;?></div>
					<?php
				}

				else if ( !empty( $purchase->ticket_variations )  && '' == $manual_attendee ) {
					$ticket_variations = unserialize( $purchase->ticket_variations );

                    if( is_array(  $ticket_variations) && count( $ticket_variations ) > 0 ) {
                        foreach( $ticket_variations as $item) {

                            if( !empty($item['etn_ticket_name']) ) {
                                if ( !empty( $item['etn_ticket_qty'] ) ) {
                                    $ticket_details_arr[] = $item['etn_ticket_name'] . ": " . $item['etn_ticket_qty'];
                                }
                                $ticket_details       = join( ', ', $ticket_details_arr );
                                ?>
                                <!-- Name -->
                                <div><?php  echo esc_html__('Ticket Name:','eventin-pro') ;?></div>
                                <div><?php  esc_html_e($item['etn_ticket_name'], 'eventin-pro') ;?></div>
                                <!-- Quantity -->
                                <div><?php  echo esc_html__('Ticket Quantity:','eventin-pro') ;?></div>
                                <div><?php  esc_html_e($item['etn_ticket_qty'], 'eventin-pro') ;?></div>
                                <?php
                            }

                        }
                    }
                }

                $purchase->ticket_details = ob_get_clean();
				if ( '' == $manual_attendee ) {
					$purchase->ticket_details = $ticket_details;
				}
            }
        }

        return $purchases;
    }

    public function resend_form_markup($id, $order_id, $gateway='woocommerce') {
        $zoom_event         = false;
        $order_has_attendee = Helper::check_if_attendee_exists_for_ordered_event($order_id);

        if( $zoom_event || !empty( $order_has_attendee ) ){
            ob_start();
            ?>
            <div class='etn-report-resend-emails'>
                <form action='' method='POST'>
                    <input type='hidden' name='resend_email_event_id' value='<?php echo esc_attr( $id ); ?>' />
                    <input type='hidden' name='resend_email_order_id' value='<?php echo esc_attr( $order_id ); ?>' />
                    <input type='hidden' name='payment_gateway' value='<?php echo esc_attr( $gateway ); ?>' />
                    <input type='submit' name='etn_purchase_resend_email'  value='<?php echo esc_attr__( 'Re-send', 'eventin-pro' ); ?>' class='etn-btn etn-btn-primary'/>
                </form>
            </div>
            <?php
            $resend_email_markup = ob_get_clean();
        } else {
            ob_start();
            ?>
            <div class='etn-report-resend-emails'>
                <span class='etn-btn etn-btn-secondary '>
                    <?php echo esc_html__('Not available', 'eventin-pro'); ?>
                </span>
            </div>
            <?php
            $resend_email_markup = ob_get_clean();
        }

        return $resend_email_markup;
    }

    /**
     * Re-send purchase email 
     */
    public function resend_purchase_emails(){
                 
        if( !empty( $_POST['etn_purchase_resend_email'] ) && !empty( $_POST['resend_email_event_id'] ) && !empty( $_POST['resend_email_order_id'] ) ){

            $post_array = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
            $event_id   = $post_array['resend_email_event_id'];
            $order_id   = $post_array['resend_email_order_id'];
            $gateway    = $post_array['payment_gateway'];
            $settings   = Helper::get_settings();

            $attendee_reg_enable        = !empty( $settings["attendee_registration"] ) ? true : false;
            // $zoom_event                 = \Etn\Core\Zoom_Meeting\Helper::check_if_zoom_event( $event_id );
            $order_has_attendee         = Helper::check_if_attendee_exists_for_ordered_event($order_id);
            
            // send email with zoom details for zoom events
            // if( $zoom_event ){
			// 	\Etn\Core\Zoom_Meeting\Helper::send_email_with_zoom_meeting_details( $order_id, $event_id, $gateway );
            // }

            if( $attendee_reg_enable && !empty( $order_has_attendee ) ){
                // send email with attendee ticket
                \Etn\Utils\Helper::send_attendee_ticket_for_woo_order( $order_id, $event_id, $gateway );
            }
        }
    }
 
    /**
     * Count attendee
     */
    public function total_attendee( $id, $email_remaindar = false ) {
        global $wpdb;

        if ( $email_remaindar ) {
            $items = $wpdb->get_results( "SELECT * FROM ". ETN_EVENT_PURCHASE_HISTORY_TABLE ." WHERE post_id = $id" );
            return $items;
        } else {
            $items = $wpdb->get_var( "SELECT  COUNT(*) FROM ". ETN_EVENT_PURCHASE_HISTORY_TABLE ." WHERE post_id = $id" );
            return $items;
        }

    }

	public function success_status() {
		return array('Processing','Hold','Completed');
	}

    /**
     * Purchase history
     */
    public function purchase_history( $args ) {
        $data     = [];
        $defaults = [
            'limit'        => 50,
            'offset'       => 0,
            'taxonomy_cat' => '',
            'taxonomy_tag' => '',
            'event_name'   => '',
            'order_by'     => 'title',
            'order'        => 'DESC',
        ];

        $args             = wp_parse_args( $args, $defaults );
        $purchase_history = [];
        $query            = [
            'post_type'      => 'etn',
            'post_status'    => 'any',
            'orderby'        => [$args['order_by'] => $args['order']],
            'posts_per_page' => $args['limit'],
            'offset'         => $args['offset'],
        ];

        if ( $args['taxonomy_cat'] !== '' ) {
            $query['tax_query'] = [
                [
                    'taxonomy' => 'etn_category',
                    'field'    => 'name',
                    'terms'    => $args['taxonomy_cat'],
                ]];
        }

        if ( $args['taxonomy_tag'] !== '' ) {
            $query['tax_query'] = [
                [
                    'taxonomy' => 'etn_tags',
                    'field'    => 'name',
                    'terms'    => $args['taxonomy_tag'],
                ]];
        }

        if ( $args['event_name'] !== '' ) {
            $query['s'] = $args['event_name'];
        }

        $get_all_posts      = get_posts( $query );
        $this->search_count = count( $get_all_posts );

        if ( is_array( $get_all_posts ) && count( $get_all_posts ) > 0 ) {

            foreach ( $get_all_posts as $key => $post ) {
                $post_id    = $post->ID;

                // WPML Compatibility check
                if( class_exists('SitePress') && function_exists('icl_object_id') ){
                    global $sitepress;
                    $trid = $sitepress->get_element_trid($post_id);
                    $original_translation = $sitepress->get_original_element_id($post_id, 'post_etn'); 
                    if( $original_translation != $post_id ){
                        continue;
                    }
                }

                $is_recurring_parent = Helper::get_child_events( $post_id );
                if(is_array( $is_recurring_parent ) && !empty( $is_recurring_parent )){
                    continue;
                }

                $total_stock        = absint( get_post_meta( $post_id, "etn_total_avaiilable_tickets", true ) );
                $total_sold_ticket  = absint( get_post_meta( $post_id, "etn_total_sold_tickets", true ) );

                $purchase_history[$key]['event_id']         = $post_id;
                $purchase_history[$key]['title']            = get_the_title( $post_id );
                $purchase_history[$key]['available_ticket'] = $total_stock;
                $purchase_history[$key]['sold_ticket']      = $total_sold_ticket;

                $all_sales        = \Etn_Pro\Core\Action::instance()->get_all_event( $post_id );
                $total_sale_price = 0;

                if ( is_array( $all_sales ) && count( $all_sales ) > 0 ) {
                    foreach ( $all_sales as $single_sale ) {
                        if ( is_object( $single_sale ) && in_array($single_sale->status, $this->success_status())  ) {
                            $total_sale_price += $single_sale->event_amount;
                        }
                    }
                }

                $remaining_ticket                           = $total_stock - $total_sold_ticket;
                $purchase_history[$key]['sale_price']       = $total_sale_price;
                $purchase_history[$key]['remaining_ticket'] = $remaining_ticket < 1 ? 0 : $remaining_ticket;
                $purchase_history[$key]['event_date']       = get_post_meta( $post_id, 'etn_start_date', true);
            }

        }

        wp_reset_postdata();
	
        $data['data'] = $purchase_history;

        if ( $args['filter_name'] === 'Filter' ) {
            $data['count'] = $this->total_purchase( false, true );
        } else {
            $data['count'] = $this->total_purchase( false, false );
        }

        return $data;
    }

    /**
     * Order history
     */
    public function order_history( $args ) {
        $data     = [];
        $defaults = [
            'limit'        => 20,
            'offset'       => 0,
            'order_by'     => 'event_id',
            'order'        => 'DESC',
        ];

        $args             = wp_parse_args( $args, $defaults );
        extract( $args );

        global $wpdb;
        $table_name 	= ETN_EVENT_PURCHASE_HISTORY_TABLE;
        $payment_type 	= 'stripe';
    
        $query_string 	= "SELECT * FROM $table_name WHERE payment_type = '". $payment_type ."' ORDER BY event_id DESC";

        if( !empty($limit) ){
            $query_string .= " LIMIT $offset, $limit";
        }

        $stripe_orders  = $wpdb->get_results( $query_string , ARRAY_A );
        $data['data']   = (array) $stripe_orders;

        $query_string 	= "SELECT count(*) FROM $table_name WHERE payment_type = %s";
        $order_total    = $wpdb->get_var( $wpdb->prepare("$query_string", $payment_type) );
        $data['count']  = $order_total;

        return $data;
    }


    /**
     * Count purchase history
     *
     * @return void
     */
    public function total_purchase( $summary = false, $search = false ) {
        $query = [
            'post_type'      => 'etn',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'suppress_filters' => false,
        ];
        $all_events = get_posts( $query );
        $total_events   = count( $all_events );

        if ( $summary == true ) {
            $purchase_summary   = [];
            $total_sale_price   = 0;
            $total_sale_tickets = 0;

            $settings   = Helper::get_settings();
            $etn_count_refund_price = !empty( $settings["etn_count_refund_price"] ) ? true : false;
            $result_params = array('Processing','Hold','Completed');

            if($etn_count_refund_price){
                array_push($result_params,'Failed');
            }
            $filter_string = "'" . implode("','", $result_params) . "'";

            global $wpdb;
            $all_sales = $wpdb->get_results( "SELECT * FROM ". ETN_EVENT_PURCHASE_HISTORY_TABLE ." WHERE status IN ($filter_string) order by event_id DESC" );
            foreach( $all_sales as $single_sale ){
                $sale_price         = $single_sale->event_amount;
                $total_sale_price  += $sale_price;
            }

            foreach ( $all_events as $single_event ) {
                $single_event_sale  = absint( get_post_meta( $single_event->ID, "etn_total_sold_tickets", true ) );
                $total_sale_tickets += $single_event_sale;
            }

            $purchase_summary['events']       = $total_events;
            $purchase_summary['sale_tickets'] = $total_sale_tickets;
            $purchase_summary['sale_price']   = $total_sale_price;

            return $purchase_summary;

        }elseif( $search == true ) {
            return $this->search_count;
        }else{
            return $total_events;
        }

        wp_reset_postdata();
    }

    /**
     * Purchase Report Summary
     *
     * @return void
     */
    public function purchase_summary() {
        return $this->total_purchase( true );
    }

    /**
     * get event by id function
     */
    public function get_all_event( $post_id ) {
        global $wpdb;

        $settings   = Helper::get_settings();
        $etn_count_refund_price = !empty( $settings["etn_count_refund_price"] ) ? true : false;
        $result_params = array('Cancelled');

        if(! $etn_count_refund_price){
            array_push($result_params,'Failed');
        }
        $filter_string = "'" . implode("','", $result_params) . "'";

        $all_sales = $wpdb->get_results( "SELECT * FROM ". ETN_EVENT_PURCHASE_HISTORY_TABLE ." WHERE post_id = $post_id AND status NOT IN ($filter_string) order by event_id DESC" );

        return $all_sales;
    }

    /**
     * Export attendee function
     */
    public function csv_export_attendee_report( $id ) {

        // Check for current user privileges
        if ( !current_user_can( 'manage_options' ) || !is_admin() ) {
            return;
        }

        $attendee_report = $this->attendee_list( $id, [], true );

        if ( is_array( $attendee_report ) && !empty( $attendee_report ) ) {

            $generated_date = date( 'd-m-Y His' ); //Date will be part of file name.

            header( "Content-type: text/csv" );
            header( "Content-Disposition: attachment; filename=\"etn_attendee_" . $generated_date . ".csv\";" );

            ob_end_clean();
            // create a file pointer connected to the output stream
            $output = fopen( 'php://output', 'w' ) or die( "Can\'t open php://output" );

            // output the column headings
            fputcsv( 
                $output, 
                [
                    'Invoice', 
                    'Full Name', 
                    "Email",
                    "Payment", 
                    "No of Tickets",
                    'Ticket Details', 
                    'Amount', 
                    "Date", 
                    "Status"
                ]
            );

            foreach ( $attendee_report as $key => $value ) {
                fputcsv( 
                    $output,
                    [
                        $value->invoice, 
                        $value->full_name, 
                        $value->email, 
                        $value->payment_gateway,
                        $value->ticket_qty, 
                        $value->ticket_details, 
                        $value->event_amount, 
                        "=\"" . $value->date_time . "\"", 
                        $value->status
                    ]
                );

            }

            // Close output file stream
            fclose( $output );

            die();
        } else {
            ?>
            <div class=""><?php echo esc_html__( 'No data found.', 'eventin-pro' ) ?></div>
            <?php
        }

    }

}
