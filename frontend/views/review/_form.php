<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Review */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="review-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'message')->textarea(['rows' => 6, 'style' => 'resize: none; width: 90%;'])->label('Message') ?>
        
        <?= $form->field($model, 'author')->textInput(['maxlength' => true, 'style' => 'width: 90%;'])->label('Your Name') ?>

        <p>Your message will be published after admin moderation.</p>
        
        <div class="form-group">
            <?= Html::submitButton('Send', ['class' => 'btn btn-success btn-lg']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
