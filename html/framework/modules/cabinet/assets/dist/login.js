/**
 * Created by krok on 16.02.16.
 */
(function ($) {
    $.fn.cabinetLogin = function (options) {

        options = $.extend({
            popup: {
                resizable: 'yes',
                scrollbars: 'no',
                toolbar: 'no',
                menubar: 'no',
                location: 'no',
                directories: 'no',
                status: 'yes',
                width: 450,
                height: 380
            }
        }, options);

        var $container = $(this);

        $container.on('click', function (e) {
            e.preventDefault();

            var cabinetLoginPopup = $container.data('cabinetLoginPopup');

            if (cabinetLoginPopup) {
                cabinetLoginPopup.close();
            }

            var url = this.href;
            var popupOptions = $.extend({}, options.popup);

            var localPopupWidth = this.getAttribute('data-popup-width');
            if (localPopupWidth) {
                popupOptions.width = localPopupWidth;
            }
            var localPopupHeight = this.getAttribute('data-popup-height');
            if (localPopupWidth) {
                popupOptions.height = localPopupHeight;
            }

            popupOptions.left = (window.screen.width - popupOptions.width) / 2;
            popupOptions.top = (window.screen.height - popupOptions.height) / 2;

            var popupFeatureParts = [];
            for (var propName in popupOptions) {
                if (popupOptions.hasOwnProperty(propName)) {
                    popupFeatureParts.push(propName + '=' + popupOptions[propName]);
                }
            }
            var popupFeature = popupFeatureParts.join(',');

            cabinetLoginPopup = window.open(url, 'cabinetLogin', popupFeature);
            cabinetLoginPopup.focus();

            $container.data('cabinetLoginPopup', cabinetLoginPopup);
        });
    };
})(jQuery);
