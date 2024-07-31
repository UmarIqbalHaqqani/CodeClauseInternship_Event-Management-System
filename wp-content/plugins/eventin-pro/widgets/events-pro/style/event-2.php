<?php
if (!defined('ABSPATH')) exit;

use \Etn_Pro\Utils\Helper;

$event_options = get_option("etn_event_options");
$show_thumb = isset($show_thumb)? $show_thumb : 'yes';

$data = Helper::post_data_query('etn', $event_count, $order, $event_cat, 'etn_category', null, null, $event_tag, $orderby_meta, $orderby, $filter_with_status, $post_parent);

?>
<div class='etn-row etn-event-wrapper etn-event-style2'>
	<?php
		if ( !empty( $data ) ) {
			foreach( $data as $value ) {

					$social           = get_post_meta( $value->ID, 'etn_event_socials', true);
					$event_location   = \Etn\Core\Event\Helper::instance()->display_event_location( $value->ID );
					$category =  Helper::cate_with_link( $value->ID, 'etn_category');
					$banner_image_url       = get_post_meta( $value->ID, 'event_banner', true );
					?>
					<div class="etn-col-md-6 etn-col-lg-<?php echo esc_attr($event_col); ?>">
							<div class="etn-event-item">
									<?php if ( esc_url( get_the_post_thumbnail_url( $value->ID ) ) && $show_thumb == 'yes' ) { ?>
											<div class="etn-event-thumb">
											<?php if ( $banner_image_url ): ?>
												<a 
													href="<?php echo esc_url(get_the_permalink($value->ID)); ?>" 
													aria-label="<?php echo get_the_title(); ?>"
												>
													<img src="<?php echo esc_url($banner_image_url); ?>" alt="Image">
												</a>
											<?php elseif ( get_the_post_thumbnail_url($value->ID) ): ?>
												<a 
													href="<?php echo esc_url(get_the_permalink($value->ID)); ?>" 
													aria-label="<?php echo get_the_title(); ?>"
												>
													<?php echo get_the_post_thumbnail($value->ID, 'large');  ?>
												</a>
											<?php endif; ?>
													<?php if ($show_category === 'yes') : ?>
															<div class="etn-event-category">
																	<?php echo  Helper::kses( $category ); ?>
															</div>
													<?php endif; ?>

													<?php if ($show_btn == 'yes') : ?>
															<div class="etn-atend-btn">
																	<a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>" class="etn-btn etn-btn-border"><?php echo esc_html( $btn_text ); ?></a>
															</div>
													<?php endif; ?>
													<?php Helper::event_recurring_status($value); ?>

											</div>
									<?php } ?>
									<!-- thumbnail start-->

									<!-- content start-->
									<div class="etn-event-content">
											<div class="etn-title-info">
													<h3 class="etn-title etn-event-title"><a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>"> <?php echo esc_html( get_the_title( $value->ID ) ); ?></a> </h3>
													<?php if( $show_desc == 'yes' ) : ?>
															<p><?php echo esc_html( Helper::trim_words( get_the_excerpt($value->ID), $desc_limit) ); ?></p>
													<?php endif; ?>
													<?php if($show_event_location == 'yes'): ?>
														<?php if($event_location != '') { ?>
																<div class="etn-event-location"><i class="etn-icon etn-location"></i> <?php echo esc_html( $event_location ); ?></div>
														<?php } ?>
													<?php endif; ?>
													<div class="etn-event-date">
															<?php
																echo esc_html(Helper::etn_display_date($value->ID, 'yes', $show_end_date)); 
															?>
													</div>
											</div>
									</div>
									<!-- content end-->
							</div>
							<!-- etn event item end -->
					</div>
					<!-- col end -->
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