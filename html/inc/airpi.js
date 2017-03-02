// Global variables
var dayData = []; // dayData[dateTime] = [m10, color] for cusomDayRenderer
var evtData = []; // evtData.push({startDate, endDate, pm10}) for tooltip

// Executed once the page is loaded: bind events to functions.
$(document).ready(function() {
    $('#data-download').on('click', 'a', onDownloadClick);
    if ($("#calendar").length) {
        var station_id = $("#calendar").attr("station-id");
        $.getJSON('getpmdata.php?id=' + station_id, getCalData);
    }
});

// Get daily pollution data and initialize the calendar.
function getCalData(data, status, jqXHR) {
    for (var i = 0; i < data.length; i++) {
        var d = data[i] // d = [year, month, day, pm10, color]
        var dateTime = new Date(d[0], d[1], d[2]).getTime();
        dayData[dateTime] = [d[3], d[4]];
        evtData.push({
            startDate: dateTime,
            endDate: dateTime,
            pm10: d[3]
        });
    }
    var currentYear = new Date().getFullYear();
    var maxDay = new Date(currentYear, 11, 31);
    var minDay = new Date(currentYear - 3, 0, 1);
    $("#calendar").calendar({
        customDayRenderer: renderDay,
        mouseOnDay: tooltipShow,
        mouseOutDay: tooltipHide,
        dataSource: evtData,
        minDate: minDay,
        maxDate: maxDay,
        style: 'custom',
        language: 'it'
    });
}

function tooltipShow(e) {
    if (e.events.length > 0) {
        var content = '';
        for (var i in e.events) {
            content += Math.round(e.events[i].pm10);
        }

        $(e.element).popover({
            trigger: 'manual',
            container: 'body',
            html: true,
            content: content
        });
        $(e.element).popover('show');
    }
}

function tooltipHide(e) {
    if (e.events.length > 0) {
        $(e.element).popover('hide');
    }
}

function renderDay(element, date) {
    curDay = date.getTime();
    if (curDay in dayData) {
        $(element).css('background-color', '#' + dayData[curDay][1]);
        $(element).css('border', '1px solid gray');
        $(element).css('padding', '4px 5px');
        if (dayData[curDay][0] >= 50) {
            $(element).css('font-weight', 'bold');
            $(element).css('color', 'white');
        }
    }
}

// Make "%s" substitutions like sprintf().
function sprintfLight(format) {
    for(var i = 1; i < arguments.length; i++) {
        format = format.replace(/%s/, arguments[i]);
    }
    return format;
}

function onDownloadClick(event) {
    event.preventDefault();
    // Set hidden input from clicked button data-alias.
    var period = $(this).data('alias');
    $('#data-download input[name="period"]').val(period);
    $('#data-download').submit();
}
