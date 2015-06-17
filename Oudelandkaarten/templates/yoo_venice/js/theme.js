/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

jQuery(function($) {

    var config = $('html').data('config') || {};

    // Social buttons
    $('article[data-permalink]').socialButtons(config);

    var navbar    = $('.tm-navbar'),
        menuItems = navbar.find('.uk-navbar-nav > li'),
        logo      = $('a.tm-logo'),
        wrapper   = $('.tm-wrapper'),
        middle    = $('.tm-block-middle');

    if (menuItems.length && logo.length && config.centerlogo) {

        menuItems.eq(Math.floor(menuItems.length/2) - 1).after('<li class="tm-sticky-logo" data-uk-dropdown><a>...</a></li>').next().html(logo.clone());
    }

    $.UIkit.$win.on('load', function() {
        var contentHeight = navbar.outerHeight() + wrapper.outerHeight();

        if (contentHeight < $.UIkit.$win.height()) {

            middle.css('min-height', middle.outerHeight());

            $.UIkit.$win.bind('resize', (function(){
                var setHeight = function() {
                    middle.outerHeight(parseInt(middle.css('min-height')) + ($.UIkit.$win.height() - contentHeight));

                    return setHeight;
                };

                return setHeight();
            })());
        }
    });


    if (config.fixednav) {

        $.UIkit.sticky($('.tm-navbar'), (function(){

            var fullscreen = $('.tm-fullscreen'),
                cfg        = {top: 0,media: 960};

            if (fullscreen.length) {
                navbar.addClass('tm-navbar-fullscreen');
                cfg.top = fullscreen.innerHeight() * -1;
                cfg.animation = 'uk-animation-slide-top';
                cfg.clsactive ='uk-active uk-navbar-attached';
            }
            else {
                navbar.addClass('uk-navbar-attached');
                cfg.clsactive = 'uk-active';
            }

            return cfg;

        })());
    }

});