<?php
namespace Etn_Pro\Templates\Event\Parts;
use Etn_Pro\Utils\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Event details class.
 *
 * @since 3.3.9
 */
class EventDetailsPartsPro {

	/**
	 * Process event single FAQs
	 *
	 * @param [type] $event_id
	 *
	 * @return void
	 */
	public static function event_single_faq( $event_id ) {
		$etn_faqs = get_post_meta( $event_id, 'etn_event_faq', true );
		if(!empty($etn_faqs)):
			?>
			<div class="etn-accordion-wrap etn-event-single-content-wrap">
					<h3 class="etn-faq-heading"><?php 
					$event_faq_title = apply_filters( 'etn_event_faq_title', esc_html__( "frequently asked questions", "eventin-pro" ));
					echo esc_html( $event_faq_title ); ?></h3>
					<?php
							if ( is_array( $etn_faqs ) && !empty( $etn_faqs ) ) {
									foreach ( $etn_faqs as $key => $faq ) {
											$acc_class = ( $key == 0 ) ? 'active' : '';
											?>
											<div class="etn-content-item">
													<h4 class="etn-accordion-heading <?php echo esc_attr( $acc_class ); ?>">
															<?php echo esc_html( $faq["etn_faq_title"] ); ?>
															<i class="etn-icon etn-plus"></i>
													</h4>
													<p class="etn-acccordion-contents <?php echo esc_attr( $acc_class ); ?>">
													<?php 
													if ( has_blocks( $faq["etn_faq_content"] ) ) {
														echo do_blocks( $faq["etn_faq_content"] );
													} else {
														echo esc_html( $faq["etn_faq_content"] );
													}
														?>
													</p>
											</div>
											<?php
									}
							} else {
									?>
									<div class="etn-event-faq-body">
											<?php echo esc_html__( "No FAQ found!", "eventin-pro" ); ?>
									</div>
									<?php
							}
					?>
			</div>
			<?php
		endif;
	}


	/**
	 * Event details speaker.
	 *
	 * @since 3.3.9
	 */
	public static function event_single_speakers( $etn_speaker_events ) {
		if(!empty($etn_speaker_events)):
			?>
			<div class="etn-event-speakers etn-single-event-speaker-block">
				<h3 class="speaker-block-title">
				<?php  
					$event_speakers_title = apply_filters( 'etn_event_organizers_title', esc_html__("Speaker",  'eventin-pro' ) );
					echo esc_html( $event_speakers_title );
				?> 
				</h3>
				<?php
				$speakers = is_serialized( $etn_speaker_events ) ? maybe_unserialize( $etn_speaker_events ) : $etn_speaker_events;
				$args = [
					'post_type' => 'etn-speaker',
					'post__in' 	=> $speakers
				];
				
				$data = get_posts( $args );
				?>

				
				<div class="etn-single-event-speaker-grid-wrapper">
						<?php
						if ($data && $speakers) {
								foreach ($data as $value) { 
										$social = get_post_meta( $value->ID , 'etn_speaker_socials', true);
										$etn_speaker_designatioin = get_post_meta( $value->ID , 'etn_speaker_designation', true);
										$etn_speaker_permalink = get_post_permalink($value);
										?>
										<div class="etn-se-speaker-item">
												<?php if (has_post_thumbnail($value->ID)) : ?>
														<div class="etn-speaker-image">
																<a href='<?php echo esc_url($etn_speaker_permalink); ?>' aria-label="<?php echo esc_html( get_the_title( $value->ID ) ); ?>">
																		<?php echo get_the_post_thumbnail( $value->ID, [100, 100] ); ?>
																</a>
														</div>
												<?php endif; ?>

												<h4 class="etn-speaker-name">
														<a href='<?php echo esc_url($etn_speaker_permalink); ?>'> <?php echo esc_html( get_the_title( $value->ID ) ); ?> </a>
												</h4> 
												<?php if ($etn_speaker_designatioin) : ?>
														<div class="etn-speaker-designation">
																<p><?php echo esc_html($etn_speaker_designatioin); ?></p>
														</div>
												<?php endif; ?>

												<?php if (is_array( $social ) && !empty( $social ) ) : ?>
														
														<div class="etn-social etn-social-style-1">
																<?php 
																		foreach ($social as $social_value) : 
																		$etn_social_class = 'etn-' . str_replace('fab fa-', '', $social_value['icon']);
																?> 
																		<a  
																			href="<?php echo esc_url($social_value["etn_social_url"]); ?>" 
																			target="_blank" 
																			class="<?php echo esc_attr($etn_social_class); ?>" 
																			title="<?php echo esc_attr($social_value["etn_social_title"]); ?>"
																			aria-label="<?php echo esc_attr($social_value["etn_social_title"]); ?>"
																			
																			>
																			<i class="etn-icon <?php echo esc_attr($social_value["icon"]); ?>"></i>
																		</a>
																<?php  endforeach; ?>
														</div>
												<?php endif; ?>
										</div>
										<?php
								}
								wp_reset_postdata();
						}
						?>
				</div>
			</div>
			<?php
		endif;

	}

	/**
	 * single event schedule
	 */
	public static function event_single_schedule( $single_event_id ) {
		$event_options          = get_option("etn_event_options");
		$data                   = Helper::single_template_options( $single_event_id );
		$etn_event_schedule     = isset( $data['etn_event_schedule']) ? $data['etn_event_schedule'] : [];

		if (!isset($event_options["etn_hide_schedule_from_details"]) && !empty($etn_event_schedule)) {
				if (is_array($etn_event_schedule)) {
						$args = array(
								'post__in' => $etn_event_schedule,
								'orderby' => 'post_date',
								'order' => 'asc',
								'post_type' => 'etn-schedule',
								'post_status' => 'publish',
								'suppress_filters' => false,
								'numberposts'	=> -1
						);

						$schedule_query = get_posts($args);

						?>
						<!-- schedule tab start -->
						<div class="schedule-tab-wrapper etn-tab-wrapper schedule-style-1  no-shadow pt-0">
								<h3 class="etn-tags-title"><?php echo esc_html__('Schedule:', 'eventin-pro'); ?></h3>
								<ul class='etn-nav'>
										<?php
										$i = -1;
										foreach ($schedule_query as $post) :
												$single_schedule_id = $post->ID;
												$i++;
												$schedule_meta = get_post_meta($single_schedule_id);
												$schedule_date = !empty( $schedule_meta['etn_schedule_date'][0] ) ? date_i18n(\Etn\Core\Event\Helper::instance()->etn_date_format(), strtotime($schedule_meta['etn_schedule_date'][0])) : "";
												$active_class = (($i == 0) ? 'etn-active' : ' ');
												?>
												<li>
														<a href='#' class='etn-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($i); ?>'>
																<span class='etn-date'><?php echo esc_html($post->post_title); ?></span>
																<span class='etn-day'><?php echo esc_html($schedule_date); ?></span>
														</a>
												</li>
										<?php endforeach; ?>
								</ul>
								<div class='etn-tab-content clearfix etn-schedule-wrap'>
										<?php
										$j = -1;
										foreach ($schedule_query as $post) :
												$single_schedule_id = $post->ID;
												$j++;
												$schedule_meta  = get_post_meta($single_schedule_id);
												$schedule_date  = strtotime($schedule_meta['etn_schedule_date'][0]);
												$schedule_topics = !empty($schedule_meta['etn_schedule_topics'][0]) ? unserialize($schedule_meta['etn_schedule_topics'][0]) : [];
												$schedule_date  = date_i18n("d M", $schedule_date);
												$active_class   = (($j == 0) ? 'tab-active' : ' ');
												$etn_show_speaker_with_schedule = get_post_meta( $single_event_id, 'etn_select_speaker_schedule_type', true );
												$etn_show_speaker_with_schedule = !empty( $etn_show_speaker_with_schedule) ? $etn_show_speaker_with_schedule : 'schedule_with_speaker';
												?>
												<!-- start repeatable item -->
												<div class='etn-tab <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($j); ?>'>
														<?php
														$etn_tab_time_format = (!empty($event_options["time_format"]) && $event_options["time_format"] == '24') ? "H:i" : get_option( 'time_format' );
														if( is_array( $schedule_topics ) && !empty( $schedule_topics ) ){
																foreach($schedule_topics as $topic) {
																		$etn_schedule_topic         = (isset($topic['etn_schedule_topic']) ? $topic['etn_schedule_topic'] : '');
																		$etn_schedule_start_time    = !empty($topic['etn_shedule_start_time']) ? date_i18n($etn_tab_time_format, strtotime($topic['etn_shedule_start_time'])) : '';
																		$etn_schedule_end_time      = !empty($topic['etn_shedule_end_time']) ? date_i18n($etn_tab_time_format, strtotime($topic['etn_shedule_end_time'])) : '';
																		$etn_schedule_room          = (isset($topic['etn_shedule_room']) ? $topic['etn_shedule_room'] : '');
																		$etn_schedule_objective     = (isset($topic['etn_shedule_objective']) ? $topic['etn_shedule_objective'] : '');
																		$etn_schedule_speaker       = (isset($topic['etn_shedule_speaker']) ? (array) $topic['etn_shedule_speaker'] : []);
																		$dash_sign	                = ( !empty( $etn_schedule_start_time ) && !empty( $etn_schedule_end_time ) ) ? " - " : " ";
				
																	?>
																		<div class='etn-single-schedule-item etn-row'>
																				<div class='etn-schedule-info etn-col-sm-4'>
																						<?php
																						
																						if(!empty($etn_schedule_start_time) || !empty( $etn_schedule_end_time )){
																								?>
																									<span class='etn-schedule-time'>
																												<?php echo esc_html($etn_schedule_start_time) . $dash_sign . esc_html($etn_schedule_end_time); ?>
																										</span>
				
																								<?php
																						}
				
																						if( !empty( $etn_schedule_room ) ){
																								?>
																								<span class='etn-schedule-location'>
																										<i class='etn-icon etn-location'></i>
																										<?php echo esc_html($etn_schedule_room); ?>
																								</span>
																								<?php
																						}
																						?>
																				</div>
																				<div class='etn-col-sm-8'>
																						<div class="etn-accordion-wrap">
																								<div class="etn-content-item">
																										<h4 class='etn-accordion-heading'>
																												<?php echo esc_html($etn_schedule_topic); ?>
																												<?php if($etn_show_speaker_with_schedule === 'schedule_with_speaker') : ?>
																													<i class="etn-icon etn-plus"></i>
																												<?php endif; ?>
																										</h4>
																										<?php echo Helper::render(trim( $etn_schedule_objective )); ?>
																										<?php  if($etn_show_speaker_with_schedule === 'schedule_with_speaker') : ?>
																										<div class="etn-acccordion-contents">
																												<div class='etn-schedule-content'>
																														<div class='etn-schedule-speaker'>
																																<?php
																																$speaker_avatar = apply_filters("etn/speakers/avatar", \Wpeventin::assets_url() . "images/avatar.jpg");
																																if (count($etn_schedule_speaker) > 0) {
																																		foreach ($etn_schedule_speaker as $key => $value) {
																																				$speaker_thumbnail = !empty( get_the_post_thumbnail_url($value, 'thumbnail') ) ?  get_the_post_thumbnail_url($value, 'thumbnail') : $speaker_avatar;
																																
																																				$etn_schedule_single_speaker = get_post($value);
																																				$etn_speaker_permalink = get_post_permalink($value);
																																				$speaker_title = $etn_schedule_single_speaker->post_title;
																																				?>
																																				<div class='etn-schedule-single-speaker'>
																																						<a href='<?php echo esc_url($etn_speaker_permalink); ?>'>
																																								<img src='<?php echo esc_url($speaker_thumbnail); ?>' alt='<?php echo esc_attr($speaker_title); ?>'>
																																						</a>
																																						<span class='etn-schedule-speaker-title'><?php echo esc_html($speaker_title); ?></span>
																																				</div>
																																				<?php
																																		}
																																}
																																?>
																														</div>
																												</div>
																										</div>
																										<?php endif; ?>
																								</div>
																						</div>
																				</div>
																		</div>
																		<?php 
																}
														} 
														?>
												</div>
												<!-- end repeatable item -->
										<?php endforeach;
										wp_reset_postdata(); ?>
								</div>
						</div>
						<!-- schedule tab end -->
						<?php
				}
		}
	}

}