<?php 

use yii\helpers\Html;
use yii\bootstrap\Alert;
use common\models\ServiceContentField;
use common\models\ServiceContentOption;

$this->title = 'Формула расчета стоимости услуги "' . $service->name . '"';
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$script = <<<JS
$(function () {
    getFormula();
})
JS;
$this->registerJs($script, $this::POS_END);
?>

<div id="message-formula" style="display: none;">
    <?= Alert::widget([
        'options' => [
            'class' => 'alert-success',
        ],
        'closeButton' => false,
        'body' => 'Изменения успешно сохранены',
    ]); ?>
</div>
<div class="service-price-content">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (isset($service->serviceContentFields)): ?>
        <input type="hidden" id="service-id-txt" value="<?= $service->id; ?>">
        <div class="fomula-elements" style="display: inline-block;">
            <p>Элементы на странице</p>
            <div class="btn-group">
                <?php foreach ($service->serviceContentFields as $field): ?>
                    <?php if ($field->field_type == ServiceContentField::TYPE_SELECT): ?>
                        <?php if (isset($field->serviceContentOptions)): ?>
                            <div class="btn-group">
                                <button type="button" data-field-btn="<?= $field->id; ?>" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?= $field->field_label; ?> <span class="caret"></span></button>
                                <ul class="dropdown-menu" data-options-ul="<?= $field->id; ?>" data-field="<?= $field->id; ?>" data-field-id="<?= $field->field_id; ?>" role="menu">
                                    <?php foreach ($field->serviceContentOptions as $option): ?>
                                        <li><a href="javascript:void(0)" data-option-id="<?= $option->option_id; ?>" class="formula-option"><?= $option->option_label; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <button type="button" data-field-id="<?= $field->field_id; ?>" class="btn btn-default formula"><?= $field->field_label; ?></button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="formula-signs" style="display: inline-block;">
            <p>Действия</p>
            <div class="btn-group">
                <button type="button" class="btn btn-default formula" data-field-id="sign" data-field-sign="plus">+</button>
                <button type="button" class="btn btn-default formula" data-field-id="sign" data-field-sign="minus">-</button>
                <button type="button" class="btn btn-default formula" data-field-id="sign" data-field-sign="multiply">*</button>
                <button type="button" class="btn btn-default formula" data-field-id="sign" data-field-sign="divide">/</button>
            </div>
        </div>
        <div class="formula-logic" style="display: inline-block;">
            <p>Логика</p>
            <div class="btn-group">
                <button type="button" class="btn btn-default formula" data-field-id="logic" data-field-sign="por">ИЛИ +</button>
                <button type="button" class="btn btn-default formula" data-field-id="logic" data-field-sign="xor">Исключающее ИЛИ</button>
            </div>
        </div>
        <div class="formula-value-container" style="display: inline-block;">
            <p>Значение</p>
            <div class="btn-group">
                <button type="button" class="btn btn-default formula-value">Ввод значения</button>
                <button type="button" class="btn btn-default formula-table">Связанные значения</button>
            </div>
        </div>
        <div class="formula-options" id="formula-options-container" style="margin-top: 20px; display: none;">
            <hr>
            <p>Значения для опций</p>
            <button type="button" data-field="" data-field-id="" class="btn btn-default option-multiply">Использовать как множитель</button>
            <button type="button" class="btn btn-default option-set-value">Установить значения</button>
            <button type="button" class="btn btn-danger btn-xs" id="formula-options-cancel-btn">Отмена</button>
            <div id="options-value" style="margin-top: 15px; display: none;"></div>
        </div>
        <div id="formula-value-container" style="margin-top: 20px; display: none;">
            <hr>
            <p>Ввод значения</p>
            <input type="text" id="formula-value-txt">
            <button type="button" class="btn btn-success btn-xs" id="formula-value-insert-btn">Вставить</button>
            <button type="button" class="btn btn-danger btn-xs" id="formula-value-cancel-btn">Отмена</button>
        </div>
        
        <div id="formula-table-check-container" style="margin-top: 20px; display: none;">
            <hr>
            <p>Поля для связи</p>
            <?php foreach ($service->serviceContentFields as $field): ?>
                <?php if ($field->field_type == ServiceContentField::TYPE_SELECT): ?>
                    <input type="checkbox" class="table-checker" data-options-checker="<?= $field->id; ?>">&nbsp;<?= $field->field_label; ?>&nbsp;&nbsp;&nbsp;
                <?php endif; ?>
            <?php endforeach; ?>
            <button type="button" class="btn btn-success btn-xs" id="formula-table-insert-btn">Ввести значения</button>
            <button type="button" class="btn btn-danger btn-xs" id="formula-table-cancel-btn">Отмена</button>
        </div>
        <div id="formula-table-container" style="margin-top: 20px; display: none;">
            <hr>
            <table id="formula-table" class="formula-table-tbl"></table>
            <button type="button" class="btn btn-success btn-xs" id="formula-table-container-insert-btn">Вставить</button>
            <button type="button" class="btn btn-danger btn-xs" id="formula-table-container-cancel-btn">Отмена</button>
        </div>
        <hr>
        <div class="formula-result" style="margin-top: 25px;">
            <p>Результат (Total)</p>
            <div id="formula-result"></div>
        </div>
        <hr>
        <div id="formula-update-options" style="display: none; margin-top: 25px;">
            <hr>
        </div>
        <div class="formula-save" style="margin-top: 30px;">
            <?= Html::a('Сохранить', 'javascript:void(0)', ['class' => 'btn btn-success', 'id' => 'formula-save']) ?>
            <?= Html::a('Редактировать значения', 'javascript:void(0)', ['class' => 'btn btn-success', 'id' => 'formula-update', 'style' => 'display: none;']) ?>
            <?= Html::a('Отмена', 'javascript:void(0)', ['class' => 'btn btn-danger', 'id' => 'formula-update-cancel', 'style' => 'display: none;']) ?>
        </div>
    <?php endif; ?>
</div>