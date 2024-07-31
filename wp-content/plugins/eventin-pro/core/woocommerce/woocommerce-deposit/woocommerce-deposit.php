<?php

namespace Etn_Pro\Core\Woocommerce\Woocommerce_Deposit;

defined('ABSPATH') || exit;

class Woocommerce_Deposit {
    use \Etn\Traits\Singleton;

    public function init(){
        if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Deposits' ) ) {
            add_filter( "etn/metaboxs/etn_metaboxs", [$this, "register_deposit_meta_boxes"] );
            add_action( 'save_post', [$this, 'process_deposit_meta_box_data'], 999 );
            add_action( 'etn_before_add_to_cart_button', array( $this, 'deposits_form_output' ), 9999 );
        }
    }

    /**
     * Add metabox for deposit module
     */
    public function register_deposit_meta_boxes( $existing_boxes ) {
            $existing_boxes['deposit_settings'] = [
                'label'     => esc_html__('Deposit Settings', 'eventin-pro'),
                'instance' => $this,
                'callback'  => 'deposit_item_display',
                'cpt_id'    => 'etn',
            ];
        return $existing_boxes;
    }

    /**
     * Save deposit data
     *
     * @param [type] $post_id
     * @return void
     */
    public function process_deposit_meta_box_data( $post_id ) {

        if ( class_exists( 'WC_Deposits_Plans_Product_Admin' ) ) {
            \WC_Deposits_Plans_Product_Admin::get_instance()->save_product_data( $post_id );
        }

    }

    
    /**
     * Callback function for deposit meta-box
     *
     * @param [type] $post
     * @return void
     */
    public function deposit_item_display( $post ) {
        if( class_exists('WC_Deposits_Plans_Product_Admin') ){
           echo '<div class="etn-deposit-wrap">';
             \WC_Deposits_Plans_Product_Admin::get_instance()->deposit_panels();
           echo '</div>';
        }
    }

	/**
	 * Show deposits form.
	 */
	public function deposits_form_output() {
        global $post;

        if ( 'etn' !== $post->post_type ) {
            return;
        }

        if ( ! class_exists('WC_Deposits') ) {
            return;
        }
        
        // Load the deposit form template
        wp_enqueue_script( 'wc-deposits-frontend' );
        wc_get_template( 'deposit-form.php', array( 'product' => wc_get_product($post->ID) ), 'woocommerce-deposits', WC_DEPOSITS_TEMPLATE_PATH );
    }
}
?>