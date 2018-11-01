/**
 * Created by krok on 18.07.17.
 */

jQuery(function ($) {
    var $body = $('body');

    $body.on('blind.set.fontSize', function (e, fontSize) {
        $.ajax({
            url: '/cabinet/blind-configure/font-size',
            method: 'GET',
            cache: false,
            data: 'value=' + fontSize
        });
    });

    $body.on('blind.set.colorSchema', function (e, colorSchema) {
        $.ajax({
            url: '/cabinet/blind-configure/color-schema',
            method: 'GET',
            cache: false,
            data: 'value=' + colorSchema
        });
    });

    $body.on('blind.unset.fontSize', function (e, fontSize) {
        $.ajax({
            url: '/cabinet/blind-configure/font-size',
            method: 'GET',
            cache: false,
            data: 'value=' + fontSize
        });
    });

    $body.on('blind.unset.colorSchema', function (e, colorSchema) {
        $.ajax({
            url: '/cabinet/blind-configure/color-schema',
            method: 'GET',
            cache: false,
            data: 'value=' + colorSchema
        });
    });
}(jQuery));
