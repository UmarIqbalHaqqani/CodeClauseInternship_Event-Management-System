<?php
use Etn\Utils\Helper;

if (class_exists('WooCommerce')) {
    add_filter('woocommerce_thankyou_order_received_text', 'etn_thank_you_title', 20, 2);
    add_filter('woocommerce_endpoint_order-received_title', 'etn_thank_you_title_details');
    add_action("eventin/after_thankyou", "etn_thankyou_extra_options");

    if (!empty(\Etn_Pro\Utils\Helper::get_option('invoice_include_event_details'))) {
        add_action('woocommerce_order_item_meta_start', 'etn_email_include_additional_details', 10, 3);
        add_action('woocommerce_after_cart_item_name', 'maybe_show_event_details', 11, 2);
    }

    // Add fluent crm contact after order created
    add_action('woocommerce_thankyou', 'process_fluent_crm_data_after_order_created', 10, 1);

    // add attendee emails to groundhogg contacts list
    $settings       = \Etn\Core\Settings\Settings::instance()->get_settings_option();
    $groundhogg_api = !empty($settings["etn_groundhogg_api"]) ? true : false;

    if ($groundhogg_api) {
        add_action('woocommerce_checkout_update_order_meta', 'create_groundhogg_contacts_list', 10, 1);
    }

}

function maybe_show_event_details($cart_item, $cart_item_key)
{
    etn_email_include_additional_details(0, $cart_item, null);
}

/**
 * Include Additional Event Data
 *
 * @since 2.4.2
 *
 * @param [type] $item_id
 * @param [type] $item
 * @param [type] $order
 * @return void
 */
function etn_email_include_additional_details($item_id, $item, $order)
{

    if (is_checkout() && !empty(is_wc_endpoint_url('order-received'))) {
        return;
    }

    if (is_cart()) {
        $item = $item['data'];
    }

    $event_id = !is_null($item->get_meta('event_id', true)) ? $item->get_meta('event_id', true) : "";

    if (!empty($event_id)) {
        $product_post = get_post($event_id);
    } else {
        if (is_cart()) {
            $matched_posts = get_posts([
                'name'        => $item->get_slug(),
                'post_type'   => 'etn',
                'numberposts' => 1,
            ]);

            if (!empty($matched_posts)) {
                $product_post = $matched_posts[0];
            }
        } else {
            $array_of_objects = get_posts(
                [
                    'title'     => $item->get_name(),
                    'post_type' => 'etn',
                ]
            );

            if (!empty($array_of_objects[0]->ID)) {
                $product_post = get_post($array_of_objects[0]->ID);
            }
        }
    }

    if (!empty($product_post)) {
        $post_id    = $product_post->ID;
        $event_link = (\Etn_pro\Utils\Helper::is_recurrence($post_id)) ? get_the_permalink(wp_get_post_parent_id($post_id)) : get_the_permalink($post_id);
        // $date_options      = \Etn_Pro\Utils\Helper::get_date_formats();

        $event_options  = get_option("etn_event_options");
        $etn_start_date = get_post_meta($post_id, 'etn_start_date', true);
        $etn_start_time = strtotime(get_post_meta($post_id, 'etn_start_time', true));
        $etn_end_date   = get_post_meta($post_id, 'etn_end_date', true);
        $etn_end_time   = strtotime(get_post_meta($post_id, 'etn_end_time', true));
        $event_timezone = get_post_meta($post_id, 'event_timezone', true);

        $event_time_format = empty($event_options["time_format"]) ? '12' : $event_options["time_format"];
        $event_start_time  = empty($etn_start_time) ? '' : (($event_time_format == "24") ? date('H:i', $etn_start_time) : date('g:i a', $etn_start_time));
        $event_end_time    = empty($etn_end_time) ? '' : (($event_time_format == "24") ? date('H:i', $etn_end_time) : date('g:i a', $etn_end_time));
        $event_start_date  = Helper::etn_date($etn_start_date);
        $event_end_date    = Helper::etn_date($etn_end_date);

        ?>
        <div class="etn-invoice-email-event-meta">
            <div>
                <?php echo esc_html__('Event Page: ', 'eventin-pro'); ?>
                <a href="<?php echo esc_url($event_link); ?>"><?php echo esc_html__('Click here. ', 'eventin-pro'); ?></a>
            </div>
            <div><?php echo esc_html__('Starts At: ', 'eventin-pro') . $event_start_date . " " . $event_start_time; ?></div>
            <div><?php echo esc_html__('Ends At: ', 'eventin-pro') . $event_end_date . " " . $event_end_time; ?></div>
            <div><?php echo esc_html__('Timezone: ', 'eventin-pro') . $event_timezone; ?></div>
        </div>
        <?php
}
}

function etn_thank_you_title($thank_you_title, $order)
{

    if (!empty($order->get_order_key())) {
        $thank_you_markup = strtoupper("Invoice: " . $order->get_order_key());
        $thank_you_title .= ' ' . $thank_you_markup;
    }
    return $thank_you_title;
}

function etn_thank_you_title_details($old_title)
{
    return esc_html__("Order Received", "eventin-pro");
}

function etn_thankyou_extra_options()
{
    $settings                            = \Etn\Core\Settings\Settings::instance()->get_settings_option();
    $etn_show_print_download_on_thankyou = isset($settings['etn_show_print_download_on_thankyou']) ? true : false;

    if ($etn_show_print_download_on_thankyou) {

        wp_enqueue_script('pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js', ['jquery'], \Wpeventin_Pro::version(), true);
        wp_enqueue_script('html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js', ['jquery'], \Wpeventin_Pro::version(), true);

        ?>
        <div class="extra-buttons">
            <button class="etn-btn etn-primary" onclick="etn_pro_pirnt_content_area('woocommerce-order');"><?php echo esc_html__("Print", "eventin-pro"); ?></button>
            <a class="etn-btn etn-primary download-invoice-pdf" href="javascript:etn_pro_download_pdf()" ><?php echo esc_html__("Download", "eventin-pro"); ?></a>
        </div>
        <?php
}
}

/**
 * add attendee id to order id at the time order is created for paypal payment
 *
 * @param int $order_id
 * @return void
 */

function process_fluent_crm_data_after_order_created( $order_id ) {

    // Return if order id is not set
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $billing_data = [
        'email' => $order->get_billing_email(),
        'fname' => $order-> get_billing_first_name(),
        'lname' => $order-> get_billing_last_name(),
    ];


    foreach ($order->get_items() as $item_id => $item) {
        $product_name = $item->get_name();
        $event_id = $item->get_meta('event_id', true) ?: "";

        $event_object = !empty($event_id) ? get_post($event_id) : \Etn\Core\Event\Helper::instance()->get_etn_object($product_name);

        // Check if the product is an event
        if ($event_object->post_type === 'etn') {

            $fluent_crm_enabled = get_post_meta($event_id, 'fluent_crm', true);
    
            if ($fluent_crm_enabled === "yes") {

                $fluent_crm_webhook = get_post_meta($event_id, 'fluent_crm_webhook', true);
                
                if ($fluent_crm_webhook) {

                    $settings            = \Etn\Core\Settings\Settings::instance()->get_settings_option();
                    $attendee_reg_enable = !empty($settings["attendee_registration"]);
                    $reg_require_email   = !empty($settings["reg_require_email"]);
            
                    if ($attendee_reg_enable && $reg_require_email) {
            
                        $attendees = ent_get_attendee_by_order_id($order_id, $event_id);
            
                        foreach ($attendees as $attendee) {
                            $attendee_data = [
                                'email' => get_post_meta($attendee->ID, 'etn_email', true) ?? '',
                                'full_name' => get_post_meta($attendee->ID, 'etn_name', true) ?? '',
                            ];
                            // Send attendee data to fluent crm webhook 
                            etn_send_fluent_crm_data($fluent_crm_webhook, $attendee_data);
                        }
                    } else {
                        // Send purchaser data to fluent crm webhook
                        etn_send_fluent_crm_data($fluent_crm_webhook, $billing_data);
                    }
                }
            }
        }
    }
}

/**
 * Get attendees by order ID and event ID.
 *
 * @param int $order_id The ID of the order.
 * @param int $event_id The ID of the event.
 *
 * @return array An array of WP_Post objects representing the attendees.
 */

function ent_get_attendee_by_order_id($order_id, $event_id) {
    $args = [
        'post_type' => 'etn-attendee',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query' => [
            'relation' => 'AND',
            ['key' => 'etn_attendee_order_id', 'value' => $order_id],
            ['key' => 'etn_event_id', 'value' => $event_id],
        ],
    ];

    $attendees = get_posts($args);

    return $attendees;
}

/**
 * Send data to fluent crm
 *
 * @param string $url FluentCRM webhook url
 * @param array $data Data to be sent
 * @return void
 */

function etn_send_fluent_crm_data($url, $data) {
    $response = wp_remote_post(
        $url,
        [
            'body' => $data
        ]
    );
    return $response;
}

/**
 * add attendee email to groundhogg contcts list
 *
 * @param int $order_id
 * @return void
 */
function create_groundhogg_contacts_list($order_id)
{

    if (!$order_id) {
        return;
    }

    global $wpdb;

    $order = wc_get_order($order_id);

    $settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();

    $token               = trim($settings["groundhogg_token"]); // Your token
    $public_key          = trim($settings["groundhogg_public_key"]); // Your public key
    $groundhogg_v3_route = trim($settings["groundhogg_v3_route"]); // Your v3 route

    $userId = 0;

    foreach ($order->get_items() as $item_id => $item) {

        // Get the product name
        $product_name = $item->get_name();
        $event_id     = !is_null($item->get_meta('event_id', true)) ? $item->get_meta('event_id', true) : "";

        if (!empty($event_id)) {
            $event_object = get_post($event_id);
        } else {
            $array_of_objects = get_posts(
                [
                    'title'     => $product_name,
                    'post_type' => 'etn',
                ]
            );

            $event_object = get_post($array_of_objects[0]->ID);

        }

        if (!empty($event_object->post_type) && ('etn' == $event_object->post_type)) {

            $event_id        = $event_object->ID;
            $groundhogg_tags = trim(get_post_meta($event_id, 'groundhogg_tags', true));
            $tags            = explode(',', $groundhogg_tags);

            $groundhogg_woocommerce_purchase = $settings["groundhogg_woocommerce_purchase"];
            if (!isset($groundhogg_woocommerce_purchase)) {
                $user_email = get_post_meta($order_id, '_billing_email', true);
                $first_name = get_post_meta($order_id, '_billing_first_name', true);

                $body_purchase = array(
                    'email'      => $user_email,
                    'first_name' => $first_name,
                );
                if ($groundhogg_tags != '' && !empty($tags)) {
                    $body_purchase['tags'] = $tags;
                }

                $response_user = wp_remote_post($groundhogg_v3_route, [
                    'headers' => [
                        'Gh-Token'      => $token,
                        'Gh-Public-Key' => $public_key,
                    ],
                    'body'    => $body_purchase,
                    'timeout' => 845,
                ]);
            }

            $groundhogg_attendee_email = $settings["groundhogg_attendee_email"];
            $attendee_reg_enable       = !empty($settings["attendee_registration"]) ? true : false;
            $reg_require_email         = !empty($settings["reg_require_email"]) ? true : false;
            if (!isset($groundhogg_attendee_email) && $attendee_reg_enable && $reg_require_email) {
                $args = array(
                    'post_type'   => 'etn-attendee',
                    'post_status' => 'publish',
                    'numberposts' => -1,
                    'meta_query'  => array(
                        'relation' => 'AND',
                        array(
                            'key'   => 'etn_attendee_order_id',
                            'value' => $order_id,
                        ),
                        array(
                            'key'   => 'etn_event_id',
                            'value' => $event_id,
                        ),
                    ),
                );
                $attendees = get_posts($args);

                foreach ($attendees as $attendee) {
                    $attendee_email = get_post_meta($attendee->ID, 'etn_email', true);
                    $etn_name       = get_post_meta($attendee->ID, 'etn_name', true);

                    $body = array(
                        'email'      => $attendee_email,
                        'first_name' => $etn_name,
                    );
                    if ($groundhogg_tags != '' && !empty($tags)) {
                        $body['tags'] = $tags;
                    }

                    $response = wp_remote_post($groundhogg_v3_route, [
                        'headers' => [
                            'Gh-Token'      => $token,
                            'Gh-Public-Key' => $public_key,
                        ],
                        'body'    => $body,
                        'timeout' => 845,
                    ]);

                }
            }

        }
    }
}