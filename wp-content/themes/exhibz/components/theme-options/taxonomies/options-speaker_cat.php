<?php if(!defined('ABSPATH')) {
	die('Direct access forbidden.');
}


/**
 * Speader Category Texonomy
 */
CSF::createTaxonomyOptions('ts-speaker-cat', [
	'taxonomy' => 'ts-speaker_cat'
]);

CSF::createSection('ts-speaker-cat', array(
	'fields' => [
		[
			'id'      => 'exhibz_speaker_count',
			'type'    => 'text',
			'title'   => esc_html__('Speaker Count', 'exhibz'),
			'desc'    => esc_html__('Speaker category page pagination settings', 'exhibz'),
			'default' => '10',
		],
	]
));