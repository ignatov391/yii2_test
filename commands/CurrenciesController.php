<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\XmlParser;
use app\models\Currency;
use app\models\CurrencyHistory;

//from crontab
//# m h  dom mon dow   command
//*/10 * * * * /app/yii currencies/index
class CurrenciesController extends Controller
{
    private static $urlData = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @return int
     */
    public function actionIndex()
    {
        $string = file_get_contents(self::$urlData);
        $arData = simplexml_load_string($string);
        if (!empty($arData->Valute) && count($arData->Valute) > 0) {
            foreach ($arData->Valute as $item) {
                $item = (array)$item;
                $curValue = (float) str_replace(',', '.', $item['Value']);
                $remoteID = $item['@attributes']['ID'];
                $model = Currency::find()->where(['charCode' => $item['CharCode']])->one();
                if (!($model) || $model->lastValue != $curValue) {
                    if (!$model) {
                        $model = new Currency();
                        $model->isNewRecord = true;
                        $model->created_at = date("Y-m-d H:i:s");
                    }
                    $model->name = $item['Name'];
                    $model->numCode = $item['NumCode'];
                    $model->charCode = $item['CharCode'];
                    $model->nominal = $item['Nominal'];
                    $model->lastValue = $curValue;
                    $model->updated_at = date("Y-m-d H:i:s");
                    $model->remoteID = $remoteID;
                    // $model->validate();
                    // var_dump($model->getErrors());
                    $status = $model->save();
                    if ($model->id) {
                        $currencyID  = $model->id;
                        $modelHistory = new CurrencyHistory();
                        $modelHistory->isNewRecord = true;
                        $modelHistory->currency_id = $currencyID;
                        $modelHistory->remoteID = $remoteID;
                        $modelHistory->nominal = $item['Nominal'];
                        $modelHistory->value = $curValue;
                        $modelHistory->created_at = date("Y-m-d H:i:s");
                        // $modelHistory->validate();
                        // var_dump($modelHistory->getErrors());
                        $statusHistory = $modelHistory->save();
                    }
                    print_r([
                        'status save' => $status,
                        'status history save' => $statusHistory ?? null,
                        'ID' => $currencyID ?? null,
                    ]);
                }
            }
            return ExitCode::OK;
        }
        return ExitCode::DATAERR;
    }
}