<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reviews';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Leave a Review', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php if ($reviews): ?>
        <?php foreach ($reviews as $review): ?>
            <div>
                <strong><?= date('j F Y', strtotime($review->published_at)); ?></strong>
                <div>
                    <?= $review->message; ?>
                </div>
                <strong><?= $review->author; ?></strong>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
</div>
