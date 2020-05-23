<?php

namespace app\controllers;

use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use app\models\Currency;
use app\models\CurrencyHistory;
use yii\rest\ActiveController;
use \yii\web\ForbiddenHttpException;

class CurrenciesController extends ActiveController
{
    public $modelClass = 'app\models\Currency';
//    public $enableSession = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (false) { // проверка заголовка на текущий сайт
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::className(),
            ];
        }
        return $behaviors;
    }

    public function actionView($remoteID)
    {
        return Currency::find()->where(['remoteID' => $remoteID])->one();
    }

    public function actions()
    {
        $actions = parent::actions();

        // отключить действия "delete", "create" и "update"
        unset($actions['delete'], $actions['create'], $actions['update']);

        // настроить подготовку провайдера данных с помощью метода "prepareDataProvider()"
        // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

}