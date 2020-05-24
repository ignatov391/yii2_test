<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>
        <p class="lead">You have successfully created your Yii-powered application.</p>
    </div>

    <div class="body-content">

        <form name="currency_form">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-none"></label>
                        <select class="form-control" name="currency_name">
                            <option>выберите валюту</option>
                            <option value="currency1">currency1</option>
                            <option value="currency2">currency2</option>
                            <option value="currency3">currency3</option>
                            <option value="currency4">currency4</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputDate">Начальный период:</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputDate">Конечный период:</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="text-none"></label>
                    <button type="button" name="submit" class="btn btn-primary w-100">Показать</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-lg-12">
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

                <div class="currency_graphic" id="chart_div"></div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Year', 'USD', 'EURO'],
      ['2013',  1000,      400],
      ['2014',  1170,      460],
      ['2015',  660,       1120],
      ['2016',  1030,      540]
    ]);

    var options = {
      title: 'Company Performance',
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
</script>