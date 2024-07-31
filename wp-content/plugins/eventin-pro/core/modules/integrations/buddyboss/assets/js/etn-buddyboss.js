'use strict';
jQuery(document).ready(function ($) {

    // click on event create button
    $('.etn-bb-create-event-button').on('click', function (event) {
        event.preventDefault();
        $("#etn_default_event_list").hide();
        $("#etn_multivendor_form").show();
    });

    // on category change
    $('.etn-bb-sidebar-content .etn-shortcode-select').change(function (event) {
        etn_bp_event_list($(this).val(), null);
    });
    // search on event list
    $('#bp_etn_events_search').keyup(function (event) {
        etn_bp_event_list(null, $(this).val());
    });

    // Reload event sidebar once custom event fires from the React app
    document.addEventListener('eventSidebarAjax', () => {
        etn_bp_event_list(null, $(this).val());
    });


    if ($(".bb-sticky-sidebar").length > 0 && $("#etn_fes_form").length > 0) {
        $(".etn-fes-form-cntt,.etn-fes-form-sdbr,.etn-fes-submit-wide").css("width", "100%")
        $(".etn-fes-form-cntt,.etn-fes-form-sdbr,.etn-fes-submit-wide").css("padding", "0")
    }

    // Eventin assign group 
    $('.etn-assign-group').on('click', function (e) {
        e.preventDefault();

        let eventId = $(this).data('event_id');
        let groupId = $('.etn-bp-groups');

        let data = {
            action: 'post_assign_group',
            group_id: Number(groupId.val()),
            event_id: eventId
        };

        $.ajax({
            url: etn_buddyboss_ajax.assing_group_url,
            method: 'POST',
            dataType: 'json',
            data: JSON.stringify(data),
            success: function (res) {
                console.log(res);
            },
            error: function (error) {
                console.log(error)
            }
        });
		
    });

});
/**
 * Click event on load
 * @param {*} event_id
 * @returns
 */
function etn_bp_events_load(event_id) {
    var loading_class = 'etn-bb-list-loading';
    var active_class = 'etn-bb-list-item-active';
    var main_elem = jQuery('#etn_default_event_list');
    main_elem.addClass(loading_class);

    if (main_elem.css('display') == 'none') {
        main_elem.css("display", "block");
        jQuery("#etn_multivendor_form").css("display", "none");
    }

    jQuery.ajax({
        url: etn_buddyboss_ajax.ajaxurl,
        type: 'POST',
        dataType: 'json',
        beforeSend: function () {
            main_elem.addClass(loading_class);
        },
        data: {
            action: 'etn_bp_event',
            event_id: event_id,
        },
    })
        .done(function (data) {
            $ = jQuery;
            main_elem.removeClass(loading_class);
            jQuery('.events-item').removeClass(active_class);
            jQuery('#event-show-row-' + event_id).addClass(active_class);
            main_elem.html(data.data);

            // eventin assign group
            $('.etn-assign-group').on('click', function (e) {
                e.preventDefault();

                let eventId = $(this).data('event_id');
                let groupId = $('.etn-bp-groups');

                let data = {
                    action: 'post_assign_group',
                    group_id: Number(groupId.val()),
                    event_id: eventId
                };

                $.ajax({
                    url: etn_buddyboss_ajax.assing_group_url,
                    method: 'POST',
                    dataType: 'json',
                    data: JSON.stringify(data),
                    success: function (res) {
                        console.log(res);
                    },
                    error: function (error) {
                        console.log(error)
                    }
                });
                console.log(eventId, Number(groupId.val()));
            });

            return false;
        })
        .fail(function () {
            console.log("error");
        })
    return false;
}

/**
 * Get event list on search, category update
 * @param {*} category 
 * @param {*} query 
 * @returns 
 */
function etn_bp_event_list(category, query) {
    var loading_class = 'etn-bb-list-loading';
    var main_elem = jQuery('#eventss-list');
    main_elem.addClass(loading_class);

    if (main_elem.css('display') == 'none') {
        main_elem.css("display", "block");
        jQuery("#etn_multivendor_form").css("display", "none");
    }

    var status = jQuery('#etn-bp-status_load').val();
    var cat_send = category;
    var query_send = query;
    if (category == null) {
        cat_send = jQuery('.etn-bb-sidebar-content .etn-shortcode-select').val();
    }

    if (query == null) {
        query_send = jQuery('#bp_etn_events_search').val();
    }



    jQuery.ajax({
        url: etn_buddyboss_ajax.ajaxurl,
        type: 'GET',
        dataType: 'json',
        beforeSend: function () {
            main_elem.addClass(loading_class);
        },
        data: {
            'action': 'etn_bp_event_list',
            'category': cat_send,
            'query': query_send,
            'status': status,
            'is_current_user_profile': etn_buddyboss_ajax.is_current_user_profile,
        },
    })
        .done(function (data) {
            main_elem.removeClass(loading_class);
            main_elem.html(data.data);
            var first_child = jQuery("#eventss-list a:first-child div");
            if (first_child.length > 0) {
                first_child.addClass("etn-bb-list-item-active");
                first_child.trigger('click');
            }
        })
        .fail(function () {
            console.log("error");
        })

    return false;
}
