<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = ucfirst($service->name);

$script = <<<JS
$(function () {
    $.ajax({
        url: "/service/get-formula",
        type: "POST",
        data: {s_id: $("#service-id-txt").val()},
        success: function(data) {
            if (data.formula) {
                var fields = JSON.parse(data.formula);
                $.each(fields, function() {
                    if (typeof(this.field_id) !== 'undefined') {
                        if ($.type(this.field_id) == 'object') {
                            $("#" + this.field_id.field_id).attr('onchange', 'getTotal()');
                        } else {
                            $("#" + this.field_id).attr('onchange', 'getTotal()');
                        }
                    } else if (typeof(this.related) !== 'undefined') {
                        $("#" + this.related.field_1).attr('onchange', 'getTotal()');
                        $("#" + this.related.field_2).attr('onchange', 'getTotal()');
                    }
                });
                if (fields.length == 1 && typeof(fields[0].value) !== 'undefined') {
                    var total = fields[0].value;
                    $("#service-total").html("$" + total);
                    $("#service-total-price").val(total);
                }
            }
        }
    });
})
JS;
$this->registerJs($script, $this::POS_END);

?>
<?php if (isset($service->serviceContent)): ?>
    <input type="hidden" id="service-id-txt" value="<?= $service->id; ?>">
    <input type="hidden" id="formula-step" value="-1">
    <?= $service->serviceContent->content; ?>
    <label>Total:</label>
    <span id="service-total"></span>
    
    <?php $form = ActiveForm::begin(['action' => ['/order']]); ?>
    
        <input type="hidden" name="service_price" id="service-total-price" value="" />
        <input type="hidden" name="service_id" value="<?= $service->id ?>" />
        <p class="text-center">
            <input type="submit" class="btn btn-success btn-lg text-center" value="Order Now">
        </p>
    <?php ActiveForm::end(); ?>
        
<?php endif; ?>