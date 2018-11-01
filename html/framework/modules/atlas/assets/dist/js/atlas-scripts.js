$(document).ready(function () {

    $('.rate-type-selector li').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('active')) {
            return false;
        }
        $('.rate-type-selector li').removeClass('active');
        $(this).addClass('active');
        $('#map').trigger('rate.load', [$(this).data('rate')]);
    });

    $('#map').on('rate.loaded', function (e, rate, year, year_list) {
        var legend = $('#legend_' + rate);
        if(legend.length === 0){
            return false;
        }
        if (legend.is(':visible')) {
            return false;
        }
        $('.region-info .legend').hide();
        legend.show();
        //show sub-types
        var subTypes = $('.sub-type-selector');
        subTypes.empty();
        year_list.sort(function (a, b) {
            if (a > b) {
                return -1;
            } else {
                return 1;
            }
        });
        for (var i in year_list) {
            if (!year_list.hasOwnProperty(i)) {
                continue;
            }
            subTypes.append('<li ' +
                (year_list[i] === year ? 'class="active"' : '') +
                ' data-rate="' + rate + '" data-year="' + year_list[i] + '"><em>' + year_list[i] + ' г.</em></li>')
        }
    }).on('region.click', function (e, reg_id, rate, year) {
        var container = $('#story'),
            stat = container.find('.stat');
        if (stat.length > 0 && stat.data('reg_id') === reg_id) {
            return false;
        }
        container.load(container.data('url') + '?reg=' + reg_id + '&rate=' + rate + '&year=' + year, function () {
            $('body, html').animate({scrollTop: $('#story').offset().top}, 500);
        });
    });

    $('.sub-type-selector').on('click', 'li', function (e) {
        e.preventDefault();

        if ($(this).hasClass('active')) {
            return false;
        }
        $('.sub-type-selector').find('li').removeClass('active');
        $(this).addClass('active');
        $('#map').trigger('rate.show', [$(this).data('rate'), $(this).data('year')]);
        // renew stat
        var container = $('#story'),
            stat = container.find('.stat');
        if (stat.length > 0) {
            container.load(container.data('url') + '?reg=' + stat.data('reg_id') + '&rate=' + $(this).data('rate') + '&year=' + $(this).data('year'));
        }

    });

});