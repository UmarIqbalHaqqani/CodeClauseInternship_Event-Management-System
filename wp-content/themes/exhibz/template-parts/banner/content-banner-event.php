<?php

if(class_exists('CSF') && theme_is_valid_license()) {

    $banner_image         = EXHIBZ_IMG . '/banner/banner_bg.jpg';
    $header_style         = exhibz_option('header_layout_style', 'classic');
    $banner_settings      = exhibz_option('event_banner_setting');
    $banner_show          = isset($banner_settings['event_show_banner']) ? $banner_settings['event_show_banner'] : true;
    $breadcrumb_show      = isset($banner_settings['event_show_breadcrumb']) ? $banner_settings['event_show_breadcrumb'] : false;
    $banner_title         = !empty($banner_settings['banner_event_title']) ? $banner_settings['banner_event_title'] : esc_html__('Exhibz Blog', 'instive');
    $banner_image         = !empty($banner_settings['banner_event_image']['url']) ? $banner_settings['banner_event_image']['url'] : $banner_image;
    $banner_heading_class = ($header_style == "transparent") ? 'mt-80' : '';
} else{
    //default
    $exhibz_banner_image             = '';
    $exhibz_banner_title             = get_bloginfo( 'name' );
    $exhibz_show                     = 'yes';
    $exhibz_show_breadcrumb          = 'no';
      
}


if(isset($banner_image) && $banner_image != '') {
	$banner_image = 'style="background-image:url(' . esc_url($banner_image) . ');"';
}

$banner_heading_class = ($header_style == "classic") ? 'mt-80' : '';


?>


<?php if(isset($banner_show) && $banner_show == true): ?>

    <div class="event-banner ts_eventin_banner align-items-center <?php echo esc_attr($banner_image == '' ? 'banner-solid' : 'banner-bg'); ?>" <?php echo exhibz_kses($banner_image); ?>>
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-12">
                    <h2 class="banner-title <?php echo esc_attr($banner_heading_class); ?>">
						<?php
						if(is_archive()) {
							the_archive_title();
						} elseif(is_single()) {
							the_title();
						} else {
							$exhibz_title = str_replace(['{', '}'], ['<span>', '</span>'], $banner_title);
							echo exhibz_kses($exhibz_title);
						}
						?>
                    </h2>
					<?php if($breadcrumb_show) {
						exhibz_get_breadcrumbs(' / ');
					} ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>