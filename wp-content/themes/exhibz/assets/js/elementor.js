(function ($, elementor) {
    "use strict";


    var Exhibs = {
        /**
         * Create a new Swiper instance
         *
         * @param swiperElement widget scope
         * @param swiperConfig swiper config
         */
        swiper: function (swiperElement, swiperConfig) {
            var swiperContainer = swiperElement.get(0);
            if (typeof Swiper !== 'function') {
                // If Swiper is not defined or not a function, load the Swiper library asynchronously
                const asyncSwiper = elementor.utils.swiper;
                return new asyncSwiper(swiperContainer, swiperConfig).then((newSwiperInstance) => {
                    return newSwiperInstance;
                });
            }
            // If Swiper is already defined, create a new Swiper instance using the global Swiper object
            const swiperInstance = new Swiper(swiperContainer, swiperConfig);
            return Promise.resolve(swiperInstance);
        },

        init: function () {

            var widgets = {
                'exhibz-speaker.default': Exhibs.Speaker_Image_Popup,
                'exhibz-speaker-slider.default': Exhibs.Speaker_Slider_popup,
                'exhibz-testimonial.default': Exhibs.Testimonial_Slider,
                'exhibz-slider.default': Exhibs.Main_Slider,
                'exhibz-gallery-slider.default': Exhibs.Exhibz_Gallery_Slider,
                'exhibz-event-category-slider.default': Exhibs.Exhibz_Category_Slider,
                'exhibz-creative-speaker.default': Exhibs.Exhibz_Creative_Speaker_Widget,
                'creative-schedule.default': Exhibs.Exhibz_Creative_Schedule_Tab,
                'exhibz-event-ticket.default': Exhibs.Ticket_Variation_Slider,
                'exhibz-countdown.default': Exhibs.Exhibz_Countdown,
            };
            $.each(widgets, function (widget, callback) {
                elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
            });

        },
        Exhibz_Creative_Speaker_Widget: function ($scope) {
            const container = $scope.find('.exhibz-creative-speaker');
            if (container.length > 0) {
                const settings = container.data('widget_settings');
                const slider_space_between = parseInt(settings.slider_space_between);
                const slide_autoplay = (settings.speaker_slider_autoplay === 'yes') ? true : false;
                const speaker_slider_speed = parseInt(settings.speaker_slider_speed);
                var config = {
                    slidesPerView: settings.slider_items,
                    spaceBetween: slider_space_between,
                    loop: true,
                    speed: speaker_slider_speed,
                    autoplay: slide_autoplay,
                    navigation: {
                        nextEl: `.swiper-next-${settings.widget_id}`,
                        prevEl: `.swiper-prev-${settings.widget_id}`,
                    },
                    pagination: {
                        el: ".exhibz-speaker-scrollbar",
                        type: "progressbar",
                    },
                    // Responsive breakpoints
                    breakpoints: {
                        // when window width is >= 320px
                        0: {
                            slidesPerView: 1,
                        },
                        // when window width is >= 480px
                        767: {
                            slidesPerView: 2,
                        },
                        // when window width is >= 640px
                        1024: {
                            slidesPerView: settings.slider_items,
                        }
                    }
                }
                // swiper
                let swiperClass = $scope.find(`.${window.elementorFrontend.config.swiperClass}`);
                Exhibs.swiper(swiperClass, config).then(function (swiperInstance) { });
            }

            $('.exh-speaker-title a').each(function () {
                let speakerName = $(this);
                speakerName.html(speakerName.text().replace(/([^\s]+)/, '<span class="first-name">$1</span>'));
            });
        },

        Exhibz_Category_Slider: function ($scope) {
            const container = $scope.find('.ts-event-category-slider');

            if (container.length > 0) {
                const count = $(".ts-event-category-slider").data("count");
                const controls = container.data('controls');
                const autoslide = Boolean(controls.autoplay_slide === 'yes' ? true : false);
                const slider_loop = (controls.slider_loop === 'yes') ? true : false;
                const slider_items = controls.slider_items;
                const widget_id = controls.widget_id;
                var config = {
                    wrapperClass: 'swiper-wrapper',
                    slideClass: 'swiper-slide',
                    slidesPerView: slider_items,
                    mouseDrag: true,
                    loop: false,
                    touchDrag: true,
                    autoplay: autoslide,
                    nav: false,
                    spaceBetween: 30,
                    dots: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 600,
                    navigation: {
                        nextEl: `.swiper-next-${widget_id}`,
                        prevEl: `.swiper-prev-${widget_id}`,
                    },
                    breakpoints: {
                        // when window width is >= 320px
                        0: {
                            slidesPerView: 2,
                        },
                        // when window width is >= 480px
                        767: {
                            slidesPerView: 3,
                        },
                        // when window width is >= 640px
                        1024: {
                            slidesPerView: slider_items
                        }
                    }
                }

                // swiper
                let swiperClass = $scope.find(`.${window.elementorFrontend.config.swiperClass}`);
                Exhibs.swiper(swiperClass, config).then(function (swiperInstance) { });
            };
        },

        Speaker_Slider_popup: function ($scope) {
            var $container = $scope.find('.ts-image-popup');
            var $container2 = $scope.find('.ts-speaker-slider');
            let controls = $container2.data('controls');
            $container.magnificPopup({
                type: 'inline',
                closeOnContentClick: false,
                midClick: true,
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
                zoom: {
                    enabled: true,
                    duration: 500, // don't foget to change the duration also in CSS
                },
                mainClass: 'mfp-fade',
            });

            let widget_id = controls.widget_id;
            let speaker_slider_speed = parseInt(controls.speaker_slider_speed);
            let slider_count = parseInt(controls.slider_count);
            let speaker_slider_autoplay = Boolean(controls.speaker_slider_autoplay ? true : false);
            var config = {
                slidesPerView: slider_count,
                spaceBetween: 0,
                loop: true,
                wrapperClass: 'swiper-wrapper',
                slideClass: 'swiper-slide',
                grabCursor: false,
                allowTouchMove: true,
                autoplay: speaker_slider_autoplay ? { delay: 5000 } : false,
                speed: speaker_slider_speed, //slider transition speed
                mousewheelControl: 1,
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                    dynamicBullets: true,
                    clickable: true,
                },
                navigation: {
                    nextEl: `.swiper-next-${widget_id}`,
                    prevEl: `.swiper-prev-${widget_id}`,
                },
                breakpoints: {
                    // when window width is >= 320px
                    0: {
                        slidesPerView: 1,
                    },
                    // when window width is >= 480px
                    767: {
                        slidesPerView: 2,
                    },
                    // when window width is >= 640px
                    1024: {
                        slidesPerView: slider_count,
                    }
                }
            }
            // swiper
            let swiperClass = $scope.find(`.${window.elementorFrontend.config.swiperClass}`);
            Exhibs.swiper(swiperClass, config).then(function (swiperInstance) { });
        },

        Speaker_Image_Popup: function ($scope) {
            var $container = $scope.find('.ts-image-popup');

            $container.magnificPopup({
                type: 'inline',
                closeOnContentClick: false,
                midClick: true,
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
                zoom: {
                    enabled: true,
                    duration: 500, // don't foget to change the duration also in CSS
                },
                mainClass: 'mfp-fade',
            });

        },

        Main_Slider: function ($scope) {
            let $container = $scope.find('.main-slider');
            let controls = $container.data('controls');

            var autoslide = Boolean(controls.auto_nav_slide ? true : false);
            const slider_speed = parseInt(controls.slider_speed);
            let widget_id = controls.widget_id;
            var config = {
                slidesPerView: 1,
                centeredSlides: true,
                spaceBetween: 0,
                loop: true,
                wrapperClass: 'swiper-wrapper',
                slideClass: 'swiper-slide',
                grabCursor: false,
                allowTouchMove: true,
                speed: slider_speed, //slider transition speed
                parallax: true,
                autoplay: autoslide ? { delay: 5000 } : false,
                effect: 'slide',
                mousewheelControl: 1,
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                    dynamicBullets: true,
                    clickable: true,
                },
                navigation: {
                    nextEl: `.swiper-next-${widget_id}`,
                    prevEl: `.swiper-prev-${widget_id}`,
                },
            }

            // swiper
            let swiperClass = $scope.find(`.${window.elementorFrontend.config.swiperClass}`);
            Exhibs.swiper(swiperClass, config).then(function (swiperInstance) { });
        },

        Exhibz_Gallery_Slider: function ($scope) {
            const container = $scope.find('.ts-gallery-slider');
            if (container.length > 0) {
                const settings = container.data('widget_settings');
                const slider_space_between = parseInt(settings.slider_space_between);
                const slide_autoplay = (settings.speaker_slider_autoplay === 'yes') ? true : false;
                const speaker_slider_speed = parseInt(settings.speaker_slider_speed);
                var config = {
                    slidesPerView: settings.slider_items,
                    spaceBetween: slider_space_between,
                    loop: true,
                    gap: 0,
                    centeredSlides: true,
                    speed: speaker_slider_speed,
                    autoplay: slide_autoplay,
                    navigation: {
                        nextEl: `.swiper-next-${settings.widget_id}`,
                        prevEl: `.swiper-prev-${settings.widget_id}`,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        type: "bullets",
                        clickable: true
                    },
                    // Responsive breakpoints
                    breakpoints: {
                        // when window width is >= 320px
                        0: {
                            slidesPerView: 1,
                        },
                        // when window width is >= 480px
                        767: {
                            slidesPerView: 2,
                        },
                        // when window width is >= 640px
                        1024: {
                            slidesPerView: settings.slider_items,
                        }
                    }
                }

                // swiper
                let swiperClass = $scope.find(`.${window.elementorFrontend.config.swiperClass}`);
                Exhibs.swiper(swiperClass, config).then(function (swiperInstance) { });
            }
        },

        Testimonial_Slider: function ($scope) {
            const container = $scope.find('.testimonial-carousel');
            if (container.length > 0) {
                const settings = container.data('widget_settings');
                const slide_autoplay = (settings.autoplay_onoff === 'yes') ? true : false;
                const quote_slider_speed = parseInt(settings.quote_slider_speed);
                var config = {
                    slidesPerView: settings.quote_slider_count,
                    spaceBetween: 10,
                    loop: true,
                    speed: quote_slider_speed,
                    autoplay: slide_autoplay,
                    navigation: {
                        nextEl: `.swiper-next-${settings.widget_id}`,
                        prevEl: `.swiper-prev-${settings.widget_id}`,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        type: "bullets",
                        clickable: true
                    },
                    // Responsive breakpoints
                    breakpoints: {
                        // when window width is >= 320px
                        0: {
                            slidesPerView: 1,
                        },
                        // when window width is >= 480px
                        767: {
                            slidesPerView: 2,
                        },
                        // when window width is >= 640px
                        1024: {
                            slidesPerView: settings.quote_slider_count,
                        }
                    }
                }

                // swiper
                let swiperClass = $scope.find(`.${window.elementorFrontend.config.swiperClass}`);
                Exhibs.swiper(swiperClass, config).then(function (swiperInstance) { });
            }
        },

        Exhibz_Creative_Schedule_Tab: function ($scope) {
            const container = $scope.find('.etn-tab-content .etn-schedule-speaker');
            if (container.length > 0) {
                const controls = container.data('controls');
                const slider_items = controls.speaker_count;
                const centeredSlides = Boolean(controls.slider_center === 'yes' ? true : false);
                var config = {
                    slidesPerView: slider_items,
                    spaceBetween: 5,
                    autoplay: true,
                    effect: 'slide',
                    loop: true,
                    centeredSlides: centeredSlides,
                    navigation: false,
                    // Responsive breakpoints
                    breakpoints: {
                        // when window width is >= 320px
                        0: {
                            slidesPerView: 1,
                        },
                        // when window width is >= 480px
                        767: {
                            slidesPerView: 2,
                        },
                        // when window width is >= 640px
                        1024: {
                            slidesPerView: slider_items,
                        }
                    }
                };

                let swiperClass = $scope.find(`.${window.elementorFrontend.config.swiperClass}`);
                swiperClass.each(function (index, currentItem) {
                    let $currentItem = $(currentItem);
                    Exhibs.swiper($currentItem, config).then(function (swiperInstance) { }).catch(function (error) { });
                });

                let nav = $scope.find('.etn-nav li a.etn-tab-a');
                nav.on('click', function (e) {
                    e.preventDefault();
                    setTimeout(function () {
                        window.elementorFrontend.elementsHandler.runReadyTrigger($scope);
                    }, 800);
                });
            }
        },


        Ticket_Variation_Slider: function ($scope) {
            const container = $scope.find('.ticket-variation-slider');
           
            if (container.length > 0) {
                let controls = container.data('controls');
                let autoplay = Boolean((controls.autoplay_slide === 'yes') ? true : false);
                let speed = parseInt(controls.speed);
                let loop = (controls.slider_loop === 'yes') ? true : false;
                let widget_id = controls.widget_id;
                var config = {
                    slidesPerView: 1,
                    centeredSlides: true,
                    loop: false,
                    loopAdditionalSlides: 0,
                    grabCursor: false,
                    allowTouchMove: true,
                    speed: speed, //slider transition speed
                    autoplay: autoplay,
                    effect: 'slide',
                    navigation: {
                        nextEl: `.swiper-next-${widget_id}`,
                        prevEl: `.swiper-prev-${widget_id}`,
                    },
                }

                // swiper
                let swiperClass = $scope.find(`.${window.elementorFrontend.config.swiperClass}`);
                Exhibs.swiper(swiperClass, config).then(function (swiperInstance) { });
            }
        },
        Exhibz_Countdown: function ($scope) {
            var container = $(".countdown");

            if (container.length > 0) {
                $.each(container, function (key, item) {
                var current_countdown_wrap = this;

                // countdown
                let etn_event_start_date = $(item).data("start-date");

                var countDownDate = new Date(etn_event_start_date).getTime();

                let etn_timer_x = setInterval(function () {
                    var now = new Date().getTime();
                    var distance = countDownDate - now;

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor(
                    (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                    );
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $(item).find(".day-count").html(days);
                    $(item).find(".hr-count").html(hours);
                    $(item).find(".min-count").html(minutes);
                    $(item).find(".sec-count").html(seconds);
                    if (distance < 0) {
                    clearInterval(etn_timer_x);
                    $(current_countdown_wrap).html(localized_data_obj.expired);
                    }
                }, 1000);
                });
            }

         }


    };
    $(window).on('elementor/frontend/init', Exhibs.init);
}(jQuery, window.elementorFrontend)); 
