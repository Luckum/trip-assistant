<?php

use yii\helpers\Html;

$this->title = 'Order Process';

$script = <<<JS
$(function () {
    $("#submit-order").click(function() {
        $.ajax({
            url: "/order/set",
            type: "POST",
            data: {
                s_id:  $("#service-id").val(),
                total: $("#user-total").val(),
                email: $("#user-email").val(),
                name:  $("#user-name").val(),
                phone: $("#user-phone").val()
            },
            success: function(data) {
                if (data && data.success) {
                    $("#user1-hdn").val(data.order_id);
                    $("#order-frm").submit();
                } else {
                    alert("Incorrect input data");
                }
            }
        });
    });
})
JS;
$this->registerJs($script, $this::POS_END);

?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!empty($error)): ?>
        <p><?= $error; ?></p>
    <?php else: ?>
        <p>Please fill out the following fields to order process:</p>
        <div class="form-group">
            <?= Html::label('Your Email:', 'user-email', ['class' => 'control-label']) ?>
            <?= Html::input('text', 'email', '', ['id' => 'user-email', 'class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <?= Html::label('Your Name:', 'user-name', ['class' => 'control-label']) ?>
            <?= Html::input('text', 'name', '', ['id' => 'user-name', 'class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <?= Html::label('Your Phone Number:', 'user-phone', ['class' => 'control-label']) ?>
            <?= Html::input('text', 'phone', '', ['id' => 'user-phone', 'class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <?= Html::label('Payment Sum: $' . $service_price, 'user-total', ['class' => 'control-label']) ?>
            <?= Html::input('hidden', 'pay_total', $service_price, ['id' => 'user-total']) ?>
            <?= Html::input('hidden', 'service_id', $service->id, ['id' => 'service-id']) ?>
        </div>
        <form action="<?= Yii::$app->params['payment_multicard_url']; ?>" id="order-frm">
            <?= Html::input('hidden', 'mer_id', Yii::$app->params['payment_multicard_mer_id']) ?>
            <?= Html::input('hidden', 'mer_url_idx', '2') ?>
            <?= Html::input('hidden', 'item1_desc', $service->name . ' Service') ?>
            <?= Html::input('hidden', 'item1_price', $service_price) ?>
            <?= Html::input('hidden', 'item1_qty', '1') ?>
            <?= Html::input('hidden', 'user1', '', ['id' => 'user1-hdn']) ?>
            <?= Html::input('button', 'submit_order', 'Process Order', ['class' => 'btn btn-success btn-lg', 'id' => 'submit-order']) ?>
        </form>
    <?php endif; ?>
</div>
