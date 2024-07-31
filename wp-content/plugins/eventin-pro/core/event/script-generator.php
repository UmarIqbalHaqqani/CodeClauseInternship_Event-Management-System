<?php
/**
 * Generate script for single event and shortcodes
 *
 * @package EventinPro
 */

namespace Etn_Pro\Core\Event;

use Etn_Pro\Traits\Singleton;

/**
 * Class script generator
 *
 * @since 3.3.6
 */
class Script_Generator {
    use Singleton;

    /**
     * Initialize neccesary things
     *
     * @return  void
     */
    public function init() {
        // Generate External Scripts.
        add_action( 'init', [$this, 'generate_external_script'] );

        // Add scrirpt on template head.
        add_action( 'wp_head', [$this, 'add_script_to_single_event'] );
        add_action( 'wp_head', [$this, 'manage_admin_bar'] );

        // Add template for single event and shortcodes.
        add_filter( 'template_include', [$this, 'include_template'], 100 );

        // Add row action for generating single event.
        add_filter( 'page_row_actions', [ $this, 'add_row_actions' ], 10, 2 );

        add_action( 'admin_footer', [ $this, 'add_modal' ] );

        add_filter( 'etn/purchase_form_template', [ $this, 'include_purchase_template' ] );

        // Handling ajax-request
        add_action( 'wp_ajax_eventin_external_script', [$this, 'create_shortcode_page'] );

        add_action( 'wp_footer', [ $this, 'add_link_script' ] );
    }

    /**
     * Enqueue iframe content window scripts
     *
     * @param   string  $name  Toplevel view name
     *
     * @return  void
     */
    public function add_script_to_single_event( $name ) {
        $external = isset( $_GET['eventin-external'] ) ? $_GET['eventin-external'] : null;

        if ( 1 != $external ) {
            return;
        }

        wp_enqueue_script(
            'eventin-iframe-content',
            ETN_PRO_ASSETS . 'js/iframeResizer.contentWindow.js'
        );
    }

    /**
     * Hide admin bar when showin on external site
     *
     * @return  void
     */
    public function manage_admin_bar() {
        $external = isset( $_GET['eventin-external'] ) ? $_GET['eventin-external'] : null;

        /**
         * If it is not from external site return default template
         */
        if (  ( is_single() || is_page() ) && 1 == $external ) {
            add_filter( 'show_admin_bar', '__return_false' );
            echo '<style>body{background-color:unset;}</style>';
        }
    }

    /**
     * Override the template if it is from external site
     *
     * @param   string  $template  Single post template
     *
     * @return  string Single Post Template
     */
    public function include_template( $template ) {
        $external = isset( $_GET['eventin-external'] ) ? $_GET['eventin-external'] : null;

        /**
         * Select template for the single event
         */
        if ( is_single() && 'etn' == get_post_type() && 1 == $external ) {
            /**
             * Get the external single eventin template
             *
             * @var string
             */
            $template = ETN_PRO_PLUGIN_TEMPLATE_DIR . '/external-script/single-event.php';
        }

        /**
         * Select template for the shortcodes
         *
         * @var string
         */
        if ( is_page() && 1 == $external ) {
            /**
             * Get the external shortcodes template
             *
             * @var string
             */
            $template = ETN_PRO_PLUGIN_TEMPLATE_DIR . '/external-script/archive-events.php';
        }

        return $template;
    }

    /**
     * Generate external script
     *
     * @return  void
     */
    public function generate_external_script() {
        $url_path      = trim( parse_url( add_query_arg( array() ), PHP_URL_PATH ), '/' );
        $external_path = 'eventin-external-script';

        $hosts          = $this->get_registered_domains();
        $external_url   = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : null;

        if ( ! strpos( $url_path, 'eventin-external-script' ) && $url_path != $external_path ) {
            return;
        }

        $id   = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
        $type = isset( $_GET['t'] ) ? sanitize_text_field( $_GET['t'] ) : '';

        $url = add_query_arg( [
            'eventin-external' => 1,
        ], get_permalink( $id ) );

        header( 'Content-type: application/javascript; charset=utf-8' );
        $script = $this->make_script( [
            'id'    => $id,
            'type'  => $type,
            'title' => get_the_title( $id ),
            'src'   => $url,
        ] );

        if ( ! $external_url ) {
            echo "console.error('Can\'t detect the domain.');";
        }

        if ( ! in_array( $this->get_domain( $external_url ), $hosts ) ) {
            echo "console.error('Your site is not registered for external script. Please register first.');";
            exit();
        }

        echo $script;
        exit();
    }

    /**
     * Make external script
     *
     * @param   array  $args  Scripts Args
     *
     * @return string
     */
    private function make_script( $args = [] ) {
        $defaults = [
            'id'    => 0,
            'type'  => '',
            'title' => '',
            'src'   => '',
        ];

        $args              = wp_parse_args( $args, $defaults );
        $iframe_resizer_js = ETN_PRO_ASSETS . 'js/iframeResizer.min.js';

        $html = '<div align=\"center\">';

        $html .= "<iframe area-label=\"{$args['title']}\" id=\"event-external-{$args['id']}\" width=\"100%\" style=\"border: none; display: block; border: 0\" frameborder=\"0\" scrolling=\"no\" scroll=\"no\" src=\"{$args['src']}\">";

        $html .= "</iframe><script src=\"{$iframe_resizer_js}\"></script><script>iFrameResize({ log: false,heightCalculationMethod : \'lowestElement\', }, \"#event-external-{$args['id']}\")</script></div>";

        $script = "document.write('" . $html . "')";

        return $script;
    }

    /**
     * Create shortcode page
     *
     * @return  void Handle Ajax request for shortcode page
     */
    public function create_shortcode_page() {
        $post_title = isset( $_POST['post_name'] ) ? sanitize_text_field( $_POST['post_name'] ) : '';
        $short_code = isset( $_POST['shortcode'] ) ? $_POST['shortcode'] : '';

		$array_of_objects = get_posts(
			[
				'title' => $post_title,
				'post_type' => 'etn'
			]
		);

		$page_id = get_post( $array_of_objects[0]->ID );

        $args = [
            'post_title'   => $post_title,
            'post_content' => $short_code,
            'post_status'  => 'publish',
            'post_author'  => '1',
            'post_type'    => 'page',
            'post_name'    => $post_title,
        ];

        if ( $page_id ) {
            $args['ID'] = $page_id->ID;
        }

        $page = wp_insert_post( $args );

        if ( is_wp_error( $page ) ) {
            wp_send_json_error( [
                'message' => $page->get_error_message(),
            ] );
        }

        wp_send_json_success( [
            'message' => __( 'Success', 'eventin-pro' ),
            'id'      => $page,
        ] );
    }

    /**
     * Add row actions for etn post type
     *
     * @param   array  $actions  Row actions
     * @param   object  $post     Actions for the current post
     *
     * @return  array   Row actions
     */
    public function add_row_actions( $actions, $post ) {
        if ( 'etn' != $post->post_type ) {
            return $actions;
        }

        $actions['create_script'] = sprintf( '<a href="#" class="etn-script-generate-btn" data-id="%s">%s</a>', $post->ID, __( 'Get Script', 'eventin-pro' ) );
    
        return $actions;
    }

    /**
     * Single event modal template
     *
     * @return  void   Modal Template
     */
    public function add_modal() {
        if ( 'etn' != get_current_screen()->post_type ) {
            return;
        }
        ?>
            <div 
                class="shortcode-generator-main-wrap etn-script-generator-modal" 
            >
                <div 
                    class="shortcode-generator-inner etn-script-generator-modal-body"
                >
                    <div class="shortcode-popup-close etn-script-generator-modal-close">x</div>
                    <div class="etn-script-generator-modal-content"></div>
                </div>
            </div>
        <?php
    }

    /**
     * Add purchase titcket template
     *
     * @param   string  $template  Purchase ticket template
     *
     * @return  string
     */
    public function include_purchase_template( $template ) {
        $external = isset( $_GET['eventin-external'] ) ? $_GET['eventin-external'] : null;

        /**
         * Update template if request from external site
         *
         * @var string
         */
        if ( 1 == $external ) {
            $template = ETN_PRO_PLUGIN_TEMPLATE_DIR . '/external-script/purchase-ticket.php';
        }

        return $template;
    }

    /**
     * Get domain from url.
     *
     * @param   string  $url  URL from string.
     *
     * @return  string|bool Return domain otherwise false.
     */
    public function get_domain( $url ) {
        $pieces = parse_url($url);
		$domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];

		if ( preg_match(
                '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,20})$/i',
                $domain, $regs
            )) {
			return  strtolower( $regs['domain'] );
		}

		return false;
    }

    /**
     * Get registered domains.
     *
     * @return  array
     */
    public function get_registered_domains() {
        $settings =  \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $domains  = isset( $settings['external_domain'] ) ? $settings['external_domain'] : [];

        $results = [];

        foreach( $domains as $domain ) {
            $results[] = $this->get_domain( $domain );
        }

        return $results;
    }

    /**
     * Add href target blank for event list
     *
     * @return  void
     */
    public function add_link_script() {
        ?>
            <script>
				jQuery(document).ready(function($){
					var links = $('#eventin-external-event-list').find('a');
                    $(links).attr('target', '_blank');
				});
			</script>
        <?php
    }
}
