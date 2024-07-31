<?php
/**
 * License Admin Class
 * 
 * @package ThemeLicense
 */
namespace Theme\License;

/**
 * Class Admin
 */
class Admin {
    /**
     * Store notice
     *
     * @var bool
     */
    protected $error = false;

    /**
     * Store type
     *
     * @var string
     */
    protected $action_type;

    /**
     * Store error messages
     *
     * @var array
     */
    public $error_messages = [
        'missing'               => 'License doesn\'t exist',
        'missing_url'           => 'URL not provided',
        'license_not_activable' => 'Attempting to activate a bundle\'s parent license',
        'disabled'              => 'License key revoked',
        'no_activations_left'   => 'No activations left',
        'expired'               => 'License has expired',
        'key_mismatch'          => 'License is not valid for this product',
        'invalid_item_id'       => 'Invalid Item ID',
        'item_name_mismatch'    => 'License is not valid for this product',
    ];

    /**
     * Store update notification.
     *
     * @var bool
     */
    protected $is_updated = false;

    /**
     * Constructor for Admin Class
     *
     * @return  void
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        add_action( 'admin_init', [ $this, 'handle_form_submit' ] );
        add_action( 'admin_init', [ $this, 'redirect_license_page' ] );
        add_action( 'admin_notices', [ $this, 'add_notice' ] );
    }

    /**
     * Register admin menu.
     *
     * @return void
     */
    public function register_menu() {
        add_submenu_page(
            'themes.php',
            __( 'License', 'exhibz' ),
            __( 'License', 'exhibz' ),
            'manage_options',
            'license',
            [ $this, 'add_menu_page' ],
        );
    }

    /**
     * Add menu page content.
     *
     * @return void
     */
    public function add_menu_page() {
        $license_key = theme_get_license_key();
        $name        = theme_get_name();
        $email       = theme_get_email(); 
        ?>
        <div class="license-wrap">
            <?php if ( theme_is_valid_license() ): ?>
                <div class="license-content">
                    <h2 class="license-title">
                        <?php esc_html_e( 'Your license is activated', 'exhibz' ); ?>
                    </h2>
                    <div class="license-instruction">
                        <ul class="license-link">
                            <li><?php echo esc_html__("Install and active all the require plugin Follow the ", "exhibz");?> 
                                <a href="https://support.themewinter.com/docs/themes/docs-category/exhibz/" target="_blank"><?php echo esc_html__("Official Documentation", "exhibz"); ?></a>
                                <?php echo esc_html__("for installing the demo content, accessing the customizer etc. For more details follow the video doc", "exhibz");?> 
                                <a href="https://youtu.be/b8ifo_m0hDY" target="_blank">
                                    <?php echo esc_html__("Video Documentation", "exhibz"); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
                <form action="" method="POST">
                    <input 
                        type="hidden" 
                        id="name" 
                        class="regular-text" 
                        name="name"
                        value="<?php echo esc_attr( $name ); ?>" 
                        placeholder="<?php esc_attr_e( 'Enter your name', 'exhibz' ); ?>" required
                    >
                    <input 
                        type="hidden" 
                        id="email" 
                        class="regular-text" 
                        name="email" 
                        value="<?php echo esc_attr( $email ); ?>"
                        placeholder="<?php esc_attr_e( 'Enter your email', 'exhibz' ); ?>" required
                    >
                    <input 
                        type="hidden" 
                        id="license_key" 
                        class="regular-text" 
                        name="license_key" 
                        placeholder="<?php esc_attr_e( 'Enter your license key', 'exhibz' ); ?>"
                        value="<?php echo esc_attr( $license_key ); ?>"
                        required
                    >
                    <?php 
                        wp_nonce_field( 'theme_license_activation', 'theme_license_activation_nonce' );
                        submit_button( __('Deactivate License', 'exhibz'), 'delete button-primary', 'theme-deactive' ); 
                    ?>
                </form>
                
            <?php else: ?>
            <div class="license-content">
                <h2 class="license-title"><?php esc_html_e( 'License Activation', 'exhibz' ); ?></h2>
                <div class="license-instruction">
                    <ul class="license-link">
                        <li><?php echo esc_html__("Log into your Envato Market account ", "exhibz"); ?></li>
                        <li><?php echo esc_html__( "Hover the mouse over your username at the top of the screen.", "exhibz" ); ?></li>
                        <li><?php echo esc_html__("Click 'Downloads' from the drop-down menu.", "exhibz");?></li>
                        <li><?php echo esc_html__("Click 'License certificate & purchase code' (available as PDF or text file) See the ", "exhibz");?> 
                            <a href="https://youtu.be/srghr25uBgc" target="_blank"><br><?php echo esc_html__("Video Documentation", "exhibz"); ?></a>
                            <?php echo esc_html__("for details ", "exhibz");?> 
                        </li>
                    </ul>
                </div>
                <div class="license-desc">
                    <div class="notice-icon">
                        <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.27148 5.6001V9.80009" stroke="#FF7129" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.536 6.26402V11.736C15.536 12.632 15.056 13.464 14.28 13.92L9.52801 16.664C8.75201 17.112 7.792 17.112 7.008 16.664L2.256 13.92C1.48 13.472 1 12.64 1 11.736V6.26402C1 5.36802 1.48 4.53599 2.256 4.07999L7.008 1.336C7.784 0.888 8.74401 0.888 9.52801 1.336L14.28 4.07999C15.056 4.53599 15.536 5.36002 15.536 6.26402Z" stroke="#FF7129" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.27148 12.3599V12.4399" stroke="#FF7129" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <p>
                        <?php esc_html_e( 'In order to get the regular updates, support, demo content & customizer option you must activate the theme license.', 'exhibz' ); ?>
                    </p>
                </div>
            </div>
            
            <form action="" method="POST">
                <div class="form-item">
                    <input 
                        type="text" 
                        id="name" 
                        class="regular-text" 
                        name="name"
                        value="<?php echo esc_attr( $name ); ?>" 
                        placeholder="<?php esc_attr_e( 'Enter your name', 'exhibz' ); ?>" required
                    />
                </div>
                <div class="form-item">
                    <input 
                        type="text" 
                        id="email" 
                        class="regular-text" 
                        name="email" 
                        value="<?php echo esc_attr( $email ); ?>"
                        placeholder="<?php esc_attr_e( 'Enter your email', 'exhibz' ); ?>" required
                    />
                </div>
                <div class="form-item">
                    <input 
                        type="text" 
                        id="license_key" 
                        class="regular-text" 
                        name="license_key" 
                        placeholder="<?php esc_attr_e( 'Enter your license key', 'exhibz' ); ?>"
                        value="<?php echo esc_attr( $license_key ); ?>"
                        required
                    >
                </div>
                <?php wp_nonce_field( 'theme_license_activation', 'theme_license_activation_nonce' ); ?>
                <?php 
                    submit_button( __('Activate License', 'exhibz') );
                ?>
            </form>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Handle form submission
     *
     * @return void
     */
    public function handle_form_submit() {
        $nonce = isset( $_POST['theme_license_activation_nonce'] ) ? wp_unslash( sanitize_text_field( $_POST['theme_license_activation_nonce'] ) ) : '';
        $name = isset( $_POST['name'] ) ? wp_unslash( sanitize_text_field( $_POST['name'] ) ) : '';
        $email = isset( $_POST['email'] ) ? wp_unslash( sanitize_text_field( $_POST['email'] ) ) : '';
        $license_key = isset( $_POST['license_key'] ) ? wp_unslash( sanitize_text_field( $_POST['license_key'] ) ) : '';

        if ( ! wp_verify_nonce( $nonce, 'theme_license_activation' ) ) {
            return;
        }

        if ( ! $license_key ) {
            return;
        }

        $activator = new License_Activator();
        $args = [
            'name'          => $name,
            'email'         => $email,
            'license_key'   => $license_key,
        ];

        if ( isset( $_POST['theme-deactive'] ) ) {
            // Deactivate license if deactivate request.
            $data = $activator->deactivate_license( $args );
            $this->action_type = 'deactivated';
        } else {
            // Activate license.
            $data = $activator->activate_license( $args );
            $this->action_type = 'activated';
        }

        if ( ! empty( $data['error'] ) ) {
            $this->error = $data['error'];
        }

        // Updated the notice
        $this->is_updated = true;
    }

    /**
     * Add invalid notice
     *
     * @return void
     */
    public function add_notice() {
        if ( ! $this->is_updated ) {
            return;
        }

        $is_valid   = theme_is_valid_license();
        $error      = ! empty( $this->error_messages[ $this->error ] ) ? $this->error_messages[ $this->error ] : $this->error ; 
        $message    = $error ? $error : __( 'Your license is ' . $this->action_type, 'exhibz' );
        $notice_class = $error ? 'error' : 'updated';

        
        ?>
        <div id="message" class="notice is-dismissible theme-notice <?php echo esc_attr( $notice_class ) ?>">
            <p><?php esc_html_e( $message, 'exhibz' ); ?></p>
            <button type="button" class="notice-dismiss theme-notice-btn">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <script>
            (function($){
                $('.theme-notice-btn').on('click', function(e) {
                    e.preventDefault();
                    $('.theme-notice').remove();
                });
            })(jQuery);
        </script>
        <?php
    }

    /**
     * Redirect after theme activated
     *
     * @return  void
     */
    public function redirect_license_page() {
        global $pagenow;

        if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
            wp_redirect(admin_url("themes.php?page=license"));
        }
    }
}

// Instantiate Admin Class.
new Admin();