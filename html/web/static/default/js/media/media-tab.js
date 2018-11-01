/**
 * Created by user on 08.08.2017.
 */

jQuery(function ($) {
    var queryParams = location.hash.replace('#', '').split('&').reduce(function (s, c) {
            var t = c.split('=');
            s[t[0]] = t[1];
            return s;
        }, {}),
        parsedUrl = {tab: false, page: queryParams.page ? queryParams.page : 1, gallery: false},
        form = $('#media-search'),
        selectDirectory = form.find('#media-directory');

    for (var i in queryParams) {
        if (queryParams.hasOwnProperty(i)) {
            if (/tab_/i.test(i)) {
                parsedUrl.tab = i;
            } else if (/gallery/i.test(i)) {
                parsedUrl.gallery = i.replace(/([\d]+)-([\d]+)$/i, '$1');
            }
        }
    }

    $('.media-nav-tab a').on('click', function (e) {
        e.preventDefault();
        var $this = $(this),
            target = $($this.attr('href'));

        if (target.data('type')) {
            selectDirectory.val(target.data('type'));
            selectDirectory.selectpicker('refresh');
        } else {
            selectDirectory.val('');
            selectDirectory.selectpicker('refresh');
        }
        history.pushState(null, null, $(this).attr('href'));
        if (target.length > 0) {
            var url = $(this).attr('href') +
                (parsedUrl.page > 1 ? '&page=' + parsedUrl.page : '') +
                (parsedUrl.gallery ? '&' + parsedUrl.gallery : '');
            if (history.pushState) {
                history.pushState(null, document.title, url);
            } else {
                location.hash = url;
            }
            if (target.data('loaded') !== true) {
                loadMediaList(target, parsedUrl.page);
            }
        }
    });

    $('.tab-pane').on('click', '.wrap-pagination a', function (e) {
        e.preventDefault();
        var target = $(this).closest('.tab-pane'),
            page = $(this).data('page') + 1;
        if (target.length > 0) {
            loadMediaList(target, page);
        }
    });

    if (parsedUrl.tab) {
        var link = $('.media-nav-tab a[href="#' + parsedUrl.tab + '"]');
        if (link.length) {
            link.tab('show').triggerHandler('click');
        }
    }

    function getTab(directory) {
        return $('.custom-tab-item a[href="#tab_media_' + directory + '"]');
    }

    if (form.length) {
        form.on('change', function () {
            $('.tab-pane-media').data('loaded', false);
            var directory = selectDirectory.val();
            var tab = getTab(directory);
            if (!tab.length) {
                tab = getTab('all');
            }
            tab.trigger('click');
        });
        form.on('submit', function () {
            // form.trigger('change');
            return false;
        });
    }


    function loadMediaList(target, page) {
        target.css('opacity', 0.6);
        target.load(form.attr('action') + '?' + form.serialize() + '&page=' + page, function () {
            target.data('loaded', true);
            target.css('opacity', 1);

            if (parsedUrl.gallery) {
                $.fancybox.open(target.find('[data-fancybox=' + parsedUrl.gallery + ']'), {
                    hash: parsedUrl.tab + (parsedUrl.page ? '&page=' + parsedUrl.page : '') + '&' + parsedUrl.gallery
                });
            }
        });
    }
});