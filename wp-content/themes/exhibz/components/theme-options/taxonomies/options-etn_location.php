<?php if(!defined('ABSPATH')) {
	die('Direct access forbidden.');
}


/**
 * Eventin Location Texonomy
 */
CSF::createTaxonomyOptions('exhibz-etn-location', [
	'taxonomy' => 'event_location'
]);

CSF::createSection('exhibz-etn-location', array(
	'fields' => [
		[
			'id'             => 'featured_upload_img',
			'type'           => 'media',
			'title'          => esc_html__('Upload Location Feature Image', 'exhibz'),
			'subtitle'       => esc_html__('This will be used as the image', 'exhibz'),
			'url'            => false,
			'preview_width'  => 150,
			'preview_height' => 150,
		],
	]
));