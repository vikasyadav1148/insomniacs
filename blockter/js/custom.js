(function ($) {
    "use strict";
    var HT = {};
    /*=============SHORTCODE===========*/
    /*plyr setup*/
    //plyr.setup();
    HT.postGallery = function () {
        var postGallery = $(".blog-post-gallery");
        postGallery.not('.slick-initialized').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            infinite: false,
            accessibility: false

        });
    };
    /*topbar on mobile*/
    HT.topbar_mobile = function () {
        var topbartoggle = $('#topbar-toggle');
        topbartoggle.on('click', function () {
            var themtopbar = $('.theme-topbar');
            themtopbar.slideToggle('fast');
        });
    }
    HT.menu_mobile = function () {
        enquire.register("screen and (max-width:991px)", {
            match: function () {
                $('.theme-wrap-primary-menu').ht_menu({
                    resizeWidth: '991',
                    initiallyVisible: false,
                    collapserTitle: '',
                    animSpeed: 'fast',
                    easingEffect: null,
                    indentChildren: false,
                    childrenIndenter: '&nbsp;&nbsp;',
                    expandIcon: '',
                    collapseIcon: ''
                });
            }
        });
    }

    /*READY*/
    $(document).ready(function () {
        /*Topbar on mobile*/
        /*theme tabs*/
        var themetabs = $(".theme-tabs");
        themetabs.organicTabs({
            "speed": 200
        });
        /*=============//SHORTCODE===========*/
        /*tab for movie single*/
        var tabsClick = $('.tabs .tab-links a');
        tabsClick.on('click', function () {
            var currentAttrValue = $(this).attr('href');
            var tabsCurrent = $('.tabs ' + currentAttrValue);
            var tabsOffset = $(".tabs").offset();
            scrollTo(tabsOffset.left, tabsOffset.top);
            // Show/Hide Tabs
            tabsCurrent.show().siblings().hide();
            // Change/remove current tab to active
            $(this).parent('li').addClass('active').siblings().removeClass('active');
            return false;
        });

        var $tabLinks = $('.media-tab');
        $tabLinks.on('click', function () {
            var currentAttrValue = $(this).attr('href');
            var currentLink = $('.tabs .tab-links a[href=' + currentAttrValue + ']');
            currentLink.trigger('click');
            return false;
        });
        /*Twitter carousel*/
        var twitterCarousel = $(".theme-twitter-carousel");
        twitterCarousel.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            accessibility: false,
            dots: true,
            infinite: true,
            fade: false,
            variableWidth: true,
            draggable: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        /*movie slider carousel*/
        var movieSlider = $('.movie-slider-style-1 .movie-grid-items');
        movieSlider.slick({
            infinite: true,
            draggable: true,
            slidesToShow: 4,
            slidesToScroll: 4,
            accessibility: false,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: false,
            dots: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        /*movie slider carousel 2*/
        var movieSlider2 = $('.movie-slider-style-2 .movie-grid-items, .movie-slider-style-3 .movie-grid-items');
        movieSlider2.slick({
            infinite: true,
            draggable: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            accessibility: false,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: false
        });
        /*movie carousel*/
        var movieCarousel = $(".movie-carousel-style");
        movieCarousel.slick({
            infinite: true,
            draggable: true,
            slidesToShow: 4,
            slidesToScroll: 4,
            accessibility: false,
            arrows: false,
            dots: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        /*movie carousel full width*/
        var movieCarouselFw = $('.movie-carousel-style-fw');
        movieCarouselFw.slick({
            infinite: true,
            draggable: true,
            slidesToShow: 6,
            slidesToScroll: 4,
            accessibility: false,
            arrows: false,
            dots: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        /*movie trailer horizontal carousel*/
        //for home v3
        var $horizontalTrailerItem = $('.js-horizontal-trailer');
        $horizontalTrailerItem.each(function () {
            var $horizontalTrailerItemId = $(this).attr('id'),
                $horizontalTrailerSelector = "#" + $horizontalTrailerItemId,
                slidefor = $($horizontalTrailerSelector).find('.slider-for'),
                slidenav = $($horizontalTrailerSelector).find('.slider-nav'),
                $horizontalSliderNavSelector = $horizontalTrailerSelector + ' .slider-nav',
                $horizontalSliderForSelector = $horizontalTrailerSelector + ' .slider-for';
            slidefor.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                accessibility: false,
                arrows: false,
                fade: true,
                asNavFor: $horizontalSliderNavSelector,
            });
            slidenav.slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                accessibility: false,
                asNavFor: $horizontalSliderForSelector,
                dots: true,
                focusOnSelect: true,

                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            arrows: true
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: true
                        }
                    }
                ]
            });
        });
        /*movie trailer vertical carousel*/
        var $trailerItem = $('.movie-trailer-items');
        $trailerItem.each(function () {
            var $trailerItemId = $(this).attr('id'),
                $trailerSelector = '#' + $trailerItemId,
                slidefor2 = $($trailerSelector).find('.slider-for-2'),
                slidenav2 = $($trailerSelector).find('.slider-nav-2'),
                $sliderNavSelector = $trailerSelector + ' .slider-nav-2',
                $sliderForSelector = $trailerSelector + ' .slider-for-2';
            slidefor2.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                accessibility: false,
                arrows: false,
                fade: true,
                asNavFor: $sliderNavSelector,
            });
            slidenav2.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                accessibility: false,
                asNavFor: $sliderForSelector,
                dots: false,
                arrows: true,
                focusOnSelect: true,
                vertical: true,
            });
        });
        /*Light box*/
        var consultLightbox = $('.consult-lightbox-popup');
        consultLightbox.fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            prevEffect: 'none',
            nextEffect: 'none',
            arrows: false,
            helpers: { media: {}, buttons: {} }
        });
        var fancybox = $(".fancybox");
        fancybox.fancybox({
            openEffect: 'none',
            closeEffect: 'none'
        });
        /*Select dropdown*/
        var consultdrop = $('.consult-dropdown-list');
        consultdrop.dropkick();

        //js for twitter
        var tweets = $(".tweet");
        tweets.each(function (t, tweet) {
            var id = $(this).attr('id');
            twttr.widgets.createTweet(
                id, tweet,
                {
                    conversation: 'none',    // or all
                    cards: 'hidden',  // or visible
                    linkColor: 'default', // default is blue
                    theme: 'light'    // or dark
                });
        });

        /*sticky sidebar movie*/
        var windowWidth = $(window).width();
        if (windowWidth > 1200) {
            var stickySidebar = $('.sticky-sb-movie');
            if (stickySidebar.length > 0) {
                var stickyHeight = stickySidebar.height(),
                    sidebarTop = stickySidebar.offset().top;
            }
            // on scroll move the sidebar
            $(window).scroll(function () {
                if (stickySidebar.length > 0) {
                    var scrollTop = $(window).scrollTop();
                    var moviecontent = $('.movie-single-content');
                    if (sidebarTop < scrollTop) {
                        stickySidebar.css('top', scrollTop - sidebarTop);
                        // stop the sticky sidebar at the footer to avoid overlapping
                        var sidebarBottom = stickySidebar.offset().top + stickyHeight,

                            stickyStop = moviecontent.offset().top + moviecontent.height();
                        stickySidebar.addClass('sticky-poster');
                        if (stickyStop < sidebarBottom) {
                            var stopPosition = moviecontent.height() - stickyHeight;
                            stickySidebar.css('top', stopPosition);
                        }
                    }
                    else {
                        stickySidebar.css('top', '0');
                        stickySidebar.removeClass('sticky-poster');
                    }
                }
            });
            $(window).resize(function () {
                if (stickySidebar.length > 0) {
                    stickyHeight = stickySidebar.height();
                }
            });
        }
        /*fancybox video*/
        var fancyboxmedia = $('.fancybox-media');
        fancyboxmedia.fancybox({
            openEffect: 'float',
            closeEffect: 'none',
            helpers: {
                media: {},
                overlay: {
                    locked: false
                }
            }
        });
        /*fancybox for gallery movie item*/
        //== js for image lightbox
        var imglightbox = $(".img-lightbox");
        imglightbox.fancybox({
            helpers: {
                title: {
                    type: 'float'
                },
                overlay: {
                    locked: false
                }
            }
        });
        /* Scroll to top */
        var scrolltop = $('.scroll-to-top');
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                scrolltop.addClass('scroll-animation');
            } else {
                scrolltop.removeClass('scroll-animation');
            }
        });
        scrolltop.on('click', function () {
            $('html, body').animate({
                scrollTop: $("html").offset().top
            }, 300);
        });

        /*switch grid and list movie*/
        var list = $('.list');
        var grid = $('.grid');
        var movieitems = $('.movie-items .item');
        list.on('click', function (e) {
            e.preventDefault();
            list.addClass('current');
            grid.removeClass('current-grid');
            grid.removeClass('current');
            movieitems.addClass('list-group-item').removeClass('grid-group-item');
        });
        grid.on('click', function (e) {
            e.preventDefault();
            list.removeClass('current');
            grid.addClass('current-grid');
            movieitems.removeClass('list-group-item').addClass('grid-group-item');
        });

        $('[name="sortby"]').change(function () {
            $(this).closest('form').submit();
        });

        /*dropdown for gener filter movie for mobile */
        var generesDropdown = $('.category-filter');
        generesDropdown.on("click", function (event) {
            // document.getElementById("myDropdown").classList.toggle("show");
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        });
        /*popup to show full biography*/
        var seeallBio = $('.readmore-bio');
        var closeBio = $('.popup-close');
        seeallBio.on('click', function (e) {
            var targeted_popup_class = $(this).attr('data-popup-open');
            var popUp = $('[data-popup="' + targeted_popup_class + '"]');
            popUp.fadeIn(350);
            e.preventDefault();
        });
        //----- CLOSE
        closeBio.on('click', function (e) {
            var targeted_popup_class = $(this).attr('data-popup-close');
            var popUp = $('[data-popup="' + targeted_popup_class + '"]');
            popUp.fadeOut(350);
            e.preventDefault();
        });
        /*Login and sign up form*/
        var loginbtn = $(".login-btn");
        var signupbtn = $(".signup-btn");
        var overlay = $('.overlay');
        var overlaysignup = $('.overlay-signup')
        var loginct = $("#loginform");
        var signupct = $("#wp_signup_form");
        var loginform = $('.login-form')
        var signupform = $('.signup-form');
        loginbtn.on('click', function (event) {
            event.preventDefault();
            if (overlay.hasClass('disableform')) {
                overlay.removeClass('disableform');
                loginct.parents(overlay).addClass("openform");
                $(document).on('click', function (e) {
                    var target = $(e.target);
                    if ($(target).hasClass("overlay")) {
                        $(target).find(loginct).each(function () {
                            $(this).removeClass("openform");
                        });
                        setTimeout(function () {
                            $(target).removeClass("openform");
                        }, 350);
                    }
                });
                overlaysignup.addClass('disableform');
            } else {
                loginct.parents(overlay).addClass("openform");
                $(document).on('click', function (e) {
                    var target = $(e.target);
                    if ($(target).hasClass("overlay")) {
                        $(target).find(loginct).each(function () {
                            $(this).removeClass("openform");
                        });
                        setTimeout(function () {
                            $(target).removeClass("openform");
                        }, 350);
                    }
                });
                overlaysignup.addClass('disableform');
            }
        });
        var close = $('.close');
        close.on('click', function () {
            overlay.removeClass('openform');
            loginct.parents(overlay).removeClass("openform");
            overlaysignup.removeClass('disableform');
            $(document).on('click', function (e) {
                var target = $(e.target);
                if ($(target).hasClass("overlay")) {
                    $(target).find(loginct).each(function () {
                        $(this).removeClass("openform");
                    });
                    setTimeout(function () {
                        $(target).removeClass("openform");
                    }, 350);
                }
            });
        });
        signupbtn.on('click', function (event) {
            event.preventDefault();
            if (overlaysignup.hasClass('disableform')) {
                overlaysignup.removeClass('disableform');
                signupct.parents(overlaysignup).addClass("openform");
                $(document).on('click', function (e) {
                    var target = $(e.target);
                    if ($(target).hasClass("overlay-signup")) {
                        $(target).find(signupct).each(function () {
                            $(this).removeClass("openform");
                        });
                        setTimeout(function () {
                            $(target).removeClass("openform");
                        }, 350);
                    }
                });
                overlay.addClass('disableform');
            } else {
                signupct.parents(overlaysignup).addClass("openform");
                $(document).on('click', function (e) {
                    var target = $(e.target);
                    if ($(target).hasClass("overlay-signup")) {
                        $(target).find(signupct).each(function () {
                            $(this).removeClass("openform");
                        });
                        setTimeout(function () {
                            $(target).removeClass("openform");
                        }, 350);
                    }
                });
                overlay.addClass('disableform');
            }
        });
        var close = $('.close-signup');
        close.on('click', function () {
            overlaysignup.removeClass('openform');
            signupct.parents(overlaysignup).removeClass("openform");
            overlay.removeClass('disableform');
            $(document).on('click', function (e) {
                var target = $(e.target);
                if ($(target).hasClass("overlay-signup")) {
                    $(target).find(signupct).each(function () {
                        $(this).removeClass("openform");
                    });
                    setTimeout(function () {
                        $(target).removeClass("openform");
                    }, 350);
                }
            });
        });
        var mobilefiltercate = $('.filter-cate-mobile');
        mobilefiltercate.each(function (event) {
            var $cate_id = $(this).attr('id'),
                $cate = "#" + $cate_id,
                $dropbtn = $($cate).find('.dropbtn'),
                $mvtabdropcontent = $($cate).find('.dropdown-content');
            $($dropbtn).on('click', function () {
                $mvtabdropcontent.toggleClass('show');
            });
        });
        /*check social login active*/
        if (loginform.find('.apsl-login-networks').length !== 0) {
            loginform.addClass('expand-form');
        }
        function fixSlickHiddenElements() {

    $('.slick-slide[aria-hidden="true"]').find(
        'a, button, input, select, textarea'
    ).attr({
        tabindex: '-1'
    });

    $('.slick-slide[aria-hidden="false"]').find(
        'a, button, input, select, textarea'
    ).removeAttr('tabindex');
}


// initial
fixSlickHiddenElements();
$(document).on(
    'init reInit afterChange setPosition',
    '.slick-slider',
    function () {

        fixSlickHiddenElements();

    }
);
console.log($('#wpcf7-6a0b29f4d2709-field').get(0));
$('.subscriber-verification-field-wrap').find('input').attr(
    'tabindex',
    '-1'
);
$('select.search-movies').attr(
    'aria-label',
    'Search Movies'
);

$('select[name="topsortby"]').attr(
    'aria-label',
    'Sort Movies'
);
 $('#wp-chatbot-ball-container').attr({
        role: 'button',
        tabindex: '0'
    });


    });
    /*LOAD*/
    $(window).on('load', function () {
        HT.menu_mobile();
        HT.postGallery();
        // js for preloading
        var status = $('#status');
        var preloader = $('#preloader');
        var body = $('body');
        status.fadeOut(); // will first fade out the loading animation
        preloader.delay(0).fadeOut('fast'); // will fade out the white DIV that covers the website.
        body.delay(0).css({ 'overflow': 'visible' });
        var vidDefer = document.getElementsByTagName('iframe');
        for (var i = 0; i < vidDefer.length; i++) {
            if (vidDefer[i].getAttribute('data-src')) {
                vidDefer[i].setAttribute('src', vidDefer[i].getAttribute('data-src'));
            }
        }
    });

    $('.sub-menu .menu-item-has-children').on(
        'hover',
        function () {
            var width = $(this).offset().left,
                windowWidth = $(window).width(),
                range = windowWidth - width;
            if (range < 400) {
                $(this).find('.sub-menu').css(
                    {
                        "left": 'auto',
                        "top": "100%",
                        "right": "50%"
                    }
                );
                $(this).find('.boostify-menu-child').css(
                    {
                        "left": 'auto',
                        "top": "100%",
                        "right": "50%"
                    }
                );
            }
        }
    );
    
})(jQuery);



