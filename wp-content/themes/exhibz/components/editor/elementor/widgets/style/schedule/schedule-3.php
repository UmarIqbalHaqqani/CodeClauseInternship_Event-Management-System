<?php
extract( array(
	'number' => 1,
	'order'  => $schedule_order,
	'class'  => '',
) );

global $post;
$args = array(
	'post_type' => 'ts-schedule',
	'post__in'  => array( $schedule_post_id )
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

foreach ( $posts as $postnav ) {
	setup_postdata( $postnav );

	?>
    <section class="ts-schedule">
        <div class="container">
            <div class="row">
                <div class="col-lg-12"
                     style="display:<?php echo esc_attr( $header_show == "yes" ? "block" : "none" ); ?>">
                    <h2 class="section-title">
						<?php echo get_the_title( $postnav->ID ) ?>
                    </h2>
                </div>
                <!-- col end-->
            </div>
            <!-- row end-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="tab-content schedule-tabs">
                        <div role="tabpanel" class="tab-pane active" id="date3">
							<?php
							$schedule_list = exhibz_meta_option( $postnav->ID, 'exhibz_schedule_pop_up', [] );
							foreach ( $schedule_list as $schedule ) {

								$speaker          = exhibz_meta_option( $schedule["speakers"], "exhibs_photo", '' );
								$speaker_image_id = ! empty( $speaker["id"] ) ? $speaker["id"] : '';
								$speaker_name     = get_post( $schedule["speakers"] );

								?>
                                <div class="schedule-listing">
                                    <div class="schedule-slot-time">
                                        <span> <?php echo esc_html( $schedule["schedule_time"] ); ?> </span>
                                    </div>
                                    <div class="schedule-slot-info">
										<?php if ( $speaker ) : ?>
                                            <a rel="noreferrer"
                                               href="<?php echo esc_url( get_the_permalink( $speaker_name->ID ) ); ?>">
												<?php echo wp_get_attachment_image( $speaker_image_id, 'thumbnail', false, [ 'class' => 'schedule-slot-speakers' ] ); ?>
                                            </a>
										<?php endif; ?>
                                        <div class="schedule-slot-info-content">
                                            <p class="schedule-speaker speaker-1">
												<?php if ( $schedule["speakers"] != '' ) : ?>
													<?php echo isset($speaker_name->post_title) ? esc_html( $speaker_name->post_title ) : ''; ?>
												<?php endif; ?>
                                            </p>
                                            <h3 class="schedule-slot-title">
												<?php echo esc_html( $schedule["schedule_title"] ); ?>
                                            </h3>
                                            <p>
												<?php echo wp_kses_post( $schedule["schedule_note"] ); ?>
                                            </p>
                                        </div>
                                        <!--Info content end -->
                                    </div>
                                    <!-- Slot info end -->
                                </div>
                                <!--schedule-listing end -->
							<?php } ?>
                            <!--schedule-listing end -->
                            <div class="schedule-listing-btn">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container end-->
    </section>
<?php } 

?>
