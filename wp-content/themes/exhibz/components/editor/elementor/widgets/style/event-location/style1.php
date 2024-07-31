<?php 
    if(!empty($all_locations)) {
?>

    <div class="row">
        <?php       
            foreach($all_locations as $location):
				$count = get_term( $location->term_id , 'event_location' );
				if(class_exists('CSF')) {
					$location_tax = get_term_meta($location->term_id, 'exhibz-etn-location', true);
					$location_featured_image   = !empty($location_tax['featured_upload_img']['id']) ? $location_tax['featured_upload_img']['id'] : '';
					$location_name = $location-> name; ?>
					<div class="col-lg-<?php echo esc_attr($etn_event_col); ?> col-md-6">
						<div class="location-box">
							<?php if($location_featured_image){
								
								?>
								<div class="location-image">
									<a href="<?php echo esc_url( get_term_link($location->term_id, 'event_location') ); ?>">
										<?php echo wp_get_attachment_image($location_featured_image, 'thumbnail', false) ?>
									</a>
								</div>
							<?php } ?>
							<div class="location-des">
								<a href="<?php echo esc_url( get_term_link($location->term_id, 'event_location') ); ?>">
									<span class="location-name"><?php echo esc_html($location_name); ?></span>
									<span class="event-number">
										<?php
										$event_count = $count->count;
	
										$count = sprintf( _n( '%s Event', '%s Events', $event_count, 'exhibz' ), $event_count );
										
										// "3 stars"
										esc_html_e($count);
	
									 ?> </span>
								</a>
							</div>
						</div>
					</div>
					<?php
				}
                ?>
        <?php endforeach; ?>   
    </div>

<?php } ?>
