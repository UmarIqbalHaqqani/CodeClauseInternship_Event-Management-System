<?php

namespace Etn_Pro\Core\Modules\Rsvp\Admin;

defined( 'ABSPATH' ) || die();

class Admin {

	use \Etn_Pro\Traits\Singleton;

	public function init() {

		// enqueue scripts
		$this->enqueue_scripts();
		
		// RSVP Single page meta boxes
		\Etn_Pro\Core\Modules\Rsvp\Admin\Metaboxs\Metabox::instance()->init();

		// add menu pages
		$admin_menu = ['admin_rsvp_invitations', 'admin_rsvp_reports'];
		foreach ( $admin_menu as $value ) {
			//admin page for RSVP invitation and report
			add_action( 'admin_menu', [$this, $value], 99 );
		}

	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		add_action( 'admin_enqueue_scripts', array( $this, 'js_css_admin' ) );
	}

	/**
	 *  Admin scripts.
	 */
	public function js_css_admin() {
		$screen = get_current_screen();
		// Main script of rsvp script and js
		wp_enqueue_script( 'etn-rsvp-admin-js', ETN_PRO_CORE . 'modules/rsvp/assets/js/etn-rsvp-admin.js', ['jquery'], \Wpeventin_Pro::version(), false );
		// style
		if ( 'admin_page_etn_rsvp_report' == $screen->id ) {
			wp_enqueue_style( 'etn-rsvp-admin-css', ETN_PRO_CORE . 'modules/rsvp/assets/css/etn-rsvp-admin.css', [], \Wpeventin_Pro::version(), 'all' );
		}

		$form_data             = array();
		$form_data['ajax_url'] = admin_url( 'admin-ajax.php' );
		wp_localize_script( 'etn-rsvp-admin-js', 'localized_rsvp_admin_data', $form_data );
	}

	

	/**
	 * Add RSVP invitation page for admin
	 */
	public function admin_rsvp_invitations() {
		if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) || current_user_can( 'manage_etn_event' ) ) {
			add_submenu_page(
				'etn-events-manager',
				esc_html__( 'RSVP Invitations', 'eventin-pro' ),
				esc_html__( 'RSVP Invitations', 'eventin-pro' ),
				'read',
				'etn_rsvp_invitation',
				[$this, 'rsvp_invitation'],
				3
			);
		}
	}

	/**
	 * Show rsvp invitations list
	 *
	 */
	public function rsvp_invitation() {
		if ( file_exists( ETN_PRO_DIR . "/core/modules/rsvp/admin/views/invitations.php" ) ) {
			include ETN_PRO_DIR . "/core/modules/rsvp/admin/views/invitations.php";
		}
	}

	/**
	 * Add RSVP report page for admin
	 */
	public function admin_rsvp_reports() {
		if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) || current_user_can( 'manage_etn_event' ) ) {
			add_submenu_page(
				'',
				esc_html__( 'RSVP Report', 'eventin-pro' ),
				esc_html__( 'RSVP Report', 'eventin-pro' ),
				'manage_options',
				'etn_rsvp_report',
				[$this, 'rsvp_report'],
				3
			);
		}
	}

	/**
	 * Show rsvp report table
	 *
	 */
	public function rsvp_report() {
		$event_id = isset( $_GET['event_id'] ) ? intval( $_GET['event_id'] ) : 0;

		$columns = [
			'responser'       => esc_html__( 'Responser', 'eventin-pro' ),
			'total_attendees' => esc_html__( 'Total Attendees', 'eventin-pro' ),
			'attendees'       => esc_html__( 'Attendees', 'eventin-pro' ),
			'status'          => esc_html__( 'Status', 'eventin-pro' ),
		];

		$rsvp_list = [
			'singular_name' => esc_html__( 'RSVP Report', 'eventin-pro' ),
			'plural_name'   => esc_html__( 'RSVP Reports', 'eventin-pro' ),
			'event_id'      => $event_id,
			'columns'       => $columns,
		];
		$limit = get_post_meta( $event_id, 'etn_rsvp_limit_amount', true ) == "" ? 1 : get_post_meta( $event_id, 'etn_rsvp_limit_amount', true );
		?>
		<div class="wrap">
				<h1 class="wp-heading-inline rsvp-page-title"><?php echo esc_html__( 'RSVP Report', 'eventin-pro' ) ?></h1>
				<div class="rsvp-info-wrapper">
					<div class="rsvp-info-item">
						<p class="rsvp-info-subtext"><?php echo esc_html__( 'Event Name', 'eventin-pro' ); ?></p>
						<h2 class="rsvp-info-title">
							<a href="<?php echo esc_url(admin_url( 'post.php?post='.intval( $event_id ).'&action=edit'))?>">
							<?php echo esc_html__( get_the_title( $event_id ), 'eventin-pro' ); ?></a></h2>
					</div>
					<div class="rsvp-info-item rsvp-info-box-parent">
						<div class="rsvp-info-box">
							<p class="rsvp-info-subtext"><?php echo esc_html__( 'RSVP Limit', 'eventin-pro' ); ?></p>
							<h2 class="rsvp-info-title"><?php echo esc_html( $limit ); ?></h2>
						</div>
						<?php
							$rsvp_form_type = maybe_unserialize( get_post_meta( $event_id, 'etn_rsvp_form_type', true ) );
							$get_rsvp_data  = $this->rsvp_form_type_data( $event_id );

							if ( is_array( $rsvp_form_type ) ):
								foreach ( $rsvp_form_type as $key => $value ): ?>
									<div class="rsvp-info-box">
										<p class="rsvp-info-subtext"><?php echo esc_html__( $value, 'eventin-pro' ); ?></p>
										<h2 class="rsvp-info-title"><?php echo esc_html( $get_rsvp_data[$key] ); ?></h2>
									</div>
								<?php endforeach;
							endif;
						?>
					</div>
				</div>
				<div class="wrap etn-recurring-list">
					<form method="POST">
						<?php
							$table = new \Etn_Pro\Core\Modules\Rsvp\Admin\Table\Table( $rsvp_list );
									$table->preparing_items();
									$table->display();
								?>
					</form>
				</div>
			</div>
		<?php
	}

	/**
	 * Rsvp Form type data
	 */
	public function rsvp_form_type_data( $event_id ) {
		$rsvp_form_type = array();
		$form_type = maybe_unserialize( get_post_meta( $event_id, 'etn_rsvp_form_type', true ) );
		if ( is_array( $form_type ) ) {
			foreach ( $form_type as $key => $value ) {
				$rsvp_form_type[$key] = $this->rsvp_form_type_count( $event_id, $key );
			}
		} 
		
		return $rsvp_form_type;
	}

	/**
	 * Count attendee based on form type
	 * Yes,No,Maybe
	 */
	public function rsvp_form_type_count( $event_id = null, $meta_value = "" ) {
		$total_attendee = 0;
		$data           = get_posts(
			array(
				'post_type'  => "etn_rsvp",
				'meta_query' => [
					'relation' => 'AND',
					[
						'key'     => 'event_id',
						'value'   => $event_id,
						'compare' => "=",
					],
					[
						'key'     => "etn_rsvp_value",
						'value'   => $meta_value,
						'compare' => "=",
					],
				],
			)
		);
		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				$attendee = (int) get_post_meta( $value->ID, 'number_of_attendee', true );
				$total_attendee += $attendee;
			}
		}

		return $total_attendee;
	}

	/**
	 * RSVP short summary for single page
	 */
	public function get_rsvp_summary_markup( $event_id = null ){
		$html = "";
		$rsvp = \Etn_Pro\Core\Modules\Rsvp\Helper::instance()->get_rsvp();
		if ( $rsvp ) {
			$rsvp_form_type = maybe_unserialize(get_post_meta( $event_id ,'etn_rsvp_form_type',true));
			$get_rsvp_data = $this->rsvp_form_type_data( $event_id );
			if ( count($get_rsvp_data ) > 0 ) {
				$html .= "<div class='rsvp-info-box-parent'>";
				foreach ($rsvp_form_type as $key => $value) {
					$html .= "<div class='rsvp-info-box'><p class='rsvp-info-subtext'>";
					$html .= $value;
					$html .= "</p><p class='rsvp-info-title'>";
					$html .= $get_rsvp_data[$key];
					$html .= "</p></div>";
				}
				$html .= "</div>";
			}
			$html .= "<p>Go to the Link to get more details RSVP of the event. <a href='".admin_url('admin.php?page=etn_rsvp_report&event_id='.intval( $event_id ))."'>RSVP Report</a></p>";
		}
		else{
			$html = esc_html__( "RSVP form isn't submitted yet", "eventin-pro" );
		}
		return $html;
	}

}
