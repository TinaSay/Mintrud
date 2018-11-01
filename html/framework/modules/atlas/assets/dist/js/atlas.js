function Atlas(pathes, initRateCode, options) {
    var msgStatus, self = this;

    this.pathes = pathes;
    this.options = options;
    this.getLayerUrl = '/atlas/default/get-layer';
    this.getRegionData = '/atlas/default/get-region-data';
    this.initRateCode = initRateCode;
    this.map_id = 'map';
    this.map_width = $('#' + self.map_id).innerWidth();
    this.map_top_offset = $('#' + self.map_id).offset().top;

    self.area_obj = {};
    self.data_rates = {};
    self.data_regions = {};
    self.data_colors = {};
    self.map = 0;
    self.current_rate = initRateCode;
    self.lock_over = 0;
    self.current_region = 0;
    self.current_year = 0;
    self.map_attributes = {
        highlight: '#dbecf2',
        fill: '#ffffff',
        stroke: '#666666',
        'stroke-width': 1,
        'stroke-linejoin': 'round'
    };


    this.isEmpty = function (value) {
        return (typeof value === 'undefined' || value === '');
    };


    this.getOption = function (name) {
        if (!self.isEmpty(self.options) && !self.isEmpty(self.options[name])) {
            return self.options[name];
        }
        return false;
    };

    this.showMessage = function (text, process, status) {
        var mBlock = $('#l_text');

        try {
            clearTimeout(self.msgStatus);
        } catch (e) {
        }

        mBlock.html(text);

        if (typeof status === 'undefined') mBlock.get(0).className = '';
        else mBlock.get(0).className = status;

        if (process) $('#l_image').show();
        else $('#l_image').hide();

        $('#loader').show();
    };


    this.hideMessage = function () {
        $('#loader').hide();
    };

    this.showMessageTime = function (secs, text, status) {
        try {
            clearTimeout(self.msgStatus);
        } catch (e) {
        }
        self.showMessage(text, 0, status);
        try {
            self.msgStatus = setTimeout(self.hideMessage, 1000 * secs);
        } catch (e) {
        }
    };


    this.thous = function (v) {
        var sRegExp = /(-?[0-9]+)([0-9]{3})/,
            sValue = v + '',
            sep = ' ';

        while (sRegExp.test(sValue))
            sValue = sValue.replace(sRegExp, '$1' + '&nbsp;' + '$2');

        return sValue;
    };


    this.template_regionTable = function (reg_id, noScroll) {
        self.showMessage('Загрузка данных региона...', 1);
        $('#story').load(self.getRegionData + '?reg=' + reg_id + '&rate=' + self.current_rate, function () {
            self.hideMessage();
            if (!noScroll) {
                $('body, html').animate({scrollTop: $('#story').offset().top}, 500);
            }
        });
    };


    this.template_regionPopupAsTable = function (region, point, mouseY) {
        var html = '', region_title, data, area_color, table, code, tooltip_value_area, value;

        region_title = self.pathes['reg' + region].name;
        data = self.data_rates[self.current_rate][region];
        if (!data) return;

        area_color = self.area_obj[region].attrs.fill;

        table = $('#popup_rate_' + self.current_rate);
        if (table.length === 0) table = $('#popup_rate_' + self.current_rate.slice(0, self.current_rate.lastIndexOf('_')));
        if (table.length) {

            table.hide();
            table.find('.tts_region').html(region_title);
            table.find('.marker').css('background-color', area_color);

            $.each(table.find('[data-ttv]'), function () {
                code = $(this).attr('data-ttv');
                if (data.hasOwnProperty(code)) {
                    (data[code] >= 1000 || data[code] <= -1000) ? value = self.thous(data[code]) : value = data[code];
                    $(this).html(value);
                }
            });
            table.css({
                'visibility': 'hidden',
                'display': 'block'
            });
            var height = table.height();
            table.css({
                'visibility': 'visible',
                'display': 'none'
            });
            console.log(height, mouseY);
            table.css({
                left: point.x - 80 + Math.round(point.width / 2),
                top: mouseY - height + 40
            }).show();
        }
    };


    this.assign_events_selectRate = function () {
        $('#type-selector').find('li[data-rate]').on('click', function () {
            var code = $(this).attr('data-rate'),
                isSub = $(this).parent().hasClass('sub-type-selector');

            if (code !== self.current_rate && !self.data_rates[code])
                self.loadRate(code);

            if (!self.data_rates[code]) {
                self.showMessageTime(3, 'Не удалось загрузить данные показателя', 'err');
            }
            else {
                $.each($('#type-selector').find(isSub ? '.sub-type-selector li[data-rate]' : 'li[data-rate]'), function () {
                    $(this).attr('data-rate') === code ? $(this).addClass('active') : $(this).removeClass('active');
                });

                if (!isSub)
                    $.each($('#type-selector').find('.sub-type-selector'), function () {
                        $(this).toggle(this.id.slice(8) === code);
                    });

                self.showLegend(code);
                self.current_rate = code;
                self.paintRegions(code);
            }

            if (isSub && self.current_region) {
                self.template_regionTable(self.current_region, true);
            }
        });
    };


    this.assign_events_highCharts = function () {
        $(document).on('click', '#story .chart-box img', function () {
            var d, code = this.id.slice(6);
            if (!self.isEmpty(highcharts_data[code])) {
                d = highcharts_data[code];
                $('#highchart').highcharts({
                    chart: {type: 'bar', backgroundColor: '#F4F6F0'},
                    title: {text: d.title},
                    subtitle: {text: d.subtitle},
                    xAxis: {
                        categories: d.categories,
                        title: {text: null}
                    },
                    yAxis: {
                        title: {
                            text: 'Человек',
                            align: 'high'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    tooltip: {
                        valueSuffix: ' чел.'
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -100,
                        y: 100,
                        floating: true,
                        borderWidth: 1,
                        backgroundColor: '#FFFFFF',
                        shadow: true
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: false
                    },
                    series: [{
                        name: d.popup,
                        color: '#8BBC21',
                        data: d.data
                    }]
                });
            }
        });
    };


    this.assign_events_map_area = function (area) {
        var point, map_top_offset;

        area.mouseover(function (evnt) {
            var mouseY, regid = this.id.slice(3);

            if (self.lock_over) return;
            self.lock_over = 1;

            mouseY = evnt.pageY - self.map_top_offset; // evnt.Y || (evnt.clientY + document.body.scrollTop + document.documentElement.scrollTop);
            point = this.getBBox(0);

            self.template_regionPopupAsTable(regid, point, mouseY);
            self.area_obj[regid].animate({fill: self.map_attributes.highlight}, 0);
            self.lock_over = 0;
        });

        area.mouseout(function () {
            var popup, regid = this.id.slice(3);

            if (self.lock_over) return;
            self.lock_over = 1;

            self.paintRegions(self.current_rate, regid);
            popup = $('#popup_rate_' + self.current_rate);
            if (!popup.length) popup = $('#popup_rate_' + self.current_rate.slice(0, self.current_rate.lastIndexOf('_')));
            popup.hide();
            self.lock_over = 0;
        });

        area.click(function () {
            var reg_id;
            reg_id = this.id.slice(3);
            self.current_region = reg_id;
            self.template_regionTable(reg_id);
        });
    };


    this.init_Raphael_map = function () {
        var region, area, map_attr, reg_id,
            orig_width = 1200,
            orig_height = 640,
            resizeRate = (self.map_width / orig_width).toFixed(2);


        self.map = Raphael(self.map_id, self.map_width, resizeRate * orig_height);
        for (region in self.pathes) {
            area = self.map.path(self.pathes[region].path.replace(/\d+\.?\d*/g,
                function (digit) {
                    return (digit * resizeRate).toFixed(1);
                }
            ));

            map_attr = {};
            reg_id = region.slice(3);
            jQuery.extend(map_attr, self.map_attributes);

            self.assign_events_map_area(area);

            area.id = region;
            area.attr(map_attr);
            self.area_obj[reg_id] = area;
        }
    };


    this.paintRegions = function (code, year, regid) {
        var i, area, color, f, len, value, id, regions = [];

        if (regid) {
            regions[regid] = self.area_obj[regid];
        } else {
            regions = self.area_obj;
        }

        for (i in regions) {
            if (!regions.hasOwnProperty(i)) {
                continue;
            }
            area = regions[i];
            id = area.id.slice(3);
            color = false;
            value = self.data_rates[code][year][id] ? self.data_rates[code][year][id].value : false;

            if (value !== false)
                for (f = 0, len = self.data_colors[code][year].length; f < len; f++) {
                    color = self.data_colors[code][year][f].check(value);
                    if (color) break;
                }

            if (!color) color = self.map_attributes.fill;
            area.animate({fill: color}, 0);
        }
    };


    this.factoryCompare = function (value, color) {
        var result,
            regExp_exact = /^-?\d+(\.\d+)?$/,
            regExp_less = /^:-?\d+(\.\d+)?$/,
            regExp_more = /^-?\d+(\.\d+)?:$/,
            regExp_range = /^(-?\d+(\.\d+)?):(-?\d+(\.\d+)?)$/;

        result = regExp_exact.exec(value);
        if (result !== null) {
            return {
                check: function (v) {
                    var value = parseInt(result[0], 10);
                    if (v === value) return color;
                    return false;
                }
            }
        }

        result = regExp_range.exec(value);
        if (result !== null) {
            return {
                check: function (v) {
                    var from = parseFloat(result[1]),
                        to = parseFloat(result[3]);

                    if (from < to && v >= from && v < to) return color;
                    return false;
                }
            }
        }

        result = regExp_less.exec(value);
        if (result !== null) {
            return {
                check: function (v) {
                    var to = parseFloat(result[0].slice(1));
                    if (v < to) return color;
                    return false;
                }
            }
        }

        result = regExp_more.exec(value);
        if (result !== null) {
            return {
                check: function (v) {
                    var from = parseFloat(result[0].substr(0, result[0].length - 1));
                    if (v >= from) return color;
                    return false;
                }
            }
        }

        return {
            check: function (v) {
                return false;
            }
        }

    };


    this.make_mapValueColor = function () {
        var code, range, color, legend;

        $.each($('#type-selector').find('li[data-rate]'), function () {
            code = this.getAttribute('data-rate');
            legend = $('#legend_' + code);
            if (!legend.length) legend = $('#legend_' + code.slice(0, code.lastIndexOf('_')));

            if (legend.length) {
                self.data_colors[code] = [];
                $.each(legend.find('span[data-cmp]'), function () {
                    range = $(this).attr('data-cmp');
                    color = $(this).prev('em').css('background-color');
                    self.data_colors[code].push(self.factoryCompare(range, color));
                });
            }
        });
    };


    this.showLegend = function (code) {
        var legend;

        $('#legends_block').find('div.legend').hide();
        legend = $('#legend_' + code);
        if (!legend.length)
            legend = $('#legend_' + code.slice(0, code.lastIndexOf('_')));

        legend.show();
    };


    this.loadRate = function (code) {
        self.showMessage('загрузка..', 1);
        $.ajax({
            url: self.getLayerUrl + '?code=' + code,
            async: false,
            dataType: 'json',

            success: function (data) {
                self.hideMessage();
                self.data_rates[code] = data;
            },

            error: function (xhr, status, errorThrown) {
                self.showMessageTime(3, status + '<br>' + errorThrown, 'err');
            }
        });
    };


    this.init = function () {
        self.getLayerUrl = self.getOption('layer') || self.getLayerUrl;
        self.getRegionData = self.getOption('region') || self.getRegionData;
        self.loadRate(self.initRateCode);
        if (!self.data_rates[self.initRateCode]) return;
        self.showLegend(self.initRateCode);
        self.assign_events_selectRate();
        $('#type-selector').find('li[data-rate="' + self.initRateCode + '"]').trigger('click');
        self.assign_events_highCharts();
        self.make_mapValueColor();
        self.init_Raphael_map();
        self.paintRegions(self.initRateCode);
    };

    self.init();
}

new Atlas(window.map.paths, 'growth');