<?php

namespace Etn_Pro\Core\Event;

use Etn_Pro\Utils\Helper;
use Etn\Core\Settings\Settings as SettingsFree;

defined( 'ABSPATH' ) || exit;

class Event {

	use \Etn\Traits\Singleton;

	/**
	 * Call hooks
	 */
	public function init() {
		//Add column
		add_filter( 'manage_etn_posts_columns', [ $this, 'attende_header_column' ] );

		// Add the data to the custom columns for the etn post type:
		add_action( 'manage_etn_posts_custom_column', [ $this, 'custom_attende_column' ], 10, 2 );

		// filter query for past/present/upcoming
		add_action( 'etn/event_filter', [ $this, 'event_type_filter' ], 10, 1 );

		add_filter( 'etn/event_parse_query', [ $this, 'event_filter_request_query' ], 10, 2 );

		// add email remainder.
		\Etn_Pro\Core\Event\Cron::instance()->init();

		// create event location taxonomy.
		\Etn_Pro\Core\Event\Event_Location::instance();

		// check permission for manage user.
		add_action( 'admin_menu', [ $this, 'admin_attendee_menu' ], 102 );

		//  remove date column
		add_action( 'admin_init', [ $this, 'remove_column_init' ] );

		add_action( 'wp_ajax_send_attendee_certificates', [ $this, 'send_attendee_certificates' ] );
		add_action( 'wp_ajax_nopriv_send_attendee_certificates', [ $this, 'send_attendee_certificates' ] );

		$this->initialize_template_hooks();

		// include API for events
		include_once \Wpeventin_Pro::core_dir() . 'event/api-event.php';

		// include API for events
		include_once \Wpeventin_Pro::core_dir() . 'event/api-event-taxonomy.php';

		// include API for events
		include_once \Wpeventin_Pro::core_dir() . 'event/api-speaker-taxonomy.php';

		// include API for events
		include_once \Wpeventin_Pro::core_dir() . 'event/api-schedules.php';

		// include API for locations
		include_once \Wpeventin_Pro::core_dir() . 'event/api-location-taxonomy.php';

		// include API for tags
		include_once \Wpeventin_Pro::core_dir() . 'event/api-event-tags.php';
	}

	/**
	 * Event Template Hooks
	 *
	 * @return void
	 */
	public function initialize_template_hooks() {
		include_once ETN_PRO_DIR . '/core/event/template-hooks.php';
		include_once ETN_PRO_DIR . '/core/event/template-functions.php';
	}

	/**
	 * Fire hook for removing column
	 */
	public function remove_column_init() {
		add_filter( 'manage_etn_posts_columns', [ $this, 'remove_comments_column' ] );
	}

	/**
	 * Remove date column
	 */
	public function remove_comments_column( $columns ) {
		unset( $columns['date'] );

		return $columns;
	}

	/**
	 * Override Dashboard Column
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function attende_header_column( $columns ) {
		$columns['status']      = esc_html__( 'Status', 'eventin-pro' );
		$columns['seats']       = esc_html__( 'Seats', 'eventin-pro' );
		$columns['attendee']    = esc_html__( 'Report', 'eventin-pro' );
		$columns['scan_ticket'] = esc_html__( 'Ticket Scanner', 'eventin-pro' );
		if ( ! empty( Helper::get_settings()["attendee_registration"] ) ) {
			$columns['certificate'] = esc_html__( 'Certificate', 'eventin-pro' );
		}

		return $columns;
	}

	/**
	 * Render Event Column Data
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function custom_attende_column( $column, $post_id ) {
		if ( class_exists( 'SitePress' ) ) {
			$post_id = \SitePress::get_original_element_id( $post_id, 'post_etn' );
		}

		$is_recurring_parent = Helper::get_child_events( $post_id );
		switch ( $column ) {
			case 'attendee':
				if ( ! $is_recurring_parent ) {
					$url = admin_url( 'admin.php?page=etn_sales_report&event_id=' . $post_id );
					?>
                    <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html__( 'Purchase report', 'eventin-pro' ) ?></a>
					<?php
				} else {
					?>
                    <div class='etn-event-report-view-event'>
                        <div class="etn-btn-text btn-primary"><?php echo esc_html__( 'Not Available', 'eventin-pro' ); ?></div>
                    </div>
					<?php
				}
				break;
			case 'seats':
				if ( ! $is_recurring_parent ) {
					$total_ticket_count      = $this->get_event_total_ticket_count( $post_id );
					$total_sold_ticket_count = $this->get_event_sold_ticket_count( $post_id );
					echo esc_html( $total_sold_ticket_count . " / " . $total_ticket_count );
				} else {
					?>
                    <div class='etn-event-report-view-event'>
                        <div class="etn-btn-text btn-primary"><?php echo esc_html__( 'Not Available', 'eventin-pro' ); ?></div>
                    </div>
					<?php
				}
				break;
			case 'certificate':
				$has_certificate_page = ! empty( get_post_meta( $post_id, 'etn_event_certificate', true ) ) ? intval( get_post_meta( $post_id, 'etn_event_certificate', true ) ) : false;

				if ( ! $is_recurring_parent && false !== $has_certificate_page ) {
					$button = sprintf( '<button class="etn-btn etn-generate-certificate" data-event-id="%1$s">Send</button><span class="etn-generate-certificate-response"></span>', $post_id );
					printf( $button );
				}
				break;

			case 'scan_ticket':
				$event_status			= $this->etn_get_event_status( $post_id, false );

				if ( 'Expired' === $event_status ) {
					echo esc_html( 'Expired', 'eventin-pro' );
				} else {
					printf( '<a href="%1$s" target="_blank" >%2$s</a>', admin_url( 'edit.php?post_type=etn-attendee&etn_action=ticket_scanner&event_id=' . $post_id ), __( 'Scan Ticket', 'eventin-pro' ) );
				}

				break;

			case 'status':
				$current_status = $this->etn_get_event_status( $post_id, true );
				echo esc_html( $current_status );
				break;

		}
	}

	/**
	 * Create attende list page function
	 */
	public function admin_attendee_menu() {

		if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) || current_user_can( 'manage_etn_event' ) ) {
			add_submenu_page(
				'etn-events-manager',
				esc_html__( 'Event Purchase Report', 'eventin-pro' ),
				esc_html__( 'Purchase Report', 'eventin-pro' ),
				'read',
				'etn_sales_report',
				[ $this, 'sales_report' ],
				3
			);
		}

		$payment_gateway = Helper::retrieve_payment_gateway();
		if ( $payment_gateway == 'stripe' ) {
			if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) || current_user_can( 'manage_etn_event' ) ) {
				add_submenu_page(
					'etn-events-manager',
					esc_html__( 'Stripe Orders', 'eventin-pro' ),
					esc_html__( 'Stripe Orders', 'eventin-pro' ),
					'manage_options',
					'etn_stripe_orders_report',
					[ $this, 'stripe_orders_report' ],
					3
				);
			}
		}
	}

	/**
	 * show stripe order report
	 *
	 * @return void
	 */
	public function stripe_orders_report() {
		if ( isset( $_GET['order_id'] ) && absint( $_GET['order_id'] ) > 0 ) {
			if ( file_exists( ETN_PRO_DIR . "/core/modules/sells-engine/stripe/orders/order-details.php" ) ) {
				include_once ETN_PRO_DIR . "/core/modules/sells-engine/stripe/orders/order-details.php";
			}
		} else {
			?>
            <div class="wrap etn-stripe-orders-report">
                <h3><?php echo esc_html__( 'Orders Report', 'eventin-pro' ); ?></h3>
                <form method="POST">
					<?php
					$orders_reports = array(
						'singular_name' => esc_html__( 'Orders Report', 'eventin-pro' ),
						'plural_name'   => esc_html__( 'Orders Reports', 'eventin-pro' ),
						'name'          => 'etn_category',
					);

					$table = new \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Orders\Orders_Report( $orders_reports );
					$table->preparing_items();
					$table->display();
					?>
                </form>
            </div>
			<?php
		}

	}

	/**
	 * Show purchase report function
	 *
	 * @return void
	 */
	public function sales_report() {
		$id = isset( $_GET['event_id'] ) ? intval( $_GET['event_id'] ) : 0;
		if ( isset( $_GET['event_id'] ) && intval( $id ) !== 0 ) {
			$count_attendee = \Etn_Pro\Core\Action::instance()->total_attendee( $id );
			if ( (int) $count_attendee > 0 ) {
				?>
                <p>
                    <a href="<?php echo admin_url( 'admin.php?page=etn_sales_report' ) ?>&action=etn_pro_download_report_attendee&attendee_event_id=<?php echo esc_attr( $id ); ?>&_wpnonce=<?php echo wp_create_nonce( 'etn_pro_download_report_attendee' ) ?>"
                       class="etn-btn etn-btn-primary button-large">
						<?php echo esc_html__( 'Export to CSV', 'eventin-pro' ); ?>
                    </a>
                </p>
				<?php
			}
			$event_name            = get_the_title( $id );
			$etn_pro_attendee_list = array(
				'singular_name' => esc_html__( 'Purchase list', 'eventin-pro' ),
				'plural_name'   => esc_html__( 'Purchase lists', 'eventin-pro' ),
				'event_id'      => $id,
			);
			?>
            <h1 class="wp-heading-inline"><?php echo esc_html__( 'Purchase report of ' . $event_name . '', 'eventin-pro' ) ?></h1>
            <div class="wrap etn-atendee-list-report">
                <form method="POST">
					<?php
					$table = new \Etn_Pro\Core\Event\Sales\Attendee_Sales_Report( $etn_pro_attendee_list );
					$table->preparing_items();
					$table->display();
					?>
                </form>
            </div>
			<?php
		} elseif ( isset( $_GET['action'] ) && sanitize_text_field( $_GET['action'] ) == 'etn_pro_download_report_attendee' ) {
			// Add action hook only if action=etn_pro_download_report_attendee
			$event_id = intval( $_GET['attendee_event_id'] );
			\Etn_Pro\Core\Action::instance()->csv_export_attendee_report( $event_id );

		} else {
			$purchase_summary = \Etn_Pro\Core\Action::instance()->purchase_summary();
			if ( ! empty( $purchase_summary ) ) {
				?>
                <!-- event sales report header -->
                <div class="event-sales-report-head">
                    <div class="attr-row">
                        <div class="attr-col-md-4">
                            <div class="sales-report-head-item">
                                <div class="sales-report-icon">
                                    <img src="<?php echo esc_url( ETN_PRO_ASSETS . 'images/icon1.png' ); ?>"
                                         alt="<?php echo esc_attr__( 'report settings icon', 'eventin-pro' ); ?>">
                                </div>
                                <h4 class="sales-info">
									<?php echo esc_html__( 'Total Events', 'eventin-pro' ); ?>
                                    <span class="total-count"><?php echo esc_html( $purchase_summary['events'] ) ?></span>
                                </h4>
                            </div>
                        </div>
                        <div class="attr-col-md-4">
                            <div class="sales-report-head-item report-head2">
                                <div class="sales-report-icon">
                                    <img src="<?php echo esc_url( ETN_PRO_ASSETS . 'images/icon2.png' ); ?>"
                                         alt="<?php echo esc_attr__( 'report settings icon', 'eventin-pro' ); ?>">
                                </div>
                                <h4 class="sales-info">
									<?php echo esc_html__( 'Total Sold Tickets', 'eventin-pro' ); ?>
                                    <span class="total-count"><?php echo esc_html( $purchase_summary['sale_tickets'] ) ?></span>
                                </h4>
                            </div>
                        </div>
                        <div class="attr-col-md-4">
                            <div class="sales-report-head-item report-head3">
                                <div class="sales-report-icon">
                                    <img src="<?php echo esc_url( ETN_PRO_ASSETS . 'images/icon3.png' ); ?>"
                                         alt="<?php echo esc_attr__( 'report settings icon', 'eventin-pro' ); ?>">
                                </div>
                                <h4 class="sales-info">
									<?php echo esc_html__( 'Total Sold Price', 'eventin-pro' ); ?>
                                    <span class="total-count"><?php echo esc_html( $purchase_summary['sale_price'] ) ?></span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
			?>
            <div class="wrap etn-sales-report">
                <h3><?php echo esc_html__( 'Purchase History', 'eventin-pro' ); ?></h3>
                <form method="POST">
					<?php
					$sales_reports = array(
						'singular_name' => esc_html__( 'Sales Report', 'eventin-pro' ),
						'plural_name'   => esc_html__( 'Sales Reports', 'eventin-pro' ),
						'name'          => 'etn_category',
					);

					$table = new \Etn_Pro\Core\Event\Sales\Sales_Report( $sales_reports );
					$table->preparing_items();
					$table->display();
					?>
                </form>
            </div>
			<?php
		}
	}

	/**
	 * Returns event totaL sold ticket count
	 */
	public function get_event_sold_ticket_count( $post_id ) {
		$ticket_qty        = get_post_meta( $post_id, "etn_total_sold_tickets", true );
		$total_sold_ticket = isset( $ticket_qty ) && is_numeric( $ticket_qty ) ? intval( $ticket_qty ) : 0;

		return $total_sold_ticket;
	}

	/**
	 * Returns event total ticket
	 */
	public function get_event_total_ticket_count( $post_id ) {
		$ticket_qty   = get_post_meta( $post_id, "etn_total_avaiilable_tickets", true );
		$total_ticket = isset( $ticket_qty ) && is_numeric( $ticket_qty ) ? intval( $ticket_qty ) : 0;

		return $total_ticket;
	}


	/**
	 * Get event status
	 * @param $post_id
	 * @param bool $show_upcoming_time
	 * @return string
	 */

	public function etn_get_event_status( $post_id , $show_upcoming_time = false) {
		$is_expire  = \Etn\Core\Event\Helper::instance()->event_registration_deadline( array( 'single_event_id'=> $post_id) );
		$deadline   = \Etn\Core\Event\Helper::instance()->event_expire_date( $post_id );
		if ( $is_expire ) {
			return  esc_html__( "Expire", "eventin-pro" );
		}
		
		$event_status            = esc_html__( "Ongoing", "eventin-pro" );
		$event_start_date = ! empty( get_post_meta( $post_id, "etn_start_date", true ) ) ? get_post_meta( $post_id, "etn_start_date", true ) : "";
		$etn_start_time   = ! empty( get_post_meta( $post_id, "etn_start_time", true ) ) ? get_post_meta( $post_id, "etn_start_time", true ) : "";
		$event_end_date   = ! empty( get_post_meta( $post_id, "etn_end_date", true ) ) ? get_post_meta( $post_id, "etn_end_date", true ) : $event_start_date;
		$event_end_time   = ! empty( get_post_meta( $post_id, "etn_end_time", true ) ) ? get_post_meta( $post_id, "etn_end_time", true ) : $etn_start_time;

		$current_time_string           = time();
		$event_start_date_string       = strtotime( $event_start_date ." ". $etn_start_time );
		$event_end_date_string         = strtotime( $event_end_date ." ". $event_end_time  );


		if (  ( $event_start_date_string > $current_time_string ) && 
			( $event_end_date_string > $current_time_string )  
		) {
			$time_difference = "upcoming";
			$event_status = esc_html__( "Upcoming", "eventin-pro" );
			if ( $show_upcoming_time ) {
				$difference = $event_start_date_string - $current_time_string;
				$event_status .= ": " . $this->seconds_to_human( abs( $difference ) );
			}
		}

		return $event_status;
	}

	/**
	 * Takes seconds and returns human readable time
	 */
	public function seconds_to_human( $seconds ) {
		// $s = $seconds % 60;
		// $m = floor( ( $seconds % 3600 ) / 60);
		$h = floor( ( $seconds % 86400 ) / 3600 );
		$d = floor( ( $seconds % 2592000 ) / 86400 );
		$M = floor( $seconds / 2592000 );

		return "$M months, $d days, $h hours";
	}

	/**
	 * Event wise filtering function
	 *
	 */
	public function event_type_filter( $options ) {

		$filter_options = array(
			'Past'     => esc_html( 'Past', 'eventin-pro' ),
			'Ongoing'  => esc_html( 'Ongoing', 'eventin-pro' ),
			'Upcoming' => esc_html( 'Upcoming', 'eventin-pro' )
		);

		return array_merge( $options, $filter_options );
	}

	/**
	 * Result of query
	 */
	public function event_filter_request_query( $meta, $search_value ) {

		if ( $search_value != '' ) {
			// compare sign
			switch ( $search_value ) {
				case 'Past':
					$compare_sign_less    = '<=';
					$compare_sign_greater = '<=';
					break;
				case 'Ongoing':
					$compare_sign_less    = '<=';
					$compare_sign_greater = '>=';
					break;
				case 'Upcoming':
					$compare_sign_less    = '>=';
					$compare_sign_greater = '>=';
					break;
				default:
					$compare_sign_less    = '';
					$compare_sign_greater = '';
					break;
			}

			// setup this functions meta values
			$meta[] = array(
				'relation' => 'AND',
				array(
					'key'     => 'etn_start_date',
					'value'   => date( 'Y-m-d' ),
					'compare' => $compare_sign_less,
					'type'    => 'DATE'
				),
				array(
					'key'     => 'etn_end_date',
					'value'   => date( 'Y-m-d' ),
					'compare' => $compare_sign_greater,
					'type'    => 'DATE'
				)
			);

		}

		return $meta;

	}

	/**
	 * Generate And Send Attendee Certificates
	 *
	 * @return void
	 */
	public function send_attendee_certificates() {
		$response = array(
			'messages'    => array(esc_html__( 'Could not generate certificate. Try again!', 'eventin-pro' )),
			'content'     => [],
			'success'     => false,
		);

		if ( wp_verify_nonce( sanitize_text_field( $_POST['security'] ), 'generate_attendee_certificate' ) && ! empty( $_POST['event_id'] ) ) {
			$event_id         = intval( sanitize_text_field( $_POST['event_id'] ) );
			$response         = Helper::send_certificate_email( $event_id );
			wp_send_json_success( $response );
		}
		else{
			wp_send_json_error( $response );
		}

		exit;
	}

}
