<?php

if(!defined('ABSPATH')) {
	die('Direct access forbidden.');
}

$speakers = get_posts(array(
    'post_type'      => 'ts-speaker',
    'posts_per_page' => -1,
));

// Create an options array from the fetched speakers
$options = [];
$options['custom_break'] = 'Break';
foreach ($speakers as $speaker) {
    $options[$speaker->ID] = $speaker->post_title;
}


//Ts Schedule Day Post
CSF::createMetabox('settings-ts-schedule-day-post', array(
	'title'     => esc_html__('Day Title', 'exhibz'),
	'post_type' => 'ts-schedule',
	'context'   => 'normal',
	'theme'     => 'light',
	'data_type' => 'unserialize',
));

CSF::createSection('settings-ts-schedule-day-post', [
	'fields' => [
		[
			'id'    => 'schedule_day',
			'type'  => 'text',
			'title' => esc_html__('Schedule Day', 'exhibz'),
		],
	],
]);


//Ts All Schedule Post
CSF::createMetabox('settings-ts-all-schedule-post', array(
	'title'     => esc_html__('Schedule', 'exhibz'),
	'post_type' => 'ts-schedule',
	'context'   => 'normal',
	'theme'     => 'light',
	'data_type' => 'unserialize',
));

CSF::createSection('settings-ts-all-schedule-post', [
	'fields' => [
		[
			'id'     => 'exhibz_schedule_pop_up',
			'type'   => 'group',
			'title'  => esc_html__('All Schedule', 'exhibz'),
			'desc'   => esc_html__('Add Your Schedule', 'exhibz'),
			'fields' => [
				[
					'id'    => 'schedule_time',
					'type'  => 'text',
					'title' => esc_html__('Time', 'exhibz'),
				],
				[
					'id'         => 'speakers',
					'type'       => 'select',
					'title'      => esc_html__('Speakers', 'exhibz'),
					// 'options'    => 'posts',
					'options'    => $options,
					// 'query_args' => array(
					// 	'post_type'      => 'ts-speaker',
					// 	'posts_per_page' => -1,
					// ),
				],
				[
					'id'       => 'style',
					'type'     => 'switcher',
					'title'    => esc_html__('Multi Speaker', 'exhibz'),
					'default'  => true,
					'text_on'  => esc_html__('Yes', 'exhibz'),
					'text_off' => esc_html__('No', 'exhibz'),
				],
				[
					'id'          => 'multi_speakers',
					'type'        => 'select',
					'title'       => esc_html__('Multi Speakers', 'exhibz'),
					'subtitle'   => esc_html__('Use elementor style multi tab circle', 'exhibz'),
					'chosen'      => true,
					'multiple'    => true,
					'placeholder' => 'Select Multi Speakers',
					'options'     => 'posts',
					'query_args'  => array(
						'post_type'      => 'ts-speaker',
						'posts_per_page' => -1,
					),
					'dependency'  => array('style', '==', 'true')
				],
				[
					'id'    => 'schedule_title',
					'type'  => 'text',
					'title' => esc_html__('Schedule Title', 'exhibz'),
				],
				[
					'id'    => 'schedule_note',
					'type'  => 'wp_editor',
					'title' => esc_html__('Short Note', 'exhibz'),
				],
			],
		],
	],
]);
