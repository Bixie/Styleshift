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

        defaults: {
            ajaxUrl: '/index.php?option=com_ajax&module=styleshifttool&format=json',
            fbID: '6020912795179',
            token: '',
            eenmalig: [],
            periodiek: [],
            rowTemplate: '',
            prijsTemplate: '<h3 class="bps-prijs-format">&euro; <span>{{prijs}}</span></h3>'
        },

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
            var $this = this, ouputRange;
            this.prijsEls = {};
            this.rowTemplate = $('script[type="text/rowTemplate"]').text();
            this.rowTemplate = UI.Utils.template(this.rowTemplate || this.options.rowTemplate);
            this.prijsTemplate = $('script[type="text/prijsTemplate"]').text();
            this.prijsTemplate = UI.Utils.template(this.prijsTemplate || this.options.prijsTemplate);
            this.setupTable('eenmalig', this.options.eenmalig);
            this.setupTable('periodiek', this.options.periodiek);
            this.on('click', 'input[type=checkbox]', function () {
                $this.doRequest('process', $this.getData());
            });
            ouputRange = this.find('.range-slider .output');
            this.find('input[type=range]').on('input', function () {
                    ouputRange.text($(this).val());
                })
                .on('change', function () {
                    $this.doRequest('process', $this.getData());
                });
            this.find('button.bix-submit').click(function () {
                $(this).find('i').addClass('uk-icon-spin');
                $this.doRequest('submit', $this.getData());
            });
            this.doRequest('process', $this.getData());
        },
        setupTable: function (type, rows) {
            var $this = this, holder = this.find('ul.bix-' + type);
            this.prijsEls[type] = this.find('.bix-prijs-' + type);
            $.each(rows,  function (key) {
                this.naam = key;
                this.type = type;
                holder.append($this.rowTemplate(this));
            });
        },
        getData: function () {
            var data = {};
            this.find('input[type=checkbox]').each(function () {
                var $ele = $(this);
                data[$ele.attr('name')] = $ele.prop('checked') ? 1: 0;
            });
            this.find('input[type=range], input[type=email]').each(function () {
                var $ele = $(this);
                data[$ele.attr('name')] = $ele.val();
            });
            data.opmerking = this.find('textarea').val();
            return data;
        },
        setReturnData: function (returndata) {
            var $this = this;
            $.each(returndata.calculation, function (type) {
                $this.prijsEls[type].html($this.formatPrice(this));
            })
        },
        doRequest: function (method, data) {
            var $this = this, postData = {
                data: data,
                method: method
            };
            postData[this.options.token] = 1;
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: $this.options.ajaxUrl,
                data: postData
            })
                .done(function (response) {
                    if (response.success && response.data) {
                        if (response.data.success) {
                            $this.options.token = response.data.token;
                            $this.setReturnData(response.data);
                        }
                        if (method === 'submit') {
                            window._fbq = window._fbq || [];
                            window._fbq.push(['track', $this.options.fbID, {'value':'0.00','currency':'EUR'}]);
                        }
                    } else if (response.error) {
                        UI.notify({message: response.error, status: 'danger'});
                    }
                })
                .fail(function (jqXHR, textStatus) {
                    if (textStatus !== 'abort') {
                        UI.notify({message: 'Fout in request', status: 'danger'});
                    }
                })
                .always(function (response) {
                    if (response.data) {
                        $.each(response.data.messages, function (type) {
                            var shown = [];
                            $.each(this, function () {
                                if (shown.indexOf(this) === -1) {
                                    UI.notify({message: this, status: type});
                                    shown.push(this);
                                }
                            });
                        });
                    }
                    $this.find('button.bix-submit i').removeClass('uk-icon-spin');
                });
        },
        formatPrice: function (price, full) {
            var fprice = this.formatMoney(price);
            full = full || true;
            if (full) {
                return this.prijsTemplate({prijs: fprice});
            } else {
                return fprice;
            }
        },
        formatMoney: function (price, dec, sep, thou) {
            var sign, fprice, j;
            dec = isNaN(dec = Math.abs(dec)) ? 2 : dec;
            sep = sep || ",";
            thou = thou || ".";
            sign = price < 0 ? "-" : "";
            fprice = parseInt(price = Math.abs(+parseInt(price)).toFixed(dec)) + "";
            j = (j = fprice.length) > 3 ? j % 3 : 0;
            return sign + (j ? fprice.substr(0, j) + thou : "") + fprice.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thou) + (dec ? sep + Math.abs(price - fprice).toFixed(dec).slice(2) : "");
        }

    });

    return UI.bixstyleshifttool;
}));
