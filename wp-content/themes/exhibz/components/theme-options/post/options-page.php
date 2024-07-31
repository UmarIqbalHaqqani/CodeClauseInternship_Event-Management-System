<?php if(!defined('ABSPATH')) {
    die('Direct access forbidden.');
}
/**
 * Page meta options.
 */
CSF::createMetabox('settings-page', array(
    'title'     => esc_html__('Page Settings', 'exhibz'),
    'post_type' => 'page',
    'context'   => 'normal',
    'theme'     => 'light',
    'data_type' => 'unserialize',
));

CSF::createSection('settings-page', [
    'fields' => [
        [
            'id'     => 'xs_page_section',
            'type'   => 'switcher',
            'title'    => esc_html__('This page is a section:', 'exhibz'),
            'subtitle' => esc_html__('If this a One page Section, set this field to yes. And if this page is not section, set it to no', 'exhibz'),
            'default'  => false,
            'text_on'  => esc_html__('Yes', 'exhibz'),
            'text_off' => esc_html__('No', 'exhibz'),
		],
        [
            'id'     => 'hide_title_from_menu',
            'type'   => 'switcher',
            'title'  => esc_html__('Hide title from menu ?', 'exhibz'),
            'subtitle' => esc_html__('If you dont want to add title(or this page) on menu. you can set yes. if you set yes. this menu will be hide in menu.', 'exhibz'),
            'default'  => false,
            'text_on'  => esc_html__('Yes', 'exhibz'),
            'text_off' => esc_html__('No', 'exhibz'),
            'dependency' => array('xs_page_section', '!=', 'false')
        ],
        [
            'id'     => 'header_title',
            'type'   => 'text',
            'title'  => esc_html__('Banner title', 'exhibz'),
            'subtitle'  => esc_html__('Add your Page hero title', 'exhibz'),
        ],
        [
            'id'     => 'header_image',
            'type'   => 'media',
            'title'       => esc_html__(' Banner image', 'exhibz'),
            'subtitle'   => esc_html__('Upload a page header image', 'exhibz'),
            'url'    =>  false,
            'preview_width'  =>  50,
            'preview_height' =>  50,
        ],
        [
            'id'     => 'page_header_override',
            'type'   => 'switcher',
            'title'  => esc_html__('Override default header layout?', 'autrics'),
            'subtitle'   => esc_html__('Override customizer header layout', 'autrics'),
            'default'    => false,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'page_header_layout_style',
            'type'   => 'image_select',
            'options'    => array(
                'transparent' => EXHIBZ_IMG . '/admin/header-style/style1.png',
                'standard' => EXHIBZ_IMG . '/admin/header-style/style2.png',
                'transparent2' => EXHIBZ_IMG . '/admin/header-style/style3.png',
                'transparent3' => EXHIBZ_IMG . '/admin/header-style/style4.png',
                'classic' => EXHIBZ_IMG . '/admin/header-style/style5.png',
                'center' => EXHIBZ_IMG . '/admin/header-style/style6.png',
                'fullwidth' => EXHIBZ_IMG . '/admin/header-style/style7.png',
            ),
            'dependency' => array('page_header_override', '==', 'true')
        ],
    ],
]);