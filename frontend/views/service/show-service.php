<?php
use yii\helpers\Html;

$this->title = ucfirst($service->name);
?>
<?php if (isset($service->serviceContent)): ?>
    <input type="hidden" id="service-id-txt" value="<?= $service->id; ?>">
    <input type="hidden" id="formula-step" value="-1">
    <?= $service->serviceContent->content; ?>
    <label>Total:</label>
    <span id="service-total"></span>
    <form action="<?= Yii::$app->params['payment_multicard_url']; ?>" method="post">
        <input type="hidden" name="mer_id" value="<?= Yii::$app->params['payment_multicard_mer_id']; ?>" />
        <input type="hidden" name="mer_url_idx" value="01" />
        <input type="hidden" name="item1_desc" value="" />
        <input type="hidden" name="item1_price" id="service-total-price" value="" />
        <input type="hidden" name="item1_qty" maxlength="2" value="1" />
        <input type="hidden" name="user1" value="" />
        <input type="hidden" name="user2" value="" />
        <p class="text-center">
            <input type="submit" class="btn btn-success btn-lg text-center" value="Order Now">
        </p>
    </form>
        
<?php endif; ?>