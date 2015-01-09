/* *
 *  Styleshift
 *  styleshifttool.js
 *  Created on 9-1-2015 14:27
 *  
 *  @author Matthijs
 *  @copyright Copyright (C)2015 Bixie.nl
 *
 */

(function (addon) {
    "use strict";

    var component;

    if (jQuery && UIkit) {
        component = addon(jQuery, UIkit);
    }

    if (typeof define === "function" && define.amd) {
        define("uikit-bixstyleshifttool", ["uikit"], function () {
            return component || addon(jQuery, UIkit);
        });
    }

}(function ($, UI) {
    "use strict";

    UI.component('bixstyleshifttool', {

        defaults: {},

        boot: function () {
            UI.ready(function (context) {
                $("[data-bix-styleshifttool]", context).each(function () {
                    var $ele = $(this);
                    if (!$ele.data("bixstyleshifttool")) {
                        UI.bixstyleshifttool($ele, UI.Utils.options($ele.attr('data-bix-styleshifttool')));
                    }
                });
            });
        },

        init: function () {
            var $this = this;
console.log(this.options);
        }
    });

    return UI.bixstyleshifttool;
}));
