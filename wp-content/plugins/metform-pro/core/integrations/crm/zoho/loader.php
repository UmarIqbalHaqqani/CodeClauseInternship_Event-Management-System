<?php

namespace MetForm_Pro\Core\Integrations\Crm\Zoho;

use MetForm_Pro\Traits\Singleton;
use MetForm_Pro\Utils\Render;

defined('ABSPATH') || exit;

class Integration
{
    use Singleton;

    /**
     * @var mixed
     */
    private $parent_id;
    /**
     * @var mixed
     */
    private $sub_tab_id;
    /**
     * @var mixed
     */
    private $sub_tab_title;

    public function init()
    {
        /**
         *
         * Create a new tab in admin settings tab
         *
         */

        $this->parent_id = 'mf_crm';

        $this->sub_tab_id    = 'zoho';
        $this->sub_tab_title = 'Zoho';

        add_action('metform_after_store_form_data', [$this, 'create_contact'], 10, 4);
        add_action('metform_settings_subtab_' . $this->parent_id, [$this, 'sub_tab']);
        add_action('metform_settings_subtab_content_' . $this->parent_id, [$this, 'sub_tab_content']);
    }

    public function sub_tab()
    {
        Render::sub_tab($this->sub_tab_title, $this->sub_tab_id);
    }

    public function contents()
    {
        $data = [
            'lable'       => 'API Authentication Token',
            'name'        => 'mf_zoho_token',
            'description' => '',
            'placeholder' => 'Enter Zoho API token here'
        ];

        Render::textbox($data);
    }

    public function sub_tab_content()
    {
        Render::sub_tab_content($this->sub_tab_id, [$this, 'contents']);
    }

    /**
     * @param $form_id
     * @param $form_data
     * @param $form_settings
     * @param $attributes
     * @return null
     */
    public function create_contact($form_id, $form_data, $form_settings, $attributes)
    {
        if (isset($form_settings['mf_zoho']) && $form_settings['mf_zoho'] == '1') {

            $first_name = (isset($form_data['mf-listing-fname']) ? $form_data['mf-listing-fname'] : '');
            $last_name  = (isset($form_data['mf-listing-lname']) ? $form_data['mf-listing-lname'] : '');
            $email      = (isset($form_data[$attributes['email_field_name']]) ? $form_data[$attributes['email_field_name']] : '');

            $settings_option = \MetForm\Core\Admin\Base::instance()->get_settings_option();

            $token = $settings_option['mf_zoho_token'];

            $url  = 'https://www.zohoapis.com/crm/v2/Contacts';
            $data = [
                'data' => [
                    [
                        'Last_Name'  => $first_name,
                        'First_Name' => $last_name,
                        'Email'      => $email
                    ]
                ]
            ];

            wp_remote_post($url, [
                'method'  => 'POST',
                'timeout' => 45,
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $token,
                    'Content-Type'  => 'application/json; charset=utf-8'
                ],
                'body'    => json_encode($data)
            ]);
        }
        return;
    }
}

Integration::instance()->init();
