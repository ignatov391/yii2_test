$(document).ready(function(){
    var paramCsrf = $('meta[name=csrf-param]').attr("content");
    var tokenCsrf = $('meta[name=csrf-token]').attr("content");
    google.charts.load('current', {'packages':['corechart']});

    params = {
        expand: 'currencyHistories'
    }
    params[paramCsrf] = tokenCsrf;
    $.ajax({
        url: '/currencies',
        type: "GET",
        dataType: 'json',
        data: params,
        success: function(data) {
            dateBegin = parseInt(new Date().getTime());
            // From init
            $.each(data, function(i, item) {
                //console.log(data[i].name + ' ' + data[i].remoteID);
                $('.js-currency_select').append('<option value="' + data[i].remoteID + '">' + data[i].name + '</option>');
                created_at = new Date(item.created_at.split(".").reverse().join(".")).getTime();
                if (dateBegin > created_at) {
                    dateBegin = created_at;
                }
            });
            dateBegin = formatFate(dateBegin);
            $('.js-currency_date_begin').attr('min', dateBegin);
            $('.js-currency_date_end').attr('min', dateBegin);

            updateCharts(data);
        }
    });

    $('.js-currency_select, .js-currency_date_begin, .js-currency_date_end').on('change', function() {
        charCode = $('.js-currency_select').val();
        dateBegin = $('.js-currency_date_begin').val();
        dateEnd = $('.js-currency_date_end').val();
        // console.log(charCode+' '+dateBegin+' '+dateEnd + '' + charCode.length);
        if (charCode.length > 0) {
            ajaxChartsOne(charCode,dateBegin,dateEnd);
        } else {
            ajaxChartsAll(dateBegin,dateEnd);
        }
    });
});
var dataChart = [];

function ajaxChartsAll(dateBegin,dateEnd) {
    params = {
        'date-min': dateBegin,
        'date-max': dateEnd,
        expand: 'currencyHistories'
    }
    params[paramCsrf] = tokenCsrf;
    $.ajax({
        url: '/currencies',
        type: "GET",
        dataType: 'json',
        data: params,
        success: function(data) {
            updateCharts(data);
        }
    });
}

function ajaxChartsOne(charCode,dateBegin,dateEnd) {
    params = {
        'access-token': '099-token',
        'date-min': dateBegin,
        'date-max': dateEnd,
        expand: 'currencyHistories'
    };
    $.ajax({
        url: '/currencies/'+charCode,
        type: "GET",
        dataType: 'json',
        data: params,
        success: function(data) {
            updateCharts(data);
        }
    });
}

function updateCharts(data) {
    if (typeof data == 'undefined') {
        console.log('Error data!');
        return false;
    }
    // console.log(data);
    dataChart = [];
    dataChart[0] = ['Date'];

    if (typeof data.remoteID != "undefined") {
        /*
            item = data;
            i = 0;
        */
        dataChart[0][1] = data.charCode;
        $.each(data.currencyHistories, function(j, history) {
            /* todo Только для теста! */
            // timeKey = Math.trunc(new Date(history.created_at.split(".").reverse().join(".")).getTime() / 1);//10000);
            timeKey = Math.trunc(new Date(history.created_at.split(".").reverse().join(".")).getTime() / 1000000);
            if (!dataChart[timeKey]) {
                dataChart[timeKey]  =[];
                dataChart[timeKey][0] = history.created_at;
            }
            dataChart[timeKey][1] = history.value;
        });
    } else {
        $.each(data, function(i, item) {
            dataChart[0][i+1] = item.charCode;
            $.each(item.currencyHistories, function(j, history) {
                /* todo Только для теста! */
                // timeKey = Math.trunc(new Date(history.created_at.split(".").reverse().join(".")).getTime() / 1);//10000);
                timeKey = Math.trunc(new Date(history.created_at.split(".").reverse().join(".")).getTime() / 1000000);
                if (!dataChart[timeKey]) {
                    dataChart[timeKey]  =[];
                    dataChart[timeKey][0] = history.created_at;
                }
                dataChart[timeKey][i+1] = history.value;
            });
        });
    }

    mapChars = Object.keys(dataChart).sort();
    mapChars = mapChars.sort();
    lengthChars = dataChart[0].length;
    dataChartTmp = [];
    if (lengthChars > 0) {
        for (index = 0; index < mapChars.length; ++index) {
            item = mapChars[index];
            dataChartTmp[index] = dataChart[item];
            if (index > 0) {
                for (i = 1; i < lengthChars; i++) {
                    if (!dataChartTmp[index][i]) {
                        if (index > 1) {
                            for (k = index-1; k > 1 && dataChartTmp[k][i] <= 0; --k) {
                                // console.log(dataChartTmp[k][i]+'= dataChartTmp[index][k]'+k+' '+i);
                            }
                            if (dataChartTmp[k][i]) {
                                dataChartTmp[index][i] = dataChartTmp[k][i];
                            } else {
                                 dataChartTmp[index][i] = 0;
                            }
                        } else {
                            dataChartTmp[index][i] = 0;
                        }
                    }
                }
            }
        }
        dataChart = dataChartTmp;
        initGoogleCharts();
    }
    if (typeof dataChart[1] != 'undefined') {
        $('.js-currency_graphic').show();
        $('.js-currency_graphic_message').hide();
    } else {
        $('.js-currency_graphic').hide();
        $('.js-currency_graphic_message').show();
    }
    // console.log(dataChart);
}


function formatFate(timestamp) {
    var d = new Date(timestamp);
    var curr_date = d.getDate();
    var curr_month = d.getMonth() + 1;
    var curr_year = d.getFullYear();
    if (curr_date < 10) {
        curr_date = '0'+curr_date;
    }
    if (curr_month < 10) {
        curr_month = '0'+curr_month;
    }
    return curr_year+"-"+curr_month+"-"+curr_date;
}

function initGoogleCharts() {
    google.charts.setOnLoadCallback(drawChart);
}

function drawChart() {
    var data = google.visualization.arrayToDataTable(dataChart);
    var options = {
        //title: 'Company Performance',
        //hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
        vAxis: {minValue: 0},

        // Allow multiple
        // simultaneous selections.
        selectionMode: 'multiple',
        // Trigger tooltips
        // on selections.
        tooltip: {trigger: 'selection'},
        // Group selections
        // by x-value.
        aggregationTarget: 'category',
    };
    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}