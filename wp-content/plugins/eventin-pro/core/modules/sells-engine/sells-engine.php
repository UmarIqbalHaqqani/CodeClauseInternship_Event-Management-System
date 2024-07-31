<?php

namespace Etn_Pro\Core\Modules\Sells_Engine;

use Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe;
use \Etn_Pro\Utils\Helper;

defined( 'ABSPATH' ) || die();

/**
 * Stripe payment functionality
 */
class Sells_Engine {

	use \Etn\Traits\Singleton;

	/**
	 * Fire sell engine hooks.
	 */
	public function init() {
		$sells_engine = $this->check_sells_engine();

		if ( 'stripe' == $sells_engine ) {
			\Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->init();

			// sells engine field.
			add_filter( 'etn_pro/stripe/stripe_field', array( $this,'stripe_field' ) ,10 , 1 );

			add_action( 'wp_ajax_stripe_payment_transaction', [$this, 'stripe_payment_transaction'] );
			add_action( 'wp_ajax_nopriv_stripe_payment_transaction', [$this, 'stripe_payment_transaction'] );

			add_action( 'init', [ $this, 'stripe_thank_you_page_creation' ] );
			add_filter( 'page_template', [$this, 'template_file'] );

			/* Stripe emails after payment completed or declined */
			add_action( 'etn_pro/stripe/payment_completed', [$this, 'stripe_payment_confirm_email'], 10 , 2 );

			add_action( 'etn_stripe_thankyou', [ $this, 'payment_completed' ], 10, 4 );

			/* Stripe emails store in fluent CRM after completing order */
			add_action( 'etn_pro/stripe/payment_completed', [$this, 'stripe_payment_confirm_fluent_crm'], 10 , 2 );

		}

		// submit attendee form.
		$this->attendee_submit();
	}

	/**
	 * Complete payment
	 *
	 * @return  void
	 */
	public function payment_completed( $event_id, $order_id, $invoice, $payment_data ) {
		$this->stripe_db_functionalities($event_id, $order_id);

		global $wpdb;
		$table_name = ETN_EVENT_PURCHASE_HISTORY_TABLE;

		$update_where = [
			'status'  => 'Pending',
			'form_id' => $order_id,
			'post_id' => $event_id,
		];

		if ( 'succeeded' ==  $payment_data['payment_status'] ) {

			update_post_meta( $order_id, 'payment_intent', $payment_data['payment_intent'] );
			update_post_meta( $order_id, 'payment_intent_client_secret', $payment_data['payment_intent_client_secret'] );
			update_post_meta( $order_id, 'etn_payment_status', 'Completed' );

			$wpdb->update( $table_name, [ 'status' => 'Completed' ], $update_where );

			do_action( 'etn_pro/stripe/payment_completed', $event_id, $order_id );

		} else {

			$wpdb->update( $table_name, [ 'status' => 'Declined' ], $update_where );
				
			do_action( 'etn_pro/stripe/payment_declined', $event_id, $order_id);
		}
	}

	/**
	 * Stripe thank you page creation
	 *
	 * @return void
	 */
    public function stripe_thank_you_page_creation(){
        $page_id = Helper::create_page( 'etn_stripe_thank_you', '', null , "_" );

        return $page_id;
    }

	/**
	 * stripe thank you page template file content
	 *
	 * @param [type] $page_template
	 * @return void
	 */
	public function template_file( $page_template ) {

        if ( is_page( 'etn_stripe_thank_you' ) ) {
            $page_template = ETN_PRO_DIR . "/core/modules/sells-engine/stripe/payments/thank-you.php";
        }

        return $page_template;
    }

	/**
	 * Check conditions for ticket selling
	 */
	public function check_sells_engine() {
		$settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();
		$sell_tickets = ( ! empty( $settings['sell_tickets'] ) ? 'checked' : '' );
		$etn_sells_engine_stripe = ( ! empty( $settings['etn_sells_engine_stripe'] ) ? 'checked': '' );

		if( 'checked' == $etn_sells_engine_stripe && '' == $sell_tickets ) {
			$sells_engine = 'stripe';
		} elseif($sell_tickets == 'checked') {
			$sells_engine = 'woocommerce';
		} else {
			$sells_engine = '';
		}

		return $sells_engine;
	}

	/**
	 * After submit attendee , redirect to
	 * redirect to responsible page.
	 */
	public function attendee_submit() {
		$sells_engine = $this->check_sells_engine();
		switch ( $sells_engine ) {
			case 'woocommerce':
				$attendee_obj = \Etn\Core\Attendee\Hooks::instance();
				add_action( 'woocommerce_add_to_cart', array( $attendee_obj, 'add_attendee_data' ), 10 , 2 );

				break;
			case 'stripe':
				// ajax call.
				if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
					$stripe_obj = \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance();
					add_action( 'wp_ajax_insert_attendee_stripe', array( $stripe_obj, 'insert_attendee_stripe' ) );
					add_action( 'wp_ajax_nopriv_insert_attendee_stripe', array( $stripe_obj, 'insert_attendee_stripe' ) );
				}
				break;
			default:
			$attendee_obj = \Etn\Core\Attendee\Hooks::instance();
			add_action( 'woocommerce_add_to_cart', array( $attendee_obj, 'add_attendee_data' ), 10 , 2 );
			break;
		}
	}
	
	/**
	* Render extra field for stripe ticket template.
	*/
	public function stripe_field() {
			$sells_engine = \Etn_Pro\Core\Modules\Sells_Engine\Sells_Engine::instance()->check_sells_engine();

			if( 'stripe' == $sells_engine ) :
			?>
				<div class="etn-stripe-input-wrapper">
					<label for="etn-st-client-fname"><?php echo esc_html__( 'First Name' , 'eventin-pro' ); ?></label>
					<input type="text" id="etn-st-client-fname" name="client_fname" placeholder="<?php echo esc_html__( 'Enter your first name here' , 'eventin-pro' ); ?>" />
					<div class="etn-error client_fname_error"></div>
				</div>
				<div class="etn-stripe-input-wrapper">
					<label for="etn-st-client-lname"><?php echo esc_html__( 'Last Name' , 'eventin-pro' ); ?></label>
					<input type="text" id="etn-st-client-lname" name="client_lname" placeholder="<?php echo esc_html__( 'Enter your last name here' , 'eventin-pro' ); ?>" />
					<div class="etn-error client_lname_error"></div>
				</div>
				<div class="etn-stripe-input-wrapper">
					<label for="etn-st-client-email"><?php echo esc_html__( 'Email' , 'eventin-pro' ); ?></label>
					<input type="email" id="etn-st-client-email" name="client_email" placeholder="<?php echo esc_html__( 'Enter your email here' , 'eventin-pro' ); ?>" />
					<div class="etn-error client_email_error"></div>
				</div>
			<?php	endif ;?>
			<input type="hidden" name="sells_engine" value="<?php esc_html_e( $sells_engine, 'eventin-pro' );?>"/>
			<?php
	}

	/**
     * stripe_payment_transaction
     */
    public function stripe_payment_transaction() {
        $status_code  = 0;
        $messages     = [];
        $content      = [];
		$msg 		      = '';
		$post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );

		if ( wp_verify_nonce( sanitize_text_field( $post_arr['security'] ), 'stripe_payment_nonce_value' ) ) {
			$token    = !empty($post_arr['token']) ? sanitize_text_field( $post_arr['token'] ) : '';
			$event_id = !empty($post_arr['event_id']) ? absint( $post_arr['event_id'] ) : 0; 
			$order_id = !empty($post_arr['order_id']) ? absint( $post_arr['order_id'] ) : 0; 
			$sandbox  = !empty($post_arr['sandbox']) ? $post_arr['sandbox'] : 'No';
			$currency_code = !empty($post_arr['currency_code']) ? sanitize_text_field( $post_arr['currency_code'] ) : 'USD';
			$num_decimals = !empty($post_arr['num_decimals']) ? sanitize_text_field( $post_arr['num_decimals'] ) : '2';

			if( empty($token) || empty($event_id) || empty($order_id) ) {
				$msg = esc_html__('Sorry event order token mismatched, Please try again', 'eventin-pro');
			}
			else {
				$this->stripe_db_functionalities($event_id, $order_id);

				$status = get_post_meta($order_id, 'etn_payment_status', true);
				if(!in_array($status, ['Pending', 'Review'])) {
					$msg = esc_html__('Sorry wrong event transaction.', 'eventin-pro');
				}

				$order_info = \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->stripe_order_details( $order_id );

				if(!isset($order_info->status)) {
					$msg = esc_html__('Invalid payment.', 'eventin-pro');
				}

 				if(isset($order_info->status) && in_array($order_info->status, ['Completed', 'Delete', 'ReFunded'])) {
					$msg = esc_html__('Your payment already processed. [Code: ' . $order_info->status . ']', 'eventin-pro');
				}

				$settings 		= \Etn\Core\Settings\Settings::instance()->get_settings_option();
				$sandbox_enable = isset($settings['etn_stripe_test_mode']) ? $settings['etn_stripe_test_mode'] : 'No';
				$sandbox 		= ($sandbox_enable == 'on') ? true : false;

				$setup['_sandbox']  = $sandbox;
				$test_secret_key 	= isset($settings['stripe_test_secret_key']) ? $settings['stripe_test_secret_key'] : '';
				$live_secret_key  	= isset($settings['stripe_live_secret_key']) ? $settings['stripe_live_secret_key'] : '';

				$stripe_credentials_error = false;
				if ( $sandbox ) {
					if ( empty( $test_secret_key ) ) {
						$stripe_credentials_error = true;
						$msg = esc_html__('Test Secret key is empty. Please fill up', 'eventin-pro');
					}
				} else {
					if ( empty( $live_secret_key ) ) {
						$stripe_credentials_error = true;
						$msg = esc_html__('Live Secret key is empty. Please fill up', 'eventin-pro');
					}
				}

				if ( !$stripe_credentials_error ) {
					$setup['stripe_secret_key'] 	 = $live_secret_key;
					$setup['stripe_secret_key_test'] = $test_secret_key;

					$payment = \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->etn_stripe()->init($setup);

					$amount      = floatval($order_info->event_amount);
					$amount_cent = $amount * 100;

					$payment_data = [
						'token'    => $token,
						'amount'   => $amount_cent,
						'currency' => $currency_code,
						'num_decimals' => $num_decimals
					];

					$data = $payment->stripe_verify($payment_data);
					// checking stripe card payment
					if ( isset($data['status']) && $data['status'] ) {
						$response = $data['get'];
						$txn_id   = !empty($response['invoice']) ? $response['invoice'] : $response['balance_transaction'];
						update_post_meta( $order_id, 'etn_payment_token', $token );
						update_post_meta( $order_id, 'etn_payment_txn_id', $txn_id );
						update_post_meta( $order_id, 'etn_payment_txn_data', $response );
						update_post_meta( $order_id, 'etn_payment_status', 'Completed' );

						$update_where = [
							'status'  => 'Pending',
							'form_id' => $order_id,
							'post_id' => $event_id,
						];
						global $wpdb;
						$table_name = ETN_EVENT_PURCHASE_HISTORY_TABLE;
						if ( $wpdb->update( $table_name, [ 'status' => 'Completed' ], $update_where ) ) {
							$status_code = 1;
							$msg 		 = esc_html__('Thanks for your payment', 'eventin-pro');

							do_action( 'etn_pro/stripe/payment_completed', $event_id, $order_id );

							$messages[] = $msg;
							$response = [
								'status_code' => $status_code,
								'messages'    => $messages,
								'content'     => $content,
							];
							wp_send_json_success( $response );
							exit();
						}
					}
					else if( isset($data['status']) && $data['status'] == false ){
							$update_where = [
								'status'  => 'Pending',
								'form_id' => $order_id,
								'post_id' => $event_id,
							];
							global $wpdb;
							$table_name = ETN_EVENT_PURCHASE_HISTORY_TABLE;
							if ( $wpdb->update( $table_name, [ 'status' => 'Declined' ], $update_where ) ) {
								$msg 		 = sprintf( __('%s', 'eventin-pro'), $data['message']);

								do_action( 'etn_pro/stripe/payment_declined', $event_id, $order_id);

								$messages[] = $msg;
								$response = [
									'status_code' => 0,
									'messages'    => $messages,
									'content'     => $content,
								];
								wp_send_json_error( $response );
								exit();
							}
					}
				}
			}
		}

		$messages[] = $msg;
		$response = [
				'status_code' => $status_code,
				'messages'    => $messages,
				'content'     => $content,
		];
		wp_send_json_error( $response );
		exit;

      }

	/**
     * stripe_db_functionalities
	 * Send Email, update stock, update 
	 * Purchase table
     */
	public function stripe_db_functionalities( $event_id = null, $order_id = null , $manual = 0 ) {
		global $wpdb;

		$user_id = 0;
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
		}

		$etn_payment_method = 'stripe_payment';
		$status       		= $manual == 1 ? 'Completed' : 'Pending';

		$product_total      = get_post_meta( $order_id, 'etn_variation_total_price', true );
		$product_quantity   = get_post_meta( $order_id, 'etn_variation_total_quantity', true );

		$pledge_id = "";
		$insert_post_id         = $event_id;
		$insert_form_id         = $order_id;
		$insert_invoice         = get_post_meta( $order_id, '_order_key', true );
		$insert_event_amount    = Stripe::instance()->float_value( $product_total );
		$insert_ticket_qty      = $product_quantity;
		$insert_user_id         = $user_id;
		$insert_email           = get_post_meta( $order_id, '_billing_email', true );
		$insert_event_type      = "ticket";
		$insert_payment_type    = "stripe";

		$etn_ticket_variations  = get_post_meta( $order_id, 'etn_ticket_variations_picked', true );
		$insert_ticket_variation= serialize($etn_ticket_variations);

		$insert_pledge_id       = $pledge_id;
		$insert_payment_gateway = $etn_payment_method;
		$insert_date_time       = date( "Y-m-d H:i:s" );
		$insert_status          = $status;
		// insert into ETN_EVENT_PURCHASE_HISTORY_TABLE
		$inserted               = $wpdb->query( "INSERT INTO `". ETN_EVENT_PURCHASE_HISTORY_TABLE ."` (`post_id`, `form_id`, `invoice`, `event_amount`, `ticket_qty`, `ticket_variations`, `user_id`, `email`, `event_type`, `payment_type`, `pledge_id`, `payment_gateway`, `date_time`, `status`) VALUES ('$insert_post_id', '$insert_form_id', '$insert_invoice', '$insert_event_amount', '$insert_ticket_qty', '$insert_ticket_variation', '$insert_user_id', '$insert_email', '$insert_event_type', '$insert_payment_type', '$insert_pledge_id', '$insert_payment_gateway', '$insert_date_time', '$insert_status')" );
		$id_insert              = $wpdb->insert_id;

		if ( $inserted ) {
			$metaKey            = [];
			$billing_first_name = get_post_meta( $order_id, '_billing_first_name', true );
			$billing_last_name 	= get_post_meta( $order_id, '_billing_last_name', true );
			$billing_email 		= get_post_meta( $order_id, '_billing_email', true );
			$billing_country 	= get_post_meta( $order_id, '_billing_country', true );
			$order_currency 	= get_post_meta( $order_id, '_order_currency', true );
			$order_date_time 	= date( "Y-m-d H:i:s" );

			$metaKey['_etn_first_name']           = $billing_first_name;
			$metaKey['_etn_last_name']            = $billing_last_name;
			$metaKey['_etn_email']                = $billing_email;
			$metaKey['_etn_post_id']              = $event_id;
			$metaKey['_etn_order_key']            = '_etn_' . $id_insert;
			$metaKey['_etn_order_shipping']       = get_post_meta( $order_id, '_order_shipping', true );
			$metaKey['_etn_order_shipping_tax']   = get_post_meta( $order_id, '_order_shipping_tax', true );
			$metaKey['_etn_order_qty']            = $product_quantity;
			$metaKey['_etn_order_total']          = $product_total;
			$metaKey['_etn_order_tax']            = get_post_meta( $order_id, '_order_tax', true );
			$metaKey['_etn_addition_fees']        = 0;
			$metaKey['_etn_addition_fees_amount'] = 0;
			$metaKey['_etn_addition_fees_type']   = '';
			$metaKey['_etn_country']              = $billing_country;
			$metaKey['_etn_currency']             = $order_currency;
			$metaKey['_etn_date_time']            = $order_date_time;

			// insert into ETN_EVENT_PURCHASE_HISTORY_META_TABLE
			foreach ( $metaKey as $k => $v ) {
				$data               = [];
				$data["event_id"]   = $id_insert;
				$data["meta_key"]   = $k;
				$data["meta_value"] = $v;
				$wpdb->insert( ETN_EVENT_PURCHASE_HISTORY_META_TABLE, $data );
			}

			// stock update
			$ticket_variations              = !empty( get_post_meta( $event_id, "etn_ticket_variations", true ) ) ? get_post_meta( $event_id, "etn_ticket_variations", true ) : [];
			$item_variations                = $etn_ticket_variations;
			$variation_picked_total_qty     = $product_quantity;
			// update stock for a event
			$etn_total_sold_tickets = absint( get_post_meta( $event_id, "etn_total_sold_tickets", true ) );
			$updated_total_sold_tickets = $etn_total_sold_tickets + $variation_picked_total_qty;
			// update stock for a ticket

			if ( is_array( $item_variations ) && !empty( $item_variations ) ) {
				foreach ( $item_variations as $item_index => $item_variation ) {
					$ticket_index = $this->search_array_by_value( $ticket_variations, $item_variation['etn_ticket_slug'] );
					
					if ( isset( $ticket_variations[ $ticket_index ] ) ) {
						$variation_picked_qty   = absint( $item_variation[ 'etn_ticket_qty' ] );
						$etn_sold_tickets       = absint( $ticket_variations[ $ticket_index ]['etn_sold_tickets'] );
						$total_tickets          = absint( $ticket_variations[ $ticket_index ]['etn_avaiilable_tickets'] );
						
						$updated_sold_tickets   = $etn_sold_tickets + $variation_picked_qty;
						if ( $updated_sold_tickets <= $total_tickets && $updated_sold_tickets >= 0 ) {
							$ticket_variations[ $ticket_index ]['etn_sold_tickets'] = $updated_sold_tickets;
						}
					}
				}

				update_post_meta( $event_id, "etn_ticket_variations", $ticket_variations );
				update_post_meta( $event_id, "etn_total_sold_tickets", $updated_total_sold_tickets );
			}
		}

    }

	/**
	 * invoice email for site admin and purchaser
	 */
	public function process_order_email( $for = 'admin', $order_data = [] ) {
		$billing_info = $order_data['billing_info'];
		$order_id 	  = $order_data['order_id'];
        ob_start();
        ?>
		<?php if ( $for == 'admin' ) { ?>
			<div>
				<?php echo esc_html( $order_data['title'] ) . ': #' . $order_id; ?>
			</div><br>
			<div>
				<?php echo esc_html__( "You've received the following order from ", 'eventin-pro' ) . $billing_info['first_name']; ?>
			</div>
			<div>
				<?php echo '[' . esc_html__( 'Order #', 'eventin-pro' ) . $order_id . ']' . '(' . date("F d, Y", strtotime( $order_data['order_date_time'] ) ) . ')'; ?>
			</div><br>
		<?php } else { ?>
			<div>
				<?php echo esc_html__( 'Thank you for your order', 'eventin-pro' ); ?>
			</div><br>
			<div>
				<?php echo esc_html__( 'Hi ', 'eventin-pro' ) . $billing_info['first_name'] . $billing_info['last_name']; ?>
			</div><br>
			<div>
				<?php echo esc_html__( "Just to let you know — we've received your order #", 'eventin-pro' ) . $order_id; ?>
			</div><br>
		<?php } ?>

		<?php
		$order    = \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->stripe_order_details( $order_id );
		$manual_attendee   = get_post_meta( $order_id ,'_manual_attendee',true);
		$ticket_details    = esc_html__( 'Ticket Details', 'eventin-pro' ) . '<br>';
		$total    = $order->event_amount;
		$currency = $order_data['currency'];
		if ( "" == $manual_attendee ) {
			$variation_details = maybe_unserialize( $order->ticket_variations );
			foreach( $variation_details as $single_variation ) {
				if ( absint( $single_variation['etn_ticket_qty'] ) > 0) {
					$ticket_details .= esc_html( $single_variation['etn_ticket_name'] . '*' . $single_variation['etn_ticket_qty'] ) . '<br>';
				}
			}
		} else {
			$ticket_details = \Etn_Pro\Core\Attendee\Hooks::instance()->attendee_meta_by_order( $order_id );
		}
		
		?>
		<div class="etn-table-container">
			<table class="form-table etn-table-design etn-order-details">
				<thead>
					<tr>
						<th class="product-name"><strong><?php echo esc_html__('Product', 'eventin-pro'); ?></strong></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$product_name   = get_the_title( $order->post_id );
					$product_amount = $total;
					$sub_total      = $total;
					$total          = $total;
					?>
					<tr><br>
						<td>
							<?php echo esc_html($product_name); ?><strong> × 1</strong><br>
							<?php echo $ticket_details; ?>
						</td>
						<!-- <td><?php echo esc_html( $product_amount ) . ' ' . esc_html( $currency ); ?></td> -->
					</tr><br>
					<tr>
						<td>
							<strong><?php echo esc_html__('Subtotal:', 'eventin-pro'); ?></strong>
						</td>
						<td><?php echo esc_html( $sub_total ) . ' ' . esc_html( $currency ); ?></td>
					</tr>
					<tr><br>
						<td>
							<strong><?php echo esc_html__('Payment method:', 'eventin-pro'); ?></strong>
						</td>
						<td>
							<?php echo esc_html__('Stripe', 'eventin-pro'); ?>
						</td>
					</tr><br>
					<tr>
						<td>
							<strong><?php echo esc_html__('Total:', 'eventin-pro'); ?></strong>
						</td>
						<td><?php echo esc_html( $total ) . ' ' . esc_html( $currency ); ?></td>
					</tr>
				</tbody>
			</table>
		</div><br>
		<div>
			<b><?php echo esc_html__('Billing address', 'eventin-pro'); ?></b><br>
			<?php echo esc_html( $billing_info['first_name'] ) ?><br>
			<?php echo esc_html( $billing_info['email'] ) ?>
		</div>
		<?php

		$mail_content = ob_get_clean();
		$mail_content = Helper::kses( $mail_content );

		$settings_options = Helper::get_settings();
		if ( !empty( $settings_options['admin_mail_address'] ) ) {
				$site_title = esc_html( get_bloginfo( 'name' ) );

				if ( $for == 'admin' ) {
					$subject   = '['. $site_title .']: ' . esc_html( $order_data['title'] ) . ': #' . $order_id;
					$to        = $settings_options['admin_mail_address'];
				} else {
					$subject   = esc_html__( 'Your ', 'eventin-pro' ) . $site_title . esc_html__( ' order has been received!', 'eventin-pro' );
					$to        = $billing_info['email'];
				}

				$from      = $settings_options['admin_mail_address'];
				$from_name = Helper::retrieve_mail_from_name();

				$proceed_ticket_mail = true;
				$order_status = strtolower( $order_data['status'] );
				if ( !( $order_status == 'pending' || $order_status == 'completed' ) ) {
						$proceed_ticket_mail = false;
				}

				if ( $proceed_ticket_mail ) {
						Helper::send_email( $to, $subject, $mail_content, $from, $from_name );
				}

		}

    }


	/**
    * Get ticket info by slug
    */
    public function search_array_by_value($meta_data,$slug){

        $result_key = null;
        if ( count( $meta_data )> 0 ) {
            foreach ($meta_data as $key => $value) {
                if ( $value['etn_ticket_slug'] == $slug ) {
                    $result_key = $key;
                }
            }
        }

        return $result_key;
    }

	/**
    * Fire email sending functionality after successfull order completion 
    */
    public function stripe_payment_confirm_email($event_id, $order_id){

		// attach order id to attendees
		$settings               = Helper::get_settings();
		$attendee_reg_enable    = !empty( $settings["attendee_registration"] ) ? true : false;
		if( $attendee_reg_enable ) {
			$etn_status_update_key   = get_post_meta( $order_id, 'etn_status_update_key', true );

			// update attendee status and send ticket to email
			$event_location   = !is_null( get_post_meta( $event_id , 'etn_event_location', true ) ) ? get_post_meta( $event_id , 'etn_event_location', true ) : "";
			$etn_ticket_price = !is_null( get_post_meta( $event_id , 'etn_ticket_price', true ) ) ? get_post_meta( $event_id , 'etn_ticket_price', true ) : "";
			$etn_start_date   = !is_null( get_post_meta( $event_id , 'etn_start_date', true ) ) ? get_post_meta( $event_id , 'etn_start_date', true ) : "";
			$etn_end_date     = !is_null( get_post_meta( $event_id , 'etn_end_date', true ) ) ? get_post_meta( $event_id , 'etn_end_date', true ) : "";
			$etn_start_time   = !is_null( get_post_meta( $event_id , 'etn_start_time', true ) ) ? get_post_meta( $event_id , 'etn_start_time', true ) : "";
			$etn_end_time     = !is_null( get_post_meta( $event_id , 'etn_end_time', true ) ) ? get_post_meta( $event_id , 'etn_end_time', true ) : "";
			$update_key       = $etn_status_update_key;
			$insert_email     = !is_null( get_post_meta( $order_id, '_billing_email', true ) ) ? get_post_meta( $order_id, '_billing_email', true ) : "";

			$pdf_data = [
				'order_id'          => $order_id,
				'event_name'        => get_the_title( $event_id ) ,
				'event_id'          => $event_id ,
				'update_key'        => $update_key ,
				'user_email'        => $insert_email ,
				'event_location'    => $event_location ,
				'etn_ticket_price'  => $etn_ticket_price,
				'etn_start_date'    => $etn_start_date,
				'etn_end_date'      => $etn_end_date,
				'etn_start_time'    => $etn_start_time,
				'etn_end_time'      => $etn_end_time
			];

			// mail functionalities
			Helper::mail_attendee_report( $pdf_data, true, true, 'stripe' );

			$billing_first_name = get_post_meta( $order_id, '_billing_first_name', true );
			$billing_last_name 	= get_post_meta( $order_id, '_billing_last_name', true );
			$billing_email 		= get_post_meta( $order_id, '_billing_email', true );
			$order_date_time 	= date( "Y-m-d H:i:s" );
			$order_currency 	= get_post_meta( $order_id, '_order_currency', true );

			$order_data = [
				'title' 	=> esc_html__( 'New order', 'eventin-pro' ),
				'order_id' 	=> $order_id,
				'status' 	=> 'completed',
				'billing_info' => [
					'first_name' 	=> $billing_first_name,
					'last_name' 	=> $billing_last_name,
					'email' 		=> $billing_email,
				],
				'order_date_time' 	=> $order_date_time,
				'currency' 			=> $order_currency,
			];
			$this->process_order_email( 'admin', $order_data );
			$this->process_order_email( 'purchaser', $order_data );

			// Allow code execution only once
			if ( !get_post_meta( $order_id, 'etn_attendee_ticket_email_sent_on_order_placement', true ) ){ 
				// call function to send email
				Helper::mail_attendee_report( $pdf_data, false, false, 'stripe' );
				update_post_meta( $order_id, 'etn_attendee_ticket_email_sent_on_order_placement', true );
			}

		}
    }

	/**
    * Fluent CRM data store after completing order
    */
	public function stripe_payment_confirm_fluent_crm ( $event_id, $order_id ) {

		$settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();

		if ( !$order_id || !$event_id ) {
			return;
		}

		$data               = [];
		$data['email']      = !empty( get_post_meta( $order_id, '_billing_email', true ) ) ? get_post_meta( $order_id, '_billing_email', true ) : "";
		$data['fname']      = !empty( get_post_meta( $order_id, '_billing_first_name', true ) ) ? get_post_meta( $order_id, '_billing_first_name', true ) : "";
		$data['lname']      = !empty( get_post_meta( $order_id, '_billing_last_name', true ) ) ? get_post_meta( $order_id, '_billing_last_name', true ) : "";

		$event_object = get_post( $event_id );

		if ( !empty( $event_object->post_type ) && ( 'etn' == $event_object->post_type ) ) {

            $event_id             = $event_object->ID;
            $fluent_crm_enabled   = get_post_meta( $event_id, 'fluent_crm', true );
            
            if( $fluent_crm_enabled == "yes" ){
                $fluent_crm_webhook = get_post_meta( $event_id, 'fluent_crm_webhook', true );
                
                if( !empty( $fluent_crm_webhook ) ) {

                    try {
                        $this->stripe_etn_send_fluent_crm_data( $fluent_crm_webhook, $data );
                    } catch( \Exception $ex) {
                    }

                    $attendee_reg_enable = !empty( $settings["attendee_registration"] ) ? true : false;
                    $reg_require_email   = !empty( $settings["reg_require_email"] ) ? true : false;

                    if( $attendee_reg_enable && $reg_require_email ){ 
                        $args = array(
                        'post_type' => 'etn-attendee',
                        'post_status' => 'publish',
                        'numberposts'   => -1,
                        'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key'   => 'etn_attendee_order_id',
                                    'value' => $order_id,
                                ),
                                array(
                                    'key'     => 'etn_event_id',
                                    'value' => $event_id,
                                ),
                            ),                       
                        );

                        $attendees = get_posts($args);
        
                        try {
                            foreach( $attendees as $attendee) {
                                $attendee_email = get_post_meta($attendee->ID, 'etn_email',true);
                                $etn_name = get_post_meta($attendee->ID, 'etn_name',true);
            
                                $body = array(
                                    'email' => $attendee_email,
                                    'first_name' => $etn_name,
                                );
                            
                                wp_remote_post($fluent_crm_webhook, ['body' => $body]);
            
                            }
                        } catch( \Exception $ex) {
                        }   
                    } 
                }
            }

        }

	}

	public function stripe_etn_send_fluent_crm_data( $url, $data ) {

		$data = [
			'email'      => isset($data['email']) ? $data['email'] : '',
			'first_name' => isset($data['fname']) ? $data['fname'] : '',
			'last_name'  => isset($data['lname']) ? $data['lname'] : '',
		];
	
		$response = wp_remote_post($url, ['body' => $data]);
	
		return $response;
	}

}
