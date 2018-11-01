$(document).ready(function () {

    $('.rate-type-selector li').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('active')) {
            return false;
        }
        $('.rate-type-selector li').removeClass('active');
        $(this).addClass('active');
        $('#map-allowance').trigger('rate.load', [$(this).data('rate')]);
    });

    $('#map-allowance').on('region.click', function (e, reg_id, rate) {
        var container = $('#story'),
            stat = container.find('.stat');
        if (stat.length > 0 && stat.data('reg_id') === reg_id) {
            return false;
        }
        container.load(container.data('url') + '?reg=' + reg_id + '&rate=' + rate, function () {
            $('body, html').animate({scrollTop: $('#story').offset().top}, 500);
        });
    });

});