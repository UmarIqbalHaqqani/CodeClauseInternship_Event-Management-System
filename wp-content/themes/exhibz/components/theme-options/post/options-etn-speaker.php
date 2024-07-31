<?php if(!defined('ABSPATH')) {
die('Direct access forbidden.');
}

//Eventin Speaker Post
CSF::createMetabox('settings-eventin-speaker-post', array(
    'title'     => esc_html__('Speaker Style', 'exhibz'),
    'post_type' => 'etn-speaker',
    'context'   => 'normal',
    'theme'     => 'light',
    'data_type' => 'unserialize',
));


CSF::createSection('settings-eventin-speaker-post', [
    'fields' => [
        [
            'id'     => 'speaker_image_overlay_color',
            'type'   => 'color',
            'title'       => esc_html__('Image Overlay Color', 'exhibz'),
            'default'    => '#FF2E00',
        ],
        [
            'id'     => 'speaker_image_blend_mode',
            'title'  => esc_html__('Overlay Blend Mode', 'exhibz'),
            'type'   => 'select',
            'multiple' => false,
            'desc' => esc_html__('Select Speaker Image Overly Blend Mode.', 'exhibz'),
            'options'  => array(
                'darken'   => esc_html__('Darken', 'exhibz'),
                'multiply' => esc_html__('Multiply Sidebar', 'exhibz'),
            ),
            'default' => 'darken',
        ],
    ],
]);
