<?php if(!defined('ABSPATH')) {
    die('Direct access forbidden.');
}
/*
 * Customizer Option: Footer
 * */
CSF::createSection($prefix, [
    'parent' => 'theme_settings',
    'title' => esc_html__('Footer settings', 'exhibz'),
    'fields' => [
        [
            'id'     => 'footer_style',
            'type'   => 'image_select',
            'title'  => esc_html__('Select Style', 'exhibz'),
            'options'    => array(
                'style-1' => EXHIBZ_IMG . '/style/footer/style-2.png',
                'style-2' => EXHIBZ_IMG . '/style/footer/style-1.png',
                'style-3' => EXHIBZ_IMG . '/style/footer/style-3.png',
            ),
        ],
        [
            'id'     => 'footer_mailchimp',
            'title'  => esc_html__('Mailchimp Shortcode', 'exhibz'),
            'type'   => 'text',
            'dependency' => array('footer_style', '==', 'style-2')
        ],
        [
            'id'     => 'footer_bg_img',
            'type'   => 'media',
            'title'  => esc_html__('Footer Background Image', 'exhibz'),
            'subtitle'   => esc_html__('It\'s the main Footer background image', 'exhibz'),
            'url'    =>  false,
            'preview_width'  =>  50,
            'preview_height' =>  50,
        ],
        [
            'id'     => 'footer_bg_color',
            'type'   => 'color',
            'title'       => esc_html__('Footer Background color', 'exhibz'),
            'subtitle'   => esc_html__('You can change the footer\'s background color with rgba color or solid color', 'exhibz'),
        ],
        [
            'id'     => 'footer_copyright_color',
            'type'   => 'color',
            'title'       => esc_html__('Footer Copyright color', 'exhibz'),
            'subtitle'   => esc_html__('You can change the footer\'s background color with rgba color or solid color', 'exhibz'),
        ],
        [
            'id'     => 'footer_social_links',
            'type'   => 'group',
            'title'       => esc_html__('Social links', 'exhibz'),
            'subtitle'   => esc_html__('Add social links and it\'s icon class below. These are all fontaweseome-4.7 icons.', 'exhibz'),
            'fields'  =>  [
                [
                    'id'      => 'title',
                    'title'   => esc_html__('Title', 'exhibz'),
                    'type'    => 'text',
                    'default' => 'Facebook'
                ],
                [
                    'id'      => 'icon_class',
                    'title'   => esc_html__('Social icon', 'exhibz'),
                    'type'    => 'icon',
                ],
                [
                    'id'      => 'url',
                    'title'   => esc_html__('Social link', 'exhibz'),
                    'type'    => 'text',
                ],
            ],
        ],
        [
            'id'     => 'footer_copyright',
            'type'   => 'textarea',
            'title'       => esc_html__('Copyright text', 'exhibz'),
            'subtitle'   => esc_html__('This text will be shown at the footer of all pages.', 'exhibz'),
            'default'    => esc_html__('Exhibz. All rights reserved', 'exhibz'),
        ],
        [
            'id'     => 'footer_padding_top',
            'type'   => 'text',
            'title'  => esc_html__('Footer Padding Top', 'exhibz'),
            'subtitle'   => esc_html__('Use Footer Padding Top', 'exhibz'),
        ],
        [
            'id'     => 'back_to_top',
            'type'   => 'switcher',
            'title'  => esc_html__('Back to top', 'exhibz'),
            'default'    => false,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
    ],
]);
