<?php if( !empty($event) ): ?>
	<a href="#" onclick="return etn_bp_events_load('<?php echo esc_attr( $event->ID ); ?>');">
		<div class="events-item etn-bb-list-item" id="event-show-row-<?php echo esc_attr($event->ID); ?>" data-events-id="<?php echo esc_attr($event->ID); ?>">
			<div class="etn-bb-event-list-title">
				<h4 class="events-title">
					<?php echo get_the_title( $event->ID ); ?>
				</h4>
			</div>
			<div class="etn-bb-event-list-desc">
				<ul class="etn-bb-list-meta">
					<li>
						<p>
							<?php echo \Etn\Utils\Helper::etn_display_date($event->ID, 'yes', 'yes'); ?>
						</p>
					</li>
				</ul>
			</div>
		</div>
	</a>
<?php endif; ?>