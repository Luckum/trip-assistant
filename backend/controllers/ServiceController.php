<?php

namespace backend\controllers;

use Yii;
use common\models\Service;
use common\models\ServiceContent;
use common\models\ServiceContentField;
use common\models\ServiceContentOption;
use common\models\ServiceContentPrice;
use yii\data\ActiveDataProvider;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class ServiceController extends BaseController
{
    /**
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Service::find()->orderBy('priority ASC'),
        ]);
        
        $max_priority = Service::find()->max('priority');
        $min_priority = Service::find()->min('priority');
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'max_priority' => $max_priority,
            'min_priority' => $min_priority,
        ]);
    }

    /**
     * Displays a single Service model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Service model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Service();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Service model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUp()
    {
        $service_id = $_POST['id'];
        $service = Service::findOne($service_id);
        $min_priority = Service::find()->min('priority');
        if ($service->priority > $min_priority) {
            $service_instead = Service::getPriorityLess($service->priority);
            Service::updateAll(['priority' => $service_instead->priority], 'id = ' . $service->id);
            Service::updateAll(['priority' => $service->priority], 'id = ' . $service_instead->id);
        }
        return true;
    }
    
    public function actionDown()
    {
        $service_id = $_POST['id'];
        $service = Service::findOne($service_id);
        $max_priority = Service::find()->max('priority');
        if ($service->priority < $max_priority) {
            $service_instead = Service::getPriorityGreater($service->priority);
            Service::updateAll(['priority' => $service_instead->priority], 'id = ' . $service->id);
            Service::updateAll(['priority' => $service->priority], 'id = ' . $service_instead->id);
        }
        return true;
    }
    
    public function actionContent($id)
    {
        $service = Service::findOne($id);
        return $this->render('content', [
            'service' => $service
        ]);
    }
    
    public function actionFormula($id)
    {
        $service = Service::findOne($id);
        
        return $this->render('formula', [
            'service' => $service,
        ]);
    }
    
    public function actionSetContent()
    {
        $content_html = $_POST['c_html'];
        $content_json = $_POST['c_json'];
        $service_id = $_POST['s_id'];
        
        $content_model = ServiceContent::find()->where(['service_id' => $service_id])->one();
        if (!$content_model) {
            $content_model = new ServiceContent;
            $content_model->service_id = $service_id;
        }
        $content_model->content = $content_html;
        $content_model->content_json = json_encode($content_json);
        if ($content_model->save()) {
            $decoded = json_decode($content_json);
            if ($decoded) {
                $field_model = ServiceContentField::find()->where(['service_id' => $service_id])->all();
                if ($field_model) {
                    foreach ($field_model as $field) {
                        $field->delete();
                    }
                }
                $option_model = ServiceContentOption::find()->where(['service_id' => $service_id])->all();
                if ($option_model) {
                    foreach ($option_model as $option) {
                        $option->delete();
                    }
                }
                foreach ($decoded as $val) {
                    if ($val->type == ServiceContentField::TYPE_TEXT) {
                        $field_model = new ServiceContentField;
                        $field_model->service_id = $service_id;
                        $field_model->field_type = ServiceContentField::TYPE_TEXT;
                        $field_model->field_id = $val->name;
                        $field_model->field_label = strip_tags($val->label);
                        $field_model->save();
                    }
                    if ($val->type == ServiceContentField::TYPE_DATE) {
                        $field_model = new ServiceContentField;
                        $field_model->service_id = $service_id;
                        $field_model->field_type = ServiceContentField::TYPE_DATE;
                        $field_model->field_id = $val->name;
                        $field_model->field_label = strip_tags($val->label);
                        $field_model->save();
                    }
                    if ($val->type == ServiceContentField::TYPE_SELECT) {
                        $field_model = new ServiceContentField;
                        $field_model->service_id = $service_id;
                        $field_model->field_type = ServiceContentField::TYPE_SELECT;
                        $field_model->field_id = $val->name;
                        $field_model->field_label = strip_tags($val->label);
                        $field_model->save();
                        
                        foreach ($val->values as $k => $rec) {
                            $option_model = new ServiceContentOption;
                            $option_model->service_id = $service_id;
                            $option_model->field_id = $field_model->id;
                            $option_model->option_value = $rec->value;
                            $option_model->option_id = $val->name . "-" . $k;
                            $option_model->option_label = $rec->label;
                            $option_model->save();
                        }
                        
                    }
                }
            }
        }
    }
    
    public function actionSetFormula()
    {
        $formula = json_encode($_POST['formula']);
        $service_id = $_POST['s_id'];
        $model = ServiceContentPrice::find()->where(['service_id' => $service_id])->one();
        if (!$model) {
            $model = new ServiceContentPrice;
            $model->service_id = $service_id;
        }
        $model->formula = $formula;
        $model->save();
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
    
    public function actionGetFieldLabel()
    {
        $field_id = $_POST['f_id'];
        $model = ServiceContentField::find()->where(['field_id' => $field_id])->one();
        if ($model) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'label' => $model->field_label,
            ];
        }
    }
    
    public function actionGetOptionLabel()
    {
        $option_id = $_POST['o_id'];
        $model = ServiceContentOption::find()->where(['option_id' => $option_id])->one();
        if ($model) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'label' => $model->option_label,
            ];
        }
    }
}
