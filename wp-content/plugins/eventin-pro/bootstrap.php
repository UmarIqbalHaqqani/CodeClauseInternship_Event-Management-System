<?php

namespace Etn_Pro;

defined('ABSPATH') || exit;

use Etn\Utils\Helper;
use Etn_Pro\Core\Action;
use Etn_Pro\Core\Woocommerce\Woocommerce_Deposit\Woocommerce_Deposit;
use Etn_Pro\Utils\Plugin_Installer;
use Wpeventin_Pro;

final class Bootstrap
{

    private static $instance;
    private $failed;

    public function __construct()
    {
        // Autoloader::run();
    }

    public function package_type()
    {
        return 'pro';
    }

    public function product_id()
    {
        return '1013';
    }

    public function store_url()
    {
        return 'https://themewinter.com';
    }

    public function marketplace()
    {
        return 'themewinter';
    }

    public function author_name()
    {
        return 'themewinter';
    }

    public function account_url()
    {
        return 'https://account.themewinter.com';
    }

    public function api_url()
    {
        return 'https://api.themewinter.com/public/';
    }

    public static function instance()
    {

        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Main function
     *
     * @return void
     */
    public function init()
    {

        //make eventin free ready.
        // $this->prepare_eventin();

        // check if eventin installed and activated
        if (!did_action('eventin/after_load')) {
            $this->failed = true;
        }

        if ($this->failed == true) {
            return;
        }

        //initialize license if only multisite is enabled and current site is main network site
        if ((!is_multisite()) || (is_multisite() && is_main_network() && is_main_site() && defined('MULTISITE'))) {
            $this->initialize_license_module();
        }

        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        // fire up elementor widgets
        Widgets\Manifest::instance()->init();

        add_action('admin_enqueue_scripts', [$this, 'js_css_admin']);
        add_action('wp_enqueue_scripts', [$this, 'js_css_public']);
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'etn_elementor_js']);
        // advanced search filter
        add_action('etn_advanced_search', '\Etn_Pro\Utils\Helper::advanced_search_filter');

        //fire-up all woocommerce related hooks
        if (file_exists(ETN_PRO_DIR . '/core/woocommerce/hooks.php')) {
            include_once ETN_PRO_DIR . '/core/woocommerce/hooks.php';
        }

        // load sells engin.
        \Etn_Pro\Core\Modules\Sells_Engine\Sells_Engine::instance()->init();

        //  fire up all actions.
        \Etn_Pro\Core\Event\Event::instance()->init();

        // call shortcode hooks.
        \Etn_Pro\Core\Shortcodes\Hooks::instance()->init();

        // call speaker hooks.
        \Etn_Pro\Core\Metaboxs\Speaker_meta::instance()->init();

        // call event-metabox hooks.
        \Etn_Pro\Core\Metaboxs\Event_meta::instance()->init();

        // call event single-page view hook.
        \Etn_Pro\Core\Event\Single_Page_View::instance()->init();

        // Initialize external script.
        \Etn_Pro\Core\Event\Script_Generator::instance()->init();

        // Webhook.
        \Etn_Pro\Core\Webhook\Webhook_Admin::instance()->init();
        \Etn_Pro\Core\Webhook\Hooks::instance()->init();

        // active modules.
        \Etn_Pro\Base\Config::instance()->init();

        if (file_exists(ETN_PRO_DIR . "/core/speaker/views/template-hooks.php")) {
            include_once ETN_PRO_DIR . "/core/speaker/views/template-hooks.php";
        }

        if (file_exists(ETN_PRO_DIR . "/core/speaker/views/template-functions.php")) {
            include_once ETN_PRO_DIR . "/core/speaker/views/template-functions.php";
        }

        Action::instance()->init();

        if (class_exists('WC_Deposits')) {
            Woocommerce_Deposit::instance()->init();
        }

        \Etn_Pro\Core\Attendee\Hooks::instance()->init();

        // fire up all migrations.
        \Etn_Pro\Core\Migration\Migration::instance()->init();

        // call ajax submit.
        if (defined('DOING_AJAX') && DOING_AJAX) {
            // All ajax action.
            \Etn_Pro\Widgets\Event_Locations\Actions\Ajax_Action::instance()->init();
        }

        if ( class_exists( 'Wpeventin' ) ) {
            $this->require_files();
            // Google Auth.
            new \EventinPro\Integrations\Google\Auth();
            new \EventinPro\Admin\Hooks();
        }
    }

    public function require_files() {
       require_once Wpeventin_Pro::core_dir() . '/Integrations/Google/GoogleClient.php';
       require_once Wpeventin_Pro::core_dir() . '/Integrations/Google/GoogleCredential.php';
       require_once Wpeventin_Pro::core_dir() . '/Integrations/Google/GoogleToken.php';
       require_once Wpeventin_Pro::core_dir() . '/Integrations/Google/Auth.php';
       require_once Wpeventin_Pro::core_dir() . '/Integrations/Google/Services/Service.php';
       require_once Wpeventin_Pro::core_dir() . '/Integrations/Google/Services/Calender.php';
       require_once Wpeventin_Pro::core_dir() . '/Integrations/Google/GoogleMeet.php';
       require_once Wpeventin_Pro::core_dir() . '/Admin/Hooks.php';
       require_once Wpeventin_Pro::core_dir() . '/event/Api/EventController.php';
       
    }

    public function initialize_license_module()
    {

        if (current_user_can('manage_etn_settings') && current_user_can('manage_options')) {

            //handle license notice
            $this->manage_license_notice();

            //fire up edd update module
            Utils\Updater\Init::instance()->init();
        }

    }

    public function manage_license_notice()
    {
        $license_settings = \Etn_Pro\Utils\Helper::get_option("license");
        $enable_license   = (!empty($license_settings) ? true : false);

        // Register license module
        $license = \Etn_Pro\Utils\License\License::instance();

        //fire up edd license module
        $license->init();

        $settings              = get_option("etn_premium_marketplace");
        $selected_market_place = empty($settings) ? "" : $settings;

        if (!$enable_license || $selected_market_place == "codecanyon") {
            return;
        }

        if ($license->status() != 'valid') {
            \Oxaim\Libs\Notice::instance('eventin-pro', 'pro-not-active')
                ->set_class('error')
                ->set_dismiss('global', (3600 * 24 * 7))
                ->set_message(esc_html__('Please activate Eventin Pro to get automatic updates and premium support.', 'eventin-pro'))
                ->set_button([
                    'url'   => self_admin_url('admin.php?page=etn-license'),
                    'text'  => 'Activate License Now',
                    'class' => 'button-primary',
                ])
                ->call();
        }

    }

    /**
     * Prepare wp-cafe free version if not activated
     *
     * @return void
     */
    private function prepare_eventin()
    {

        // if eventin not installed
        if (!did_action('eventin/after_load')) {

            if (Plugin_Installer::instance()->make_eventin_ready()) {
                // redirect to plugin dashboard
                wp_safe_redirect("admin.php?page=etn-event-settings");
            }

        }

    }

    public function js_css_public()
    {
        wp_register_style('swiper-bundle-min', ETN_PRO_ASSETS . 'css/swiper-bundle.min.css', [], \Wpeventin_Pro::version(), 'all');
        wp_register_style('jquery-countdown', ETN_PRO_ASSETS . 'css/jquery.countdown.css', [], \Wpeventin_Pro::version(), 'all');
        wp_enqueue_style('etn-public', ETN_PRO_ASSETS . 'css/etn-public.css', [], \Wpeventin_Pro::version(), 'all');

        if (is_rtl()) {
            wp_enqueue_style('etn-rtl-pro', ETN_PRO_ASSETS . 'css/rtl.css');
        }

        wp_register_script('swiper-bundle-min', ETN_PRO_ASSETS . 'js/swiper-bundle.min.js', ['jquery'], \Wpeventin_Pro::version(), false);
        wp_register_script('jquery-countdown', ETN_PRO_ASSETS . 'js/jquery.countdown.min.js', ['jquery'], \Wpeventin_Pro::version(), false);

        wp_register_script('etn-qr-code', ETN_PRO_ASSETS . 'js/qr-code.js', array('jquery'), '4.0.10', false);
        wp_register_script('etn-qr-code-scanner', ETN_PRO_ASSETS . 'js/qr-scanner.umd.min.js', array('jquery'), '4.0.10', false);
        wp_register_script('etn-qr-code-custom', ETN_PRO_ASSETS . 'js/qr-code-custom.js', array('jquery'), '4.0.10', false);

        wp_enqueue_script('etn-public-pro', ETN_PRO_ASSETS . 'js/etn-public.js', ['jquery'], \Wpeventin_Pro::version(), false); // Dependancy removed ['etn-qr-code'];

        // event location js start.
        $settings = get_option("etn_event_options");
        if (!empty($settings['etn_googlemap_api'])) {
            $map_js = $this->map_url();
            wp_enqueue_script('etn-map', $map_js, array('jquery'), '4.0.10', false);
            wp_enqueue_script('etn-location', ETN_PRO_ASSETS . 'js/etn-location.js', array('jquery'), '4.0.10', false);
        }
        // event location js end.
        $settings                    = Helper::get_settings();
        $attendee_verification_style = isset($settings["attendee_verification_style"]) ? $settings["attendee_verification_style"] : 'on';
        $attendee_registration       = !empty($settings["attendee_registration"]) ? true : false;

        $array = [
            'ajax_url'                     => admin_url('admin-ajax.php'),
            'location_map_nonce'           => wp_create_nonce('location_map_nonce'),
            'scanner_nonce'                => wp_create_nonce('scanner_nonce_value'),
            'attendee_page_link'           => admin_url('/edit.php?post_type=etn-attendee'),
            'scanner_common_msg'           => esc_html__('Something went wrong! Please try again.', 'eventin-pro'),
            'attendee_verification_style'  => $attendee_verification_style,
            'location_icon'                => \Wpeventin_Pro::assets_url() . 'images/location-icon.png',
            'attendee_registration_option' => $attendee_registration,
            'event_expired_message'        => esc_html__('This event has been expired.', 'eventin-pro'),
            'is_enable_attendee_registration' => etn_get_option('attendee_registration') ?: false,
        ];

        wp_localize_script('etn-public-pro', 'etn_pro_public_object', $array);

        wp_register_style('etn-frontend-submission', \Wpeventin_Pro::plugin_url() . 'multivendor/build/index.css', ['wp-edit-blocks'], \Wpeventin_Pro::version(), 'all');
        wp_register_script('etn-frontend-submission', \Wpeventin_Pro::plugin_url() . 'multivendor/build/index.js', [
            'jquery',
            'wp-element',
            'wp-i18n',
        ], \Wpeventin_Pro::version(), true);

        /**
         * Localize Frontend Submission strings
         */
        $etn_translate_text = [
            'cat_add_new'                   => esc_html__('Add New Category', 'eventin-pro'),
            'cat_name'                      => esc_html__('Category Name', 'eventin-pro'),
            'cat_search'                    => esc_html__('Search Category', 'eventin-pro'),
            'cat_name_empty_msg'            => esc_html__('Category name should not empty!', 'eventin-pro'),
            'cat_desc'                      => esc_html__('Category Description', 'eventin-pro'),
            'cat_create'                    => esc_html__('Create Category', 'eventin-pro'),
            'cat_update'                    => esc_html__('Update Category', 'eventin-pro'),
            'edit'                          => esc_html__('Edit', 'eventin-pro'),
            'preview'                       => esc_html__('Preview', 'eventin-pro'),
            'delete'                        => esc_html__('Delete', 'eventin-pro'),
            'delete_not'                    => esc_html__('Delete!', 'eventin-pro'),
            'deleted'                       => esc_html__('Deleted!', 'eventin-pro'),
            'error'                         => esc_html__('Something went wrong!', 'eventin-pro'),
            'back_to_category'              => esc_html__('Back to Category', 'eventin-pro'),
            'spinner_tip'                   => esc_html__('Loading...', 'eventin-pro'),
            'spinner_msg'                   => esc_html__('Please Wait.', 'eventin-pro'),
            'spinner_desc'                  => esc_html__('Loader will vanish after all data load.', 'eventin-pro'),
            'cat_result_title'              => esc_html__('You haven\'t added any categories yet.', 'eventin-pro'),
            'event_info'                    => esc_html__('Event Info', 'eventin-pro'),
            'add_new_event'                 => esc_html__('Add new event', 'eventin-pro'),
            'add_new_event_placeholder'     => esc_html__('Name your event', 'eventin-pro'),
            'event_title'                   => esc_html__('Event title', 'eventin-pro'),
            'event_title_empty_msg'         => esc_html__('Post title and content should not empty!', 'eventin-pro'),
            'event_content'                 => esc_html__('Event content', 'eventin-pro'),
            'attendee_list'                 => esc_html__('Attendee list', 'eventin-pro'),
            'back_to_events'                => esc_html__('Back to Events', 'eventin-pro'),
            'ticket_scanner'                => esc_html__('Ticket Scanner', 'eventin-pro'),
            'event_tooltip'                 => esc_html__('This event doesn\'t have any attendee', 'eventin-pro'),
            'event_result_title'            => esc_html__('You haven\'t added any events yet.', 'eventin-pro'),
            'post_content_error'            => esc_html__('Shouldn\'t empty!', 'eventin-pro'),
            'upload_event_logo'             => esc_html__('Upload event logo:', 'eventin-pro'),
            'upload_feature_img'            => esc_html__('Upload feature image:', 'eventin-pro'),
            'upload_banner_img'             => esc_html__('Upload banner image:', 'eventin-pro'),
            'add_category'                  => esc_html__('Add category:', 'eventin-pro'),
            'search_category'               => esc_html__('Search category', 'eventin-pro'),
            'add_tags'                      => esc_html__('Add tags:', 'eventin-pro'),
            'search_tags'                   => esc_html__('Search tags', 'eventin-pro'),
            'add_social_links'              => esc_html__('Add social links', 'eventin-pro'),
            'add'                           => esc_html__('Add', 'eventin-pro'),
            'success_title'                 => esc_html__('Event Created.', 'eventin-pro'),
            'success_msg'                   => esc_html__('Your event has been created. Take next action from the below button.', 'eventin-pro'),
            'error_save_title'              => esc_html__('Event couldn\'t saved.', 'eventin-pro'),
            'error_save_desc'               => esc_html__('Your event is not saved successfully.', 'eventin-pro'),
            'start_and_end_date'            => esc_html__('Start and end date', 'eventin-pro'),
            'start_and_end_time'            => esc_html__('Start and end time', 'eventin-pro'),
            'location_type'                 => esc_html__('Location Type', 'eventin-pro'),
            'existing_location'             => esc_html__('Existing Locations', 'eventin-pro'),
            'online_location'             => esc_html__('Online Locations', 'eventin-pro'),
            'online_location_placeholder'             => esc_html__('Select Online Location', 'eventin-pro'),
            'select_location'               => esc_html__('Select Locations', 'eventin-pro'),
            'event_location'                => esc_html__('Event location', 'eventin-pro'),
            'full_address'                  => esc_html__('Full Address', 'eventin-pro'),
            'offline_location'            => esc_html__('Offline Location', 'eventin-pro'),
            'timezone'                      => esc_html__('Timezone', 'eventin-pro'),
            'schedule'                      => esc_html__('Schedule', 'eventin-pro'),
            'search_schedule'               => esc_html__('Search Schedule', 'eventin-pro'),
            'add_new_tag'                   => esc_html__('Add New Tag', 'eventin-pro'),
            'tag_name'                      => esc_html__('Tag Name', 'eventin-pro'),
            'tag_desc'                      => esc_html__('Tag Description', 'eventin-pro'),
            'create_tag'                    => esc_html__('Create Tag', 'eventin-pro'),
            'update_tag'                    => esc_html__('Update Tag', 'eventin-pro'),
            'tag_result_title'              => esc_html__('Oops! You haven\'t added any tags yet.', 'eventin-pro'),
            'back_to_tags'                  => esc_html__('Back to Tags', 'eventin-pro'),
            'tag_name_empty_msg'            => esc_html__('Tag name should not empty!', 'eventin-pro'),
            'create_another'                => esc_html__('Create another', 'eventin-pro'),
            'add_new_location'              => esc_html__('Add New Location', 'eventin-pro'),
            'location_name'                 => esc_html__('Location Name', 'eventin-pro'),
            'location_desc'                 => esc_html__('Location Description', 'eventin-pro'),
            'back_to_location'              => esc_html__('Back to Location', 'eventin-pro'),
            'location_address'              => esc_html__('Location Address', 'eventin-pro'),
            'email'                         => esc_html__('Email', 'eventin-pro'),
            'valid_email'                   => esc_html__('Please enter a valid E-mail', 'eventin-pro'),
            'latitude'                      => esc_html__('Latitude', 'eventin-pro'),
            'longitude'                     => esc_html__('Longitude', 'eventin-pro'),
            'update_location'               => esc_html__('Update Location', 'eventin-pro'),
            'create_location'               => esc_html__('Create Location', 'eventin-pro'),
            'location_name_empty_msg'       => esc_html__('Location name should not empty!', 'eventin-pro'),
            'speaker_label'                 => esc_html__('Speaker Group', 'eventin-pro'),
            'speaker_empty_msg'             => esc_html__('Ops! You haven\'t added any speaker yet', 'eventin-pro'),
            'add_new_speaker'               => esc_html__('Add new speaker', 'eventin-pro'),
            'back_to_speaker'               => esc_html__('Back to speakers', 'eventin-pro'),
            'speaker_name_empty_msg'        => esc_html__('Speaker name should not empty!', 'eventin-pro'),
            'speaker_name'                  => esc_html__('Name', 'eventin-pro'),
            'speaker_designation'           => esc_html__('Designation', 'eventin-pro'),
            'speaker_desig_placeholder'     => esc_html__('Enter Designation', 'eventin-pro'),
            'speaker_profile_img'           => esc_html__('Speaker profile image:', 'eventin-pro'),
            'company_logo'                  => esc_html__('Company logo:', 'eventin-pro'),
            'speaker_summary'               => esc_html__('Summary', 'eventin-pro'),
            'create_speaker'                => esc_html__('Create Speaker', 'eventin-pro'),
            'update_speaker'                => esc_html__('Update Speaker', 'eventin-pro'),
            'speaker_cat'                   => esc_html__('Category', 'eventin-pro'),
            'speaker_cat_placeholder'       => esc_html__('Select Category', 'eventin-pro'),
            'organizer_label'               => esc_html__('Organizer Group', 'eventin-pro'),
            'add_more_organizer'            => esc_html__('Add More Organizer', 'eventin-pro'),
            'select_organizer'              => esc_html__('Select organizer', 'eventin-pro'),
            'display_name_of_the_day'       => esc_html__('Display name of the day', 'eventin-pro'),
            'program_title'                 => esc_html__('Program Title', 'eventin-pro'),
            'topic'                         => esc_html__('Topic', 'eventin-pro'),
            'topic_details'                 => esc_html__('Topic Details', 'eventin-pro'),
            'start_time'                    => esc_html__('Start Time', 'eventin-pro'),
            'end_time'                      => esc_html__('End Time', 'eventin-pro'),
            'add_new_schedule'              => esc_html__('Add New Schedule', 'eventin-pro'),
            'schedule_title'                => esc_html__('Schedule Title', 'eventin-pro'),
            'create_schedule'               => esc_html__('Create Schedule', 'eventin-pro'),
            'update_schedule'               => esc_html__('Update Schedule', 'eventin-pro'),
            'schedule_updated_title'        => esc_html__('Schedule Updated Successfully', 'eventin-pro'),
            'schedule_updated_message'      => esc_html__('Your schedule has been updated successfully.', 'eventin-pro'),
            'schedule_created_title'        => esc_html__('Schedule Created Successfully', 'eventin-pro'),
            'schedule_created_message'      => esc_html__('Your schedule has been created successfully.', 'eventin-pro'),
            'schedule_delete_message'       => esc_html__('Are you sure you want to delete this schedule?', 'eventin-pro'),
            'select_speaker_placeholder'    => esc_html__('Please select speakers', 'eventin-pro'),
            'add_new_topic'                 => esc_html__('Add New Topic', 'eventin-pro'),
            'back_to_schedule'              => esc_html__('Back to Schedule', 'eventin-pro'),
            'schedule_title_error'          => esc_html__('Please input schedule title!', 'eventin-pro'),
            'schedule_empty_msg'            => esc_html__('You have not added any schedule yet!', 'eventin-pro'),
            'date'                          => esc_html__('Date', 'eventin-pro'),
            'name_of_the_day'               => esc_html__('Day of the Week', 'eventin-pro'),
            'schedule_topic'                => esc_html__('Schedule Topic', 'eventin-pro'),
            'select_speaker'                => esc_html__('Select Speaker', 'eventin-pro'),
            'attendee_name'                 => esc_html__('Attendee name', 'eventin-pro'),
            'edit_attendee'                 => esc_html__('Edit attendee', 'eventin-pro'),
            'delete_attendee'               => esc_html__('Delete attendee', 'eventin-pro'),
            'attendee_email'                => esc_html__('Attendee e-mail', 'eventin-pro'),
            'attendee_update_details'       => esc_html__('Update details', 'eventin-pro'),
            'ticket_details'                => esc_html__('Ticket Details', 'eventin-pro'),
            'limited_ticket'                => esc_html__('Limited tickets', 'eventin-pro'),
            'limited_ticket_description'    => esc_html__('Enable limited ticket. Set ticket stock from ticket variation.', 'eventin-pro'),
            'ticket_variation'              => esc_html__('Ticket variation', 'eventin-pro'),
            'ticket_name'                   => esc_html__('Ticket Name:', 'eventin-pro'),
            'ticket_price'                  => esc_html__('Ticket Price:', 'eventin-pro'),
            'no_of_ticket'                  => esc_html__('No. of Tickets:', 'eventin-pro'),
            'min_purchase_qty'              => esc_html__('Minimum Purchase Qty:', 'eventin-pro'),
            'max_purchase_qty'              => esc_html__('Maximum Purchase Qty:', 'eventin-pro'),
            'ticket_id_label'               => esc_html__('Ticket ID', 'eventin-pro'),
            'ticket_status'                 => esc_html__('Ticket status', 'eventin-pro'),
            'used'                          => esc_html__('Used', 'eventin-pro'),
            'unused'                        => esc_html__('Unused', 'eventin-pro'),
            'payment_status'                => esc_html__('Payment status', 'eventin-pro'),
            'attendees_list'                => esc_html__('Attendees List', 'eventin-pro'),
            'empty_attendees_msg'           => esc_html__('This event doesn\'t have any attendee', 'eventin-pro'),
            'general_info'                  => esc_html__('General Info', 'eventin-pro'),
            'input_missing'                 => esc_html__('Input missing!', 'eventin-pro'),
            "confirm_delete"                => esc_html__('Are you sure you want to delete this item?', 'eventin-pro'),
            'name'                          => esc_html__('Name', 'eventin-pro'),
            'desc'                          => esc_html__('Description', 'eventin-pro'),
            'create'                        => esc_html__('Create', 'eventin-pro'),
            'previous'                      => esc_html__('Previous', 'eventin-pro'),
            'next'                          => esc_html__('Next', 'eventin-pro'),
            'update'                        => esc_html__('Update', 'eventin-pro'),
            'updated'                       => esc_html__('Updated', 'eventin-pro'),
            'success'                       => esc_html__('Success', 'eventin-pro'),
            'failed'                        => esc_html__('Failed', 'eventin-pro'),
            'cancel'                        => esc_html__('Cancel', 'eventin-pro'),
            'submit'                        => esc_html__('Submit', 'eventin-pro'),
            'action'                        => esc_html__('Action', 'eventin-pro'),
            'refresh'                       => esc_html__('Refresh', 'eventin-pro'),
            'select_all'                    => esc_html__('Select All', 'eventin-pro'),
            'already_exist'                 => esc_html__('Already exists', 'eventin-pro'),
            'virtual_product'               => esc_html__('Virtual Product', 'eventin-pro'),
            'virtual_product_description'   => esc_html__('Register event as WooCommerce virtual product and let WooCommerce handle it\'s behaviour.', 'eventin-pro'),
            'add_variation'                 => esc_html__('Add Variation', 'eventin-pro'),
            'event_external_link'           => esc_html__('Event external link', 'eventin-pro'),
            'google_meet_link'              => esc_html__('Google meet link', 'eventin-pro'),
            'google_meet_description'       => esc_html__('Google Meet Description', 'eventin-pro'),
            'enable_google_meet'            => esc_html__('Enable Google Meet', 'eventin-pro'),
            'certificate_title'             => esc_html__('Select Certificate Template', 'eventin-pro'),
            'certificate_desc'              => esc_html__('Select the page template which will be used as event certificate.', 'eventin-pro'),
            'item'                          => esc_html__('Item', 'eventin-pro'),
            'faq_title'                     => esc_html__('Event FAQ\'s', 'eventin-pro'),
            'faq_item_title'                => esc_html__('Faq Title', 'eventin-pro'),
            'faq_item_content'              => esc_html__('Faq Content', 'eventin-pro'),
            'events'                        => esc_html__('Events', 'eventin-pro'),
            'event_categories'              => esc_html__('Event Categories ', 'eventin-pro'),
            'event_tags'                    => esc_html__('Event Tags ', 'eventin-pro'),
            'event_locations'               => esc_html__('Event Locations', 'eventin-pro'),
            'speakers'                      => esc_html__('Speakers', 'eventin-pro'),
            'schedules'                     => esc_html__('Schedules', 'eventin-pro'),
            'upload'                        => esc_html__('Upload', 'eventin-pro'),
            'avatar_image'                  => esc_html__('Avatar Image', 'eventin-pro'),
            'search_sand_select'            => esc_html__('Search and select', 'eventin-pro'),
            'no_data_found'                 => esc_html__('No data found', 'eventin-pro'),
            'yearly_months_day'             => esc_html__('Yearly Month\'s Day', 'eventin-pro'),
            'select_month'                  => esc_html__('Select month', 'eventin-pro'),
            'preview_text'                  => esc_html__('Preview will appear here', 'eventin-pro'),
            'icons'                         => esc_html__('Icons', 'eventin-pro'),
            'close'                         => esc_html__('Close', 'eventin-pro'),
            'recurring_thumbnail'           => esc_html__('Do want to hide Recurring event thumbnail?', 'eventin-pro'),
            'recurring_event'               => esc_html__('Recurring Event', 'eventin-pro'),
            'registration_deadline'         => esc_html__('Registration Deadline', 'eventin-pro'),
            'step_one'                      => esc_html__('Step 1', 'eventin-pro'),
            'step_two'                      => esc_html__('Step 2', 'eventin-pro'),
            'step_three'                    => esc_html__('Step 3', 'eventin-pro'),
            'welcome_to_eventin'            => esc_html__('Welcome to Eventin', 'eventin-pro'),
            'subscriptions'                 => esc_html__('Subscription', 'eventin-pro'),
            'step'                          => esc_html__('step', 'eventin-pro'),
            'of'                            => esc_html__('of', 'eventin-pro'),
            'event_will_repeat'             => esc_html__('Event will repeat', 'eventin-pro'),
            'recurrence_interval'           => esc_html__('Recurrence Interval', 'eventin-pro'),
            'daily'                         => esc_html__('Daily', 'eventin-pro'),
            'weekly'                        => esc_html__('Weekly', 'eventin-pro'),
            'monthly'                       => esc_html__('Monthly', 'eventin-pro'),
            'monthly_advanced'              => esc_html__('Monthly (advanced)', 'eventin-pro'),
            'yearly'                        => esc_html__('Yearly', 'eventin-pro'),
            'ends'                          => esc_html__('Ends', 'eventin-pro'),
            'recurrence_duration'           => esc_html__('Each recurrence duration for Day(s)', 'eventin-pro'),
            'on_event_end_date'             => esc_html__('On event end date', 'eventin-pro'),
            'recurrence_validation_message' => esc_html__('You must provide event start and end date for enabling recurrence', 'eventin-pro'),
            'icon_media_name'               => esc_html__('Icon Name', 'eventin-pro'),
            'social_title'                  => esc_html__('Social Title', 'eventin-pro'),
            'social_url'                    => esc_html__('Social URL', 'eventin-pro'),
            'select_schedule_type'          => esc_html__('Select Schedule Type', 'eventin-pro'),
            'schedule_with_speaker'         => esc_html__('Schedule With Speaker', 'eventin-pro'),
            'schedule_without_speaker'      => esc_html__('Schedule Without Speaker', 'eventin-pro'),
            'start_date'                    => esc_html__('Start Date', 'eventin-pro'),
            'end_date'                      => esc_html__('End Date', 'eventin-pro'),
            'select_deadline'               => esc_html__('Select deadline', 'eventin-pro'),
            'select_with_dash'              => esc_html__('-- Select --', 'eventin-pro'),
            'add_question'                  => esc_html__('Add Question', 'eventin-pro'),
            'please_select_a_date'          => esc_html__('Please select a date!', 'eventin-pro'),
            'select_date'                   => esc_html__('Select date', 'eventin-pro'),
            'recurring_parent'              => esc_html__('Recurring Parent', 'eventin-pro'),
            'recurring_child'               => esc_html__('Recurring Child', 'eventin-pro'),
            'ticket_sale_dates'               => esc_html__('Ticket Sale Start and End Date', 'eventin-pro'),
            'ticket_sale_times'               => esc_html__('Ticket Sale Start and End Time', 'eventin-pro'),
            'google_meet'               => esc_html__('Google Meet', 'eventin-pro'),
            'zoom'               => esc_html__('Zoom', 'eventin-pro'),
            'custom_url'               => esc_html__('Custom URL', 'eventin-pro'),
            'custom_url_placeholder'               => esc_html__('Please enter your custom url', 'eventin-pro'),
            'configure_zoom_meet'               => esc_html__("Click Here to Configure Google Meet and Zoom", 'eventin-pro'),
            'google_meet_notice'               => esc_html__("Click Here to Configure Google Meet", 'eventin-pro'),
            'zoom_notice'               => esc_html__("Click Here to Configure Zoom", 'eventin-pro'),
            'use_google_meet'               => esc_html__("Use Google Meet for this Event", 'eventin-pro'),
            'meet_not_connected'               => esc_html__("Google Meet is not connected", 'eventin-pro'),
            'use_zoom'               => esc_html__("Click Here to Configure Zoom", 'eventin-pro'),
            'zoom_not_connected'               => esc_html__("Click Here to Configure Zoom", 'eventin-pro'),
           
        ];
        wp_localize_script('etn-frontend-submission', 'etn_translate_object', $etn_translate_text);
    }

    /**
     * Get Map Url
     */
    public function map_url()
    {
        $map_js   = 'https://maps.google.com/maps/api/js?libraries=places';
        $settings = get_option("etn_event_options");
        if (!empty($settings)) {
            $api_key = !empty($settings['google_api_key']) ? $settings['google_api_key'] : 'AIzaSyBRiJpfKRV-hDFuQ6ynEAStJVO09g5Ecd4';
            $map_js  = $map_js . '&key=' . $api_key;
            $map_js .= '&callback=Function.prototype';
        }

        return $map_js;
    }

    public function js_css_admin()
    {

        // get screen id
        $screen             = get_current_screen();
        $screen_id          = $screen->id;
        $allowed_screen_ids = array(
            'post',
            'page',
            'etn',
            'edit-etn',
            'etn-attendee',
            'edit-etn-attendee',
            'edit-etn_category',
            'edit-etn_tags',
            'etn-schedule',
            'edit-etn-schedule',
            'edit-etn_speaker_category',
            'etn-speaker',
            'edit-etn-speaker',
            'etn-zoom-meeting',
            'edit-etn-zoom-meeting',
            'eventin_page_etn-event-settings',
            'eventin_page_etn_sales_report',
            'eventin_page_eventin_get_help',
            'eventin_page_etn-license',
            'edit-etn_location',
            'eventin_page_etn_stripe_orders_report',
            'eventin_page_etn-event-shortcode',
            'eventin_page_etn_rsvp_invitation',
            'eventin_page_etn_fb_import',
        );
        /**
         * register the eventin pro admin css and js 
         */ 
        $dep_file_path = \Wpeventin_Pro::plugin_dir() . 'assets/build/js/script.asset.php';
        if( file_exists($dep_file_path) ) {
            $deps = require $dep_file_path;

            // push into dependencies
            array_push($deps['dependencies'], 'etn-packages');
            wp_register_style('etn-style-pro', \Wpeventin_Pro::plugin_url() . 'assets/build/css/style.css', [], $deps['version'], 'all');
            wp_register_script('etn-script-pro', \Wpeventin_Pro::plugin_url() . 'assets/build/js/script.js', $deps['dependencies'], $deps['version'], true);
        }

        if (in_array($screen_id, $allowed_screen_ids)) {
            
            wp_enqueue_style('etn-admin', ETN_PRO_ASSETS . 'css/etn-admin.css', [], \Wpeventin_Pro::version(), 'all');
            wp_enqueue_script('etn-admin-pro', ETN_PRO_ASSETS . 'js/etn-admin.js', [
                'jquery',
                'wp-color-picker',
            ], \Wpeventin_Pro::version(), false);

            // Event location js start.
            wp_enqueue_script('etn-pro-map-admin', $this->map_url(), ['jquery'], \Wpeventin_Pro::version(), true);
            wp_enqueue_script('etn-location-admin', ETN_PRO_ASSETS . 'js/etn-location-admin.js', ['jquery'], \Wpeventin_Pro::version(), false);
            // Event location js end.

            $base_url            = admin_url();
            $attendee_cpt        = new \Etn\Core\Attendee\Cpt();
            $attendee_endpoint   = $attendee_cpt->get_name();
            $action_url          = $base_url . $attendee_endpoint;
            $ticket_scanner_link = $action_url . "&etn_action=" . urlencode('ticket_scanner');
            $ticket_scanner_link = admin_url('/edit.php?post_type=etn-attendee&etn_action=ticket_scanner');

            // localize data
            $license_settings = \Etn_Pro\Utils\Helper::get_option("license");
            $enable_license   = (!empty($license_settings) ? "yes" : "no");
            $array            = [
                'ajax_url'            => admin_url('admin-ajax.php'),
                'license_module'      => $enable_license,
                'scanner_nonce'       => wp_create_nonce('scanner_nonce_value'),
                'ticket_scanner_link' => $ticket_scanner_link,
                'ticket_scanner_text' => esc_html__('Ticket Scanner', 'eventin-pro'),
                'required'            => esc_html__('Required', 'eventin-pro'),
                'optional'            => esc_html__('Optional', 'eventin-pro'),
                'warning_message'     => esc_html__('Please fill the label field', 'eventin-pro'),
                'warning_icon'        => '<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6574 2.34456C10.5339 -0.778916 5.46609 -0.778916 2.34261 2.34456C-0.78087 5.46804 -0.78087 10.5359 2.34261 13.6593C5.46609 16.7828 10.5339 16.7828 13.6574 13.6593C16.7843 10.5324 16.7809 5.46804 13.6574 2.34456ZM9.04522 11.4785C9.04522 12.0559 8.57913 12.522 8.00174 12.522C7.42435 12.522 6.95826 12.0559 6.95826 11.4785V7.30456C6.95826 6.72717 7.42435 6.26108 8.00174 6.26108C8.57913 6.26108 9.04522 6.72717 9.04522 7.30456V11.4785ZM7.98435 5.52021C7.38261 5.52021 6.98261 5.09587 6.99652 4.57065C6.98261 4.02108 7.38609 3.60717 7.99826 3.60717C8.61044 3.60717 9 4.02108 9.01391 4.57065C9.01044 5.09587 8.61044 5.52021 7.98435 5.52021Z" fill="#F42929"/></svg>',
                'certificate_nonce'   => wp_create_nonce('generate_attendee_certificate'),
                'site_url'            => site_url(),
            ];

            wp_localize_script('etn-admin-pro', 'etn_pro_admin_object', $array);
        }

    }

    public function etn_elementor_js()
    {
        wp_enqueue_script('etn-elementor-pro-inputs', ETN_PRO_ASSETS . 'js/elementor.js', ['elementor-frontend'], \Wpeventin_Pro::version(), true);
    }

}