(function ($) {
    $.fn.atlas = function (paths, options) {
        if (!paths) {
            console.error('paths argument is required');
            return this;
        }
        // Default values
        var defaults = {
            rateParentSelector: '.rate-type-selector',
            legendSelector: 'li[data-rate]',
            map_attributes: {
                highlight: '#1F3684',
                fill: '#e1e1e1',
                stroke: '#ffffff',
                'stroke-width': 1,
                'stroke-linejoin': 'round'
            },
            orig_width: 1200,
            orig_height: 640,
            initRateCode: 'growth',
            regionRatePopupTemplateSelector: '#regionRatePopupTemplate'
        };

        options = $.extend(defaults, options);

        if (!options.url) {
            console.error('url option is required');
            return this;
        }
        // Apply to each matching item
        return this.each(function () {
            var self = this;
            self.area_obj = [];
            self.data_colors = [];
            // Get handle on current obj
            var obj = $(this);
            self.mapWidth = obj.innerWidth();
            self.mapTopOffset = obj.offset().top;

            // register events

            this.registerAreaEvents = function (area) {
                var point;

                area.mouseover(function (event) {
                    var mouseY, regid = this.id.slice(3);

                    if (self.lock_over) return;
                    self.lock_over = 1;

                    mouseY = event.pageY - self.mapTopOffset; // event.Y || (event.clientY + document.body.scrollTop + document.documentElement.scrollTop);
                    point = this.getBBox(0);

                    self.template_regionPopupAsTable(regid, point, mouseY);
                    //self.area_obj[regid].animate({fill: options.map_attributes.highlight}, 0);

                    self.area_obj[regid].animate({stroke: '#1F3684'}, 0);
                    
                    self.lock_over = 0;
                });

                area.mouseout(function () {
                    var regid = this.id.slice(3);

                    if (self.lock_over) return;
                    self.lock_over = 1;

                    self.paintRegions(self.current_rate, self.current_year, regid);
                    // hide popup table
                    if (self.popup) self.popup.hide();
                    self.lock_over = 0;
                });

                area.click(function () {
                    var reg_id;
                    reg_id = this.id.slice(3);
                    obj.trigger('region.click', [reg_id, self.current_rate, self.current_year]);
                });
            };

            this.template_regionPopupAsTable = function (region, point, mouseY) {


                var region_title = paths['reg' + region].name,
                    rates = obj.data('rates.' + self.current_rate),
                    year = self.current_year;


                if (!rates) return;

                var value = rates[year] && rates[year][region] ? rates[year][region].value : false,
                    area_color = self.area_obj[region].attrs.fill,
                    template = $(options.regionRatePopupTemplateSelector),
                    year_values = {};

                if (template.length === 0) {
                    return false;
                }
                var year_list = {},
                    rate_list = [],
                    prev_year = year - 1,
                    rate_title = $(options.rateParentSelector).find('[data-rate=' + self.current_rate + ']').text(),
                    y;

                year_list[year] = year + ' г.';

                if (rates[prev_year]) {
                    year_list[prev_year] = prev_year + ' г.';
                }
                for (var i in rates[year][region]) {
                    if (!rates[year][region].hasOwnProperty(i) || i === 'value') {
                        continue;
                    }
                    if (rates[year][region]['diff'] && prev_year) {
                        year_list['diff'] = year_list[year] + ' к ' + year_list[prev_year] + ', %';
                    }
                    if (i !== 'diff') {
                        year_values = {};
                        for (y in year_list) {
                            if (y === 'diff') {
                                year_values[y] = rates[year][region]['diff'][i];
                                continue;
                            }
                            year_values[y] = rates[y][region][i];
                        }
                        rate_list.push({
                            title: i,
                            value: year_values
                        });
                    }
                }
                if (rate_list.length === 0) {
                    return false;
                }
                // compile template
                if (!self.compiled) {
                    self.compiled = _.template(template.html());
                }
                // render template
                var html = self.compiled({
                    region: region_title,
                    color: area_color,
                    year_list: year_list,
                    rate_title: rate_title,
                    rates: rate_list
                });


                if (!self.popup) {
                    self.popup = $('<div class="digit-box">' + html + '</div>').appendTo(obj.parent());
                } else {
                    self.popup.html(html);
                }
                self.popup.css({
                    'visibility': 'hidden',
                    'display': 'block'
                });
                var height = self.popup.height();
                self.popup.css({
                    'visibility': 'visible',
                    'display': 'none'
                });

                self.popup.css({
                    left: point.x - 80 + Math.round(point.width / 2),
                    top: mouseY - height + 40
                }).show();

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
                    check: function () {
                        return false;
                    }
                }

            };

            this.mapValueColor = function () {
                var code, range, color, legend;

                $(options.legendSelector).each(function (index, elem) {
                    code = $(elem).data('rate');
                    legend = $('#legend_' + code);
                    if (!legend.length) legend = $('#legend_' + code.slice(0, code.lastIndexOf('_')));
                    if (!self.data_colors[code]) {
                        self.data_colors[code] = [];
                    }

                    if (legend.length) {
                        $.each(legend.find('span[data-cmp]'), function () {
                            range = $(this).attr('data-cmp');
                            color = $(this).prev('em').css('background-color');
                            self.data_colors[code].push(self.factoryCompare(range, color));
                        });
                    }
                });
            };

            this.initMap = function () {
                var region, area, reg_id,
                    resizeRate = (self.mapWidth / options.orig_width).toFixed(2);

                self.map = Raphael(obj.attr('id'), self.mapWidth, resizeRate * options.orig_height);
                for (region in paths) {
                    if (!paths.hasOwnProperty(region)) {
                        continue;
                    }

                    area = self.map.path(paths[region].path.replace(/\d+\.?\d*/g,
                        function (digit) {
                            return (digit * resizeRate).toFixed(1);
                        }
                    ));
                    reg_id = region.slice(3);

                    self.registerAreaEvents(area);

                    area.id = region;
                    area.attr(options.map_attributes);
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
                    //
                    var rates = obj.data('rates.' + code);
                    value = rates[year] && rates[year][id] ? rates[year][id].value : false;

                    if (value !== false)
                        for (f = 0, len = self.data_colors[code].length; f < len; f++) {
                            color = self.data_colors[code][f].check(value);
                            if (color) break;
                        }
                    if (!color) color = options.map_attributes.fill;
                    area.animate({fill: color}, 0);
                    area.animate({stroke: '#ffffff'}, 0);
                }
            };

            this.loadRate = function (code, year) {

                if (obj.data('rates.' + code)) {
                    var year_list = obj.data('rates.' + code);
                    year_list = Object.keys(year_list);
                    if (!year) {
                        year = year_list[year_list.length - 1];
                    }
                    self.paintRegions(code, year);
                    self.current_rate = code;
                    self.current_year = year;
                    obj.trigger('rate.loaded', [code, year, year_list]);
                    return true;
                }

                obj.trigger('ajax.beforeSend', [code]);
                $.ajax({
                    url: options.url + '?code=' + code + '&rnd=' + Math.round(Math.random() * 1000000),
                    dataType: 'json',
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            obj.data('rates.' + code, data.list);
                            var year_list = Object.keys(data.list),
                                year = year_list[year_list.length - 1];
                            self.mapValueColor();
                            self.paintRegions(code, year);

                            self.current_rate = code;
                            self.current_year = year;
                            obj.trigger('rate.loaded', [code, year, year_list]);
                        }
                        obj.trigger('ajax.success', [code, year, data]);

                    },

                    error: function (xhr, status, errorThrown) {
                        obj.trigger('ajax.error', [status, errorThrown]);
                    }
                });
            };

            // init

            self.initMap();
            self.loadRate(options.initRateCode);
            obj.on('rate.load', function (e, code) {
                self.loadRate(code);
            });
            obj.on('rate.show', function (e, code, year) {
                self.loadRate(code, year);
            });
        });
    }
})(jQuery);