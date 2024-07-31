<?php 
/*
 * Control core classes for avoid errors
 * */
if (class_exists('CSF')) {

    $option_list = [
        'default',
		'license'
    ];

    // Include customizer options
    if(theme_is_valid_license()) {
        $option_list = [
            'default',
            'general',
            'style',
            'header',
            'banner',
            'blog',
            'footer',
            'optimization'
        ];
    }
    include_framework_options(
        $option_list,
        // Folder name
        "customizer"
    );
 
    // Include posts options
    include_framework_options(
        $option_list = [
            'post',
            'page',
            'etn-speaker',
            'etn',
            'ts-schedule',
            'ts-speaker',
        ],
        // Folder name
        "post",
    );

    // Include Taxonomies Options
    include_framework_options(
        $option_list = [
            'category',
            'etn_category',
            'etn_location',
            'speaker_cat',
        ],
        // Folder name
        "taxonomies"
    );
}

/** 
 * Exhibz Icons Library
 */

 if( ! function_exists( 'exhibz_custom_icons' ) ) {

	function exhibz_custom_icons( $icons ) {
	
		// Adding new icons
		$icons[] = array(
			'title' => 'Exhibz Icons',
			'icons' => array(
                'icon icon-facebook',
                'icon icon-instagram',
                'icon icon-linkedin',
                'icon icon-twitter',
                'icon icon-twitter1',
                'icon icon-youtube',
                'icon icon-dribbble',
                'icon icon-pinterest',
                'icon icon-vimeo',
                'icon icon-soundcloud',
                'icon icon-behance',
                'icon icon-double-left-chevron',
                'icon icon-double-angle-pointing-to-right',
                'icon icon-arrow-point-to-down',
                'icon icon-play-button',
                'icon icon-minus',
                'icon icon-plus',
                'icon icon-download-arrow',
                'icon icon-phone1',
                'icon icon-play',
                'icon icon-search',
                'icon icon-checked',
                'icon icon-clock1',
                'icon icon-down-arrow1',
                'icon icon-down-arrow2',
                'icon icon-right-arrow',
                'icon icon-left-arrows',
                'icon icon-school',
                'icon icon-x-twitter',
                'icon icon-square-x-twitter',
                'icon icon-tiktok',
                'icon icon-whatsapp',
                'icon icon-facebook-messenger'
			)
		);
	
		//
		// Move custom icons to top of the list.
		$icons = array_reverse( $icons );
	
		return $icons;
	
	}

	add_filter( 'csf_field_icon_add_icons', 'exhibz_custom_icons' );
}
