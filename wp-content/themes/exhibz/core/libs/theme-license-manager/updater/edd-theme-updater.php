<?php
/**
 * Theme updater class.
 *
 * @package EDD Theme Updater
 */
namespace Theme\License;

/**
 * Class EDD Theme Updater
 */
class EDD_Theme_Updater {
	/**
	 * Store Remote api url
	 *
	 * @var string
	 */
    private $remote_api_url;

	/**
	 * Store Requested data
	 *
	 * @var array
	 */
    private $request_data;

	/**
	 * Store Response key
	 *
	 * @var string
	 */
    private $response_key;

	/**
	 * Theme slug
	 *
	 * @var string
	 */
    private $theme_slug;

	/**
	 * Store License key
	 *
	 * @var string
	 */
    private $license_key;

	/**
	 * Store version
	 *
	 * @var string
	 */
    private $version;

	/**
	 * Store author
	 *
	 * @var string
	 */
    private $author;

	/**
	 * Store product id
	 *
	 * @var integer
	 */
	private $item_id;

	/**
	 * Store all strings
	 *
	 * @var array
	 */
    protected $strings = null;
    private $license;
    private $item_name;

	/**
	 * Constructor for EDD Theme Updater
	 *
	 * @param   array $args
	 * @param   array  $strings
	 *
	 * @return  void
	 */
    function __construct( $args = array(), $strings = array() ) {

        $args = wp_parse_args( $args, array(
            'remote_api_url' => 'http://easydigitaldownloads.com',
            'request_data'   => array(),
            'theme_slug'     => get_template(),
            'item_name'      => '',
			'item_id'		 =>	'',
            'license'        => '',
            'version'        => '',
            'author'         => '',
        ) );

        extract( $args );

        $this->license        = $license;
        $this->item_name      = $item_name;
		$this->item_id		  = $item_id;
        $this->version        = $version;
        $this->theme_slug     = sanitize_key( $theme_slug );
        $this->author         = $author;
        $this->remote_api_url = $remote_api_url;
        $this->response_key   = $this->theme_slug . '-update-response';
        $this->strings        = $strings;

        add_filter( 'site_transient_update_themes', array( &$this, 'theme_update_transient' ) );

        add_filter( 'delete_site_transient_update_themes', array( &$this, 'delete_theme_update_transient' ) );

        add_action( 'load-update-core.php', array( &$this, 'delete_theme_update_transient' ) );

        add_action( 'load-themes.php', array( &$this, 'delete_theme_update_transient' ) );

        add_action( 'load-themes.php', array( &$this, 'load_themes_screen' ) );

		add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );
		add_filter( 'http_request_args', array( $this, 'http_request_args' ), 10, 2 );
    }

	/**
	 * Update notice hook
	 *
	 * @return  void
	 */
    function load_themes_screen() {
        add_thickbox();
        add_action( 'admin_notices', array( &$this, 'update_nag' ) );
    }

	/**
	 * Show update notice
	 *
	 * @return  void
	 */
    function update_nag() {
		
        $strings = $this->strings;

        $theme = wp_get_theme( $this->theme_slug );

        $api_response = get_transient( $this->response_key );
		
        if ( false === $api_response ) {
            return;
        }

        $update_url     = wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $this->theme_slug ), 'upgrade-theme_' . $this->theme_slug );
		
        $update_onclick = ' onclick="if ( confirm(\'' . esc_js( $strings['update-notice'] ) . '\') ) {return true;}return false;"';

        if ( version_compare( $this->version, $api_response->new_version, '<' ) ) {

            echo '<div id="update-nag" class="notice updated" style="display: block; margin: 10px 0">';
            printf(
                $strings['update-available'],
                $theme->get( 'Name' ),
                $api_response->new_version,
                '#TB_inline?width=640&amp;inlineId=' . $this->theme_slug . '_changelog',
                $theme->get( 'Name' ),
                $update_url,
                $update_onclick
            );
            echo '</div>';
            echo '<div id="' . $this->theme_slug . '_' . 'changelog" style="display:none;">';
            echo wpautop( $api_response->sections['changelog'] );
            echo '</div>';
        }
    }

	/**
	 * Update theme transient
	 *
	 * @param   Object  $value
	 *
	 * @return  array
	 */
    function theme_update_transient( $value ) {
        $update_data = $this->check_for_update();
        if ( $update_data ) {
            $value->response[$this->theme_slug] = $update_data;
        }
        return $value;
    }

    function delete_theme_update_transient() {
        delete_transient( $this->response_key );
    }

    function check_for_update() {

        $update_data = get_transient( $this->response_key );

        if ( false === $update_data ) {
            $failed = false;

            $api_params = array(
                'edd_action' => 'get_version',
                'license'    => $this->license,
                'item_id'    => $this->item_id,
                'author'     => $this->author,
            );

            $response = wp_remote_post( $this->remote_api_url, array( 'timeout' => 15, 'body' => $api_params ) );

            // Make sure the response was successful
            if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
                $failed = true;
            }

            $update_data = json_decode( wp_remote_retrieve_body( $response ) );

            if ( ! is_object( $update_data ) ) {
                $failed = true;
            }

            // If the response failed, try again in 30 minutes
            if ( $failed ) {
                $data              = new \stdClass;
                $data->new_version = $this->version;
                set_transient( $this->response_key, $data, strtotime( '+30 minutes' ) );
                return false;
            }

            // If the status is 'ok', return the update arguments
            if ( ! $failed ) {
                $update_data->sections = maybe_unserialize( $update_data->sections );
                set_transient( $this->response_key, $update_data, strtotime( '+12 hours' ) );
            }
        }

        if ( version_compare( $this->version, $update_data->new_version, '>=' ) ) {
            return false;
        }

        return (array) $update_data;
    }

	/**
	 * Disable requests to wp.org repository for this theme.
	 *
	 * @since 1.0.0
	 */
	function disable_wporg_request( $r, $url ) {

		// If it's not a theme update request, bail.
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
 			return $r;
 		}

 		// Decode the JSON response
 		$themes = json_decode( $r['body']['themes'] );

 		// Remove the active parent and child themes from the check
 		$parent = get_option( 'template' );
 		$child = get_option( 'stylesheet' );
 		unset( $themes->themes->$parent );
 		unset( $themes->themes->$child );

 		// Encode the updated JSON response
 		$r['body']['themes'] = json_encode( $themes );

 		return $r;
	}

	function http_request_args( $args, $url  ) {
		$verify_ssl = $this->verify_ssl();
        if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
            $args['sslverify'] = $verify_ssl;
        }
        return $args;
	}

	/**
	 * Returns if the SSL of the store should be verified.
	 *
	 * @return  bool
	 */
	function verify_ssl() {
		return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true, $this );
	}
}