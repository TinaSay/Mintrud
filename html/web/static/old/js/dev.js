/**
 * Created by krok on 02.07.17.
 */

jQuery(function () {
    jQuery('[data-ajax]').on('click', function (e) {
        e.preventDefault();

        var $a = jQuery(this);

        jQuery.ajax({
            url: $a.attr('href'),
            method: 'GET',
            cache: false,
            beforeSend: function () {
                $a.trigger('ajax.beforeSend');
            }
        }).success(function (data) {
            $a.trigger('ajax.success', [data]);
        });
    });
});
