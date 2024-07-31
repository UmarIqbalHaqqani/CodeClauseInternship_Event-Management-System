<?php if(!defined('ABSPATH')) {
	die('Direct access forbidden.');
}

/*
 * Customizer Option: General
 * */

CSF::createSection($prefix, [
	'parent' => 'theme_settings',
	'title' => esc_html__('General settings', 'exhibz'),
	'fields' => [
        [
            'id'     => 'preloader_show',
            'type'   => 'switcher',
            'title'  => esc_html__('Preloader Show', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to show preloader on your site ?', 'exhibz'),
            'default'    => false,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'preloader_logo',
            'type'   => 'media',
            'title'       => esc_html__('Preloader Logo', 'exhibz'),
            'subtitle'   => esc_html__('When you enable preloader then you can set preloader image otherwise default color preloader you will see', 'exhibz'),
            'url'    =>  false,
            'preview_width'  =>  50,
            'preview_height' =>  50,
            'dependency' => array('preloader_show', '==', 'true')
        ],
        [
            'id'     => 'general_text_logo',
            'type'   => 'switcher',
            'title'  => esc_html__('Logo Text', 'exhibz'),
            'default'    => false,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'general_text_logo_title',
            'title'  => esc_html__('Title', 'exhibz'),
            'type'   => 'text',
            'dependency' => array('general_text_logo', '==', 'true')
        ],
        [
            'id'     => 'general_main_logo',
            'type'   => 'media',
            'title'       => esc_html__('Main Logo', 'exhibz'),
            'subtitle'   => esc_html__('It is the main logo, mostly it will be shown on "dark or coloreful" type area.', 'exhibz'),
            'url'    =>  false,
            'preview_width'  =>  50,
            'preview_height' =>  50,
        ],
        [
            'id'     => 'general_dark_logo',
            'type'   => 'media',
            'title'       => esc_html__('Dark Logo', 'exhibz'),
            'subtitle'   => esc_html__('It will be shown on any "light background" type area.', 'exhibz'),
            'url'    =>  false,
            'preview_width'  =>  50,
            'preview_height' =>  50,
        ],
        [
            'id'     => 'general_social_links',
            'type'   => 'group',
            'title'       => esc_html__('Social Links', 'exhibz'),
            'subtitle'   => esc_html__('Add social links and it is icon class bellow. These are all fontaweseome-4.7 icons.', 'exhibz'),
            'fields'  =>  [
                [
                    'id'      => 'title',
                    'title'   => esc_html__('Title', 'exhibz'),
                    'type'    => 'text',
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
            'id'     => 'attendee_show',
            'type'   => 'switcher',
            'title'  => esc_html__('Event Attendee Show/Hide?', 'exhibz'),
            'subtitle'   => esc_html__('Do you want to show or hide attendee?', 'exhibz'),
            'default'    => true,
            'text_on'    => esc_html__('Yes', 'exhibz'),
            'text_off'   => esc_html__('No', 'exhibz'),
        ],
        [
            'id'     => 'attendee_length',
            'title'  => esc_html__('How Many Attendee Want to SHow?', 'exhibz'),
            'subtitle'   => esc_html__('How many attendee do you want to show?', 'exhibz'),
            'type'   => 'text',
            'dependency' => array('attendee_show', '==', 'true')
        ],
	],
]);