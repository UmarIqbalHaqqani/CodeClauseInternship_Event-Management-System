<?php

/*
 * One Click Demo Import added
 * */
function exhibz_import_files() {
	$demo_content_installer = EXHIBZ_REMOTE_CONTENT;

	return [
		[
			'import_file_name'           => 'Main Demo (01-11)',
			'import_file_url'            => $demo_content_installer . '/default/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/default/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/default/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/default/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL
		],
		[
			'import_file_name'           => 'One Page',
			'import_file_url'            => $demo_content_installer . '/onepage/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/onepage/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/onepage/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/onepage/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL . 'onepage'
		],
		[
			'import_file_name'           => 'Food Festival Home',
			'import_file_url'            => $demo_content_installer . '/foodfestival/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/foodfestival/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/foodfestival/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/foodfestival/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL . 'food-festival'
		],
		[
			'import_file_name'           => 'Education Home',
			'import_file_url'            => $demo_content_installer . '/education/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/education/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/education/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/education/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL . 'education'
		],
		[
			'import_file_name'           => 'Classic20 Home',
			'import_file_url'            => $demo_content_installer . '/classic20/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/classic20/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/classic20/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/classic20/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL . 'classic20'
		],
		[
			'import_file_name'           => 'Exhibz WooCommerce',
			'import_file_url'            => $demo_content_installer . '/woo/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/woo/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/woo/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/woo/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL . 'exhibz-woo'
		],
		[
			'import_file_name'           => 'Exhibz Multi Event',
			'import_file_url'            => $demo_content_installer . '/multi_event/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/multi_event/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/multi_event/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/multi_event/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL . 'multi-event'
		],
		[
			'import_file_name'           => 'Creative Conference',
			'import_file_url'            => $demo_content_installer . '/creative_conference/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/creative_conference/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/creative_conference/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/creative_conference/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL . 'creative-conference'
		],
		[
			'import_file_name'           => 'Business',
			'import_file_url'            => $demo_content_installer . '/business/main.xml',
			'import_customizer_file_url' => $demo_content_installer . '/business/customizer.dat',
			'import_widget_file_url'     => $demo_content_installer . '/business/widgets.wie',
			'import_preview_image_url'   => $demo_content_installer . '/business/screenshot.png',
			'preview_url'                => EXHIBZ_LIVE_URL . 'business'
		],
	];
}

add_filter( 'pt-ocdi/import_files', 'exhibz_import_files' );

/*
 * Set default homepages from the demo site.
 * */
function exhibz_after_import( $selected_import ) {
	// Set homepage in imported demo
	$page_setup_array = [
		"Main Demo (01-11)" => [
			"slug" => "Home 1",
		],
		"One Page" => [
			"slug" => "One Page",
		],
		"Food Festival Home" => [
			"slug" => "Home Foods",
		],
		"Education Home" => [
			"slug" => "education",
		],
		"Classic20 Home" => [
			"slug" => "Home",
		],
		"Exhibz WooCommerce" => [
			"slug" => "Home",
		],
		"Exhibz Multi Event" => [
			"slug" => "Home",
		],
		"Creative Conference" => [
			"slug" => "Home",
		],
	];


	if ( is_array( $page_setup_array ) ) {
		foreach ( $page_setup_array as $i => $values ) {
			if ( $i === $selected_import['import_file_name'] ) {
				foreach ( $values as $key => $value ) {
					//Set Front page
					$page_query = new WP_Query( array(
						'post_type' => 'page',
						'post_status' => 'publish',
						'posts_per_page' => 1,
						'title' => $values['slug'],
					) );
					if ( $page_query->have_posts() ) {
						$page = $page_query->posts[0];
						update_option( 'page_on_front', $page->ID );
						update_option( 'show_on_front', 'page' );
					}

					// Reset post data
					wp_reset_postdata();
				}
			}
		}
	}

	// Set menu after demo import
	$primary_menu    = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
	$footer_menu     = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
	$sub_header_menu = get_term_by( 'name', 'Sub Header Menu', 'nav_menu' );
	set_theme_mod( 'nav_menu_locations', [
			'primary'    => $primary_menu->term_id,
			'footermenu' => $footer_menu->term_id,
			'submenu'    => $sub_header_menu->term_id,
		]
	);
}

add_action( 'pt-ocdi/after_import', 'exhibz_after_import' );

function demo_license_content() {
	?>
	<div class="license-wrap">
		<h2 class="license-title"><?php esc_html_e( 'Please Activate Your License', 'nuclear-txd' ); ?></h2>
		<div class="license-desc">
			<div class="notice-icon">
				<svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.27148 5.6001V9.80009" stroke="#FF7129" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M15.536 6.26402V11.736C15.536 12.632 15.056 13.464 14.28 13.92L9.52801 16.664C8.75201 17.112 7.792 17.112 7.008 16.664L2.256 13.92C1.48 13.472 1 12.64 1 11.736V6.26402C1 5.36802 1.48 4.53599 2.256 4.07999L7.008 1.336C7.784 0.888 8.74401 0.888 9.52801 1.336L14.28 4.07999C15.056 4.53599 15.536 5.36002 15.536 6.26402Z" stroke="#FF7129" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M8.27148 12.3599V12.4399" stroke="#FF7129" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
			<p>
			<?php 
				echo exhibz_kses('In order to get regular update, support and demo content, you must activate the theme license. Please  <a href="'. admin_url('themes.php?page=license') .'">Goto License Page</a> and activate the theme license as soon as possible.	','exhibz');
			?>
			</p>
		</div>
	</div>
	<?php
}

function set_license_menu() {
	if ( theme_is_valid_license() ) {
		return;
	}

	remove_submenu_page('theme.php', 'demo-content');
	$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

	if ( 'one-click-demo-import' === $page ) {
		// wp_die('Sorry, you are not allowed to access this page', '');
		wp_redirect(admin_url("themes.php?page=license"));
	}

	add_submenu_page(
		'themes.php',
		'Demo Content Install',
		'manage_options',
		'demo-content',
		'demo_license_content'
	);
}

add_action('admin_menu', 'set_license_menu', 999);



