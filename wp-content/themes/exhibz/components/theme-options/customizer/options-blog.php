<?php if(!defined('ABSPATH')) {
    die('Direct access forbidden.');
}
/*
 * Customizer Option: Blog
 * */
CSF::createSection($prefix, [
    'parent' => 'theme_settings',
    'title' => esc_html__('Blog settings', 'exhibz'),
    'fields' => [
        [
            'id'     => 'blog_sidebar',
            'type'   => 'select',
            'multiple' => false,
            'title'    => esc_html__('Sidebar', 'exhibz'),
            'subtitle'    => esc_html__('Blog Sidebar', 'exhibz'),
            'options'  => array(
                '1'   => esc_html__('No Sidebar', 'exhibz'),
                '2' => esc_html__('Left Sidebar', 'exhibz'),
                '3' => esc_html__('Right Sidebar', 'exhibz'),
            ),
            'default' => '1',
        ],
        [
            'id'     => 'blog_single_sidebar',
            'type'   => 'select',
            'multiple' => false,
            'title'    => esc_html__('Sidebar Single', 'exhibz'),
            'subtitle'    => esc_html__('Single Post Sidebar', 'exhibz'),
            'options'  => array(
                '1'   => esc_html__('No Sidebar', 'exhibz'),
                '2' => esc_html__('Left Sidebar', 'exhibz'),
                '3' => esc_html__('Right Sidebar', 'exhibz'),
            ),
            'default' => '1',
        ],
        [
            'id'     => 'blog_author',
            'type'   => 'switcher',
            'title'  => esc_html__('Blog author', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to show blog author?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'single_speaker_schedule_sort',
            'type'   => 'switcher',
            'title'  => esc_html__('Speaker schedule sort', 'exhibz'),
            'subtitle'   => esc_html__('This option speaker single page schedule sort by ascending ', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
    ],
]);
