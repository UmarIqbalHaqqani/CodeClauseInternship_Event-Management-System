<?php
	use Etn\Templates\Event\Parts\EventDetailsParts;
	use Etn_Pro\Templates\Event\Parts\EventDetailsPartsPro;
	use Etn_Pro\Utils\Helper;

?>
<div class="etn-event-single-content-wrap">
	<?php
		function etn_bp_get_groups( $args = array() ) {
			$defaults = array(
				'per_page' => -1,
			);

			if ( ! current_user_can( 'administrator' ) ) {

				$defaults['user_id'] = bp_loggedin_user_id();
			}

			$args = wp_parse_args( $args, $defaults );

			$groups = function_exists('groups_get_groups') ? groups_get_groups( $args ) : null;

			return isset( $groups['groups'] ) && count( $groups['groups'] ) > 0 ? $groups['groups'] : null;
		}
	?>
	<!-- Hide this block if it's BuddyBoss group -->
	<?php  if ( ! bp_is_group() ) :  ?>
	<div class="etn-group-wrap">
		<?php
			$groups = etn_bp_get_groups();
			if ( $groups != null ):
		?>

		<select class="etn-bp-groups">
			<option value="">
				<?php esc_html_e( 'Select Group', 'eventin-pro' );?>
			</option>
			<?php
				foreach ( $groups as $group ) {
				?>
				<option value="<?php echo esc_attr( $group->id ); ?>"><?php echo esc_html( $group->name ); ?></option>
				<?php
					}
				?>
		</select>
		<a href="#" class="etn-assign-group" data-event_id="<?php echo esc_attr( $event_details->ID ); ?>">
			<?php esc_html_e( 'Assign', 'eventin-pro' );?>
		</a>
		<?php else: ?>
		<b>
			<?php esc_html_e( 'Group not found!', 'eventin-pro' );?>
		</b>
		<?php endif;?>
		<div class="etn-assign-group-list">
			<?php
				if ( $groups ) {
					foreach ( $groups as $group ) {
						$current_assinged_group = get_post_meta( $event_details->ID, 'etn_bp_group_' . $group->id, true );
						if ( $current_assinged_group ) {?>
							<a href="<?php echo esc_url( bp_get_group_permalink( $group ) ); ?>"><?php echo esc_html( $group->name ); ?></a>
						<?php }
					}
				}
			?>
		</div>
	</div>
	<?php endif; ?>
	
	<?php EventDetailsParts::event_single_category( $event_details->ID );?>
	<!-- ./categories -->

	<h2 class="etn-event-entry-title">
		<?php echo get_the_title( $event_details->ID ); ?>
	</h2>

	<?php if ( has_post_thumbnail( $event_details->ID ) && ! post_password_required() ): ?>
		<div class="etn-single-event-media">
			<?php
				echo get_the_post_thumbnail( $event_details->ID, 'full', '' );
			?>
		</div>
	<?php endif;?>

	<div class="etn-event-content-body">
		<?php
			echo apply_filters( 'the_content', get_post_field( 'post_content', $event_details->ID ) );
		?>
	</div>
	<!-- ./content -->

	<div class="etn-event-tag-list">
		<?php EventDetailsParts::event_single_tag_list( $event_details->ID );?>
	</div>
	<!-- ./tags -->
</div>

<?php
	$etn_speaker_events = get_post_meta( $event_details->ID, 'etn_event_speaker', true );
	EventDetailsPartsPro::event_single_speakers( $etn_speaker_events );
?>

<!-- ./speaker -->

<?php EventDetailsPartsPro::event_single_faq( $event_details->ID );?>

<?php
	$event_options = get_option( "etn_event_options" );
	if ( ! isset( $event_options["hide_related_event_from_details"] ) ) {
		// related events start
		Helper::related_events_widget( $event_details->ID, ["column" => 6] );
		// related events end
	}
?>

<div class="etn-sidebar">
	<?php EventDetailsParts::event_single_sidebar_meta( $event_details->ID );?>

	<?php
		$event_options        = get_option( "etn_event_options" );
		$etn_organizer_events = get_post_meta( $event_details->ID, 'etn_event_organizer', true );
		EventDetailsParts::event_single_tag_list( $etn_organizer_events );
	?>

	<?php etn_after_single_event_meta_add_to_calendar( $event_details->ID );?>

	<?php etn_after_single_event_meta_ticket_form( $event_details->ID );?>

	<?php EventDetailsPartsPro::event_single_schedule( $event_details->ID );?>

</div>