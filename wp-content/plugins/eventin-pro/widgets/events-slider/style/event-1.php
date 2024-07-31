<?php
if (!defined('ABSPATH')) exit;

use \Etn_Pro\Utils\Helper;



$date_options= Helper::get_date_formats();

$event_options = get_option("etn_event_options");
$data = Helper::post_data_query('etn', $event_count, $order, $event_cat, 'etn_category', null, null, $event_tag, $orderby_meta, $orderby, $filter_with_status, $post_parent);
?>
<div class='etn-event-wrapper etn-event-slider' data-count="<?php echo esc_attr($event_slider_count); ?>" data-autoplay="<?php echo esc_attr($auto_play); ?>">
    <div class="swiper-wrapper">

        <?php
        if ( !empty( $data ) ) {
            foreach( $data as $value ) { 
                $social                 = get_post_meta($value->ID, 'etn_event_socials', true);
                $location = \Etn\Core\Event\Helper::instance()->display_event_location($value->ID);
                $category =  Helper::cate_with_link($value->ID, 'etn_category');
                ?>
                <div class="swiper-slide">
                    <div class="etn-event-item">
                        <!-- thumbnail start-->
                        <?php if ( esc_url( get_the_post_thumbnail_url( $value->ID ) ) ) { ?>
                            <div class="etn-event-thumb">
                                    <a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>" aria-label="<?php echo esc_html( get_the_title( $value->ID ) ); ?>">
                                        <img src="<?php echo esc_url( get_the_post_thumbnail_url( $value->ID ) ); ?>" alt="<?php the_title_attribute($value->ID); ?>">
                                    </a>
                                <?php if ($show_category === 'yes') : ?>
                                    <div class="etn-event-category">
                                        <?php echo  Helper::kses($category); ?>
                                    </div>
                                <?php endif; ?>
                                <?php Helper::event_recurring_status($value); ?>

                            </div>
                        <?php } ?>
                        <!-- thumbnail end-->
                        <!-- content start-->
                        <div class="etn-event-content">
                            <?php if($show_event_location === 'yes' && !empty($location)): ?>
                                <div class="etn-event-location">
                                    <i class="etn-icon etn-location"></i>
                                    <?php echo esc_html($location); ?>
                                </div>
                            <?php endif; ?>
                          
                                
                            <h3 class="etn-title etn-event-title"><a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>"> <?php echo esc_html( get_the_title( $value->ID ) ); ?></a> </h3>
                            <?php if ($show_desc == 'yes') : ?>
                                <p><?php echo esc_html(Helper::trim_words( get_the_excerpt($value->ID), $desc_limit)); ?></p>
                            <?php endif; ?>
                            <div class="etn-event-footer">
                                <div class="etn-event-date">
                                     <?php
                                        echo esc_html(Helper::etn_display_date($value->ID, 'yes', $show_end_date)); 
                                    ?>
                                </div>
                                <?php if ($show_btn == 'yes') : ?>
                                    <div class="etn-atend-btn">
                                        <a href="<?php echo esc_url( get_the_permalink( $value->ID ) ); ?>" class="etn-btn etn-btn-border"><?php echo esc_html($btn_text); ?> <i class="etn-icon etn-arrow-right"></i></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- content end-->
                    </div>
                </div>
                <?php
            }
        }
        ?>

    </div>
    <?php if ($slider_nav_show == 'yes') : ?>
        <!-- next / prev arrows -->
        <div class="swiper-button-next"> <i class="etn-icon etn-arrow-right"></i> </div>
        <div class="swiper-button-prev"> <i class="etn-icon etn-arrow-left"></i> </div>
        <!-- !next / prev arrows -->
    <?php endif; ?>
    <?php if ($slider_dot_show == 'yes') : ?>
        <!-- pagination dots -->
        <div class="swiper-pagination"></div>
        <!-- !pagination dots -->
    <?php endif; ?>

</div>