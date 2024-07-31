<?php

namespace Etn_Pro\Utils;

use Error;
use Etn\Core\Settings\Settings as SettingsFree;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Global helper class.
 *
 * @since 1.0.0
 */
class Helper extends \Etn\Utils\Helper {

	/**
	 * get texonomy function
	 */
	public static function get_custom_texonomy( $taxonomy ) {
		// show category
		$terms = get_terms( [
			'taxonomy'         => $taxonomy,
			'hide_empty'       => false,
			'suppress_filters' => false,
		] );

		return $terms;
	}

	/**
	 * Make Eventin free version ready before initiating Eventin Pro
	 *
	 * @return void
	 */
	public static function make_eventin_ready() {

		$basename            = 'wp-event-solution/eventin.php';
		$is_plugin_installed = self::get_installed_plugin_data( $basename );
		$plugin_data         = self::get_plugin_data( 'wp-event-solution', $basename );

		if ( $is_plugin_installed ) {

			// upgrade plugin - attempt for once
			if ( isset( $plugin_data->version ) && $is_plugin_installed['Version'] != $plugin_data->version ) {
				self::upgrade_or_install_plugin( $basename );
			}

			// activate plugin
			if ( is_plugin_active( $basename ) ) {
				return true;
			} else {
				activate_plugin( self::safe_path( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $basename ), '', false, true );

				return true;
			}

		} else {
			// install & activate plugin
			$download_link = isset( $plugin_data->download_link ) ? $plugin_data->download_link : "";

			if ( self::upgrade_or_install_plugin( $download_link, false ) ) {
				return true;
			}

		}

		return false;
	}

	/**
	 * Download and Install Eventin Free version
	 *
	 * @param string $slug
	 * @param string $basename
	 *
	 * @return void
	 */
	private static function get_plugin_data( $slug = '', $basename = '' ) {

		if ( empty( $slug ) ) {
			return false;
		}

		$installed_plugin = false;

		if ( $basename ) {
			$installed_plugin = self::get_installed_plugin_data( $basename );
		}

		if ( $installed_plugin ) {
			return $installed_plugin;
		}

		$args = [
			'slug'   => $slug,
			'fields' => [
				'version' => false,
			],
		];

		$response = wp_remote_post(
			'http://api.wordpress.org/plugins/info/1.0/',
			[
				'body' => [
					'action'  => 'plugin_information',
					'request' => serialize( (object) $args ),
				],
			]
		);

		if ( is_wp_error( $response ) ) {
			return false;
		} else {
			$response = unserialize( wp_remote_retrieve_body( $response ) );

			if ( $response ) {
				return $response;
			} else {
				return false;
			}

		}

	}

	/**
	 * Get installed plugin data
	 *
	 * @param string $basename
	 *
	 * @return void
	 */
	public static function get_installed_plugin_data( $basename = '' ) {

		if ( empty( $basename ) ) {
			return false;
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();

		return isset( $plugins[ $basename ] ) ? $plugins[ $basename ] : false;
	}

	/**
	 * Install or Upgrade plugin
	 *
	 * @param string $basename
	 * @param boolean $upgrade
	 *
	 * @return false
	 */
	private static function upgrade_or_install_plugin( $basename = '', $upgrade = true ) {

		if ( empty( $basename ) ) {
			return false;
		}

		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';

		$skin     = new \Automatic_Upgrader_Skin;
		$upgrader = new \Plugin_Upgrader( $skin );

		if ( $upgrade == true ) {
			$upgrader->upgrade( $basename );
		} else {
			$upgrader->install( $basename );
			activate_plugin( $upgrader->plugin_info(), '', false, true );
		}

		return $skin->result;
	}

	/**
	 * Countdown markup
	 */
	public static function countdown_markup( $etn_start_date, $event_start_time, $etn_timezone ) {

		$event_start_time   = isset( $event_start_time ) && ( "" != $event_start_time ) ? date_i18n( "H:i:s", strtotime( $event_start_time ) ) : "00:00:00";
		$event_start_date   = isset( $etn_start_date ) && ( "" != $etn_start_date ) ? date_i18n( "m/d/Y", strtotime( $etn_start_date ) ) : date_i18n( "m/d/Y", time() );
		$counter_start_time = $event_start_date . " " . $event_start_time;
		$countdown_day      = esc_html__( "day", "eventin-pro" );
		$countdown_hr       = esc_html__( "hr", "eventin-pro" );
		$countdown_min      = esc_html__( "min", "eventin-pro" );
		$countdown_sec      = esc_html__( "sec", "eventin-pro" );
		$event_options      = get_option( "etn_event_options" );
		$show_seperate_dot  = true;
		$timezone_offset    = \Etn\Core\Event\Helper::instance()->get_timezone_numeric_value( $etn_timezone );

		$date_texts = [
			'day'    => $countdown_day,
			'days'   => esc_html__( "days", "eventin-pro" ),
			'hr'     => $countdown_hr,
			'hrs'    => esc_html__( "hrs", "eventin-pro" ),
			'min'    => $countdown_min,
			'mins'   => esc_html__( "mins", "eventin-pro" ),
			'sec'    => $countdown_sec,
			'secs'   => esc_html__( "secs", "eventin-pro" ),
			'offset' => $timezone_offset
		];

		if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-countdown.php' ) ) {
			require get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-countdown.php';
		} else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-countdown.php' ) ) {
			require get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-countdown.php';
		} else {
			require \Wpeventin_Pro::templates_dir() . 'event/event-pro-countdown.php';
		}

	}

	/**
	 * Single page organizer block
	 */
	public static function single_template_organizer( $etn_organizer_events ) {

		if ( file_exists( \Wpeventin_Pro::templates_dir() . 'event/event-pro-organizers.php' ) ) {

			require \Wpeventin_Pro::templates_dir() . 'event/event-pro-organizers.php';
		}

	}

	/**
	 * Single page speaker block
	 */
	public static function single_template_speaker( $etn_speaker_events ) {

		if ( file_exists( \Wpeventin_Pro::templates_dir() . 'event/event-pro-speaker.php' ) ) {
			require \Wpeventin_Pro::templates_dir() . 'event/event-pro-speaker.php';
		}

	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $event_id
	 * @param [type] $show_avatar
	 * @param [type] $show_email
	 *
	 * @return void
	 */
	public static function attendee_list_widget( $event_id, $show_avatar, $show_email ) {
		$event_attendees  = self::get_attendees_by_event( $event_id );
		$template         = ETN_PRO_DIR . "/core/shortcodes/views/event-attendees-view.php";
		$attendee_enabled = self::get_option( "attendee_registration" );

		if ( $attendee_enabled && file_exists( $template ) ) {
			include $template;
		}

	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function advanced_search_filter() {

		$date_order       = [
			'today'        => esc_html__( ' Today ', 'eventin-pro' ),
			'tomorrow'     => esc_html__( ' Tomorrow ', 'eventin-pro' ),
			'yesterday'    => esc_html__( ' Yesterday ', 'eventin-pro' ),
			'this-weekend' => esc_html__( ' This Weekend ', 'eventin-pro' ),
			'this-week'    => esc_html__( ' This Week ', 'eventin-pro' ),
			'this-month'   => esc_html__( ' This Month ', 'eventin-pro' ),
			'upcoming'     => esc_html__( ' Upcoming ', 'eventin-pro' ),
			'expired'      => esc_html__( ' Expired ', 'eventin-pro' ),
		];
		$event_type_order = [
			'on' => esc_html__( 'Online Event', 'eventin-pro' ),
			'no' => esc_html__( 'Offline Event', 'eventin-pro' ),
		];
		?>
        <div class="etn_event_inline_form_bottom">
            <h3 class="etn_event_form_title"><?php echo esc_html__( "Advanced Search", 'eventin-pro' ); ?></h3>
            <div class="etn-row">
                <div class="etn-col-lg-4 etn-col-md-6">
                    <p class="etn_event_inline_input_label"><?php echo esc_html__( "Sort by:", 'eventin-pro' ) ?></p>
                    <select name="etn_event_date_range" class="etn_event_select2 etn_event_select">
                        <option value><?php echo esc_html__( "Event Date", 'eventin-pro' ) ?></option>
						<?php
						if ( ! empty( $date_order ) && is_array( $date_order ) ) {
							$select_date_value = '';

							if ( isset( $_GET['etn_event_date_range'] ) ) {
								$select_date_value = $_GET['etn_event_date_range'];
							}
							foreach ( $date_order as $key => $value ) {
								?>
                                <option <?php
								if ( ! empty( $select_date_value ) && $select_date_value == $key ) {
									echo ' selected="selected"';
								}
								?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?>
                                </option>
								<?php
							}

						}
						?>
                    </select>
                    <!-- // event date -->
                </div>
				<?php
				$settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();

				if ( isset( $settings['etn_zoom_api'] ) && $settings['etn_zoom_api'] === "on" ) {
					?>
                    <div class="etn-col-lg-4 etn-col-md-6">
                        <p class="etn_event_inline_input_label"><?php echo esc_html__( "Event Type:", 'eventin-pro' ) ?></p>
                        <select name="etn_event_will_happen" class="etn_event_select2 etn_event_select">
                            <option value><?php echo esc_html__( "Event Type", 'eventin-pro' ) ?>
                            </option>
							<?php
							if ( is_array( $event_type_order ) && ! empty( $event_type_order ) ) {
								foreach ( $event_type_order as $key => $value ) {
									$select_value = "";
									if ( isset( $_GET['etn_event_will_happen'] ) ) {
										$select_value = $_GET['etn_event_will_happen'];
									}
									?>
                                    <option
										<?php
										if ( ! empty( $select_value ) && $select_value === $key ) {
											echo ' selected="selected"';
										}
										?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?>
                                    </option>
									<?php
								}

							}
							?>
                        </select>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
		<?php
	}

	/**
	 * Get event locations
	 */
	public static function get_location_data( $default_options = "", $no_options = "", $value_type = "key" ) {
		$default_options = esc_html__( 'Select event location', 'eventin-pro' );
		// get location
		$locations = get_terms( 'etn_location', [
			'taxonomy'   => 'etn_location',
			'hide_empty' => 0,
			'orderby'    => 'DESC',
			'parent'     => 0
		] );

		if ( $no_options == 'yes' ) {
			$location_arr = [];
		} else {
			$location_arr = [ '' => $default_options ];
		}

		if ( ! empty( $locations ) ) {

			foreach ( $locations as $value ) {

				if ( $value_type == "key" ) {
					$location_arr["$value->slug"] = $value->name;
				} else if ( $value_type == "id" ) {
					$location_arr["$value->term_id"] = $value->name;
				} else {
					$location_arr["$value->name"] = $value->name;
				}

			}

		}

		return $location_arr;
	}


	/**
	 * return currency and country
	 */
	public static function retrieve_country_currency( $code = false ) {
		$settings              = \Etn\Core\Settings\Settings::instance()->get_settings_option();
		$country_currency_data = isset( $settings['etn_settings_country_currency'] ) ? $settings['etn_settings_country_currency'] : [];

		$country_currency = [
			'country'      => '',
			'currency'     => 'USD',
			'num_decimals' => '2',
		];

		if ( ! empty( $country_currency_data ) ) {
			$info                        = $country_currency_data['options']['currency']['name'];
			$info_arr                    = explode( '-', $info );
			$country_currency['country'] = $info_arr[0];

			if ( $code ) {
				$country_currency['currency']     = $info_arr[1];
				$country_currency['num_decimals'] = $info_arr[3];
			} else {
				$country_currency['currency']     = $info_arr[2];
				$country_currency['num_decimals'] = $info_arr[3];
			}

		}

		return $country_currency;
	}

	/**
	 * Showing space
	 */
	public static function showing_space( $param ) {
		$dash = '';

		if ( '' !== $param ) {
			$dash = ' - ';
		}

		return $dash;
	}


	/**
	 * Process Certificate And Send Through Email
	 *
	 * @param [type] $event_id
	 *
	 * @return void
	 */

	public static function send_certificate_email( $event_id ) {
		$settings               = get_option( "etn_event_options" );
		$event_name             = get_the_title( $event_id );
		$subject                = esc_html__( 'Attendee Certificate for ', 'eventin-pro' ) . $event_name;
		$from                   = !empty($settings['admin_mail_address']) ? $settings['admin_mail_address'] : '';
		$from_name              = self::retrieve_mail_from_name();
		$certificate_preference = get_post_meta( $event_id, 'certificate_preference', true );

		if ( ! $certificate_preference ) {
			$certificate_preference = $settings['certificate_preference'] ? $settings['certificate_preference'] : 'on';
		}
		
		$attendees = self::get_attendees( $event_id , $certificate_preference );

		if ( ! $attendees ) {
			return new WP_Error( 'no-attendee-found', __( 'No attendee found.', 'eventin-pro' ), ['status' => 400] );
		}

		$args = array(
			'response' => [
				'success' => false,
				'message' => [] , 
				'content' => [] 
			], 
			'attendees' => $attendees,
			'event_id' => $event_id, 
			'event_name' => $event_name, 
			'subject' => $subject,
			'from' => $from , 
			'from_name' => $from_name,
		);
		  
		$response = self::sending_attendee_email( $args );
	
		return $response;
	}

	/**
	 * Fetch attendee
	 *
	 * @param [type] $event_id
	 * @param [type] $order_event_key
	 * @param [type] $certificate_preference
	 * @return void
	 */
	public static function get_attendees( $event_id , $certificate_preference ) {
		$arg = [
			'post_type'   => 'etn-attendee',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query'      => array(
				array(
					'key' => 'etn_event_id',
					'value' => $event_id,
					'compare'     => 'LIKE',
				),
			)
		];

		if ( 'on' == $certificate_preference ) {
			$arg['meta_query'][] = [
				'key'   => 'etn_attendeee_ticket_status',
				'value' => 'used',
			];
		}

		return get_posts( $arg );	
	}
	
	/**
	 * Send email to attendees
	 *
	 * @param [type] $args
	 * @return void
	 */
	public static function sending_attendee_email($args) {
		extract($args);
	
		$response = [
			'success' => false,
			'status_code' => 400,
			'message' => esc_html__("No attendees found", "eventin-pro"),
		];
	
		if (is_array($attendees) && !empty($attendees)) {
			$attendee_first = reset($attendees);
			$attendee_email = get_post_meta($attendee_first->ID, 'etn_email', true);
			$attendee_name = get_post_meta($attendee_first->ID, 'etn_name', true);

			if ($attendee_email !== "") {
				foreach ($attendees as $attendee) {
					$email_content 	= self::generate_single_attendee_markup($attendee, $event_id, $event_name);
					$attendee_email = get_post_meta($attendee->ID, 'etn_email', true);
					$attendee_name 	= get_post_meta($attendee->ID, 'etn_name', true);
	
					if ($attendee_email !== "") {
						Helper::send_email($attendee_email, $subject, $email_content, $from, $from_name);
					} else {
						$response['message'] = $attendee_name . esc_html__(" has no email. Certificate has not been sent.", "eventin-pro");
						break;
					}
				}
	
				$response['message'] = esc_html__("Mail Sent Successfully", "eventin-pro");
				$response['success'] = true;
				$response['status_code'] = 200;
			} else {
				$response['message'] = $attendee_name . esc_html__(" has no email. Certificate has not been sent.", "eventin-pro");
			}
		}
	
		return $response;
	}
	

	/**
	 * Generate Single Attendee Markup
	 *
	 * @param [string] $attendee
	 * @param [string] $event_id
	 * @param [string] $event_name
	 *
	 * @return $markup string
	 */
	public static function generate_single_attendee_markup( $attendee, $event_id, $event_name ) {
		$markup            = sprintf( __( '<p>Congratulations for successfully attending/completing the event \'%1$s\'. Your certificate is ready! Click on the link provided below to get the PDF certificate. </p>', 'eventin-pro' ), '<span>' . $event_name . '</span>' );
		$name              = get_post_meta( $attendee->ID, 'etn_name', true );
		$token             = get_post_meta( $attendee->ID, 'etn_info_edit_token', true );
		$base_url          = home_url();
		$attendee_endpoint = ( new \Etn\Core\Attendee\Cpt() )->get_name();
		$action_url        = $base_url . "/" . $attendee_endpoint;
		$certificate_url   = $action_url . "?etn_action=" . urlencode( 'download_certificate' ) . "&event_id=" . urlencode( $event_id ) . "&attendee_id=" . urlencode( $attendee->ID ) . "&etn_info_edit_token=" . urlencode( $token );
		$row               = sprintf( __( 'Attendee: ', 'eventin-pro' ) . " " . $name );
		$row               .= sprintf( __( '<p><a href="%1$s">Download Certificate</a></p>', 'eventin-pro' ), $certificate_url );
		$markup            .= $row;

		return $markup;
	}

	/**
    * Get schedule based on user_id
    *
    * @return array
    */
    public static function get_schedule_by_user_id( $user_id ) {
		$schedules = get_posts( array(
			'post_type'   => 'etn-schedule',
			'numberposts' => '-1',
			'post_status' => 'publish',
            'author' => $user_id,
		) );

        return $schedules;
    }
}