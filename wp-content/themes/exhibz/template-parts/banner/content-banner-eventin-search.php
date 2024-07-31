<?php

    if(class_exists('CSF') && theme_is_valid_license()) {
        $banner_image = EXHIBZ_IMG.'/banner/banner_bg.jpg';
        $blog_banner_title_color = "#FFFFFF";
        $exhibz_banner_settings = exhibz_option('event_banner_setting');
        $banner_show          = isset($exhibz_banner_settings['event_show_banner']) ? $exhibz_banner_settings['event_show_banner'] : true;
        $exhibz_show_breadcrumb =  (isset($exhibz_banner_settings['event_show_breadcrumb'])) ? $exhibz_banner_settings['event_show_breadcrumb'] : false;
        $banner_image         = !empty($exhibz_banner_settings['banner_event_image']['url']) ? $exhibz_banner_settings['banner_event_image']['url'] : $banner_image;
        $meta_banner_image = exhibz_meta_option(get_the_ID(), 'event_banner_image');
    } else{
        //default
        $exhibz_banner_image             = '';
        $exhibz_banner_title             = get_bloginfo( 'name' );
        $exhibz_show                     = 'yes';
        $exhibz_show_breadcrumb          = 'no';
          
    }
    
    if(!empty($meta_banner_image['url'])) {
        $banner_image = $meta_banner_image['url'];
    }
    if( isset($banner_image)){
       $banner_image = 'style="background-image:url('.esc_url( $banner_image ).');"';
    }

    // Event location setting
    $event_location = "";
    if (isset($_GET['etn_event_location']) && !empty($_GET['etn_event_location'])) {
        $event_location = $_GET['etn_event_location'];
    }
    $count = count(get_exhibz_eventin_data());
?>
<?php if($banner_show === true) : ?>
<section class="ts-banner banner-area ts_eventin_banner blog-banner <?php echo esc_attr($banner_image == ''?'banner-solid':'banner-bg'); ?>"  style="background-image: url(<?php echo esc_attr( $banner_image ); ?>)">
    <div class="container">
        <div class="d-flex align-items-cente">
            <div class="row w-100">
                <div class="col-lg-12 text-center">
                    <h1 class="banner-title banner-blog-title" style="color: <?php echo esc_attr($blog_banner_title_color === '' ? '#ffffff' : $blog_banner_title_color); ?>">
                        <?php echo esc_html__( "Search: Event in ".$event_location."", "exhibz" ); ?>
                    </h1>
                    <p class="banner-subtitle banner-blog-title" style="color: <?php echo esc_attr($blog_banner_title_color === '' ? '#ffffff' : $blog_banner_title_color); ?>">
                        <?php echo esc_html__( "Discover ". $count ." Upcoming "._n( "Event", "Events", $count, "exhibz" )."", "exhibz" ); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
            if( $exhibz_show_breadcrumb == true && !is_home(  ) ){
                exhibz_get_breadcrumbs();
            }
        ?>
    </div>
</section>
<?php endif; ?>