<?php if(!defined('ABSPATH')) {
    die('Direct access forbidden.');
}
/*
 * Customizer Option: General
 * */
CSF::createSection($prefix, [
    'parent' => 'theme_settings',
    'title' => esc_html__('Optimization Settings', 'exhibz'),
    'fields' => [
        [
            'id'     => 'optimization_blocklibrary_enable',
            'type'   => 'switcher',
            'title'  => esc_html__('Load Block Library CSS Files?', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to load block library css files?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'optimization_fontawesome_enable',
            'type'   => 'switcher',
            'title'  => esc_html__('Load Fontawesome Icons?', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to load font awesome icons?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'optimization_elementoricons_enable',
            'type'   => 'switcher',
            'title'  => esc_html__('Load Elementor Icons?', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to load elementor icons?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'optimization_elementkitsicons_enable',
            'type'   => 'switcher',
            'title'  => esc_html__('Load Elementskit Icons?', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to load elementskit icons?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'optimization_dashicons_enable',
            'type'   => 'switcher',
            'title'  => esc_html__('Load Dash Icons?', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to load dash icons?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'optimization_meta_viewport',
            'type'   => 'switcher',
            'title'  => esc_html__('Load Meta Description?', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to load meta description in header?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'optimization_eventin_enable',
            'type'   => 'switcher',
            'title'  => esc_html__('Load Eventin CSS/Js in Frontpage?', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to eventin css/js in frontpage?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
    ],
]);

