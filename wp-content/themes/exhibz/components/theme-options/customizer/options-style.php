<?php if(!defined('ABSPATH')) {
	die('Direct access forbidden.');
}

/*
 * Customizer Option: Style
 * */

CSF::createSection($prefix, [
	'parent' => 'theme_settings',
	'title' => esc_html__('Style settings', 'exhibz'),
	'fields' => [
		[
			'id'     => 'body_bg_img',
			'type'   => 'media',
			'title'       => esc_html__('Body Background Image', 'exhibz'),
			'subtitle'   => esc_html__('It is the main body background image', 'exhibz'),
			'url'    =>  false,
			'preview_width'  =>  50,
			'preview_height' =>  50,
		],
		[
			'id'     => 'style_body_bg',
			'type'   => 'color',
			'title'       => esc_html__('Body Background', 'exhibz'),
			'subtitle'   => esc_html__('Sites main background color.', 'exhibz'),
		],
				[
			'id'     => 'style_body_text_color',
			'type'   => 'color',
			'title'       => esc_html__('Body Text Color', 'exhibz'),
			'subtitle'   => esc_html__('Sites main body text color.', 'exhibz'),
		],
		[
			'id'     => 'style_primary',
			'type'   => 'color',
			'title'       => esc_html__('Primary Color', 'exhibz'),
			'subtitle'   => esc_html__('Sites main color.', 'exhibz'),
		],
				[
			'id'     => 'secondary_color',
			'type'   => 'color',
			'title'       => esc_html__('Hover Color', 'exhibz'),
			'subtitle'   => esc_html__('Site Hover Color.', 'exhibz'),
		],
				[
			'id'     => 'title_color',
			'type'   => 'color',
			'title'       => esc_html__('Title Color', 'exhibz'),
			'subtitle'   => esc_html__('Blog title color.', 'exhibz'),
		],
		[
			'id'     => 'body_font',
			'type'   => 'typography',
            'title'    => esc_html__('Body Font', 'exhibz'),
			'subtitle' => esc_html__('Choose the typography for the body', 'exhibz'),
            'output' => 'body',

            'default'        => array(
				'font-family' => 'Roboto',
				'font-size'   => '16',
				'unit'        => 'px',
				'type'        => 'google',
			),
			'font_style'     => true,
			'text_align'     => false,
			'line_height'    => true,
			'letter_spacing' => false,
			'text_transform' => false,
			'color'          => false,
			'subset'         => false,
		],
		[
			'id'     => 'heading_font_one',
			'type'   => 'typography',
            'title'    => esc_html__('Heading H1 and H2 Fonts', 'exhibz'),
			'subtitle' => esc_html__('This is for heading google fonts', 'exhibz'),
            'output' => 'html.fonts-loaded h1 , html.fonts-loaded h2',

            'default'        => array(
				'font-family' => 'Raleway',
                'font-weight' => '700',
				'unit'        => 'px',
				'type'        => 'google',
			),
			'font_style'     => true,
			'text_align'     => false,
			'line_height'    => true,
			'letter_spacing' => false,
			'text_transform' => false,
			'color'          => false,
			'subset'         => false,
		],
		[
			'id'     => 'heading_font_two',
			'type'   => 'typography',
            'title'    => esc_html__('Heading H3 Fonts', 'exhibz'),
			'subtitle' => esc_html__('This is for heading google fonts', 'exhibz'),
            'output' => 'html.fonts-loaded h3',

            'default'        => array(
				'font-family' => 'Raleway',
                'font-weight' => '700',
				'unit'        => 'px',
				'type'        => 'google',
			),
			'font_style'     => true,
			'text_align'     => false,
			'line_height'    => true,
			'letter_spacing' => false,
			'text_transform' => false,
			'color'          => false,
			'subset'         => false,
		],
		[
			'id'     => 'heading_font_three',
			'type'   => 'typography',
            'title'    => esc_html__('Heading H4 Fonts', 'exhibz'),
			'subtitle' => esc_html__('This is for heading google fonts', 'exhibz'),
            'output' => 'html.fonts-loaded h4',

            'default'        => array(
				'font-family' => 'Roboto',
                'font-weight' => '700',
				'unit'        => 'px',
				'type'        => 'google',
			),
			'font_style'     => true,
			'text_align'     => false,
			'line_height'    => true,
			'letter_spacing' => false,
			'text_transform' => false,
			'color'          => false,
			'subset'         => false,
		],
	],
]);