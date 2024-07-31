<?php
global $post;
extract( [
	'number' => $schedule_day_limit,
	'order'  => $schedule_order,
	'class'  => '',
] );
$args = array(
	'post_type'        => 'ts-schedule',
	'suppress_filters' => false,
	'posts_per_page'   => esc_attr( $number ),
	'order'            => $order,
);

if ( isset( $schedule_term_id ) && $schedule_term_id != '' ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'ts-schedule_cat',
			'field'    => 'term_id',
			'terms'    => $schedule_term_id
		),
	);
};

$i     = 1;
$posts = get_posts( $args );
?>

<section class="ts-schedule">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 align-self-center wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="400ms">
                <div class="ts-schedule-content">
                    <h2 class="column-title">
                        <span><?php echo esc_html( $schedule_top_title ); ?></span>
						<?php echo esc_html( $schedule_title ); ?>
                    </h2>
                    <p>
						<?php echo esc_html( $schedule_desc ); ?>
                    </p>
                </div>
            </div>
            <!-- col end-->
            <div class="col-lg-6 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="500ms">
                <div class="ts-schedule-info mb-70">
                    <ul class="nav nav-tabs" role="tablist">
						<?php
						foreach ( $posts as $postnav ) {
							setup_postdata( $postnav );
							?>
							<?php if ( $i == 1 ) { ?>
                                <li class="nav-item" role="presentation">
                                    <a class="active" title="<?php echo get_the_title( $postnav->ID ) ?>"
                                       href="<?php echo esc_attr( "#date" . $i ); ?>" role="tab" data-toggle="tab">
                                        <h3><?php echo get_the_title( $postnav->ID ) ?></h3>
										<?php $schedule_day = exhibz_meta_option( $postnav->ID, 'schedule_day' );
										if ( ! empty( $schedule_day ) ) { ?>
                                            <span><?php echo $schedule_day; ?></span>
										<?php } ?>
                                    </a>
                                </li>
							<?php } else { ?>
                                <li class="nav-item" role="presentation">
                                    <a title="<?php echo get_the_title( $postnav->ID ) ?>"
                                       href="<?php echo esc_attr( "#date" . $i ); ?>" role="tab" data-toggle="tab">
                                        <h3><?php echo get_the_title( $postnav->ID ) ?></h3>

										<?php $schedule_day = exhibz_meta_option( $postnav->ID, 'schedule_day' );
										if ( ! empty( $schedule_day ) ) { ?>
                                            <span><?php echo $schedule_day; ?></span>
										<?php } ?>
                                    </a>
                                </li>
							<?php } ?>
							<?php $i ++;
						}
						wp_reset_postdata(); ?>
                    </ul>
                    <!-- Tab panes -->
                </div>
            </div>
            <!-- col end-->
        </div>
        <!-- row end-->
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content schedule-tabs">
					<?php $j = 1;
					foreach ( $posts as $post ) {
						setup_postdata( $post );
						$schedule_list = exhibz_meta_option( $post->ID, 'exhibz_schedule_pop_up', [] );
						?>
						<?php if ( $j == 1 ) : ?>
                            <div role="tabpanel" class="test tab-pane active"
                                 id="<?php echo esc_attr( "date" . $j ); ?>"
                                 aria-labelledby="<?php echo esc_attr( "date" . $j ); ?>-tab">
								<?php foreach ( $schedule_list as $key => $schedule ) :
									if ( $key == $schedule_limit ) {
										break;
									}
									?>
                                    <!--schedule-listing Start -->
                                    <div class="schedule-listing multi-schedule-list">
                                        <div class="schedule-slot-time">
                                            <span> <?php echo esc_html( $schedule["schedule_time"] ); ?> </span>
                                        </div>
                                        <div class="schedule-slot-info">
                                            <div class="schedule-slot-info-content">
                                                <h3 class="schedule-slot-title">
													<?php echo esc_html( $schedule["schedule_title"] ); ?>
                                                </h3>
                                                <p>
													<?php echo exhibz_kses( $schedule["schedule_note"] ); ?>
                                                </p>
												<?php if ( $schedule['style'] == true ) :

													$multiple_speaker = $schedule["multi_speakers"];
													$speaker_query = get_posts( [
														'post_type'      => 'ts-speaker',
														'posts_per_page' => 3,
														'post__in'       => $multiple_speaker,
													] );
													?>
                                                    <div class="single-speaker-2">
														<?php foreach ( $multiple_speaker as $sp_key => $speaker ) :
															$single_speaker = get_post( $speaker );
															$speaker_name = $single_speaker->post_title;
															$speaker_id = $single_speaker->ID;
															$speaker_image_meta = exhibz_meta_option( $speaker_id, "exhibs_photo" );
															$speaker_image_id = ! empty( $speaker_image_meta['id'] ) ? $speaker_image_meta['id'] : '';
															?>
                                                            <div class="speaker-content">
                                                                <a href="<?php echo get_the_permalink( $speaker_id ); ?>">
																	<?php echo wp_get_attachment_image( $speaker_image_id, 'thumbnail', false, [ 'class' => 'schedule-slot-speakers' ] ); ?>
                                                                </a>
                                                                <p class="schedule-speaker"><?php echo exhibz_kses( $speaker_name ); ?></p>
                                                            </div>

														<?php endforeach; ?>
                                                    </div>

												<?php else :
													$schedule_single_speaker = $schedule["speakers"];
													if ( $schedule_single_speaker != '' ) {
														$single_speaker_query      = get_post( $schedule_single_speaker );
														$single_speaker_name       = $single_speaker_query->post_title;
														$single_speaker_id         = $single_speaker_query->ID;
														$single_speaker_image_meta = exhibz_meta_option( $single_speaker_id, "exhibs_photo" );
														$single_speaker_image_id   = ! empty( $single_speaker_image_meta['id'] ) ? $single_speaker_image_meta['id'] : '';
													}
													?>
                                                    <div class="single-speaker-2">
                                                        <div class="speaker-content">
                                                            <a href="<?php echo get_the_permalink( $single_speaker_id ); ?>">
																<?php echo wp_get_attachment_image( $single_speaker_image_id, 'thumbnail', false, [ 'class' => 'schedule-slot-speakers' ] ); ?>
                                                            </a>
                                                            <p class="schedule-speaker"><?php echo exhibz_kses( $single_speaker_name ); ?></p>
                                                        </div>
                                                    </div>
												<?php endif; ?>
                                            </div>
                                            <!--Info content end -->
                                        </div>
                                        <!-- Slot info end -->
                                    </div>
								<?php endforeach; ?>
                                <!--schedule-listing end -->
                            </div>
						<?php else : ?>
                            <div role="tabpanel" class="tab-pane" id="<?php echo esc_attr( "date" . $j ); ?>"
                                 aria-labelledby="<?php echo esc_attr( "date" . $j ); ?>-tab">
								<?php foreach ( $schedule_list as $key => $schedule ) :
									if ( $key == $schedule_limit ) {
										break;
									}
									?>
                                    <!--schedule-listing Start -->
                                    <div class="schedule-listing multi-schedule-list">
                                        <div class="schedule-slot-time">
                                            <span> <?php echo esc_html( $schedule["schedule_time"] ); ?> </span>
                                        </div>
                                        <div class="schedule-slot-info">
                                            <div class="schedule-slot-info-content">
                                                <h3 class="schedule-slot-title">
													<?php echo esc_html( $schedule["schedule_title"] ); ?>
                                                </h3>
                                                <p>
													<?php echo exhibz_kses( $schedule["schedule_note"] ); ?>
                                                </p>
												<?php if ( $schedule['style'] == true ) :

													$multiple_speaker = $schedule["multi_speakers"];
													$speaker_query = get_posts( [
														'post_type'      => 'ts-speaker',
														'posts_per_page' => 3,
														'post__in'       => $multiple_speaker,
													] );
													?>
                                                    <div class="single-speaker-2">
														<?php foreach ( $multiple_speaker as $sp_key => $speaker ) :
															$single_speaker = get_post( $speaker );
															$speaker_name = $single_speaker->post_title;
															$speaker_id = $single_speaker->ID;
															$speaker_image_meta = exhibz_meta_option( $speaker_id, "exhibs_photo" );
															$speaker_image_id = ! empty( $speaker_image_meta['id'] ) ? $speaker_image_meta['id'] : '';
															?>
                                                            <div class="speaker-content">
                                                                <a href="<?php echo get_the_permalink( $speaker_id ); ?>">
																	<?php echo wp_get_attachment_image( $speaker_image_id, 'thumbnail', false, [ 'class' => 'schedule-slot-speakers' ] ); ?>
                                                                </a>
                                                                <p class="schedule-speaker"><?php echo exhibz_kses( $speaker_name ); ?></p>
                                                            </div>

														<?php endforeach; ?>
                                                    </div>

												<?php else :
													$schedule_single_speaker = $schedule["speakers"];
													if ( $schedule_single_speaker != '' ) {
														$single_speaker_query      = get_post( $schedule_single_speaker );
														$single_speaker_name       = isset( $single_speaker_query->post_title ) ? $single_speaker_query->post_title : '';
														$single_speaker_id         = isset( $single_speaker_query->ID ) ? $single_speaker_query->ID : '' ;
														$single_speaker_image_meta = exhibz_meta_option( $single_speaker_id, "exhibs_photo" );
														$single_speaker_image_id   = ! empty( $single_speaker_image_meta['id'] ) ? $single_speaker_image_meta['id'] : '';
													}
													?>
                                                    <div class="single-speaker">
                                                        <a href="<?php echo get_the_permalink( $single_speaker_id ); ?>">
															<?php echo wp_get_attachment_image( $single_speaker_image_id, 'thumbnail', false, [ 'class' => 'schedule-slot-speakers' ] ); ?>
                                                        </a>
                                                        <p class="schedule-speaker"><?php echo exhibz_kses( $single_speaker_name ); ?></p>
                                                    </div>
												<?php endif; ?>
                                            </div>
                                            <!--Info content end -->
                                        </div>
                                        <!-- Slot info end -->
                                    </div>
								<?php endforeach; ?>
                                <!--schedule-listing end -->
                            </div>
						<?php endif; ?>
						<?php $j ++;
					}
					wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- container end-->
</section>
