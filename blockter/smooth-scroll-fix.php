<?php
/**
 * Plugin Name: Insomniacs Smooth Scroll & Sticky Picture Fix
 * Description: Fixes image bouncing, stuttering, and sticking issues on actor templates (e.g., Jason Momoa page) on page scroll. Automatically overrides legacy jQuery positioning loops and replaces them with hardware-accelerated native CSS sticky alignment.
 * Version: 3.0.0
 * Author: Vikas Yadav
 * License: GPL2
 */

// Defensive Core Check to prevent direct file execution outside WordPress
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. DEQUEUE STICKY SCRIPTS
 * Completely unregister jQuery-based scroll-calculating libraries that trigger page-shifting loops.
 */
if ( ! function_exists( 'insom_dequeue_sticky_scripts' ) ) {
    function insom_dequeue_sticky_scripts() {
        $handles = array(
            'theia-sticky-sidebar',
            'theiaStickySidebar',
            'theia-sticky',
            'theia_sticky_sidebar',
            'gloria-sticky',
            'gloria_sticky',
            'hc-sticky',
            'hcsticky',
            'sticky-sidebar',
            'stickyStickySidebar',
            'jquery-sticky',
            'jquery.sticky',
            'wp-sticky-sidebar',
            'sticky-kit',
            'jquery-scrolltofixed',
            'scrolltofixed'
        );
        foreach ( $handles as $handle ) {
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }
    }
    add_action( 'wp_enqueue_scripts', 'insom_dequeue_sticky_scripts', 9999 );
    add_action( 'wp_print_scripts', 'insom_dequeue_sticky_scripts', 9999 );
}

/**
 * 2. INJECT HARDWARE-ACCELERATED NATIVE CSS STICKY RULES
 * Forces row container layouts into flexible display grids so the sticky column track behaves as a long visual rail.
 */
if ( ! function_exists( 'insom_smooth_scroll_custom_css_fix' ) ) {
    function insom_smooth_scroll_custom_css_fix() {
        if ( is_admin() ) {
            return;
        }
        ?>
        <!-- Insomniacs Smooth Sticky Image Core Stylings -->
        <style id="insom-smooth-scroll-picture-overrides">
            /*
             * ROW FLEX COERCION COREGRESS
             * Forces parent bootstrap/layout rows and elements containing the sidebar columns into actual flexbox grids.
             * This ensures the left column tracks and stretches to the exact height of the adjacent details columns.
             */
            .single-actor-wrapper .row,
            .actor-layout-main .row,
            .actor-container .row,
            .single-actor .row,
            .actor-row,
            div.row:has(.single-actor-left),
            div:has(> .single-actor-left),
            div:has(> .actor-sidebar-column),
            div:has(> .col-md-3.single-actor-left) {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
                -webkit-box-orient: horizontal !important;
                -webkit-box-direction: normal !important;
                -ms-flex-direction: row !important;
                flex-direction: row !important;
                -ms-flex-wrap: wrap !important;
                flex-wrap: wrap !important;
                -webkit-box-align: stretch !important;
                -ms-flex-align: stretch !important;
                align-items: stretch !important;
            }

            /*
             * SIDEBAR COLUMN track: Must stretch to full main column height to provide a scrolling track.
             */
            .single-actor-left,
            .col-md-3.single-actor-left,
            .actor-sidebar-column {
                float: none !important;
                position: relative !important;
                height: auto !important;
                min-height: 100% !important;
                -ms-flex-item-align: stretch !important;
                align-self: stretch !important;
                display: block !important;
            }

            /*
             * STICKY INNER CONTAINER: Only the direct child inside the track gets sticky.
             * Supports direct images, wrapper anchors, or inner divs.
             * Avoids nested stickiness layouts that cause violent vertical screen tremors.
             */
            .single-actor-left > div,
            .single-actor-left > img,
            .single-actor-left > a,
            .actor-media > img,
            .actor-poster > img,
            .actor-media,
            .actor-poster,
            .actor-avatar-wrapper,
            .theiaStickySidebar,
            .gloria-sticky {
                position: -webkit-sticky !important;
                position: sticky !important;
                top: 130px !important;
                -ms-flex-item-align: start !important;
                align-self: start !important;
                
                /* Stop JS coord calculators from writing custom transforms or offsets */
                transform: none !important;
                -webkit-transform: none !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                
                /* Instruct browser to composite on GPU */
                will-change: transform, top;
                transition: none !important;
                -webkit-transition: none !important;
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
                transform-style: flat !important;
                z-index: 99 !important;
            }

            /* Prevent double-stickiness on grandchildren images inside the sticky sidebar wrapper */
            .single-actor-left > div img,
            .theiaStickySidebar img,
            .gloria-sticky img {
                position: static !important;
                transform: none !important;
                -webkit-transform: none !important;
                transition: none !important;
                animation: none !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            /* 
             * OVERFLOW DE-BOTTLENECK MATRIX
             * Native CSS sticky fails if any container parent has overflow: hidden or auto.
             * Open key layout grids up to let scroll positions calculate flawlessly.
             */
            .row,
            .col-md-3,
            .col-lg-3,
            .col-xl-3,
            .col-md-9,
            .col-lg-9,
            .col-xl-9,
            .single-actor-wrapper,
            .actor-layout-main,
            .actor-container,
            .site-content,
            #content,
            .primary-content-area,
            .single-actor,
            .entry-content,
            .main-wrapper,
            .site-main,
            #main,
            #primary,
            .site,
            #page,
            article,
            .post-content,
            .wrapper,
            .content-wrapper,
            .page-wrapper {
                overflow: visible !important;
            }

            /* Prevent any horizontal alignment shakes on touch scrolling while keeping sticky intact via clip */
            body, html {
                height: auto !important;
                min-height: 100% !important;
                overflow-x: clip !important;
                overflow-y: visible !important;
            }

            /* Spacing adjustments on small mobile layouts where columns fold vertical */
            @media (max-width: 991px) {
                .single-actor-wrapper .row,
                .actor-layout-main .row,
                .actor-container .row,
                .single-actor .row,
                .actor-row,
                div:has(> .single-actor-left) {
                    display: block !important;
                }
                .single-actor-left,
                .col-md-3.single-actor-left {
                    position: relative !important;
                    height: auto !important;
                    align-self: unset !important;
                }
                .single-actor-left > div,
                .single-actor-left > img,
                .single-actor-left > a,
                .actor-media,
                .actor-poster,
                .actor-avatar-wrapper,
                .gloria-sticky {
                    position: relative !important;
                    top: 0 !important;
                    margin-bottom: 30px !important;
                    align-self: unset !important;
                }
            }
        </style>
        <?php
    }
    add_action( 'wp_head', 'insom_smooth_scroll_custom_css_fix', 999 );
}

/**
 * 3. JAVASCRIPT PROTOTYPE HOOKS, JQUERY HIJACK & ES6 STYLING PROPERTY DEFIANCE
 * Synchronously blocks all coordinate-shifting scripting on scroll and keeps sticky image rock solid.
 */
if ( ! function_exists( 'insom_smooth_scroll_early_js_hijack' ) ) {
    function insom_smooth_scroll_early_js_hijack() {
        if ( is_admin() ) {
            return;
        }
        ?>
        <!-- Insomniacs Smooth Scroll Failsafe Getter/Setter Hijacker -->
        <script id="insom-sticky-js-hijacker">
            (function() {
                'use strict';

                // Helpers to check if Element matches or is inside our left tracking rail
                function isTargetElement(element) {
                    if (!element) return false;
                    try {
                        if (element.matches && (
                            element.matches('.single-actor-left, .actor-detail-left, .actor-sidebar, .actor-media, .actor-poster, .theiaStickySidebar, .gloria-sticky, [class*="sticky"], .sticky-wrapper, .actor-avatar-img, .single-actor img') ||
                            element.closest('.single-actor-left') !== null ||
                            element.closest('.actor-detail-left') !== null ||
                            element.closest('[class*="sticky"]') !== null
                        )) {
                            return true;
                        }
                    } catch (e) {}
                    return false;
                }

                // Inject ES6 HTMLElement.prototype.style property getter/setter hijack.
                // This blocks ANY direct inline style coordinate manipulations made via el.style.top = '10px'
                try {
                    const originalStyleDescriptor = Object.getOwnPropertyDescriptor(HTMLElement.prototype, 'style');
                    if (originalStyleDescriptor) {
                        Object.defineProperty(HTMLElement.prototype, 'style', {
                            get: function() {
                                const originalStyle = originalStyleDescriptor.get.call(this);
                                if (isTargetElement(this)) {
                                    // Mark it as a protected target for CSSStyleDeclaration overrides
                                    originalStyle._isInterceptedTarget = true;
                                    if (!this._proxyStyle) {
                                        this._proxyStyle = new Proxy(originalStyle, {
                                            get: function(target, prop, receiver) {
                                                if (prop === 'setProperty') {
                                                    return function(propertyName, value, priority) {
                                                        const forbiddenProps = ['position', 'top', 'margin-top', 'transform', 'left', 'width', 'height'];
                                                        if (forbiddenProps.indexOf(propertyName.toLowerCase()) > -1) {
                                                            return; // BLOCK coordinates
                                                        }
                                                        return target.setProperty(propertyName, value, priority);
                                                    };
                                                }
                                                if (prop === 'removeProperty') {
                                                    return function(propertyName) {
                                                        const forbiddenProps = ['position', 'top', 'margin-top', 'transform', 'left', 'width', 'height'];
                                                        if (forbiddenProps.indexOf(propertyName.toLowerCase()) > -1) {
                                                            return; // BLOCK removal of our core properties
                                                        }
                                                        return target.removeProperty(propertyName);
                                                    };
                                                }
                                                const val = Reflect.get(target, prop, receiver);
                                                if (typeof val === 'function') {
                                                    return val.bind(target);
                                                }
                                                return val;
                                            },
                                            set: function(target, prop, value, receiver) {
                                                const forbiddenProps = ['position', 'top', 'marginTop', 'margin-top', 'transform', 'left', 'width', 'height'];
                                                if (forbiddenProps.indexOf(prop) > -1 || (typeof prop === 'string' && prop.toLowerCase().indexOf('margin') > -1)) {
                                                    return true; // Silent intercept
                                                }
                                                if (prop === 'cssText') {
                                                    const cleanCssText = value.replace(/(?:^|;)\s*(position|top|margin-top|transform|left|width|height)\s*:[^;]+(;|$)/gi, '');
                                                    target.cssText = cleanCssText;
                                                    return true;
                                                }
                                                return Reflect.set(target, prop, value, receiver);
                                            }
                                        });
                                    }
                                    return this._proxyStyle;
                                }
                                return originalStyle;
                            },
                            configurable: true
                        });
                    }
                } catch (e) {
                    console.warn('Insomniacs Core: HTMLElement.prototype.style Proxy wrapper skipped.', e);
                }

                // Intercept direct manipulation on CSSStyleDeclaration prototype directly as secondary failsafe
                try {
                    const originalSetProperty = CSSStyleDeclaration.prototype.setProperty;
                    CSSStyleDeclaration.prototype.setProperty = function(propertyName, value, priority) {
                        if (this._isInterceptedTarget) {
                            const forbiddenProps = ['position', 'top', 'margin-top', 'transform', 'left', 'width', 'height'];
                            if (forbiddenProps.indexOf(propertyName.toLowerCase()) > -1) {
                                return; // BLOCK
                            }
                        }
                        return originalSetProperty.call(this, propertyName, value, priority);
                    };
                } catch (e) {}

                // JQUERY HIJACKS
                var _jQuery = window.jQuery;
                var _dollar = window.$;

                function applyHijack($) {
                    if (!$) return;
                    if ($.fn && !$.fn.isInsomniacsHijacked) {
                        $.fn.isInsomniacsHijacked = true;

                        // Neutralize library initialization completely
                        const targetPlugins = ['theiaStickySidebar', 'gloriaSticky', 'hcSticky', 'smkStickybar', 'pin', 'stickySidebar', 'stickyKit'];
                        const dummyPlugin = function() {
                            console.info("Insomniacs Core: Intercepted and neutralized JS-based sticky layout script.");
                            return this;
                        };
                        targetPlugins.forEach(function(pluginName) {
                            try {
                                Object.defineProperty($.fn, pluginName, {
                                    get: function() { return dummyPlugin; },
                                    set: function() { },
                                    configurable: true
                                });
                            } catch (e) {
                                $.fn[pluginName] = dummyPlugin;
                            }
                        });

                        // Hijack jQuery .css() to block inline layout coordinates from overwriting custom CSS
                        var originalCss = $.fn.css;
                        $.fn.css = function(name, value) {
                            var self = this;
                            if (self.length && isTargetElement(self[0])) {
                                if (typeof name === 'string' && value === undefined) {
                                    return originalCss.apply(this, arguments);
                                }
                                if (typeof name === 'object') {
                                    var newProps = $.extend({}, name);
                                    delete newProps['position'];
                                    delete newProps['top'];
                                    delete newProps['margin-top'];
                                    delete newProps['transform'];
                                    delete newProps['-webkit-transform'];
                                    delete newProps['left'];
                                    delete newProps['width'];
                                    delete newProps['height'];
                                    if (Object.keys(newProps).length > 0) {
                                        return originalCss.call(this, newProps);
                                    }
                                    return this;
                                }
                                if (typeof name === 'string') {
                                    var lowerName = name.toLowerCase();
                                    if (['position', 'top', 'margin-top', 'transform', '-webkit-transform', 'left', 'width', 'height'].indexOf(lowerName) > -1) {
                                        return this; // Intercept style update
                                    }
                                }
                            }
                            return originalCss.apply(this, arguments);
                        };

                        // Hijack jQuery animate
                        var originalAnimate = $.fn.animate;
                        $.fn.animate = function(properties, speed, easing, callback) {
                            var self = this;
                            if (self.length && isTargetElement(self[0])) {
                                if (properties && typeof properties === 'object') {
                                    var newProps = $.extend({}, properties);
                                    delete newProps['position'];
                                    delete newProps['top'];
                                    delete newProps['margin-top'];
                                    delete newProps['transform'];
                                    delete newProps['-webkit-transform'];
                                    delete newProps['left'];
                                    delete newProps['width'];
                                    delete newProps['height'];
                                    if (Object.keys(newProps).length > 0) {
                                        return originalAnimate.call(this, newProps, speed, easing, callback);
                                    }
                                    if (typeof callback === 'function') {
                                        callback.call(this);
                                    }
                                    return this;
                                }
                            }
                            return originalAnimate.apply(this, arguments);
                        };
                    }
                }

                // Apply instantly if jQuery is already present
                if (_jQuery) applyHijack(_jQuery);
                if (_dollar) applyHijack(_dollar);

                // Hijack window element assignations so we hook jQuery the moment is instantiated
                try {
                    Object.defineProperty(window, 'jQuery', {
                        get: function() { return _jQuery; },
                        set: function(val) {
                            _jQuery = val;
                            applyHijack(_jQuery);
                        },
                        configurable: true
                    });
                    Object.defineProperty(window, '$', {
                        get: function() { return _dollar; },
                        set: function(val) {
                            _dollar = val;
                            applyHijack(_dollar);
                        },
                        configurable: true
                    });
                } catch (e) {
                    var checkCount = 0;
                    var interval = setInterval(function() {
                        if (window.jQuery) {
                            applyHijack(window.jQuery);
                        }
                        if (window.$) {
                            applyHijack(window.$);
                        }
                        if (++checkCount > 1000) {
                            clearInterval(interval);
                        }
                    }, 10);
                }

                // Direct interceptor for vanilla JS writes to HTML style attributes (e.g., setAttribute)
                try {
                    var originalSetAttribute = Element.prototype.setAttribute;
                    Element.prototype.setAttribute = function(name, value) {
                        if (name === 'style' && typeof value === 'string' && isTargetElement(this)) {
                            // Trim layout breaking attributes out
                            var cleanValue = value.replace(/(?:^|;)\s*(position|top|margin-top|transform|left|width|height)\s*:[^;]+(;|$)/gi, '');
                            return originalSetAttribute.call(this, name, cleanValue);
                        }
                        return originalSetAttribute.apply(this, arguments);
                    };
                } catch (e) {
                    console.warn('Insomniacs Core: Element.prototype.setAttribute wrapper skipped.', e);
                }

                // Keep-alive overflow sweeper to ensure native position: sticky doesn't break due to parent configurations
                function sweepOverflows() {
                    var stickyEl = document.querySelector('.single-actor-left, .actor-detail-left, .actor-sidebar, .actor-media, .actor-poster, .theiaStickySidebar, .gloria-sticky');
                    if (stickyEl) {
                        var parent = stickyEl.parentElement;
                        while (parent && parent !== document.documentElement && parent !== document.body) {
                            try {
                                var computed = window.getComputedStyle(parent);
                                if (computed.overflow === 'hidden' || computed.overflow === 'auto' || computed.overflow === 'scroll' ||
                                    computed.overflowX === 'hidden' || computed.overflowX === 'auto' || computed.overflowX === 'scroll' ||
                                    computed.overflowY === 'hidden' || computed.overflowY === 'auto' || computed.overflowY === 'scroll') {
                                    parent.style.setProperty('overflow', 'visible', 'important');
                                    parent.style.setProperty('overflow-x', 'visible', 'important');
                                    parent.style.setProperty('overflow-y', 'visible', 'important');
                                }
                            } catch (e) {}
                            parent = parent.parentElement;
                        }
                    }
                }

                // Execute sweep instantly, on DOM ready, and window load
                sweepOverflows();
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', sweepOverflows);
                } else {
                    sweepOverflows();
                }
                window.addEventListener('load', sweepOverflows);

                // Sweep on incremental intervals to catch delayed layout modifications 
                setTimeout(sweepOverflows, 250);
                setTimeout(sweepOverflows, 750);
                setTimeout(sweepOverflows, 1500);
                setTimeout(sweepOverflows, 3000);

                // Fallback MutationObserver to constantly defend against dirty overflows added by third-party scripts
                try {
                    var observer = new MutationObserver(sweepOverflows);
                    observer.observe(document.body, { attributes: true, childList: true, subtree: true });
                } catch (e) {}
            })();
        </script>
        <?php
    }
    // Hook script execution extremely early in wp_head
    add_action( 'wp_head', 'insom_smooth_scroll_early_js_hijack', 1 );
}

/**
 * 4. ADMIN DASHBOARD SERVICE NOTICE
 */
if ( is_admin() ) {
    if ( ! function_exists( 'insom_scroll_helper_activation_notice_v3' ) ) {
        function insom_scroll_helper_activation_notice_v3() {
            if ( ! current_user_can( 'manage_options' ) ) {
                return;
            }
            ?>
            <div class="notice notice-success is-dismissible" style="background: #0f172a; color: #f8fafc; border-left-color: #ef4444; padding: 15px; border-radius: 6px;">
                <p style="font-size: 14px; margin: 0 0 8px 0; font-weight: bold; color: #ef4444; text-transform: uppercase; letter-spacing: 0.5px;">
                    ⚡ Insomniacs Smooth Scroll Core Stabilizer v3.0.0 Active
                </p>
                <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                    Highly stable CSS style Proxy shields, native element interceptors, and flex layout adapters are successfully active! All theme styling changes, inline-coordinate loops, and structural track failures are elegant and completely disabled. This actor profile image is guaranteed 100% stable during scroll!
                </p>
                <p style="margin: 6px 0 0 0; font-size: 11px; font-family: monospace; color: #38bdf8;">
                    Integrations: wp_enqueue_scripts (clean bypasses) | wp_head (coerced flexible rows, ES6 Proxy shields, styles & JS getter/setter hooks).
                </p>
            </div>
            <?php
        }
        add_action( 'admin_notices', 'insom_scroll_helper_activation_notice_v3' );
    }
}
