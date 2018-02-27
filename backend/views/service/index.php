<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Услуги';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="service-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить услугу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(['id' => 'service-index-pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Приоритет',
                    'headerOptions' => ['width' => '80'],
                    'template' => '{up}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{down}',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'buttons' => [
                        'up' => function ($url, $model) use ($min_priority) {
                            return $model->priority != $min_priority ? Html::a('<span class="fa fa-sort-up"></span>', 'javascript:void(0)', ['onclick' => 'priorityUp(' . $model->id . ');']) : '&nbsp;&nbsp;';
                        },
                        'down' => function ($url, $model) use ($max_priority) {
                            return $model->priority != $max_priority ? Html::a('<span class="fa fa-sort-down"></span>', 'javascript:void(0)', ['onclick' => 'priorityDown(' . $model->id . ');']) : '&nbsp;&nbsp;';
                        }
                    ]
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['width' => '120'],
                    'template' => '{content}&nbsp;&nbsp;{formula}&nbsp;&nbsp;{view}&nbsp;{update}&nbsp;{delete}',
                    'buttons' => [
                        'content' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', $url, ['title' => 'Содержимое страницы']);
                        },
                        'formula' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-cog"></span>', $url, ['title' => 'Формула расчета стоимости']);
                        }
                    ]
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
