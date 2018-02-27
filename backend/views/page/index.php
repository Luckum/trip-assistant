<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Статические страницы';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="page-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('Добавить страницу', ['create'], [
            'id' => 'add-page-btn',
            'class' => 'btn btn-success',
        ]); ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'visibility',
            'slug',
            'title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>

