<?php

namespace MetForm_Pro\Core\Integrations\Email\Automizy\Settings;

class Automizy_Settings {


	public $url = 'https://gateway.automizy.com/v2/';

	public function get_list( $api_token ) {

		$endpoint = 'smart-lists';
		$headers = [
			"Authorization" => "Bearer $api_token",
			"Content-Type" => "application/json",
			"Accept" => "application/json"
		];

	 	$response = wp_remote_get(
			$this->url . $endpoint,
			[
				'method'   => 'GET',
				'sslverify'   => false,
				'timeout'     => 500,
				'headers'     => $headers,
			]
		);

		if(wp_remote_retrieve_response_code($response) === 200) {
			return json_decode($response['body'] , true) ;
		}

		return [];
	}

	public function add_contact($form_id, $form_data, $form_settings, $attributes) {

		$email_field_name = $attributes['email_field_name'];

		if ( isset( $form_settings['mf_automizy'] ) && $form_settings['mf_automizy'] && $email_field_name && isset( $form_data[ $email_field_name ] ) && $form_data[ $email_field_name ] ) {

			if ( $form_settings['mf_automizy_list_id'] && $form_settings['mf_automizy_api_token'] ) {

				$headers = [
					"Authorization" => "Bearer 2ba7dcccc1908d0fcc09717d2823914ab1a6dff0",
					// . $form_settings['mf_automizy_api_token'],
					"Content-Type"  => "application/json",
					"Accept"        => "application/json"
				];


				$endpoint = $this->url . 'smart-lists/' . $form_settings['mf_automizy_list_id'] . '/contacts';

				$data = [
					'email'        =>  $form_data[ $email_field_name ],
					'customFields' => [
						'firstname' => $form_data['mf-listing-fname'] ?? null,
						'lastname'  => $form_data['mf-listing-lname'] ?? null,
					]
				];

				wp_remote_post(
					$endpoint,
					[
						'sslverify' => false,
						'timeout'   => 5000,
						'headers'   => $headers,
						'body'      => json_encode( $data, true ),
					]
				);

			}
		}
	}

}