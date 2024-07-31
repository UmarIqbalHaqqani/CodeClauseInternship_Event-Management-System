(function ($, elementor) {
    "use strict";

    var etn = {
        init: function () {
            var widgets = {
                'etn-event-slider.default': etn.etn_event_slider,
                'etn-pro-speaker-slider.default': etn.etn_pro_speaker_slider,
                'etn-pro-countdown.default': etn.etn_pro_countdown,
                'etn-event-ticket.default': etn.etn_pro_ticket,
            };
            $.each(widgets, function (widget, callback) {
                elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
            });
        },

        //etn event slider start
        etn_event_slider: function ($scope) {
            event_sliders_pro($, $scope);
        },
        // etn slider end

        //etn speaker slider start
        etn_pro_speaker_slider: function ($scope) {
            speaker_sliders_pro($, $scope);
        },
        // etn slider end

        //etn countdown
        etn_pro_countdown: function ($scope) {
            count_down($, $scope)
        },

        //etn ticket widget start
        etn_pro_ticket: function ($scope) {
            etn_ticket_quantity_update($, $scope);
        },
        // etn ticket widget end
    };
    $(window).on('elementor/frontend/init', etn.init);
}(jQuery, window.elementorFrontend));