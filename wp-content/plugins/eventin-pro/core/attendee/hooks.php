<?php

namespace Etn_Pro\Core\Attendee;

use \Etn_Pro\Utils\Helper;
use \Etn_Pro\Core\Event\Event as EventPro;

defined( 'ABSPATH' ) || exit;

/**
 * Attendee functionalities
 */
class Hooks {
	use \Etn_Pro\Traits\Singleton;

	/**
	 * Fire all hooks
	 */
	public function init() {
		// filter attendee with event name.
		add_action( 'restrict_manage_posts', [ $this, 'show_attendee_report_filter' ] );
		add_filter( 'parse_query', [ $this, 'attendee_report_filter_result' ] );

		// add bulk action to update attendee post status
		add_filter( 'handle_bulk_actions-edit-etn-attendee', [
			$this,
			'attendee_bulk_action_change_to_publish'
		], 10, 3 );
		add_action( 'admin_notices', [ $this, 'attendee_show_notice_after_change_to_publish' ] );


		add_action( 'admin_notices', [ $this, 'attendee_show_notice_after_download_csv' ] );

		add_filter( 'etn_attendee_fields', [ $this, 'etn_attendee_fields_add_extra' ], 8 );

		add_action( 'etn\pdf\before_main_details', [ $this, 'before_pdf_body_show_unique_id' ], 10, 3 );

		add_filter( 'posts_join', [ $this, 'attendee_ticket_id_search_join' ] );

		add_filter( 'posts_where', [ $this, 'attendee_ticket_id_search_where' ] );

		add_filter( "manage_etn-attendee_posts_columns", [ $this, "attendee_post_columns" ], 10, 1 );
		add_action( "manage_etn-attendee_posts_custom_column", [ $this, 'attendee_custom_column_data' ], 10, 2 );

		add_action( 'init', [ $this, 'scanner_module_functionalities' ] );
		add_action( "etn_pro_ticket_qr", [ $this, 'etn_pro_ticket_qr_cb' ], 10, 2 );
		add_action( "etn_pro_ticket_id", [ $this, 'etn_pro_ticket_id_cb' ], 10, 2 );

		add_action( 'init', [ $this, 'show_attendee_certificate' ] );

		add_filter( 'theme_page_templates', [ $this, 'certificate_add_template_to_select' ], 10, 4 );
		add_filter( 'page_template', [ $this, 'certificate_page_template' ] );
		add_action( 'wp_ajax_attendee_event', [ $this, 'get_attendee_event' ] );

		add_action( 'after_attendee_ticket_title', [ $this, 'bulk_attendee_checkbox' ] );

		// include API for tags
		include_once \Wpeventin_Pro::core_dir() . 'attendee/api.php';
	}

	/**
	 * Dashboard column positioning
	 */
	public function attendee_post_columns( $columns ) {

		$settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();

		$attendee_extra_fields = isset( $settings['attendee_extra_fields'] ) ? $settings['attendee_extra_fields'] : [];

		$extra_items = array();
		if ( is_array( $attendee_extra_fields ) && ! empty( $attendee_extra_fields ) ) {
			foreach ( $attendee_extra_fields as $index => $attendee_extra_field ) {

				if ( isset( $attendee_extra_field['show_in_dashboard'] ) && $attendee_extra_field['label'] != '' ) {
					$column_name                 = Helper::generate_name_from_label( "etn_attendee_extra_field_", $attendee_extra_field['label'] );
					$extra_items[ $column_name ] = esc_html__( $attendee_extra_field['label'], 'eventin-pro' );
				}

			}
		}

		$new_item['unique_id'] = esc_html__( 'Ticket ID', 'eventin-pro' );

		$slice_pos = 2;
		if ( ! empty( Helper::get_option( 'reg_require_email' ) ) ) {
			$slice_pos += 1;
		}

		if ( ! empty( Helper::get_option( 'reg_require_phone' ) ) ) {
			$slice_pos += 1;
		}

		$new_columns = array_slice( $columns, 0, 1, true ) + $new_item +
		               array_slice( $columns, 1, $slice_pos, true ) + $extra_items +
		               array_slice( $columns, 3, count( $columns ) - 1, true );

		return $new_columns;

	}

	/**
	 * Return row
	 */
	public function attendee_custom_column_data( $column, $post_id ) {
		$ticket_id = '#' . get_post_meta( $post_id, 'etn_unique_ticket_id', true );

		switch ( $column ) {
			case 'unique_id':
				echo esc_html( $ticket_id );
				break;
		}

		$settings              = \Etn\Core\Settings\Settings::instance()->get_settings_option();
		$attendee_extra_fields = isset( $settings['attendee_extra_fields'] ) ? $settings['attendee_extra_fields'] : [];

		if ( is_array( $attendee_extra_fields ) && ! empty( $attendee_extra_fields ) ) {
			foreach ( $attendee_extra_fields as $index => $attendee_extra_field ) {
				if ( ! $attendee_extra_field ) {
					continue;
				}
				
				if ( $attendee_extra_field['label'] != '' ) {
					$column_name = Helper::generate_name_from_label( "etn_attendee_extra_field_", $attendee_extra_field['label'] );

					if ( $column == $column_name ) {
						if ( $attendee_extra_field['type'] == 'radio' ) {
							$radio_val = get_post_meta( $post_id, $column_name, true );
							if ( isset( $attendee_extra_field['radio'] ) ) {
								if ( is_array( $attendee_extra_field['radio'] ) && ! empty( $attendee_extra_field['radio'] ) ) {
									if ( isset( $attendee_extra_field['radio'][ $radio_val ] ) ) {
										echo esc_html__( $attendee_extra_field['radio'][ $radio_val ], 'eventin-pro' );
									}
								}
							}
						} else if ( $attendee_extra_field['type'] == 'checkbox' ) {
							$checkbox_serialized_arr = maybe_unserialize( get_post_meta( $post_id, $column_name, true ) );

							if ( isset( $attendee_extra_field['checkbox'] ) ) {
								if ( is_array( $attendee_extra_field['checkbox'] ) && ! empty( $attendee_extra_field['checkbox'] ) ) {
									if ( is_array( $checkbox_serialized_arr ) && ! empty( $checkbox_serialized_arr ) ) {
										$checkbox_text_arr = [];
										foreach ( $checkbox_serialized_arr as $index => $val ) {
											if ( isset( $attendee_extra_field['checkbox'][ $val ] ) ) {
												array_push( $checkbox_text_arr, esc_html__( $attendee_extra_field['checkbox'][ $val ], 'eventin-pro' ) );
											}
										}

										echo join( ', ', $checkbox_text_arr );
									}
								}
							}
						} else {
							echo esc_html( get_post_meta( $post_id, $column_name, true ) );
						}
						break;
					}
				}

			}
		}

	}

	/**
	 * Join postmeta in admin post search
	 *
	 * @return string SQL join
	 */
	public function attendee_ticket_id_search_join( $join ) {
		global $pagenow, $wpdb;
		if ( is_admin() && $pagenow == 'edit.php' && ! empty( $_GET['post_type'] ) && $_GET['post_type'] == 'etn-attendee' ) {
			if ( ! empty( $_GET['event_id'] ) ) {
				$join .= 'JOIN ' . $wpdb->postmeta . ' AS etn_meta_table  ON ' . $wpdb->posts . '.ID = etn_meta_table.post_id 
				AND  etn_meta_table.meta_key="etn_event_id" AND etn_meta_table.meta_value = ' . $_GET['event_id'] . '';
			}
			if ( ! empty( $_GET['s'] ) ) {
				$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' AS etn_meta_table  ON ' . $wpdb->posts . '.ID = etn_meta_table.post_id ';
			}
		}

		return $join;
	}

	/**
	 * Filtering the where clause in admin post search query
	 *
	 * @return string SQL WHERE
	 */
	public function attendee_ticket_id_search_where( $where ) {
		global $pagenow, $wpdb;
		if ( is_admin() && $pagenow == 'edit.php' && ! empty( $_GET['post_type'] ) && $_GET['post_type'] == 'etn-attendee' && ! empty( $_GET['s'] ) ) {
			$where = preg_replace( "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/", "(" . $wpdb->posts . ".post_title LIKE $1) OR ( etn_meta_table.meta_value LIKE $1)", $where );
		}


		return $where;
	}

	/**
	 * Add extra fields to attendee cpt metabox
	 *
	 * @param [type] $default_attendee_fields
	 *
	 * @return void
	 */
	public function etn_attendee_fields_add_extra( $default_attendee_fields ) {
		$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;

		if ( ! $post_id ) {
			$post_id = isset( $_POST['post_ID'] ) ? intval( $_POST['post_ID'] ) : 0;
		}

		$event_id = get_post_meta( $post_id, 'etn_event_id', true );

		

		$attendee_extra_fields = get_post_meta( $event_id, 'attendee_extra_fields', true );

		$settings              = Helper::get_settings();
		
		if ( ! $attendee_extra_fields ) {
			$attendee_extra_fields = isset( $settings['attendee_extra_fields'] ) ? $settings['attendee_extra_fields'] : [];
		}


		$attendee_arr          = array();

		if ( ! empty( $_GET['post'] ) ) {
			$attendee_arr['etn_unique_ticket_id'] = [
				'label'         => esc_html__( 'Ticket ID', 'eventin-pro' ),
				'desc'          => esc_html__( 'Ticket ID will be generated automatically after purchasing ticket successfully', 'eventin-pro' ),
				'type'          => 'text',
				'value'         => "",
				'priority'      => 1,
				'readonly'      => true,
				'disabled'      => true,
				'placeholder'   => esc_html__( 'Ticket ID', 'eventin-pro' ),
				'attr'          => [ 'class' => 'etn-label-item' ],
				'group'         => 'etn-label-group',
				'tooltip_title' => '',
				'tooltip_desc'  => '',
			];
			$attendee_arr['ticket_name']          = [
				'label'         => esc_html__( 'Ticket Name', 'eventin-pro' ),
				'desc'          => esc_html__( 'Ticket Name which client have been purchased', 'eventin-pro' ),
				'type'          => 'text',
				'value'         => ETN_DEFAULT_TICKET_NAME,
				'priority'      => 1,
				'readonly'      => true,
				'disabled'      => true,
				'placeholder'   => esc_html__( 'Ticket Name', 'eventin-pro' ),
				'attr'          => [ 'class' => 'etn-label-item' ],
				'group'         => 'etn-label-group',
				'tooltip_title' => '',
				'tooltip_desc'  => '',
			];
		} else {

			$get_tickets = array( 'tickets' => array(), 'ticket_price' => array() );
			$event_id    = ! empty( $_GET['event_id'] ) ? intval( $_GET['event_id'] ) : 0;

			if ( $event_id !== "" ) {
				$get_tickets = \Etn\Core\Event\Helper::instance()->ticket_by_events( $event_id );
			}
			
			$attendee_arr['etn_event_id']  = [
				'label'       => esc_html__( 'Event', 'eventin-pro' ),
				'desc'        => esc_html__( 'Select event for attendee', 'eventin-pro' ),
				'value'       => $event_id,
				'type'        => 'select_single',
				'options'     => \Etn\Utils\Helper::get_events( null, true, false, false , 0 ),
				'priority'    => 1,
				'required'    => true,
				'attr'        => [ 'class' => 'etn-label-item' ],
				'group'       => 'etn-label-group',
				'warning'     => esc_html__( 'Create Event', 'eventin-pro' ),
				'warning_url' => admin_url( 'edit.php?post_type=etn' ),
			];
			$attendee_arr['ticket_name']   = [
				'label'    => esc_html__( 'Ticket Name', 'eventin-pro' ),
				'desc'     => esc_html__( 'Ticket Name which client have been purchased', 'eventin-pro' ),
				'type'     => 'select_single',
				'priority' => 1,
				'options'  => $get_tickets['tickets'],
				'attr'     => [ 'class' => 'etn-label-item etn-meta-loading' ],
				'group'    => 'etn-label-group',
			];
			$attendee_arr['_ticket_price'] = [
				'label'    => esc_html__( 'Ticket Price', 'eventin-pro' ),
				'desc'     => esc_html__( 'Ticket price which client have been purchased', 'eventin-pro' ),
				'type'     => 'text',
				'value'    => "",
				'readonly' => true,
				'disabled' => true,
				'attr'     => [ 'class' => 'etn-label-item etn-meta-loading' ],
				'group'    => 'etn-label-group',
			];
			$attendee_arr['ticket_price']  = [
				'type'  => 'hidden',
				'value' => "",
				'label' => '',
				'desc'  => '',
				'attr'  => [ 'class' => 'etn-label-item etn-meta-loading' ],
				'group' => 'etn-label-group',
			];

			$attendee_arr['_etn_variation_total_price']    = [
				'type'  => 'hidden',
				'label' => '',
				'desc'  => '',
				'attr'  => [ 'class' => 'etn-label-item' ],
				'group' => 'etn-label-group',
			];
			$attendee_arr['_etn_variation_total_quantity'] = [
				'type'  => 'hidden',
				'label' => '',
				'desc'  => '',
				'attr'  => [ 'class' => 'etn-label-item' ],
				'group' => 'etn-label-group',
			];
		}

		if ( is_array( $attendee_extra_fields ) && ! empty( $attendee_extra_fields ) ) {
			foreach ( $attendee_extra_fields as $index => $attendee_extra_field ) {

				$label_content = $attendee_extra_field['label'];

				if ( $label_content != '' ) {
					$extra_field_label = $label_content;
					$extra_field_name  = Helper::generate_name_from_label( "etn_attendee_extra_field_", $label_content );
					$extra_field_desc  = ! empty( $attendee_extra_field['place_holder'] ) ? $attendee_extra_field['place_holder'] : '';
					$extra_field_type  = $attendee_extra_field['field_type'] == "checkbox" ? "multi_checkbox" : $attendee_extra_field['field_type'];

					$attendee_arr[ $extra_field_name ] = [
						'label'         => esc_html( $extra_field_label ),
						'type'          => $extra_field_type,
						'value'         => get_post_meta( $post_id, $extra_field_name, true),
						'desc'          => $extra_field_desc,
						'attr'          => [ 'class' => 'etn-label-item' ],
						'group'         => 'etn-label-group',
						'tooltip_title' => '',
						'tooltip_desc'  => '',
					];

					if ( $extra_field_type == "checkbox" ) {
						$default_attendee_fields[ $extra_field_name ]['inputs']        = array_column($attendee_extra_field['field_options'], 'value');
						$default_attendee_fields[ $extra_field_name ]['input_checked'] = $attendee_extra_field['field_options'];
					}

					if ( $extra_field_type == 'radio' ) {
						$radio_options                                           = ( isset( $attendee_extra_field['field_options'] ) && is_array( $attendee_extra_field['field_options'] ) ) ? array_column($attendee_extra_field['field_options'], 'value') : [];

						$attendee_arr[ $extra_field_name ]['options'] = $radio_options;
					}

					if ( $extra_field_type == 'select' ) {
						$select_options                                           = ( isset( $attendee_extra_field['field_options'] ) && is_array( $attendee_extra_field['field_options'] ) ) ? array_column($attendee_extra_field['field_options'], 'value') : [];

						$attendee_arr[ $extra_field_name ]['options'] = array_combine($select_options, $select_options);
						$attendee_arr[ $extra_field_name ]['type'] = 'select_single';
					}

					if ( $extra_field_type == 'multi_checkbox' ) {
						$checkbox_options                                        = ( isset( $attendee_extra_field['field_options'] ) && is_array( $attendee_extra_field['field_options'] ) ) ? array_column($attendee_extra_field['field_options'], 'value') : [];

						$attendee_arr[ $extra_field_name ]['inputs'] = $checkbox_options;
					}
				}

			}
		}

		$attendee_extra_fields_single = get_post_meta( $event_id, 'attendee_extra_fields', true );
		

		$fields = array_merge( $attendee_arr, $default_attendee_fields );

		return $fields;
	}

	/**
	 * Update query
	 * @return void
	 * @since 1.1.0
	 */
	public function attendee_report_filter_result( $query ) {

		if ( ! $query->is_main_query() ) {
			return $query;
		}

		$event_id            = ! empty( $_GET['etn_event_id'] ) ? sanitize_text_field( $_GET['etn_event_id'] ) : null;
		$etn_status          = ! empty( $_GET['etn_status'] ) ? sanitize_text_field( $_GET['etn_status'] ) : null;
		$etn_attendee_status = ! empty( $_GET['etn_attendeee_ticket_status'] ) ? sanitize_text_field( $_GET['etn_attendeee_ticket_status'] ) : null;

		if ( ! isset( $query->query['post_type'] ) || ( 'etn-attendee' !== $query->query['post_type'] ) ) {
			return $query;
		}

		$meta = [
			'relation' => 'AND'
		];


		if ( $event_id ) {
			$meta[] = [
				'key'     => 'etn_event_id',
				'value'   => $event_id,
				'compare' => '=',
				'type'    => 'NUMERIC'
			];
		}

		if ( $etn_status ) {
			$meta[] = [
				'key'     => 'etn_status',
				'value'   => $etn_status,
				'compare' => '=',
				'type'    => 'CHAR'
			];
		}

		if ( $etn_attendee_status ) {
			$meta[] = [
				'key'     => 'etn_attendeee_ticket_status',
				'value'   => $etn_attendee_status,
				'compare' => '=',
				'type'    => 'CHAR'
			];
		}

		$query->set( 'meta_query', $meta );

		return $query;
	}

	/**
	 * Filter slugs
	 * @return void
	 * @since 1.1.0
	 */
	public function show_attendee_report_filter() {
		global $typenow;

		if ( $typenow == 'etn-attendee' ) {
			//Filter by event
			$all_events = get_posts( [
				'post_type'        => 'etn',
				'numberposts'      => '-1',
				'suppress_filters' => false,
			] );

			$events = [];

			foreach ( $all_events as $key => $value ) {
				$id            = $value->ID;
				$title         = $value->post_title;
				$etn_start_date = $value->etn_start_date;
				$etn_start_date_format = date('d-m-Y', strtotime($etn_start_date));
				$events[ $id ] = $title. ' ('.$etn_start_date_format.')';
			}

			$current_event_name = '';

			if ( isset( $_GET['etn_event_id'] ) ) {
				$current_event_name = sanitize_text_field( $_GET['etn_event_id'] ); // Check if option has been selected
			}

			?>

            <select name="etn_event_id" id="etn_event_id">
                <option value="<?php selected( 'all_events', $current_event_name ); ?>">
					<?php echo esc_html__( 'All Events', 'eventin-pro' ); ?>
                </option>
				<?php
				foreach ( $events as $key => $value ) {
					?>
                    <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $current_event_name ); ?>>
						<?php echo esc_attr( $value ); ?>
                    </option>
					<?php
				}
				?>
            </select>

            <!-- Payment status -->
			<?php
			$all_status     = [
				'success' => esc_html__( 'Success', 'eventin-pro' ),
				'failed'  => esc_html__( 'Failed', 'eventin-pro' ),
			];
			$current_status = '';
			if ( isset( $_GET['etn_status'] ) ) {
				$current_status = sanitize_text_field( $_GET['etn_status'] );
			}
			?>

            <select name='etn_status' id='etn_status'>
                <option value='' <?php selected( 'etn_status', $current_status ); ?> >
					<?php echo esc_html__( 'All Payment Status', 'eventin-pro' ); ?>
                </option>
				<?php foreach ( $all_status as $key => $value ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $current_status ); ?> >
						<?php echo esc_html( $value ); ?>
                    </option>
				<?php endforeach; ?>
            </select>

			<?php
			// Ticket using status
			$ticket_status = [
				'unused' => esc_html__( 'Unused', 'eventin-pro' ),
				'used'   => esc_html__( 'Used', 'eventin-pro' ),
			];

			$current_ticket_status = '';

			if ( isset( $_GET['etn_attendeee_ticket_status'] ) ) {
				$current_ticket_status = sanitize_text_field( $_GET['etn_attendeee_ticket_status'] );
			}

			?>

            <select name="etn_attendeee_ticket_status" id="etn_attendeee_ticket_status">
                <option value="" <?php selected( 'all_ticket', $current_ticket_status ); ?>>
					<?php echo esc_html__( 'All Ticket Status', 'eventin-pro' ); ?>
                </option>
				<?php foreach ( $ticket_status as $key => $value ) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $current_ticket_status ); ?>>
						<?php echo esc_attr( $value ); ?>
                    </option>
				<?php } ?>
            </select>

			<?php
		}

	}


	/**
	 * Show notice after post status changed to published
	 *
	 * @return void
	 */
	public function attendee_show_notice_after_change_to_publish() {

		if ( ! empty( $_REQUEST['changed-to-published'] ) ) {
			$num_changed = (int) $_REQUEST['changed-to-published'];
			printf( '<div id="message" class="updated notice is-dismissable"><p>' . __( 'Published %d posts.', 'eventin-pro' ) . '</p></div>', $num_changed );
		}

	}

	/**
	 * Change multiple post status to Publish
	 *
	 * @param [type] $redirect_url
	 * @param [type] $action
	 * @param [type] $post_ids
	 *
	 * @return void
	 */
	public function attendee_bulk_action_change_to_publish( $redirect_url, $action, $post_ids ) {

		if ( $action == 'change-to-published' ) {

			foreach ( $post_ids as $post_id ) {
				wp_update_post( [
					'ID'          => $post_id,
					'post_status' => 'publish',
				] );
			}

			$redirect_url = add_query_arg( 'changed-to-published', count( $post_ids ), $redirect_url );
		}

		return $redirect_url;
	}

	/**
	 * add custom bulk action to attendee dashboard
	 *
	 * @param [type] $bulk_actions
	 *
	 * @return void
	 */
	public function attendee_bulk_action_change_status_to_publish( $bulk_actions ) {
		$bulk_actions['change-to-published'] = esc_html__( 'Change to published', 'eventin-pro' );

		return $bulk_actions;
	}

	/**
	 * add custom bulk action to attendee dashboard
	 *
	 * @param [type] $bulk_actions
	 *
	 * @return void
	 */
	public function attendee_bulk_action_download_csv( $bulk_actions ) {
		$bulk_actions['download-attendee-csv'] = esc_html__( 'Download Details as CSV', 'eventin-pro' );

		return $bulk_actions;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $redirect_url
	 * @param [type] $action
	 * @param [type] $post_ids
	 *
	 * @return void
	 */
	public function attendee_handle_bulk_action_download_csv( $redirect_url, $action, $post_ids ) {

		if ( $action == 'download-attendee-csv' ) {

			$settings              = \Etn\Core\Settings\Settings::instance()->get_settings_option();
			$attendee_extra_fields = isset( $settings['attendee_extra_fields'] ) ? $settings['attendee_extra_fields'] : [];

			$extra_field_array = [];

			if ( is_array( $attendee_extra_fields ) && ! empty( $attendee_extra_fields ) ) {
				foreach ( $attendee_extra_fields as $attendee_extra_field ) {

					$label_content = $attendee_extra_field['label'];
					if ( $label_content != '' ) {
						$extra_field_type         = $attendee_extra_field['type'];
						$name_from_label['label'] = $label_content;
						$name_from_label['type']  = $extra_field_type;

						if ( $extra_field_type == 'radio' ) {
							$name_from_label['type_radio_arr'] = isset( $attendee_extra_field['radio'] ) ? $attendee_extra_field['radio'] : []; // rel 2.5
						} else if ( $extra_field_type == 'checkbox' ) {
							$name_from_label['type_checkbox_arr'] = isset( $attendee_extra_field['checkbox'] ) ? $attendee_extra_field['checkbox'] : []; // rel 2.5
						}

						$name_from_label['name'] = \Etn_Pro\Utils\Helper::generate_name_from_label( "etn_attendee_extra_field_", $label_content );
						array_push( $extra_field_array, $name_from_label );
					}
				}

			}

			$attendee_array = [];
			foreach ( $post_ids as $post_id ) {
				//create attendee array
				$attendee_array[ $post_id ]['etn_name'] = get_the_title( $post_id );

				if ( ! empty( Helper::get_option( 'reg_require_email' ) ) ) {
					$attendee_array[ $post_id ]['etn_email'] = get_post_meta( $post_id, 'etn_email', true );
				}

				if ( ! empty( Helper::get_option( 'reg_require_phone' ) ) ) {
					$attendee_array[ $post_id ]['etn_phone'] = get_post_meta( $post_id, 'etn_phone', true );
				}

				$attendee_array[ $post_id ]['ticket_id']      = '#' . get_post_meta( $post_id, 'etn_unique_ticket_id', true );
				$attendee_array[ $post_id ]['ticket_name']    = ! empty( get_post_meta( $post_id, 'ticket_name', true ) ) ? get_post_meta( $post_id, 'ticket_name', true ) : ETN_DEFAULT_TICKET_NAME;
				$attendee_array[ $post_id ]['event_name']     = get_the_title( get_post_meta( $post_id, 'etn_event_id', true ) );
				$attendee_array[ $post_id ]['payment_status'] = get_post_meta( $post_id, 'etn_status', true );
				$attendee_array[ $post_id ]['ticket_status']  = get_post_meta( $post_id, 'etn_attendeee_ticket_status', true );

			}

			$this->download_attendee_information_csv( $attendee_array, $extra_field_array );

			$redirect_url = add_query_arg( 'download-attendee-csv', count( $post_ids ), $redirect_url );
		}

		return $redirect_url;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function attendee_show_notice_after_download_csv() {

		if ( ! empty( $_REQUEST['download-attendee-csv'] ) ) {
			$num_downloaded = (int) $_REQUEST['download-attendee-csv'];
			printf( '<div id="message" class="updated notice is-dismissable"><p>' . esc_html__( 'Downloaded %d attendee information.', 'eventin-pro' ) . '</p></div>', $num_downloaded );
		}

	}

	/**
	 * Export CSV file with selected attendees
	 *
	 * @param [type] $attendee_array
	 *
	 * @return void
	 */
	public function download_attendee_information_csv( $attendee_array, $extra_field_array ) {

		$generated_date      = date( 'd-m-Y His' ); //Date will be part of file name.
		$table_title_array[] = esc_html__( 'Id', 'eventin-pro' );
		$table_title_array[] = esc_html__( 'Name', 'eventin-pro' );
		if ( ! empty( Helper::get_option( 'reg_require_email' ) ) ) {
			$table_title_array[] = esc_html__( 'Email', 'eventin-pro' );
		}
		if ( ! empty( Helper::get_option( 'reg_require_phone' ) ) ) {
			$table_title_array[] = esc_html__( 'Phone', 'eventin-pro' );
		}
		$table_title_array[] = esc_html__( 'Event Name', 'eventin-pro' );
		$table_title_array[] = esc_html__( 'Payment Status', 'eventin-pro' );
		$table_title_array[] = esc_html__( 'Ticket Status', 'eventin-pro' );
		$table_title_array[] = esc_html__( 'Ticket ID', 'eventin-pro' );
		$table_title_array[] = esc_html__( 'Ticket Name', 'eventin-pro' );

		//add extra fields label to csv file header row
		foreach ( $extra_field_array as $extra_field ) {
			array_push( $table_title_array, $extra_field['label'] );
		}

		header( "Content-type: text/csv" );
		header( "Content-Disposition: attachment; filename=\"etn_attendee_info_" . $generated_date . ".csv\";" );
		ob_end_clean();

		// create a file pointer connected to the output stream
		$output = fopen( 'php://output', 'w' ) or die( "Can\'t open php://output" );

		// output the column headings
		fputcsv(
			$output,
			$table_title_array
		);

		foreach ( $attendee_array as $key => $value ) {
			$table_content_row = [];
			array_push( $table_content_row, $key );
			array_push( $table_content_row, $value['etn_name'] );
			if ( ! empty( Helper::get_option( 'reg_require_email' ) ) ) {
				array_push( $table_content_row, $value['etn_email'] );
			}
			if ( ! empty( Helper::get_option( 'reg_require_phone' ) ) ) {
				array_push( $table_content_row, "=\"" . $value['etn_phone'] . "\"" );
			}
			array_push( $table_content_row, $value['event_name'] );
			array_push( $table_content_row, $value['payment_status'] );
			array_push( $table_content_row, $value['ticket_status'] );
			array_push( $table_content_row, $value['ticket_id'] );
			array_push( $table_content_row, $value['ticket_name'] );

			//add extra field data to row
			foreach ( $extra_field_array as $extra_field ) {
				$extra_field_meta_key = $extra_field['name'];
				$post_meta_value      = get_post_meta( $key, $extra_field_meta_key, true );

				if ( $extra_field['type'] == 'radio' && isset( $extra_field['type_radio_arr'][ $post_meta_value ] ) ) {
					$post_meta_value = $extra_field['type_radio_arr'][ $post_meta_value ];
				}

				if ( $extra_field['type'] == 'checkbox' ) {
					$defined_checkbox_arr = $extra_field['type_checkbox_arr'];
					$saved_checkbox_arr   = maybe_unserialize( $post_meta_value );

					$post_meta_value = '';
					if ( is_array( $defined_checkbox_arr ) && count( $defined_checkbox_arr ) > 0 && is_array( $saved_checkbox_arr ) && count( $saved_checkbox_arr ) > 0 ) {
						$selected_checkbox = array_intersect_key( $defined_checkbox_arr, array_flip( $saved_checkbox_arr ) );
						$post_meta_value   = join( ', ', $selected_checkbox );
					}
				}

				if ( $extra_field['type'] == 'date' ) {
					$date_options    = Helper::get_date_formats();
					$selected_format = Helper::get_option( 'date_format' );
					$post_meta_value = ! empty( $post_meta_value ) ? ( ! empty( $selected_format ) ? date_i18n( $date_options[ $selected_format ], strtotime( $post_meta_value ) ) : date_i18n( get_option( 'date_format' ), strtotime( $post_meta_value ) ) ) : '';
				}

				array_push( $table_content_row, "=\"" . $post_meta_value . "\"" );
			}

			fputcsv(
				$output,
				$table_content_row
			);

		}

		// Close output file stream
		fclose( $output );

		die();
	}

	/**
	 * Attendee qr callback
	 *
	 * @param [integer] $attendee_id
	 * @param [integer] $event_id
	 *
	 * @return void
	 */
	public function etn_pro_ticket_qr_cb( $attendee_id, $event_id ) {
		?>
        <p class="etn-ticket-qr-code">
			<?php
			echo esc_html__( "Scan the QR code: ", "eventin-pro" );
			?>
        </p>
        <img class="etn-qrImage" src="" alt="" id="qrImage"/>
		<?php
	}

	/**
	 * Attendee ticket id callback
	 *
	 * @param [integer] $attendee_id
	 * @param [integer] $event_id
	 *
	 * @return void
	 */
	public function etn_pro_ticket_id_cb( $attendee_id, $event_id ) {
		$unique_id         = get_post_meta( $attendee_id, 'etn_unique_ticket_id', true );
		$ticket_verify_url = admin_url( '/edit.php?post_type=etn-attendee&etn_action=ticket_scanner' );
		$ticket_verify_url .= "&attendee_id=$attendee_id&ticket_id=$unique_id";
		?>
        <li class="etn-ticket-body-top-li">
            <span><?php echo esc_html__( "ID:", "eventin-pro" ); ?></span>
            <p class="etn-ticket-id" id="ticketUnqId"
               data-ticketverifyurl="<?php echo esc_url( $ticket_verify_url ) ?>"><?php echo esc_html( $unique_id ); ?></p>
        </li>
		<?php
	}

	/**
	 * scanner module working functionalities
	 *
	 * @return void
	 */
	public function scanner_module_functionalities() {
		$event_id    = isset( $_GET['event_id'] ) ? intval( $_GET['event_id'] ) : 0;
		$attendee_id = isset( $_GET['attendee_id'] ) ? intval( $_GET['attendee_id'] ) : 0;

		if ( isset( $_GET['etn_action'] ) && sanitize_text_field( $_GET['etn_action'] ) == 'ticket_scanner' ) {

			if ( $event_id && ! $this->has_attendee( $event_id ) ) {
				wp_die( sprintf( 'No ticket found for this event. Back to <a href="%1$s">%2$s</a>', admin_url( 'edit.php?post_type=etn' ), __( 'Events', 'eventin-pro' ) ), __( 'Not found attendee', 'eventin-pro' ) );
				exit;
			}

			if ( isset( $_GET['security'] ) ) {

				if ( is_user_logged_in() ) {
					if ( $event_id && ! $this->is_valid_attendee( $attendee_id, $event_id ) ) {
						wp_send_json_error( [
							'messages' => [ __( 'Invalid ticket. This ticket is not valid for this event', 'eventin-pro' ) ],
						] );
					}

					if ( ! wp_verify_nonce( sanitize_text_field( $_GET['security'] ), 'scanner_nonce_value' ) ) {
						$response = [
							'status_code' => 0,
							'messages'    => [ esc_html__( 'Nonce is not valid! Please try again.', 'eventin-pro' ) ],
							'content'     => [],
						];
						wp_send_json_error( $response );
						exit();
					} else {
						// ajax response
						if ( isset( $_GET['ticket_id'] ) && isset( $_GET['attendee_id'] ) ) {
							if ( isset( $_GET['ticket_info'] ) ) {
								$response = $this->validate_ticket( $_GET['ticket_id'], $_GET['attendee_id'], absint( $_GET['ticket_info'] ) );
								wp_send_json_success( $response );
								exit();
							} else if ( isset( $_GET['scanner_active'] ) ) {
								$response = $this->validate_ticket( $_GET['ticket_id'], $_GET['attendee_id'] );
								wp_send_json_success( $response );
								exit();
							}
						}
					}
				} else {
					$status_code             = 3;
					$content['redirect_url'] = wp_login_url( admin_url( '/edit.php?post_type=etn-attendee&etn_action=ticket_scanner' ) );
					$messages[]              = esc_html__( 'Organiser is not logged in. Please login first.', 'eventin-pro' );

					$response = [
						'status_code' => $status_code,
						'messages'    => $messages,
						'content'     => $content,
					];

					wp_send_json_error( $response );
					exit();
				}
			} else {

				if (
					is_user_logged_in()
					&&
					( current_user_can( 'manage_etn_attendee' ) ||
					  current_user_can( 'seller' ) || current_user_can( 'author' ) )
				) {

					// copied url from third party
					if ( isset( $_GET['ticket_id'] ) && isset( $_GET['attendee_id'] ) && ! isset( $_GET['scanner_active'] ) ) {
						$settings                    = Helper::get_settings();
						$attendee_verification_style = isset( $settings["attendee_verification_style"] ) ? $settings["attendee_verification_style"] : 'on';
						$ticket_info                 = false;
						if ( $attendee_verification_style ) {
							$ticket_info = true;
						}
						$scanned_response = $this->validate_ticket( $_GET['ticket_id'], $_GET['attendee_id'], $ticket_info );
					}

					// include scanner file
					if ( ! isset( $_GET['scanner_active'] ) || ( isset( $_GET['scanner_active'] ) && $_GET['scanner_active'] != 'true' ) ) {
						if ( file_exists( \Wpeventin_Pro::core_dir() . "attendee/scanner.php" ) ) {
							include_once \Wpeventin_Pro::core_dir() . "attendee/scanner.php";
						}
					}
				} else {
					$new_redirect_url = admin_url( '/edit.php?post_type=etn-attendee&etn_action=ticket_scanner' );
					if ( isset( $_GET['ticket_id'] ) ) {
						$new_redirect_url .= '&ticket_id=' . sanitize_text_field( $_GET['ticket_id'] );
					}

					if ( isset( $_GET['attendee_id'] ) ) {
						$new_redirect_url .= '&attendee_id=' . absint( $_GET['attendee_id'] );
					}
					wp_safe_redirect(
						wp_login_url( $new_redirect_url )
					);
					exit();
				}
			}

			die();
		}

		return;
	}

	/**
	 * check ticket id from qr scanner is valid and update ticket status accordingly
	 *
	 * @return array status_code, messages, content
	 */
	public function validate_ticket($ticket_id, $attendee_id, $ticket_info = 0) {
		$status_code = 0;
		$messages = [];
		$content = [];
		$request = [
			'ticket_id' => $ticket_id,
			'attendee_id' => $attendee_id,
		];
	
		if (is_user_logged_in() && (current_user_can('manage_etn_attendee') || current_user_can('seller') || current_user_can('author'))) {
			$inputs_field = [
				[
					'name' => 'attendee_id',
					'required' => true,
					'type' => 'number'
				],
				[
					'name' => 'ticket_id',
					'required' => true,
					'type' => 'text'
				],
			];
	
			$validation = Helper::input_field_validation($request, $inputs_field);
	
			if (!empty($validation['status_code'])) {

				$input_data = $validation['data'];
				$attendee_id = $input_data['attendee_id'];
				$ticket_id = $input_data['ticket_id'];
				$event_id = get_post_meta($attendee_id, 'etn_event_id', true);

				if ($ticket_info) {
					if (!empty($attendee_id) && !empty($ticket_id)) {
						if (!empty($event_id)) {
							$event_name = get_post_field('post_title', $event_id, 'raw');
							$attendee_name = get_post_meta($attendee_id, 'etn_name', true);
							$ticket_name = !empty(get_post_meta($attendee_id, 'ticket_name', true)) ? html_entity_decode(get_post_meta($attendee_id, 'ticket_name', true), ENT_QUOTES | ENT_HTML5, 'UTF-8') : ETN_DEFAULT_TICKET_NAME;
	
							if (!empty($event_name) && !empty($attendee_name) && !empty($ticket_name)) {
								$status_code = 1;
								$msg = esc_html__('ok', 'eventin-pro');
								$content = [
									'event_name' => $event_name,
									'attendee_name' => esc_html__('Attendee: ', 'eventin-pro') . $attendee_name,
									'ticket_name' => esc_html__('Ticket Type: ', 'eventin-pro') . $ticket_name,
									'ticket_Message' => esc_html__('Want to let him in? ', 'eventin-pro'),
								];
							} else {
								$msg = esc_html__('Information is missing for this ticket. Please try again.', 'eventin-pro');
							}
						} else {
							$msg = esc_html__('Event not found for this ticket.', 'eventin-pro');
						}
					} else {
						$msg = esc_html__('Attendee ID or Ticket ID is missing', 'eventin-pro');
					}
	
					$messages[] = $msg;
				} else {
					$ticket_status_data = $this->check_ticket_id($attendee_id, $ticket_id, $event_id);
					$messages[] = $ticket_status_data['msg'];
					if (!empty($ticket_status_data['update_status'])) {
						$status_code = $ticket_status_data['update_status'];
						$content = [
							'name' => get_post_meta($attendee_id, 'etn_name', true),
							'event_name' => get_post_field('post_title', $event_id, 'raw'),
							'ticket_name' => !empty(get_post_meta($attendee_id, 'ticket_name', true)) ? html_entity_decode(get_post_meta($attendee_id, 'ticket_name', true), ENT_QUOTES | ENT_HTML5, 'UTF-8') : ETN_DEFAULT_TICKET_NAME
						];
					}
				}
			} else {
				$status_code = $validation['status_code'];
				$messages = $validation['messages'];
			}
		} else {
			$status_code = 3;
			$content['redirect_url']	= wp_login_url(admin_url('/edit.php?post_type=etn-attendee&etn_action=ticket_scanner'));
			$messages[]					= esc_html__('Organizer must be logged in to backend panel and should have attendee manage permission.', 'eventin-pro');
		}

		return [
			'status_code' => $status_code,
			'messages'    => $messages,
			'content'     => $content,
		];

	}
	

	/**
	 * check ticket status and update
	 *
	 * @param [type] $attendee_id
	 * @param [type] $ticket_id
	 *
	 * @return array
	 */
	public function check_ticket_id( $attendee_id = null, $ticket_id = null, $event_id = null) {

		$msg           = '';
		$update_status = false;
		$event_status  = EventPro::instance()->etn_get_event_status( $event_id, false );

		if ( ! empty( $attendee_id ) && ! empty( $ticket_id ) ) {
			$unique_id = get_post_meta( $attendee_id, 'etn_unique_ticket_id', true );


			if ( $unique_id === $ticket_id ) {
				$ticket_status = get_post_meta( $attendee_id, 'etn_attendeee_ticket_status', true );
				$payment_status = get_post_meta( $attendee_id, 'etn_status', true );

				if( $event_status === 'Expired' ){

					$update_status = 1;
					$msg           = esc_html__( 'Event is already expired.', 'eventin-pro' );

				} elseif ( $ticket_status == 'used' ) {

					$update_status = 1;
					$msg           = esc_html__( 'Ticket is already used.', 'eventin-pro' );

				}elseif ( 'failed' == $payment_status ) {

					$update_status = 1;
					$msg           = esc_html__( 'Payment Status Failed.', 'eventin-pro' );

				}else {

					update_post_meta( $attendee_id, 'etn_attendeee_ticket_status', 'used' );
					$update_status = 2;
					$msg           = esc_html__( 'The Ticket is Valid. Successfully Checked in.', 'eventin-pro' );

				}

			} else {
				$msg = esc_html__( "Invalid ticket ID. Please try again.", 'eventin-pro' );
			}
		}

		$response = [
			'msg'           => $msg,
			'update_status' => $update_status,
		];

		return $response;
	}

	/**
	 * Show Attendee Certificate On Front-end
	 *
	 * @return void
	 * @since 3.5.6
	 */
	public function show_attendee_certificate() {
		if ( isset( $_GET['etn_action'] ) && sanitize_text_field( $_GET['etn_action'] ) === 'download_certificate' ) {

			$input = filter_input_array( INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

			if ( empty( $input["attendee_id"] ) || empty( $input["etn_info_edit_token"] ) || empty( $input["event_id"] ) || ! Helper::verify_attendee_edit_token( $input["attendee_id"], $input["etn_info_edit_token"] ) ) {
				Helper::show_404();
			}

			// All ok, proceed...
			$event_id    = intval( $input["event_id"] );
			$attendee_id = intval( $input["attendee_id"] );
			$page_id     = ! empty( get_post_meta( $event_id, 'certificate_template', true ) ) ? intval( get_post_meta( $event_id, 'certificate_template', true ) ) : false;

			if ( ! $page_id ) {
				Helper::show_404();
			}

			$certificate_page = get_page_link( $page_id ) . '?event_id=' . urlencode( $event_id ) . '&attendee_id=' . urlencode( $attendee_id );
			wp_redirect( $certificate_page );
			exit;
		}

		return;
	}

	/**
	 * Get attendee by event id
	 *
	 * @param integer $event_id Event id
	 *
	 * @return  array
	 */
	public function get_attendee_by_event( $event_id ) {
		$attendees = get_posts( [
			'post_type'   => 'etn-attendee',
			'post_status' => 'publish',
			'fields'      => 'ids',
			'numberposts' => - 1,
			'meta_query'  => [
				[
					'key'     => 'etn_event_id',
					'value'   => $event_id,
					'compare' => '=',
				],
			],
		] );

		return $attendees;
	}

	/**
	 * Check an event has attendees or not
	 *
	 * @param integer $event_id Event id
	 *
	 * @return bool
	 */
	public function has_attendee( $event_id ) {
		return count( $this->get_attendee_by_event( $event_id ) ) > 0;
	}

	/**
	 * Check a attendee is valid for certain event
	 *
	 * @param integer $attendee_id Attendee id for the current event
	 * @param integer $event_id Current event id
	 *
	 * @return  bool
	 */
	public function is_valid_attendee( $attendee_id, $event_id ) {
		$attendess = $this->get_attendee_by_event( $event_id );

		return in_array( $attendee_id, $attendess );
	}

	/**
	 * Certificate pdf template
	 *
	 * @return  bool
	 */

	public function certificate_add_template_to_select( $post_templates, $wp_theme, $post, $post_type ) {
		// Add custom template named template-custom.php to select dropdown
		$post_templates['template-pdf-certificate.php'] = esc_html__( 'Eventin Certificate', 'eventin-pro' );

		return $post_templates;
	}

	/**
	 * Load template from specific page
	 */
	public function certificate_page_template( $page_template ) {

		if ( get_page_template_slug() == 'template-pdf-certificate.php' ) {
			$page_template = dirname( __FILE__ ) . '/template-pdf-certificate.php';
		}

		return $page_template;
	}

	/**
	 * Create stripe order for manual attendee
	 *
	 * @param object $post
	 * @param array $data
	 *
	 * @return  void
	 */
	public function create_manual_stripe_order( $post_arr ) {
		$event_id                                    = isset( $post_arr['etn_event_id'] ) ? sanitize_text_field( $post_arr['etn_event_id'] ) : '';
		$ticket_name                                 = isset( $post_arr['ticket_name'] ) ? sanitize_text_field( $post_arr['ticket_name'] ) : '';
		$etn_email                                   = isset( $post_arr['etn_email'] ) ? sanitize_text_field( $post_arr['etn_email'] ) : '';
		$ticket_price                                = isset( $post_arr['ticket_price'] ) ? sanitize_text_field( $post_arr['ticket_price'] ) : 0;
		$total_price                                 = isset( $post_arr['_etn_variation_total_price'] ) ? floatval( $post_arr['_etn_variation_total_price'] ) : $ticket_price;
		$quantity                                    = isset( $post_arr['_etn_variation_total_quantity'] ) ? intval( $post_arr['_etn_variation_total_quantity'] ) : 1;
		$etn_ticket_variations                       = $this->create_ticket_variation( $event_id, $ticket_name );
		$attendee_info_update_key                    = md5( md5( "etn-access-token" . time() . $quantity ) );
		
		$currency      = Helper::instance()->retrieve_country_currency( true );
		$currency_code = ! empty( $currency ) ? $currency['currency'] : 'USD';
		$num_decimals  = ! empty( $currency ) ? $currency['num_decimals'] : '2';
		$data          = [
			'fname'                    => $ticket_name,
			'lname'                    => '',
			'email'                    => $etn_email,
			'picked_ticket_variations' => $etn_ticket_variations,
			'etn_total_price'          => $total_price,
			'etn_total_qty'            => $quantity,
			'etn_status_update_token'  => md5( md5( "etn-access-token" . time() . $quantity ) ),
			'check_id'                 => \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->get_uuid(),
			'currency_code'            => $currency_code,
			'num_decimals'             => $num_decimals
		];

		// create stripe order
		$order_id = \Etn_Pro\Core\Modules\Sells_Engine\Stripe\Stripe::instance()->stripe_order_creation( $data, true );

		// send ticket email
		$ticket_email = array('event_id'=> $post_arr['etn_event_id'] , 'order_id' => $order_id ,
		'attendee_info_update_key' => $attendee_info_update_key , 'gateway' => 'stripe' );

		$this->send_ticket_email( $ticket_email );
		
		// update stock, mail, purchase history table
		\Etn_Pro\Core\Modules\Sells_Engine\Sells_Engine::instance()->stripe_db_functionalities( $event_id, $order_id , 1 );
		
		$meta_s = array("etn_attendee_order_id"=> $order_id );
		$this->save_meat_values( $post_arr['post_ID'] , $meta_s );

	}
	
	/**
	 * create ticket array
	 *
	 * @param [type] $event_id
	 * @param [type] $ticket_name
	 * @return void
	 */
	public function create_ticket_variation( $event_id, $ticket_name ) {
		$etn_ticket_variations                       = array();
		$etn_ticket_variations[0]['etn_ticket_slug'] = \Etn\Utils\Helper::generate_unique_slug_from_ticket_title( $event_id, $ticket_name );
		$etn_ticket_variations[0]['etn_ticket_name'] = $ticket_name;
		$etn_ticket_variations[0]['etn_ticket_qty']  = 1;

		return $etn_ticket_variations;
	}

	public function save_meat_values( $id , $meta_s ) {
		foreach ( $meta_s as $key => $value) {
			update_post_meta( $id , $key, $value );
		}
	}

	/**
	 * Manual create order for attendee
	 */
	public function manual_create_order( $post_arr = array() ) {
		$_etn_variation_total_quantity = ! empty( $post_arr['_etn_variation_total_quantity'] ) ? $post_arr['_etn_variation_total_quantity'] : 1;
		$_etn_variation_total_price    = ! empty( $post_arr['_etn_variation_total_price'] ) ? $post_arr['_etn_variation_total_price'] : $post_arr['ticket_price'];
		$attendee_info_update_key      = md5( md5( "etn-access-token" . time() . $_etn_variation_total_quantity ) );
		$attendee_name                 = ! empty( $post_arr['etn_name'] ) ? $post_arr['etn_name'] : '';
		$ticket_name                   = ! empty( $post_arr['ticket_name'] ) ? $post_arr['ticket_name'] : '';
		$etn_email                     = ! empty( $post_arr['etn_email'] ) ? $post_arr['etn_email'] : '';
		$price                         = ! empty( $post_arr['ticket_price'] ) ? $post_arr['ticket_price'] : 0;

		// update manual attendee data
		$this->update_meta_manual_attendee( $post_arr );
		// create¸manual order
		$order = $this->create_manual_order( $post_arr , $attendee_name , $price );

		// add order meta
		foreach ( $order->get_items() as $item_id => $item ) {
			wc_add_order_item_meta( $item_id, esc_html__( "Ticket name", "eventin-pro" ), $ticket_name . ' * ' . $_etn_variation_total_quantity );
			wc_add_order_item_meta( $item_id, '_etn_variation_total_price', $_etn_variation_total_price );
			wc_add_order_item_meta( $item_id, '_etn_variation_total_quantity', $_etn_variation_total_quantity );
			wc_add_order_item_meta( $item_id, 'etn_status_update_key', $attendee_info_update_key );
		}

		$order->update_meta_data( '_manual_attendee', 1 );

		$order->save();

		if ( $order ) {
			// insert purchase report
			$purchase_report = array( 'post_arr' => $post_arr , 'order_id' => $order->get_id() ,
				'etn_email' => $etn_email,
				'ticket_name' => $ticket_name,
				'_etn_variation_total_price' => $_etn_variation_total_price,
				'_etn_variation_total_quantity' => $_etn_variation_total_quantity,
				'payment_type' => 'woocommerce',
				'payment_status' => 'Completed',

			);
			$this->save_purchase_report( $purchase_report );
			// add order id for attendee
			$unique_key             = md5( microtime().rand() );
			update_post_meta( $post_arr['post_ID'], "unique_key", $unique_key );

			$meta_s = array("etn_attendee_order_id"=> $order->get_id() );

			$this->save_meat_values( $post_arr['post_ID'] , $meta_s );
			// add attendee/mail/pdf
			\Etn\Core\Woocommerce\Hooks::instance()->process_all_once_order_created( $order->get_id(), $order );
			// update stock
			$etn_total_sold_tickets = absint( get_post_meta( $post_arr['etn_event_id'], "etn_total_sold_tickets", true ) );
			$updated_total_sold_tickets = $etn_total_sold_tickets + $_etn_variation_total_quantity;
			update_post_meta( $post_arr['etn_event_id'], "etn_total_sold_tickets", $updated_total_sold_tickets );
			
			// send ticket email
			$ticket_email = array('event_id'=> $post_arr['etn_event_id'] , 'order_id' => $order->get_id() ,
			'attendee_info_update_key' => $attendee_info_update_key , 'gateway' => 'woocommerce'  );
			$this->send_ticket_email( $ticket_email );
			// fluent crm action
			process_fluent_crm_data_after_order_created( $order->get_id() );
		}

	}

	private function save_purchase_report( $args ){
		extract($args);
		$etn_ticket_variations  = $this->create_ticket_variation( $post_arr['etn_event_id'] , $ticket_name );

		$purchase_report = array(
			'insert_post_id'=>$post_arr['etn_event_id'],
			'insert_form_id'=>$order_id,
			'insert_event_amount'=>$_etn_variation_total_price,
			'insert_ticket_qty'=> $_etn_variation_total_quantity,
			'insert_ticket_variation'=>serialize($etn_ticket_variations),
			'insert_user_id'=> get_current_user_id(),
			'insert_email'=> $etn_email,
			'insert_event_type'=>'ticket',
			'insert_payment_type'=>$payment_type,
			'insert_pledge_id'=>'',
			'insert_payment_gateway'=>'cod',
			'insert_date_time'=> date( "Y-m-d" ),
			'insert_status'=> $payment_status 
		);

		$this->insert_purchase_report( $purchase_report );
	}

	private function send_ticket_email( $ticket_email ) {
		extract($ticket_email);
		$product_name = get_the_title($event_id);
		\Etn\Utils\Helper::mail_info_data( $event_id, $order_id , $product_name, $attendee_info_update_key , $gateway );
	}

	public function insert_purchase_report($args) {
		global $wpdb;
		extract($args);
		$insert_invoice         = get_post_meta( $insert_form_id, '_order_key', true );
		$wpdb->query( "INSERT INTO `". ETN_EVENT_PURCHASE_HISTORY_TABLE ."` (`post_id`, `form_id`, `invoice`, `event_amount`, `ticket_qty`, `ticket_variations`, `user_id`, `email`, `event_type`, `payment_type`, `pledge_id`, `payment_gateway`, `date_time`, `status`) VALUES 
			('$insert_post_id', '$insert_form_id', '$insert_invoice', '$insert_event_amount', '$insert_ticket_qty', '$insert_ticket_variation', '$insert_user_id', '$insert_email', '$insert_event_type', '$insert_payment_type', '$insert_pledge_id', '$insert_payment_gateway', '$insert_date_time', '$insert_status')" );
	}
	
	/**
	 * Create Order object
	 */
	public function create_manual_order( $post_arr , $attendee_name , $price ) {
		$address = array(
			'first_name' => $attendee_name,
			'last_name'  => '',
			'company'    => '',
			'email'      => ! empty( $post_arr['etn_email'] ) ? $post_arr['etn_email'] : '',
			'phone'      => ! empty( $post_arr['etn_phone'] ) ? $post_arr['etn_phone'] : '',
			'address_1'  => '',
			'address_2'  => '',
			'city'       => '',
			'state'      => '',
			'postcode'   => '',
			'country'    => get_bloginfo("language"),
		);

		$prices = array(
			'totals' => array(
				'subtotal' => $price,
				'total'    => $price,
			)
		);

		$order = wc_create_order();

		$order->add_product( wc_get_product( $post_arr['etn_event_id'] ), 1, $prices );
		$order->set_address( $address, 'billing' );
		$order->set_address( $address, 'shipping' );
		$order->calculate_totals();
		$order->update_status( "completed", esc_html__( 'Imported order', 'eventin-pro' ), true );

		return $order;
	}

	/**
	 * Add manual attendee meta data
	 */
	public function update_meta_manual_attendee( $post_arr ) {
		if ( empty( $post_arr ) || empty( $post_arr['post_ID'] ) ) {
			return true;
		}

		$_etn_variation_total_quantity = ! empty( $post_arr['_etn_variation_total_quantity'] ) ? $post_arr['_etn_variation_total_quantity'] : 1;
		$attendee_info_update_key      = md5( md5( "etn-access-token" . time() . $_etn_variation_total_quantity ) );
		$ticket_name                   = ! empty( $post_arr['ticket_name'] ) ? $post_arr['ticket_name'] : '';
		$price                         = ! empty( $post_arr['ticket_price'] ) ? $post_arr['ticket_price'] : 0;
		$attendee_name                 = ! empty( $post_arr['etn_name'] ) ? $post_arr['etn_name'] : '';

		// add attendee name
		$post_update = array(
			'ID'         => $post_arr['post_ID'],
			'post_title' => $attendee_name,
		);
		// update title
		wp_update_post( $post_update );

		// update ticket slug
		update_post_meta( $post_arr['post_ID'], "etn_unique_ticket_id", Helper::generate_unique_ticket_id_from_attendee_id( $post_arr['post_ID'] ) );
		// add form token
		update_post_meta( $post_arr['post_ID'], 'etn_info_edit_token', $attendee_info_update_key );
		update_post_meta( $post_arr['post_ID'], 'etn_status_update_token', $attendee_info_update_key );
		update_post_meta( $post_arr['post_ID'], 'ticket_name', $ticket_name );
		update_post_meta( $post_arr['post_ID'], 'etn_ticket_price', $price );
	}

	/**
	 * Get event ticket variations
	 *
	 * @return  void
	 */
	public function get_attendee_event() {
		$event_id = isset( $_GET['event_id'] ) ? intval( $_GET['event_id'] ) : 0;

		if ( ! $event_id ) {
			return;
		}

		$ticket_variations = get_post_meta( $event_id, 'etn_ticket_variations', true );
		wp_send_json_success( $ticket_variations );
	}

	/**
	 * Get Attendee meta by Order
	 */
	public function attendee_meta_by_order( $order_id ) {
		$sells_engine = \Etn_Pro\Core\Modules\Sells_Engine\Sells_Engine::instance()->check_sells_engine();
		if ( "woocommerce" == $sells_engine ) {
			$order = wc_get_order( $order_id );
			$data  = "";
			if ( $order ) {
				foreach ( $order->get_items() as $key => $item ) {
					$data = wc_get_order_item_meta( $key, esc_html__( "Ticket name", "eventin-pro" ) );
				}
			}

		} else {
			$variations  = get_post_meta( $order_id, "etn_ticket_variations_picked", true );
			$ticket_name = ! empty( $variations[0]['etn_ticket_name'] ) ? $variations[0]['etn_ticket_name'] : "";
			$data        = $ticket_name . "*" . get_post_meta( $order_id, "etn_variation_total_quantity", true );
		}


		return $data;
	}

	/**
	 * Show attendee checkbox
	 */

	public function bulk_attendee_checkbox( $args ) {

		$settings             = \Etn\Core\Settings\Settings::instance()->get_settings_option();
		$enable_attendee_bulk = ( isset( $settings['enable_attendee_bulk'] ) ? 'checked' : '' );

		if ( $enable_attendee_bulk == 'checked' ):
			?>
            <div class="etn-bulk-attendee">
                <input type="checkbox" name="etn_bulk_attendee" id="etn_bulk_attendee" class="etn_bulk_attendee"
                       value="" data-total_ticket_quantity="<?php echo esc_attr( $args['ticket_total_quantity'] ); ?>"/>
                <label class="etn_bulk_attendee_label"
                       for="etn_bulk_attendee"><?php echo esc_html__( 'Enable Bulk Attendee', 'eventin-pro' ); ?></label>
            </div>
		<?php
		endif;

	}
}
