/**
 * Created by krok on 27.10.15.
 */
(function ($) {
    $.fn.sortableWidget = function (options) {

        var settings = $.extend({
            url: location.href
        }, options);

        var data = this.sortable('serialize', {attribute: 'item-id'});

        $.ajax({
            url: settings.url,
            method: 'POST',
            data: data
        }).done(function (responce) {
            /* todo */
            console.log(responce);
        }).fail(function (responce) {
            /* todo */
            console.log(responce);
        });

    };
})(jQuery);
