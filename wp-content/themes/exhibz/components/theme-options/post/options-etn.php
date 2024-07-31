<?php
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

//Eventin Speaker Post
CSF::createMetabox('settings-eventin-post', array(
    'title' => esc_html__('Event Banner Settings', 'exhibz'),
    'post_type' => 'etn',
    'context' => 'normal',
    'theme' => 'light',
    'data_type' => 'unserialize',
));


CSF::createSection('settings-eventin-post', [
    'fields' => [
        [
            'id'         => 'event_banner_image',
            'type'       => 'media',
            'title'      => esc_html__('Event Banner Image', 'exhibz'),
            'desc' => esc_html__('Upload a event banner image', 'exhibz'),
            'url'    =>  false,
            'preview_width'  =>  150,
            'preview_height' =>  150,
        ],
    ],
]);
