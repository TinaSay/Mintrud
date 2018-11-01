$(document).ready(function () {

    var $calendar = $('.calendar-container');
    $calendar.fullCalendar({
        // eventStartEditable: false,
        header: {
            left: 'today',
            center: 'title',
            right: 'prev,next'
        },
        eventRender: function (event, element, view) {

            if (event.properties.type === 'discussion') {
                if (event.url) {
                    return $('<a' + (event.properties.meeting ? ' data-meeting_id="' + event.properties.meeting_id + '"' : '') +
                        ' class="fc-day-grid-event fc-h-event fc-event discussion" href="' + event.url + '">' +
                        '<div class="fc-content"> <span class="fc-title">' + event.title + '</span></div></a>');
                } else {
                    return $('<div' + (event.properties.meeting ? ' data-meeting_id="' + event.properties.meeting_id + '"' : '') +
                        ' class="fc-day-grid-event fc-h-event fc-event discussion">' +
                        '<div class="fc-content"> <span class="fc-title">' + event.title + '</span></div></div>');
                }
            } else if (event.properties.type === 'meeting') {
                return $('<div class="fc-day-grid-event fc-h-event fc-event meeting" data-id="' + event.properties.id + '">' +
                    '<div class="fc-content"> <span class="fc-title">' + event.title + '</span></div></div>');
            }
        },
        // navLinks: true, // can click day/week names to navigate views
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: {
            url: $calendar.data('url')
        }
    }).on('click', '.meeting', function () {
        var $btn = $(this);
        $btn.toggleClass('clicked');
        if ($btn.hasClass('clicked')) {
            $calendar.find('.discussion').hide();
            $calendar.find('.meeting').removeClass('clicked');
            $calendar.find('.discussion[data-meeting_id=' + $btn.data('id') + ']').show();
            $btn.addClass('clicked');
        } else {
            $calendar.find('.discussion').show();
        }
    });

});