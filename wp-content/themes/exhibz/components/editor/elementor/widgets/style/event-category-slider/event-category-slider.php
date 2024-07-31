<?php
$category_limit = $exhibz_event_category_settings['category-options']['category_limit'];
$categories_id  = $exhibz_event_category_settings['category-options']['categories_id'];
$post_sort_by   = $exhibz_event_category_settings['category-options']['post_sort_by'];
$hide_empty     = $exhibz_event_category_settings['hide_empty'] == 'yes' ? '1' : '0';
$taxonomy       = 'etn_category';
$term_link      = '';

if(is_array($categories_id) && !empty($categories_id)) {
	$cats = $categories_id;
} else {
	$args_cat = array(
		'taxonomy'   => $taxonomy,
		'number'     => $category_limit,
		'hide_empty' => $hide_empty,
		'orderby'    => 'post_date',
		'order'      => $post_sort_by,
	);
	$cats     = get_categories($args_cat);
}


?>
    <?php $swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';?>
    <div class="ts-event-category-slider" data-controls="<?php echo esc_attr($slide_controls); ?>">
        <div class="<?php echo esc_attr($swiper_class); ?>">
            <div class="swiper-wrapper">
				<?php foreach($cats as $value) {
					$term = get_term($value, $taxonomy);
					if(class_exists('CSF')) {
						$category_tax = get_term_meta($term->term_id, 'exhibz-etn-category', true);
						$img_id       = !empty($category_tax['event_category_featured_img']['id']) ? $category_tax['event_category_featured_img']['id'] : '';
						?>
                        <div class="swiper-slide">
                            <div class="event-slider-item">
                                <div class="cat-content">

                                    <h3 class="ts-title">
                                        <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a>
                                    </h3>
                                </div>

								<?php
								if($img_id) :
									$term_link = get_term_link($term->slug, $taxonomy);
									?>
                                    <div class="cat-bg">
                                        <a class="cat-link" href="<?php echo esc_url($term_link); ?>">
											<?php echo wp_get_attachment_image($img_id, 'thumbnail', false) ?>
                                        </a>
                                    </div>
								<?php endif; ?>
                            </div>
                        </div>
						<?php
					}
				} ?>
            </div>
        </div>
		<?php if("yes" == $show_navigation):
			?>
            <div class="slider-nav">
                <div class="swiper-button-next swiper-next-<?php echo esc_attr($this->get_id()); ?>" > 
                    <?php \Elementor\Icons_Manager::render_icon( $settings['right_arrow_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>
                <div class="swiper-button-prev swiper-prev-<?php echo esc_attr($this->get_id()); ?>">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['left_arrow_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </div>
            </div>
		<?php endif; ?>
    </div>
<?php
