<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use backend\assets\AppAsset;
use yii\bootstrap\ActiveForm;
$this->title = Yii::$app->params['siteName'];

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container">
    <div class="container login-container">
        <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel white-alpha-90">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <h2>Вход в <span class="text-primary"><?= Yii::$app->params['siteName']; ?></span> admin</h2>
                    </div>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'options' => ['class' => 'form-horizontal']
                    ]); ?>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <input id="loginform-login" class="form-control" name="LoginForm[username]" value="" placeholder="Логин" type="text" autofocus>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input id="loginform-password" class="form-control" name="LoginForm[password]" placeholder="Пароль" type="password">
                        </div>
                        <div class="input-group col-xs-12 text-center login-action">
                            <div class="checkbox">
                                <label>
                                    <input id="loginform-rememberme" name="LoginForm[rememberMe]" value="1" style="margin-top: 10px;" type="checkbox">&nbsp;Запомнить&nbsp;
                                    <span id="btn-login">
                                        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <?= $form->errorSummary($model); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>