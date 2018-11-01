/**
 * Created by krok on 02.07.17.
 */

jQuery(function ($) {

    var $body = $('body');

    $('[data-ajax]').on('click', function (e) {
        e.preventDefault();

        var $a = $(this);

        $.ajax({
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

    $('[form-ajax]').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);

        $.ajax({
            url: $form.attr('action'),
            method: $form.attr('method'),
            data: $form.serialize(),
            cache: false,
            beforeSend: function () {
                $form.trigger('ajax.beforeSend');
            }
        }).success(function (data) {
            $form.trigger('ajax.success', [data]);
        });
    });

    $body.on('blind.set.fontSize', function (e, fontSize) {
        $.cookie('blind-fontSize', fontSize, {expires: 30, path: '/'});
    });

    $body.on('blind.set.colorSchema', function (e, colorScheme) {
        $.cookie('blind-colorSchema', colorScheme, {expires: 30, path: '/'});
    });

    $body.on('blind.unset.fontSize', function (e, fontSize) {
        $.cookie('blind-fontSize', fontSize, {expires: 30, path: '/'});
    });

    $body.on('blind.unset.colorSchema', function (e, colorScheme) {
        $.cookie('blind-colorSchema', colorScheme, {expires: 30, path: '/'});
    });

    $('.post-list.text-black.post-list--with-delete').on('ajax.success', '.link-delete', function (e) {
        e.preventDefault();
        $(this).closest('.post-list.text-black.post-list--with-delete').remove();
    });
}(jQuery));
