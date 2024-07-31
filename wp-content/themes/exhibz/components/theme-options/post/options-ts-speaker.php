<?php

if(!defined('ABSPATH')) {
	die('Direct access forbidden.');
}

//Ts Speaker Details
CSF::createMetabox('settings-ts-speaker-details', array(
	'title'     => esc_html__('Speaker Details', 'exhibz'),
	'post_type' => 'ts-speaker',
	'context'   => 'normal',
	'theme'     => 'light',
	'data_type' => 'unserialize',
));


CSF::createSection('settings-ts-speaker-details', [
	'fields' => [
		[
			'id'    => 'exhibs_designation',
			'type'  => 'text',
			'title' => esc_html__('Designation', 'exhibz'),
		],
		[
			'id'             => 'exhibs_photo',
			'type'           => 'media',
			'title'          => esc_html__('Profile Photo', 'exhibz'),
			'url'            => false,
			'preview_width'  => 120,
			'preview_height' => 120,
		],
		[
			'id'             => 'exhibs_logo',
			'type'           => 'media',
			'title'          => esc_html__('Logo', 'exhibz'),
			'url'            => false,
			'preview_width'  => 120,
			'preview_height' => 120,
		],
		[
			'id'    => 'exhibs_summery',
			'type'  => 'wp_editor',
			'title' => esc_html__('Summary', 'exhibz'),
		],
	],
]);


//Ts Speakers Social Links
CSF::createMetabox('settings-ts-speaker-social-links', array(
	'title'     => esc_html__('Speaker Social Links', 'exhibz'),
	'post_type' => 'ts-speaker',
	'context'   => 'normal',
	'theme'     => 'light',
	'data_type' => 'unserialize',
));

CSF::createSection('settings-ts-speaker-social-links', [
	'fields' => [
		[
			'id'     => 'social',
			'type'   => 'group',
			'title'  => esc_html__('Social Links', 'exhibz'),
			'fields' => [
				[
					'id'    => 'option_site_name',
					'title' => esc_html__('Website Name', 'exhibz'),
					'type'  => 'text',
				],
				[
					'id'    => 'option_site_link',
					'title' => esc_html__('Website Link', 'exhibz'),
					'type'  => 'text',
				],
				[
					'id'    => 'option_site_icon',
					'title' => esc_html__('Icon', 'exhibz'),
					'type'  => 'icon',
				],
			],
		],
	],
]);

//Ts Speakers Social Links
CSF::createMetabox('settings-ts-speaker-square-style', array(
	'title'     => esc_html__('Speaker Style Square Alter', 'exhibz'),
	'post_type' => 'ts-speaker',
	'context'   => 'normal',
	'theme'     => 'light',
	'data_type' => 'unserialize',
));

CSF::createSection('settings-ts-speaker-square-style', [
	'fields' => [
		[
			'id'      => 'speaker_color',
			'type'    => 'color',
			'title'   => esc_html__('Color', 'exhibz'),
			'default' => '#25CD44'
		],
	],
]);
