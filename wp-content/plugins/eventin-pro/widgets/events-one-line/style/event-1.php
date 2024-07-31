<?php
if (!defined('ABSPATH')) exit;

use \Etn_Pro\Utils\Helper;

$event_options  = get_option("etn_event_options");
$data           = Helper::post_data_query('etn', $event_count, $order, $event_cat, 'etn_category', null, null, $event_tag, $orderby_meta, $orderby,$filter_with_status, $post_parent);

?>
<div class="etn-container events_calendar_list">
	<?php
	if ( !empty( $data ) ) {
			foreach( $data as $value ) {
				$social             = get_post_meta( $value->ID, 'etn_event_socials', true);
				$event_location   = \Etn\Core\Event\Helper::instance()->get_event_location($value->ID);
			 
				$category           = Helper::cate_with_link($value->ID, 'etn_category');
				?>
				<div class="etn-row calendar-event-details">
					<?php if ( esc_url( get_the_post_thumbnail_url( $value->ID ) ) && $show_thumb == 'yes') { ?>
							<div class="etn-col-md-3 calendar-event-details-thumbnail">
								<div class="etn-event-thumb">
									<a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>" aria-label="<?php echo esc_html( get_the_title( $value->ID ) ); ?>">
										<img class="calendar-event-thumb-img" src="<?php echo esc_url( esc_url( get_the_post_thumbnail_url( $value->ID ) ) ); ?>" alt="<?php the_title_attribute($value->ID); ?>">
									</a>
									<?php Helper::event_recurring_status($value); ?>
								</div>
							</div>
					<?php } ?>
					<div class="etn-col-md-6 etn-col-lg-7">
						<div class="calendar-event-content">
							<?php if ($show_category === 'yes') : ?>
								<div class="calendar-event-category-wrap">
									<?php echo  Helper::kses( $category ); ?>
								</div>
							<?php endif; ?>
							<h3 class="calendar-event-title etn-title etn-event-title">
								<a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>"> <?php echo esc_html( get_the_title( $value->ID ) ); ?></a>
							</h3>
							<ul class="calendar-event-time-vanue">
								<li class="etn-event-calender-vanue etn-event-calender-time">
									<?php
										echo esc_html(Helper::etn_display_date($value->ID, 'yes', $show_end_date)); 
									?>
								</li>
								<?php if($show_event_location == 'yes'): ?>
									<?php if( isset( $event_location ) && $event_location != '') { ?>
											<li class="etn-event-calender-vanue">
												<i class="etn-icon etn-location"></i>
												<?php echo esc_html( $event_location ); ?>
											</li>
									<?php } ?>
								<?php endif; ?>
							</ul>
						</div>
					</div>

					<?php if ($show_btn == 'yes') : ?>
						<div class="etn-col-md-3 etn-col-lg-2 event-calendar-action">
							<a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>" class="etn-btn event-calendar-details-btn">
								<?php echo esc_html( $btn_text ); ?> 
								<i class="etn-icon etn-arrow-right"></i>
							</a>
						</div>
					<?php endif; ?>
				</div>
				<!-- ./row -->
				<?php
			}
	}else{
			?>
			<p class="etn-not-found-post"><?php echo esc_html__('No Event Found', 'eventin-pro'); ?></p>
			<?php
	}
	wp_reset_postdata();
	?>
</div>