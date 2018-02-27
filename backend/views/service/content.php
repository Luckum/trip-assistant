<?php 

use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = 'Содержимое страницы для услуги "' . $service->name . '"';
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/admin/js/formbuilder/dist/form-builder.min.js',  ['position' => $this::POS_END, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/admin/js/formbuilder/dist/form-render.min.js',  ['position' => $this::POS_END, 'depends' => [\yii\web\JqueryAsset::className()]]);
$default_fields = isset($service->serviceContent) ? json_decode($service->serviceContent->content_json) : '[]';

$script = <<<JS
$(function () {
    var service_content = $("#service-content-frm-bld").formBuilder({
        i18n: {
            locale: 'ru-RU'
        },
        disableFields: [
            'autocomplete',
            'file',
            'button',
            'checkbox-group',
            'header',
            'hidden',
            'number',
            'radio-group',
            'textarea',
        ],
        disabledActionButtons: [
            'data',
            'save',
        ],
        actionButtons: [{
            id: 'save-html',
            className: 'btn btn-success',
            label: 'Сохранить',
            type: 'button',
            events: {
                click: function() {
                    saveContent();
                }
            }
        }],
        defaultFields: $default_fields
    });
    
    function saveContent()
    {
        var renderedForm = $('<div>');
        var formData = service_content.actions.getData('json', true);
        renderedForm.formRender({formData});
        
        $.ajax({
            url: "/admin/service/set-content",
            type: "POST",
            data: {c_html: renderedForm.html(), c_json: formData, s_id: $('#service-id').val()},
            success: function(response) {
                $("#message-service-content").slideDown();
                setTimeout(function() { $("#message-service-content").slideUp(); }, 3000);
            }
        });
    }
})
JS;
$this->registerJs($script, $this::POS_END);
?>

<div id="message-service-content" style="display: none;">
    <?= Alert::widget([
        'options' => [
            'class' => 'alert-success',
        ],
        'closeButton' => false,
        'body' => 'Изменения успешно сохранены',
    ]); ?>
</div>
<div class="service-content">
    <h1><?= Html::encode($this->title) ?></h1>
    <br><br>
    
    <input type="hidden" id="service-id" value="<?= $service->id; ?>">
    <div id="service-content-frm-bld"></div>
</div>