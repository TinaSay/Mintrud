/**
 * Created by user on 02.08.2017.
 */
$(document).ready(function () {
    //анимация чисел в статистике
    var show = true;
    var countbox = ".header-wrap";
    $(window).on("scroll load resize", function () {

        if (!show) return false;                   // Отменяем показ анимации, если она уже была выполнена

        var w_top = $(window).scrollTop();        // Количество пикселей на которое была прокручена страница
        var e_top = $(countbox).offset().top;     // Расстояние от блока со счетчиками до верха всего документа

        var w_height = $(window).height();        // Высота окна браузера
        var d_height = $(document).height();      // Высота всего документа

        var e_height = $(countbox).outerHeight(); // Полная высота блока со счетчиками

        if (w_top >= e_top || w_height + w_top == d_height) {
            $(".numeral").spincrement({
                thousandSeparator: "",
                from: 0,
                duration: 1000
            });

            show = false;
        }
    });
});