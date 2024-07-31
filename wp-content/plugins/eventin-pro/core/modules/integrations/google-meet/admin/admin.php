<?php

    namespace Etn_Pro\Core\Modules\Integrations\Google_Meet\Admin;

    defined( 'ABSPATH' ) || die();

    class Admin {

        use \Etn_Pro\Traits\Singleton;

        public function init() {

            // enqueue scripts
            $this->enqueue_scripts();

            // add_action( 'etn_after_integration_settings_inner_tab_heading', [$this, 'after_integration_settings_google_meet_tabs'], 15 );
            // add_action( 'etn_after_integration_settings', [$this, 'after_integration_settings_google_meet_api'], 20 );

            // RSVP Single page meta boxes
            \Etn_Pro\Core\Modules\Integrations\Google_Meet\Admin\Metaboxs\Metabox::instance()->init();

        }

        /**
         * Enqueue scripts.
         */
        public function enqueue_scripts() {
            add_action( 'admin_enqueue_scripts', array( $this, 'js_css_admin' ) );
        }

        /**
         *  Admin scripts.
         */
        public function js_css_admin() {
            // Main script of google meet script and js
            wp_enqueue_script( 'etn-googlemeet-admin-js', ETN_PRO_CORE . 'modules/integrations/google-meet/assets/js/etn-googlemeet-admin.js', ['jquery'], \Wpeventin_Pro::version(), false );
        }

        /**
         * Add inner tabs for Google Meet
         *
         */

        function after_integration_settings_google_meet_tabs() {
			?>
			<li>
				<a class="etn-settings-tab-a" data-id="google-meet-options">
					<?php echo esc_html__( 'Google Meet', 'eventin-pro' ); ?>
					<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>
				</a>
			</li>
			<?php
        }

		/*
			* Google Meet integration API key options
			*/

		public function after_integration_settings_google_meet_api() {
			$settings                      = \Etn\Core\Settings\Settings::instance()->get_settings_option();
			$google_meet_client_id         = isset( $settings['google_meet_client_id'] ) ? $settings['google_meet_client_id'] : '';
			$google_meet_client_secret_key = isset( $settings['google_meet_client_secret_key'] ) ? $settings['google_meet_client_secret_key'] : '';
			$redirect_uri                  = site_url( 'eventin-integration/google-auth' );
			$google_auth_url               = 'https://accounts.google.com/o/oauth2/v2/auth';
			$auth_scope                    = 'https://www.googleapis.com/auth/calendar';

			$auth_url = add_query_arg(
				[
					'client_id'     => $google_meet_client_id,
					'scope'         => urlencode_deep( $auth_scope ),
					'redirect_uri'  => $redirect_uri,
					'response_type' => 'code',
					'access_type'   => 'offline',

				], $google_auth_url
			);

			?>
			<div class="etn-settings-tab" id="google-meet-options">
				<div class="google-meet-block">
					<div class="attr-form-group etn-label-item etn-label-top">
						<div class="etn-label">
							<label class="etn-setting-label" for="google_meet_client_id"><?php esc_html_e( 'Client ID', 'eventin-pro' );?></label>
							<div class="etn-desc">
								<?php esc_html_e( 'Please enter Google Meet client ID here.', 'eventin-pro' );?>
							</div>
						</div>
						<div class="etn-meta">
							<div class="etn-secret-key mb-2">
								<input
									type="password"
									class="etn-setting-input attr-form-control"
									name="google_meet_client_id"
									value="<?php echo esc_attr( $google_meet_client_id ); ?>"
									id="google_meet_client_id"
									placeholder="<?php echo esc_attr__( 'Enter client ID', 'eventin-pro' ); ?>"
								/>
								<span><i class="etn-icon etn-eye-slash eye_toggle_click"></i></span>
							</div>
						</div>
					</div>
					<div class="attr-form-group etn-label-item etn-label-top">
						<div class="etn-label">
							<label class="etn-setting-label" for="google_meet_client_secret_key"><?php esc_html_e( 'Client Secret Key', 'eventin-pro' );?></label>
							<div class="etn-desc">
								<?php esc_html_e( 'Please enter Google Meet client secret key.', 'eventin-pro' );?>
							</div>
						</div>
						<div class="etn-meta">
							<div class="etn-secret-key mb-2">
								<input type="password"class="etn-setting-input attr-form-control" name="google_meet_client_secret_key" value="<?php echo esc_attr( $google_meet_client_secret_key ); ?>" id="google_meet_client_secret_key" placeholder="<?php echo esc_attr__( 'Enter client secret key', 'eventin-pro' ); ?>"
								/>
								<span><i class="etn-icon etn-eye-slash eye_toggle_click"></i></span>
							</div>
						</div>
					</div>
					<div class="attr-form-group etn-label-item etn-label-top">
						<div class="etn-label">
							<label class="etn-setting-label" for="google_meet_redirect_url"><?php esc_html_e( 'Authorized redirect URI', 'eventin-pro' );?></label>
							<div class="etn-desc">
								<?php esc_html_e( 'Your redirection will authorize from this URL....', 'eventin-pro' );?>
							</div>
						</div>
						<div class="etn-meta">
							<div class="etn-secret-key mb-2">
								<input type="text" readonly class="etn-setting-input attr-form-control" name="google_meet_redirect_url" value="<?php echo esc_attr( $redirect_uri ); ?>" id="google_meet_redirect_url" placeholder="<?php echo esc_attr__( 'Enter redirect URL', 'eventin-pro' ); ?>"
								/>
							</div>
						</div>
					</div>
					<div class="attr-form-group etn-label-item etn-label-connection etn-label-top">
						<div class="etn-label">
							<label class="etn-setting-label"><?php esc_html_e( 'Authenticate with Google account', 'eventin-pro' );?></label>
							<div class="etn-desc">
								<p>
									<strong>
										<?php esc_html_e('Alert:', 'eventin-pro'); ?>
									</strong>
									<?php esc_html_e( 'Client ID and Client Secret Key must be entered and saved before authenticate.', 'eventin-pro' ); ?>
									<span>
										<?php esc_html_e( 'For more details please check our ', 'eventin-pro' );?>
										<a target="_blank" href="<?php echo esc_url( 'https://support.themewinter.com/docs/plugins/plugin-docs/integration/google-meet' ) ?>">
											<?php esc_html_e( 'documentation', 'eventin-pro' );?>
										</a>
									</span>
								</p>
							</div>
						</div>
						<div class="etn-meta">
							<div class="etn-api-connect-wrap">
								<a href="<?php echo esc_url( $auth_url ); ?>" type="button" class="etn-btn-text google_meet_authentication"><?php echo esc_html__( 'Authenticate', 'eventin-pro' ) ?></a>
								<div class="api-keys-msg">
									<?php esc_html_e( 'Note: Save changes before authenticate.', 'eventin-pro' );?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
        }
    }
