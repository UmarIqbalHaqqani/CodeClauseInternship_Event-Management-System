<?php

if(class_exists('CSF') && theme_is_valid_license()) {
    $button_settings  = exhibz_option('header_cta_button_settings');
    $header_btn_show  = !empty($button_settings['header_btn_show']) ? $button_settings['header_btn_show'] : false;
    $header_btn_url   = !empty($button_settings['header_btn_url']) ? $button_settings['header_btn_url'] : '#';
    $header_btn_title = !empty($button_settings['header_btn_title']) ? $button_settings['header_btn_title'] : ' Buy Ticket ';
} else {
    $header_btn_show = 'no';
	$header_btn_url = '#';
	$header_btn_title = ' Buy Ticket ';
}
?>

<!-- header nav start-->
<header id="header"
        class="header header-transparent <?php echo (exhibz_option('header_nav_sticky_section', false) == true) ? "navbar-fixed" : ''; ?> ">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-6 align-self-center">
				<?php if(exhibz_text_logo()) : ?>
                    <h1 class="logo-title">
                        <a rel='home' class="logo" href="<?php echo esc_url(home_url('/')); ?>">
							<?php echo esc_html(exhibz_text_logo()); ?>
                        </a>
                    </h1>
				<?php else : ?>
                    <a class="navbar-brand logo" href="<?php echo esc_url(home_url('/')); ?>">
                        <img width="158" height="46" src="<?php
						echo esc_url(
							exhibz_src(
								'general_main_logo',
								EXHIBZ_IMG . '/logo/logo-light.png'
							)
						);
						?>" alt="<?php bloginfo('name'); ?>">
                    </a>
				<?php endif; ?>
            </div><!-- Col end -->

            <div class="<?php echo ($header_btn_show == true) ? 'col-lg-7' : 'col-lg-9'; ?>">
				<?php get_template_part('template-parts/navigations/nav', 'primary'); ?>
            </div>


			<?php if($header_btn_show == true) { ?>
                <div class="col-lg-2 d-none d-lg-block text-lg-right">
                    <a class="ticket-btn btn" href="<?php echo esc_url($header_btn_url); ?>">
						<?php echo esc_html($header_btn_title); ?>
                    </a>
                </div>
			<?php } ?>
        </div><!-- Row end -->
    </div>
    <!--Container end -->
</header><!-- Header end -->