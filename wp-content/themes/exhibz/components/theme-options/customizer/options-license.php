<?php if (!defined('ABSPATH')) die('Direct access forbidden.');
/**
 * customizer option: License
 */

 
CSF::createSection( $prefix, array(
    'parent' => 'theme_settings',
    'title'  => 'Activate the theme license',
    'fields' => array(
        array(
            'title'  => 'Activate the theme license',
            'type'    => 'notice',
            'style'   => 'danger',
            'help'  => __('Goto Admin Dashboard => Appearance => License and active your license', 'exhibz'),
            'content'  => 'In order to get regular update, support and demo content, you must activate the theme license. Please <a href="'. admin_url('themes.php?page=license') .'">Goto License Page</a> and activate the theme license as soon as possible.',
          ),
    )
) );

