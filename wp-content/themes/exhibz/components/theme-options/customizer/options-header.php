<?php if(!defined('ABSPATH')) {
	die('Direct access forbidden.');
}

/*
 * Customizer Option: Header
 * 
 * */

CSF::createSection($prefix, [
	'parent' => 'theme_settings',
	'title' => esc_html__('Header settings', 'exhibz'),
	'fields' => [
        [
			'id'     => 'header_layout_style',
			'type'   => 'image_select',
            'title'  => esc_html__('Header Style', 'exhibz'),
			'subtitle'   => esc_html__('This is the sites main header style.', 'exhibz'),
			'class'      => 'header_layout_style',
            'options'    => array(
				'transparent' => EXHIBZ_IMG . '/admin/header-style/style1.png',
				'standard'   => EXHIBZ_IMG . '/admin/header-style/style2.png',
				'transparent2'   => EXHIBZ_IMG . '/admin/header-style/style3.png',
				'transparent3'   => EXHIBZ_IMG . '/admin/header-style/style4.png',
				'classic'   => EXHIBZ_IMG . '/admin/header-style/style5.png',
				'center'   => EXHIBZ_IMG . '/admin/header-style/style6.png',
				'fullwidth'   => EXHIBZ_IMG . '/admin/header-style/style7.png',
				'fullwidthsolid'   => EXHIBZ_IMG . '/admin/header-style/style8.png',
				'woo'   => EXHIBZ_IMG . '/admin/header-style/style9.png',
			),
		],
        [
			'id'     => 'header_nav_sticky_section',
			'type'   => 'switcher',
			'title'  => esc_html__('Sticky Header Show', 'exhibz'),
			'subtitle'   => esc_html__('Do you want to show sticky in header?', 'exhibz'),
			'default'    => false,
			'text_on'    => esc_html__('Yes', 'exhibz'),
			'text_off'   => esc_html__('No', 'exhibz'),
		],
		[
			'id'     => 'header_nav_sticky_bg',
			'type'   => 'color',
			'title'       => esc_html__('Sticky Header Backgound', 'exhibz'),
			'subtitle'   => esc_html__('This option will works when sticky header enable', 'exhibz'),
			'default'    => '#1a1831',
            'dependency' => array('header_nav_sticky_section', '==', 'true')
		],
		[
			'id'     => 'header_nav_search',
			'type'   => 'switcher',
			'title'  => esc_html__('Search Button Show', 'exhibz'),
			'subtitle'   => esc_html__('only for header style 5', 'exhibz'),
			'default'    => false,
			'text_on'    => esc_html__('Yes', 'exhibz'),
			'text_off'   => esc_html__('No', 'exhibz'),
		],
		[
			'id'     => 'header_cta_button_settings',
			'type'   => 'accordion',
			'accordions'       => [
                [
					'title'  => esc_html__('Header CTA Button Settings', 'exhibz'),
					'fields' => [
						[
							'id'    => 'header_btn_show',
							'type'  => 'switcher',
							'title'  => esc_html__('Show button?', 'exhibz'),
							'subtitle'   => esc_html__('Show or hide the header button', 'exhibz'),
							'default'    => false,
							'text_on'    => esc_html__('Yes', 'exhibz'),
							'text_off'   => esc_html__('No', 'exhibz'),
						],
						[
							'id'    => 'header_btn_title',
							'type'  => 'text',
							'title' => esc_html__('Button Title', 'exhibz'),
							'dependency' => array('header_btn_show', '==', 'true'),
						],
						[
							'id'    => 'header_btn_bg_color',
							'type'  => 'color',
                            'title' => esc_html__('Button Background', 'exhibz'),
                            'dependency' => array('header_btn_show', '==', 'true'),
						],
						[
							'id'    => 'header_btn_url',
							'type'  => 'text',
							'title' => esc_html__('Button Url', 'exhibz'),
                            'subtitle'   => esc_html__('Put the url of the button', 'exhibz'),
                            'dependency' => array('header_btn_show', '==', 'true'),
						],
					], 
				],
			],
		],
        [
			'id'     => 'header_nav_shopping_cart_section',
			'type'   => 'switcher',
			'title'  => esc_html__('Shopping Cart Show', 'exhibz'),
			'subtitle'   => esc_html__('Only for header woo ', 'exhibz'),
			'default'    => true,
			'text_on'    => esc_html__('Yes', 'exhibz'),
			'text_off'   => esc_html__('No', 'exhibz'),
		],
        [
			'id'     => 'header_top_settings',
			'type'   => 'accordion',
			'accordions'       => [
				[
					'title'  => esc_html__('Header Top Area Settings', 'exhibz'),
					'icon'   => '', 
					'fields' => [ 
						[
							'id'    => 'header_contact_show',
							'type'  => 'switcher',
							'title'  => esc_html__('Show Contact info?', 'exhibz'),
							'subtitle'   => esc_html__('Show or hide the header Contact info', 'exhibz'),
							'default'    => false,
							'text_on'    => esc_html__('Yes', 'exhibz'),
							'text_off'   => esc_html__('No', 'exhibz'),
						],
						[
							'id'    => 'header_contact_icon',
							'type'  => 'icon',
                            'title'  => esc_html__('Contact Icon', 'exhibz'),
                            'subtitle'   => esc_html__('Give Contact Icon (only for header style 5).', 'exhibz'),
                            'dependency' => array('header_contact_show', '==', 'true'),
						],
						[
							'id'    => 'header_contact_number',
							'type'  => 'text',
							'title' => esc_html__('Contact Number', 'exhibz'),
                            'dependency' => array('header_contact_show', '==', 'true'),
                            'subtitle' => esc_html__('Give Contact Number  (only for header style 5).', 'exhibz'),
						],
						[
							'id'    => 'header_contact_mail_icon',
                            'type'  => 'icon',
                            'title'  => esc_html__('Contact Mail Icon', 'exhibz'),
                            'dependency' => array('header_contact_show', '==', 'true'),
                            'subtitle'   => esc_html__('Give Contact Mail Icon (only for header style 5).', 'exhibz'),
						],
						[
							'id'    => 'header_contact_mail',
							'type'  => 'text',
                            'dependency' => array('header_contact_show', '==', 'true'),
							'title' => esc_html__('Contact Mail', 'exhibz'),
							'subtitle' => esc_html__('Give Contact Mail (only for header style 5).', 'exhibz'),
						],
					], 
				],
			],
		],
	],
]);