(function () {

    function setEqualHeight($els) {
        var maxHeight = 0;
        $.each($els, function (i, item) {
            var elHeight = $(item).height();
            if (elHeight > maxHeight) {
                maxHeight = elHeight;
            }
        });
        $.each($els, function (i, item) {
            $(item).height(maxHeight);
        });
    }

    setEqualHeight($('.news-card-daily-map'));

    // hiding beta block
    $('.beta-close a').on('click', function () {
        $(this).closest('.beta-version').addClass('hide');
    });

    // Custom tabs
    function customTabs(navId, contentId) {
        var $navEl = $('#' + navId);
        var $navItems = $navEl.find('.custom-tab-item');
        var $contentEl = $('#' + contentId);
        var $contentItems = $contentEl.find('.custom-tabs-content');

        $navItems.on('click', function () {
            var $selectedTab = $(this);
            $navItems.removeClass('active');
            $selectedTab.addClass('active');
            $contentItems.removeClass('active');
            $contentEl.find('#' + $selectedTab.data('content')).addClass('active');
        });
    }

    customTabs('day_map_tabs_nav', 'day_map_tabs_content');
    customTabs('media_tabs_nav', 'media_tabs_content');

    // fixed position nav
    var $gosbar = $('.main');
    var $betabar = $('.beta-version');
    var $navbar = $('.top-nav');

    function getBarsOffset() {
        var gosbarHeight = $gosbar.height() > 0 ? $gosbar.height() : 50;
        var betabarHeight = $betabar.height() > 0 ? $betabar.height() : 0;
        return gosbarHeight + betabarHeight;
    }

    $(document).on('scroll', function () {
        var scrollTopValue = $(this).scrollTop();
        var offsetVal = getBarsOffset();

        if (scrollTopValue > offsetVal && !$navbar.hasClass('navbar-fixed-top')) {
            $navbar.addClass('navbar-fixed-top');

            $('body').css('padding-top', offsetVal + 32 + 'px');
            $('.beta-version').css('display', 'none')

        } else if (scrollTopValue < offsetVal && $navbar.hasClass('navbar-fixed-top')) {
            $navbar.removeClass('navbar-fixed-top');

            $('body').css('padding-top', '0');
            $('.beta-version').css('display', 'block')
        }

    });

})();