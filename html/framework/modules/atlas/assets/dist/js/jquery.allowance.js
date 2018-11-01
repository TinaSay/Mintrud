(function ($) {
    $.fn.allowance = function (paths, options) {
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
                    self.area_obj[regid].animate({fill: options.map_attributes.highlight}, 0);
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
                    template = $(options.regionRatePopupTemplateSelector);


                if (!region_title) return;

                // compile template
                if (!self.compiled) {
                    self.compiled = _.template(template.html());
                }
                // render template
                var html = self.compiled({
                    region: region_title,
                    color: options.map_attributes.fill
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
                    top: mouseY - height
                }).show();

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

            this.paintRegions = function (code, regid) {
                var i, area, id, regions = [];
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

                    area.animate({fill: options.map_attributes.fill}, 0);
                }
            };

            this.loadRate = function (code) {

                if (obj.data('rates.' + code)) {
                    self.paintRegions(code);
                    self.current_rate = code;
                    obj.trigger('rate.loaded', [code]);
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
                            self.paintRegions(code);

                            self.current_rate = code;
                            obj.trigger('rate.loaded', [code]);
                        }
                        obj.trigger('ajax.success', [code, data]);

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
            obj.on('rate.show', function (e, code) {
                self.loadRate(code);
            });
        });
    }
})(jQuery);