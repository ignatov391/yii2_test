<?php
/* @var $this yii\web\View */

$this->registerJsFile('//www.gstatic.com/charts/loader.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/currency.js', ['depends' => 'yii\web\JqueryAsset']);

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
                        <select class="form-control js-currency_select" name="currency_name">
                            <option value="">Все валюты</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputDate">Начальный период:</label>
                        <input type="date" name="currency_date_begin" class="form-control js-currency_date_begin" min="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputDate">Конечный период:</label>
                        <input type="date" name="currency_date_end" class="form-control js-currency_date_end" min="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>">
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
                <div class="currency_graphic" id="chart_div"></div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">

</script>