<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */
/* @var $form yii\widgets\ActiveForm */

$script = <<<JS
    $(function () {
        if ($('#visible-chk').prop('checked')) {
            console.log('yes');
            $('#updated-container').show();
        } else {
            console.log('no');
        }
        
        $('#visible-chk').change(function() {
            if (this.checked) {
                $('#updated-container').show();
            } else {
                $('#updated-container').hide();
            }
        });
    });
JS;
$this->registerJs($script, $this::POS_END);
?>

<div class="comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'visibility')->checkbox(['id' => 'visible-chk']) ?>
    
    <div id="updated-container" style="display: none;">
        <?= $form->field($model, 'published_at')->widget(DatePicker::classname(), [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'autoclose' => true
            ]
        ]); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
