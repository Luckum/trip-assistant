<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use common\models\Service;
use common\models\ServiceContent;
use common\models\ServiceContentPrice;

/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ServiceController extends BaseController
{
    public function actionShowService($slug)
    {
        $service = Service::find()->where(['slug' => $slug])->one();
        
        return $this->render('show-service', [
            'service' => $service,
        ]);
    }
    
    public function actionGetFormula()
    {
        $service_id = $_POST['s_id'];
        $model = ServiceContentPrice::find()->where(['service_id' => $service_id])->one();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model) {
            return [
                'formula' => $model->formula,
            ];
        }
        return false;
    }
}