<?php

namespace MetForm_Pro\Core\Integrations\Email\Automizy;


use MetForm_Pro\Core\Integrations\Email\Automizy\Settings\Automizy_Settings;

class Automizy_Integration {


	public function init(){

		add_action( 'get_automixy_settings_content', [ $this, 'get_form_settings_content'] );
		add_action( 'get_pro_settings_tab_for_newsletter_integration', [ $this, 'get_settings_tab'] );
		add_action( 'get_pro_settings_tab_content_for_newsletter_integration', [ $this, 'get_settings_tab_content'], 10, 1 );

		new Automizy_Api();

		add_action('metform_after_store_form_data', [ new Automizy_Settings(), 'add_contact'], 20, 4);

	}

	public function get_form_settings_content(){
		return include __DIR__.'/templates/form-settings-content.php' ;
	}

	public function get_settings_tab(){
		return include __DIR__.'/templates/settings-tab.php' ;
	}

	public function get_settings_tab_content( $settings ) {

		return include __DIR__.'/templates/settings-tab-content.php' ;
	}

}