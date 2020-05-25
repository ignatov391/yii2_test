$(document).ready(function(){
    var param = $('meta[name=csrf-param]').attr("content");
    var token = $('meta[name=csrf-token]').attr("content");
    google.charts.load('current', {'packages':['corechart']});

    $.ajax({
        url: '/currencies/',
        type: "GET",
        dataType: 'json',
        data: {
        'access-token': '099-token',
        expand:'currencyHistories'
        },
        success: function(data) {
            console.log(data);
            dateBegin = parseInt(new Date().getTime());
            dataChart = [];
            dataChart[0] = ['Date'];
            $.each(data, function(i, item) {
                //console.log(data[i].name + ' ' + data[i].remoteID);
                $('.js-currency_select').append('<option value="' + data[i].remoteID + '">' + data[i].name + '</option>');
                created_at = new Date(item.created_at.split(".").reverse().join(".")).getTime();
                if (dateBegin > created_at) {
                    dateBegin = created_at;
                }
                dataChart[0][i+1] = item.charCode;
                $.each(item.currencyHistories, function(j, history) {
                    timestamp = new Date(history.created_at.split(".").reverse().join(".")).getTime() / 1000;
                    if (!dataChart[timestamp]) {
                        dataChart[timestamp]  =[];
                    }
                    dataChart[timestamp][0] = history.created_at;
                    dataChart[timestamp][i+1] = history.value;
                });
            });
            dateBegin = formatFate(dateBegin);
            $('.js-currency_date_begin').attr('min', dateBegin);
            $('.js-currency_date_end').attr('min', dateBegin);

            mapChars = Object.keys(dataChart);
            lengthChars = dataChart[0].length;
            dataChartTmp = [];
            console.log(dataChart);
            if (lengthChars > 0) {
                $.each(Object.keys(dataChart), function(index, item) {
                    /* todo FIX ME! неверный перебор. */
                    if (index > 0) {
                        for (i = 1; i < lengthChars; i++) {
                            if (!dataChart[item][i]) {
//                                if (i > 1) {
//                                    for (j = i-1; j > 1 && dataChart[item][j]>0; --j) {
//                                    }
//                                    if (dataChart[item][j]) {
//                                        dataChart[item][i] = dataChart[item][j];
//                                        // console.log(j +' '+ i + ' ' + index);
//                                    } else {
//                                        console.log(j +' '+ i + ' ' + index);
//                                        // dataChart[item][i] = 0;
//                                    }
//                                } else {
                                    dataChart[item][i] = 0;
//                                }
                            }
                        }
                    }
                    dataChartTmp[index] = dataChart[item];
                });
            }
            dataChart = dataChartTmp;
            console.log(dataChart);
            initGoogleCharts();
        }
    });
});

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

var dataChart = [];

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