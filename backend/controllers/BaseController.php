<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\User;

class BaseController extends Controller
{
    public $publicActions = ['login'];
    
    public function beforeAction($action) {
        if (!Yii::$app->user->isGuest) return true;
        if (!in_array($action->id, $this->publicActions)) $this->redirect(Yii::$app->user->loginUrl);
        return true;
    }
}