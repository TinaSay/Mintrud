$(document).ready(function () {
    var form = $('#news-search');
    var selectDirectory = form.find('#news-directory');
    var tabs = $('.custom-tab-item a');

    tabs.on('click', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var tabContent = $(href);

        if (tabContent.data('directory') > 0) {
            selectDirectory.val(tabContent.data('directory'));
            selectDirectory.selectpicker('refresh');
        }
        var url = tabContent.data('url') + '?' + form.serialize();
        history.pushState(null, null, $(this).attr('href'));
        loadNews(tabContent, url);
    });

    if (document.location.hash > '') {
        $('.custom-tab-item a[href="' + document.location.hash + '"]').trigger('click');
    }

    $('.custom-tabs-content').on('click', '.wrap-pagination a', function (e) {
        e.preventDefault();
        var tabContent = $(this).closest('.custom-tabs-content');
        if (tabContent.length > 0) {
            loadNews(tabContent, $(this).prop('href'));
        }
    });

    if (form.length) {
        form.submit(function () {
            return false;
        });
        form.on('change', function () {
            var directory = selectDirectory.val();
            var tab = getTab(directory);
            if (!tab.length) {
                tab = getTab('all');
            }
            tab.trigger('click');
        })
    }

    function loadNews(tabContent, url) {
        tabContent.load(url);
    }

    function getTab(directory) {
        return $('.custom-tab-item a[href="#day_map_' + directory + '"]');
    }
});