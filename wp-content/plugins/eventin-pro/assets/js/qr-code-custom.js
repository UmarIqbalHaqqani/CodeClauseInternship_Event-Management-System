jQuery(document).ready(function ($) {
    const scanAgainBtn = $("#scanAgainBtn");
    const cancelScanBtn = $("#cancelScanBtn");
    const startButton = $('#start-button');
    const ticketConfirmBtn = $('#ticketConfirmBtn');
    const ticketRejectBtn = $('#ticketRejectBtn');
    const backToAtendee = $('#backToAtendee');
    const video = $('#qr-video')[0];
    const msg = $('#scan_response_msg');
    const ticketInfoHeading = $('.ticket-info-heading');
    const attendeeName = $('.etn-scanner-attendee-name');
    const eventName = $('.etn-scanner-event-title');
    const eventTicketName = $('.etn-scanner-event-ticket-type');
    const event_id = $('#attendee-event').data('event');
    let data;

    // ####### Web Cam Scanning #######
    if (video) {
        let scannedResponse = startButton.data('redirect_scanned');
        scannedResponse = scannedResponse && scannedResponse !== '' ? scannedResponse : null;

        const scanner = new QrScanner(video, (result) => { onScanSuccess(result) }, {
            onDecodeError: error => { },
            highlightScanRegion: true,
            highlightCodeOutline: true,
        });

        checkForCamera();

        function checkForCamera() {
            const hasCamera = QrScanner.hasCamera();
            hasCamera.then((res) => {
                if (!res || scannedResponse !== null) {
                    showAlert();
                } else {
                    openToScan();
                }
            }).catch((err) => {
                $('.loader-wrapper').addClass('etn-d-none')
                $('.etn-ticket-modal').removeClass('success-green warning-yellow question-icon').addClass('faild-ticket');
                msg.text(etn_pro_public_object?.scanner_common_msg);
                $('#responseModal').css('display', 'block');
            });
        }

        // when hit the link directly from third party QR scanner 
        function showAlert() {
            $('#ticketConfirmBtn').addClass('etn-d-none')
            $('#ticketRejectBtn').addClass('etn-d-none')

            // attendee_verification_style == 'off' means need to verify manually/two step
            if (etn_pro_public_object?.attendee_verification_style == 'off') {
                two_step_verify();
            }

            if (etn_pro_public_object?.attendee_verification_style == 'on' || etn_pro_public_object?.attendee_verification_style == null) {
                data = window.location.href + '&scanner_active=' + scanner._active + '&action=scanner_verification'
                    + '&security=' + etn_pro_public_object.scanner_nonce;
                if (event_id) {
                    data += `&event_id=${event_id}`;
                }

                validateTicket(data);
            }

        }

        function openToScan() {
            scanner.start().then(() => {
                // TODO localize stop text
                startButton.text('Stop');
                startButton.removeClass('etn-ticket-scan-start-button etn-btn-primary');
                startButton.addClass('etn-ticket-scan-stop-button etn-btn-border');
            });
        }

        function validateTicket(data) {
            $('.loader-wrapper').removeClass('etn-d-none');
            fetch(data).then(async (res) => {
                const parsedData = await res.json();
                const statusCode = parsedData?.data?.status_code;
                const content = parsedData?.data?.content;
                msg.text(parsedData?.data?.messages[0]);

                // Cache selectors
                const $ticketModal = $('.etn-ticket-modal');
                const $loaderWrapper = $('.loader-wrapper');
                const $responseModal = $('#responseModal');

                const unescapeHtml = (html) => $('<div/>').html(html).text();

                switch (statusCode) {
                    case 2:
                        $ticketModal.removeClass('faild-ticket warning-yellow question-icon').addClass('success-green');
                        attendeeName.text(unescapeHtml(content?.name));
                        eventName.text(content?.event_name);
                        eventTicketName.text(content?.ticket_name);
                        ticketInfoHeading.css('display', 'block');
                        break;
                    case 3:
                        const redirectUrl = content?.redirect_url;
                        const parser = new URL(redirectUrl);
                        window.location = parser.href;
                        break;
                    default:
                        $ticketModal.removeClass('success-green faild-ticket question-icon').addClass('warning-yellow');
                }

                // hide loader
                $loaderWrapper.addClass('etn-d-none');
                $responseModal.css('display', 'block');
                stopScan();
            })
                .catch(err => {
                    $('.loader-wrapper').addClass('etn-d-none')
                    $('.etn-ticket-modal').removeClass('success-green warning-yellow question-icon').addClass('faild-ticket');
                    msg.text(etn_pro_public_object?.scanner_common_msg);
                    $('#responseModal').css('display', 'block');
                });
        }

        const onScanSuccess = (result) => {
            $('#ticketConfirmBtn').addClass('etn-d-none')
            $('#ticketRejectBtn').addClass('etn-d-none')

            data = result.data + '&scanner_active=' + scanner._active
                + '&action=scanner_verification'
                + '&security=' + etn_pro_public_object.scanner_nonce;

            if (event_id) {
                data += `&event_id=${event_id}`
            }

            data = data.toString();
            if (result && result.data && result?.data !== '') {
                // first need to hit api for ticket data, then after confirm btn click hit validateTicket()...
                if (etn_pro_public_object?.attendee_verification_style == 'off') {
                    two_step_verify();
                }

                if (etn_pro_public_object?.attendee_verification_style == 'on' || etn_pro_public_object?.attendee_verification_style == null) {
                    validateTicket(data);
                }

                stopScan();
            }
        }


        // two step verification of ticket
        if (ticketConfirmBtn && ticketRejectBtn) {

            ticketConfirmBtn.click(() => {
                ticketAcceptOrReject();
                // if(scannedResponse){
                //     alert_after_confirm_modal();
                // }else{
                validateTicket(data);
                scanAgain();
                // }
            });
            ticketRejectBtn.click(() => {
                ticketAcceptOrReject();
                scanAgain();
            });
        }

        function ticketAcceptOrReject() {
            msg.text('');
            $('#ticketConfirmBtn').addClass('etn-d-none')
            $('#ticketRejectBtn').addClass('etn-d-none')
            $('#scanAgainBtn').removeClass('etn-d-none');
            $('#cancelScanBtn').removeClass('etn-d-none');
        }



        // for debugging
        window.scanner = scanner;

        startButton.click(() => {
            //TODO translate Stop/Start`
            if (startButton.html() == 'Stop') {
                startButton.html('Start');
                startButton.removeClass('etn-ticket-scan-stop-button etn-btn-border').addClass('etn-ticket-scan-start-button etn-btn-primary');
                backToAtendee.removeClass('scan-cancel-btn etn-btn etn-btn-primary').addClass('scan-cancel-btn etn-btn etn-btn-border');
                stopScan()
            }
            else {
                startButton.html('Stop');
                startButton.removeClass('etn-ticket-scan-start-button etn-btn-primary').addClass('etn-ticket-scan-stop-button etn-btn-border');
                backToAtendee.removeClass('scan-cancel-btn etn-btn etn-btn-border').addClass('scan-cancel-btn etn-btn etn-btn-primary');
                startScan()
            }
        });
        scanAgainBtn.click(scanAgain);
        cancelScanBtn.click(cancelScan);

        function startScan() {
            checkForCamera();
        }

        function stopScan() {
            scanner.stop();
        }

        function scanAgain() {
            scannedResponse = null;
            $('#responseModal').css('display', 'none');
            checkForCamera();

        }

        function cancelScan() {
            $('#responseModal').css('display', 'none');
        }

        function two_step_verify() {
            $('.loader-wrapper').addClass('etn-d-none')
            const _url = data + '&ticket_info=' + '1';
            if (scannedResponse && scannedResponse !== '') {
                const { content } = scannedResponse;
                directHitTwoStep(content);
                data = window.location.href + '&scanner_active=' + scanner._active + '&action=scanner_verification'
                    + '&security=' + etn_pro_public_object.scanner_nonce;
            } else {

                fetch(_url).then(async (res) => {
                    const { data } = await res.json();
                    const { content } = data;

                    directHitTwoStep(content)
                }).catch(err => {
                    $('.loader-wrapper').addClass('etn-d-none')
                    $('.etn-ticket-modal').removeClass('success-green warning-yellow question-icon').addClass('faild-ticket');
                    msg.text(etn_pro_public_object?.scanner_common_msg);
                    $('#responseModal').css('display', 'block');
                })
            }

        }

        function alert_after_confirm_modal() {
            const statusCode = scannedResponse.status_code;
            msg.text(scannedResponse.messages[0]);

            if (statusCode == 2) {
                $('.etn-ticket-modal').removeClass('faild-ticket warning-yellow question-icon').addClass('success-green');
            } else if (statusCode == 3) {
                const redirectUrl = scannedResponse.content.redirect_url;
                const parser = new URL(redirectUrl);
                window.location = parser.href;
            } else {
                $('.etn-ticket-modal').removeClass('success-green faild-ticket question-icon').addClass('warning-yellow');
            }
            // hide loader
            $('.loader-wrapper').addClass('etn-d-none')
            $('#responseModal').css('display', 'block');
            stopScan();
        }

    }

    function directHitTwoStep(content) {
        let message = `<div class="scanner-ticket-info"><p class="scaner-ticket-title">${content.event_name}</p>`;
        message += `<div class="scaner-ticket-atendee-name">${content.attendee_name}</div>`;
        message += `<div class="scaner-ticket-type"> ${content.ticket_name}</div></div>`;
        message += `<div class='scanner-ticket-msg'>${content.ticket_Message}</div>`;



        $('#ticketConfirmBtn').removeClass('etn-d-none')
        $('#ticketRejectBtn').removeClass('etn-d-none')
        $('#scanAgainBtn').addClass('etn-d-none');
        $('#cancelScanBtn').addClass('etn-d-none');

        $('.etn-ticket-modal').removeClass('success-green faild-ticket warning-yellow').addClass('question-icon');
        msg.html(message);
        $('#responseModal').css('display', 'block');
    }

    function generateQrCode() {
        const qrImg = document.getElementById("qrImage");
        const id = document.getElementById('ticketUnqId');
        const QRUrl = id?.dataset?.ticketverifyurl;

        if (QRCode) {
            QRCode.toDataURL(QRUrl, function (err, url) {
                if (!err) {
                    document.getElementById("qrImage").src = url;
                } else {
                    jQuery(qrImg).before('Could not generate QR!').remove();
                }
            });
        } else {
            jQuery(qrImg).before('Could not generate QR!').remove();
        }

    }
    // To generate QR code
    generateQrCode();

})

