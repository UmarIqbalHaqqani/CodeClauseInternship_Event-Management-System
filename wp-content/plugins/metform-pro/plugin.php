<?php

namespace MetForm_Pro;

use MetForm_Pro\Core\Integrations\Initiator;

defined('ABSPATH') || exit;

define( 'METFROM_PRO_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)) );

final class Plugin {

	const METFORM_PRO_PREFIX = 'metform-pro';

	private static $instance;

	private $entries;
	private $forms;
	private $failed;

	public function __construct() {
		Autoloader::run();
	}

	public function version() {
		return '3.1.4';
	}

	public function package_type() {
		return 'pro';
	}

	public function product_id() {
		return '200';
	}

	public function marketplace() {
		return 'wpmet';
	}

	public function author_name() {
		return 'wpmet';
	}

	public function account_url() {
		return 'https://account.wpmet.com';
	}

	public function api_url() {
		return 'https://api.wpmet.com/public/';
	}

	public function plugin_url() {
		return trailingslashit(plugin_dir_url(__FILE__));
	}

	public function plugin_dir() {
		return trailingslashit(plugin_dir_path(__FILE__));
	}

	public function core_url() {
		return $this->plugin_url() . 'core/';
	}

	public function core_dir() {
		return $this->plugin_dir() . 'core/';
	}

	public function base_url() {
		return $this->plugin_url() . 'base/';
	}

	public function base_dir() {
		return $this->plugin_dir() . 'base/';
	}

	public function utils_url() {
		return $this->plugin_url() . 'utils/';
	}

	public function utils_dir() {
		return $this->plugin_dir() . 'utils/';
	}

	public function widgets_url() {
		return $this->plugin_url() . 'widgets/';
	}

	public function widgets_dir() {
		return $this->plugin_dir() . 'widgets/';
	}

	public function public_url() {
		return $this->plugin_url() . 'public/';
	}

	public function public_dir() {
		return $this->plugin_dir() . 'public/';
	}

	public function i18n() {
		load_plugin_textdomain('metform-pro', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}

	public function init() {

		require $this->plugin_dir() . 'XPD_Constants.php';
		require $this->core_dir() . 'integrations/Initiator.php';

		add_action('init', [$this, 'i18n']);

		// check if metform installed and activated
		if(!did_action('metform/after_load')) {
			$this->missing_metform();
			$this->failed = true;
		}

		if($this->failed == true) {
			return;
		}


		Widgets\Manifest::instance()->init();
		Templates\Base::instance()->init();

		add_action('metform/onload/enqueue_scripts', [$this, 'js_css_public']);
		add_action('elementor/frontend/before_enqueue_scripts', [$this, 'elementor_js']);
		add_action('elementor/editor/before_enqueue_styles', [$this, 'elementor_css']);
		add_action('admin_enqueue_scripts', [$this, 'add_pro_integration_asset']);


		// Response Message Controls
		new Core\Integrations\Response_Message_Controls();

		// multistep form controls
		new Core\Integrations\Multistep_Form_Controls();

		// multistep
		new Core\Integrations\Multistep_Section_Settings();

		// payment action
		new Core\Integrations\Payment\Process();

		// license
		Core\Admin\Base::instance()->init();

		#Registering Aweber actions...

		Initiator::autoload();
		Initiator::initiate();

		$this->load_integrations();

		/*
		 * load integration
		 * added by AL EMRAN
		 * from version 1.3.3
		 */
		$integration = new \MetForm_Pro\Core\Integrations\Integrations();
		$integration->init();
	}


	public function add_pro_integration_asset(){

		$screen = get_current_screen();

		wp_enqueue_style('metform-pro-integration-style', $this->public_url() . 'assets/css/integration.css', false, $this->version());
		wp_enqueue_script('metform-pro-integration-js', $this->public_url() . 'assets/js/integration.js', ['jquery'], $this->version(), true);
		wp_localize_script('metform-pro-integration-js', 'metform_api', ['resturl' => get_rest_url(), 'admin_url' => get_admin_url()]);

		if ($screen->id == 'edit-metform-entry' || $screen->id == 'metform-entry') {
			wp_enqueue_script('metform-jspdf', $this->public_url() . 'assets/js/jspdf.min.js', array(), $this->version(), true);
			wp_enqueue_script('metform-pdf-export', $this->public_url() . 'assets/js/pdf-export.js', array(), $this->version(), true);
        }

	}


	public function missing_metform() {
		if(isset($_GET['activate'])) {
			unset($_GET['activate']);
		}

		if(file_exists(WP_PLUGIN_DIR . '/metform/metform.php')) {
			$btn['text'] = esc_html__('Activate MetForm', 'metform-pro');
			$btn['url'] = wp_nonce_url('plugins.php?action=activate&plugin=metform/metform.php&plugin_status=all&paged=1', 'activate-plugin_metform/metform.php');
		} else {
			$btn['text'] = esc_html__('Install MetForm', 'metform-pro');
			$btn['url'] = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=metform'), 'install-plugin_metform');
		}

		$message = sprintf(esc_html__('MetForm Pro required MetForm, which is currently NOT RUNNING. ', 'metform-pro'));
		\Oxaim\Libs\Notice::instance('metform-pro', 'unsupported-metform-pro-version')
		                  ->set_dismiss('global', (3600 * 24 * 15))
		                  ->set_message($message)
		                  ->set_button($btn)
		                  ->call();
	}


	public function js_css_public() {
		$is_edit_mode = 'metform-form' === get_post_type() && \Elementor\Plugin::$instance->editor->is_edit_mode();
		wp_enqueue_style('metform-pro-style', $this->public_url() . 'assets/css/style.min.css', false, $this->version());
	}

	public function elementor_js() {
		wp_enqueue_script('metform-pro-repeater', $this->public_url() . 'assets/js/repeater.js', ['elementor-frontend'], $this->version(), true);
	}

	public function elementor_css() {
		if('metform-form' == get_post_type()) {
			wp_enqueue_style('metform-pro-category-top', $this->public_url() . 'assets/css/category-top.css', false, $this->version());
		}
	}

	public static function instance() {
		if(!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 *
	 * ==============================
	 * Include the integrations files
	 * =============================
	 */

	public function load_integrations() {
		foreach(glob(plugin_dir_path(__FILE__) .
		             "core/integrations/*/*/loader.php")
		        as
		        $integrations) {
			require $integrations;
		}
		foreach(glob(plugin_dir_path(__FILE__) .
		             "core/features/*/loader.php")
		        as
		        $integrations) {
			require $integrations;
		}
	}
}
