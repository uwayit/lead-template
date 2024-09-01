


$(document).on('DOMContentLoaded', function () {

    //"use strict";

    /* ==============================================
    AFFIX
    =============================================== */
    $('.megamenu').affix({
        offset: {
            top: 800,
            bottom: function() {
                return (this.bottom = $('.footer').outerHeight(true))
            }
        }
    })

    /* ==============================================
    BACK TOP
    =============================================== */
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > 1) {
            jQuery('.dmtop').css({
                bottom: "75px"
            });
        } else {
            jQuery('.dmtop').css({
                bottom: "-100px"
            });
        }
    });

    /* ==============================================
       LOADER -->
        =============================================== */



    /* ==============================================
     FUN FACTS -->
     =============================================== */

    function count($this) {
        var current = parseInt($this.html(), 10);
        current = current + 50; /* Where 50 is increment */
        $this.html(++current);
        if (current > $this.data('count')) {
            $this.html($this.data('count'));
        } else {
            setTimeout(function() {
                count($this)
            }, 30);
        }
    }
    $(".stat_count, .stat_count_download").each(function() {
        $(this).data('count', parseInt($(this).html(), 10));
        $(this).html('0');
        count($(this));
    });

    /* ==============================================
     TOOLTIP -->
     =============================================== */
    //$('[data-toggle="tooltip"]').tooltip()
    //$('[data-toggle="popover"]').popover()

    /* ==============================================
     CONTACT -->
     =============================================== */

        $('#contactform').submit(function() {
            var action = $(this).attr('action');
            $("#message").slideUp(750, function() {
                $('#message').hide();
                $('#submit')
                    .after('<img src="" class="loader" />')
                    .attr('disabled', 'disabled');
                $.post(action, {
                        first_name: $('#first_name').val(),
                        last_name: $('#last_name').val(),
                        email: $('#email').val(),
                        phone: $('#phone').val(),
                        select_service: $('#select_service').val(),
                        select_price: $('#select_price').val(),
                        comments: $('#comments').val(),
                        verify: $('#verify').val()
                    },
                    function(data) {
                        document.getElementById('message').innerHTML = data;
                        $('#message').slideDown('slow');
                        $('#contactform img.loader').fadeOut('slow', function() {
                            $(this).remove()
                        });
                        $('#submit').removeAttr('disabled');
                        if (data.match('success') != null) $('#contactform').slideUp('slow');
                    }
                );
            });
            return false;
        });


    /* ==============================================
     CODE WRAPPER -->
     =============================================== */

    $('.code-wrapper').on("mousemove", function(e) {
        var offsets = $(this).offset();
        var fullWidth = $(this).width();
        var mouseX = e.pageX - offsets.left;

        if (mouseX < 0) {
            mouseX = 0;
        } else if (mouseX > fullWidth) {
            mouseX = fullWidth
        }

        $(this).parent().find('.divider-bar').css({
            left: mouseX,
            transition: 'none'
        });
        $(this).find('.design-wrapper').css({
            transform: 'translateX(' + (mouseX) + 'px)',
            transition: 'none'
        });
        $(this).find('.design-image').css({
            transform: 'translateX(' + (-1 * mouseX) + 'px)',
            transition: 'none'
        });
    });
    $('.divider-wrapper').on("mouseleave", function() {
        $(this).parent().find('.divider-bar').css({
            left: '50%',
            transition: 'all .3s'
        });
        $(this).find('.design-wrapper').css({
            transform: 'translateX(50%)',
            transition: 'all .3s'
        });
        $(this).find('.design-image').css({
            transform: 'translateX(-50%)',
            transition: 'all .3s'
        });
    });





/* ==============================================
MAP -->
=============================================== */
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
        $("#map").on(500).fadeIn();
var locations = [['<div class="infobox"><h3 class="title"><a href="#">OUR USA OFFICE</a></h3><span>NEW YORK CITY 2045 / 65</span><span>+90 555 666 77 88</span></div>',
    52.370216,
    4.895168,
    2]];
var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 14, scrollwheel: false, navigationControl: true, mapTypeControl: false, scaleControl: false, draggable: true, styles: [{
        "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{ "featureType": "poi.business", "elementType": "geometry.fill", "stylers": [{ "visibility": "on" }] }]
    }
    ], center: new google.maps.LatLng(52.370216, 4.895168), mapTypeId: google.maps.MapTypeId.ROADMAP
}

);
var infowindow = new google.maps.InfoWindow();
var marker,
    i;
for (i = 0;
    i < locations.length;
    i++) {
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]), map: map, icon: ''
    }
    );
    google.maps.event.addListener(marker, 'click', (function (marker, i) {
        return function () {
            infowindow.setContent(locations[i][0]);
            infowindow.open(map, marker);
        }
    }
    )(marker, i));
}
    }

    // email-decode.min
    !function () { "use strict"; function e(e) { try { if ("undefined" == typeof console) return; "error" in console ? console.error(e) : console.log(e) } catch (e) { } } function t(e) { return d.innerHTML = '<a href="' + e.replace(/"/g, "&quot;") + '"></a>', d.childNodes[0].getAttribute("href") || "" } function r(e, t) { var r = e.substr(t, 2); return parseInt(r, 16) } function n(n, c) { for (var o = "", a = r(n, c), i = c + 2; i < n.length; i += 2) { var l = r(n, i) ^ a; o += String.fromCharCode(l) } try { o = decodeURIComponent(escape(o)) } catch (u) { e(u) } return t(o) } function c(t) { for (var r = t.querySelectorAll("a"), c = 0; c < r.length; c++)try { var o = r[c], a = o.href.indexOf(l); a > -1 && (o.href = "mailto:" + n(o.href, a + l.length)) } catch (i) { e(i) } } function o(t) { for (var r = t.querySelectorAll(u), c = 0; c < r.length; c++)try { var o = r[c], a = o.parentNode, i = o.getAttribute(f); if (i) { var l = n(i, 0), d = document.createTextNode(l); a.replaceChild(d, o) } } catch (h) { e(h) } } function a(t) { for (var r = t.querySelectorAll("template"), n = 0; n < r.length; n++)try { i(r[n].content) } catch (c) { e(c) } } function i(t) { try { c(t), o(t), a(t) } catch (r) { e(r) } } var l = "/cdn-cgi/l/email-protection#", u = ".__cf_email__", f = "data-cfemail", d = document.createElement("div"); i(document), function () { var e = document.currentScript || document.scripts[document.scripts.length - 1]; e.parentNode.removeChild(e) }() }();


    /* ==============================================
    LOADER -->
    =============================================== */

    // Встановлюємо маску на поле з телефоном
    // зробив лише під GB для зручності умовного користувача
    if (typeof $.fn.mask === 'function') {
        $('.phone').mask('+000000000000');
    }

    // Скріпти завантажено, показуємо сайт
    $("#preloader").on(1500).fadeOut();
    $(".preloader").on(1000).fadeOut("slow");
    

});

/**
 * jquery.hoverdir.js v1.1.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2012, Codrops
 * http://www.codrops.com
 */
; (function ($, window, undefined) {

    'use strict';

    $.HoverDir = function (options, element) {

        this.$el = $(element);
        this._init(options);

    };

    // the options
    $.HoverDir.defaults = {
        speed: 300,
        easing: 'ease',
        hoverDelay: 0,
        inverse: false
    };

    $.HoverDir.prototype = {

        _init: function (options) {

            // options
            this.options = $.extend(true, {}, $.HoverDir.defaults, options);
            // transition properties
            this.transitionProp = 'all ' + this.options.speed + 'ms ' + this.options.easing;
            // support for CSS transitions
            this.support = Modernizr.csstransitions;
            // load the events
            this._loadEvents();

        },
        _loadEvents: function () {

            var self = this;

            this.$el.on('mouseenter.hoverdir, mouseleave.hoverdir', function (event) {

                var $el = $(this),
                    $hoverElem = $el.find('div'),
                    direction = self._getDir($el, { x: event.pageX, y: event.pageY }),
                    styleCSS = self._getStyle(direction);

                if (event.type === 'mouseenter') {

                    $hoverElem.hide().css(styleCSS.from);
                    clearTimeout(self.tmhover);

                    self.tmhover = setTimeout(function () {

                        $hoverElem.show(0, function () {

                            var $el = $(this);
                            if (self.support) {
                                $el.css('transition', self.transitionProp);
                            }
                            self._applyAnimation($el, styleCSS.to, self.options.speed);

                        });


                    }, self.options.hoverDelay);

                }
                else {

                    if (self.support) {
                        $hoverElem.css('transition', self.transitionProp);
                    }
                    clearTimeout(self.tmhover);
                    self._applyAnimation($hoverElem, styleCSS.from, self.options.speed);

                }

            });

        },
        // credits : http://stackoverflow.com/a/3647634
        _getDir: function ($el, coordinates) {

            // the width and height of the current div
            var w = $el.width(),
                h = $el.height(),

                // calculate the x and y to get an angle to the center of the div from that x and y.
                // gets the x value relative to the center of the DIV and "normalize" it
                x = (coordinates.x - $el.offset().left - (w / 2)) * (w > h ? (h / w) : 1),
                y = (coordinates.y - $el.offset().top - (h / 2)) * (h > w ? (w / h) : 1),

                // the angle and the direction from where the mouse came in/went out clockwise (TRBL=0123);
                // first calculate the angle of the point,
                // add 180 deg to get rid of the negative values
                // divide by 90 to get the quadrant
                // add 3 and do a modulo by 4  to shift the quadrants to a proper clockwise TRBL (top/right/bottom/left) **/
                direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;

            return direction;

        },
        _getStyle: function (direction) {

            var fromStyle, toStyle,
                slideFromTop = { left: '0px', top: '-100%' },
                slideFromBottom = { left: '0px', top: '100%' },
                slideFromLeft = { left: '-100%', top: '0px' },
                slideFromRight = { left: '100%', top: '0px' },
                slideTop = { top: '0px' },
                slideLeft = { left: '0px' };

            switch (direction) {
                case 0:
                    // from top
                    fromStyle = !this.options.inverse ? slideFromTop : slideFromBottom;
                    toStyle = slideTop;
                    break;
                case 1:
                    // from right
                    fromStyle = !this.options.inverse ? slideFromRight : slideFromLeft;
                    toStyle = slideLeft;
                    break;
                case 2:
                    // from bottom
                    fromStyle = !this.options.inverse ? slideFromBottom : slideFromTop;
                    toStyle = slideTop;
                    break;
                case 3:
                    // from left
                    fromStyle = !this.options.inverse ? slideFromLeft : slideFromRight;
                    toStyle = slideLeft;
                    break;
            };

            return { from: fromStyle, to: toStyle };

        },
        // apply a transition or fallback to jquery animate based on Modernizr.csstransitions support
        _applyAnimation: function (el, styleCSS, speed) {

            $.fn.applyStyle = this.support ? $.fn.css : $.fn.animate;
            el.stop().applyStyle(styleCSS, $.extend(true, [], { duration: speed + 'ms' }));

        },

    };

    var logError = function (message) {

        if (window.console) {

            window.console.error(message);

        }

    };

    $.fn.hoverdir = function (options) {

        var instance = $.data(this, 'hoverdir');

        if (typeof options === 'string') {

            var args = Array.prototype.slice.call(arguments, 1);

            this.each(function () {

                if (!instance) {

                    logError("cannot call methods on hoverdir prior to initialization; " +
                        "attempted to call method '" + options + "'");
                    return;

                }

                if (!$.isFunction(instance[options]) || options.charAt(0) === "_") {

                    logError("no such method '" + options + "' for hoverdir instance");
                    return;

                }

                instance[options].apply(instance, args);

            });

        }
        else {

            this.each(function () {

                if (instance) {

                    instance._init();

                }
                else {

                    instance = $.data(this, 'hoverdir', new $.HoverDir(options, this));

                }

            });

        }

        return instance;

    };

})(jQuery, window);

$(function () {
    $('.pitem ').each(function () { $(this).hoverdir(); });
});




(function ($) {
    var $container = $('.portfolio'),
        colWidth = function () {
            var w = $container.width(),
                columnNum = 1,
                columnWidth = 50;
            if (w > 1200) {
                columnNum = 5;
            }
            else if (w > 900) {
                columnNum = 3;
            }
            else if (w > 600) {
                columnNum = 2;
            }
            else if (w > 300) {
                columnNum = 1;
            }
            columnWidth = Math.floor(w / columnNum);
            $container.find('.pitem').each(function () {
                var $item = $(this),
                    multiplier_w = $item.attr('class').match(/item-w(\d)/),
                    multiplier_h = $item.attr('class').match(/item-h(\d)/),
                    width = multiplier_w ? columnWidth * multiplier_w[1] - 0 : columnWidth - 5,
                    height = multiplier_h ? columnWidth * multiplier_h[1] * 1 - 5 : columnWidth * 0.5 - 5;
                $item.css({
                    width: width,
                    height: height
                });
            });
            return columnWidth;
        }
    function refreshWaypoints() {
        setTimeout(function () {
        }, 3000);
    }
    $('nav.portfolio-filter ul a').on('click', function () {
        var selector = $(this).attr('data-filter');
        $container.isotope({ filter: selector }, refreshWaypoints());
        $('nav.portfolio-filter ul a').removeClass('active');
        $(this).addClass('active');
        return false;
    });
    function setPortfolio() {
        setColumns();
        $container.isotope('reLayout');
    }
    $container.imagesLoaded(function () {
        $container.isotope();
    });
    isotope = function () {
        $container.isotope({
            resizable: true,
            itemSelector: '.pitem',
            layoutMode: 'masonry',
            gutter: 10,
            masonry: {
                columnWidth: colWidth(),
                gutterWidth: 0
            }
        });
    };
    isotope();
    $(window).smartresize(isotope);
}(jQuery));