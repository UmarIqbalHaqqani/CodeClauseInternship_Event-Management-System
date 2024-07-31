<?php
/**
 * Manage admin notice
 *
 * @package EventinPro/Admin
 */
namespace EventinPro\Admin;

class Notice {
    // Add constructor
    public function __construct() {
        add_action( 'admin_notices', [ $this, 'admin_notice' ] );
    }   
    /**
     * Add admin notice
     * if eventin is not acitve add notice to active eventin
     * if eventin pro version is 4.0.0 then add notice to required eventin verion 4.0.0
     * @return void
     */
    public function admin_notice() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $message = '';

        // check if eventin is not acitve
        if ( ! function_exists( 'wpeventin' ) ) {
            $message = sprintf(
                esc_html__( 'Eventin Pro requires %1$sEventin%2$s plugin to be active.', 'eventin-pro' ),
                '<a href="' . esc_url( 'https://wordpress.org/plugins/wp-event-solution/' ) . '">',
                '</a>',
            );
        } elseif ( \Wpeventin_Pro::version() == '4.0.0' && version_compare( \Wpeventin::version(), '4.0.0', '<' ) ) {
            $message = sprintf(
                esc_html__( 'Eventin Pro requires %1$sEventin v%2$s %3$s  or higher to be active.', 'eventin-pro' ),
                '<a href="' . esc_url( 'https://wordpress.org/plugins/wp-event-solution/' ) . '">',
                esc_html( \Wpeventin_Pro::version()),
                '</a>',
            );
        }

        if ( ! empty( $message ) ) {
            ?>
            <div class="notice notice-error">
                <p>
                    <?php echo wp_kses_post( $message ); ?>                
                </p>
            </div>
            <?php
        }
    }  
}
