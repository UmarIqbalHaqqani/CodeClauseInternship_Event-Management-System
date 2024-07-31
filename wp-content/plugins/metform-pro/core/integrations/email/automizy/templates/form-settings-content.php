<div class="mf-input-group mf_automizy metform-settings-field-toggler">
    <label class="attr-input-label">
        <input type="checkbox" value="1" name="mf_automizy" class="mf-admin-control-input mf-form-modalinput-automizy">
        <span><?php esc_html_e( 'Automizy:', 'metform-pro' ); ?></span>
    </label>

    <span class='mf-input-help'><?php esc_html_e( 'Integrate Automizy with this form.', 'metform-pro' ); ?><strong><?php esc_html_e( 'The form must have at least one Email widget and it should be required. ', 'metform-pro' ); ?><a
                    target="_blank"
                    href="<?php echo get_dashboard_url() . 'admin.php?page=metform-menu-settings#mf-newsletter_integration'; ?>"><?php esc_html_e( 'Configure Automizy.', 'metform-pro' ); ?></a></strong></span>

</div>

<div class="metform-settings-hidden-field-container"  >


    <div class="mf-input-group  mf_automizy">
        <label for="attr-input-label"
               class="attr-input-label"><?php esc_html_e( 'Automizy contact List:', 'metform-pro' ); ?>
            <span class="dashicons dashicons-update metfrom-btn-refresh-automizy-list"></span></label>


        <select class="attr-form-control automizy-campaign-list mf_automizy_list_id" id="mf_automizy_list_id" name="mf_automizy_list_id">

        </select>
        <span class='mf-input-help'><?php esc_html_e( 'Select Automizy list. ', 'metform-pro' ); ?></span>
    </div>


</div>