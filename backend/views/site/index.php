<?php

use yii\helpers\Url;

$this->title = Yii::$app->params['siteName'] . ' | ' . 'Админ панель';
?>
<div class="site-index">
    <h4>Отзывов, требующих модерации: <?= $reviews_cnt; ?>. <a href="<?= Url::to(['/review']) ?>">Перейти к отзывам</a></h4>
</div>
