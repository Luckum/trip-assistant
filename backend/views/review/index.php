<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить отзыв', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'author',
            //'message:ntext',
            'created_at',
            [
                'attribute' => 'visibility',
                'content' => function ($data) {
                    return $data->visibility == 1 ? 'Да' : 'Нет';
                }
            ],
            
            'published_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
