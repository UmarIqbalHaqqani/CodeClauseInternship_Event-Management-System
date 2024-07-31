'use strict';
jQuery(document).ready(function ($) {

	// before add to cart check whether differnet vendor products already added in cart
    // if yes, then show alert to chose if s/he discard cart or cancel
    $('.etn-add-to-cart-block').on('click',function(e){
        let current_this       = $(this);
        let multivendor_enable = current_this.attr('data-multivendor_active');
        let validation_checked = current_this.attr('data-validation_checked');

        if( multivendor_enable && validation_checked == '0' ) {
            e.preventDefault();
            current_this.css('cursor', 'wait');
            
            $.ajax({
                type: "POST",
                url: localized_multivendor_data.ajax_url,
                dataType: 'json',
                data: {
                    event_id: current_this.attr('data-event_id'),
                    action: 'add_to_cart_validation',
                    security: localized_multivendor_data.add_to_cart_nonce,
                },
                beforeSend: function () {

                },
                complete: function () {
                    current_this.css('cursor', 'auto');
                },
                success: function (res) {
                    let res_data    = res.data;
                    let status_code = res_data.status_code;
                    let res_content = res_data.content;
                    let msg         = res_data.messages[0];
    
                    if (res.success) {
                        if (status_code) {
                            $(".etn_multivendor_modal").removeClass("hide_field");
                            $("body").addClass("etn_multivendor_popup");
                        } else {
                            current_this.attr('data-validation_checked', 1);
                            current_this.trigger('click');
                        }
                    } else {
                        alert( msg );
                    }
                },
                error: function (res) {
                    alert( localized_multivendor_data.common_err_msg );
                }
            });
        }
    });

    // modal yes/no click, hide modal
    $(".etn_discard_cart_yes, .etn_discard_cart_no").on('click', function (e) {
        $('body').removeClass('etn_multivendor_popup');
        $(".etn_multivendor_modal").addClass("hide_field");

        if( $(this).hasClass("etn_discard_cart_no") ) {
            return false;
        }
    });
});
