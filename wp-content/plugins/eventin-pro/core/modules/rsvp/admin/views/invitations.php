<?php
	use \Etn\Utils\Helper;
	use \Etn_Pro\Core\Modules\Rsvp\Helper as RSVPHelper;
	?>
	<?php
	// header menu start.
	include_once \Wpeventin::plugin_dir() . "templates/layout/header.php";
	// header menu end.
	?>
	<!-- General settings data tab -->
	<div class="wrap etn-rsvp-invitation">
		<span class="sent-message"></span>
		<h3><?php echo esc_html__( 'Send RSVP Invitations', 'eventin-pro' ); ?></h3>
		<form method="POST" class="rsvp_invitation_submit">
			<div class="etn-label-item form-group">
				<select style="max-width:100%;width:100%"  name="event_lists_invitations" id="event_lists_invitations" class="etn-form-control event_lists_invitations">
					<option value=""><?php echo esc_html__( 'Select an event', 'eventin-pro' ); ?></option>
					<?php
						$events = Helper::get_events(null, true, false, false);
						foreach ($events as $event_id => $event) {
							$recurrence_start_date = Helper::is_recurrence($event_id) ? Helper::single_template_options($event_id)['event_start_date'] : null;
							$event_display = $recurrence_start_date ? $event . ' - ' . $recurrence_start_date : $event;
							?>
							<option value="<?php echo esc_attr($event_id); ?>">
								<?php echo esc_html($event_display); ?>
							</option>
							<?php
						}
					?>
				</select>
			</div>
			<h3><?php echo esc_html__( 'Invitation Information', 'eventin-pro' ); ?></h3>
			<div class="etn-label-item form-group etn-label-invite">
				<div class="etn-name-fields">		
					<div class="etn-name-field-wrap">
						<h4><?php echo esc_html__('Attendee Name List:','eventin-pro'); ?></h4>
						<textarea style="width:100%" name="rsvp_invitation_info_name" id="rsvp_invitation_info_name" placeholder="<?php echo esc_attr__( 'Name'.PHP_EOL.'', 'eventin-pro' ); ?>" class="etn-form-control msg-control-box"><?php echo RSVPHelper::instance()->get_attendee_name(); ?></textarea>
					</div>
					<div class="etn-name-field-wrap">
						<h4><?php echo esc_html__('Attendee Email List:','eventin-pro'); ?></h4>
						<textarea style="width:100%" name="rsvp_invitation_info_email" id="rsvp_invitation_info_email" placeholder="<?php echo esc_attr__( 'Email'.PHP_EOL.'', 'eventin-pro' ); ?>" class="etn-form-control msg-control-box"><?php echo RSVPHelper::instance()->get_attendee_email(); ?></textarea>
					</div>
				</div>	
				<div class="rsvp-msg-notice">
					<div class="icon">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8 16C12.4 16 16 12.4 16 8C16 3.6 12.4 0 8 0C3.6 0 0 3.6 0 8C0 12.4 3.6 16 8 16Z" fill="#0A1018"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M8.0001 3.04C8.5303 3.04 8.9601 3.46981 8.9601 4V8C8.9601 8.53019 8.5303 8.96 8.0001 8.96C7.46991 8.96 7.0401 8.53019 7.0401 8V4C7.0401 3.46981 7.46991 3.04 8.0001 3.04Z" fill="white"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M6.80052 11.2C6.80052 10.5373 7.33778 10 8.00052 10H8.00771C8.67045 10 9.20771 10.5373 9.20771 11.2C9.20771 11.8627 8.67045 12.4 8.00771 12.4H8.00052C7.33778 12.4 6.80052 11.8627 6.80052 11.2Z" fill="white"/>
						</svg>
					</div>
					<p><?php echo esc_html__( 'Write the information about each invitation in one line as follows.', 'eventin-pro' ); ?></p>
				</div>
			</div>
			<h3><?php echo esc_html__( 'Email Body', 'eventin-pro' ); ?></h3>
			<div class="attr-form-group etn-label-item form-group"> 
				<div class='etn-meta' style="width:100%">
					<?php wp_editor('', 'invitation_email_message', ['textarea_name'=> 'invitation_email_message', 'media_buttons' => false, 'wpautop' => true, 'textarea_rows' => 5 ]); ?>
				</div>
			</div>
			<button type="submit" class="etn-btn etn-btn-primary etn-btn-send-invitation"><?php echo esc_html__( 'Send', 'eventin-pro' ); ?></button>
			<span class="invitation-loader"></span>
		</form>
	</div>
<!-- End RSVP Tab -->
