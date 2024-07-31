<?php

$banner_image         = EXHIBZ_IMG . '/banner/banner_bg.jpg';
$header_style         = exhibz_option('header_layout_style', 'classic');
$banner_settings      = exhibz_option('blog_banner_setting');
$banner_show          = isset($banner_settings['blog_show_banner']) ? $banner_settings['blog_show_banner'] : true;
$breadcrumb_show      = isset($banner_settings['blog_show_breadcrumb']) ? $banner_settings['blog_show_breadcrumb'] : false;
$banner_title_disable = $banner_settings['page_title_disable'] ?? false;
$banner_title         = !empty($banner_settings['banner_blog_title']) ? $banner_settings['banner_blog_title'] : esc_html__('Exhibz Blog', 'instive');
$banner_image         = !empty($banner_settings['banner_blog_image']['url']) ? $banner_settings['banner_blog_image']['url'] : $banner_image;


if(isset($banner_image) && $banner_image != '') {
	$banner_image = 'style="background-image:url(' . esc_url($banner_image) . ');"';
}

$banner_heading_class = ($header_style == "classic") ? 'mt-80' : '';


?>

<?php if(isset($banner_show) && $banner_show == true): ?>
    <div id="page-banner-area" class="page-banner-area" <?php echo wp_kses_post($banner_image); ?>>
        <!-- Subpage title start -->
        <div class="page-banner-title <?php echo esc_attr($banner_heading_class); ?>">

            <div class="text-center">

                <h2 class="banner-title">
					<?php
					if(is_archive()) {
						the_archive_title();
					} else {
						echo esc_html($banner_title);
					}
					?>
                </h2>

				<?php if($breadcrumb_show): ?>
					<?php exhibz_get_breadcrumbs(' / '); ?>
				<?php endif; ?>
            </div>
        </div><!-- Subpage title end -->
    </div><!-- Page Banner end -->
<?php endif; ?>