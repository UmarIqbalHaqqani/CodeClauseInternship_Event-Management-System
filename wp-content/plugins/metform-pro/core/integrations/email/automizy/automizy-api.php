<?php

namespace MetForm_Pro\Core\Integrations\Email\Automizy;


use MetForm_Pro\Core\Integrations\Email\Automizy\Settings\Automizy_Settings;

class Automizy_Api extends \MetForm\Base\Api{


	public function config() {
		$this->prefix = 'integration/automizy';
	}

	public function get_contact_list() {
		$settings = \MetForm\Core\Admin\Base::instance()->get_settings_option();
		$api_token = $settings['mf_automizy_api_token'] ?? null;
		return ( new Automizy_Settings() )->get_list( $api_token );
	}
	
	public function get_store_get_response_list($r = null) {
		$data = $this->request->get_params();
		if(isset($data['refresh']) && $data['refresh'] === 'yes' || $r === 'yes') {
			$response = $this->get_contact_list();
			if(!empty($response)) {
				if(!isset($response['status']) ) {
					update_option('wpmet_automize_smart_lists', serialize($response['smartLists']));
					return $response['smartLists'];
				}
			}
			return false;
		}
		
		$response = get_option('wpmet_automize_smart_lists');
		if(empty($response)) {
			return $this->get_store_get_response_list('yes');
		}
		return unserialize($response);
	}

}