<?php $swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';?>
        <div class="<?php echo esc_attr($this->get_name()); ?>" data-widget_settings='<?php echo json_encode($settings); ?>'>
            <?php if (!empty($settings['enable_carousel']) && $settings['enable_carousel'] == 'yes') : ?>
            <div class="<?php echo esc_attr($swiper_class); ?>">
                <div class="swiper-wrapper">
                    <?php else: ?>
                    <div class="speakers-grid">
                        <?php endif;
                        ?>
                        <?php
                        foreach ($query as $post):
                            $social = get_post_meta($post->ID, 'etn_speaker_socials', true);
                            $etn_speaker_designation = get_post_meta($post->ID, 'etn_speaker_designation', true);
                            $speaker_overlay_color = $data['speaker_image_overlay_color'] = exhibz_meta_option($post->ID, 'speaker_image_overlay_color', '#FF2E00');
                            $speaker_overlay_blend_mode = $data['speaker_image_blend_mode'] = exhibz_meta_option($post->ID, 'speaker_image_blend_mode', 'darken');
                            $speaker_name = get_the_title($post->ID);
                            ?>
                            <div class="speaker-item <?php echo esc_attr($item_class); ?>"
                                 style="--speaker-overlay-color: <?php echo esc_attr($speaker_overlay_color); ?>; --speaker-overlay-blend-mode: <?php echo esc_attr($speaker_overlay_blend_mode); ?>">
                                <a href="<?php echo esc_url(get_the_permalink($post->ID)); ?>" class="exhibz-img-link">
                                    <div class="speaker-thumb">
                                        <?php
                                        if (get_the_post_thumbnail_url($post->ID)) {
                                            ?>
                                                <img src="<?php echo esc_url(get_the_post_thumbnail_url($post->ID, 'full')); ?>"
                                                    alt="<?php the_title_attribute($post->ID); ?>">
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </a>
                                <div class="speaker-content-wrapper">
                                    <div class="etn-speakers-social">
                                        <?php
                                        if (is_array($social) & !empty($social)) {
                                            ?>
                                            <?php
                                            foreach ($social as $social_value) {
                                                ?>
                                                <a href="<?php echo esc_url($social_value["etn_social_url"]); ?>"
                                                   title="<?php echo esc_attr($social_value["etn_social_title"]); ?>">
                                                    <i class="<?php echo esc_attr($social_value["icon"]); ?>"></i>
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="speaker-information">
                                        <h3 class="exh-speaker-title">
                                            <a href="<?php echo esc_url(get_the_permalink($post->ID)); ?>"><?php echo esc_html($speaker_name); ?></a>
                                        </h3>
                                        <?php if($etn_speaker_designation !=''): ?>
                                            <p class="exh-speaker-designation">
                                                <?php
                                                    echo esc_html($etn_speaker_designation);                                               
                                                ?>
                                            </p>
                                        <?php endif; ?>
                                        <a class="speaker-details-arrow"
                                           href="<?php echo esc_url(get_the_permalink($post->ID)); ?>">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                        wp_reset_postdata();
                        ?>
                        <?php if (!empty($settings['enable_carousel']) && $settings['enable_carousel'] == 'yes') : ?>
                    </div>
                </div>
                <?php if ($settings['show_navigation'] == 'yes') { ?>
                    <div class="speaker-slider-nav-item swiper-button-prev swiper-prev-<?php echo esc_attr($this->get_id()); ?>">
                        <?php \Elementor\Icons_Manager::render_icon($settings['left_arrow_icon'], ['aria-hidden' => 'true']); ?>
                    </div>
                    <div class="speaker-slider-nav-item swiper-button-next swiper-next-<?php echo esc_attr($this->get_id()); ?>">
                        <?php \Elementor\Icons_Manager::render_icon($settings['right_arrow_icon'], ['aria-hidden' => 'true']); ?>
                    </div>
                <?php } ?>
                <?php if ($settings['enable_scrollbar'] == 'yes') { ?>
                    <div class="exhibz-speaker-scrollbar swiper-pagination">
                    </div>
                <?php } ?>
                <?php else: ?>
            </div>
        <?php endif; ?>

        </div>