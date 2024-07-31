<?php
namespace Etn_Pro\Core\Event;

defined( 'ABSPATH' ) || exit;

use DateTime;
use Etn\Base\Cron as Base_Cron;
use Etn\Core\Settings\Settings as SettingsFree;
use Etn_Pro\Utils\Helper;

class Cron extends Base_Cron {

	use \Etn\Traits\Singleton;

	public $hook_name = '';

	/**
	 * Register event and change schedule
	 */
	public function init() {
		$settings = SettingsFree::instance()->get_settings_option();
		if ( empty($settings['off_remainder']) ) {
			return;
		}
		
		$this->config();
		$this->remove_cron_job();
		add_action( 'wp_after_insert_post', [$this, 'etn_cron_register'], 10, 2 );
		add_action( 'init', [ $this, 'run_schedule_reminder_email' ] );
	}

	/**
	 * Register event remainder email
	 *
	 * @param   integer  $post_id
	 * @param   Object  $post
	 *
	 * @return  void
	 */
	public function etn_cron_register( $post_id, $post ) {

		extract(\Etn\Utils\Helper::instance()->single_template_options( $post_id ));

		if ( 'etn' !== $post->post_type ) {
			return;
		}

		if ( ! $event_start_date ) {
			return;
		}

		// Get settings.
		$settings          = SettingsFree::instance()->get_settings_option();
		$remainder_day     = isset( $settings['remainder_email_sending_day'] ) ? (int) $settings['remainder_email_sending_day'] : 0;
		$remainder_time     = isset( $settings['remainder_email_sending_time'] ) ? $settings['remainder_email_sending_time'] : "12:00 PM";

		// Prepare timestamp.
		$timestamp      = ( $remainder_day * 24 ) * 60 * 60;
		$timestamp      = strtotime( $event_start_date . ' ' . $event_start_time ) - $timestamp ;
		$remainder_dt   = date('Y-m-d ', $timestamp ) . ' ' . $remainder_time ;
		$timestamp      = \Etn\Core\Event\Helper::instance()->convert_event_time_zone($event_timezone , $remainder_dt );
		$timestamp      = strtotime($timestamp);

		// Register cron job for reminder email.
		if ( ! wp_next_scheduled( 'etn_event_remainder_' . $post_id ) ) {
			wp_schedule_single_event( $timestamp, 'etn_event_remainder_' . $post_id, [$post_id] );
		}
	}

	/**
	 * Run reminder email schedule
	 *
	 * @return  void
	 */
	public function run_schedule_reminder_email() {
		$current_time      = date( 'Y-m-d' );

		$args             = array(
			'post_type'   => 'etn',
			'post_status' => 'publish',
			'meta_query'  => [
				[
					'key'     => 'etn_start_date',
					'value'   => date( 'Y-m-d', strtotime( $current_time ) ),
					'compare' => '>=',
					'type'    => 'DATE',
				],
			],
		);

		$events = get_posts( $args );

		foreach ( $events as $event ) {
			add_action( 'etn_event_remainder_' . $event->ID, [ $this, 'send_reminder_email'] );
		}
	}

	/**
	 * Send reminder email
	 *
	 * @param   integer  $post_id
	 *
	 * @return  void
	 */
	public function send_reminder_email( $post_id ) {
		
		$settings          	= SettingsFree::instance()->get_settings_option();
		$remainder_message 	= isset( $settings['remainder_message'] ) ? $settings['remainder_message'] : esc_html__( 'You have an upcoming event.', 'eventin-pro' );
		$admin_mail_addr   	= ! empty( $settings['admin_mail_address'] ) ? $settings['admin_mail_address'] : '';
		$mail_subject       = esc_html__( 'Email remainder', 'eventin-pro' );
		$mail_status       = get_post_meta( $post_id, 'etn_remainder_email', true  );


		$attendee_list = \Etn_Pro\Core\Action::instance()->total_attendee( $post_id, true );
		
		if ( $attendee_list && $mail_status == "" ) {
			foreach ( $attendee_list as $key => $value ) {
				if ( isset( $value->email ) && $admin_mail_addr ) {
					Helper::send_email( $value->email, $mail_subject, $remainder_message, $admin_mail_addr, esc_html__( 'Admin', 'eventin-pro' ) );
					add_post_meta( $post_id, 'etn_remainder_email', 1  );
				}
			}
		}
	}

	/**
	 * Do the task
	 * Register cron schedule to remove failed status attendee
	 */
	public function action_name() {
		return false;
	}

	/**
	 * recurrence action name
	 */
	public function recurrence_action() {
		// register a schedule
		add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );

		return 'etn-event-remainder-email';
	}

	/**
	 * Filters cron_schedules to add a new schedule
	 */
	public function cron_schedules( $schedules ) {
		return $schedules;
	}

	/**
	 * Send Schedule for sending email
	 */
	public function email_sending_schedule() {
		return false;
	}

	/**
	 * Remove existing job
	 */
	public function remove_cron_job() {
		$args = array(
			'post_type'   => 'etn',
			'post_status' => 'publish',
		);

		$events = get_posts( $args );

		if ( ! $events ) {
			return;
		}

        // Run cron action.
        foreach ( $events as $event ) {
            $timestamp = wp_next_scheduled( 'etn_event_remainder_' . $event->ID );

            if ( $timestamp && $timestamp < time() ) {
                wp_unschedule_event( $timestamp, 'etn_event_remainder_' . $event->ID );
            }
        }
	}

}