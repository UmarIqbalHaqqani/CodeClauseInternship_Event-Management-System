<?php

if(class_exists('CSF') && theme_is_valid_license()) {

    $banner_image         = EXHIBZ_IMG . '/banner/banner_bg.jpg';  // Default page banner static image
    $header_style         = exhibz_option('header_layout_style', 'classic');
    $banner_settings      = exhibz_option('page_banner_setting');
    $show                 = isset($banner_settings['page_show_banner']) ? $banner_settings['page_show_banner'] : true;
    $show_breadcrumb      = isset($banner_settings['page_show_breadcrumb']) ? $banner_settings['page_show_breadcrumb'] : true;
    $banner_image         = !empty($banner_settings['banner_page_image']['url']) ? $banner_settings['banner_page_image']['url'] : $banner_image;
    $title_from_meta      = exhibz_meta_option(get_the_ID(), 'header_title');
    if(exhibz_meta_option( get_the_ID(), 'header_title' ) != ''){
        $banner_title  = exhibz_meta_option( get_the_ID(), 'header_title' );
    }elseif($banner_settings['banner_page_title'] != ''){
        $banner_title = $banner_settings['banner_page_title'];
    } else {
        $banner_title = get_the_title();
    }
    $banner_heading_class = ($header_style == "classic") ? 'mt-80' : '';
} else {
    //default
   $exhibz_banner_image             = '';
   $exhibz_banner_title             = get_bloginfo( 'name' );
   $exhibz_show                     = 'yes';
   $exhibz_show_breadcrumb          = 'no';
}

// Banner image can be overwritten from page meta option
$meta_banner_image = exhibz_meta_option(get_the_ID(), 'header_image');
if(!empty($meta_banner_image['url'])) {
	$banner_image = $meta_banner_image['url'];
}
// Creating banner background CSS
if(isset($banner_image) && $banner_image != '') {
	$banner_image = 'style="background-image:url(' . esc_url($banner_image) . ');"';
}

?>

<?php if(isset($show) && $show == true): ?>
    <div id="page-banner-area" class="page-banner-area" <?php echo wp_kses_post($banner_image); ?>>
        <!-- Subpage title start -->
        <div class="page-banner-title <?php echo esc_attr($banner_heading_class); ?>">
            <div class="text-center">
				<?php if($banner_title != ''): ?>
                    <h2 class="banner-title">
						<?php echo esc_html($banner_title); ?>
                    </h2>
				<?php endif; ?>
				<?php exhibz_get_breadcrumbs(' / '); ?>
                <?php if($show_breadcrumb): ?>
				<?php endif; ?>
            </div>
        </div><!-- Subpage title end -->
    </div><!-- Page Banner end -->
<?php endif; ?>