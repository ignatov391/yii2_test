<?php

namespace app\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use app\models\Currency;
use yii\rest\ActiveController;

class CurrenciesController extends ActiveController
{
    public $modelClass = 'app\models\Currency';
//    public $enableSession = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $request = \Yii::$app->request;

        if (empty($request->get($request->csrfParam))) { // исключение для текущего сайта
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
//                'class' => HttpBearerAuth::className(),
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

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $data = Currency::find()
            ->with(['currencyHistories' => function ($query)
            {
                // $created_at = '2020-05-23';
                $request = \Yii::$app->request;
                $dateMin = $request->get('date-min');
                $dateMax = $request->get('date-max');
                $dateTime = new \DateTime();
                if ($dateMin) {
                    $createdMin = $dateTime->setTimestamp(strtotime($dateMin))->format('Y-m-d H:i:s');
                    $query->andWhere(['>=', 'created_at', $createdMin]);
                }
                if ($dateMax) {
                    $createdMax = $dateTime->setTimestamp(strtotime($dateMax)+86400)->format('Y-m-d H:i:s');
                    $query->andWhere(['<=', 'created_at', $createdMax]);
                }
            },
            ])
            ->all();

        if ($data) {
            return $data;
        }
    }

    public function actionView($remoteID)
    {
        $data = Currency::find()
            ->where(['currency.remoteID' => $remoteID])
            ->with(['currencyHistories' => function ($query)
                {
                    // $created_at = '2020-05-23';
                    $request = \Yii::$app->request;
                    $dateMin = $request->get('date-min');
                    $dateMax = $request->get('date-max');
                    $dateTime = new \DateTime();
                    if ($dateMin) {
                        $createdMin = $dateTime->setTimestamp(strtotime($dateMin))->format('Y-m-d H:i:s');
                        $query->andWhere(['>=', 'created_at', $createdMin]);
                    }
                    if ($dateMax) {
                        $createdMax = $dateTime->setTimestamp(strtotime($dateMax)+86400)->format('Y-m-d H:i:s');
                        $query->andWhere(['<=', 'created_at', $createdMax]);
                    }
                },
            ])
            ->one();

        if ($data) {
            return $data;
        }
        throw new \yii\web\HttpException(400, 'Missing required parameters: remoteID');
    }

}