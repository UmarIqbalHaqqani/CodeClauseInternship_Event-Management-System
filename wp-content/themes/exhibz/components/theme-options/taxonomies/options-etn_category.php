<?php if(!defined('ABSPATH')) {
	die('Direct access forbidden.');
}


/**
 * Eventin Category Texonomy
 */
CSF::createTaxonomyOptions('exhibz-etn-category', [
	'taxonomy'  => 'etn_category',
	'data_type' => 'serialize'
]);

CSF::createSection('exhibz-etn-category', array(
	'fields' => [
		[
			'id'             => 'event_category_featured_img',
			'type'           => 'media',
			'title'          => esc_html__('Upload Feature Image', 'exhibz'),
			'subtitle'       => esc_html__('This will be used as the image', 'exhibz'),
			'url'            => false,
			'preview_width'  => 150,
			'preview_height' => 150,
		],
	]
));