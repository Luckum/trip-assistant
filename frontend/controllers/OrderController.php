<?php

namespace frontend\controllers;

use Yii;
use frontend\controllers\BaseController;
use yii\web\Response;
use common\models\Order;
use common\models\Service;

class OrderController extends BaseController
{
    public function actionIndex()
    {
        if (Yii::$app->request->post('service_price') !== null && !empty(Yii::$app->request->post('service_price'))) {
            $service = Service::findOne(Yii::$app->request->post('service_id'));
            $service_price = Yii::$app->request->post('service_price');
            $error = "";
        } else {
            $service = "";
            $service_price = "";
            $error = "For order process, please select service and service options to get price.";
        }
        
        return $this->render('index', [
            'error' => $error,
            'service' => $service,
            'service_price' => $service_price,
        ]);
    }
    
    public function actionResponse()
    {
        if (!empty($_REQUEST)) {
            
            echo '<!—success—>';
            http_response_code(200);
        }
    }
    
    public function actionSet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->post('s_id') !== null) {
            $model = new Order;
            $model->service_id = Yii::$app->request->post('s_id');
            $model->total = Yii::$app->request->post('total');
            $model->user_email = Yii::$app->request->post('email');
            $model->user_name = Yii::$app->request->post('name');
            $model->user_phone = Yii::$app->request->post('phone');
            if ($model->save()) {
                return [
                    'success' => true,
                    'order_id' => $model->id
                ];
            }
        }
        return [
            'success' => false
        ];
    }
}