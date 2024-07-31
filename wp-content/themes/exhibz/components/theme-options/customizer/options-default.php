<?php
// Control core classes for avoid errors
if(class_exists('CSF')) {

	// Prefix slug (ID) for theme settings.
	$prefix = 'exhibz_section_theme_settings';

	// Create customize options
    CSF::createCustomizeOptions($prefix, [
        'save_defaults'   => true,
        'enqueue_webfont' => true,
        'async_webfont'   => true
    ]);

	// Creating theme settings parent section
	CSF::createSection($prefix, array(
		'id'       => 'theme_settings',
		'title'    => esc_html__('Theme Settings', 'exhibz'),
		'priority' => 1,
	));
}
