<?php

	namespace Etn_Pro\Core\Modules\Integrations\Google_Meet\Frontend;
	use Etn\Utils\Helper;

	defined( 'ABSPATH' ) || die();

	class Frontend {

		use \Etn_Pro\Traits\Singleton;

		public function init() {
			// enqueue scripts
			$this->enqueue_scripts();

			add_action( 'after_ticket_purchase_option_details', array( $this, 'googl_meet_message_show_ticket' ), 5 );
			add_action( 'woocommerce_order_status_changed', [$this, 'email_google_meet_event_details_on_order_status_update' ], 10, 3 );
			add_action( 'etn_pro/stripe/payment_completed', [$this, 'email_google_meet_event_details_on_stripe_payment'], 10 , 2 );

		}

		/**
		 * Enqueue scripts.
		 */
		public function enqueue_scripts() {
			add_action( 'wp_enqueue_scripts', array( $this, 'js_css_public' ) );
		}

		/**
		 *  Frontend scripts.
		 */
		public function js_css_public() {
			// Main script of google meet script and js
			wp_enqueue_script( 'etn-google-public', ETN_PRO_CORE . 'modules/integrations/google-meet/assets/js/etn-google-meet.js', ['jquery'], \Wpeventin_Pro::version(), false );
		}

		/**
		 * show message in the ticket purchase area
		 */

		public function googl_meet_message_show_ticket() {
			$is_google_meet_event = get_post_meta( get_the_ID(), 'etn_google_meet', true );
			if ( isset( $is_google_meet_event ) && ( "on" == $is_google_meet_event ||  "yes" == $is_google_meet_event  ) ) {
				?>
				<div class="etn-zoom-event-notice etn-google-meet-event-notice">
					<?php echo esc_html__( '[Note: This event will be held on Google Meet. Attendee will get Google Meet URL through email]', 'eventin-pro' ); ?>
				</div>
				<?php
			}
		}

		/**
		 * prepare google meet email details
		 *
		 * @param [int] $order_id
		 * @param [string] $mail_body_content
		 *
		 * @return void
		 */
		public static function google_meet_email_event_details( $order_id = null, $mail_body_content = '' ) {
			ob_start();
			?>
			<div>
				<?php echo esc_html__( "Your order no: {$order_id} includes Event(s) which will be hosted on Google Meet. Google Meet details are as follows. ", 'eventin-pro' ); ?>
			</div>
			<br><br>
			<?php
			$mail_body_header = ob_get_clean();
			$mail_body        = $mail_body_header . $mail_body_content;
			$subject          = esc_html__( 'Event Google Meet details', "eventin-pro" );
			$from             = Helper::get_settings()['admin_mail_address'];
			$from_name        = Helper::retrieve_mail_from_name();
			$sells_engine 	  = Helper::check_sells_engine();

			if('woocommerce' === $sells_engine) {
				$order 			  = wc_get_order( $order_id );
				$to 			  = !empty($order) ? $order->get_billing_email() : "";
			} else {
				$to = !empty( get_post_meta( $order_id, '_billing_email', true ) ) ? get_post_meta( $order_id, '_billing_email', true ) : "";
			}

			Helper::send_email( $to, $subject, $mail_body, $from, $from_name );
			update_post_meta( $order_id, 'etn_google_meet_email_sent', 'yes' );
		}

		/**
		 * Send Email With Google Meet Details
		 *
		 * @param [type] $order_id
		 *
		 * @since 2.4.1
		 *
		 * @return void
		 */
		public function send_email_with_google_meet_details( $order_id, $report_event_id = null, $gateway = 'woocommerce' ) {

			if ( $gateway === 'woocommerce' ) {
				$order = wc_get_order( $order_id );

				foreach ( $order->get_items() as $item_id => $item ) {
					$product_name = $item->get_name();
					$event_id = !is_null( $item->get_meta( 'event_id', true ) ) ? $item->get_meta( 'event_id', true ) : "";
		
					if ( !empty( $event_id ) ) {
						$product_post = get_post( $event_id );
					} else {
						$array_of_objects = get_posts(
							[
								'title' => $product_name,
								'post_type' => 'etn'
							]
						);
		
						if( !empty($array_of_objects ) ){
							$product_post = get_post( $array_of_objects[0]->ID );
						}
					}
		
					if (  ( empty( $report_event_id ) && !empty( $product_post ) ) || ( !empty( $product_post ) && !empty( $event_id ) && ( $report_event_id == $product_post->ID ) ) ) {
						$event_id = $product_post->ID;
					}
					
					$is_google_meet_event = $this->check_if_google_meet_event( $event_id );
		
					if ( $is_google_meet_event ) {
						$mail_body_content = $this->google_meet_mail_body_content($event_id, '');
						$this->google_meet_email_event_details($order_id, $mail_body_content);
					}

				}  
			} else {
				$event_id = $report_event_id;
				$is_google_meet_event = $this->check_if_google_meet_event( $event_id );
		
				if ( $is_google_meet_event ) {
					$mail_body_content = $this->google_meet_mail_body_content($event_id, '');
					$this->google_meet_email_event_details($order_id, $mail_body_content);
				}
			}
			return;
		}
		

		/**
		 * prepare Google meet email body
		 *
		 * @param [int] $event_id
		 * @param [string] $mail_body_content
		 *
		 * @return string
		 */
		public function google_meet_mail_body_content( $event_id = null, $mail_body_content = '') {

			// $google_meet_url   = get_post_meta( $event_id, 'etn_google_meet_link', true ); // We don't have data in this metabox. 
			$event_name        = get_the_title( $event_id );
			$event_link        = ( Helper::is_recurrence( $event_id ) ) ? get_the_permalink( wp_get_post_parent_id( $event_id ) ) : get_the_permalink( $event_id );
			$google_meet_data  = get_post_meta( $event_id, 'google_calendar_event_data', true );
			$google_meet_url   = ( !empty( $google_meet_data['hangoutLink'] ) ) ? $google_meet_data['hangoutLink'] : '';
			$date_options      = Helper::get_date_formats();
			$event_options     = get_option( "etn_event_options" );
			$etn_start_date    = strtotime( get_post_meta( $event_id, 'etn_start_date', true ) );
			$etn_start_time    = strtotime( get_post_meta( $event_id, 'etn_start_time', true ) );
			$etn_end_date      = strtotime( get_post_meta( $event_id, 'etn_end_date', true ) );
			$etn_end_time      = strtotime( get_post_meta( $event_id, 'etn_end_time', true ) );
			$event_time_format = empty( $event_options["time_format"] ) ? '12' : $event_options["time_format"];
			$event_start_date  = ( isset( $event_options["date_format"] ) && $event_options["date_format"] !== '' ) ? date_i18n( $date_options[$event_options["date_format"]], $etn_start_date ) : date_i18n( get_option( 'date_format' ), $etn_start_date );
			$event_start_time  = ( $event_time_format == "24" || $event_time_format == "" ) ? date_i18n( 'H:i', $etn_start_time ) : date_i18n( get_option( 'time_format' ), $etn_start_time );
			$event_end_time    = ( $event_time_format == "24" || $event_time_format == "" ) ? date_i18n( 'H:i', $etn_end_time ) : date_i18n( get_option( 'time_format' ), $etn_end_time );
			$event_end_date    = '';

			if ( $etn_end_date ) {
				$event_end_date = isset( $event_options["date_format"] ) && ( "" != $event_options["date_format"] ) ? date_i18n( $date_options[$event_options["date_format"]], $etn_end_date ) : date_i18n( get_option( 'date_format' ), $etn_end_date );
			}

			ob_start();
			?>
			<div class="etn-invoice-google-meet-event">
				<span class="etn-invoice-google-meet-event-title">
					<?php echo esc_html( $event_name ); ?>
				</span>
				<div class="etn-invoice-google-meet-event-details">
					<?php
					if ( !empty( Helper::get_option( 'invoice_include_event_details' ) ) ) {
						?>
						<div class="etn-invoice-email-event-meta">
							<div>
								<?php echo esc_html__( 'Event Page: ', 'eventin-pro' ); ?>
								<a href="<?php echo esc_url( $event_link ); ?>"><?php echo esc_html__( 'Click here. ', 'eventin-pro' ); ?></a>
							</div>
							<div><?php echo esc_html__( 'Start: ', 'eventin-pro' ) . $event_start_date . " " . $event_start_time; ?></div>
							<div><?php echo esc_html__( 'End: ', 'eventin-pro' ) . $event_end_date . " " . $event_end_time; ?></div>
						</div>
						<?php
					}
					?>

					<div class="etn-google-meet-url">
						<span><?php echo esc_html__( 'Google Meet URL: ', 'eventin-pro' ); ?></span>
						<a target="_blank" href="<?php echo esc_url( $google_meet_url ); ?>">
							<?php echo esc_html__( 'Click to join the Google Meet', 'eventin-pro' ); ?>
						</a>
					</div>
				</div>
			</div>

			<?php
			$google_meet_details = ob_get_clean();
			$mail_body_content .= $google_meet_details;
			
			return $mail_body_content;
		}

		/**
		 * Send Google Meet Event Details on Status Change
		 *
		 * @param [type] $order_id
		 * @param [type] $old_order_status
		 * @param [type] $new_order_status
		 * @return void
		 */
		public function email_google_meet_event_details_on_order_status_update(  $order_id, $old_order_status, $new_order_status ) {
			$parent_id = wp_get_post_parent_id( $order_id );
			if ( ! empty( $parent_id ) ) {
				return;
			}

			$payment_success_status_array = [
				// 'pending', 'on-hold', 'cancelled','refunded', 'failed',
				'processing',
				'completed',
				'partial-payment',
			];

			$google_meet_email_sent = $this->check_if_google_meet_email_sent_for_order( $order_id );

			if( !$google_meet_email_sent && in_array($new_order_status, $payment_success_status_array)){
				//email not sent yet and order order status is paid, so proceed..
				$this->send_email_with_google_meet_details( $order_id );
			}
		}


		/**
		 * Send Google Meet Event Details on Stripe Payment
		 *
		 * @param [type] $event_id
		 * @param [type] $order_id
		 * @return void
		 */
		public function email_google_meet_event_details_on_stripe_payment( $event_id, $order_id ) {
			$this->send_email_with_google_meet_details( $order_id, $event_id, 'stripe' );
		}

		/**
		 * Check If Google Meet Details Email Sent Already
		 *
		 * @param [type] $order_id
		 *
		 * @since 2.4.1
		 *
		 * @return bool
		 */
		public function check_if_google_meet_email_sent_for_order( $order_id ) {

			$is_email_sent = ( !empty( get_post_meta( $order_id, 'etn_google_meet_email_sent', true ) ) && 'yes' === get_post_meta( $order_id, 'etn_google_meet_email_sent', true ) ) ? true : false;
			return $is_email_sent;
		}

		/**
		 * Check If Google Meet Event
		 *
		 * @since 2.4.1
		 *
		 * @return bool
		 *
		 * check if a provided event id is google meet event
		 */
		public function check_if_google_meet_event( $event_id ) {
			$is_google_meet_event = get_post_meta( $event_id, 'etn_google_meet', true );

			if ( isset( $is_google_meet_event ) && ( "on" == $is_google_meet_event ||  "yes" == $is_google_meet_event  ) ) {
				return true;
			}

			return false;
		}

	}
