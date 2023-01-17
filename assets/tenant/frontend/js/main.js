(function($) {
    "use strict";

    $(document).ready(function() {
        /*
        ========================================
            input search open item
        ========================================
        */
        $(document).on('keyup change', '#search_form_input', function(event) {

            let input_values = $(this).val();

            if (input_values.length > 0) {
                $('#search_suggestions_wrap, .search-suggestion-overlay').addClass("show");
            } else {
                $('#search_suggestions_wrap, .search-suggestion-overlay').removeClass("show");
            }
        });
        $(document).on('click', '.search-suggestion-overlay, .search-click-icon', function() {
            $('#search_suggestions_wrap, .search-suggestion-overlay').removeClass('show')
        })
        $(document).on('click', '.search-click-icon', function() {
            $('.search-suggetions-show').toggleClass('open')
        })
        $(document).on('click', '.suggetions-icon-close, .search-suggestion-overlay', function() {
            $('.search-suggetions-show').removeClass('open')
            $('#search_suggestions_wrap, .search-suggestion-overlay').removeClass('show')
        })

        /*
        ========================================
            Nice Scroll js
        ========================================
        */
        $(".product-suggestion-list, .contents-wrapper, .single-addto-cart-wrappers, .single-shop-details-wrapper, .shop-details-cart-scroll-responsive").niceScroll({});

        /* 
        ========================================
            Popup Modal Cart
        ========================================
        */
        $(document).on('click', '.close-icon, .body-overlay-desktop', function() {
            $('.shop-detail-cart-content, .body-overlay-desktop').removeClass('active');
        });
        $(document).on('click', '.popup-modal', function() {
            $('.shop-detail-cart-content, .body-overlay-desktop').addClass('active');
        });

        /* 
        ========================================
            Discount Popup Click
        ========================================
        */
        $(document).on('click', '.discount-overlays, .close-icon', function() {
            $('.discount-overlays, .discount-popup-area').hide();
        });

        $('.discount-popup-area').hide();
        setTimeout(function() {
            $('.discount-popup-area').show();
        }, 3000);

        /* 
        ========================================
            Addto-Cart Click Close
        ========================================
        */
        $(document).on('click', '.close-cart', function() {
            $(this).parent().hide(100);
        });

        /* 
        ========================================
            Cart Click Loading
        ========================================
        */
        $(document).on('click', '.cart-loading', function() {
            $(this).addClass('active-loading')
            setTimeout(function() {
                $('.cart-loading').removeClass('active-loading');
            }, 1500);
        });

        /* 
        ========================================
            Navbar Toggler
        ========================================
        */
        $(document).on('click', '.navbar-toggler', function() {
            $(".navbar-toggler").toggleClass("active");
        });

        $(document).on('click', '.click-nav-right-icon', function() {
            $(".show-nav-content").toggleClass("show");
        });

        /*
        ========================================
            counter Odometer
        ========================================
        */
        $(".single-counter-count").each(function() {
            $(this).isInViewport(function(status) {
                if (status === "entered") {
                    for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
                        var el = document.querySelectorAll('.odometer')[i];
                        el.innerHTML = el.getAttribute("data-odometer-final");
                    }
                }
            });
        });

        /* 
        ========================================
            Nice Select
        ========================================
        */
        $('#nice-select').niceSelect();

        /* 
        ========================================
            Lazy Load Js
        ========================================
        */
        $('.lazyloads').Lazy();
        /* 
        ========================================
            Click Active Class
        ========================================
        */
        $(document).on('click', '.active-list .list', function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });

        $(document).on('click', '.active-list .list a span.ad-values', function() {
            $(this).parent().parent().siblings().removeClass('active');
            $(this).parent().parent().addClass('active');
        });

        /*-------------------------------
            Click Slide Open Close
        ------------------------------*/

        $(document).on('click', '.shop-left-title .title', function(e) {
            var shopTitle = $(this).parent('.shop-left-title');
            if (shopTitle.hasClass('open')) {
                shopTitle.removeClass('open');
                shopTitle.find('.shop-left-list').removeClass('open');
                shopTitle.find('.shop-left-list').slideUp(300, "swing");
            } else {
                shopTitle.addClass('open');
                shopTitle.children('.shop-left-list').slideDown(300, "swing");
                shopTitle.siblings('.shop-left-title').children('.shop-left-list').slideUp(300, "swing");
                shopTitle.siblings('.shop-left-title').removeClass('open');
            }
        });
        /* 
        ========================================
            Click Clear Filter
        ========================================
        */
        $(document).on('click', '.category-lists .list .ad-values', function() {
            $('.selected-clear-items').show();
            $('.click-hide-filter .click-hide').show();
        });
        /*
        ========================================
            Click add Value text
        ========================================
        */

        // Category
        $(document).on('click', '.category-lists .ad-values, .category-lists li', function(event) {
            $('.selectder-filter-contents').show();
            let value = $(this).data('value').trim();
            let slug = $(this).data('slug').trim();
            let filters = $('#_porduct_fitler_item li.show-value');

            let contains = $('#_porduct_fitler_item .category-filter');

            if (contains.length === 0) {
                $('#_porduct_fitler_item').append(`<li class="show-value category-filter" value="${value}" data-filter="category-lists" data-slug="${slug}"> <a class="click-hide" href="javascript:void(0)"> ${value} </a> </li>`);
            } else {
                contains.attr('value', value);
                contains.attr('data-slug', slug);
                contains.find('a').text(value);
            }

            return false;
        });

        // Size
        $(document).on('click', '.size-lists li', function(event) {
            $('.selectder-filter-contents').show();
            let value = $(this).data('value');
            let slug = $(this).data('slug');
            let filters = $('#_porduct_fitler_item li.show-value');

            let contains = $('#_porduct_fitler_item .size-filter');

            if (contains.length === 0) {
                $('#_porduct_fitler_item').append(`<li class="show-value size-filter" value="${value}" data-filter="size-lists" data-slug="${slug}"> <a class="click-hide" href="javascript:void(0)"> ${value} </a> </li>`);
            } else {
                contains.attr('value', value);
                contains.attr('data-slug', slug);
                contains.find('a').text(value);
            }
            return false;
        });

        // Color
        $(document).on('click', '.color-lists li', function(event) {
            $('.selectder-filter-contents').show();
            let value = $(this).data('value').trim();
            let slug = $(this).data('slug');
            let filters = $('#_porduct_fitler_item li.show-value');

            let contains = $('#_porduct_fitler_item .color-filter');

            if (contains.length === 0) {
                $('#_porduct_fitler_item').append(`<li class="text-capitalize show-value color-filter" value="${value}" data-filter="color-lists" data-slug="${slug}"> <a class="click-hide" href="javascript:void(0)"> ${value} </a> </li>`);
            } else {
                contains.attr('value', value);
                contains.attr('data-slug', slug);
                contains.find('a').text(value);
            }
            return false;
        });

        // Rating
        $(document).on('click', '.filter-lists li', function(event) {
            $('.selectder-filter-contents').show();
            let slug = $(this).data('slug');
            let value = slug;

            let contains = $('#_porduct_fitler_item .rating-filter');
            console.log(contains)

            if (contains.length === 0) {
                $('#_porduct_fitler_item').append(`<li class="show-value rating-filter" data-filter="filter-lists" data-slug="${slug}"> <a class="click-hide" href="javascript:void(0)"> <span class="star_count">${value}</span> <span><i class="las la-star"></i></span> </a> </li>`);
            } else {
                contains.attr('data-slug', slug);
                contains.find('a .star_count').text(value);
            }
            return false;
        });

        // Tag
        $(document).on('click', '.tag-lists li', function(event) {
            $('.selectder-filter-contents').show();
            let slug = $(this).data('slug');
            let value = slug;

            let contains = $('#_porduct_fitler_item .tag-filter');

            if (contains.length === 0) {
                $('#_porduct_fitler_item').append(`<li class="text-capitalize show-value tag-filter" data-filter="tag-lists" data-slug="${slug}"> <a class="click-hide" href="javascript:void(0)"> ${value} </a> </li>`);
            } else {
                contains.attr('data-slug', slug);
                contains.find('a').text(value);
            }
        });
        /* 
        ========================================
            Shop Responsive Sidebar
        ========================================
        */
        $(document).on('click', '.close-bars, .responsive-overlay', function() {
            $('.shop-close, .shop-close-main, .responsive-overlay').removeClass('active');
        });

        $(document).on('click', '.sidebar-icon', function() {
            $('.shop-close, .shop-close-main, .responsive-overlay').addClass('active');
        });

        /*
        ========================================
            wow js init
        ========================================
        */
        new WOW().init();

        /* 
        ========================================
            Initialize tooltips
        ========================================
        */
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        /* 
        ========================================
            Compare Click Close 
        ========================================
        */
        $(document).on('click', '.close-compare', function() {
            $(this).parent().parent().hide(50);
        });

        /* 
        ========================================
            Tab
        ========================================
        */
        $(document).on('click', 'ul.tabs li', function() {
            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('active');
            $('.tab-content-item').removeClass('active');

            $(this).addClass('active');
            $("#" + tab_id).addClass('active');
        });

        /*
        ========================================
        accordion
        ========================================
        */
        $('.faq-contents .faq-title').on('click', function(e) {
            var element = $(this).parent('.faq-item');
            if (element.hasClass('open')) {
                element.removeClass('open');
                element.find('.faq-panel').removeClass('open');
                element.find('.faq-panel').slideUp(300, "swing");
            } else {
                element.addClass('open');
                element.children('.faq-panel').slideDown(300, "swing");
                element.siblings('.faq-item').children('.faq-panel').slideUp(300, "swing");
                element.siblings('.faq-item').removeClass('open');
                element.siblings('.faq-item').find('.faq-title').removeClass('open');
                element.siblings('.faq-item').find('.faq-panel').slideUp(300, "swing");
            }
        });

        /* 
        ========================================
            Cart Click Close
        ========================================
        */

        $(document).on('click', '.close-table-cart', function() {
            $(this).parent().parent().hide(100);
        });

        $(document).on('click', '.btn-clear', function() {
            $('.table-cart-clear').hide(500);
        });

        /* 
        ========================================
            Payment Card Delivery 
        ========================================
        */

        $(document).on('click', '.payment-card .single-card, .select-list li', function() {
            $(this).siblings().removeClass("active");
            $(this).addClass("active");
        });

        $(document).on('click', '.select-list li.disabled', function() {
            $(this).removeClass("active");
        });

        /* 
        ========================================
            Click Open SignIn SignUp
        ========================================
        */

        $(document).on('click', '.click-open-form', function() {
            $('.checkout-form-open').toggleClass('active');
        });

        $(document).on('click', '.click-open-form2', function() {
            $(this).toggleClass('active');
            $('.checkout-signup-form-wrapper').toggleClass('active');
        });

        $(document).on('click', '.click-open-form3', function() {
            $(this).toggleClass('active');
            $('.checkout-address-form-wrapper').toggleClass('active');
        });

        /*
        ========================================
            Global Slider Init
        ========================================
        */
        var globalSlickInit = $('.global-slick-init');
        if (globalSlickInit.length > 0) {
            //todo have to check slider item 
            $.each(globalSlickInit, function(index, value) {
                if ($(this).children('div').length > 1) {
                    //todo configure slider settings object
                    var sliderSettings = {};
                    var allData = $(this).data();
                    var infinite = typeof allData.infinite == 'undefined' ? false : allData.infinite;
                    var arrows = typeof allData.arrows == 'undefined' ? false : allData.arrows;
                    var autoplay = typeof allData.autoplay == 'undefined' ? false : allData.autoplay;
                    var focusOnSelect = typeof allData.focusonselect == 'undefined' ? false : allData.focusonselect;
                    var swipeToSlide = typeof allData.swipetoslide == 'undefined' ? false : allData.swipetoslide;
                    var slidesToShow = typeof allData.slidestoshow == 'undefined' ? 1 : allData.slidestoshow;
                    var slidesToScroll = typeof allData.slidestoscroll == 'undefined' ? 1 : allData.slidestoscroll;
                    var speed = typeof allData.speed == 'undefined' ? '500' : allData.speed;
                    var dots = typeof allData.dots == 'undefined' ? false : allData.dots;
                    var cssEase = typeof allData.cssease == 'undefined' ? 'linear' : allData.cssease;
                    var prevArrow = typeof allData.prevarrow == 'undefined' ? '' : allData.prevarrow;
                    var nextArrow = typeof allData.nextarrow == 'undefined' ? '' : allData.nextarrow;
                    var centerMode = typeof allData.centermode == 'undefined' ? false : allData.centermode;
                    var centerPadding = typeof allData.centerpadding == 'undefined' ? false : allData.centerpadding;
                    var rows = typeof allData.rows == 'undefined' ? 1 : parseInt(allData.rows);
                    var autoplay = typeof allData.autoplay == 'undefined' ? false : allData.autoplay;
                    var autoplaySpeed = typeof allData.autoplayspeed == 'undefined' ? 2000 : parseInt(allData.autoplayspeed);
                    var lazyLoad = typeof allData.lazyload == 'undefined' ? false : allData.lazyload; // have to remove it from settings object if it undefined
                    var appendDots = typeof allData.appenddots == 'undefined' ? false : allData.appenddots;
                    var appendArrows = typeof allData.appendarrows == 'undefined' ? false : allData.appendarrows;
                    var asNavFor = typeof allData.asnavfor == 'undefined' ? false : allData.asnavfor;
                    var verticalSwiping = typeof allData.verticalswiping == 'undefined' ? false : allData.verticalswiping;
                    var vertical = typeof allData.vertical == 'undefined' ? false : allData.vertical;
                    var fade = typeof allData.fade == 'undefined' ? false : allData.fade;
                    var rtl = typeof allData.rtl == 'undefined' ? false : allData.rtl;
                    var responsive = typeof $(this).data('responsive') == 'undefined' ? false : $(this).data('responsive');
                    //slider settings object setup
                    sliderSettings.infinite = infinite;
                    sliderSettings.arrows = arrows;
                    sliderSettings.autoplay = autoplay;
                    sliderSettings.focusOnSelect = focusOnSelect;
                    sliderSettings.swipeToSlide = swipeToSlide;
                    sliderSettings.slidesToShow = slidesToShow;
                    sliderSettings.slidesToScroll = slidesToScroll;
                    sliderSettings.speed = speed;
                    sliderSettings.dots = dots;
                    sliderSettings.cssEase = cssEase;
                    sliderSettings.prevArrow = prevArrow;
                    sliderSettings.nextArrow = nextArrow;
                    sliderSettings.rows = rows;
                    sliderSettings.autoplaySpeed = autoplaySpeed;
                    sliderSettings.autoplay = autoplay;
                    sliderSettings.verticalSwiping = verticalSwiping;
                    sliderSettings.vertical = vertical;
                    sliderSettings.rtl = rtl;
                    if (centerMode != false) {
                        sliderSettings.centerMode = centerMode;
                    }
                    if (centerPadding != false) {
                        sliderSettings.centerPadding = centerPadding;
                    }
                    if (lazyLoad != false) {
                        sliderSettings.lazyLoad = lazyLoad;
                    }
                    if (appendDots != false) {
                        sliderSettings.appendDots = appendDots;
                    }
                    if (appendArrows != false) {
                        sliderSettings.appendArrows = appendArrows;
                    }
                    if (asNavFor != false) {
                        sliderSettings.asNavFor = asNavFor;
                    }
                    if (fade != false) {
                        sliderSettings.fade = fade;
                    }
                    if (responsive != false) {
                        sliderSettings.responsive = responsive;
                    }
                    $(this).slick(sliderSettings);
                }
            });
        }

        /* 
        ========================================
            Password Show Hide On Click
        ========================================
        */

        $(document).on("click", ".toggle-password", function(e) {
            e.preventDefault();
            $(this).toggleClass("show-pass");
            let input = $(this).parent().find("input");
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
        /* 
        ========================================
            Click add Items
        ========================================
        */
        $(document).on('click', '.click-attr .cmn-btn', function() {
            var addItem = $(this).parent().parent().find('.load-more-items:last-child').clone();
            $('.load-more-items-wrapper').append(addItem);
            addItem.css('display', 'none');
            addItem.slideDown(600);
        })

        /* 
        ========================================
            back to top
        ========================================
        */

        $(document).on('click', '.back-to-top', function() {
            $("html,body").animate({
                scrollTop: 0
            }, 700);
        });

    });

    /* 
    ========================================
        back to top
    ========================================
    */

    $(window).on('scroll', function() {
        //back to top show/hide
        var ScrollTop = $('.back-to-top');
        if ($(window).scrollTop() > 200) {
            ScrollTop.fadeIn(10);
        } else {
            ScrollTop.fadeOut(10);
        }
    });

})(jQuery);