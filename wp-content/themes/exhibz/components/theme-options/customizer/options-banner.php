<?php
if(!defined('ABSPATH')) {
    die('Direct access forbidden.');
}
/*
 * Customizer Option: Banner
 * */
CSF::createSection($prefix, [
    'parent' => 'theme_settings',
    'title' => esc_html__('Banner Settings', 'exhibz'),
    'fields' => [
        [
            'id'     => 'banner_title_color',
            'type'   => 'color',
            'title'       => esc_html__('Banner Title color', 'exhibz'),
            'subtitle'   => esc_html__('Banner title color.', 'exhibz'),
        ],
        [
            'id'     => 'page_banner_setting',
            'type'   => 'accordion',
            'accordions'  => [
                [
                    'title'  => esc_html__('Page Banner settings', 'exhibz'),
                    'icon'   => '',
                    'fields' => [
                        [
                            'id'    => 'page_show_banner',
                            'type'  => 'switcher',
                            'title'  => esc_html__('Show banner?', 'exhibz'),
                            'subtitle'   => esc_html__('Show or hide the banner', 'exhibz'),
                            'default'    => true,
                            'text_on'    => esc_html__('Yes', 'exhibz'),
                            'text_off'   => esc_html__('No', 'exhibz'),
                        ],
                        [
                            'id'    => 'page_show_breadcrumb',
                            'type'  => 'switcher',
                            'title'  => esc_html__('Show Breadcrumb?', 'exhibz'),
                            'subtitle'   => esc_html__('Show or hide the Breadcrumb', 'exhibz'),
                            'default'    => true,
                            'text_on'    => esc_html__('Yes', 'exhibz'),
                            'text_off'   => esc_html__('No', 'exhibz'),
                        ],
                        [
                            'id'    => 'banner_page_title',
                            'type'  => 'text',
                            'title' => esc_html__('Banner title', 'exhibz'),
                            'default' => 'Welcome Exhibz',
                        ],
                        [
                            'id'    => 'banner_page_image',
                            'type'  => 'media',
                            'title'       => esc_html__('Banner image', 'exhibz'),
                            'url'    =>  false,
                            'preview_width'  =>  50,
                            'preview_height' =>  50,
                        ],
                    ],
                ],
            ],
        ],
        [
            'id'     => 'blog_banner_setting',
            'type'   => 'accordion',
            'accordions'       => [
                [
                    'title'  => esc_html__('Blog Banner settings', 'exhibz'),
                    'icon'   => '',
                    'fields' => [
                        [
                            'id'    => 'blog_show_banner',
                            'type'  => 'switcher',
                            'title'  => esc_html__('Show banner?', 'exhibz'),
                            'subtitle'   => esc_html__('Show or hide the banner', 'exhibz'),
                            'default'    => true,
                            'text_on'    => esc_html__('Yes', 'exhibz'),
                            'text_off'   => esc_html__('No', 'exhibz'),
                        ],
                        [
                            'id'    => 'blog_show_breadcrumb',
                            'type'  => 'switcher',
                            'title'  => esc_html__('Show Breadcrumb?', 'exhibz'),
                            'subtitle'   => esc_html__('Show or hide the Breadcrumb', 'exhibz'),
                            'default'    => true,
                            'text_on'    => esc_html__('Yes', 'exhibz'),
                            'text_off'   => esc_html__('No', 'exhibz'),
                        ],
                        [
                            'id'    => 'banner_blog_title',
                            'type'  => 'text',
                            'title' => esc_html__('Banner title', 'exhibz'),
                        ],
                        [
                            'id'    => 'banner_blog_image',
                            'type'  => 'media',
                            'title'       => esc_html__('Image', 'exhibz'),
                            'subtitle'   => esc_html__('Banner blog image', 'exhibz'),
                            'url'    =>  false,
                            'preview_width'  =>  50,
                            'preview_height' =>  50,
                        ],
                    ],
                ],
            ],
        ],
        [
            'id'     => 'shop_banner_settings',
            'type'   => 'accordion',
            'accordions'       => [
                [
                    'title'  => esc_html__('Shop banner settings', 'exhibz'),
                    'icon'   => '',
                    'fields' => [
                        [
                            'id'    => 'show',
                            'type'  => 'switcher',
                            'title'  => esc_html__('Show banner?', 'exhibz'),
                            'default'    => true,
                            'text_on'    => esc_html__('Yes', 'exhibz'),
                            'text_off'   => esc_html__('No', 'exhibz'),
                        ],
                        [
                            'id'    => 'show_breadcrumb',
                            'type'  => 'switcher',
                            'title'  => esc_html__('Show breadcrumb?', 'exhibz'),
                            'default'    => true,
                            'text_on'    => esc_html__('Yes', 'exhibz'),
                            'text_off'   => esc_html__('No', 'exhibz'),
                        ],
                        [
                            'id'    => 'title',
                            'type'  => 'text',
                            'title' => esc_html__('Shop Banner title', 'exhibz'),
                        ],
                        [
                            'id'    => 'single_title',
                            'type'  => 'text',
                            'title' => esc_html__('Single Shop Banner title', 'exhibz'),
                        ],
                        [
                            'id'    => 'image',
                            'type'  => 'media',
                            'title'       => esc_html__('Banner image', 'exhibz'),
                            'url'    =>  false,
                            'preview_width'  =>  50,
                            'preview_height' =>  50,
                        ],
                    ],
                ],
            ],
        ],
        [
            'id'     => 'event_banner_setting',
            'type'   => 'accordion',
            'accordions'       => [
                [
                    'title'  => esc_html__('Event Banner settings', 'exhibz'),
                    'icon'   => '',
                    'fields' => [
                        [
                            'id'    => 'event_show_banner',
                            'type'  => 'switcher',
                            'title'  => esc_html__('Show banner?', 'exhibz'),
                            'subtitle'   => esc_html__('Show or hide the banner', 'exhibz'),
                            'default'    => true,
                            'text_on'    => esc_html__('Yes', 'exhibz'),
                            'text_off'   => esc_html__('No', 'exhibz'),
                        ],
                        [
                            'id'    => 'event_show_breadcrumb',
                            'type'  => 'switcher',
                            'title'  => esc_html__('Show Breadcrumb?', 'exhibz'),
                            'subtitle'   => esc_html__('Show or hide the Breadcrumb', 'exhibz'),
                            'default'    => true,
                            'text_on'    => esc_html__('Yes', 'exhibz'),
                            'text_off'   => esc_html__('No', 'exhibz'),
                        ],
                        [
                            'id'    => 'banner_event_title',
                            'type'  => 'text',
                            'title' => esc_html__('Banner title', 'exhibz'),
                        ],
                        [
                            'id'    => 'banner_event_image',
                            'type'  => 'media',
                            'title' => esc_html__('Image', 'exhibz'),
                            'subtitle'  => esc_html__('Banner event image', 'exhibz'),
                            'url'    =>  false,
                            'preview_width'  =>  50,
                            'preview_height' =>  50,
                        ],
                    ],
                ],
            ],
        ],
    ],
]);
