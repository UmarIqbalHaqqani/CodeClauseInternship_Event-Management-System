<?php

defined( 'ABSPATH' ) || exit;

$banner_bg_type         = get_post_meta($single_event_id, 'banner_bg_type', true);
$banner_bg_image        = get_post_meta($single_event_id, 'banner_bg_image', true);
$banner_image           = wp_get_attachment_image_src($banner_bg_image, 'large');
$banner_bg_color        = get_post_meta($single_event_id, 'etn_event_calendar_bg', true);
$etn_banner             = get_post_meta($single_event_id, 'etn_banner', true);
$event_logo             = get_post_meta($single_event_id, 'etn_event_logo', true);
$text_color             = get_post_meta( $single_event_id, 'etn_event_calendar_text_color', true );

$text_color             = $text_color ?: '#fff';

$banner_style = '';
$banner_image_url       = get_post_meta( $single_event_id, 'event_banner', true );


if ( ! $banner_image_url && is_array( $banner_image )) {
    $banner_image_url = $banner_image[0];
}

if ( $banner_image_url ) {
    $banner_style = 'style=background-image:url(' . esc_url( $banner_image_url ) . ');';
} else {
    $banner_background_color = !empty( $banner_bg_color ) ? $banner_bg_color : "#ff057c";
    $banner_style    = 'style=background-color:' . esc_attr( $banner_background_color ) . ';';
}

$is_banner_on   = ! empty( $banner_bg_color ) || ! empty( $banner_image_url );

// banner area 
if ( $is_banner_on ) : 
    ?>
    <div class="etn-event-banner-wrap etn-event-single2" <?php echo esc_attr($banner_style); ?>>
        <div class="etn-container">
            <div class="etn-row">
                <div class="etn-col-lg-10">
                    <div class="etn-event-banner-content">
                        <?php if ( $event_logo ) : ?>
                            <div class="etn-event-logo">
                                <img src="<?php echo esc_url( $event_logo ); ?>" alt="<?php the_title_attribute(); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="etn-banner-content">
                            
                            <?php do_action("etn_before_single_event_content_title", $single_event_id); ?>

                            <h1 class="etn-event-entry-title" style="color: <?php echo esc_attr( $text_color )?>;">
                                <?php echo esc_html( apply_filters('etn_single_event_content_title', get_the_title()) ); ?> 
                            </h1>

                            <?php do_action("etn_after_single_event_content_title", $single_event_id); ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
endif; 