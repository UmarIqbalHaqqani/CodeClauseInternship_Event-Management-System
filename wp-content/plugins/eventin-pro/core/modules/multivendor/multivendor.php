<?php

namespace Etn_Pro\Core\Modules\Multivendor;

use Eventin\Integrations\Zoom\Zoom;
use Eventin\Integrations\Google\GoogleMeet;


defined( 'ABSPATH' ) || die();
class Multivendor {
	use \Etn_Pro\Traits\Singleton;

	public function init() {

		$this->add_dashboard_menu();
		$this->add_query_var();
		$this->add_menu_template();
		$this->show_event_listing_on_store();

		$this->enqueue_scripts();

		add_action( 'etn_cart_multivendor_products_modal', [ $this, 'cart_multivendor_products_modal_content' ] );

		add_filter( 'dokan_add_new_product_redirect', [ $this, 'add_edit_redirection' ], 10, 2 );

		add_action( 'template_redirect', array( $this, 'handle_delete_product' ) );

		// in cart page, compare cart item with stock.
		add_action( 'woocommerce_before_cart', [ $this, 'before_cart_check_stock' ] );
		// before checkout, compare cart item with stock.
		add_action( 'woocommerce_before_checkout_form', [ $this, 'before_checkout_check_stock' ], 9 );
		// before place order, compare cart item with stock.
		add_action( 'woocommerce_after_checkout_validation', [ $this, 'before_submit_order_check_stock' ], 10, 2 );

		// before adding to cart, compare cart item with stock.
		add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'validate_add_to_cart_item' ], 10, 5 );

		add_action( 'wp_ajax_add_to_cart_validation', [ $this, 'add_to_cart_validation' ] );
		add_action( 'wp_ajax_nopriv_add_to_cart_validation', [ $this, 'add_to_cart_validation' ] );

        add_filter( 'dokan_product_row_actions', [ $this, 'add_event_attendee_list' ], 10, 2 );

	}

	public function handle_delete_product() {
		$product_id = isset( $_GET['product_id'] ) ? (int) wp_unslash( $_GET['product_id'] ) : 0;
		if ( !is_admin() && ( isset( $_GET['action'] ) &&  'dokan-delete-product' == $_GET['action'] )
		&&  0 !== $product_id && 'etn' == get_post_type( $product_id )  ) {
			// delete event.
			dokan()->product->delete( $product_id, true );
			// re-direct to event page.
			wp_redirect( dokan_get_navigation_url( 'eventin/vendor_event' ) . '?message=event_deleted' );
			exit;
		}

	}

	public function add_edit_redirection ( $redirect_url, $product_id ) {

			if ( 'etn' == get_post_type( $product_id ) ) {
				$redirect_url = dokan_get_navigation_url( 'eventin/vendor_event' );
			}

			return $redirect_url;
	}

		/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		add_action( 'wp_enqueue_scripts', array( $this , 'js_css_public' ) );
	}


		/** Frontend scripts. */
		public function js_css_public() {
			
			
			// Main script of multivendor script and js
			if ( dokan_is_seller_dashboard() ) {
				wp_enqueue_media();
				wp_enqueue_style('etn-frontend-submission');
				wp_enqueue_style('etn-version-four');
				wp_enqueue_script('etn-frontend-submission');
			}

			wp_enqueue_script( 'etn-multivendor-public', ETN_PRO_CORE . 'modules/multivendor/assets/js/etn-multivendor.js', ['jquery', 'etn-packages'], \Wpeventin_Pro::version(), false );

			$localized_data = [
				'ajax_url'          => admin_url( 'admin-ajax.php' ),
				'common_err_msg'    => esc_html__( 'Something went wrong, Please try again.', 'eventin-pro' ),
				'add_to_cart_nonce' => wp_create_nonce( 'add_to_cart_nonce_value' ),
				'zoom_connected'	=> Zoom::is_connected(),
				'google_meet_connected' => GoogleMeet::is_connected(),
				'googlemap'		=> etn_get_option('etn_googlemap_api'),
			];

			wp_localize_script( 'etn-multivendor-public', 'localized_multivendor_data', $localized_data );
		}

		public function show_event_listing_on_store(){
				add_action( 'dokan_store_profile_frame_after', [$this, 'show_event_on_vendor_product_list'], 10, 2 );
		}

		public function add_menu_template() {
				// load setting template
				add_action( 'dokan_load_custom_template', [$this, 'multivendor_load_menu_template'] );
		}

		public function add_query_var() {
				add_filter( 'dokan_query_var_filter', [$this, 'multivendor_load_vendor_cafe_menu'] );
		}

		public function add_dashboard_menu() {
				add_filter( 'dokan_get_dashboard_nav', [$this, 'multivendor_add_vendor_event_menu'] );
		}

		public function show_event_on_vendor_product_list($store_user, $store_info){

				$vendor_id = $store_user->data->ID;
				$post_statuses  = apply_filters( 'eventin_dokan_event_listing_post_statuses_front', [ 'publish' ] );
				$args = array(
						'post_type'      => 'etn',
						'posts_per_page' => -1,
						'author'         => $vendor_id,
						'post_status'    => $post_statuses,
				);
				$vendor_events = get_posts($args);

				if( is_array( $vendor_events ) && !empty( $vendor_events ) && file_exists(\Wpeventin_Pro::core_dir() . 'modules/multivendor/template/store-event-listing.php')){
						require_once \Wpeventin_Pro::core_dir() . 'modules/multivendor/template/store-event-listing.php';
				}

		}

		/**
		 * Add query variable
		 */
		public function multivendor_load_vendor_cafe_menu( $query_vars ) {
				$query_vars['eventin'] = 'eventin';

				return $query_vars;
		}

		/**
		 * Add event menu
		 */
		public function multivendor_add_vendor_event_menu( $urls ) {

				$urls['eventin'] = [
						'title' => esc_html__( 'Event', 'eventin-pro' ),
						'icon'  => '',
						'url'   => dokan_get_navigation_url( 'eventin/vendor_event' ),
						'pos'   => 51,
				];

				return $urls;
		}

		/**
		 * Load cafe  template in dokan vendor settings
		 *
		 * @param [type] $query_vars
		 * @return void
		 */
		public function multivendor_load_menu_template( $query_vars ) {
				$user_id = get_current_user_id();
				if ( isset( $query_vars['eventin'] ) && 'vendor_event' === $query_vars['eventin'] ) {

						if ( !current_user_can( 'dokan_view_store_settings_menu' ) ) {

							dokan_get_template_part( 'global/dokan-error', '', [
									'deleted' => false,
									'message' => __( 'You have no permission to view this page', 'eventin-pro' ),
							] );

						} else if( file_exists( \Wpeventin_Pro::core_dir() . 'modules/multivendor/template/event-list.php' ) ){
							require_once \Wpeventin_Pro::core_dir() . 'modules/multivendor/template/event-list.php';
						}

				}

				if ( isset( $query_vars['eventin'] ) && 'create_event' === $query_vars['eventin'] ) {

						if ( !current_user_can( 'dokan_view_store_settings_menu' ) ) {

								dokan_get_template_part( 'global/dokan-error', '', [
										'deleted' => false,
										'message' => __( 'You have no permission to view this page', 'eventin-pro' ),
								] );

						} else if( file_exists( \Wpeventin_Pro::core_dir() . 'modules/multivendor/template/event-create.php') ) {
								require_once \Wpeventin_Pro::core_dir() . 'modules/multivendor/template/event-create.php';
						}

				}

            if ( isset( $query_vars['eventin'] ) && 'attendees' === $query_vars['eventin'] ) {
                if ( ! current_user_can( 'dokan_view_store_settings_menu' ) ) {

                    dokan_get_template_part( 'global/dokan-error', '', [
                        'deleted' => false,
                        'message' => __( 'You have no permission to view this page', 'eventin-pro' ),
                    ] );
                

                } else if( file_exists( \Wpeventin_Pro::core_dir() . 'modules/multivendor/template/attendee-list.php') ) {
                    require_once \Wpeventin_Pro::core_dir() . 'modules/multivendor/template/attendee-list.php';
                }
            }

		}

		/**
		 * modal content 
		 *
		 * @return void
		 */
		public function cart_multivendor_products_modal_content() {
				?>
<div class="etn_multivendor_modal hide_field">
    <div class="modal-content">
        <p>
            <?php echo esc_html__( "Sorry, Your cart contains products from another vendor. Multiple vendor products in same transaction currently not available. Do you want to remove other vendor prodcuts from cart and add this product?", "eventin-pro" ); ?>
        </p>
        <button
            class="etn_discard_cart_yes etn-btn etn-btn-primary"><?php echo esc_html__( "Yes", "eventin-pro" );?></button>
        <button
            class="etn_discard_cart_no etn-btn etn-btn-primary"><?php echo esc_html__( "No", "eventin-pro" );?></button>
    </div>
</div>
<?php
		}
		
		/**
		 * in cart page, compare cart item with stock
		 */
		public function before_cart_check_stock() {
				$error_messages = [];
				
				if ( $this->check_multiple_vendor_exists() ) {
						// $error_msg        = 'before cart';
						$error_msg        = esc_html__( 'Sorry, In cart you have products from multiple vendors. Multiple vendor products in same transaction currently not available.' , 'eventin-pro' );
						$error_messages[] = $error_msg;
				}

				if ( !empty( $error_messages ) ) {
						$this->print_err_messages( $error_messages );
				}
		}
		
		/**
		 * in checkout page, compare cart item with stock
		 */
		public function before_checkout_check_stock() {
				$error_messages = [];

				if ( $this->check_multiple_vendor_exists() ) {
						// $error_msg        = 'before checkout';
						$error_msg        = esc_html__( 'Sorry, In cart you have products from multiple vendors. Multiple vendor products in same transaction currently not available.' , 'eventin-pro' );
						$error_messages[] = $error_msg;
				}

				if ( !empty( $error_messages ) ) {
						$this->print_err_messages( $error_messages );

						$cart_url = wc_get_cart_url();
						?>
<a class="button wc-backward"
    href="<?php echo esc_url( $cart_url ); ?>"><?php echo esc_html__( 'Return to cart', 'eventin-pro' ); ?></a>

<?php
						die();
				}
		}

		/**
		 * in checkout page, when click place order button: final chance to compare cart item with stock
		 */
		public function before_submit_order_check_stock( $fields, $errors ) {
				$error_messages = [];

				if ( $this->check_multiple_vendor_exists() ) {
						// $error_msg        = 'before place order';
						$error_msg        = esc_html__( 'Sorry, In cart you have products from multiple vendors. Multiple vendor products in same transaction currently not available.' , 'eventin-pro' );
						$error_messages[] = $error_msg;
				}

				if ( !empty( $error_messages ) ) {
						$this->print_err_messages( $error_messages, $errors );
				}
		}

		/**
		 * validate cart item product's vendor id with attempted product vendor id
		 *
		 * @param [bool] $passed
		 * @param [int] $product_id
		 * @param [int] $qty
		 * @param string $variation_id
		 * @param string $variations
		 * @return bool
		 */
		public function validate_add_to_cart_item( $validation_passed, $product_id, $qty, $variation_id = '', $variations= '' ) {
				if ( !empty( $product_id ) && get_post_type( $product_id ) == 'etn' ) {
						$proceed_to_go_next = true;

						if ( !WC()->cart->is_empty() ) {
								$vendor_id     = get_post( $product_id )->post_author;
								$cart_contents = WC()->cart->get_cart();

								foreach ( $cart_contents as $cart_item_key => $cart_data ) {
										$already_vendor_id = get_post( $cart_data['product_id'] )->post_author;

										if ( $vendor_id != $already_vendor_id ) {
												$proceed_to_go_next = false;
												break;
										}
								} 
						}
				
						if ( !$proceed_to_go_next ) {
								WC()->cart->empty_cart();
						}
				}

				return $validation_passed;
		}

		/**
		 * update ticket status from attendee dashboard
		 */
		public function add_to_cart_validation() {
				$status_code  = 0;
				$messages     = [];
				$content      = [];

				if ( wp_verify_nonce( sanitize_text_field( $_POST['security'] ), 'add_to_cart_nonce_value' ) ) {
						$event_id   = absint( $_POST['event_id'] );
						$product_id = $event_id;

						if ( !empty( $product_id ) && get_post_type( $product_id ) == 'etn' ) {
								$multiple_vendor = false;

								if ( !WC()->cart->is_empty() ) {
										$vendor_id     = get_post( $product_id )->post_author;
										$cart_contents = WC()->cart->get_cart();

										foreach ( $cart_contents as $cart_item_key => $cart_data ) {
												$already_vendor_id = get_post( $cart_data['product_id'] )->post_author;

												if ( $vendor_id != $already_vendor_id ) {
														$multiple_vendor = true;
														$status_code     = 1;
														break;
												}
										} 
								}
						
								if ( $multiple_vendor ) {
										$error_msg  = esc_html__( 'Sorry, You already have added products from another vendor. Multiple vendor products in same transaction currently not available.' , 'eventin-pro' );
										$messages[] = $error_msg;
								}
						}

						$response = [
								'status_code' => $status_code,
								'messages'    => $messages,
								'content'     => $content,
						];

						wp_send_json_success( $response );
						exit();
				} else {
						$messages[] = esc_html__( 'Something went wrong. Try again!', 'eventin-pro' );
				}

				$response = [
						'status_code' => $status_code,
						'messages'    => $messages,
						'content'     => $content,
				];

				wp_send_json_error( $response );
				exit;
		}

		/**
		 * check cart contains products of diffeent vendors
		 *
		 * @return bool
		 */
		public function check_multiple_vendor_exists() {
				$multiple_vendor_exists = false;

				if ( !WC()->cart->is_empty() ) {
						$already_vendor_id = null;

						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_data ) {
								$vendor_id = get_post( $cart_data['product_id'] )->post_author;

								if ( empty( $already_vendor_id ) ) {
										$already_vendor_id = $vendor_id;
								}

								if ( $vendor_id != $already_vendor_id ) {
										$multiple_vendor_exists = true;
										break;
								}
						} 
				}        

				return $multiple_vendor_exists;
		}

		/**
		 * through error messages show error notice
		 *
		 * @param array $error_messages
		 * @return void
		 */
		public function print_err_messages( $error_messages = [], $errors = null ) {
				if ( !empty( $error_messages ) ) {
						wc_clear_notices();

						$final_error_msg = implode( '<br>', $error_messages );
						if ( empty( $errors ) ) {
								wc_print_notice( $final_error_msg, 'error' );
						} else {
								$errors->add( 'validation', $final_error_msg );
						}
				}
		}
    
    /**
     * Add event attendee lists
     *
     * @param   array  $row_actions  Row actions for event list
     * @param   Object  $post  Post type 
     *
     * @return  array
     */
    public function add_event_attendee_list( $row_actions, $post ) {
        if ( 'etn' !== $post->post_type ) {
            return $row_actions;
        }

        $row_actions['attendees'] = [
            'title' =>  __( 'Attendee List', 'eventin-pro' ),
            'url'   =>  esc_url( home_url() . '/dashboard/eventin/attendees?event_id=' . $post->ID ),
            'class' =>  '',
        ];

        return $row_actions;
    }
}