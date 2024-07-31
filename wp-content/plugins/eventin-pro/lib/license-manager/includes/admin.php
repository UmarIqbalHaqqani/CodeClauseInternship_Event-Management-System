<?php
/**
 * License Admin Class
 * 
 * @package EtnLicense
 */
namespace Etn\License\Missile;

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
        add_action( 'admin_menu', [ $this, 'register_menu' ], 9999);
        add_action( 'admin_init', [ $this, 'handle_form_submit' ] );
        add_action( 'admin_notices', [ $this, 'add_notice' ] );
    }

    /**
     * Register admin menu.
     *
     * @return void
     */
    public function register_menu() {
        add_submenu_page(
            'etn-events-manager',
            __( 'License', 'eventin-pro' ),
            __( 'License', 'eventin-pro' ),
            'manage_options',
            'etn-license',
            [ $this, 'add_menu_page' ]
        );
    }

    /**
     * Add menu page content.
     *
     * @return void
     */
    public function add_menu_page() {
        $license_key = etn_get_license_key();
        $name        = etn_get_name();
        $email       = etn_get_email(); 
        
        include_once( \Wpeventin::plugin_dir() . "templates/layout/header.php" );
        ?>
        <div class="license-wrap">
            <?php if ( etn_is_valid_license() ): ?>
                <div class="attr-tab-content">
                    <h2 class="etn-license-title">
                        <?php esc_html_e( 'Your license is activated', 'eventin-pro' ); ?>
                    </h2>
                    <p><?php esc_html_e( 'Congratulations! Your license is now activated, enjoy full access to the Eventin\'s features and services.', 'eventin-pro' ); ?></p>

                    <form action="" method="POST">
                        <input type="hidden"  id="name"  class="attr-form-control" name="name" value="<?php echo esc_attr( $name ); ?>" placeholder="<?php esc_attr_e( 'Enter your name', 'eventin-pro' ); ?>" required />
                        <input type="hidden" id="email" class="attr-form-control" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php esc_attr_e( 'Enter your email', 'eventin-pro' ); ?>" required />
                        <input type="hidden" id="license_key" class="attr-form-control" name="license_key" placeholder="<?php esc_attr_e( 'Enter your license key', 'eventin-pro' ); ?>" value="<?php echo esc_attr( $license_key ); ?>" required />
                        <?php 
                            wp_nonce_field( 'etn_license_activation', 'etn_license_activation_nonce' );
                            submit_button( __('Deactivate License', 'eventin-pro'), 'delete button-primary', 'etn-deactive' ); 
                        ?>
                    </form>
                </div>
            <?php else: ?>
                <div class="attr-tab-content">
                    <h2 class="etn-license-title">
                        <?php esc_html_e( 'License Activation', 'eventin-pro' ); ?>
                    </h2>
                    <div class="etn-license-content">
                        <ul class="etn-license-link">
                            <li><?php echo esc_html__("If you don", "eventin-pro"); ?>&#039;<?php echo esc_html__("t yet have a license key, get ", "eventin-pro"); ?><a href="https://themewinter.com/eventin" target="_blank"><?php echo esc_html__("Eventin Pro", "eventin-pro"); ?></a><?php echo esc_html__(" now.", "eventin-pro");?></li>
                            <li><?php echo esc_html__( "Log in to your ", "eventin-pro" ); ?><a href="https://themewinter.com/purchase-history/" target="_blank"><?php echo esc_html__("Themewinter account", "eventin-pro"); ?></a><?php echo esc_html__(" to get your license key.", "eventin-pro");?></li>
                            <li><?php echo esc_html__("Copy the Eventin license key from your account and paste it below.", "eventin-pro");?></li>
                            <li><?php echo esc_html__("Follow the ", "eventin-pro");?> 
                                <a href="https://support.themewinter.com/docs/plugins/plugin-docs/general-settings-eventin/license/" target="_blank"><?php echo esc_html__("Official Documentation", "eventin-pro"); ?></a>
                                <?php echo esc_html__("for details ", "eventin-pro");?> 
                            </li>
                        </ul>
                    </div>
                    
                    <h3 class="etn-license-title"><?php echo esc_html__("Your License Key", "eventin-pro");?></h3>
                    <p>
                        <?php echo esc_html__("Enter your license key here, to get auto updates.", "eventin-pro");?>
                    </p>
                    
                    <form action="" method="POST">
                        <div class="form-item">
                            <input type="text" id="name" class="attr-form-control" name="name" value="<?php echo esc_attr( $name ); ?>" placeholder="<?php esc_attr_e( 'Enter your name', 'eventin-pro' ); ?>" required />
                        </div>
                        <div class="form-item">
                            <input type="text" id="email" class="attr-form-control" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php esc_attr_e( 'Enter your email', 'eventin-pro' ); ?>" required />
                        </div>
                        <div class="form-item">
                            <input type="text" id="license_key" class="attr-form-control" name="license_key" placeholder="<?php esc_attr_e( 'Enter your license key', 'eventin-pro' ); ?>" value="<?php echo esc_attr( $license_key ); ?>" required />
                        </div>
                        <?php 
                            wp_nonce_field( 'etn_license_activation', 'etn_license_activation_nonce' );
                            submit_button( __('Activate License', 'eventin-pro') );
                        ?>
                        <p class="attr-alert etn-pb-0 etn-mb-0 etn-pl-0">
                            <?php echo esc_html__("Still can", "eventin-pro");?>&#039;<?php echo esc_html__("t find your license key? ", "eventin-pro");?>
                            <a target="_blank" href="https://themewinter.com/support/"><?php echo esc_html__("Knock us here!", "eventin-pro");?></a>
                        </p>
                    </form>
                </div>
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
        $nonce = isset( $_POST['etn_license_activation_nonce'] ) ? wp_unslash( sanitize_text_field( $_POST['etn_license_activation_nonce'] ) ) : '';
        $name = isset( $_POST['name'] ) ? wp_unslash( sanitize_text_field( $_POST['name'] ) ) : '';
        $email = isset( $_POST['email'] ) ? wp_unslash( sanitize_text_field( $_POST['email'] ) ) : '';
        $license_key = isset( $_POST['license_key'] ) ? wp_unslash( sanitize_text_field( $_POST['license_key'] ) ) : '';

        if ( ! wp_verify_nonce( $nonce, 'etn_license_activation' ) ) {
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

        if ( isset( $_POST['etn-deactive'] ) ) {
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

        $is_valid   = etn_is_valid_license();
        $error      = ! empty( $this->error_messages[ $this->error ] ) ? $this->error_messages[ $this->error ] : $this->error ; 
        $message    = $error ? $error : __( 'Your license is ' . $this->action_type, 'eventin-pro' );
        $notice_class = $error ? 'error' : 'updated';

        
        ?>
        <div id="message" class="notice is-dismissible etn-notice <?php echo esc_attr( $notice_class ) ?>">
            <p><?php esc_html_e( $message, 'eventin-pro' ); ?></p>
            <button type="button" class="notice-dismiss etn-notice-btn">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <script>
            (function($){
                $('.etn-notice-btn').on('click', function(e) {
                    e.preventDefault();
                    $('.etn-notice').remove();
                });
            })(jQuery);
        </script>
        <?php
    }
}

// Instantiate Admin Class.
new Admin();