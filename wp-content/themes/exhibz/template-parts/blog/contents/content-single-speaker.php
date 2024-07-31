<article id="post-<?php the_ID(); ?>" <?php post_class(' post-details'); ?>>
	<?php
		$exhibs_designation = exhibz_meta_option( get_the_id(), 'exhibs_designation' );
		$exhibs_photo       = exhibz_meta_option( get_the_id(), 'exhibs_photo' );
		$exhibs_logo        = exhibz_meta_option( get_the_id(), 'exhibs_logo' );
		$exhibs_summery     = exhibz_meta_option( get_the_id(), 'exhibs_summery' );
		$socials            = exhibz_meta_option( get_the_id(), 'social', [] );
		$schedule_sort      = exhibz_option( 'single_speaker_schedule_sort', true );
		$schedules          = exhibz_speaker_schedule( get_the_id() );
		$exhibs_logo_url    = isset( $exhibs_logo['url'] ) ? $exhibs_logo['url'] : '';
	?>

	<div class="entry-content clearfix ts-single-speaker">
		<div class="row">
			<div class="col-md-4">
				<div class="speaker-content">
					<div class="speaker-exhibs_photo">
						<img alt="speaker-photo" src="<?php echo isset($exhibs_photo['url']) ? esc_url($exhibs_photo['url']) : ''; ?> " />
					</div>
					<h2 class="ts-title ts-speaker-title"><?php echo esc_html(get_the_title()); ?></h2>
					<div class="speaker-designation">
						<?php echo esc_html($exhibs_designation); ?>
					</div>

					<?php if($exhibs_logo_url != ''): ?>
						<div class="speaker-exhibs_logo">
							<img alt="speaker-logo" src="<?php echo esc_url($exhibs_logo_url) ; ?>" />
						</div>
					<?php endif; ?>
					<div class="speaker-exhibs_summery">
						<?php echo exhibz_kses($exhibs_summery); ?>
					</div>
					<ul class="ts-social-list">
						<?php foreach ($socials as $social) : ?>
							<li>
								<a href="<?php echo esc_url($social['option_site_link']); ?>"> <i class="<?php echo esc_attr($social['option_site_icon']); ?>"></i></a>
							</li>
						<?php endforeach; ?>
					</ul>

				</div>
			</div>
			<?php if( !empty( $schedules ) ) : ?>
				<div class="col-md-8">
					<div class="speaker-schedule single-speaker-schedule">
						<?php foreach ( $schedules as $schedule ) : ?>
							<div class="schedule-listing">
								<div class="schedule-slot-time">
									<span> 
										<?php echo esc_html( $schedule['schedule_time'] ); ?> 
									</span>
								</div>
								<div class="schedule-slot-info">
									<div class="schedule-slot-info-content">
										<p class="schedule-speaker speaker-1">
											<?php echo esc_html( $schedule['schedule_day'] ); ?> 
											<span>
												<?php echo esc_html( $schedule['title'] ); ?> 
											</span>
										</p>
										<h3 class="schedule-slot-title">
											<?php echo esc_html( $schedule['schedule_title'] ); ?>
										</h3>
										<p>
											<?php echo exhibz_kses( $schedule['schedule_note'] ); ?>
										</p>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php

		if (is_search()) {
			the_excerpt();
		} else {
			the_content(esc_html__('Continue reading &rarr;', 'exhibz'));
			exhibz_link_pages();
		}

		?>
	</div> <!-- end entry-content -->
</article>