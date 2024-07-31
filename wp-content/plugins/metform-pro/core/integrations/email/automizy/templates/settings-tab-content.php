
<div class="attr-tab-pane attr-fade" id="automizy-tab" role="tabpanel" aria-labelledby="nav-contact-tab">
    <div class="attr-row">
        <div class="attr-col-lg-6">

            <div class="mf-setting-input-group">
                <label for="attr-input-label"
                       class="mf-setting-label mf-setting-label attr-input-label"><?php esc_html_e( 'API Token:', 'metform-pro' ); ?></label>
                <input type="text" name="mf_automizy_api_token"
                       value="<?php echo esc_attr( ( isset( $settings['mf_automizy_api_token'] ) ) ? $settings['mf_automizy_api_token'] : '' ); ?>"
                       class="mf-setting-input mf_automizy_api_token attr-form-control"
                       placeholder="<?php esc_html_e( 'Automizy API token', 'metform-pro' ); ?>">
                <p class="description">
					<?php esc_html_e( 'Enter here your Automizy API token. ', 'metform-pro' ); ?>
                    <a target="__blank" class="mf-setting-btn-link"
                       href="<?php echo esc_url( 'https://app.automizy.com/signin' ); ?>"><?php esc_html_e( 'Get Token.', 'metform-pro' ); ?></a>
                </p>
            </div>

        </div>
    </div>
</div>
