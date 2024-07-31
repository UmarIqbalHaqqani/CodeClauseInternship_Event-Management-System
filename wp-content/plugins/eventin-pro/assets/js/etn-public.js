'use strict';
jQuery(document).ready(function ($) {

    //Added active class in the eventin menu in dokan leftmenu
    const _pathname = window.location.pathname.toString();
    const create_event = '/eventin/create_event/';
    const vendor_event = '/eventin/vendor_event/';
    if (_pathname.includes(create_event) || _pathname.includes(vendor_event)) {
        $('.dokan-dashboard-menu').find('li.eventin').addClass('active');
    }

    /*================================
        speaker slider
    ===================================*/
    var $scope = $(".speaker_shortcode_slider");
    if ($scope.length > 0) {
        $($scope).each(function () {
            var $this = $(this);
            speaker_sliders_pro($, $this);
        });
    }

    /*================================
        speaker slider
    ===================================*/
    var $scope = $(".event-slider-shortcode");
    event_sliders_pro($, $scope);

    /*================================
    Event accordion
    ===================================*/

    $(".etn-content-item > .etn-accordion-heading").on("click", function (e) {
        e.preventDefault();
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $(this).siblings(".etn-acccordion-contents").slideUp(200);
            $(".etn-content-item > .etn-accordion-heading i")
                .removeClass("etn-minus")
                .addClass("etn-plus");
        } else {
            $(".etn-content-item > .etn-accordion-heading i")
                .removeClass("etn-minus")
                .addClass("etn-plus");
            $(this).find("i").removeClass("etn-plus").addClass("etn-minus");
            $(".etn-content-item > .etn-accordion-heading").removeClass("active");
            $(this).addClass("active");
            $(".etn-acccordion-contents").slideUp(200);
            $(this).siblings(".etn-acccordion-contents").slideDown(200);
        }
    });

    /*================================
      // countdown 
    ===================================*/
    const main_block = $(".count_down_block")
    if (main_block.length) {
        count_down($, main_block);
    }

    /*================================
      // Advanced search toggle
    ===================================*/
    if ($('.etn-filter-icon').length > 0) {
        $('.etn-filter-icon').on('click', function () {
            $(this).parents().find('.etn_event_inline_form_bottom').slideToggle();
        })
    }


    /*================================
       // Certificate generate
    ===================================*/
    const certificateButton = $('.etn-certificate-download');
    certificateButton.on('click', function (e) {
        e.preventDefault();
        etnCreateCertificate($(this));
    });

    /*====================================================
    * // Event bulk attendee field value update script
    * ====================================================*/
    $("#etn_bulk_attendee").on('change', function () {
        const totalVariations = $('.etn-ticket-single-variation-details').length;
        const submitButton = $(".attendee_submit");
        const isChecked = $(this).is(":checked");
        const checkbox = $(this);
        checkbox.prop('checked', isChecked);

        for (let variationIndex = 0; variationIndex < totalVariations; variationIndex++) {
            const totalTicketQuantity = checkbox.data(`total_ticket_quantity`);

            for (let quantityIndex = 0; quantityIndex < totalTicketQuantity; quantityIndex++) {
                const element = quantityIndex + 1;
                const nameElement = $(`#ticket_${variationIndex}_attendee_name_${element}`);
                const emailElement = $(`#ticket_${variationIndex}_attendee_email_${element}`);
                const phoneElement = $(`#ticket_${variationIndex}_attendee_phone_${element}`);
                const fields = [nameElement, emailElement, phoneElement];

                fields.forEach(field => {
                    if (isChecked) {
                        field.removeClass("attendee_error");
                        const fieldType = field.attr('type');
                        if (fieldType === 'email') {
                            emailElement.val(`attendee${element}@test.com`);
                        } else if (fieldType === 'tel') {
                            field.val(`0123456789${element}`);
                        } else {
                            field.val(`Attendee ${element}`);
                        }
                    } else {
                        field.val('').addClass("attendee_error");
                    }
                });
            }
        }
        submitButton.prop('disabled', !isChecked).toggleClass("attendee_submit_disable", !isChecked);
    });
});

/**
 * This function needed to generate certificate image on Safari browser.
 * There is an error in the html2image library that causes the image to be generated incorrectly.
 * Details bug report: https://github.com/bubkoo/html-to-image/issues/361
 * Can be removed after the bug is fixed in the library.
 */
const generateCertificateImage = async (element) => {
    let dataUrl = '';
    const minDataLength = 2000000;
    let i = 0;
    const maxAttempts = 10;
    dataUrl = await htmlToImage.toPng(element);
    while (dataUrl.length < minDataLength && i < maxAttempts) {
        await new Promise((resolve) => {
            setTimeout(() => resolve(null), 300)
        })
        dataUrl = await htmlToImage.toPng(element)
        i += 1
    }

    return dataUrl
};

// certificate generate function
async function etnCreateCertificate(downloadButton) {

    try {
        downloadButton.addClass('loading');

        const element = document.querySelector('.etn-pdf-content');
        const dataUrl = await generateCertificateImage(element);
        const img = new Image();

        img.onload = () => {
            const jsPDF = window.jspdf.jsPDF;
            const doc = new jsPDF({
                orientation: 'p',
                unit: 'px',
                format: 'a4',
            });

            doc.addImage(img, 'PNG', 2, 2, 445, 0);
            doc.save();
            downloadButton.removeClass('loading');
        };

        img.onerror = (error) => {
            console.error('Oops, something went wrong!', error);
            downloadButton.removeClass('loading');
        };

        img.src = dataUrl;

    } catch (error) {
        console.error('Oops, something went wrong!', error);
        downloadButton.removeClass('loading');
    }
}


// Event Countdown Function


function count_down($, $scope) {
    const countdownContainer = $scope.find('.etn-event-countdown-wrap');
    if (countdownContainer.length) {
        $scope.find(".etn-countdown-parent").countdown({
            date: countdownContainer.data('start-date'),
            day: countdownContainer.data('date-texts').day,
            days: countdownContainer.data('date-texts').days,
            hour: countdownContainer.data('date-texts').hr,
            hours: countdownContainer.data('date-texts').hrs,
            minute: countdownContainer.data('date-texts').min,
            minutes: countdownContainer.data('date-texts').mins,
            second: countdownContainer.data('date-texts').sec,
            seconds: countdownContainer.data('date-texts').secs,
            hideOnComplete: true,
            offset: countdownContainer.data('date-texts').offset
        });
    }
}


// print order details
function etn_pro_pirnt_content_area(divContents) {
    "use strict";
    var mywindow = window.open('', 'PRINT', 'height=400,width=800');
    mywindow.document.write(
        '<style type="text/css">' +
        '.woocommerce-column--1, .woocommerce-column--2{display:inline-block; float: none; width: 300px; vertical-align: top;}  .woocommerce-table tr th{text-align:left; width: 300px; }' +
        '</style>');

    var contentToPrint = document.getElementsByClassName(divContents)[0].innerHTML;
    contentToPrint = contentToPrint.split("<div class=\"extra-buttons\">")[0];
    mywindow.document.write('</head><body >');
    mywindow.document.write(contentToPrint);
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    return true;
}

//download pdf
function etn_pro_download_pdf() {
    var contentToPrint = document.getElementsByClassName("woocommerce-order")[0].innerHTML;
    var source = contentToPrint.split("<div class=\"extra-buttons\">")[0];
    var filename = "invoice";
    jQuery('.woocommerce-order').html(source);
    var divToPrint = jQuery('.woocommerce-order')[0];

    // create custom canvas for a better resolution
    var w = 1000;
    var h = 1000;
    var canvas = document.createElement('canvas');
    canvas.width = w * 5;
    canvas.height = h * 5;
    canvas.style.width = w + 'px';
    canvas.style.height = h + 'px';
    var context = canvas.getContext('2d');
    context.scale(5, 5);

    html2canvas(divToPrint, {
        scale: 4,
        dpi: 288,
        onrendered: function (canvas) {
            var data = canvas.toDataURL('image/png', 1);
            var docDefinition = {
                content: [{
                    image: data,
                    width: 500
                }]
            };
            pdfMake.createPdf(docDefinition).download(filename + ".pdf");
        }
    });
    window.setTimeout(function () {
        location.reload()
    }, 500);

}

function getSwiperOption({ count, space, autoplays }) {
    return {
        slidesPerView: count,
        spaceBetween: space,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: autoplays,

        paginationClickable: true,
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            600: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: count,
            }
        }
    }
}

// speaker sliders pro
function speaker_sliders_pro($, $scope) {
    var $container = $scope.find('.etn-speaker-slider');
    var count = $container.data('count');
    var space = $container.data('space');
    var autoplay = $container.data('autoplay');
    var autoplays = (autoplay == 'yes') ? true : false;

    if ($container.length > 0) {
        $($container).each(function (index, element) {
            var mySwiper = new Swiper(element, getSwiperOption({ count, space: 20, autoplays }))
        });
    }
}

// Event sliders pro
function event_sliders_pro($, $scope) {
    var $container = $scope.find('.etn-event-slider');
    var count = $container.data('count');
    var autoplay = $container.data('autoplay');
    var autoplays = (autoplay == 'yes') ? true : false;
    if ($container.length > 0) {
        $($container).each(function (index, element) {
            var mySwiper = new Swiper(element, getSwiperOption({ count, space: 20, autoplays }))
        });
    }
}
