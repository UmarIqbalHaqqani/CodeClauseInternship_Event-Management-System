<?php
wp_head();
// Get event id from url
$event_id = isset( $_GET['event_id'] ) ? intval( $_GET['event_id'] ) : 0;

// Include QR Code related scripts when pro plugin is activated
wp_enqueue_script( 'etn-qr-code' );
wp_enqueue_script( 'etn-qr-code-scanner' );
wp_enqueue_script( 'etn-qr-code-custom' );
wp_enqueue_style( 'etn-ticket-markup' );
wp_enqueue_style( 'etn-public-css' );

?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- This span is used only to get event id from qr-code-custom.js Don't change this -->
    <span id="attendee-event" data-event="<?php echo esc_attr( $event_id ); ?>"></span>
    <div id="video-container">
        <video id="qr-video"></video>
    </div>
    <div class="loader-wrapper etn-d-none">
        <img src='<?php echo esc_url( \Wpeventin::assets_url() . 'images/loader.gif' ); ?>' alt="Loader"/>
    </div>

    <!-- Response modal -->
    <div id="responseModal" class="etn-ticket-modal">
        <div class="etn-ticket-modal-content">
            <div class="ticket-popup-container">
                <span class='success-icon'>
                    <svg class="etn-scan-svg" viewBox="0 0 224 224" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#filter0_d_1901_446)">
                                    <circle cx="112" cy="97" r="52" fill="#09CB4B"/>
                            </g>
                            <path d="M117.302 116H106.698C97.1005 116 93 111.9 93 102.302V91.6977C93 82.1005 97.1005 78 106.698 78H117.302C126.9 78 131 82.1005 131 91.6977V102.302C131 111.9 126.9 116 117.302 116ZM106.698 80.6512C98.5498 80.6512 95.6512 83.5498 95.6512 91.6977V102.302C95.6512 110.45 98.5498 113.349 106.698 113.349H117.302C125.45 113.349 128.349 110.45 128.349 102.302V91.6977C128.349 83.5498 125.45 80.6512 117.302 80.6512H106.698Z"
                                    fill="white"/>
                            <path d="M109.489 103.327C109.136 103.327 108.8 103.186 108.552 102.938L103.55 97.9366C103.038 97.4241 103.038 96.5757 103.55 96.0631C104.063 95.5506 104.911 95.5506 105.424 96.0631L109.489 100.128L118.574 91.0436C119.086 90.531 119.935 90.531 120.447 91.0436C120.96 91.5562 120.96 92.4045 120.447 92.9171L110.426 102.938C110.178 103.186 109.843 103.327 109.489 103.327Z"
                                    fill="white"/>
                            <defs>
                                    <filter id="filter0_d_1901_446" x="0" y="0" width="224"
                                            height="224" filterUnits="userSpaceOnUse"
                                            color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                            result="hardAlpha"/>
                                                    <feOffset dy="15"/>
                                                    <feGaussianBlur stdDeviation="30"/>
                                                    <feComposite in2="hardAlpha" operator="out"/>
                                            <feColorMatrix type="matrix"
                                                            values="0 0 0 0 0.0352941 0 0 0 0 0.796078 0 0 0 0 0.294118 0 0 0 0.25 0"/>
                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                        result="effect1_dropShadow_1901_446"/>
                                            <feBlend mode="normal" in="SourceGraphic"
                                                        in2="effect1_dropShadow_1901_446"
                                                        result="shape"/>
                                    </filter>
                            </defs>
                    </svg>
				</span>
                <span class='warning-icon'>
                    <svg class="etn-scan-svg" viewBox="0 0 224 224" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#filter0_d_1901_443)">
                                    <circle cx="112" cy="97" r="52" fill="#FBAB34"/>
                            </g>
                            <path d="M112.113 101.143C111.347 101.143 110.711 100.507 110.711 99.7403V90.3899C110.711 89.6231 111.347 88.9873 112.113 88.9873C112.88 88.9873 113.516 89.6231 113.516 90.3899V99.7403C113.516 100.507 112.88 101.143 112.113 101.143Z"
                                    fill="white"/>
                            <path d="M112.114 107.221C112.002 107.221 111.871 107.202 111.74 107.184C111.628 107.165 111.516 107.127 111.404 107.071C111.291 107.034 111.179 106.978 111.067 106.903C110.973 106.828 110.88 106.753 110.786 106.679C110.45 106.323 110.244 105.837 110.244 105.351C110.244 104.865 110.45 104.378 110.786 104.023C110.88 103.948 110.973 103.873 111.067 103.799C111.179 103.724 111.291 103.668 111.404 103.63C111.516 103.574 111.628 103.537 111.74 103.518C111.983 103.462 112.245 103.462 112.47 103.518C112.6 103.537 112.713 103.574 112.825 103.63C112.937 103.668 113.049 103.724 113.161 103.799C113.255 103.873 113.348 103.948 113.442 104.023C113.779 104.378 113.984 104.865 113.984 105.351C113.984 105.837 113.779 106.323 113.442 106.679C113.348 106.753 113.255 106.828 113.161 106.903C113.049 106.978 112.937 107.034 112.825 107.071C112.713 107.127 112.6 107.165 112.47 107.184C112.357 107.202 112.226 107.221 112.114 107.221Z"
                                    fill="white"/>
                            <path d="M123.448 115H100.782C97.1358 115 94.3494 113.672 92.9282 111.279C91.5256 108.885 91.7126 105.799 93.4892 102.601L104.822 82.2175C106.692 78.8514 109.273 77 112.115 77C114.958 77 117.538 78.8514 119.408 82.2175L130.741 102.62C132.518 105.818 132.723 108.885 131.302 111.297C129.881 113.672 127.095 115 123.448 115ZM112.115 79.8051C110.357 79.8051 108.637 81.1516 107.272 83.5827L95.9577 103.985C94.686 106.267 94.4803 108.361 95.3593 109.876C96.2382 111.391 98.1831 112.214 100.801 112.214H123.467C126.085 112.214 128.011 111.391 128.908 109.876C129.806 108.361 129.582 106.285 128.31 103.985L116.959 83.5827C115.594 81.1516 113.873 79.8051 112.115 79.8051Z"
                                    fill="white"/>
                            <defs>
                                    <filter id="filter0_d_1901_443" x="0" y="0" width="224"
                                            height="224" filterUnits="userSpaceOnUse"
                                            color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                            result="hardAlpha"/>
                                                    <feOffset dy="15"/>
                                                    <feGaussianBlur stdDeviation="30"/>
                                                    <feComposite in2="hardAlpha" operator="out"/>
                                            <feColorMatrix type="matrix"
                                                            values="0 0 0 0 0.960784 0 0 0 0 0.760784 0 0 0 0 0.313726 0 0 0 0.5 0"/>
                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                        result="effect1_dropShadow_1901_443"/>
                                            <feBlend mode="normal" in="SourceGraphic"
                                                        in2="effect1_dropShadow_1901_443"
                                                        result="shape"/>
                                    </filter>
                            </defs>
                    </svg>
                </span>
                <span class='danger-icon'>
                    <svg class="etn-scan-svg" viewBox="0 0 224 224" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#filter0_d_1901_445)">
                                    <circle cx="112" cy="97" r="52" fill="#F33636"/>
                            </g>
                            <path d="M106.997 103.328C106.661 103.328 106.325 103.204 106.06 102.939C105.548 102.426 105.548 101.578 106.06 101.065L116.064 91.0617C116.576 90.5491 117.425 90.5491 117.937 91.0617C118.45 91.5742 118.45 92.4226 117.937 92.9352L107.934 102.939C107.686 103.204 107.333 103.328 106.997 103.328Z"
                                    fill="white"/>
                            <path d="M117.001 103.328C116.665 103.328 116.329 103.204 116.064 102.939L106.06 92.9352C105.548 92.4226 105.548 91.5742 106.06 91.0617C106.573 90.5491 107.421 90.5491 107.934 91.0617L117.937 101.065C118.45 101.578 118.45 102.426 117.937 102.939C117.672 103.204 117.336 103.328 117.001 103.328Z"
                                    fill="white"/>
                            <path d="M117.302 116H106.698C97.1005 116 93 111.9 93 102.302V91.6977C93 82.1005 97.1005 78 106.698 78H117.302C126.9 78 131 82.1005 131 91.6977V102.302C131 111.9 126.9 116 117.302 116ZM106.698 80.6512C98.5498 80.6512 95.6512 83.5498 95.6512 91.6977V102.302C95.6512 110.45 98.5498 113.349 106.698 113.349H117.302C125.45 113.349 128.349 110.45 128.349 102.302V91.6977C128.349 83.5498 125.45 80.6512 117.302 80.6512H106.698Z"
                                    fill="white"/>
                            <defs>
                                    <filter id="filter0_d_1901_445" x="0" y="0" width="224"
                                            height="224" filterUnits="userSpaceOnUse"
                                            color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                            result="hardAlpha"/>
                                                    <feOffset dy="15"/>
                                                    <feGaussianBlur stdDeviation="30"/>
                                                    <feComposite in2="hardAlpha" operator="out"/>
                                            <feColorMatrix type="matrix"
                                                            values="0 0 0 0 0.952941 0 0 0 0 0.211765 0 0 0 0 0.211765 0 0 0 0.2 0"/>
                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                        result="effect1_dropShadow_1901_445"/>
                                            <feBlend mode="normal" in="SourceGraphic"
                                                        in2="effect1_dropShadow_1901_445"
                                                        result="shape"/>
                                    </filter>
                            </defs>
                    </svg>
                </span>
                <span class='question-icon'>
                    <svg class="etn-scan-svg" viewBox="0 0 224 224" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#filter0_d_561_904)">
                                    <circle cx="112" cy="97" r="52" fill="#FBAB34"/>
                            </g>
                            <path d="M124.448 115H101.782C98.1358 115 95.3494 113.672 93.9282 111.279C92.5256 108.885 92.7126 105.799 94.4892 102.601L105.822 82.2175C107.692 78.8514 110.273 77 113.115 77C115.958 77 118.538 78.8514 120.408 82.2175L131.741 102.62C133.518 105.818 133.723 108.885 132.302 111.297C130.881 113.672 128.095 115 124.448 115ZM113.115 79.8051C111.357 79.8051 109.637 81.1516 108.272 83.5827L96.9577 103.985C95.686 106.267 95.4803 108.361 96.3593 109.876C97.2382 111.391 99.1831 112.214 101.801 112.214H124.467C127.085 112.214 129.011 111.391 129.908 109.876C130.806 108.361 130.582 106.285 129.31 103.985L117.959 83.5827C116.594 81.1516 114.873 79.8051 113.115 79.8051Z"
                                    fill="white"/>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M109.567 95.3807C109.567 93.0196 111.469 91.1172 113.83 91.1172C116.191 91.1172 118.093 93.0196 118.093 95.3807C118.093 97.1875 116.926 98.151 116.041 98.7559C115.228 99.3174 114.713 99.8199 114.713 100.737V101.165C114.713 101.652 114.317 102.047 113.83 102.047C113.342 102.047 112.947 101.652 112.947 101.165V100.737C112.947 98.885 114.142 97.9212 115.039 97.3019L115.043 97.2994L115.043 97.2994C115.827 96.7637 116.328 96.2609 116.328 95.3807C116.328 93.9945 115.216 92.8826 113.83 92.8826C112.444 92.8826 111.332 93.9945 111.332 95.3807C111.332 95.8682 110.937 96.2634 110.449 96.2634C109.962 96.2634 109.567 95.8682 109.567 95.3807Z"
                                    fill="white"/>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M112.497 106.033C112.497 105.301 113.089 104.708 113.821 104.708H113.839C114.57 104.708 115.163 105.301 115.163 106.033C115.163 106.764 114.57 107.357 113.839 107.357H113.821C113.089 107.357 112.497 106.764 112.497 106.033Z"
                                    fill="white"/>
                            <defs>
                                    <filter id="filter0_d_561_904" x="0" y="0" width="224"
                                            height="224" filterUnits="userSpaceOnUse"
                                            color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                            result="hardAlpha"/>
                                            <feOffset dy="15"/>
                                            <feGaussianBlur stdDeviation="30"/>
                                            <feComposite in2="hardAlpha" operator="out"/>
                                            <feColorMatrix type="matrix"
                                                            values="0 0 0 0 0.960784 0 0 0 0 0.760784 0 0 0 0 0.313726 0 0 0 0.5 0"/>
                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                        result="effect1_dropShadow_561_904"/>
                                            <feBlend mode="normal" in="SourceGraphic"
                                                        in2="effect1_dropShadow_561_904"
                                                        result="shape"/>
                                    </filter>
                            </defs>
                    </svg>
                </span>

                <!-- Markup for confirm modal -->
                <div id="scan_response_msg" class="etn-ticket-details"></div>

                <p class="ticket-info-heading">
                    <?php echo esc_html__( 'Event Title:', 'eventin-pro' ) ?>
                    <span class="etn-scanner-event-title"></span>
                </p>
                <p class="ticket-info-heading">
                    <?php echo esc_html__( 'Attendee Name:', 'eventin-pro' ) ?>
                    <span class="etn-scanner-attendee-name"></span>
                </p>
                <p class="ticket-info-heading">
                    <?php echo esc_html__( 'Ticket Type:', 'eventin-pro' ) ?>
                    <span class="etn-scanner-event-ticket-type"></span>
                </p>

                <div class="etn-ticket-btn-wrapper">
                    <!-- one-step verification -->
                    <button type="button" id="scanAgainBtn"
                            class="scan-cancel-btn etn-btn etn-btn-primary">
						<?php echo esc_html__( 'Scan Another', 'eventin-pro' ) ?>
                    </button>

					<?php if ( empty( $_GET['from'] ) ) : ?>
                        <a href="<?php echo esc_url( admin_url( '/edit.php?post_type=etn-attendee' ) ); ?>"
                           id="cancelScanBtn"
                           class="scan-another-btn etn-btn etn-btn-border"><?php echo esc_html__( 'Back to Attendee', 'eventin-pro' ) ?></a>
					<?php else : ?>
                        <button onclick="window.history.go(-1); return false;" id="cancelScanBtn"
                                class="scan-another-btn etn-btn etn-btn-border"><?php echo esc_html__( 'Go Back', 'eventin-pro' ) ?></button>
					<?php endif; ?>
                    <!-- Two-step verification -->
                    <button type="button" id="ticketConfirmBtn"
                            class=" scan-cancel-btn etn-btn etn-btn-primary">
						<?php echo esc_html__( 'Confirm', 'eventin-pro' ) ?>
                    </button>

                    <!-- TODO a tag is stopping the scan and then start again(blinking the camera) button or other tag is ok -->
                    <a href="" id="ticketRejectBtn"
                       class="scan-another-btn etn-btn etn-btn-border">
						<?php echo esc_html__( 'Reject', 'eventin-pro' ) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanner button container -->
    <div class="etn-scanner-btn-container">
        <button type="button" class="etn-ticket-scan-start-button etn-btn etn-btn-primary " id="start-button"
                data-redirect_scanned='<?php echo isset( $scanned_response ) ? json_encode( $scanned_response ) : ''; ?>'>
			<?php echo esc_html__( 'Start', 'eventin-pro' ) ?>
        </button>
		<?php if ( empty( $_GET['from'] ) ) : ?>
            <a href="<?php echo esc_url( admin_url( '/edit.php?post_type=etn-attendee' ) ); ?>" id="backToAtendee"
               class="scan-cancel-btn etn-btn etn-btn-primary">
				<?php echo esc_html__( 'Back to Attendee', 'eventin-pro' ) ?>
            </a>
		<?php else: ?>
            <button onclick="window.history.go(-1); return false;" id="backToAtendee"
                    class="scan-cancel-btn etn-btn etn-btn-primary">
				<?php echo esc_html__( 'Go Back', 'eventin-pro' ) ?>
            </button>
		<?php endif; ?>
    </div>
<?php wp_footer(); ?>