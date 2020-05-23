<?php

namespace app\controllers;

use yii\filters\auth\HttpBearerAuth;
use app\models\Currency;
use yii\rest\ActiveController;

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

    public function actions()
    {
        $actions = parent::actions();

        // отключить действия "delete", "create" и "update"
        unset($actions['delete'], $actions['create'], $actions['update']);
        unset($actions['view']); // убиваем для последующего переопределения

        return $actions;
    }

    public function actionView($remoteID)
    {
        $data = Currency::find()->where(['remoteID' => $remoteID])->one();
        if ($data) {
            return $data;
        }
        throw new \yii\web\HttpException(400, 'Missing required parameters: remoteID');
    }

}