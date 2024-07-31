<?php

namespace MetForm_Pro\Core\Integrations\Pdf_Export;

use MetForm_Pro\Base\Api;

class Pdf_Export_Api extends Api {

	public function config() {

		$this->prefix = 'pdf-export';
	}

	public function get_entry() {

		$param = $this->request->get_params();

		if(!empty($param['entry_id'])) {

			$entry_id = intval($param['entry_id']);

			$form_id = get_post_meta($entry_id, 'metform_entries__form_id', true);

            $form_inputs = \MetForm\Core\Entries\Action::instance()->get_fields($form_id);

			$form_data = get_post_meta($entry_id, 'metform_entries__form_data', true);

			$files = get_post_meta($entry_id, 'metform_entries__file_upload', true);

			if(is_array($files) && is_array($form_data)) {
				foreach($files as $key => $data) {
					$form_data[$key] = $data['url'];
				}
			}

			return ['form_data' => $form_data, 'form_inputs' => $form_inputs];
		}

		return [];
	}

}
