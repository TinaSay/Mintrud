/**
 * Created by user on 08.08.2017.
 */

jQuery(function ($) {
    var $form = $('#document-search');

    $('.document-nav-tab a').on('click', function (e) {
        e.preventDefault();
        var target = $($(this).attr('href'));
        if (target.length > 0) {
            if (history.pushState) {
                history.pushState(null, document.title, $(this).attr('href'));
            } else {
                location.hash = $(this).attr('href');
            }
            if (target.data('loaded') !== true) {
                loadDocumentList(target, 1);
            } else {
                console.log('all type', target.data('type'));
                changeType(target.data('type'));
            }
        }
    });

    $('.tab-pane').on('click', '.wrap-pagination a', function (e) {
        e.preventDefault();
        var target = $(this).closest('.tab-pane'),
            page = $(this).data('page') + 1;
        if (target.length > 0) {
            loadDocumentList(target, page);
        }
    });

    if (document.location.hash > '') {
        var link = $('.document-nav-tab a[href="' + document.location.hash + '"]');
        if (link.length) {
            link.tab('show').triggerHandler('click');
        }
    }

    if ($form.length) {
        $form.on('change', function () {
            var type = $form.find('.document-type option:selected').val(),
                oA = $('.document-nav-tab a[data-type="' + type + '"]');
            if (oA.length === 0) {
                oA = $('.document-nav-tab a.all');
            }
            var target = $(oA.attr('href'));
            if (target.length > 0) {
                target.data('type', type);
                target.data('loaded', false);
            }
            oA.tab('show').triggerHandler('click');
        })
    }


    function loadDocumentList(target, page) {
        var url = target.data('url');
        if (target.data('type')) {
            changeType(target.data('type'));
        }
        target.css('opacity', 0.6);
        target.load(url + '?' + $form.serialize() + '&page=' + page, function () {
            target.data('loaded', true);
            target.css('opacity', 1);
        });
    }

    function changeType(type) {
        var select = $form.find('.document-type'),
            option = select.find('option:selected');
        if (option.val() !== type) {
            option.prop('selected', false);
            select.find('option[value="' + type + '"]').prop('selected', true);
            select.selectpicker('refresh');
        }
    }
});