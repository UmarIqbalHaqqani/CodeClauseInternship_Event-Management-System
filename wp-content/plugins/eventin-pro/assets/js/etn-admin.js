jQuery(document).ready(function ($) {
    "use strict";
    // var extra_main_block = $('.attendee_extra_main_block');
    // localize text
    // var warning_message = typeof etn_pro_admin_object !== "undefined" ? etn_pro_admin_object.warning_message : "";
    // var warning_icon = typeof etn_pro_admin_object !== "undefined" ? etn_pro_admin_object.warning_icon : "";
    // var optional = typeof etn_pro_admin_object !== "undefined" ? etn_pro_admin_object.optional : "";
    // var required = typeof etn_pro_admin_object !== "undefined" ? etn_pro_admin_object.required : "";

    if ($('.post-type-etn-attendee').length > 0) {
        $('.wrap .wp-heading-inline').after('<a id="etn_ticket_scanner" class="etn-btn etn-ticket-scanner" href="' + etn_pro_admin_object.ticket_scanner_link + '" target="_blank"> ' + etn_pro_admin_object.ticket_scanner_text + '</a>');
    }


    jQuery(window).trigger('etn.colorPicker');

    $('form#etn-admin-license-form').on('submit', function (e) {
        e.preventDefault();
        var __this = $(this),
            edd_action_type = "activate_license",
            license_key = __this.find("#etn-admin-option-text-etn-license-key").val();
        var successText = "Congratulations! Your product is activated. Refreshing...";
        var failureText = "Invalid Credentials";
        $.ajax({
            url: etn_pro_admin_object.ajax_url,
            type: "POST",
            data: {
                action: 'action_activate_license',
                edd_action_type: edd_action_type,
                license_key: license_key
            },
            success: function (response) {
                if (response == "valid") {
                    var content = "<div class='etn-license-form-result'><p class='attr-alert attr-alert-success'>" + successText + "<\/p><\/div>";
                    location.reload();
                } else {
                    var content = "<div class='etn-license-form-result'><p class='attr-alert attr-alert-warning'>" + failureText + "<\/p><\/div>";
                }
                __this.parents(".etn-license-module-parent").find(".etn-license-result-box").html(content);
            },
            error: function (data) {
            }
        });
    });

    $(".etn-select-marketplace").on("change", function (e) {
        var __this = $(this);
        var selectedVal = __this.val();
        var parentDiv = __this.parents(".etn-license-module-parent");
        if (selectedVal == "codecanyon") {
            parentDiv.find(".etn-marketplace-codecanyon").css("display", "block");
            parentDiv.find(".etn-marketplace-themewinter").css("display", "none");
        } else if (selectedVal == "themewinter") {
            parentDiv.find(".etn-marketplace-codecanyon").css("display", "none");
            parentDiv.find(".etn-marketplace-themewinter").css("display", "block");
        } else {
            parentDiv.find(".etn-marketplace-codecanyon").css("display", "none");
            parentDiv.find(".etn-marketplace-themewinter").css("display", "none");
        }
    });
    $(".etn-select-marketplace").trigger("change");

    $('.etn-revoke-license-text').on('click', function (e) {
        var __this = $(this),
            edd_action_type = "deactivate_license",
            successText = 'License Revoked! Refreshing...',
            failureText = 'Could not revoke license. Please try again!';;
        $.ajax({
            url: etn_pro_admin_object.ajax_url,
            type: "POST",
            data: {
                action: 'action_deactivate_license',
                edd_action_type: edd_action_type
            },
            success: function (response) {
                if (response == 'deactivated') {
                    __this.parents('.etn-license-form-result').find('.attr-alert-success').empty().html(successText);
                    location.reload();
                } else {
                    __this.parents('.etn-license-form-result').find('.attr-alert-success').empty().html(failureText);
                }
            },
            error: function (data) {
            }
        });
    });

    $(".etn-btn-save-marketplace").on("click", function () {
        var __this = $(this);
        var marketPlaceValue = __this.parents(".etn-license-module-parent").find(".etn-select-marketplace").val();
        var successText = "Marketplace Saved";
        var failureText = "Couldn't save marketplace value";
        $.ajax({
            url: etn_pro_admin_object.ajax_url,
            type: "POST",
            data: {
                action: 'save_market_place',
                market_place: marketPlaceValue
            },
            success: function (response) {
                if (response == "valid") {
                    var content = "<div class='etn-license-form-result'><p class='attr-alert attr-alert-success'>" + successText + "<\/p><\/div>";
                } else {
                    var content = "<div class='etn-license-form-result'><p class='attr-alert attr-alert-warning'>" + failureText + "<\/p><\/div>";
                }
                __this.parents(".etn-license-module-parent").find(".etn-marketplace-save-result").html(content);
            },
            error: function (data) {
            }
        });
    });

    $('.etn-set-min-qty').on('change keyup', function () {
        var __this = $(this);
        var min_holder = __this.find("input[type='number']");
        var min_val = parseInt(min_holder.val());
        var max_holder = __this.siblings('.etn-set-max-qty').find("input[type='number']");
        var max_val = parseInt(max_holder.val());
        if ($.isNumeric(max_val) && min_val > max_val) {
            min_holder.val(max_val);
        }
    });

    $('.etn-set-max-qty').on('change keyup', function () {
        var __this = $(this);
        var max_holder = __this.find("input[type='number']");
        var max_val = parseInt(max_holder.val());
        var min_holder = __this.siblings('.etn-set-min-qty').find("input[type='number']");
        var min_val = parseInt(min_holder.val());
        if ($.isNumeric(min_val) && max_val < min_val) {
            max_holder.val(min_val);
        }
    });

    $("#banner_bg_color").wpColorPicker();
    $("#etn_event_calendar_bg").wpColorPicker();
    $("#etn_event_calendar_text_color").wpColorPicker();



    /** onchange location options in the single event */
    $('.etn_event_location_type').change(function () {
        if ($('.etn_event_location_type').val() == 'new_location') {
            $('.etn-existing-items').hide();
            $('.etn-new-items').show();
        } else {
            $('.etn-existing-items').show();
            $('.etn-new-items').hide();
        }
    });

    // Show hide speaker/schedule block on event type change
    // $('.etn_select_speaker_schedule_type').on('change', function () {
    //     if ($('.etn_select_speaker_schedule_type').val() === 'schedule') {
    //         $('.etn-existing-items').hide();
    //         $('.etn-new-items').css('display', 'flex');
    //     } else {
    //         $('.etn-existing-items').css('display', 'flex');
    //         $('.etn-new-items').hide();
    //     }
    // });

    // enable/disable option for stripe hide/show div
    $('#etn_sells_engine_stripe').on('change', function () {
        var _this = $(this);
        if (_this.prop('checked')) {
            $('.stripe-payment-methods').slideDown();
            var _that = $('#sell_tickets');
            if (_that.prop('checked')) {
                _that.prop("checked", false);
                $('.woocommerce-payment-type').slideUp();
            }
        } else {
            $('.stripe-payment-methods').slideUp();
        }
    });
    $('#etn_sells_engine_stripe').trigger('change');

    // enable/disable option for test mode hide/show div
    $('#etn_stripe_test_mode').on('change', function () {
        var _this = $(this);
        if (_this.prop('checked')) {
            $('.live-key-wrapper').slideUp();
            $('.test-key-wrapper').slideDown();
        } else {
            $('.live-key-wrapper').slideDown();
            $('.test-key-wrapper').slideUp();
        }
    });
    $('#etn_stripe_test_mode').trigger('change');

    // Send certificate to attendees
    $('.etn-generate-certificate').on('click', function () {
        var __this = $(this);
        var event_id = __this.data('event-id');
        var status_container = __this.siblings('.etn-generate-certificate-response');

        $.ajax({
            type: "POST",
            url: etn_pro_admin_object.ajax_url,
            dataType: 'json',
            data: {
                event_id: event_id,
                action: 'send_attendee_certificates',
                security: etn_pro_admin_object.certificate_nonce
            },
            beforeSend: function () {
                __this.prop('disabled', true).addClass('etn-ajax-loading');
            },
            complete: function () {
                __this.prop('disabled', false).removeClass('etn-ajax-loading');
            },
            success: function (response) {
                const data = response.data;
                const isSuccess = data.status_code === 200;
                const statusClass = isSuccess ? 'status-success' : 'status-failed';

                status_container
                    .html(data.message)
                    .addClass(statusClass)
                    .removeClass(isSuccess ? 'status-failed' : 'status-success')
                    .css('display', 'block');

                fadeOutStatusContainer(status_container);
            },
            error: function (response) {
                status_container
                    .html(response.data.message)
                    .removeClass('status-success')
                    .addClass('status-failed')
                    .css('display', 'block');

                fadeOutStatusContainer(status_container);
            }
        });
    });

    // Hide status container
    function fadeOutStatusContainer(container) {
        setTimeout(() => {
            container.fadeOut('slow').css('display', 'none');
        }, 2000);
    }



    // Shortcode scripts
    $(document).on('click', '.shortcode-script-btn', function () {
        let scriptParent = $(this).parents('.shortcode-generator-inner');
        let shortcode = scriptParent.find('.etn_include_shortcode').val();
        let scriptName = scriptParent.find('.script-name').val();
        let scriptInput = scriptParent.find('.etn-shortcode-script').val();
        let scriptContainer = $('.etn_copy_scripts');
        let button = $(this)

        if (scriptContainer) {
            scriptContainer.remove();
        }

        if (!shortcode) {
            alert("Please Generate ShortCode First");
            return;
        }

        button.attr("disabled", true);
        button.css("cursor", "wait");

        $.ajax({
            method: 'post',
            url: etn_pro_admin_object.ajax_url,
            data: {
                action: 'eventin_external_script',
                shortcode: shortcode,
                post_name: scriptName
            },
            success: function (res) {
                let script = `<script src="${etn_pro_admin_object.site_url}/eventin-external-script?id=${res.data.id}"></script>`;
                let html = `<div 
                            class="attr-form-group etn-label-item copy_shortcodes etn_copy_scripts" 
                            style="display: block;"
                        >
                            <div class="etn-meta">
                                <textarea 
                                    readonly=""
                                    class="etn-setting-input etn-shortcode-script"
                                    id="events-scripts"
                                >
${script}
                                </textarea>
                        
                                <button 
                                    type="button" 
                                    onclick="copyTextData('events-scripts');" 
                                    class="etn_copy_button etn-btn"
                                >
                                    <span 
                                        class="dashicons dashicons-category"
                                    >
                                    </span>
                                </button>
                            </div>
                        </div>`

                if (!scriptInput) {
                    $(scriptParent).append(html);
                }

                button.removeAttr('disabled');
                button.css('cursor', 'pointer');
            },
            error: function (error) {
                console.log(error);
            }
        });

    });

    // Script generate for single event
    $(document).on('click', '.etn-script-generate-btn', function (e) {
        e.preventDefault();
        let modal = $('.etn-script-generator-modal');
        let modalContainer = $('.etn-script-generator-modal-content');
        let id = $(this).data('id');
        let inputScript = `<script src="${etn_pro_admin_object.site_url}/eventin-external-script?id=${id}"></script>`;

        let html = `<div 
                        class="attr-form-group etn-label-item copy_shortcodes" 
                        style="display: block;"
                    >
                        <div 
                            class="etn-meta"
                            style="display: flex;
                            align-items: center;
                            gap: 16px;"
                        >
                            <textarea 
                                readonly=""
                                class="etn-setting-input etn-shortcode-script"
                                id="events-scripts"
                            >
${inputScript}</textarea>
                    
                            <button 
                                type="button" 
                                onclick="copyTextData('events-scripts');" 
                                class="etn_copy_button etn-btn"
                            >
                                <span 
                                    class="dashicons dashicons-category"
                                >
                                </span>
                            </button>
                        </div>
                    </div>`

        modalContainer.append(html);
        modal.css('display', 'block');
        $('body').css('overflow-y', 'hidden');
    });

    $(document).on('click', '.etn-script-generator-modal-close', function (e) {
        e.preventDefault();
        $('.etn-script-generator-modal').css('display', 'none');
        $('.etn-script-generator-modal-content').text('');
        $('body').css('overflow-y', 'auto');
    });



    $('.etn-set-min-qty').on('change keyup', function () {
        var __this = $(this);
        var min_holder = __this.find("input[type='number']");
        var min_val = parseInt(min_holder.val());
        var max_holder = __this.siblings('.etn-set-max-qty').find("input[type='number']");
        var max_val = parseInt(max_holder.val());
        if ($.isNumeric(max_val) && min_val > max_val) {
            min_holder.val(max_val);
        }
    });

    $('.etn-set-max-qty').on('change keyup', function () {
        var __this = $(this);
        var max_holder = __this.find("input[type='number']");
        var max_val = parseInt(max_holder.val());
        var min_holder = __this.siblings('.etn-set-min-qty').find("input[type='number']");
        var min_val = parseInt(min_holder.val());
        if ($.isNumeric(min_val) && max_val < min_val) {
            max_holder.val(min_val);
        }
    });

    $("#banner_bg_color").wpColorPicker();
    $("#etn_event_calendar_bg").wpColorPicker();
    $("#etn_event_calendar_text_color").wpColorPicker();

    /** groundhogg settings triggered show/hide */

    jQuery('#groundhogg_api').on('change', function () {
        if (jQuery("#groundhogg_api").prop('checked')) {
            jQuery(".groundhogg_block").slideDown('slow');
        } else {
            jQuery(".groundhogg_block").slideUp('slow');
        }
    })
    jQuery('#groundhogg_api').trigger('change');



    /** onchange location options in the single event */
    $('.etn_event_location_type').change(function () {
        if ($('.etn_event_location_type').val() == 'new_location') {
            $('.etn-existing-items').hide();
            $('.etn-new-items').show();
        } else {
            $('.etn-existing-items').show();
            $('.etn-new-items').hide();
        }
    });

    // enable/disable option for stripe hide/show div
    $('#etn_sells_engine_stripe').on('change', function () {
        var _this = $(this);
        if (_this.prop('checked')) {
            $('.stripe-payment-methods').slideDown();
            var _that = $('#sell_tickets');
            if (_that.prop('checked')) {
                _that.prop("checked", false);
                $('.woocommerce-payment-type').slideUp();
            }
        } else {
            $('.stripe-payment-methods').slideUp();
        }
    });
    $('#etn_sells_engine_stripe').trigger('change');

    // enable/disable option for test mode hide/show div
    $('#etn_stripe_test_mode').on('change', function () {
        var _this = $(this);
        if (_this.prop('checked')) {
            $('.live-key-wrapper').slideUp();
            $('.test-key-wrapper').slideDown();
        } else {
            $('.live-key-wrapper').slideDown();
            $('.test-key-wrapper').slideUp();
        }
    });
    $('#etn_stripe_test_mode').trigger('change');

    /**
     *  On change event, get the event details
     */
    $('#etn_attendee_meta').on('change', '.etn_event_id', function (e) {
        let eventId = $(this).select2('val')

        set_event_tickets(eventId)
    })

    /**
     * On change ticket variation set ticket price
     */

    $('#etn_attendee_meta').on('change', '.ticket_name', function (e) {
        let price = $(this).find(':selected').data('price')
        let priceField = $('#ticket_price');
        let _priceField = $('#_ticket_price');
        let etn_total_price = $('#_etn_variation_total_price');
        let etn_total_qty = $('#_etn_variation_total_quantity');

        // Add and remove loading icon on price option
        let ticketVariation = $('.etn-meta-loading');
        ticketVariation.addClass('loading')
        setTimeout(() => {
            ticketVariation.removeClass('loading')
        }, 1000);

        // set price
        priceField.val(price);
        _priceField.val(price);
        etn_total_price.val(price);
        etn_total_qty.val(1);
        $(this).after(
            `<input type="hidden" name="manual_attendee" value="1"/>`
        )

    })

    // Set ticket price.

    let event_id = $('.etn_event_id').select2('val');

    if (event_id > 0) {
        set_event_tickets(event_id)
    }


    function set_event_tickets(event_id) {
        const ticketVariation = $('.etn-meta-loading');
        $.ajax({
            url: etn_pro_admin_object.ajax_url,
            method: 'GET',
            data: {
                event_id: event_id,
                action: 'attendee_event'
            },
            beforeSend: function () {
                ticketVariation.addClass('loading');
            },

            success: function (res) {
                let ticket_option = $('.ticket_name');
                let options = `<option value="">Select</option>`;

                ticketVariation.removeClass('loading');

                if (res.data) {
                    res.data.map(item => {
                        options += `<option value="${item.etn_ticket_name}" data-price=${item.etn_ticket_price}>${item.etn_ticket_name}</option>`
                    })
                }

                ticket_option.html(options)
            },
            error: function (error) {
                console.log(error)
            }
        });
    }
});
