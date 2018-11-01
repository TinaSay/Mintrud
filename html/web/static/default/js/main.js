(function ($) {

    // pagination
    $('.disabled a').on('click', function (e) {
        e.preventDefault();
    });

    var breadcrumb = $('.breadcrumb');

    $('body').on('click', '.wrap-pagination li:not(.disabled) a', function () {
        $('body, html').animate({
            scrollTop: breadcrumb.length > 0 ? breadcrumb.offset().top + "px" : 0
        }, 700);
    });

    // открываем вкладку при загрузке страницы
    if($('a[data-toggle="tab"]').length > 0) {
        var hash = window.location.hash;
        $('a[data-toggle="tab"][href="'+hash+'"]').tab('show');
        setTimeout(function(){
            $(window).scrollTop($(hash).offset().top - 150);
        }, 100);
    } 

    // для перенесенных со старого сайта таблиц-раскрывашек
    if ($('.table-modal').length > 0 || $('.docs-content table').length > 0) {
        function tableModal(table, id, btn, title) {
            var tableModalWrap = '<div id="' + id + '" class="modal fade modal-wide" role="dialog">' +
                '<div class="modal-dialog">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                '<h4 class="modal-title">' + title + '</h4>' +
                '</div>' +
                '<div class="modal-body">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';

            table.after('<span class="btn btn-block btn-primary mr-bottom-30 btn-wrap-normal" data-toggle="modal" data-target="#' + id + '">' + btn + '</span>');
            $('body').append(tableModalWrap);
            table.addClass('table-style table').appendTo('body #' + id + ' .modal-body');

        }

        $('.table-modal, .docs-content table').not('.table-no-modal').each(function (i, el) {
            var $this = $(this),
                btn = $this.attr('data-btn') !== undefined && $this.attr('data-btn') !== false && $this.data('data-btn') !== '' ? $this.data('btn') : 'Таблица: Нажмите для просмотра',
                title = $this.attr('data-title') !== undefined && $this.attr('data-title') !== false && $this.data('data-title') !== '' ? $this.data('title') : '',
                id = 'modalTable_' + i;
            tableModal($this, id, btn, title);
        });
    }

    $('.field-icon-loupe').focus(function () {
        $(this).addClass('full');
    }).blur(function () {
        var count = $(this).val().length;
        if (count === 0) {
            $(this).removeClass('full');
        }
        else {
            $(this).addClass('full');
        }
    });


    // form date input
    if ($(".date-wiget").length > 0) {
        $('#date-start').datetimepicker({
            locale: moment.locale('ru'),
            format: 'DD/MM/YYYY'
        });
        $('#date-end').datetimepicker({
            locale: moment.locale('ru'),
            format: 'DD/MM/YYYY',
            useCurrent: false
        });

        $('#date-start').on("dp.change", function (e) {
            $('#date-end').data("DateTimePicker").minDate(e.date);
        });
        $('#date-end').on("dp.change", function (e) {
            $('#date-start').data("DateTimePicker").maxDate(e.date);
        });

        $('#date-year').datetimepicker({
            locale: moment.locale('ru'),
            format: 'YYYY',
            dayViewHeaderFormat: 'YYYY',
            useCurrent: false,
            viewMode: "years",
        });
    }

    // form placeholder fix
    $('.form-group--placeholder-fix input, .form-group--placeholder-fix textarea').focus(function () {
        $(this).closest('.form-group--placeholder-fix').addClass('full');
    }).blur(function () {
        var count = $(this).val().length;
        if (count === 0) {
            $(this).closest('.form-group--placeholder-fix').removeClass('full');
        }
    });

    $('.form-group--placeholder-fix .placeholder').click(function () {
        $(this).next('input').focus();
    });

    $('.form-group--placeholder-fix input, .form-group--placeholder-fix textarea').each(function () {
        var count = $(this).val().length;
        if (count != 0) {
            $(this).closest('.form-group--placeholder-fix').addClass('full');
        }
    });

    // функция для фиксации плавающих блоки в сайдбарах
    function widthFixBlockAside(elem, elemParent) {
        elem.css({
            'width': elemParent.width()
        });
    }

    $(".placeholder-fix-textarea textarea").bind('textchange', function () {
        var $this = $(this),
            count = $this.val().length,
            countBox = $this.closest('.placeholder-fix-textarea').find('.placeholder__add-counter')
        if (count > 0) {
            countBox.show();
            countBox.find('i').html($this.attr('maxlength') - count);
        }
        else {
            countBox.hide();
        }
    });


    if ($('#sliderMaterials').length > 0) {
        $('#sliderMaterials .slider').slick({
            autoplay: false,
            autoplaySpeed: 2500,
            dots: false,
            infinite: true,
            slidesToShow: 1,
            arrows: true,
            prevArrow: '.slider-materials-nav .slider-vert__nav-prev',
            nextArrow: '.slider-materials-nav .slider-vert__nav-next',
        });
    }

    if ($('#sliderDoc').length > 0) {
        $('#sliderDoc .slider').slick({
            autoplay: false,
            autoplaySpeed: 2500,
            dots: false,
            infinite: true,
            slidesToShow: 1,
            arrows: true,
            prevArrow: '.slider-doc-nav .slider-vert__nav-prev',
            nextArrow: '.slider-doc-nav .slider-vert__nav-next',
        });
    }

    $('input[type=checkbox], input[type=radio], input[type=file]').styler();

    // функция для фиксации плавающих блоков в сайдбаре
    function fixBlockAside(elem, elemParent, placeFix, indent) {
        if (elemParent.length <= 0) {
            return false;
        }
        if ($(window).scrollTop() <= elemParent.offset().top - indent) {
            elem.css({
                'top': 0
            }).removeClass('fix');
        }
        else if ($(window).scrollTop() > placeFix.height() - elem.height() - indent) {
            elem.css({
                'top': placeFix.height() - elem.height() - elemParent.offset().top
            }).removeClass('fix');
        }
        else {
            elem.css({
                'top': indent
            }).addClass('fix');
        }
    }

    if ($('.fix-block-aside').length > 0) {
        widthFixBlockAside($('.fix-block-aside'), $('.wrap-aside-fix'));
        fixBlockAside($('.fix-block-aside'), $('.wrap-aside-fix'), $('.main'), 70);

        $(window).on('scroll', function () {
            fixBlockAside($('.fix-block-aside'), $('.wrap-aside-fix'), $('.main'), 70);
        });

        $(window).on('resize', function () {
            widthFixBlockAside($('.fix-block-aside'), $('.wrap-aside-fix'));
            fixBlockAside($('.fix-block-aside'), $('.wrap-aside-fix'), $('.main'), 70);
        });
    }

    // toggle
    $('.toggle-container .trigger').click(function () {
        var $this = $(this),
            active = $this.hasClass('active'),
            $box = $this.closest('.toggle-container').find('.toggle-container__more');
        $box
            .toggleClass('expanded')
            .slideToggle(500);
        $this
            .toggleClass('active')
            .text(active ? 'Читать далее' : 'Свернуть');
        return false;
    });

    // link-animate-scroll
    $("a.link-animate-scroll").click(function () {
        var elementClick = $(this).attr("href");
        var destination = $(elementClick).offset().top;
        jQuery("html:not(:animated),body:not(:animated)").animate({
            scrollTop: destination
        }, 800);
        return false;
    });

    // modal
    var modalWrap = $('.modal-wrap');
    var mobalBl = $('#modal-blind');

    $('.open-modal-blind').on('click', function () {
        modalWrap.addClass('active');
        mobalBl.addClass('active');
    });

    $('.close-modal').on('click', function (e) {
        e.preventDefault();
        modalWrap.removeClass('active');
        $(this).parent().removeClass('active');
    });

    // blind mode
    $('.btn-font').on('click', function (e) {
        e.preventDefault();
        var fontSize = $(this).attr('data-class');
        $('.btn-font').removeClass('active');
        $(this).addClass('active');
        $('body').attr('font-size', fontSize).trigger('blind.set.fontSize', [fontSize]);
    });

    $('.btn-color').on('click', function (e) {
        e.preventDefault();
        var colorSchema = $(this).attr('data-class');
        $('.btn-color').removeClass('active');
        $(this).addClass('active');
        $('body').attr('color-schema', colorSchema).trigger('blind.set.colorSchema', [colorSchema]);
    });

    // возврат к дефолтным настройкам 
    $('.default-site-version').click(function () {
        $('body').attr('font-size', 'size-sm').trigger('blind.unset.fontSize', ['size-sm']);
        $('body').attr('color-schema', 'color-white').trigger('blind.unset.colorSchema', ['color-white']);
        $('.btn-color').removeClass('active');
        $('.btn-color.color-white').addClass('active');
        $('.btn-font.size-sm').addClass('active');
        return false;
    });

    // datepicker
    if ($("#tempust").length > 0) {
        $("#tempust").tempust({
            date: new Date(),
            offset: 1,
            events: {
                "2017/6/1": $("<div><p class='ev-date'>30 - 31 мая, Москва</p><p class='ev-text'>Финал V Международного инженерного чемпионата «Case-in»</p></div>"),
                "2017/6/3": $("<div><p class='ev-date'>30 - 31 мая, Москва</p><p class='ev-text'>Финал V Международного инженерного чемпионата «Case-in»</p></div>")
            }
        });
    }

    // galleria
    if ($("#galleria").length > 0) {

        Galleria.addTheme({
            name: 'classic',
            version: 1.5,
            author: 'Galleria',
            defaults: {
                transition: 'slide',
                thumbCrop: 'height',

                // set this to false if you want to show the caption all the time:
                _toggleInfo: false
            },
            init: function (options) {

                Galleria.requires(1.4, 'This version of Classic theme requires Galleria 1.4 or later');

                // add some elements
                this.addElement('info-link', 'info-close');
                this.append({
                    'info': ['info-link', 'info-close']
                });

                this.addElement('btn-screen');
                this.addElement('download');

                this.appendChild('counter', 'btn-screen');
                this.appendChild('container', 'download');

                // cache some stuff
                var info = this.$('info-link,info-close,info-text'),
                    touch = Galleria.TOUCH;

                // show loader with opacity
                this.$('loader').show().css('opacity', 0.4);

                // some stuff for non-touch browsers
                if (!touch) {
                    this.addIdleState(this.get('image-nav-left'), {left: -50});
                    this.addIdleState(this.get('image-nav-right'), {right: -50});
                    this.addIdleState(this.get('counter'), {top: -50});
                    this.addIdleState(this.get('download'), {top: -50});
                }

                // toggle info
                if (options._toggleInfo === true) {
                    info.bind('click:fast', function () {
                        info.toggle();
                    });
                } else {
                    info.show();
                    this.$('info-link, info-close').hide();
                }

                // bind some stuff
                this.bind('thumbnail', function (e) {

                    if (!touch) {
                        // fade thumbnails
                        $(e.thumbTarget).css('opacity', 0.6).parent().hover(function () {
                            $(this).not('.active').children().stop().fadeTo(100, 1);
                        }, function () {
                            $(this).not('.active').children().stop().fadeTo(400, 0.6);
                        });

                        if (e.index === this.getIndex()) {
                            $(e.thumbTarget).css('opacity', 1);
                        }
                    } else {
                        $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.6).bind('click:fast', function () {
                            $(this).css('opacity', 1).parent().siblings().children().css('opacity', 0.6);
                        });
                    }
                });

                var activate = function (e) {
                    $(e.thumbTarget).css('opacity', 1).parent().siblings().children().css('opacity', 0.6);
                };

                this.bind('loadstart', function (e) {
                    if (!e.cached) {
                        this.$('loader').show().fadeTo(200, 0.4);
                    }
                    window.setTimeout(function () {
                        activate(e);
                    }, touch ? 300 : 0);
                    this.$('info').toggle(this.hasInfo());
                });

                this.bind('loadfinish', function (e) {
                    this.$('loader').fadeOut(200);
                });
            }
        });

        Galleria.run('#galleria', {
            imageCrop: true,
            thumbnails: false,
            transition: 'fade',
            trueFullscreen: true
        });

        Galleria.ready(function () {
            $('.galleria-theme-classic .galleria-btn-screen').on('click', function () {
                $(this).toggleClass('sl-fullscreen');
                $('#galleria').data('galleria').toggleFullscreen();
            });

            var download = document.createElement('a');
            download.className = 'link-download';
            download.setAttribute('download', 'download');
            $('.galleria-download').append(download);

            this.bind('image', function (e) {
                download.setAttribute('href', e.imageTarget.getAttribute('src'));
            });
        });
    }


    // Old script
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


        $navItems.on('click', function (event) {
            var $selectedTab = $(this);
            $navItems.removeClass('active');
            $selectedTab.addClass('active');
            $contentItems.fadeOut(0).removeClass('active');
            $contentEl.find('#' + $selectedTab.data('content')).stop().fadeIn(300).addClass('active');
        });
    }

    customTabs('day_map_tabs_nav', 'day_map_tabs_content');
    customTabs('media_tabs_nav', 'media_tabs_content');

    // media tabs on change
    /*$('#section-media .custom-tab-item').click(function(e){
        e.preventDefault();

        if ($(this).attr('data-content') == 'media_content_all') {
            $('#section-media').removeClass('bg-white').addClass('bg-dark');
        } else {
            $('#section-media').removeClass('bg-dark').addClass('bg-white');
        }
    });*/

    $("[data-toggle=popover]").popover({
        html: true,
        content: function () {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
        },
        title: function () {
            var title = $(this).attr("data-popover-content");
            return $(title).children(".popover-heading").html();
        }
    });

    // выдвигашка с поиском в шапке
    $('.btn-search-form-show').click(function () {
        $('.search-form-header-container').addClass('open');
        $('.search-form-header').addClass('open');
        $(this).addClass('open');
        $('.navbar-logo').animate({
            'opacity': 0
        }, 300);
        $('#header-menu .navbar-toggle').addClass('hidden');
        return false;
    });
    $('.btn-search-form-hide').click(function () {
        $('.search-form-header').removeClass('open');
        $('.btn-search-form-show').removeClass('open');
        setTimeout(function () {
            $('.search-form-header-container').removeClass('open');

        }, 500);
        $('.navbar-logo').animate({
            'opacity': 1
        }, 600);
        $('#header-menu .navbar-toggle').removeClass('hidden');
        return false;
    });


    // видео на главной
    function videoPlay(wrap, video, btn) {
        wrap.toggleClass('play');
        if (wrap.hasClass('play')) {
            video.play();
            video.setAttribute('controls', true);
            wrap.find('.video-play').fadeOut(200);
        }
        else {
            video.pause();
            video.setAttribute('controls', null);
            wrap.find('.video-play').fadeIn(200);
        }
    };

    $('.video-play').click(function () {
        var $this = $(this),
            parent = $this.closest('.wrap-media-video'),
            thisVideo = parent.find('video')[0];
        videoPlay(parent, thisVideo, $this);
        return false;
    });


    if ($('#sectionServices').length > 0) {
        $('#sectionServices .tab-pane').each(function () {
            $(this).find('.services-box:gt(2)').hide();
        });
    }

    // услуги на главной показать больше 
    $('#servicesMoreBtn').click(function () {
        var $this = $(this),
            activeBoxesHide = $('#sectionServices .tab-pane.active .services-box:gt(2)');
        if ($this.hasClass('open')) {
            $this.removeClass('open').find('span').html('Показать больше');
            activeBoxesHide.fadeOut(300);
        }
        else {
            $this.addClass('open').find('span').html('Скрыть');
            activeBoxesHide.fadeIn(300);
        }
        return false;
    });

    $('#navbar-uslugi a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        $('#servicesMoreBtn').removeClass('open').find('span').html('Показать больше');
        $('#sectionServices .tab-pane').each(function () {
            $(this).find('.services-box:gt(2)').hide();
        });
    });


    // dropdown navbar в шапке
    $('.li-static').mouseenter(function () {
        if ($(window).innerWidth() > 768) {
            $(this).find('.nav-submenu').stop().fadeIn(200);
        }
    });

    $('.li-static').mouseleave(function () {
        if ($(window).innerWidth() > 768) {
            $(this).find('.nav-submenu').stop().fadeOut(200);
        }
    });


    // dep-open-card

    $('.dep-open-card__heading').click(function (e) {
        e.preventDefault();

        $(this).parent().toggleClass('active');
    });


})(jQuery);

(function ($) {


    var nowDate = new Date(),
        slickSlidesToShow,
        nowDateIndexPosition;

    $('.custom-tab-item--calendar').click(function () {
        slickCalendar();
    });

    function slickCalendar() {

        // переменные и константы
        var $calendar = $('#main-calendar-widget'),
            $eventContainer = $('.event-container'),
            $eventElems = $eventContainer.find('.event'),
            $calendarCounter = 0,
            CALENDAR_HEADING_HEIGHT = 80;


        $('#main-calendar-widget .calendar-content').slick({
            slidesToShow: 13,
            infinite: false,
            prevArrow: '#main-calendar-widget .calendar--prev-arrow',
            nextArrow: '#main-calendar-widget .calendar--next-arrow'
        });


        setTimeout(function () {
            $('#main-calendar-widget').addClass('active');

        }, 1000);


        var calendarEvents = {};

        // Врубаем селекты
        calendarEvents.setSelects = function (year, month) {
            if (year) {
                $('select#calendar-select-year option[value=' + year + ']').prop('selected', true);
            }
            if (month) {
                $('select#calendar-select-month option[value=' + month + ']').prop('selected', true);
            }
            $('select#calendar-select-year').selectpicker('refresh');
            $('select#calendar-select-month').selectpicker('refresh');
        };

        // Выбираем год
        calendarEvents.nextYear = function (activeYear, activeMonth) {
            var i = 0,
                boolChecker = false;

            for (i; i < $('.day.first').length; i++) {
                var elem = $('.day.first').eq(i);
                if (elem.attr('data-year') == activeYear &&
                    elem.attr('data-month') == activeMonth) {
                    boolChecker = true;
                    break;
                }
            }
            ;

            if (i == $('.day.first').length && boolChecker == false) return false;

            var goToElem = $('.day.first').eq(i).index();


            $('.calendar-content').slick('slickSetOption', 'slidesToScroll', 1);

            $('.calendar-content').slick('slickGoTo', goToElem);

            $('.calendar-content').slick('slickSetOption', 'slidesToScroll', 3);

        };

        // Выбираем месяц
        calendarEvents.nextMonth = function (activeYear, activeMonth) {
            var i = 0,
                boolChecker = false;

            for (i; i < $('.day.first').length; i++) {
                var elem = $('.day.first').eq(i);
                if (elem.attr('data-year') == activeYear &&
                    elem.attr('data-month') == activeMonth) {
                    boolChecker = true;
                    break;
                }
            }
            ;

            if (i == $('.day.first').length && boolChecker == false) return false;

            var goToElem = $('.day.first').eq(i).index();
            console.log(i);
            // console.log(goToElem);

            $('.calendar-content').slick('slickGoTo', goToElem);

        };


        slickSlidesToShow = $('.calendar-content').slick('slickGetOption', 'slidesToShow');


        nowDateIndexPosition = $('.calendar-content .day.active').index() - (parseInt(slickSlidesToShow / 2));


        $('.calendar-content').slick('slickGoTo', nowDateIndexPosition);


        calendarEvents
            .setSelects($('.calendar-content .day.active').attr('data-year'),
                $('.calendar-content .day.active').attr('data-month'));


        $('.calendar-content').slick('slickSetOption', 'slidesToScroll', 3);


        $('select#calendar-select-year').on('change', function () {
            var activeYear = $(this).val(),
                activeMonth = $('select#calendar-select-month').val();


            calendarEvents.nextYear(activeYear, activeMonth);
        });


        $('select#calendar-select-month').on('change', function () {
            var activeYear = $('select#calendar-select-year').val(),
                activeMonth = $(this).val();


            calendarEvents.nextMonth(activeYear, activeMonth);
        });


        $('.calendar .next-arrow').click(function () {
            for (var i = 0; i < $('.day.first').length; i++) {
                var elem = $('.day.first').eq(i);
                if (elem.hasClass('slick-active')) {
                    calendarEvents.setSelects(elem.attr('data-year'), elem.attr('data-month'));
                    break;
                }
            }
        });


        $('.calendar .prev-arrow').click(function () {
            for (var i = 0; i < $('.day.last').length; i++) {
                var elem = $('.day.last').eq(i);
                if (elem.hasClass('slick-active')) {
                    calendarEvents.setSelects(elem.attr('data-year'), elem.attr('data-month'));
                    break;
                }
            }
        });


        $('.calendar-container .day.has-event .day-content').click(function (e) {
            e.preventDefault();
            var elem = $(this).parent();
            $calendarCounter = 0;

            // Прячем все события
            $eventContainer
                .stop()
                .removeClass('active')
                .fadeOut(0);

            $eventElems
                .stop()
                .removeClass('active first')
                .fadeOut(0);

            // Показываем события нужного дня
            $eventContainer
                .find('.event[data-date=' + elem.attr('data-date') + ']')
                .each(function () {
                    console.log($(this).index());
                    if ($calendarCounter == 0) {
                        $(this).addClass('first');
                    }
                    $(this).stop().addClass('active').fadeIn(200);
                    $calendarCounter++;
                });

            // показываем блок с событиями
            $eventContainer
                .fadeIn(200)
                .addClass('active');

            // изменяем высоту календаря
            if ($calendar.outerHeight() < $eventContainer.outerHeight()) {
                $calendar
                    .css('height',
                        $eventContainer.outerHeight() +
                        CALENDAR_HEADING_HEIGHT + 'px');
            }
        });


        $('.event-container .event .event-close').click(function () {
            var $eventContainer = $('.event-container'),
                $eventElems = $eventContainer.find('.event'),
                $calendar = $('#main-calendar-widget');

            // обнуляем счетчик
            $calendarCounter = 0;

            $eventContainer
                .stop()
                .fadeOut(200)
                .removeClass('active');

            $eventElems
                .fadeOut(0)
                .removeClass('active first');

            $calendar
                .css('height', '');
        });


        var newEventIndex = $('.calendar-content .day.active').next('.day.has-event').index();

        $('.btn-calendar').click(function (e) {
            e.preventDefault();

            $('.calendar-content').slick('slickSetOption', 'slidesToScroll', 1);

            if (newEventIndex < $('.day.has-event').length - 1 && newEventIndex >= 0) {
                newEventIndex += 1;
            } else {
                newEventIndex = 0;
            }


            $('.calendar-content')
                .slick(
                    'slickGoTo',
                    $('.day.has-event').eq(newEventIndex).index() + 1 -
                    ($('.day.slick-active').length / 2));

            $('.calendar-content').slick('slickSetOption', 'slidesToScroll', 3);
        })
    }


    $('.header-search-form input[type="text"][name="term"]').on('focus', function () {
        $(this).attr('placeholder', '');
    });
    $('.header-search-form input[type="text"][name="term"]').on('blur', function () {
        $(this).attr('placeholder', 'Введите поисковый запрос');
    });


// head input of focus-blur
    $('.header-search-form input[type="text"][name="term"]').on('focus', function () {
        $(this).attr('placeholder', '');
    });
    $('.header-search-form input[type="text"][name="term"]').on('blur', function () {
        $(this).attr('placeholder', 'Введите поисковый запрос');
    });


    /*main content and main-aside*/

    $(window).on('load resize', function () {
        if ($(window).innerWidth() > 991 && $('.main-aside').length != 0) {

            var mainBlockMargin = 64,
                mainBlockPadding = 30,
                mainBlockWidth = $('.main').parents('.container').eq(0).outerWidth() - ($('.btn-blind.open-modal-blind').outerWidth() + mainBlockMargin + mainBlockPadding);
            $('.main').css({
                'width': mainBlockWidth + 'px',
            });
            $('.main-aside').css({
                'width': $('.btn-blind.open-modal-blind').outerWidth() + 'px',
            });

        } else if ($('.main-aside').length == 0) {
            $('.main').css('width', '100%');
        } else {
            $('.main').css({
                'width': '',
            });
            $('.main-aside').css({
                'width': '',
            });
        }
    });


// прячем элементы навигации при ресайзе
    function navComponent() {
        var _this = this;


        // задаем контейнеру объектов ширину
        this.setContainerWidth = function (container, containerParent, containerNeighbour, navElemString, navContainerString) {
            container.each(function () {
                var elem = this,
                    elemWidth;
                if (containerNeighbour.outerWidth() > containerParent.outerWidth() / 2) {
                    containerParent.addClass('nav-two-column');
                    elemWidth =
                        containerParent.outerWidth();
                    container.css({
                        'width': '100%',
                    });
                }
                else {
                    elemWidth =
                        containerParent.outerWidth() -
                        containerNeighbour.outerWidth() - 50;
                    container.css({
                        'width': elemWidth + 40 + 'px',
                    });
                }
            });

            _this.adaptNav(container, navElemString, navContainerString);
        };


        // высчитываем общую ширину всех блоков внутри контейнера
        this.adaptNav = function (container, navElemString, navContainerString) {
            container.each(function () {
                var elem = $(this),
                    navElem = container.find(navElemString),
                    navContainer = container.find(navContainerString),
                    compareCounter = 1,
                    allNavElemWidth = 0;

                for (var i = 0; i < navElem.length; i++) {
                    allNavElemWidth += navElem.eq(i).outerWidth() +
                        parseInt(navElem.eq(i).css('margin-left')) +
                        parseInt(navElem.eq(i).css('margin-right'));

                    if (elem.outerWidth() < allNavElemWidth + 50) {
                        compareCounter++;
                    }


                }

                _this.compareContainerElemWidth(
                    container,
                    navElemString,
                    allNavElemWidth,
                    navContainer,
                    compareCounter);
            });
        };

        // прячем лишние блоки в несколько итераций.
        this.compareContainerElemWidth = function (container, navElemString, elemWidth, navContainer, counts) {
            navContainer.find('> ' + navElemString).insertBefore(navContainer.parent());

            for (var i = 1; i < counts; i++) {
                container.find('> ' + navElemString).eq(-1).prependTo(navContainer);
            }
        };

        // по клику на ссылку вытаскиваем её из родителя и закидываем после ссылки "все"
        this.showActiveElem = function (container, navElemString, elem) {
            var allElem = container.find(">" + navElemString).eq(0);

            elem.insertAfter(allElem);
        };

        // инициализируем все эти операции
        this.init = function (container, containerParent, containerNeighbour, navElemString, navContainerString) {
            _this.setContainerWidth(container, containerParent, containerNeighbour, navElemString, navContainerString);
        };
    }

    var navComponentVar = new navComponent();

    function checkTabsContainer() {
        $('.tabs-container').each(function () {
            var elem = $(this),
                containerElems = elem.find('.custom-tab-item');

            if (containerElems.length != 0) {
                elem.addClass('container-active');
            } else {
                elem.removeClass('container-active');
            }
        })
    }

    var navComponentTimeout;

    function initNavComponent() {
        // clearTimeout(navComponentTimeout);

        // navComponentTimeout = setTimeout(function(){

        $('.nav-tabs').each(function () {

            navComponentVar
                .init(
                    $(this),
                    $(this).parents('.tabs-nav-wrap'),
                    $(this).parents('.tabs-nav-wrap').find('.section-head').eq(0),
                    '.custom-tab-item',
                    '.tabs-container__content');
        });
        checkTabsContainer();

        // }, 10);
    }

    $(window).on('load', function () {
        initNavComponent();
    });
    $(window).on('resize', function () {
        setTimeout(function () {
            initNavComponent();

        }, 300);
    });


// Функция для перекидывания активного таба после первого элемента навигации
    function showActiveElemEvnt(container, containerParent, containerNeighbour, navElemString, navContainerString, targetElem) {
        navComponentVar.showActiveElem(container, navElemString, targetElem);

        navComponentVar
            .setContainerWidth(container, containerParent, containerNeighbour, navElemString, navContainerString);

    }

// обработчик события перекидывания активного таба после первого элемента навигац
    var shit = function () {
        var elem = $(this),
            elemParent = elem.parents('.nav-tabs').eq(0);

        showActiveElemEvnt(elemParent,
            elemParent.parents('.tabs-nav-wrap'),
            elemParent.parents('.tabs-nav-wrap').find('.section-head').eq(0),
            '.custom-tab-item',
            '.tabs-container__content', elem);

        // удаляем обработчик события, чтобы не было ненужных срабатываний на перемещенных блоках
        $('.custom-tab-item').unbind('click', shit);
    }


// по клику на контейнер табов, накидываем события на все табы внутри этого контейнера
    $('.tabs-container__btn').click(function () {

        // кидаем обработчик событий
        $('.tabs-container .custom-tab-item').on('click', shit);
    });


// main page video vertical center
    /*if ($('#media_content_all').length != 0) {

        $(window).on('load resize', function(){
            var mediaContentAll = $('#media_content_all'),
                mediaContent8 = mediaContentAll.find('.col-lg-8'),
                mediaContent4 = mediaContentAll.find('.col-lg-4');
            if (mediaContent8.outerHeight() > mediaContent4.outerHeight()) {
                mediaContent4.css('height', mediaContent8.outerHeight() + 'px');
            } else {
                mediaContent8.css('height', mediaContent4.outerHeight() + 'px');
            }
        });
    }*/


// text error on content page show modal
    function textError(selectedText) {

        // get page URL
        $('#textErrorPageUrlTag').text(window.location.href)
        $('#textErrorPageUrl').val(window.location.href);

        // get selected text
        $('#textErrorContentTextTag').text(selectedText);
        $('#textErrorContentText').val(selectedText);


        $('#textErrorModal').modal('show');
    }

    if ($('#textErrorModal').length != 0) {

        $(document).keydown(function (e) {
            if ((e.ctrlKey == true) && (e.keyCode == 13 || e.keyCode == 10)) {
                var selected_text = window.getSelection();
                if (selected_text != '') {
                    textError(selected_text);
                }
            }
        });
    }


    if ($('iframe').length != 0) {

        $('iframe').load(function () {
            var elem = $(this),
                interviewContainer = elem.contents().find('.grey-interview-container');
            if (interviewContainer.length != 0) {
                interviewContainer
                    .parents('.pd-top-0.pd-bottom-30')
                    .find('.container').eq(0)
                    .removeClass('container');
                interviewContainer
                    .parents('.pd-top-0.pd-bottom-30')
                    .find('.row').eq(0)
                    .removeClass('row');
            }
        });
    }


})(jQuery);

$(document).on('ready', function () {
    var $body = $('body');
    $('.open-page-layer').click(function () {
        var $this = $(this),
            page = $this.data('href');
        $body.addClass('page-layer-open');
        $('.page-layer').not('[id="' + page + '"]').removeClass('open');
        $('.page-layer[id="' + page + '"]').addClass('open');
        return false;
    });
    $('.close-page-layer').click(function () {
        $body.removeClass('page-layer-open');
        $('.page-layer').removeClass('open');
        return false;
    });
});