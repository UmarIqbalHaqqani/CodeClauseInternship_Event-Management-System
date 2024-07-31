<?php

namespace Exhibx\Compatibility;

class Mig_Unyson_To_Codestar {

	const ARRAY_2D_STYLE = '2d_array';
	const ARRAY_1D_STYLE = '1d_array';
	const P_TYPE_CONDITIONAL = 'conditional';

	public function init() {

		$options           = get_option('theme_mods_exhibz', []);
		$fw_options_parent = empty($options['fw_options']) ? [] : $options['fw_options'];
		$child_options     = get_option('theme_mods_exhibz-child', []);
		$fw_options_child  = empty($child_options['fw_options']) ? [] : $child_options['fw_options'];
		$fw_options        = [];

		if(is_child_theme()) {
			// Child-theme is active, child-theme-options will be forwarded as the final options array
			$fw_options = $fw_options_child;
		} else {
			$array_keys = array_keys(array_merge($fw_options_parent, $fw_options_child));
			foreach($array_keys as $single_key) {
				if(array_key_exists($single_key, $fw_options_parent)) {
					$fw_options[$single_key] = $fw_options_parent[$single_key];
				} else {
					$fw_options[$single_key] = $fw_options_child[$single_key];
				}
			}
		}

		$modified   = [];
		$original   = [];
		$conf_array = $this->get_config_array();

		foreach($conf_array as $codestar_op_key => $unyson_array) {

			foreach($unyson_array as $ky => $conf) {

				$o_ky = $ky;
				$from = $conf['from'];
				$to   = $conf['to'];
				$ky   = empty($conf['k_map']) ? $ky : trim($conf['k_map']);

				if(empty($conf['p_type'])) {

					if(isset($fw_options[$o_ky])) {
						$modified[$ky]   = $this->fw_convert_mapper($from, $to, $fw_options[$o_ky], $conf);
						$original[$o_ky] = $fw_options[$o_ky];

					} elseif(isset($conf['def_val'])) {

						$modified[$ky]   = $conf['def_val'];
						$original[$o_ky] = $conf['def_val'];
					}

				} elseif($conf['p_type'] === self::P_TYPE_CONDITIONAL) {

					$loc_arr = explode('|', $conf['o_location']);

					$tmp = $fw_options;

					foreach($loc_arr as $item) {

						$tmp = isset($tmp[$item]) ? $tmp[$item] : [];
					}

					if(!empty($tmp)) {
						$modified[$ky]   = $this->fw_convert_mapper($from, $to, $tmp[$o_ky]);
						$original[$o_ky] = $tmp[$o_ky];

					} elseif(isset($conf['def_val'])) {

						$modified[$ky]   = $conf['def_val'];
						$original[$o_ky] = $conf['def_val'];
					}

				} elseif($conf['p_type'] === self::ARRAY_1D_STYLE || $conf['p_type'] === self::ARRAY_2D_STYLE) {

					if(isset($fw_options[$o_ky])) {
						$modified[$ky]   = $this->fw_convert_mapper($from, $to, $fw_options[$o_ky], $conf);
						$original[$o_ky] = $fw_options[$o_ky];

					} elseif(isset($conf['def_val'])) {

						$modified[$ky]   = $conf['def_val'];
						$original[$o_ky] = $conf['def_val'];
					} else {
						$modified[$ky]   = [];
						$original[$o_ky] = [];
					}

				} elseif($conf['p_type'] === 'popup_2d_array') {

					$modified[$ky] = [];

					if(!empty($conf['fields_map'])) {

						$tmp_mod = [];

						foreach($conf['fields_map'] as $fld_key => $fld_conf) {

							$old_ky  = $fld_key;
							$t_from  = $fld_conf['from'];
							$t_to    = $fld_conf['to'];
							$fld_key = empty($fld_conf['k_map']) ? $fld_key : trim($fld_conf['k_map']);

							if(empty($fw_options[$o_ky])) {

								$tmp_mod[$fld_key] = isset($fld_conf['def_val']) ? $fld_conf['def_val'] : '';

							} else {

								$tmp_mod[$fld_key] = $this->fw_convert_mapper($t_from, $t_to, $fw_options[$o_ky][$old_ky], $fld_conf);
							}

						}

						$modified[$ky]   = $tmp_mod;
						$original[$o_ky] = isset($fw_options[$o_ky]) ? $fw_options[$o_ky] : [];
					}

				}

			}

			// $this->debug($original, $modified);
			$this->persist_codestar_settings($codestar_op_key, $modified);

			$modified = [];
			$original = [];
		}

		$this->migrate_cpt();
		$this->migrate_taxonomy();

	}

	protected function get_config_array() {

		//codestar_slug_id_prefix => which fields goes here...

		$config_array = [

			// Customizer settings
			'exhibz_section_theme_settings' => array_merge(
				$this->general_settings_config(),
				$this->style_settings_config(),
				$this->header_settings_config(),
				$this->banner_settings_config(),
				$this->blog_settings_config(),
				$this->footer_settings_config(),
				$this->optimization_settings_config(),
			),
		];

		return $config_array;
	}

	protected function general_settings_config() {

		return [
			'preloader_show'          => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'def_val' => false,
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
			],
			'preloader_logo'          => [
				'from' => 'upload',
				'to'   => 'media',
			],
			'general_text_logo'       => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'def_val' => false,
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
			],
			'general_text_logo_title' => [
				'from'       => 'text',
				'to'         => 'text',
				'def_val'    => 'Blog',
				'p_type'     => self::P_TYPE_CONDITIONAL,
				'o_location' => 'general_text_logo_settings|yes',
				// Muiltipicker key from Unyson setting. This is not dependent with Codestar Freamwork
			],
			'general_main_logo'       => [
				'from' => 'upload',
				'to'   => 'media',
			],
			'general_dark_logo'       => [
				'from' => 'upload',
				'to'   => 'media',
			],
			'general_social_links'    => [
				'from'   => 'addable-popup',
				'to'     => 'group',
				'p_type' => self::ARRAY_2D_STYLE,
				'o_map'  => [
					'title'      => 'title',
					'icon_class' => 'icon_class',
					'url'        => 'url',
				],
			],
			'attendee_show'       => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'def_val' => true,
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
			],
			'attendee_length' => [
				'from'       => 'text',
				'to'         => 'text',
				'def_val'    => '6',
				'p_type'     => self::P_TYPE_CONDITIONAL,
				'o_location' => 'attendee_show|yes',
				// Muiltipicker key from Unyson setting. This is not dependent with Codestar Freamwork
			],
		];
	}

	protected function style_settings_config() {

		return [
			'body_bg_img'           => [
				'from' => 'upload',
				'to'   => 'media'
			],
			'style_body_bg'         => [
				'from' => 'color-picker',
				'to'   => 'color',
			],
			'style_body_text_color' => [
				'from' => 'color-picker',
				'to'   => 'color',
			],
			'style_primary'         => [
				'from' => 'color-picker',
				'to'   => 'color',
			],
			'secondary_color'       => [
				'from' => 'color-picker',
				'to'   => 'color',
			],
			'title_color'           => [
				'from' => 'color-picker',
				'to'   => 'color',
			],
			'body_font'             => [
				'from'    => 'typography-v2',
				'to'      => 'typography',
				'p_type'  => self::ARRAY_1D_STYLE,
				'o_map'   => $this->get_typography_v2_map(),
				'f_map'   => [
					'unit' => 'px',
					'type' => 'google',
				],
				'def_val' => [
					'family' => 'Roboto',
					'size'   => '16',
					'unit'   => 'px',
					'type'   => 'google',
				],
			],
			'heading_font_one'      => [
				'from'    => 'typography-v2',
				'to'      => 'typography',
				'p_type'  => self::ARRAY_1D_STYLE,
				'o_map'   => $this->get_typography_v2_map(),
				'f_map'   => [
					'unit' => 'px',
					'type' => 'google',
				],
				'def_val' => [
					'family'      => 'Raleway',
					'font-weight' => '700',
					'unit'        => 'px',
					'type'        => 'google',
				],
			],
			'heading_font_two'      => [
				'from'    => 'typography-v2',
				'to'      => 'typography',
				'p_type'  => self::ARRAY_1D_STYLE,
				'o_map'   => $this->get_typography_v2_map(),
				'f_map'   => [
					'unit' => 'px',
					'type' => 'google',
				],
				'def_val' => [
					'family'      => 'Raleway',
					'font-weight' => '700',
					'unit'        => 'px',
					'type'        => 'google',
				],
			],
			'heading_font_three'    => [
				'from'    => 'typography-v2',
				'to'      => 'typography',
				'p_type'  => self::ARRAY_1D_STYLE,
				'o_map'   => $this->get_typography_v2_map(),
				'f_map'   => [
					'unit' => 'px',
					'type' => 'google',
				],
				'def_val' => [
					'family'      => 'Roboto',
					'font-weight' => '700',
					'unit'        => 'px',
					'type'        => 'google',
				],
			],

		];
	}

	protected function get_typography_v2_map() {

		return [
			'family'         => 'font-family',
			'font-weight'    => 'font-weight',
			'variation'      => 'font-style',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'size'           => 'font-size',
			'color'          => 'color',
		];
	}

	protected function header_settings_config() {

		return [
			'header_layout_style'        => [
				'from'    => 'image-picker',
				'to'      => 'image_select',
				'def_val' => 'standard'
			],
			'header_nav_sticky_section'  => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'def_val' => false,
				'v_map'   => [
					''    => '',
					'yes' => 1,
					'no'  => 0,
				],
			],
			'header_nav_sticky_bg'       => [
				'from'       => 'color-picker',
				'to'         => 'color',
				'p_type'     => self::P_TYPE_CONDITIONAL,
				'def_val'    => "#1a1831",
				'o_location' => 'header_nav_sticky_section|yes'
			],
			'header_nav_search'          => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'def_val' => false,
				'v_map'   => [
					''    => '',
					'yes' => 1,
					'no'  => 0,
				]
			],
			'header_cta_button_settings' => [
				'from'       => 'popup',
				'to'         => 'accordion',
				'p_type'     => 'popup_2d_array',
				'fields_map' => [
					'header_btn_show'     => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'def_val' => false,
						'v_map'   => [
							''    => '',
							'yes' => 1,
							'no'  => 0,
						]
					],
					'header_btn_title'    => [
						'from' => 'text',
						'to'   => 'text',
					],
					'header_btn_bg_color' => [
						'from'    => 'color-picker',
						'to'      => 'color'
					],
					'header_btn_url'      => [
						'from'    => 'text',
						'to'      => 'text',
						'def_val' => '#',
					],
				],
			],

			'header_nav_shopping_cart_section' => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'def_val' => true,
				'v_map'   => [
					''    => '',
					'yes' => 1,
					'no'  => 0,
				]
			],
			'header_top_settings'              => [
				'from'       => 'popup',
				'to'         => 'accordion',
				'p_type'     => 'popup_2d_array',
				'fields_map' => [
					'header_contact_show'      => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'def_val' => false,
						'v_map'   => [
							''    => '',
							'yes' => 1,
							'no'  => 0,
						]
					],
					'header_contact_number'    => [
						'from' => 'text',
						'to'   => 'text',
					],
					'header_contact_icon'      => [
						'from' => 'new-icon',
						'to'   => 'icon',
					],
					'header_contact_mail_icon' => [
						'from' => 'new-icon',
						// Need to check with Reza Vai
						'to'   => 'icon',
					],
					'header_contact_mail'      => [
						'from' => 'text',
						'to'   => 'text',
					],
				],
			],
		];
	}

	protected function banner_settings_config() {
		return [
			'banner_title_color'   => [
				'from' => 'color-picker',
				'to'   => 'color',
			],
			'page_banner_setting'  => [
				'from'       => 'popup',
				'to'         => 'accordion',
				'p_type'     => 'popup_2d_array',
				'fields_map' => [
					'page_show_banner'     => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'yes' => 1,
						],
						'def_val' => true,
					],
					'page_show_breadcrumb' => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'yes' => 1,
						],
						'def_val' => false,
					],
					'banner_page_title'    => [
						'from'    => 'text',
						'to'      => 'text',
						'def_val' => ''
					],
					'banner_page_image'    => [
						'from' => 'upload',
						'to'   => 'media',
					],
				],
			],
			'blog_banner_setting'  => [
				'from'       => 'popup',
				'to'         => 'accordion',
				'p_type'     => 'popup_2d_array',
				'fields_map' => [
					'blog_show_banner'     => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'yes' => 1,
						],
						'def_val' => true,
					],
					'blog_show_breadcrumb' => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'yes' => 1,
						],
						'def_val' => false,
					],
					'banner_blog_title'    => [
						'from'    => 'text',
						'to'      => 'text',
						'def_val' => ''
					],
					'banner_blog_image'    => [
						'from' => 'upload',
						'to'   => 'media',
					],
				],
			],
			'shop_banner_settings' => [
				'from'       => 'popup',
				'to'         => 'accordion',
				'p_type'     => 'popup_2d_array',
				'fields_map' => [
					'show'            => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'yes' => 1,
						],
						'def_val' => true,
					],
					'show_breadcrumb' => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'yes' => 1,
						],
						'def_val' => true,
					],
					'title'           => [
						'from'    => 'text',
						'to'      => 'text',
						'def_val' => ''
					],
					'single_title'           => [
						'from'    => 'text',
						'to'      => 'text',
						'def_val' => ''
					],
					'image'           => [
						'from' => 'upload',
						'to'   => 'media',
					],
				],
			]
		];
	}

	protected function blog_settings_config() {

		return [
			'blog_sidebar'                 => [
				'from'  => 'select',
				'to'    => 'select',
				'v_map' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
				]
			],
			'blog_single_sidebar'          => [
				'from'  => 'select',
				'to'    => 'select',
				'v_map' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
				]
			],
			'blog_author'                  => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'single_speaker_schedule_sort' => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],

		];
	}

	protected function footer_settings_config() {

		return [
			'footer_style'           => [
				'from'       => 'multi-picker',
				'to'         => 'image_select',
				'picker_key' => 'style',
				'v_map'      => [
					'style-1' => 'style-1',
					'style-2' => 'style-2',
					'style-3' => 'style-3',
				],
				'def_val'    => 'style-1'
			],
			'footer_mailchimp'       => [
				'from'       => 'text',
				'to'         => 'text',
				'p_type'     => self::P_TYPE_CONDITIONAL,
				'o_location' => 'footer_style|style-2'
			],
			'footer_top_show_hide'   => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'footer_bg_img'          => [
				'from' => 'upload',
				'to'   => 'media',
			],
			'footer_bg_color'        => [
				'from' => 'color-picker',
				'to'   => 'color',
			],
			'footer_copyright_color' => [
				'from' => 'color-picker',
				'to'   => 'color',
			],
			'footer_social_links'    => [
				'from'   => 'addable-popup',
				'to'     => 'group',
				'p_type' => self::ARRAY_2D_STYLE,
				'o_map'  => [
					'title'      => 'title',
					'icon_class' => 'icon_class',
					'url'        => 'url',
				],
			],
			'footer_copyright'       => [
				'from'    => 'textarea',
				'to'      => 'textarea',
				'def_val' => '&copy; 2022 Exhibz. All rights reserved',
			],
			'footer_padding_top'     => [
				'from'    => 'text',
				'to'      => 'text',
				'def_val' => '20px',
			],
			'back_to_top'            => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => false,
			],
		];
	}

	protected function optimization_settings_config() {
		return [
			'optimization_blocklibrary_enable'     => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'optimization_fontawesome_enable'      => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'optimization_elementoricons_enable'   => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'optimization_elementkitsicons_enable' => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'optimization_socialicons_enable'      => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'optimization_dashicons_enable'        => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'optimization_meta_viewport'           => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
			'optimization_eventin_enable'          => [
				'from'    => 'switch',
				'to'      => 'switcher',
				'v_map'   => [
					''    => '',
					'no'  => 0,
					'yes' => 1,
				],
				'def_val' => true,
			],
		];
	}

	public function fw_convert_mapper($from, $to, $orig_val, $extra = []) {

		if($from === 'upload' && $to === 'media') {

			return $this->fw_convert_upload_to_media($orig_val);
		}

		if($from === 'image-picker' && $to === 'image_select') {

			return $this->fw_convert_img_picker_to_img_select($orig_val);
		}

		if($from === 'switch' && $to === 'switcher') {

			return $this->fw_convert_switch_to_switcher($orig_val, $extra);
		}

		if($from === 'switch' && $to === 'button_set') {

			return $this->fw_convert_switch_to_switcher($orig_val, $extra);
		}

		if($from === 'text' && $to === 'text') {

			return $this->fw_convert_no_change_to_no_change($orig_val, $extra);
		}

		if($from === 'wp-editor' && $to === 'wp_editor') {

			return $this->fw_convert_no_change_to_no_change($orig_val, $extra);
		}

		if($from === 'textarea' && $to === 'textarea') {

			return $this->fw_convert_no_change_to_no_change($orig_val, $extra);
		}

		if($from === 'select' && $to === 'select') {

			return $this->fw_convert_no_change_to_no_change($orig_val, $extra);
		}

		if($from === 'color-picker' && $to === 'color') {

			return $this->fw_convert_no_change_to_no_change($orig_val, $extra);
		}

		if($from === 'multi-picker' && $to === 'switcher') {

			return $this->fw_convert_mlt_picker_to_switcher($orig_val, $extra);
		}

		if($from === 'multi-picker' && $to === 'image_select') {

			return $this->fw_convert_mlt_picker_to_img_select($orig_val, $extra);
		}

		if($from === 'addable-popup' && $to === 'group') {

			return $this->fw_convert_addable_popup_to_group($orig_val, $extra);
		}

		if($from === 'typography-v2' && $to === 'typography') {

			return $this->fw_convert_typ_v2_to_typography($orig_val, $extra);
		}

		return $orig_val;
	}

	public function fw_convert_upload_to_media($input) {

		if(empty($input['attachment_id'])) {
			return;
		}

		$atc = wp_get_attachment_metadata($input['attachment_id']);

		return [
			'id'          => $input['attachment_id'],
			'url'         => $input['url'],
			'width'       => $atc['width'],
			'height'      => $atc['height'],
			'thumbnail'   => $input['url'],
			'alt'         => '',
			'title'       => '',
			'description' => '',
		];
	}

	public function fw_convert_img_picker_to_img_select($input) {

		// No need to do anything :D

		return $input;
	}

	public function fw_convert_switch_to_switcher($input, $conf) {
		/**
		 * Here we are processing 0d array
		 */

		if(empty($conf['v_map'])) {

			return $input;
		}

		$incoming = is_null($input) ? '' : $input;

		//todo need to test it with more values, specially with empty value

		return isset($conf['v_map'][$incoming]) ? $conf['v_map'][$incoming] : $input;
	}

	public function fw_convert_no_change_to_no_change($input, $extra) {

		// We do not need to do anything :D

		return $input;
	}

	public function fw_convert_mlt_picker_to_switcher($input, $conf = []) {

		$fk = $this->get_multi_picker_key($conf, $input);

		return $this->convert_multi_picker_array($fk, $input, $conf);
	}

	private function get_multi_picker_key($conf, $input) {

		/**
		 * According to ataur bhai the key os the first one's value
		 */

		if(empty($conf['picker_key'])) {

			return key($input);
		}

		return $conf['picker_key'];
	}

	private function convert_multi_picker_array($fk, $input, $conf = []) {

		$vl = $input[$fk];

		if(!empty($conf['v_map'])) {

			$incoming = is_null($vl) ? '' : $vl;

			return isset($conf['v_map'][$incoming]) ? $conf['v_map'][$incoming] : $vl;
		}

		if($vl === 'yes') {

			return 1;
		}

		return '';
	}

	public function fw_convert_mlt_picker_to_img_select($input, $conf = []) {

		return $this->fw_convert_mlt_picker_to_switcher($input, $conf);
	}

	public function fw_convert_addable_popup_to_group($input, $conf = []) {

		if(empty($input)) {

			return [];
		}

		$converted = [];

		foreach($input as $item) {

			if(empty($item)) {
				$converted[] = [];
			}

			if(empty($conf['o_map'])) {

				$converted[] = $item;

			} else {

				$tmp = [];

				foreach($conf['o_map'] as $oky => $nky) {

					$tmp[$nky] = isset($item[$oky]) ? $item[$oky] : '';
				}

				$converted[] = $tmp;
			}

		}

		return $converted;
	}

	public function fw_convert_typ_v2_to_typography($input, $conf) {

		/**
		 * Here we are processing 1d array value
		 */

		$converted = [];

		if(!empty($conf['f_map'])) {

			foreach($conf['f_map'] as $fk => $fv) {

				$converted[$fk] = $fv;
			}

		}

		if(empty($input)) {

			return $converted;
		}

		if(!empty($conf['o_map'])) {

			foreach($conf['o_map'] as $ok => $nk) {
				$converted[$nk] = isset($input[$ok]) ? $input[$ok] : '';
			}

		}

		return $converted;
	}

	private function persist_codestar_settings($key, $val) {

		return update_option($key, $val);
	}

	// Post Meta Settings
	protected function migrate_cpt() {

		$cpt = [
			// Page Meta
			'page'        => [
				'map' => [
					'header_title'             => [
						'from' => 'text',
						'to'   => 'text',
					],
					'header_image'             => [
						'from' => 'upload',
						'to'   => 'media',
					],
					'page_header_override'     => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'def_val' => false,
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'yes' => 1,
						],
					],
					'page_header_layout_style' => [
						'from'    => 'image-picker',
						'to'      => 'image_select',
						'v_map'   => [
							'transparent'  => 'transparent',
							'standard'     => 'standard',
							'transparent2' => 'transparent2',
							'transparent3' => 'transparent3',
							'classic'      => 'classic',
							'center'       => 'center',
							'fullwidth'    => 'fullwidth'
						],
						'def_val' => 'standard',
					],
				],
				'page_sections' => [
					'xs_page_section'          => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'def_val' => false,
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'on'  => 1,
						],
					],
					'hide_title_from_menu'     => [
						'from'    => 'switch',
						'to'      => 'switcher',
						'def_val' => false,
						'v_map'   => [
							''    => '',
							'no'  => 0,
							'yes' => 1,
						],
					],
				],
			],
			// ts-schedule Meta
			'ts-schedule' => [
				'map' => [
					'schedule_day'           => [
						'from' => 'text',
						'to'   => 'text',
					],
					'exhibz_schedule_pop_up' => [
						'from'   => 'addable-popup',
						'to'     => 'group',
						'p_type' => self::ARRAY_2D_STYLE,
						'o_map'  => [
							'schedule_time'  => 'schedule_time',
							'speakers'       => 'speakers',
							'style'          => 'style',
							'multi_speakers' => 'multi_speakers',
							'schedule_title' => 'schedule_title',
							'schedule_note'  => 'schedule_note',
						],
					],					
				],
				'exhibz_schedule_pop_up' => [
					'from'   => 'addable-popup',
					'to'     => 'group',
					'p_type' => self::ARRAY_2D_STYLE,
					'o_map'  => [
						'schedule_time'  => 'schedule_time',
						'speakers'       => 'speakers',
						'style'          => 'style',
						'multi_speakers' => 'multi_speakers',
						'schedule_title' => 'schedule_title',
						'schedule_note'  => 'schedule_note',
					],
				],
			],
			'ts-speaker'  => [
				'map' => [
					'exhibs_designation' => [
						'from' => 'text',
						'to'   => 'text',
					],
					'exhibs_photo'       => [
						'from' => 'upload',
						'to'   => 'media',
					],
					'exhibs_logo'        => [
						'from' => 'upload',
						'to'   => 'media',
					],
					'exhibs_summery'     => [
						'from' => 'wp-editor',
						'to'   => 'wp_editor',
					],
					'social'             => [
						'from'   => 'addable-popup',
						'to'     => 'group',
						'p_type' => self::ARRAY_2D_STYLE,
						'o_map'  => [
							'option_site_name' => 'option_site_name',
							'option_site_link' => 'option_site_link',
							'option_site_icon' => 'option_site_icon'
						],
					],
					'speaker_color'      => [
						'from' => 'color-picker',
						'to'   => 'color',
					],
				],
			],
			'etn'         => [
				'map' => [
					'event_banner_image' => [
						'from' => 'upload',
						'to'   => 'media',
					],
				],
			],
			'etn-speaker' => [
				'map' => [
					'speaker_image_overlay_color' => [
						'from' => 'color-picker',
						'to'   => 'color',
					],
					'speaker_image_blend_mode'    => [
						'from'    => 'select',
						'to'      => 'select',
						'def_val' => 'darken',
						'v_map'   => [
							'darken'   => 'darken',
							'multiply' => 'multiply',
							'3'        => '3',
						]
					],
				],
			],
		];

		$log = [];

		foreach($cpt as $cpt_name => $cpt_conf) {

			//$resume_key = 'mig_resume_'.$cpt_name;

			$posts = get_posts(
				[
					'fields'      => 'ids',
					'post_type'   => $cpt_name,
					'post_status' => 'publish',
					'numberposts' => -1,
				]
			);

			foreach($posts as $pid) {

				$log[$pid] = $this->convert_meta_key_value($pid, $cpt_conf);
			}

		}
		// $this->debug($log, []);
	}

	// Blog Settings
	protected function convert_meta_key_value($pid, $cpt_conf) {

		$fw_meta = get_post_meta($pid, 'fw_options', true);
		$res     = [];

		foreach($cpt_conf['map'] as $mk => $conf) {

			if(!isset($fw_meta[$mk])) {
				continue;
			}

			$o_ky = $mk;
			$from = $conf['from'];
			$to   = $conf['to'];
			$ky   = empty($conf['k_map']) ? $mk : trim($conf['k_map']);

			if(empty($fw_meta[$mk])) {

				$vl = is_array($fw_meta[$mk]) ? [] : '';

				update_post_meta($pid, $ky, $vl);

				$res['mod'][$ky]  = '';
				$res['orig'][$ky] = '';

				continue;
			}

			if(empty($conf['p_type'])) {

				$res['mod'][$ky]    = $this->fw_convert_mapper($from, $to, $fw_meta[$o_ky], $conf);
				$res['orig'][$o_ky] = $fw_meta[$o_ky];

				update_post_meta($pid, $mk, $res['mod'][$ky]);

			} elseif($conf['p_type'] === self::ARRAY_1D_STYLE || $conf['p_type'] === self::ARRAY_2D_STYLE) {

				$res['mod'][$ky]    = $this->fw_convert_mapper($from, $to, $fw_meta[$o_ky], $conf);
				$res['orig'][$o_ky] = $fw_meta[$o_ky];

				update_post_meta($pid, $ky, $res['mod'][$ky]);
			}

		}

		if (array_key_exists('page_sections', $cpt_conf)) {
			
			foreach($cpt_conf['page_sections'] as $mk => $conf) {

				if(!isset($fw_meta['page_sections'])) {
					continue;
				}

				if(!empty($fw_meta['page_sections'])) {

					if($mk == 'xs_page_section'){
						$value = $fw_meta['page_sections'][$mk];
					} elseif($mk == 'hide_title_from_menu'){						
						$value = $fw_meta['page_sections']['on'][$mk];
					} else {
						$value = '';
					}

					update_post_meta($pid, $mk, $value);

				}

			}
		}

		if (array_key_exists('exhibz_schedule_pop_up', $cpt_conf)) {			
			
			$i = 0;
			foreach($cpt_conf['exhibz_schedule_pop_up'] as $mk => $conf) {

				if(!isset($fw_meta['exhibz_schedule_pop_up'])) {
					continue;
				}

				if(!empty($fw_meta['exhibz_schedule_pop_up'])) {

					$new_data = $fw_meta['exhibz_schedule_pop_up'][$i]['multi_speaker_choose'];
					$multi_speakers = [];

					if( $new_data['style'] == 'yes' ) {
						$multi_speakers = $new_data['yes']['multi_speakers'];
					}

					$formattedData[$i] = [
						'schedule_time' 	=> $fw_meta['exhibz_schedule_pop_up'][$i]['schedule_time'],
						'speakers' 			=> $fw_meta['exhibz_schedule_pop_up'][$i]['speakers'],
						'style' 			=> $new_data['style'],
						'multi_speakers' 	=> $multi_speakers,
						'schedule_title' 	=> $fw_meta['exhibz_schedule_pop_up'][$i]['schedule_title'],
						'schedule_note' 	=> $fw_meta['exhibz_schedule_pop_up'][$i]['schedule_note']
					];

					update_post_meta($pid, 'exhibz_schedule_pop_up', $formattedData);

				}

				$i++;

			}
		}

		return $res;
	}

	// Taxonomy Settings
	protected function migrate_taxonomy() {

		$taxonomy = [
			'etn_category'   => [
				'map'  => [
					'event_category_featured_img' => [
						'from' => 'upload',
						'to'   => 'media',
					],
				],
				'mkey' => 'exhibz-etn-category',
			],
			'event_location' => [
				'map'  => [
					'featured_upload_img' => [
						'from' => 'upload',
						'to'   => 'media',
					],
				],
				'mkey' => 'exhibz-etn-location',
			],
			'ts-speaker_cat' => [
				'map'  => [
					'exhibz_speaker_count' => [
						'from' => 'text',
						'to'   => 'text',
					],
				],
				'mkey' => 'ts-speaker-cat',
			],
		];

		$log = [];

		foreach($taxonomy as $taxonomy_name => $taxonomy_conf) {
			$terms = get_terms(
				[
					'taxonomy'   => $taxonomy_name,
					'hide_empty' => false,
				]
			);

			foreach($terms as $term) {
				$term_id       = $term->term_id;
				$log[$term_id] = $this->convert_tax_meta_key_value($term_id, $taxonomy_conf['mkey'], $taxonomy_conf);
			}

		}
		// $this->debug($log, []);
	}

	// handle conditionals
	protected function convert_tax_meta_key_value($term_id, $term_meta_key, $taxonomy_conf) {


		$fw_meta                = get_term_meta($term_id, 'fw_options', true);   // current term meta array saved in wp_termmeta table
		$res                    = [];
		$final_meta_value_array = [];

		foreach($taxonomy_conf['map'] as $mk => $conf) {

			if(!isset($fw_meta[$mk]) && !isset($conf['p_type'])) {
				continue;
			}

			$o_ky = $mk;            // block_featured_post_layout
			$from = $conf['from'];  // image-picker
			$to   = $conf['to'];    // image_select
			$ky   = empty($conf['k_map']) ? $mk : trim($conf['k_map']); //block_featured_post_layout

			if(empty($fw_meta[$mk]) && empty($conf['p_type'])) {
				$vl                          = is_array($fw_meta[$mk]) ? [] : '';
				$final_meta_value_array[$ky] = $vl;
				$res['mod'][$ky]             = '';
				$res['orig'][$ky]            = '';
				continue;
			}


			if(empty($conf['p_type'])) {
				$res['mod'][$ky]             = $this->fw_convert_mapper($from, $to, $fw_meta[$o_ky], $conf);
				$res['orig'][$o_ky]          = $fw_meta[$o_ky];
				$final_meta_value_array[$mk] = $res['mod'][$ky];

			} elseif($conf['p_type'] === self::ARRAY_1D_STYLE || $conf['p_type'] === self::ARRAY_2D_STYLE) {

				$res['mod'][$ky]             = $this->fw_convert_mapper($from, $to, $fw_meta[$o_ky], $conf);
				$res['orig'][$o_ky]          = $fw_meta[$o_ky];
				$final_meta_value_array[$ky] = $res['mod'][$ky];

			} elseif($conf['p_type'] === self::P_TYPE_CONDITIONAL) {
				$o_val           = isset($fw_meta[$o_ky]) ? $fw_meta[$o_ky] : ''; // ''
				$con_input_array = $this->fw_handle_conditional_tax($from, $to, $o_ky, $o_val, $conf, $taxonomy_conf['map'], $fw_meta);


				if(is_array($con_input_array) && !empty($con_input_array)) {
					foreach($con_input_array as $key => $value) {
						$res['mod'][$key]             = $value;
						$final_meta_value_array[$key] = $value;
					}
				}
			}

		}

		update_term_meta($term_id, $term_meta_key, $final_meta_value_array);

		return $res;
	}

	// Footer Settings

	protected function fw_handle_conditional_tax(
		$from, // image-picker
		$to, // image_select
		$unyson_key, // block_featured_post_layout
		$unyson_val = '',       // ''
		$config_array = [],     //'from' => 'image-picker',
		$taxonomy_conf = [],    // the main array containing all key
		$fw_meta = []           // see unserialize.me
	) {

		$array = [];

		if(!isset($config_array['o_location'])) {
			return $array;
		}

		$conditional_hierarchy_arr = explode('|', $config_array['o_location']);
		if(count($conditional_hierarchy_arr) < 3) {
			return $array;
		}
		array_pop($conditional_hierarchy_arr);


		$root_parent_key = $conditional_hierarchy_arr[0];
		$last_parent_key = $conditional_hierarchy_arr[1];

		if(!isset($fw_meta[$root_parent_key])) {
			return $array;
		}

		$root_parent_vals = $fw_meta[$root_parent_key];

		if(!is_array($root_parent_vals) || empty($root_parent_vals)) {
			return $array;
		}

		$last_parent_o_val = $root_parent_vals[$last_parent_key];  //  yes
		$last_parent_from  = $taxonomy_conf[$last_parent_key]['from'];
		$last_parent_to    = $taxonomy_conf[$last_parent_key]['to'];
		$last_parent_n_val = $this->fw_convert_mapper($last_parent_from, $last_parent_to, $last_parent_o_val, $taxonomy_conf[$last_parent_key]);

		$current_parent_o_val = !empty($root_parent_vals[$last_parent_o_val][$unyson_key]) ? $root_parent_vals[$last_parent_o_val][$unyson_key] : '';
		$current_parent_n_val = $this->fw_convert_mapper($from, $to, $current_parent_o_val, $config_array);

		$array[$last_parent_key] = $last_parent_n_val;
		$array[$unyson_key]      = $current_parent_n_val;

		return $array;
	}

	private function debug($orig, $mod) {

		echo '<br/>===========================================================================<br/>';
		echo '<pre>';
		print_r($orig);
		echo '<br/>---------------------------------------------------------------<br/>';
		print_r($mod);
		echo '</pre>';
	}

}
