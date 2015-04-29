
var checkReservering;

jQuery(function ($) {
    "use strict";
    var messages = [
            {
                message: '',
                icon: 'info',
                style: '',
                data: []
            } , {
                message: 'Op deze dag een kerstmenu!',
                icon: 'check',
                style: 'uk-alert-success',
                data: ['25-12-2015']
            }
        ],
        $messages = $('#ftr-datum');
    checkReservering = function ($datum) {
        $messages.find('.uk-alert').remove();
        $.each(messages, function () {
            if (this.data.indexOf($datum.val()) !== -1) {
                var icon = this.icon ? '<i class="uk-icon-' + this.icon + ' uk-margin-small-right"></i>' : '';
                $messages.append('<div class="uk-alert uk-width-1-1 uk-margin-top-remove ' + this.style + '">' + icon + this.message + '</div>');
            }
        });
    }
});